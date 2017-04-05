<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Customer;
use App\Models\Location;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterCustomer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showForm()
    {
        return view('auth.register');
    }

    /**
     * User instance.
     *
     * @var \App\User
     */
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user, Customer $customer, Location $location)
    {
        $this->user = $user;
        $this->customer = $customer;
        $this->location = $location;
        $this->middleware('guest');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\User
     */
    public function registered(RegisterCustomer $request, User $user)
    {
        return redirect($this->redirectTo);
    }

    /**
     * @param integer $customerTypeId
     * @return integer
     */
    protected function getUserTypeId($customerTypeId)
    {
        switch ($customerTypeId) {
            case Customer::PRACTICE:
                return User::PHYSICIAN;
            case Customer::PHARMACY:
                return User::PHARMACIST;
            case Customer::MEDICAL_LAB:
            case Customer::RADIOLOGICAL_LAB:
                return User::TECHNICIAN;
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \App\User
     */
    protected function create(RegisterCustomer $request)
    {
        return DB::transaction(function () use ($request) {

            $this->user
                ->setAttribute('user_type_id', $this->getUserTypeId($request->get('customer_type_id')))
                ->fill($request->only('first_name', 'last_name', 'email_primary', 'password'))
                ->setAttribute('is_admin', true)
                ->save();

            $this->customer
                ->setAttribute('customer_type_id', $request->get('customer_type_id'))
                ->save();
                
            $this->location
                ->setAttribute('customer_id', $this->customer->id)
                ->setAttribute('name', $request->get('name'))
                ->save();

            $this->customer->users()->attach($this->user, ['location_id' => $this->location->id]);

            return $this->user;
        });
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterCustomer $request)
    {
        event(new Registered($user = $this->create($request)));

        $this->guard()->login($user);

        return $this->registered($request, $user);
    }
}
