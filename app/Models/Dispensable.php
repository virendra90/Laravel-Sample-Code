<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispensable extends Model
{
    /**
     * Attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dispense_order_id',
        'prescript_id',
        'dose_filled',
        'drug_filled',
        'strength_filled',
        'fully_filled',
    ];
    
    public function disbursements()
    {
        return $this->hasMany('App\Models\Disbursement', 'dispensable_id', 'id');
    }
    
}
