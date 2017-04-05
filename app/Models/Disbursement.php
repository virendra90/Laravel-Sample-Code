<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disbursement extends Model
{
    /**
     * Attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pharmacist_id',
        'annotation',
        'prescribed_drug_strength',
        'prescribed_drug_unit',
        'filled_drug_strength',
        'filled_drug_unit',
        'filled_drug_id',
        'filled_dose',
        'dispensable_id',
        'pharmacy_id',
    ];
    
     public function pharmacy()
    {
        return $this->hasOne('App\Models\Location', 'id', 'pharmacy_id');
    }    
    public function pharmacist()
    {
        return $this->hasOne('App\Models\User', 'id', 'pharmacist_id');
    }   

    public function dispensable()
    {
        return $this->hasOne('App\Models\Dispensable', 'id', 'dispensable_id');
    }
    
    
   
}
