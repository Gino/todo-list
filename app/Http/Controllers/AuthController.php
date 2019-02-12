<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function getUser(Request $request)
    {
        if ($request->ajax()) {
            return response()->json([
                'user' => auth()->user(),
                'role' => auth()->user()->role->name
            ]);
        } else {
            abort(404);
        }
    }

    public function getUsers(Request $request)
    {
        if (auth()->user()->role->name !== 'Administrator') {
            return abort(403);
        }

        if ($request->ajax()) {
            return response()->json([
                'users' => User::with('role')->get(),
            ]);
        } else {
            abort(404);
        }
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

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'email'                 => 'required',
            'password'              => 'required|confirmed',
        ]);

        if ($request->ajax()) {
            $u = new User;

            $u->name = $request->name;
            $u->email = $request->email;
            $u->password = Hash::make($request->password);

            $u->save();
        } else {
            abort(404);
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
