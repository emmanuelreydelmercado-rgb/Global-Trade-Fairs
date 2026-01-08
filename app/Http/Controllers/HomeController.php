<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Base query
        $query = Form::where('status', 'approved');

        // 🔍 Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('ExponName', 'like', '%'.$request->search.'%')
                  ->orWhere('VenueName', 'like', '%'.$request->search.'%')
                  ->orWhere('Orgname', 'like', '%'.$request->search.'%');
            });
        }

        // 🔴 Live Filter
        if ($request->filter == 'live') {
            $query->whereDate('Date', \Carbon\Carbon::today());
        }

        // 🔃 Sorting
        if ($request->sort === 'date') {
            $query->orderBy('Date', 'asc');
        } elseif ($request->sort === 'city') {
            $query->orderBy('VenueName', 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        // ✅ THIS LINE GOES HERE (IMPORTANT)
        $forms = $query->paginate(12);

        // 🚀 CACHE: Map statistics (cached for 30 minutes)
        $mapStats = \Cache::remember('map-analytics-data', 1800, function() {
            // 📊 Calculate map statistics
            $allApprovedEvents = Form::where('status', 'approved')->get();
            
            // Group events by country and count
            $countryEventCounts = $allApprovedEvents->groupBy('country')->map(function ($events) {
                return $events->count();
            })->filter(function ($count, $country) {
                return !empty($country); // Only include events with a country set
            });

            // 🚀 CACHE: Load country codes mapping (cached for 24 hours)
            $countriesData = \Cache::remember('countries-cities-data', 86400, function() {
                $jsonPath = public_path('data/countries-cities.json');
                return json_decode(file_get_contents($jsonPath), true);
            });
            
            $countryCodeMap = [];
            foreach ($countriesData['countries'] as $country) {
                $countryCodeMap[$country['name']] = $country['code'];
            }

            // Format data for map visualization
            $mapData = [];
            foreach ($countryEventCounts as $countryName => $eventCount) {
                if (isset($countryCodeMap[$countryName])) {
                    $mapData[] = [
                        'id' => $countryCodeMap[$countryName],
                        'name' => $countryName,
                        'value' => $eventCount
                    ];
                }
            }

            // Return all map statistics
            return [
                'mapData' => $mapData,
                'totalEvents' => $allApprovedEvents->count(),
                'totalCountries' => $countryEventCounts->count(),
                'avgPerCountry' => $countryEventCounts->count() > 0 
                    ? round($allApprovedEvents->count() / $countryEventCounts->count()) 
                    : 0
            ];
        });

        // Extract values from cached data
        $mapData = $mapStats['mapData'];
        $totalEvents = $mapStats['totalEvents'];
        $totalCountries = $mapStats['totalCountries'];
        $avgPerCountry = $mapStats['avgPerCountry'];

        // Get wishlist form IDs for current user (if authenticated)
        $wishlistFormIds = [];
        if (Auth::check()) {
            $wishlistFormIds = Wishlist::where('user_id', Auth::id())
                ->pluck('form_id')
                ->toArray();
        }

        // Return view
        return view('global-fairs', compact('forms', 'mapData', 'totalEvents', 'totalCountries', 'avgPerCountry', 'wishlistFormIds'));
    }
}
