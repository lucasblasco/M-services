<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Event;
use App\CoffeeInvitation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

use App\Mail\InvitationCoffee;
use App\Mail\AcceptCoffeeInvitation;
use Illuminate\Support\Facades\Mail;

class CoffeeInvitationController extends Controller
{
    public function coffeeParticipants(Request $request)
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

        $activity = Activity::find($input['activity_id']);

        //Elimino los caducados
        $this->updateInvitationTimedOut();

        /*$user_list = User::join('activity_user', 'activity_user.user_id', '=', 'users.id')
            ->join('activities', 'activity_user.activity_id', '=', 'activities.id')
            ->join('persons', 'persons.user_id', '=', 'users.id')
            ->leftJoin('coffee_statuses', 'coffee_statuses.id', '=', 'activity_user.id_status_coffee')
            ->where('activity_user.activity_id', '=', $activity_id)
            ->orderBy('activity_user.id_status_coffee', 'asc')
            ->select('users.id as user_id', 'persons.avatar as avatar', 'persons.name as name', 'persons.surname as surname', 'coffee_statuses.id as status_id', 'coffee_statuses.name as status')
            ->get();*/
         $user_list = DB::select('
            select DISTINCT 
                u.id as user_id, 
                p.avatar as avatar, 
                p.name as name, 
                p.surname as surname, 
                s.id as status_id, 
                s.name as status
            from activity_user au
                JOIN users u 
                    on au.user_id = u.id
                join persons p
                    on u.id = p.user_id
                left join coffee_statuses s
                    on s.id = au.id_status_coffee
            where 
                au.activity_id = ? AND
                u.id != ? AND
                u.id not in (select u.id as user_id
                        from coffee_invitations i
                        join coffee_statuses s on i.id_status_coffee = s.id
                        join activity_user au on i.id_activity_user_send = au.id
                        join users u on u.id = au.user_id
                        left join persons p on p.user_id = u.id
                        where i.id_user_receive = ?
                            and i.timed_out = 0

                        union

                        select  u.id as user_id
                        from coffee_invitations i
                        join coffee_statuses s on i.id_status_coffee = s.id
                        join activity_user au on i.id_activity_user_send = au.id and au.user_id = ?
                        join users u on u.id = i.id_user_receive
                        left join persons p on p.user_id = u.id
                        where i.id_user_receive != ?
                            and i.timed_out = 0)

            union

            select e.user_id as user_id, 
                   p.avatar as avatar, 
                   p.name as name, 
                   p.surname as surname, 
                   1 as status_id, 
                   \'Disponible\' as status 
            from event_user e
                join persons p
                    on e.user_id = p.user_id
            where e.user_id not in (select user_id from activity_user where activity_id = 1)
                and e.user_id != ?
                and  e.user_id not in (select id_user_receive from coffee_invitations)
            ',
         [
            $activity->id, 
            $user->id,
            $user->id,
            $user->id, 
            $user->id,
            $user->id
        ]);
        return $this->sendResponse($user_list, 'Lista recuperada correctamente');
    }

    public function myCoffeeList(Request $request)
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

        return $this->getMyCoffeeList($user);
    }

    public function getMyCoffeeList(User $user)
    {
        //Elimino los caducados
        $this->updateInvitationTimedOut();
        //devuelvo los demas
        $user_list = DB::select('
            select i.id as invitation_id, u.id as user_id, p.avatar as avatar, p.name as name, p.surname as surname, s.id as status_id, s.name as status, 1 as sent
            from coffee_invitations i
            join coffee_statuses s on i.id_status_coffee = s.id
            join activity_user au on i.id_activity_user_send = au.id and au.user_id = ?
            join users u on u.id = i.id_user_receive
            left join persons p on p.user_id = u.id
            where i.id_user_receive != ?
                and i.timed_out = 0

            union
            
            select i.id as invitation_id, u.id as user_id, p.avatar as avatar, p.name as name, p.surname as surname, s.id as status_id, s.name as status, 0 as sent
            from coffee_invitations i
            join coffee_statuses s on i.id_status_coffee = s.id
            join activity_user au on i.id_activity_user_send = au.id
            join users u on u.id = au.user_id
            left join persons p on p.user_id = u.id
            where i.id_user_receive = ?
             and i.timed_out = 0
            ',
            [$user->id, $user->id, $user->id]);

        return $this->sendResponse($user_list, 'Lista recuperada correctamente');
    }

    public function hasSenderInvitation($user)
    {
        $response = DB::select('
            select i.id as invitation_id, u.id as user_id, p.avatar as avatar, p.name as name, p.surname as surname, s.id as status_id, s.name as status, 1 as sent
            from coffee_invitations i
            join coffee_statuses s on i.id_status_coffee = s.id
            join activity_user au on i.id_activity_user_send = au.id and au.user_id = ?
            join users u on u.id = i.id_user_receive
            left join persons p on p.user_id = u.id
            where i.id_user_receive != ?
                and i.timed_out = 0',
            [$user->id, $user->id]);

        if (empty($response)) {
            return false;
        } else {
            return true;
        }

    }

    public function getActivityCoffee(User $user)
    {
        $coffee = User::find($user->id)->activities()->where('event_format_id', 5)->first();

        if (empty($coffee)) {
            return null;
        } else {
            return $coffee->pivot->id;
        }
    }

    public function isFreeParticipant(User $user)
    {
        $status_id = DB::table('activity_user')
            ->where('user_id', '=', $user->id)
            ->where('activity_id', '=', 1)
            ->select('id_status_coffee')
            ->get();
            
        if(empty($status_id[0]))
            return true;

        if ($status_id[0]->id_status_coffee == 2) {
            return false;
        }

        $coffee = User::find($user->id)->activities()->where('event_format_id', 5)->first();

        if (empty($coffee)) {
            return true;
        } else {
            if ($coffee->pivot->id_status_coffee == 2) {
                return false;
            }

            return true;
        }
    }

    public function sendInvitation(Request $request)
    {
        $input = $request->all();

        $user = JWTAuth::authenticate($request->bearerToken());
        if (is_null($user)) {
            return $this->sendError('usuario no autorizado.');
        }

        $validatedData = Validator::make($input, [
            'user_id' => 'required',
            'activity_id' => 'required'
        ], [
            'user_id.required' => 'La persona invitada es requerida',
            'activity_id.required' => 'La actividad es requerida',
        ]);

        if ($validatedData->fails()) {
            return $this->sendError('Error de validacion.', $validatedData->errors());
            Log::error($validatedData->errors());
            return;
        }

        $guest = User::find($input['user_id']);

        //si yo tengo una relacion
        if (!$this->isFreeParticipant($user)) {
            return $this->sendError('Ya tienes una invitacion confirmada.');
        }

        //si ya envie una invitacion y esta pendiente
        if ($this->hasSenderInvitation($user)) {
            return $this->sendError('Ya ha enviado una invitación. Tiene que esperar 72hs. para poder enviar otra.');
        }

        //si la persona que intento invitar tiene otra relacion
        if (!$this->isFreeParticipant($guest)) {
            return $this->sendError('La persona que intenta invitar ya no se encuentra disponible.');
        }

        $activity_user = $this->getActivityCoffee($user);

        if (is_null($activity_user)) {
            return $this->sendError('Debe inscribirse en la actividad Coffee.');
        }

        $invitation                        = new CoffeeInvitation();
        $invitation->id_activity_user_send = $activity_user;
        $invitation->id_user_receive       = $guest->id;
        $invitation->id_status_coffee      = 3;

        $invitation->save();

        $activity = Activity::Find($input['activity_id']);
        $event = Event::find($activity->event_id);
        Mail::to($guest->email)->send(new InvitationCoffee($user, $guest, $event->name));

        return $this->getMyCoffeeList($user);
    }

    public function updateInvitationTimedOut()
    {
       /* $response = DB::select(
            'update coffee_invitations
            set timed_out = 1
            where TIMESTAMPDIFF(HOUR, created_at, Now()) >= 72'
        );*/
        $response = DB::select(
            'update coffee_invitations
            set timed_out = 1
            where TIMESTAMPDIFF(HOUR, created_at, Now()) >= 48
                and id_status_coffee = 3'
        );
    }

    public function getSendUserFromInvitation(CoffeeInvitation $invitation)
    {
        $users = DB::table('users')
            ->join('activity_user', 'users.id', '=', 'activity_user.user_id')
            ->where('activity_user.id', '=', $invitation->id_activity_user_send)
            ->select('users.id as id')
            ->get();
        return User::find($users[0]->id);
    }

    public function acceptInvitation(Request $request)
    {
        $input = $request->all();

        $user = JWTAuth::authenticate($request->bearerToken());
        if (is_null($user)) {
            return $this->sendError('usuario no autorizado.');
        }

        $validatedData = Validator::make($input, [
            'invitation_id' => 'required',
        ], [
            'invitation_id.required' => 'La invitacion es requerida',
        ]);

        if ($validatedData->fails()) {
            return $this->sendError('Error de validacion.', $validatedData->errors());
            Log::error($validatedData->errors());
            return;
        }

        if (!$this->isFreeParticipant($user)) {
            return $this->sendError('Ya estas vinculado con otro participante');
        }

        $invitation = CoffeeInvitation::find($input['invitation_id']);

        if($invitation->timed_out == 1){
            return $this->sendError('Lo sentimos, la invitación ha caducado');
        }

        $invitation->id_status_coffee = 4;
        $invitation->save();

        $this->cancelInvitations($user);        

        $user_send    = $this->getSendUserFromInvitation($invitation);

        $this->cancelInvitations($user_send);

        $user_receive = User::find($invitation->id_user_receive);

        DB::table('activity_user')
            ->where('activity_user.user_id', $user_send->id)
            ->where('activity_user.activity_id', 1)
            ->update(['id_status_coffee' => 2]);

        DB::table('activity_user')
            ->where('activity_user.user_id', $user_receive->id)
            ->where('activity_user.activity_id', 1)
            ->update(['id_status_coffee' => 2]);

        Mail::to($user_send->email)->send(new AcceptCoffeeInvitation($user_send, $user_receive));

        return $this->getMyCoffeeList($user);
    }

    function cancelInvitations(User $user){
         $invitationQuery = DB::table('coffee_invitations')
         ->join('activity_user', 'activity_user.id', '=', 'coffee_invitations.id_activity_user_send')
         ->where('activity_user.user_id', $user->id)
         ->where('coffee_invitations.id_status_coffee', 3)
         ->where('coffee_invitations.timed_out', 0)
         ->select('coffee_invitations.id as id')
         ->get();

        if(!empty($invitationQuery[0])){
            $invitation = CoffeeInvitation::find($invitationQuery[0]->id);
            $invitation->timed_out = 1;
            $invitation->save();
        }

         $invitationQuery = DB::table('coffee_invitations')
         ->where('coffee_invitations.id_user_receive', $user->id)
         ->where('coffee_invitations.id_status_coffee', 3)
         ->where('coffee_invitations.timed_out', 0)
         ->select('coffee_invitations.id as id')
         ->get();

        if(!empty($invitationQuery[0])){
            $invitation = CoffeeInvitation::find($invitationQuery[0]->id);
            $invitation->timed_out = 1;
            $invitation->save();
        }
        return;
    }
}
