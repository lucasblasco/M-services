<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudyLevel extends Model
{
    // nombre correspondiente a la table en la base de datos
    protected $table = 'study_levels';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at'];

	public function persons(){
	   return $this->hasMany('App\Person');
	}
}
