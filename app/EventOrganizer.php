<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventOrganizer extends Model
{
    protected $table = 'event_organizers';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name', 'logo', 'link_page');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at'];

	public function events(){
       return $this->belongsTo('App\Event');
    }
}
