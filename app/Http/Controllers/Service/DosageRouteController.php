<?php

namespace App\Http\Controllers\Service;

use App\Models\DosageRoute;
use App\Http\Controllers\Controller;

class DosageRouteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\DosageRoute $dosageRoute
     * @return void
     */
    public function __construct(DosageRoute $dosageFrequency)
    {
       // $this->dosageRoute = $dosageRoute;
    }

    /**
     * Return an array of dosage routes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response()->json([
            'data' => DosageRoute::all()
        ]);
    }
}
