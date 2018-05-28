<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function index()
    {
        $obj = Event::all();
        return response()->json([
            'status'=>true, 
            'message'=>"success", 
            'data'=>$obj
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$request->input('name') || 
            !$request->input('start_date') || 
            !$request->input('description') || 
            !$request->input('days_duration') || 
            !$request->input('hours_day') || 
            !$request->input('start_hour') || 
            !$request->input('event_city_id') || 
            !$request->input('event_country_id') || 
            !$request->input('event_place') || 
            !$request->input('include_nearby_places') || 
            !$request->input('number_of_attendees') || 
            !$request->input('number_of_rooms') || 
            !$request->input('assistant_activities_id') || 
            !$request->input('include_logo') || 
            !$request->input('include_slide') || 
            !$request->input('include_screen') || 
            !$request->input('include_banners') || 
            !$request->input('include_flyers') || 
            !$request->input('send_invitations_by_mail') || 
            !$request->input('analitycs_segment_audience') || 
            !$request->input('analitycs_inbound_marketing') || 
            !$request->input('analitycs_analyze_scenarios') || 
            !$request->input('analitycs_incident_monitoring') || 
            !$request->input('analitycs_analyze_results'))
        {
            Log::critical('Error 422: No se pudo crear el evento. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }

        $newObj=Event::create($request->all());
        Log::info('Create evento: '.$newObj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(Event $event)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$person
        ], 200);
    }

    public function update(Request $request, Event $event)
    {
        $name = $request->input('name'); 
        $startDate = $request->input('start_date'); 
        $description = $request->input('description'); 
        $daysDuration = $request->input('days_duration'); 
        $hoursDay = $request->input('hours_day'); 
        $startHour = $request->input('start_hour'); 
        $eventCityId = $request->input('event_city_id'); 
        $eventProvinceId = $request->input('event_province_id'); 
        $eventCountryId = $request->input('event_country_id'); 
        $eventPlace = $request->input('event_place'); 
        $includeNearbyPlaces = $request->input('include_nearby_places'); 
        $numberOfAttendees = $request->input('number_of_attendees');
        $numberOfRooms = $request->input('number_of_rooms'); 
        $assistantActivitiesId = $request->input('assistant_activities_id');
        $logo = $request->input('include_logo'); 
        $slide = $request->input('include_slide'); 
        $screen = $request->input('include_screen'); 
        $banners = $request->input('include_banners'); 
        $flyers = $request->input('include_flyers'); 
        $sendInvitationsByMail = $request->input('send_invitations_by_mail'); 
        $analitycsSegmentAudience = $request->input('analitycs_segment_audience'); 
        $analitycsInboundMarketing = $request->input('analitycs_inbound_marketing'); 
        $analitycsAnalyzeScenarios = $request->input('analitycs_analyze_scenarios'); 
        $analitycsIncidentMonitoring = $request->input('analitycs_incident_monitoring'); 
        $analitycsAnalyzeResults = $request->input('analitycs_analyze_results');
        $statusId = $request->input('status_id');
            
        if ($request->method() === 'PATCH') {
            $band = false;
            if ($name){
                $event->name = $name;
                $band=true;
            }
            if ($startDate){
                $event->start_date = $startDate;
                $band=true;
            }
            if ($daysDuration){
                $event->days_duration = $daysDuration;
                $band=true;
            }
            if ($hoursDay){
                $event->hours_day = $hoursDay;
                $band=true;
            }
            if ($startHour){
                $event->start_hour = $startHour;
                $band=true;
            }
            if ($eventCityId){
                $event->event_city_id = $eventCityId;
                $band=true;
            }
            if ($eventProvinceId){
                $event->event_province_id = $eventProvinceId;
                $band=true;
            }
            if ($eventCountryId){
                $event->event_country_id = $eventCountryId;
                $band=true;
            }
            if ($eventPlace){
                $event->event_place = $eventPlace;
                $band=true;
            }
            if ($includeNearbyPlaces){
                $event->include_nearby_places = $includeNearbyPlaces;
                $band=true;
            }
            if ($numberOfAttendees){
                $event->number_of_attendees = $numberOfAttendees;
                $band=true;
            }
            if ($numberOfRooms){
                $event->number_of_rooms = $numberOfRooms;
                $band=true;
            }
            if ($assistantActivitiesId){
                $event->assistant_activities_id = $assistantActivitiesId;
                $band=true;
            }
            if ($logo){
                $event->logo = $logo;
                $band=true;
            }
            if ($slide){
                $event->slide = $slide;
                $band=true;
            }
            if ($screen){
                $event->screen = $screen;
                $band=true;
            }
            if ($banners){
                $event->banners = $banners;
                $band=true;
            }
            if ($flyers){
                $event->flyers = $flyers;
                $band=true;
            }
            if ($sendInvitationsByMail){
                $event->send_invitations_by_mail = $sendInvitationsByMail;
                $band=true;
            }
            if ($analitycsSegmentAudience){
                $event->analitycs_segment_audience = $analitycsSegmentAudience;
                $band=true;
            }
            if ($analitycsInboundMarketing){
                $event->analitycs_analyze_scenarios = $analitycsInboundMarketing;
                $band=true;
            }
            if ($analitycsAnalyzeScenarios){
                $event->send_invitations_by_mail = $analitycsAnalyzeScenarios;
                $band=true;
            }
            if ($analitycsIncidentMonitoring){
                $event->analitycs_incident_monitoring = $analitycsIncidentMonitoring;
                $band=true;
            }
            if ($analitycsAnalyzeResults){
                $event->analitycs_analyze_results = $analitycsAnalyzeResults;
                $band=true;
            }
            if ($statusId){
                $event->status_id = $statusId;
                $band=true;
            }
           
            if ($band){
                $event->save();
                Log::info('Update evento: '.$event->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$event
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar el evento. Parametro: '.$event->name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }
        if (!$name                        || 
            !$startDate                   || 
            !$description                 || 
            !$daysDuration                || 
            !$hoursDay                    || 
            !$startHour                   || 
            !$eventCityId                 || 
            !$eventProvinceId             || 
            !$eventCountryId              || 
            !$eventPlace                  || 
            !$encludeNearbyPlaces         || 
            !$numberOfAttendees           || 
            !$numberOfRooms               ||
            !$assistantActivitiesId       ||
            !$logo                        || 
            !$slide                       || 
            !$screen                      || 
            !$banners                     || 
            !$flyers                      || 
            !$sendInvitationsByMail       || 
            !$analitycsSegmentAudience    || 
            !$analitycsInboundMarketing   || 
            !$analitycsAnalyzeScenarios   || 
            !$analitycsIncidentMonitoring || 
            !$statusId                    || 
            !$analitycsAnalyzeResults) {
            Log::critical('Error 422: No se pudo actualizar el evento. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }
        $event->name = $name;
        $event->start_date = $startDate;
        $event->days_duration = $daysDuration;
        $event->hours_day = $hoursDay;
        $event->start_hour = $startHour;
        $event->event_city_id = $eventCityId;
        $event->event_province_id = $eventProvinceId;
        $event->event_country_id = $eventCountryId;
        $event->event_place = $eventPlace;
        $event->include_nearby_places = $includeNearbyPlaces;
        $event->number_of_attendees = $numberOfAttendees;
        $event->number_of_rooms = $numberOfRooms;
        $event->assistant_activities_id = $assistantActivitiesId;
        $event->logo = $logo;
        $event->slide = $slide;
        $event->screen = $screen;
        $event->banners = $banners;
        $event->flyers = $flyers;
        $event->send_invitations_by_mail = $sendInvitationsByMail;
        $event->analitycs_segment_audience = $analitycsSegmentAudience;
        $event->analitycs_analyze_scenarios = $analitycsInboundMarketing;
        $event->send_invitations_by_mail = $analitycsAnalyzeScenarios;
        $event->analitycs_incident_monitoring = $analitycsIncidentMonitoring;
        $event->analitycs_analyze_results = $analitycsAnalyzeResults;        
        $event->status_id = $statusId;        
        $event->save();
        Log::info('Update evento: '.$event->id);
        return response()->json([
            "status"=>true, 
            "message"=>$event
        ], 200);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        Log::info('Delete evento: '.$event->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
