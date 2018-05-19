<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventFormat extends Model
{
    protected $table = 'event_formats';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name', 'duration');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at'];
}
