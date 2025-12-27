<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;

class InputBoxController extends Controller
{
    public function viewform()
    {
        return view('event-form');
    }

    public function addtable(Request $request)
    {
        // Handle image upload
        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $filename);
        } else {
            $filename = null;
        }

        // Save data
        Form::create([
            'Orgname'    => $request->Orgname,
            'VenueName' => $request->VenueName,
            'city'      => $request->city,
            'country'   => $request->country,
            'Date'      => $request->Date,
            'ExponName' => $request->ExponName,
            'image'     => $filename,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'hallno'    => $request->hallno,
            'reglink'   => $request->reglink,
            'status'    => 'pending'
        ]);

        return back()->with('message', 'Form submitted successfully!');
    }

    public function getCities($countryName)
    {
        // 🚀 CACHE: Load countries-cities data (cached for 24 hours)
        $data = \Cache::remember('countries-cities-data', 86400, function() {
            $jsonPath = public_path('data/countries-cities.json');
            
            if (!file_exists($jsonPath)) {
                return null;
            }
            
            return json_decode(file_get_contents($jsonPath), true);
        });
        
        // Handle file not found
        if (!$data) {
            return response()->json(['error' => 'Data file not found'], 404);
        }
        
        // Find the country and return its cities
        foreach ($data['countries'] as $country) {
            if ($country['name'] === $countryName) {
                return response()->json(['cities' => $country['cities']]);
            }
        }

        return response()->json(['cities' => []]);
    }

    public function adminDashboard()
    {
        $forms = Form::where('status','pending')
                     ->orderBy('id','desc')
                     ->get();

        return view('dashboard', compact('forms'));
    }
}
