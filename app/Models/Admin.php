<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $casts = [
        'is_admin' => 'bool',
        'is_store_manager' => 'bool',
        'is_staff' => 'bool',
    ];

    protected $fillable = [
        'store_id',
        'mobile',
        'password',
        'name',
        'is_admin',
        'is_store_manager',
        'is_staff',
    ];
}
