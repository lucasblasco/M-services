<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    // nombre correspondiente a la table en la base de datos
    protected $table = 'persons';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name', 'surname', 'birth_date', 'document_type_id', 'document_number', 'phone', 'cellphone', 'email', 'study_level_id', 'user_id', 'country_id', 'province_id', 'city_id');
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

    public function professions()
    {
        return $this->belongsToMany('App\Profession', 'person_profession')->withTimestamps();
    }

    public function interests()
    {
        return $this->belongsToMany('App\Interest', 'interest_person')->withTimestamps();
    }

    public function accounts()
    {
        return $this->belongsToMany('App\Account', 'account_person')->withTimestamps();
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
}

