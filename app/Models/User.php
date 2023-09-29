<?php

namespace App\Models;


use App\Models\Avatar;
use App\Mail\confirmation;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use App\Models\Emailtokenverification;
use  Illuminate\Contracts\Mail\Mailable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'surname',
        'last_name',
        'email',
        'password',
        'sexe',
        'member',
        'is_banned_account',
        'last_login'
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
    ];
    
    public function emailsender($link,$emailreceiver)
    {
        Mail::to($emailreceiver)->send(new confirmation($link));
    }

    public function avatar(){
       return  $this->hasOne(Avatar::class);
    }

    static public function getOwerFromToken($token)
    {
        return PersonalAccessToken::findToken($token)->tokenable;
    }

    public function update_avatar($file){
        
        $filename = time().'.'.$file->extension();
            $path = 'storage/'.$file->storeAs(
                'avatars_profile',
                $filename,
                'public'
            );
            $avatar = new Avatar;
            $avatar->image_path = $path;
            $avatar->file_name = $filename;
            return $avatar;
    }
}
