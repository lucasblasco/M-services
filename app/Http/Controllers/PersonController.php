<?php

namespace App\Http\Controllers;

use App\Person;
use App\User;
use App\Event;
use App\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;

class PersonController extends Controller
{
    public function index()
    {
        $persons = Person::all();
        foreach ($persons as $person){
            $person->studyLevel; 
            $person->profession;
            $person->interests;
            $person->accounts;
        }
        return $this->sendResponse($persons, 'Personas recuperadas correctamente');
    }

    public function store(Request $request)
    {
       if ( !$request->input('name') ||
            !$request->input('surname') ||
            !$request->input('documento_type_id') ||
            !$request->input('document_number') ||
            !$request->input('birth_date') ||
            !$request->input('email'))
        {
            Log::critical('Error 422: No se pudo crear la persona. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }

        $newObj=Person::create($request->all());
        Log::info('Create persona: '.$newObj->id);
        return $this->sendResponse($newObj, 'Registro creado correctamente');
    }

    public function show(Person $person)
    {   
        $person->studyLevel; 
        $person->profession;
        $person->interests;
        $person->accounts;
        $person->user;

        return $this->sendResponse($person, 'Persona recuperada correctamente');
    }

    public function update(Request $request, Person $person)
    {
        $name = $request->input('name');
        $surname = $request->input('surname');
        $birthDate = $request->input('birth_date');
        $documentTypeId = $request->input('document_type_id');
        $studyLevelId = $request->input('study_level_id');
        $phone = $request->input('phone');
        $documentNumber = $request->input('document_number');
        $cellphone = $request->input('cellphone');
        $email = $request->input('email');
        $cityId = $request->input('city_id');
        $provinceId = $request->input('province_id');
        $countryId = $request->input('country_id');
        $postalCode = $request->input('postal_code');
        $userId = $request->input('user_id');
            
        if ($request->method() === 'PATCH') {
            $band = false;
            if ($name){
                $person->name = $name;
                $band=true;
            }
            if ($surname){
                $person->surname = $surname;
                $band=true;
            }
            if ($birthDate){
                $person->birthDate = $birthDate;
                $band=true;
            }
            if ($documentTypeId){
                $person->documentTypeId = $documentTypeId;
                $band=true;
            }
            if ($studyLevelId){
                $person->studyLevelId = $studyLevelId;
                $band=true;
            }
            if ($phone){
                $person->phone = $phone;
                $band=true;
            }
            if ($documentNumber){
                $person->documentNumber = $documentNumber;
                $band=true;
            }
            if ($cellphone){
                $person->cellphone = $cellphone;
                $band=true;
            }
            if ($email){
                $person->email = $email;
                $band=true;
            }
            if ($userId){
                $person->userId = $userId;
                $band=true;
            }
            if ($cityId){
                $person->cityId = $cityId;
                $band=true;
            }
            if ($provinceId){
                $person->provinceId = $provinceId;
                $band=true;
            }
            if ($countryId){
                $person->countryId = $countryId;
                $band=true;
            }
            if ($postalCode){
                $person->postal_code = $postalCode;
                $band=true;
            }
            if ($band){
                $person->save();
                Log::info('Update persona: '.$person->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$person
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar la persona. Parametro: '.$person->name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }
        if (!$name || 
            !$surname ||
            !$birthDate ||
            !$documentTypeId ||
            !$studyLevelId ||
            !$phone || 
            !$documentNumber ||
            !$cellphone ||
            !$cityId ||
            !$provinceId ||
            !$countryId ||
            !$postalCode ||
            !$email ||
            !$userId) {
            Log::critical('Error 422: No se pudo actualizar la persona. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }
        $person->name = $name;
        $person->surname = $surname;
        $person->birthDate = $birthDate;
        $person->documentTypeId = $documentTypeId;
        $person->studyLevelId = $studyLevelId;
        $person->phone = $phone;
        $person->documentNumber = $documentNumber;
        $person->cellphone = $cellphone;
        $person->email = $email;
        $person->userId = $userId;
        $person->cityId = $cityId;
        $person->provinceId = $provinceId;
        $person->countryId = $countryId;
        $person->postal_code = $postalCode;
        $person->save();
        Log::info('Update persona: '.$person->id);
        return response()->json([
            "status"=>true, 
            "message"=>$person
        ], 200);
    }

    public function destroy(Person $person)
    {
        $person->delete();
        Log::info('Delete persona: '.$person->id);
        return $this->sendResponse($person, 'Registro eliminado correctamente');
    }

    public function subscribeEvent(Request $request){
        $input = $request->all();     

        $currentUser = JWTAuth::authenticate($request->bearerToken());
        if(is_null($currentUser))
            return $this->sendError('usuario no autorizado.');

        $validatedData = Validator::make($input, [
            'event_id' => 'required',
        ],[
            'event_id.required' => 'El evento es requerido'
        ]);

        $event_id = $input['event_id'];

        if($validatedData->fails()){
            return $this->sendError('Error de validacion.', $validatedData->errors());   
            Log::error($validatedData->errors());
            return;
        }
        
        $person = new Person();
        $person = $currentUser->person()->first();        

       if(!is_null($person->events)){
          foreach ($person->events as $event) {
                if($event->id == $event_id )
                    return $this->sendError('Ya se encuentra participando del evento.'); 
            }
        }

        $person->events()->attach($event_id);
        return $this->sendResponse(null, '¡Ya estás participando del evento!');
    }

    public function unsubscribeEvent(Request $request){
        $input = $request->all();     

        $currentUser = JWTAuth::authenticate($request->bearerToken());
        if(is_null($currentUser))
            return $this->sendError('usuario no autorizado.');

        $validatedData = Validator::make($input, [
            'event_id' => 'required',
        ],[
            'event_id.required' => 'El evento es requerido'
        ]);

        $event_id = $input['event_id'];

        if($validatedData->fails()){
            return $this->sendError('Error de validacion.', $validatedData->errors());   
            Log::error($validatedData->errors());
            return;
        }
        
        $person = new Person();
        $person = $currentUser->person()->first();        

       if(!is_null($person->events)){
          foreach ($person->events as $event) {
                if($event->id == $event_id ){
                    $person->events()->detach($event_id);
                    return $this->sendResponse(null, 'Dejaste de participar del evento.');
                }
            }
        }
        return $this->sendError('No se encuentra inscripto en el evento.'); 
    }

    public function subscribeActivity(Request $request){
        $input = $request->all();     

        $currentUser = JWTAuth::authenticate($request->bearerToken());
        if(is_null($currentUser))
            return $this->sendError('usuario no autorizado.');

        $validatedData = Validator::make($input, [
            'activity_id' => 'required',
        ],[
            'activity_id.required' => 'La actividad es requerida'
        ]);

        $activity_id = $input['activity_id'];

        if($validatedData->fails()){
            return $this->sendError('Error de validacion.', $validatedData->errors());   
            Log::error($validatedData->errors());
            return;
        }
        
        
        $activity = Activity::find($activity_id);
        
        $event = $activity->event()->first();

        $person = new Person();
        $person = $currentUser->person()->first(); 
           

       if(is_null($person->events))
            return $this->sendError('Debe inscribirse al evento para asistir a una actividad'); 

        $existEvent = false;
        foreach ($person->events as $ev) {
                if($ev->id == $event->id )
                    $existEvent = true;
        }

        if(!$existEvent)
            return $this->sendError('Debe inscribirse al evento para asistir a una actividad'); 

        if(!is_null($person->activities)){
          foreach ($person->activities as $act) {
                if($act->id == $activity_id )
                    return $this->sendError('Ya se encuentra inscripto en la actividad.'); 
            }
        }

        $person->activities()->attach($activity_id);

        return $this->sendResponse(null, '¡Ya estás inscripto en la actividad!');
    }

    public function unsubscribeActivity(Request $request){
        $input = $request->all();     

        $currentUser = JWTAuth::authenticate($request->bearerToken());
        if(is_null($currentUser))
            return $this->sendError('usuario no autorizado.');

        $validatedData = Validator::make($input, [
            'activity_id' => 'required',
        ],[
            'activity_id.required' => 'La actividad es requerida'
        ]);

        $activity_id = $input['activity_id'];

        if($validatedData->fails()){
            return $this->sendError('Error de validacion.', $validatedData->errors());   
            Log::error($validatedData->errors());
            return;
        }
        
        
        $activity = Activity::find($activity_id);
        
        $event = $activity->event()->first();

        $person = new Person();
        $person = $currentUser->person()->first(); 
           

       if(is_null($person->events))
            return $this->sendError('No se encuentra inscripto en el evento'); 

        $existEvent = false;
        foreach ($person->events as $ev) {
                if($ev->id == $event->id )
                    $existEvent = true;
        }

        if(!$existEvent)
            return $this->sendError('No se encuentra inscripto en el evento'); 

        if(!is_null($person->activities)){
          foreach ($person->activities as $act) {
                if($act->id == $activity_id ){
                    $person->activities()->detach($activity_id);
                    return $this->sendResponse(null, 'Dejaste de participar de la actividad.');
                }
            }
        }
        return $this->sendError('No se encuentra inscripto en la actividad.'); 
    }

    public function activitiesPerson(Request $request){
        $input = $request->all();     

        $currentUser = JWTAuth::authenticate($request->bearerToken());
        if(is_null($currentUser))
            return $this->sendError('usuario no autorizado.');

        $validatedData = Validator::make($input, [
            'event_id' => 'required',
        ],[
            'event_id.required' => 'El evento es requerido'
        ]);

        $event_id = $input['event_id'];

        if($validatedData->fails()){
            return $this->sendError('Error de validacion.', $validatedData->errors());   
            Log::error($validatedData->errors());
            return;
        }
        
        $person = new Person();
        $person = $currentUser->person()->first(); 
           

       if(is_null($person->events))
            return $this->sendError('La persona no se inscribio al evento'); 

        $existEvent = false;
        foreach ($person->events as $ev) {
                if($ev->id == $event_id )
                    $existEvent = true;
        }

        if(!$existEvent)
            return $this->sendError('La persona no se inscribio al evento'); 

        $activities = $person->activities()->get();

        if(!is_null($activities)){            
          foreach ($activities as $act) {
                $act->speakers; 
                $act->status;
                $act->eventFormat;
                $act->room;
                $act->start_time = substr($act->start_time, 0, 5);
                $act->end_time = substr($act->end_time, 0, 5);
            }
        }

        Log::info($activities->where('event_id', '=', $event_id));
        return $this->sendResponse($activities->where('event_id', '=', $event_id), 'Actividad de la persona');
    }

    public function eventPerson(Request $request){
        $input = $request->all();     

        $currentUser = JWTAuth::authenticate($request->bearerToken());
        if(is_null($currentUser))
            return $this->sendError('usuario no autorizado.');

        $validatedData = Validator::make($input, [
            'event_id' => 'required',
        ],[
            'event_id.required' => 'El evento es requerido'
        ]);

        $event_id = $input['event_id'];

        if($validatedData->fails()){
            return $this->sendError('Error de validacion.', $validatedData->errors());   
            Log::error($validatedData->errors());
            return;
        }
        
        $person = new Person();
        $person = $currentUser->person()->first(); 
           

       if(is_null($person->events))
            return $this->sendError('La persona no se inscribio a ningun evento'); 

        $events = $person->events()->get();
          
        foreach ($events as $event) {
            $event->accounts; 
            $event->status;
            $event->organizers;
            $event->partners;
        }
        return $this->sendResponse($events, 'Eventos recuperados correctamente');
    }

    public function isParticipatingEvent(Request $request){
        $input = $request->all();     

        $currentUser = JWTAuth::authenticate($request->bearerToken());
        if(is_null($currentUser))
            return $this->sendError('usuario no autorizado.');

        $validatedData = Validator::make($input, [
            'event_id' => 'required',
        ],[
            'event_id.required' => 'El evento es requerido'
        ]);

        $event_id = $input['event_id'];

        if($validatedData->fails()){
            return $this->sendError('Error de validacion.', $validatedData->errors());   
            Log::error($validatedData->errors());
            return;
        }
        
        $person = new Person();
        $person = $currentUser->person()->first(); 
           

       if(is_null($person->events))
            return $this->sendError('La persona no se inscribio al evento'); 

        $existEvent = false;
        foreach ($person->events as $ev) {
                if($ev->id == $event_id )
                    return $this->sendResponse(null, 'La persona esta participando del evento');
        }
        return $this->sendError('La persona no esta participando del evento');
    }

    public function get(Request $request)
    {  
        $currentUser = JWTAuth::authenticate($request->bearerToken());
        if(is_null($currentUser))
            return $this->sendError('usuario no autorizado.');

        $person = new Person();
        $person = $currentUser->person()->first();        

        $person->studyLevel; 
        $person->profession;
        $person->interests;
        $person->accounts;
        $person->user;

        return $this->sendResponse($person, 'Persona recuperada correctamente');
    }
}
