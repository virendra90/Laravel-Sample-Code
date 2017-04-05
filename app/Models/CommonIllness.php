<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommonIllness extends Model
{
    /**
     * Attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'aliases',
        'description',
    ];

    /**
     * Build user associations.
     *
     * @return \App\Models\User
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'suffered_illnesses', 'common_illness_id', 'patient_id');
    }
}
