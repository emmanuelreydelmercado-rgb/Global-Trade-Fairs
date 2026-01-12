<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth page
     */
    public function redirectToGoogle()
    {
        // Disable SSL verification for local development only
        if (config('app.env') === 'local') {
            config(['services.google.guzzle' => ['verify' => false]]);
        }
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Disable SSL verification for local development only
            if (config('app.env') === 'local') {
                config(['services.google.guzzle' => ['verify' => false]]);
            }
            
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // Update Google ID if not set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->getId();
                    $user->save();
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(32)), // Random password for Google users
                    'profilepic' => 'default.jpg',
                ]);

                // Optionally download and save the Google profile picture
                if ($googleUser->getAvatar()) {
                    try {
                        $avatarContents = file_get_contents($googleUser->getAvatar());
                        $avatarName = 'google_' . $user->id . '_' . time() . '.jpg';
                        $path = public_path('profilepics/' . $avatarName);
                        file_put_contents($path, $avatarContents);
                        
                        $user->profilepic = $avatarName;
                        $user->save();
                    } catch (Exception $e) {
                        // If avatar download fails, just use default
                    }
                }
            }
            
            // Log the user in
            Auth::login($user, true);
            
            return redirect()->route('home')->with('success', 'Successfully logged in with Google!');
            
        } catch (Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Failed to login with Google: ' . $e->getMessage());
        }
    }
}
