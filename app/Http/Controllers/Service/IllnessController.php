<?php

namespace App\Http\Controllers\Service;

use App\Models\CommonIllness;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IllnessController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\CommonIllness $commonIllness
     * @return void
     */
    public function __construct(CommonIllness $commonIllness)
    {
        $this->commonIllness = $commonIllness;
    }

    /**
     * Return an array of common illnesses.
     *
     * @param  \App\Http\Requests\Prescription $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->get('q', '');

        if ($name != '') {
            return Response()->json([
                'data' => $this->commonIllness
                    ->where('name', 'like', '%'.$name.'%')
                    ->get()
            ]);
        }

        return Response()->json(['data' => []]);
    }
}
