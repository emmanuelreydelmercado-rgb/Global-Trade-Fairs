<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // Validate ONLY login fields
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Handle profile picture update if uploaded
            if ($request->hasFile('profilepic')) {
                $user = Auth::user();
                $file = $request->file('profilepic');
                $profilePicName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('profilepics'), $profilePicName);
                
                $user->profilepic = $profilePicName;
                $user->save();
            }

            // Check if email is verified
            if (!auth()->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            // Redirect based on role
            return auth()->user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
