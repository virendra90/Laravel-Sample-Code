<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonAllergy extends Model
{
    /**
     * Attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'aliases',
    ];

    /**
     * Build user associations.
     *
     * @return \App\Models\User
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'suffered_allergies', 'common_allergy_id', 'patient_id');
    }
}
