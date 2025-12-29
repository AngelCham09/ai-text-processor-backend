<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TextJob extends Model
{
    protected $fillable = [
        'input_text',
        'action_type',
        'output_text',
    ];
}
