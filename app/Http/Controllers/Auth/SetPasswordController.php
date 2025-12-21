<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SetPasswordController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($request->password),
            'password_set' => true,
        ]);

        return redirect('/dashboard')
            ->with('success', 'Password set successfully.');
    }
}
