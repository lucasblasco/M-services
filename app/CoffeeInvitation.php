<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoffeeInvitation extends Model
{
    // nombre correspondiente a la table en la base de datos
    protected $table = 'coffee_invitations';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('id_activity_user_send', 'id_user_receive', 'id_status_coffee', 'timed_out');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at'];  
    
    public function status()
    {
        return $this->belongsTo('App\CoffeeStatus');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
