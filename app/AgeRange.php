<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgeRange extends Model
{
    protected $table = 'age_ranges';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at'];

	public function events()
    {
        return $this->belongsToMany('App\Event', 'age_range_event');
    }
}
