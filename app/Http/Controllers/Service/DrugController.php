<?php

namespace App\Http\Controllers\Service;

use App\Models\Drug;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class DrugController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Drug $drug
     * @return void
     */
    public function __construct(Drug $drug)
    {
        $this->drug = $drug;
    }

    /**
     * Return an array of drugs.
     *
     * @param  \App\Http\Requests\Prescription $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $_order = $request->get('_order', '');
        $name = $request->get('q', '');
        if ($name != '') {
            $response =  $this->drug->with('drug_format')
                    ->where('trade_name', 'like', '%'.$name.'%')->orWhere('generic_name', 'like', '%'.$name.'%')
                    ->get()->toArray();
            $resp = [];
            foreach ($response as $drug) {
                $tmp = [];
                $tmp['_order'] = $_order;
                $tmp['label'] = $drug['trade_name'].' ('.$drug['generic_name'].') '.$drug['drug_format']['name'].' '.$drug['dosages'];   
                $tmp['id'] = $drug['id'];
                $resp[] = $tmp;
            }
            return  Response()->json($resp);
        }
        return Response()->json(['data' => []]);
    }
}
