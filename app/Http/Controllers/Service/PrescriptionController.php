<?php

namespace App\Http\Controllers\Service;

use App\Models\User;
use App\Models\Prescription;
use App\Models\Prescript;
use App\Models\DispenseOrder;
use App\Models\Dispensable;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;

class PrescriptionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        User $user, 
        Prescription $prescription,
        DispenseOrder $dispenseOrder, 
        Prescript $prescript,
        Dispensable $dispensable
    )
    {
        $this->user = $user;
        $this->prescription = $prescription;
        $this->dispenseOrder = $dispenseOrder;
        $this->prescript = $prescript;
        $this->dispensable = $dispensable;
    }

    /**
     * Return an array of prescriptions.
     *
     * @param  \App\Http\Requests\Prescription $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = strtolower($request->get('q', ''));
        $this->authorize('index', User::class);

        if ($name != '') {
            $prescription = $this->prescription->withCount('drugs')->with('location')
            ->whereHas('patient', function ($query) use ($name) {
                $query->where(DB::raw("LOWER(CONCAT(first_name, ' ', last_name))"), 'like', '%' . $name . '%');
            })
            ->with(['patient' => function ($query) {
                $query->select('id', 'date_of_birth', 'first_name', 'last_name');
            }])
            ->where('physician_id', $this->getCurrentUser()->id)
            ->get();
        } else {
            $prescription = $this->prescription->withCount('drugs')->with('location')
                ->with(['patient' => function ($query) {
                    $query->select('id', 'date_of_birth', 'first_name', 'last_name');
                }])
                ->where('physician_id', $this->getCurrentUser()->id)
                ->orderBy('id', 'DESC')
                ->get();
        }

        return Response()->json(['data' => $prescription]);
    }

    /**
     * Create a prescription and associate it with a user.
     *
     * @param  \App\Http\Requests\Prescription $request
     * @param  integer                         $id
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $this->authorize('create', User::class);

        $input = $request->all();
     
        DB::transaction(function () use ($input, $id) {
            $this->prescription
                ->setAttribute('patient_id', $id)
                ->setAttribute('physician_id', $this->getCurrentUser()->id)
               ->fill(['verification_pin'=>'random','identifier'=>8]);
             
            $this->prescription->save();
            foreach ($input as $val) {
                $val['pivot']['prescription_id'] = $this->prescription->id;
                $prescript = new Prescript($val['pivot']);
                $prescript->save();
            }
        });
        return Response()->json(['data' => $this->prescription], 201);
    }

    /**
     * Update a prescription by id.
     *
     * @param  \App\Http\Requests\Prescription $request
     * @param  integer                         $id
     * @param  integer                         $pid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $pid)
    {
        $input = $request->all();
        DB::transaction(function () use ($input, $id, $pid) {
            Prescript::where('prescription_id', $pid)->delete();
            foreach ($input as $val) {
                $val['pivot']['prescription_id'] = $pid;
                $prescript = new Prescript($val['pivot']);
                $prescript->save();
            }
        });
        return Response()->json(null, 204);
    }

    /**
     * Return illnesses.
     *
     * @param  \App\Http\Requests\Prescription $request
     * @param  integer                         $id
     * @param  integer                         $pid
     * @return \Illuminate\Http\Response
     */
    public function submit(Request $request, $id, $pid)
    {
        $input = $request->all();
        DB::transaction(function () use ($input, $id, $pid) {
            $a = $this->prescription->where('id', $pid)
                ->update([
                    'allow_generic' => $input['allow_generic'],
                    'pharmacy_id' => $input['pharmacy_id'],
                    'printed' => $input['printed']
                ]);
                
                $this->dispenseOrder->setAttribute('ongoing',true)->setAttribute('prescription_id',$pid)->setAttribute('pharmacy_id',$input['pharmacy_id'])->save();
                $prescripts = $this->prescript->with('drug.drug_format')->where('prescription_id',$pid)->get();
               
                foreach( $prescripts as $prescript){
                    //echo $prescript->drug->generic_name;
                    $dispensable = new Dispensable();
                    $dispensable->setAttribute('drug_filled', $prescript->drug->trade_name.' ('.$prescript->drug->generic_name.') '.$prescript->drug->drug_format->name.' '.$prescript->drug->dosages);
                    $dispensable->setAttribute('dose_filled', $prescript->dose);
                    $dispensable->setAttribute('dispense_order_id', $this->dispenseOrder->id);
                    $dispensable->setAttribute('prescript_id', $prescript->id);
                    $dispensable->save();
                }
                
        });
       // return Response()->json(['data'=>$this->prescript], 204);
    }

    /**
     * Find a prescription by id.
     *
     * @param  \App\Http\Requests\Prescription $request
     * @param  integer                         $id
     * @param  integer                         $pid
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request, $id, $pid)
    {
        $this->authorize('find', User::class);
        $prescription = $this->prescription->with('drugs')->with('location')->find($pid);
        return Response()->json(['data' => $prescription]);
    }
}
