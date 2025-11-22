<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingUser extends Model
{
    protected $guarded = [];
    // atau protected $fillable = ['name', 'email', 'password', 'otp_code'];
}