<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RootController extends Controller
{
    /**
     * Show the frontend.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('root');
    }
}
