<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    // nombre correspondiente a la table en la base de datos
    protected $table = 'accounts';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name', 'image_name', 'enabled', 'url');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at'];

	public function users()
    {
        return $this->belongsToMany('App\User', 'account_user')
            ->withPivot('name')
            ->withTimestamps();
    }

    public function events()
    {
        return $this->belongsToMany('App\Event', 'account_event')
            ->withPivot('name')
            ->withTimestamps();
    }
}
