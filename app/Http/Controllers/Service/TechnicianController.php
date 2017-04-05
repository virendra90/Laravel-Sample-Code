<?php

namespace App\Http\Controllers\Service;

use App\Models\User;
use App\Models\Location;
use App\Models\Vocation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class TechnicianController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\User             $user
     * @param  \App\Models\Location         $location
     * @return void
     */
    public function __construct(
        User $user,
        Location $location,
        Vocation $vocation
    ) {
        $this->user = $user;
        $this->location = $location;
        $this->vocation = $vocation;
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
        //$this->authorize('index', User::class);
        if ($name != '') {
            $patients = $this->user->technician()->with('vocation.location')
                ->whereHas('vocation', function ($query) {
                    $query->ofCustomerId($this->getCurrentUser()->vocation->customer_id);
                })
                ->get();
        } else {
            $patients = $this->user->technician()->with('vocation.location')
                ->whereHas('vocation', function ($query) {
                    $query->ofCustomerId($this->getCurrentUser()->vocation->customer_id);
                })
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
    public function create(Request $request)
    {
        // $this->authorize('create', User::class);
        DB::transaction(function () use ($request) {
            
            $user =  $this->user->setAttribute('user_type_id', User::TECHNICIAN)
                ->fill($request->only(
                  'first_name',
                        'last_name',
                        'email_primary',
                        'middle_name',
                        'phone_primary',
                        'password',
                        'address_street_1',
                        'address_street_2',
                        'address_municipality',
                        'address_territory',
                        'address_postal_code',
                        'address_country'))
                ->setAttribute('is_admin', false)
                ->save();
                
               /* $this->location->setAttribute('customer_id', $this->getCurrentUser()->vocation->customer_id)
                    ->fill($request->only(
                        'address_street_1',
                        'address_street_2',
                        'address_municipality',
                        'address_territory',
                        'address_postal_code',
                        'address_country',
                        'latitude',
                        'longitude',
                        'email_primary',
                        'email_secondary',
                        'phone_primary',
                        'phone_secondary',
                        'fax_primary',
                        'fax_secondary',
                        'name'
                    ))->save(); */

               $this->getCurrentUser()
                  ->vocation->customer->users()
                  ->attach($this->user, ['location_id'=>$this->getCurrentUser()->vocation->location_id]);
        });
        return Response()->json(['data' => $this->location], 201);
    }
    
    /**
     * Return a patient.
     *
     * @param  \App\Http\Requests\CreatePatient $request
     * @return \Illuminate\Http\Response
     */
     
     
    public function update(Request $request, $id)
    {
        // $this->authorize('create', User::class);
        DB::transaction(function () use ($request, $id) {
            
            $user = $this->user->find($id);
           $user->fill($request->only('first_name', 'last_name', 'email_primary', 'password',
                'address_street_1',
                'address_street_2',
                'address_municipality',
                'address_territory',
                'address_postal_code',
                'address_country',               
                'email_primary',               
                'phone_primary'                 
            ));
            $user->save();
      
                   
            $_location = Vocation::where('user_id', $id)->first();
        //    $_location->location_id; exit;
            $loc = $this->location->find($_location->location_id);
           
            $loc->setAttribute('customer_id', $this->getCurrentUser()->vocation->customer_id)
                ->fill($request->only(
                    'address_street_1',
                    'address_street_2',
                    'address_municipality',
                    'address_territory',
                    'address_postal_code',
                    'address_country',
                    'latitude',
                    'longitude',
                    'email_primary',
                    'email_secondary',
                    'phone_primary',
                    'phone_secondary',
                    'fax_primary',
                    'fax_secondary',
                    'name'
                ));
               $loc->save();
                    
             //  $this->getCurrentUser()->vocation->customer->users()->attach($this->user,['location_id'=>$this->location->id]);
        });
        return Response()->json(['data' => ['id'=>$id]], 201);
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
        $patient = $this->user
            ->technician()->with('vocation.location')
            ->whereHas('vocation', function ($query) {
                $query->ofCustomerId($this->getCurrentUser()->vocation->customer_id);
            })
            ->find($id);

        return Response()->json(['data' => $patient]);
    }
    
    public function get_profile(Request $request)
    {
        $patient = $this->location          
            ->where('customer_id',$this->getCurrentUser()->vocation->customer_id)          
            ->first();

        return Response()->json(['data' => $patient]);
        
    }
    
    public function profile(Request $request)
    {
        // $this->authorize('create', User::class);
        DB::transaction(function () use ($request) {     
        
                      
             //   $loc = $this->location->where('customer_id', $this->getCurrentUser()->vocation->customer_id);
              
                $this->location->where('customer_id', $this->getCurrentUser()->vocation->customer_id)->update($request->only(
                    'address_street_1',
                    'address_street_2',
                    'address_municipality',
                    'address_territory',
                    'address_postal_code',
                    'address_country',
                    'latitude',
                    'longitude',
                    'email_primary',
                    'email_secondary',
                    'phone_primary',
                    'phone_secondary',
                    'fax_primary',
                    'fax_secondary',                   
                    'name'                    
                    ));
              //  $loc->save();    
                
        });
        return Response()->json(['data' => ['id'=>$this->location]], 201);
        
    }
}
