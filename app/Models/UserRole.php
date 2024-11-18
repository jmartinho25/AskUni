<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    // No automatic timestamps.
    public $timestamps = false;

    /**
     * Get the user associated with the user_role.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role associated with the user_role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
