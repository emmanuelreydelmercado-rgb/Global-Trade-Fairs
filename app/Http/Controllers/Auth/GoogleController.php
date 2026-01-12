<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth page for LOGIN
     */
    public function redirectToGoogleLogin()
    {
        // Disable SSL verification for local development only
        if (config('app.env') === 'local') {
            config(['services.google.guzzle' => ['verify' => false]]);
        }
        
        // Store intent in session to differentiate login vs register
        session(['google_intent' => 'login']);
        
        return Socialite::driver('google')->redirect();
    }

    /**
     * Redirect to Google OAuth page for REGISTRATION
     */
    public function redirectToGoogleRegister()
    {
        // Disable SSL verification for local development only
        if (config('app.env') === 'local') {
            config(['services.google.guzzle' => ['verify' => false]]);
        }
        
        // Store intent in session to differentiate login vs register
        session(['google_intent' => 'register']);
        
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
            $intent = session('google_intent', 'login'); // Default to login if not set
            
            // Check if user already exists
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($intent === 'login') {
                // LOGIN FLOW: Only allow existing, verified users
                return $this->handleGoogleLogin($googleUser, $user);
            } else {
                // REGISTRATION FLOW: Create new user and require email verification
                return $this->handleGoogleRegistration($googleUser, $user);
            }
            
        } catch (Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            $route = session('google_intent') === 'register' ? 'register' : 'login';
            return redirect()->route($route)->with('error', 'Failed to authenticate with Google. Please try again.');
        } finally {
            // Clear the intent from session
            session()->forget('google_intent');
        }
    }

    /**
     * Handle Google Login - Only existing verified users
     */
    private function handleGoogleLogin($googleUser, $user)
    {
        // Check if user exists
        if (!$user) {
            return redirect()->route('login')->with('error', 'No account found with this email. Please create an account first.');
        }

        // Check if email is verified
        if (!$user->email_verified_at) {
            return redirect()->route('login')->with('error', 'Please verify your email address before logging in. Check your inbox for the verification link.');
        }

        // Update Google ID if not set
        if (!$user->google_id) {
            $user->google_id = $googleUser->getId();
            $user->save();
        }

        // Update profile picture if Google has one and user is using default
        if ($googleUser->getAvatar() && $user->profilepic === 'default.jpg') {
            $this->updateGoogleProfilePicture($user, $googleUser);
        }

        // Log the user in
        Auth::login($user, true);
        
        return redirect()->route('home')->with('success', 'Successfully logged in with Google!');
    }

    /**
     * Handle Google Registration - Create new user with email verification required
     */
    private function handleGoogleRegistration($googleUser, $user)
    {
        // Check if user already exists
        if ($user) {
            // User exists but trying to register
            if ($user->email_verified_at) {
                return redirect()->route('register')->with('error', 'An account with this email already exists. Please use the login page instead.');
            } else {
                return redirect()->route('register')->with('error', 'An account with this email exists but is not verified. Please check your email for the verification link.');
            }
        }

        // Create new user WITHOUT email_verified_at (requires verification)
        $user = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'email_verified_at' => null, // Not verified yet
            'password' => bcrypt(Str::random(32)), // Random password for Google users
            'profilepic' => 'default.jpg',
        ]);

        // Download and save the Google profile picture
        if ($googleUser->getAvatar()) {
            $this->updateGoogleProfilePicture($user, $googleUser);
        }

        // Trigger the Registered event - this will send the verification email
        event(new Registered($user));

        // DO NOT log the user in - they need to verify email first
        return redirect()->route('login')->with('success', 'Account created successfully! Please check your email to verify your account before logging in.');
    }

    /**
     * Download and save Google profile picture
     */
    private function updateGoogleProfilePicture($user, $googleUser)
    {
        try {
            $avatarContents = file_get_contents($googleUser->getAvatar());
            $avatarName = 'google_' . $user->id . '_' . time() . '.jpg';
            $path = public_path('profilepics/' . $avatarName);
            
            // Ensure directory exists
            if (!file_exists(public_path('profilepics'))) {
                mkdir(public_path('profilepics'), 0755, true);
            }
            
            file_put_contents($path, $avatarContents);
            
            $user->profilepic = $avatarName;
            $user->save();
        } catch (Exception $e) {
            \Log::error('Failed to download Google avatar: ' . $e->getMessage());
            // If avatar download fails, just use default
        }
    }
}
