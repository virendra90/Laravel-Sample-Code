<?php

namespace App\Http\Controllers\Service;

use App\Models\Insurer;
use App\Http\Controllers\Controller;

class InsurerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Insurer $insurer
     * @return void
     */
    public function __construct(Insurer $insurer)
    {
        $this->insurer = $insurer;
    }

    /**
     * Return an array of insurers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response()->json([
            'data' => $this->insurer->all()
        ]);
    }
}
