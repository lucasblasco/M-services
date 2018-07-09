<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    // nombre correspondiente a la table en la base de datos
    protected $table = 'persons';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name', 'surname', 'birth_date', 'document_type_id', 'document_number', 'phone', 'email', 'study_level_id', 'profession_id', 'user_id', 'country_id', 'province', 'city', 'postal_code', 'street', 'number', 'floor', 'dept', 'share_data', 'avatar');

	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at']; 

	public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function documentType()
    {
        return $this->belongsTo('App\DocumentType');
    }

    public function studyLevel()
    {
        return $this->belongsTo('App\StudyLevel');
    }

    public function profession()
    {
        //return $this->belongsToMany('App\Profession', 'person_profession')->withTimestamps();
        return $this->belongsTo('App\Profession');
    }

    public function interests()
    {
        return $this->belongsToMany('App\Interest', 'interest_person')->withTimestamps();
    }

    public function accounts()
    {
        return $this->belongsToMany('App\Account', 'account_person')
            ->withPivot('name')
            ->withTimestamps();
    }

    public function jobs()
    {
        return $this->belongsToMany('App\Job', 'job_person')->withTimestamps();
    }

     public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function province()
    {
        return $this->belongsTo('App\Province');
    }
    
    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function events()
    {
        return $this->belongsToMany('App\Event', 'event_person')->withTimestamps();
    }

    public function activities()
    {
        return $this->belongsToMany('App\Activity', 'activity_person')->withTimestamps();
    } 
}

