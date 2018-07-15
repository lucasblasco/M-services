<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SummitUploadFile extends Model
{
    // nombre correspondiente a la table en la base de datos
    protected $table = 'summit_upload_files';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('user_id', 'activity_id', 'title', 'description', 'template_path');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at'];

	public function user(){
	   return $this->belongsTo('App\User');
	}

	public function activity(){
	   return $this->belongsTo('App\Activity');
	}
}
