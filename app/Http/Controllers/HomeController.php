<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // Check if the user has the 'admin' role
        if ($user->roles()->where('role_name', 'admin')->exists()) {
            return redirect()->route('admin.dashboard');
        }

        // Check if the user has the 'user' role
        if ($user->roles()->where('role_name', 'user')->exists()) {
            return redirect()->route('user.dashboard');
        }

        // If the user has no specific role, show the default home view
        return view('/auth.login');
    }
}
