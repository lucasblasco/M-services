<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    // nombre correspondiente a la table en la base de datos
    protected $table = 'events';
    // Atributos que se pueden asignar de manera masiva.
	protected $fillable = array('name' ,'start_date' ,'description' ,'days_duration' ,'hours_day' ,'start_hour' ,'event_city_id' ,'event_province_id' ,'event_country_id' ,'event_place' ,'include_nearby_places' ,'number_of_attendees', 'number_of_rooms', 'assistant_activities_id' ,'include_logo' ,'include_slide' ,'include_screen' ,'include_banners' ,'include_flyers' ,'send_invitations_by_mail' ,'analitycs_segment_audience' ,'analitycs_inbound_marketing' ,'analitycs_analyze_scenarios' ,'analitycs_incident_monitoring' ,'analitycs_analyze_results', 'status_id');
	// Aquí ponemos los campos que no queremos que se devuelvan en las consultas.
	protected $hidden = ['created_at','updated_at']; 

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

    public function ageRanges()
    {
        return $this->belongsToMany('App\AgeRange', 'age_range_event')->withTimestamps();
    }

    public function interests()
    {
        return $this->belongsToMany('App\Interest', 'event_interest')->withTimestamps();
    }

    public function assistantActivities()
    {
        return $this->belongsTo('App\AssistantActivity');
    }

    public function activity(){
       return $this->HasMany('App\Activity');
    }
}
