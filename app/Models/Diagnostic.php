<?php

namespace App\Models;

use App\Models\Test;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Diagnostic extends Model
{
    /**
     * Attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patient_id',
        'verification_pin',
        'laboratory_id',
        'physician_id',
        'notes',
        'identifier',
        'commentable',
        'archived_for_physician',
        'archived_for_technician',
        'reviewed',
        'last_modifier_type',
        'last_modifier_id',
        'diagnosis',
    ];
    
    /**
     * Generate random pin for diagnostic
     *
     * @return \App\Models\User
     */
    public function setVerificationPinAttribute($val = '')
    {
        $this->attributes['verification_pin'] = rand(1111, 9999);
    }
    
     /**
     * Generate random identifier for diagnostic
     *
     * @return \App\Models\User
     */
    public function setIdentifierAttribute($val = '')
    {
        $this->attributes['identifier'] = strtoupper(str_random($val));
    }
    

    
    public function tests()
    {
        return $this->belongsToMany(
            'App\Models\Test',
            'testings',
            'diagnostic_id',
            'test_id'
        );
        //return $this->hasMany('App\Models\Test', 'diagnostic_id', 'id');
    }
    public function laboratory()
    {
        return $this->hasOne('App\Models\Location', 'id', 'laboratory_id');
    }
    
    public function patient()
    {
        return $this->hasOne('App\Models\User', 'id', 'patient_id');
    } 
    public function physician()
    {
        return $this->hasOne('App\Models\User', 'id', 'physician_id');
    }
    public function attachments()
    {
        return $this->hasMany('App\Models\Attachment', 'attachable_id', 'id');
    }
}
