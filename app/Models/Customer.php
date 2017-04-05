<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * Practice customer_type_id.
     *
     * @var integer
     */
    const PRACTICE = 1;

    /**
     * Pharmacy customer_type_id.
     *
     * @var integer
     */
    const PHARMACY = 2;

    /**
     * Medical Lab customer_type_id.
     *
     * @var integer
     */
    const MEDICAL_LAB = 3;

    /**
     * Radiological Lab customer_type_id.
     *
     * @var integer
     */
    const RADIOLOGICAL_LAB = 4;

    /**
     * Build user associations.
     *
     * @return \App\Models\User
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'vocations');
    }
    
    public function locations()
    {
        return $this->belongsToMany('App\Models\Location', 'vocations');
    }
}
