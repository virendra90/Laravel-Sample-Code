<?php

namespace App\Http\Controllers\Service;

use App\Models\DosageFrequency;
use App\Http\Controllers\Controller;

class DosageFrequencyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\DosageFrequency $dosageFrequency
     * @return void
     */
    public function __construct(DosageFrequency $dosageFrequency)
    {
        $this->dosageFrequency = $dosageFrequency;
    }

    /**
     * Return an array of dosage frequencies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response()->json([
            'data' => $this->dosageFrequency->all()
        ]);
    }
}
