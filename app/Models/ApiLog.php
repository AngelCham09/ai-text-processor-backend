<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    protected $fillable = [
        'method',
        'url',
        'user_id',
        'status_code',
        'ip_address',
        'execution_time',
        'error_message',
        'request_payload',
        'response_payload',
    ];
}
