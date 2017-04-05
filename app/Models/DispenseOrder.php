<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispenseOrder extends Model
{
    /**
     * Attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prescription_id',
        'pharmacy_id',
        'transferer_id',
        'processing_at',
        'ready_at',
        'delivered_at',
        'commentable',
        'transferred_at',
        'last_modifier_type',
        'last_modifier_id',
        'ongoing',
    ];
    
    public function prescription()
    {
        return $this->hasOne('App\Models\Prescription', 'id', 'prescription_id');
    }
    
    public function dispensables()
    {
        return $this->hasMany('App\Models\Dispensable', 'dispense_order_id', '');
    }
    public function messages()
    {
        return $this->hasMany('App\Models\Message', 'asset_id', '');
    }
}
