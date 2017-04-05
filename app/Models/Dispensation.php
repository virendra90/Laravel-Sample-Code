<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispensation extends Model
{
    /**
     * Attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dispensable_id',
        'price',
        'pharmacist_id',
    ];
}
