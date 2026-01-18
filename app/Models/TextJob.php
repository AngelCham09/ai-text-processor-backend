<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TextJob extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'input_text',
        'action_type',
        'output_text',
        'user_id',
    ];
}
