<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutUs extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['title', 'content'];

    protected $table = 'about_us';
}