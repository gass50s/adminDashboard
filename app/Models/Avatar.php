<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Avatar extends Model
{
    use HasFactory;
    protected $fillable = [
        'image_path',
        'file_name'
    ];
    public function user(){
      return  $this->belongsTo(User::class);
    }
}
