<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoffeeStatus extends Model
{
    // nombre correspondiente a la table en la base de datos
    protected $table = 'coffee_statuses';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at'];  
    
    public function activity(){
       return $this->HasMany('App\Activity');
    }

    public function coffee_invitations(){
       return $this->HasMany('App\CoffeeInvitation');
    }
}
