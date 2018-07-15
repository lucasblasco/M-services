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
use Carbon\Carbon;

class UserController extends Controller
{
    function getObject(User $user) : User {
        $user->person;
        $user->organization;
        $user->studyLevel;
        $user->profession;
        $user->interests;
        $user->accounts;

        if($user->person){
            $user->person->studyLevel;
            $user->person->profession;
            $user->person->country;
            $date = new Carbon($user->person->birth_date);
            $user->person->birth_date = $date->toDateString();
        }

        if($user->organization){
            $user->organization->country;
        }

        return $user;
    }


    public function index()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user = $this->getObject($user);
        }
        return $this->sendResponse($users, 'Usuarios recuperadas correctamente');
    }

    public function show(User $user)
    {
        return $this->sendResponse($this->getObject($user), 'Usuario recuperado correctamente');      
    }

    public function subscribeEvent(Request $request)
    {
        $input = $request->all();

        $user = JWTAuth::authenticate($request->bearerToken());
        if (is_null($user)) {
            return $this->sendError('usuario no autorizado.');
        }

        $validatedData = Validator::make($input, [
            'event_id' => 'required',
        ], [
            'event_id.required' => 'El evento es requerido',
        ]);       

        if ($validatedData->fails()) {
            return $this->sendError('Error de validacion.', $validatedData->errors());
            Log::error($validatedData->errors());
            return;
        }

        $event_id = $input['event_id'];

        if (!is_null($user->events)) {
            foreach ($user->events as $event) {
                if ($event->id == $event_id) {
                    return $this->sendError('Ya se encuentra participando del evento.');
                }

            }
        }

        $user->events()->attach($event_id);
        return $this->sendResponse(null, '¡Ya estás participando del evento!');
    }

    public function unsubscribeEvent(Request $request)
    {
        $input = $request->all();

        $user = JWTAuth::authenticate($request->bearerToken());
        if (is_null($user)) {
            return $this->sendError('usuario no autorizado.');
        }

        $validatedData = Validator::make($input, [
            'event_id' => 'required',
        ], [
            'event_id.required' => 'El evento es requerido',
        ]);       

        if ($validatedData->fails()) {
            return $this->sendError('Error de validacion.', $validatedData->errors());
            Log::error($validatedData->errors());
            return;
        }

        $event_id = $input['event_id'];

        if (!is_null($user->events)) {
            foreach ($user->events as $event) {
                if ($event->id == $event_id) {
                    $user->events()->detach($event_id);
                    $activities = $event->activities;
                    $user->activities()->detach($activities);
                    return $this->sendResponse(null, 'Dejaste de participar del evento.');
                }
            }
        }
        return $this->sendError('No se encuentra inscripto en el evento.');
    }

    public function subscribeActivity(Request $request)
    {
        $input = $request->all();

        $user = JWTAuth::authenticate($request->bearerToken());
        if (is_null($user)) {
            return $this->sendError('usuario no autorizado.');
        }

        $validatedData = Validator::make($input, [
            'activity_id' => 'required',
        ], [
            'activity_id.required' => 'La actividad es requerida',
        ]);
        
        if ($validatedData->fails()) {
            return $this->sendError('Error de validacion.', $validatedData->errors());
            Log::error($validatedData->errors());
            return;
        }

        $activity_id = $input['activity_id'];

        $activity = Activity::find($activity_id);

        $event = $activity->event()->first();

        if (is_null($user->events)) {
            return $this->sendError('Debe inscribirse al evento para asistir a una actividad');
        }

        $existEvent = false;
        foreach ($user->events as $ev) {
            if ($ev->id == $event->id) {
                $existEvent = true;
            }

        }

        if (!$existEvent) {
            return $this->sendError('Debe inscribirse al evento para asistir a una actividad');
        }

        if (!is_null($user->activities)) {
            foreach ($user->activities as $act) {
                if ($act->id == $activity_id) {
                    return $this->sendError('Ya se encuentra inscripto en la actividad.');
                }

            }
        }

        $user->activities()->attach($activity_id);

        return $this->sendResponse(null, '¡Ya estás inscripto en la actividad!');
    }

    public function unsubscribeActivity(Request $request)
    {
        $input = $request->all();

        $user = JWTAuth::authenticate($request->bearerToken());
        if (is_null($user)) {
            return $this->sendError('usuario no autorizado.');
        }

        $validatedData = Validator::make($input, [
            'activity_id' => 'required',
        ], [
            'activity_id.required' => 'La actividad es requerida',
        ]);        

        if ($validatedData->fails()) {
            return $this->sendError('Error de validacion.', $validatedData->errors());
            Log::error($validatedData->errors());
            return;
        }

        $activity_id = $input['activity_id'];

        $activity = Activity::find($activity_id);

        $event = $activity->event()->first();

        if (is_null($user->events)) {
            return $this->sendError('No se encuentra inscripto en el evento');
        }

        $existEvent = false;
        foreach ($user->events as $ev) {
            if ($ev->id == $event->id) {
                $existEvent = true;
            }

        }

        if (!$existEvent) {
            return $this->sendError('No se encuentra inscripto en el evento');
        }

        if (!is_null($user->activities)) {
            foreach ($user->activities as $act) {
                if ($act->id == $activity_id) {
                    $user->activities()->detach($activity_id);
                    return $this->sendResponse(null, 'Dejaste de participar de la actividad.');
                }
            }
        }
        return $this->sendError('No se encuentra inscripto en la actividad.');
    }

    public function activitiesPerson(Request $request)
    {
        $input = $request->all();

        $user = JWTAuth::authenticate($request->bearerToken());
        if (is_null($user)) {
            return $this->sendError('usuario no autorizado.');
        }

        $validatedData = Validator::make($input, [
            'event_id' => 'required',
        ], [
            'event_id.required' => 'El evento es requerido',
        ]);      

        if ($validatedData->fails()) {
            return $this->sendError('Error de validacion.', $validatedData->errors());
            Log::error($validatedData->errors());
            return;
        }

        $event_id = $input['event_id'];

        if (is_null($user->events)) {
            return $this->sendError('La persona no se inscribio al evento');
        }

        $existEvent = false;
        foreach ($user->events as $ev) {
            if ($ev->id == $event_id) {
                $existEvent = true;
            }

        }

        if (!$existEvent) {
            return $this->sendError('La persona no se inscribio al evento');
        }

        $activities = $user->activities()->get();

        if (!is_null($activities)) {
            foreach ($activities as $act) {
                $act->speakers;
                $act->status;
                $act->eventFormat;
                $act->room;
                $act->start_time = substr($act->start_time, 0, 5);
                $act->end_time   = substr($act->end_time, 0, 5);
            }
        }

        Log::info($activities->where('event_id', '=', $event_id));
        return $this->sendResponse($activities->where('event_id', '=', $event_id), 'Actividad de la persona');
    }

    public function eventPerson(Request $request)
    {
        $input = $request->all();

        $user = JWTAuth::authenticate($request->bearerToken());
        if (is_null($user)) {
            return $this->sendError('usuario no autorizado.');
        }

        if (is_null($user->events)) {
            return $this->sendError('La persona no se inscribio a ningun evento');
        }

        $events = $user->events()->get();

        foreach ($events as $event) {
            $event->accounts;
            $event->status;
            $event->organizers;
            $event->partners;
        }
        return $this->sendResponse($events, 'Eventos recuperados correctamente');
    }

    public function isParticipatingEvent(Request $request)
    {
        $input = $request->all();

        $user = JWTAuth::authenticate($request->bearerToken());
        if (is_null($user)) {
            return $this->sendError('usuario no autorizado.');
        }

        $validatedData = Validator::make($input, [
            'event_id' => 'required',
        ], [
            'event_id.required' => 'El evento es requerido',
        ]);

        $event_id = $input['event_id'];

        if ($validatedData->fails()) {
            return $this->sendError('Error de validacion.', $validatedData->errors());
            Log::error($validatedData->errors());
            return;
        }

        if (is_null($user->events)) {
            return $this->sendError('La persona no se inscribio al evento');
        }

        $existEvent = false;
        foreach ($user->events as $ev) {
            if ($ev->id == $event_id) {
                return $this->sendResponse(null, 'La persona esta participando del evento');
            }

        }
        return $this->sendError('La persona no esta participando del evento');
    }

    public function get(Request $request)
    {
        $user = JWTAuth::authenticate($request->bearerToken());
        if (is_null($user)) {
            return $this->sendError('usuario no autorizado.');
        }
        return $this->sendResponse($this->getObject($user), 'Usuario recuperado correctamente');
    }
}
