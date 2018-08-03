<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\PartnerContact;
use App\Event;
use App\User;

class PartnerContactController extends Controller
{
    public function send(Request $request)
    {
    	$input = $request->all();
        
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required',
            'event_id' => 'required',
            'cellphone' => 'required'
        ]);

        if($validator->fails()){
             return $this->sendError('Error de validación. Faltan datos necesarios para el envío.', 
             	$validator->errors());       
        }

    	$name = $input['name'];
        $cellphone = $input['cellphone'];
        $email = $input['email'];
        $event_id = $input['event_id'];

        $event = new Event();
        $event = Event::find($event_id);

        $user = new User();
       // $user = new App\User::find($event->user_id);
        $user = User::find(2);

        Mail::to($user->email)->send(new PartnerContact($user->name, $name, $cellphone, $email, $event));
        //Mail::to('contacto@mwork.com.ar')->send(new PartnerContact($user->name, $name, $cellphone, $email, $event));
        return $this->sendResponse($user, 'Email enviado correctamente');
    }
}
