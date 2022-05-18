<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class PasswordController extends Controller
{
    public function index()
    {
        return View::make('password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'password'],
            'password' => ['required', 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->post('password')),
        ]);

        return Redirect::route('profile')->with('status', __('Account password updated'));
    }
}
