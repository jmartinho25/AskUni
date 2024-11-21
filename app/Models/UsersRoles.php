<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersRoles extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'users_id', 
        'roles_id'
    ];
}
