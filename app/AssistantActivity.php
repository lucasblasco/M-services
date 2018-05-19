<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssistantActivity extends Model
{
    protected $table = 'assistant_activities';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at'];

	public function event(){
	   return $this->HasMany('App\Event');
	}
}
