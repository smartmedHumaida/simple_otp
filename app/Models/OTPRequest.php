<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OTPRequest extends Model
{
    protected $fillable = ['otp', 'phone', 'status'];
    protected $table = 'otp_requests';

    //add scope that get only those that verified = 1
    protected function scopeVerified(Builder $builder)
    {
        return $builder->where('verified', 1);
    }
    protected function scopeUnverified(Builder $builder)
    {
        return $builder->where('verified', 0);
    }
}
