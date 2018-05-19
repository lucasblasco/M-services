<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    // nombre correspondiente a la table en la base de datos
    protected $table = 'organizations';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name', 'phone', 'country_id', 'province_id', 'city_id', 'postal_code', 'street ', 'number', 'floor', 'dept', 'contact_name', 'contact_phone', 'email', 'user_id');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at']; 

	public function accounts()
    {
        return $this->belongsToMany('App\Account', 'account_organization')->withTimestamps();
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function province()
    {
        return $this->belongsTo('App\Province');
    }
    
    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
