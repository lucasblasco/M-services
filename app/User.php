<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'enabled', 'linkedin_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function person(){
       return $this->HasMany('App\Person');
    }

    public function event(){
       return $this->HasMany('App\Event');
    }

    public function organization(){
       return $this->HasMany('App\Organization');
    }
}
