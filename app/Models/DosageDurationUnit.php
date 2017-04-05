<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosageDurationUnit extends Model
{
    /**
     * Attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'threshold',
    ];
}
