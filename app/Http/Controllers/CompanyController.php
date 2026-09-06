<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Invitation;
use Illuminate\Support\Str;

class CompanyController extends Controller {
    public function inviteCompanyAdmin(Request $request) {
        $request->validate([
            'company_name' => 'required|string|max:255|unique:companies,name',
            'email' => 'required|email|unique:users,email',
        ]);
        $company = Company::create(['name' => $request->company_name]);
        $token = Str::random(32);
        Invitation::create([
            'email' => $request->email,
            'role' => 'Admin',
            'company_id' => $company->id,
            'token' => $token,
        ]);
        return back()->with('success', 'Invite link: ' . route('invitation.accept', $token));
    }
}