<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;
use Illuminate\Support\Str;

class InvitationController extends Controller {
    public function inviteUser(Request $request) {
        
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:Admin,Member',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator, 'memberErrorBag')->withInput();
        }

        $token = Str::random(32);
        Invitation::create([
            'email' => $request->email,
            'role' => $request->role,
            'company_id' => auth()->user()->company_id,
            'token' => $token,
        ]);

        return back()->with('success', 'Invite link: ' . route('invitation.accept', $token));
    }
}