<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    // nombre correspondiente a la table en la base de datos
    protected $table = 'speakers';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name', 'image');

	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at']; 

	public function activities()
    {
        return $this->belongsToMany('App\Activity', 'activity_speaker')->withTimestamps();
    }
}
