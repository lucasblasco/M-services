<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventPartner extends Model
{
   protected $table = 'event_partners';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name', 'logo', 'link_page', 'sorting');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at', 'sorting'];

	public function events(){
       return $this->belongsTo('App\Event');
    }
}
