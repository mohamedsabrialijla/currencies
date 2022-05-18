<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ProfileController extends Controller
{
    public function index()
    {
        return View::make('profile', [
            'user' => Auth::user(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        Auth::user()->update($request->all());

        return Redirect::route('profile')->with('status', __('Profile information updated'));
    }
}
