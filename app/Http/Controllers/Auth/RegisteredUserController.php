<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profilepic' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $profilePicName = 'default.jpg';
        if ($request->hasFile('profilepic')) {
            try {
                $file = $request->file('profilepic');
                $profilePicName = time() . '_' . $file->getClientOriginalName();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('profilepics');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0775, true);
                }
                
                $file->move($uploadPath, $profilePicName);
            } catch (\Exception $e) {
                // If upload fails, just use default profile picture
                \Log::warning('Profile picture upload failed: ' . $e->getMessage());
                $profilePicName = 'default.jpg';
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profilepic' => $profilePicName,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('verification.notice'));
    }
}
