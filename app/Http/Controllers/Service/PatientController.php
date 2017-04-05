<?php

namespace App\Http\Controllers\Service;

use App\Models\User;
use App\Models\CommonAllergy;
use App\Models\SufferedAllergy;
use App\Models\CommonIllness;
use App\Models\SufferedIllness;
use App\Models\PatientNote;

use App\Http\Requests\CreatePatient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class PatientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\User             $user
     * @param  \App\Models\CommonAllergy    $commonAllergy
     * @param  \App\Models\SufferedAllergy  $sufferedAllergy
     * @param  \App\Models\CommonIllness    $commonIllness
     * @param  \App\Models\$sufferedIllness $sufferedIllness
     * @param  \App\Models\PatientNote      $patientNote
     * @return void
     */
    public function __construct(
        User $user,
        CommonAllergy $commonAllergy,
        SufferedAllergy $sufferedAllergy,
        CommonIllness $commonIllness,
        SufferedIllness $sufferedIllness,
        PatientNote $patientNote
    ) {
        $this->user = $user;
        $this->commonAllergy = $commonAllergy;
        $this->sufferedAllergy = $sufferedAllergy;
        $this->commonIllness = $commonIllness;
        $this->sufferedIllness = $sufferedIllness;
        $this->patientNote = $patientNote;
    }

    /**
     * Return an array of patients.
     *
     * @param  \App\Http\Requests\Prescription $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = strtolower($request->get('q', ''));
        $this->authorize('index', User::class);
        if ($name != '') {
            $patients = $this->user->patient()
                ->whereHas('vocation', function ($query) {
                    $query->ofCustomerId($this->getCurrentUser()->vocation->customer_id);
                })
                ->whereHas('vocation', function ($query) use ($name) {
                    $query->where(DB::raw("LOWER(CONCAT(first_name, ' ', last_name))"), 'like', '%'.$name.'%');
                })
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $patients = $this->user->patient()
                ->whereHas('vocation', function ($query) {
                    $query->ofCustomerId($this->getCurrentUser()->vocation->customer_id);
                })
                ->orderBy('id', 'desc')
                ->get();
        }
        return Response()->json(['data' => $patients]);
    }

    /**
     * Return a patient.
     *
     * @param  \App\Http\Requests\CreatePatient $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreatePatient $request)
    {
        $this->authorize('create', User::class);
        DB::transaction(function () use ($request) {
            $this->user->setAttribute('user_type_id', User::PATIENT)
                ->fill($request->only('first_name', 'last_name', 'gender_id', 'date_of_birth','address_street_1'))
                ->setAttribute('is_admin', false)
                ->save();
            $this->getCurrentUser()->vocation->customer->users()->attach($this->user);
        });
        return Response()->json(['data' => $this->user], 201);
    }

    /**
     * Return a patient by id.
     *
     * @param  \App\Http\Requests\Prescription $request
     * @param  integer                         $id
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request, $id)
    {
        $this->authorize('find', User::class);

        $patient = $this->user
            ->patient()
            ->whereHas('vocation', function ($query) {
                $query->ofCustomerId($this->getCurrentUser()->vocation->customer_id);
            })
            ->find($id);

        return Response()->json(['data' => $patient]);
    }
}
