<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'member_id',
        'name',
        'father_name',
        'mother_name',
        'gender',
        'date_of_birth',
        'nid',
        'phone',
        'email',
        'division',
        'district',
        'upazila',
        'address',
        'occupation',
        'join_date',
        'photo',
        'status',
        'remarks',
    ];

}
