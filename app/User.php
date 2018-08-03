<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'enabled', 'linkedin_id', 'confirmed', 'confirmation_code'
    ];

    protected $hidden = [
        'password', 'remember_token', 'confirmation_code'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function person()
    {
        return $this->hasOne('App\Person');
    }

    public function event()
    {
        return $this->HasMany('App\Event');
    }

    public function organization()
    {
        return $this->hasOne('App\Organization');
    }

    public function interests()
    {
        return $this->belongsToMany('App\Interest', 'interest_user')->withTimestamps();
    }

    public function accounts()
    {
        return $this->belongsToMany('App\Account', 'account_user')
            ->withPivot('name')
            ->withTimestamps();
    }

    public function jobs()
    {
        return $this->belongsToMany('App\Job', 'job_user')->withTimestamps();
    }

    public function events()
    {
        return $this->belongsToMany('App\Event', 'event_user')->withTimestamps();
    }

    public function activities()
    {
        return $this->belongsToMany('App\Activity', 'activity_user')->withTimestamps()->withPivot('id');
    }    

    public function hasActivities()
    {
        return (bool) $this->has('activities')->get();
    }
}
