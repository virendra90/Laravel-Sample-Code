<?php

namespace App\Http\Controllers\Service;

use App\Models\TestTag;
use App\Models\TestType;
use App\Http\Controllers\Controller;
use Auth;

class TestTagController extends Controller
{
    /**
     * Return illnesses.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response()->json(TestType::with('testtag.tests')->get());
    }
}
