<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    // nombre correspondiente a la table en la base de datos
    protected $table = 'accounts';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name', 'enabled');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at'];

	public function persons()
    {
        return $this->belongsToMany('App\Person', 'account_person');
    }
}
