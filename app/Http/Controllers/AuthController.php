<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function getUser(Request $request)
    {
        // if ($request->ajax()) {
        return response()->json([
            'user' => auth()->user(),
            'role' => auth()->user()->role->name
        ]);
        // } else {
        //     abort(404);
        // }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required',
            'password' => 'required'
        ]);

        if ($request->ajax()) {
            if (Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'authenticated' => true
                ]);
            } else {
                return response()->json([
                    'authenticated' => false
                ]);
            }
        }
    }

    public function logout()
    {
        if (auth()->user()) {
            Auth::logout();

            return redirect()->route('login');
        } else {
            abort(404);
        }
    }
}
