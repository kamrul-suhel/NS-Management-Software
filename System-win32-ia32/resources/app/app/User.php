<?php

namespace App;

use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;

    protected $dates = ['deleted_at'];

    const VERIFIED_USER = "1";
    const UNVERIFIED_USER = "0";

    const ADMIN_USER = 'true';
    const REGULAR_USER = 'false';

    protected $table = 'users';


    /*
     *
     * This is transform attribute
     *
     */

//    public $transformer = UserTransformer::class;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
        'deleted_at'
    ];


    public function setNameAttribute($value){
        return $this->attributes['name'] = $value;
    }

    public function getNameAttribute($value){
        return ucwords($value);
    }

    public function setEmailAttribute($value){
        $this->attributes['email'] = strtolower($value);
    }

    public function getEmailAttribute(){
        return $this->attributes['email'];
    }


    public function isVerified(){
        return $this->verified == self::VERIFIED_USER;
    }

    public function isAdmin(){
        return $this->admin == self::ADMIN_USER;
    }

    public static function generateVerificationCode(){
        return str_random(40);
    }
}
