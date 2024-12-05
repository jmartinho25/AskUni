<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['users_id', 'roles_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'roles_id');
    }
}
