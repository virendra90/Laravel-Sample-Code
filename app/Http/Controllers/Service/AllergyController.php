<?php

namespace App\Http\Controllers\Service;

use App\Models\CommonAllergy;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AllergyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\CommonAllergy $commonAllergy
     * @return void
     */
    public function __construct(CommonAllergy $commonAllergy)
    {
        $this->commonAllergy = $commonAllergy;
    }

    /**
     * Return an array of common allergies.
     *
     * @param  \App\Http\Requests\Prescription $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index', CommonAllergy::class);
        $name = $request->get('q', '');
        if ($name != '') {
            return Response()->json([
                'data' => $this->commonAllergy
                    ->where('name', 'like', '%'.$name.'%')
                    ->get()
            ]);
        }
        return Response()->json(['data' => []]);
    }
    
    /**
     * Return an array of common allergies.
     *
     * @param  \App\Http\Requests\Prescription $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
            $user = CommonAllergy::where('name', $input['allergy'])->first();
            print_r($user);
        exit;
        if ($user === null) {
            $this->commonAllergy->setAttribute('name', $input['allergy']);
            $this->commonAllergy->save();
            $cid =  $this->commonAllergy->id;
        } else {
            $cid = $user->id;
        }
                     $user = $this->sufferedAllergy->where('common_allergy_id', $cid)
                          ->where('patient_id', $id)
                          ->first();
        if ($user === null) {
            $this->sufferedAllergy->setAttribute('common_allergy_id', $cid)->setAttribute('patient_id', $id);
            $this->sufferedAllergy->save();
        }
    }

    /**
     * Return an array of common allergies.
     *
     * @param  \App\Http\Requests\Prescription $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
            $user = CommonAllergy::where('name', $input['allergy'])->first();
        if ($user === null) {
            $this->commonAllergy->setAttribute('name', $input['allergy']);
            $this->commonAllergy->save();
            $cid =  $this->commonAllergy->id;
        } else {
            $cid = $user->id;
        }
                     $user = $this->sufferedAllergy
                          ->where('common_allergy_id', $cid)
                          ->where('patient_id', $id)
                          ->first();
        if ($user === null) {
            $this->sufferedAllergy->setAttribute('common_allergy_id', $cid)->setAttribute('patient_id', $id);
            $this->sufferedAllergy->save();
        }
    }
}
