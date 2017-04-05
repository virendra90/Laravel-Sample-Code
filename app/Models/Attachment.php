<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /**
     * Attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'file_file_name',
        'file_content_type',
        'file_file_size',
        'file_updated_at',
        'title',
    ];
}
