<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;


// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'description',
        'photo',
        'is_blocked',
        'score',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_blocked' => 'boolean', // perguntar isto
        'score' => 'integer',
    ];

    

    /**
     * Get the posts for a user.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'users_id');
    }

    /**
     * Get the questions for a user.
     */
    public function questions(): HasManyThrough
    {
        return $this->hasManyThrough(Question::class, Post::class, 'users_id', 'posts_id');
    }

    /**
     * Get the answers for a user.
     */
    public function answers(): HasMany
    {
        return $this->posts()->whereHas('answer')->with('answer');
    }

    /**
     * Get the notifications for a user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'users_id');
    }
    
    /**
     * Get the roles for a user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
