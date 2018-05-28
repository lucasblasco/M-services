<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	// nombre correspondiente a la table en la base de datos
    protected $table = 'activities';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name', 'description', 'event_id', 'room_id', 'event_format_id', 'day', 'start_time', 'end_time', 'status_id');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at'];
    
    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function event()
    {
        return $this->belongsTo('App\Event');
    }

    public function room()
    {
        return $this->belongsTo('App\Room');
    }

    public function eventFormat()
    {
        return $this->belongsTo('App\EventFormat');
    }
}
