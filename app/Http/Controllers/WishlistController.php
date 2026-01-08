<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Toggle wishlist (add/remove)
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'form_id' => 'required|exists:forms,id'
        ]);

        $user = Auth::user();
        $formId = $request->form_id;

        // Check if already in wishlist
        $existing = Wishlist::where('user_id', $user->id)
                           ->where('form_id', $formId)
                           ->first();

        if ($existing) {
            // Remove from wishlist
            $existing->delete();
            return response()->json([
                'success' => true,
                'action' => 'removed',
                'message' => 'Removed from wishlist'
            ]);
        } else {
            // Add to wishlist
            Wishlist::create([
                'user_id' => $user->id,
                'form_id' => $formId
            ]);
            return response()->json([
                'success' => true,
                'action' => 'added',
                'message' => 'Added to wishlist'
            ]);
        }
    }

    /**
     * Get user's wishlist
     */
    public function index()
    {
        $wishlists = Wishlist::with('form')
                            ->where('user_id', Auth::id())
                            ->latest()
                            ->get();

        return view('wishlist', compact('wishlists'));
    }

    /**
     * Check if form is in wishlist (for frontend)
     */
    public function check($formId)
    {
        $inWishlist = Wishlist::where('user_id', Auth::id())
                             ->where('form_id', $formId)
                             ->exists();

        return response()->json(['inWishlist' => $inWishlist]);
    }
}
