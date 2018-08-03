<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Event;
use App\Mail\InscriptionsActivityList;
use App\Mail\InscriptionsEventList;
use App\Mail\SubscribeCoffeeActivity;
use App\Mail\SubscribeCongressActivity;
use App\Mail\SubscribeEvent;
use App\Mail\SubscribeSummitActivity;
use App\Mail\SubscribeWorkshopActivity;
use App\Person;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class UserController extends Controller
{
    public function getObject(User $user): User
    {
        $user->person;
        $user->organization;
        $user->studyLevel;
        $user->profession;
        $user->interests;
        $user->accounts;

        if ($user->person) {
            $user->person->studyLevel;
            $user->person->profession;
            $user->person->country;
            $date                     = new Carbon($user->person->birth_date);
            $user->person->birth_date = $date->toDateString();
        }

        if ($user->organization) {
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
        $event    = Event::find($event_id);

        if (!is_null($user->events)) {
            foreach ($user->events as $event) {
                if ($event->id == $event_id) {
                    return $this->sendError('Ya se encuentra participando del evento.');
                }

            }
        }

        $user->events()->attach($event_id);

        Mail::to($user->email)->send(new SubscribeEvent($user->name, $event->name));

        $user = User::find(2);
        Mail::to($user->email)
            ->send(new InscriptionsEventList($event->name, $this->getInscriptions()));

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

        if ($activity->event_format_id == 5) {
            $user->activities()->attach([$activity_id => ['id_status_coffee' => 1]]);
            Mail::to($user->email)->send(new SubscribeCoffeeActivity($user->name, $event->name));
        } else {
            $user->activities()->attach($activity_id);
        }

        if ($activity->event_format_id == 1) {
            Mail::to($user->email)->send(new SubscribeCongressActivity($user->name, $event->name, $activity->name));
        }

        if ($activity->event_format_id == 2) {
            Mail::to($user->email)->send(new SubscribeWorkshopActivity($user->name, $event->name, $activity->name));
        }

        if ($activity->event_format_id == 3) {
            Mail::to($user->email)->send(new SubscribeSummitActivity($user->name, $event->name));
        }

        $user = User::find(2);
        Mail::to($user->email)
            ->send(new InscriptionsActivityList($activity->name, $activity->eventFormat->name, $this->getInscriptionsActivity($activity->id)));

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
        Log::info($activity_id);
        $activity = Activity::find($activity_id);
        Log::info($activity);

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

        //$activities = $user->activities()->get();
        $activities = Activity::join('activity_user', 'activities.id', '=', 'activity_user.activity_id')
            ->where('activity_user.user_id', '=', $user->id)
            ->where('activities.event_id', '=', $event_id)
            ->orderBy('activities.start_time', 'asc')
            ->select('activities.id', 'name', 'description', 'event_id', 'room_id', 'event_format_id', 'day', 'start_time', 'end_time', 'status_id')
            ->get();

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

        return $this->sendResponse($activities, 'Actividad de la persona');
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

    public function getInscriptions()
    {
        return $users = DB::table('users')
            ->join('event_user', 'users.id', '=', 'event_user.user_id')
            ->leftJoin('persons', 'users.id', '=', 'persons.user_id')
            ->where('users.id', '<>', 1)
            ->where('event_user.event_id', '=', 1)
            ->select('persons.name as name', 'persons.surname as surname', 'persons.phone as phone', 'users.email as email')
            ->get();
    }

    public function getInscriptionsActivity($id)
    {
        return $users = DB::table('users')
            ->join('activity_user', 'users.id', '=', 'activity_user.user_id')
            ->leftJoin('persons', 'users.id', '=', 'persons.user_id')
            ->where('users.id', '<>', 1)
            ->where('activity_user.activity_id', '=', $id)
            ->select('persons.name as name', 'persons.surname as surname', 'persons.phone as phone', 'users.email as email')
            ->get();
    }

    public function getInscriptionsCount()
    {
        return $user = DB::table('event_user')
            ->where('user_id', '<>', 1)
            ->where('event_user.event_id', '=', 1)
            ->count();
    }

    public function changeAvatar(Request $request){
        $input = $request->all();

        $user = JWTAuth::authenticate($request->bearerToken());
        if (is_null($user)) {
            return $this->sendError('usuario no autorizado.');
        }

        if (!$request->file('avatar')->isValid()){
            return $this->sendError('El archivo no es valido');
        }        
       
        $path = $this->saveFile($request, $user);
        $person = Person::find($user->person->id);
        $person->avatar = $path;


        //guardo 
        $person->save();

        return $this->sendResponse(null, 'Avatar cargado correctamente');
    }

    public function saveFile(Request $request, User $user) : string {
        $guessExtension = $request->file('avatar')->guessExtension();
        $time = str_replace(' ', '_', Carbon::now());
        $time = str_replace(':', '_', $time);
        $time = str_replace('-', '_', $time);
        $file = $request->file('avatar')->storeAs('public/avatar', $user->id.'.'.$time.'.'.$guessExtension);

        return $file;
    }
}
