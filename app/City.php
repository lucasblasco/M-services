<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name', 'province_id');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at'];

	public function person(){
	   return $this->HasMany('App\Person');
	}

	public function organization(){
	   return $this->HasMany('App\Organization');
	}
}
