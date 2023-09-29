<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rights extends Model
{
    use HasFactory;
    protected $fillable = [
        'groupe',
        'use_right'
    ];
}
