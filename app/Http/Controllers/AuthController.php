<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Auth::user()->createToken('auth_token');
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid Credentials']);
    }

    public function logout(Request $request) {
        if (auth()->check()) {
            auth()->user()->tokens()->delete();
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showAcceptInvitation($token) {
        $invitation = Invitation::where('token', $token)->where('accepted', false)->firstOrFail();
        return view('auth.accept-invitation', compact('invitation'));
    }

    public function acceptInvitation(Request $request, $token) {
        $invitation = Invitation::where('token', $token)->where('accepted', false)->firstOrFail();
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|min:8|confirmed',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'company_id' => $invitation->company_id,
            'role' => $invitation->role,
        ]);

        $invitation->update(['accepted' => true]);

        return redirect('/login')->with('success', 'Account created! Please login.');
    }
}