<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class RootController extends Controller
{
    /**
     * Show the frontend.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.root');
    }
}
