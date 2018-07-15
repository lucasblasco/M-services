<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerify;
use App\Organization;
use App\Person;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiController extends Controller
{
    public $loginAfterSignUp = true;

    // public function register(RegisterAuthRequest $request)
    public function register(Request $request)
    {
        $input = $request->all();
        $user  = $input['user'];

        $validatedUser = Validator::make($user, [
            'email'                 => 'required|string|email|max:255|unique:users',
            'password'              => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3|same:password',
        ], [
            'email.required'     => 'El email es requerido',
            'email.unique'       => 'Este email ya esta registrado con otro usuario',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        if ($validatedUser->fails()) {
            Log::error($validatedUser->errors());
            return $this->sendError('Error de validacion.', $validatedUser->errors());
        }

        if ($request->has('person')) {
            $person = $input['person'];

            $validatedPerson = Validator::make($user, [
                'name'            => 'required|string|max:50',
                'surname'         => 'required|string|max:50',
                'document_number' => 'required|string|max:50',
                'birth_date'      => 'required|date',
            ], [
                'name.required'            => 'El nombre es requerido',
                'surname.required'         => 'El apellido es requerido',
                'document_number.required' => 'El documento es requerido',
                'birth_date.required'      => 'La fecha de nacimiento es requerida',
            ]);

            //creo el usuario
            $newUser                    = new User();
            $newUser->name              = $person['name'];
            $newUser->email             = $user['email'];
            $newUser->password          = bcrypt($user['password']);
            $newUser->confirmation_code = str_random(160);
            $newUser->save();

            //creo la persona
            if ($person['share_data']) {
                $person['share_data'] = 1;
            } else {
                $person['share_data'] = 0;
            }

            $newPerson                   = new Person();
            $newPerson->name             = $person['name'];
            $newPerson->surname          = $person['surname'];
            $newPerson->birth_date       = $person['birth_date'];
            $newPerson->document_type_id = $person['document_type_id'];
            $newPerson->document_number  = $person['document_number'];

            $newPerson->phone          = $person['phone'];
            $newPerson->email          = $newUser->email;
            $newPerson->study_level_id = $person['study_level_id'];
            $newPerson->profession_id  = $person['profession_id'];
            $newPerson->user_id        = $newUser->id;
            $newPerson->country_id     = $person['country_id'];
            $newPerson->province       = $person['province'];
            $newPerson->city           = $person['city'];
            $newPerson->street         = $person['street'];
            $newPerson->number         = $person['number'];
            $newPerson->postal_code    = $person['postal_code'];
            $newPerson->floor          = $person['floor'];
            $newPerson->dept           = $person['dept'];
            $newPerson->share_data     = $person['share_data'];
            $newPerson->avatar         = $person['avatar'];

            $newPerson->save();

            foreach ($person['interests'] as $interest) {
                if ($interest['checked']) {
                    $newUser->interests()->attach($interest['id']);
                }
            }

            foreach ($person['accounts'] as $account) {
                if (!is_null($account['value'])) {
                    $newUser->accounts()->attach([$account['id'] => ['name' => $account['value']]]);
                }
            }

            Mail::to($newUser->email)->send(new EmailVerify($newUser->name, $newUser->confirmation_code));

            return $this->sendResponse($newPerson, 'Registro creado correctamente');

        } else {
            $organization = $input['organization'];

            $validatedOrganization = Validator::make($user, [
                'name'          => 'required|string',
                'phone'         => 'required',
                'contact_name'  => 'required',
                'contact_phone' => 'required',
                'country_id'    => 'required',
                'city'          => 'required',
                'street'        => 'required',
                'number'        => 'required',
            ], [
                'name.required'          => 'El nombre es requerido',
                'phone.required'         => 'El telefono es requerido',
                'contact_name.required'  => 'El nombre del contacto es requerido',
                'contact_phone.required' => 'El telefono del contacto es requerido',
                'country_id.required'    => 'El pais es requerido',
                'city.required'          => 'La ciudad es requerida',
                'street.required'        => 'La calle es requerida',
                'number.required'        => 'La altura de la calle es requerida',
            ]);

            //creo el usuario
            $newUser                    = new User();
            $newUser->name              = $organization['name'];
            $newUser->email             = $user['email'];
            $newUser->password          = bcrypt($user['password']);
            $newUser->confirmation_code = str_random(160);
            $newUser->save();

            //creo la persona
            if ($organization['share_data']) {
                $organization['share_data'] = 1;
            } else {
                $organization['share_data'] = 0;
            }

            $newOrganization                = new Organization();
            $newOrganization->name          = $organization['name'];
            $newOrganization->phone         = $organization['phone'];
            $newOrganization->email         = $newUser->email;
            $newOrganization->user_id       = $newUser->id;
            $newOrganization->country_id    = $organization['country_id'];
            $newOrganization->province      = $organization['province'];
            $newOrganization->city          = $organization['city'];
            $newOrganization->street        = $organization['street'];
            $newOrganization->number        = $organization['number'];
            $newOrganization->postal_code   = $organization['postal_code'];
            $newOrganization->floor         = $organization['floor'];
            $newOrganization->dept          = $organization['dept'];
            $newOrganization->share_data    = $organization['share_data'];
            $newOrganization->avatar        = $organization['avatar'];
            $newOrganization->contact_name  = $organization['contact_name'];
            $newOrganization->contact_phone = $organization['contact_phone'];

            $newOrganization->save();

            foreach ($organization['interests'] as $interest) {
                if ($interest['checked']) {
                    $newUser->interests()->attach($interest['id']);
                }
            }

            foreach ($organization['accounts'] as $account) {
                if (!is_null($account['value'])) {
                    $newUser->accounts()->attach([$account['id'] => ['name' => $account['value']]]);
                }
            }

            Mail::to($newUser->email)->send(new EmailVerify($newUser->name, $newUser->confirmation_code));

            return $this->sendResponse($newOrganization, 'Registro creado correctamente');
        }
    }

    public function registerUpdate(Request $request)
    {
        $input = $request->all();
        $user  = $input['user'];

        $userToken = JWTAuth::authenticate($request->bearerToken());
        if (is_null($userToken)) {
            return $this->sendError('usuario no autorizado.');
        }

        $validatedUser = Validator::make($user, [
            'email'                 => 'required|string|email|max:255',
            'password'              => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3|same:password',
        ], [
            'email.required'     => 'El email es requerido',            
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        if ($validatedUser->fails()) {
            Log::error($validatedUser->errors());
            return $this->sendError('Error de validacion.', $validatedUser->errors());
        }



        if ($request->has('person')) {
            $person = $input['person'];

            $validatedPerson = Validator::make($user, [
                'name'            => 'required|string|max:50',
                'surname'         => 'required|string|max:50',
                'document_number' => 'required|string|max:50',
                'birth_date'      => 'required|date',
            ], [
                'name.required'            => 'El nombre es requerido',
                'surname.required'         => 'El apellido es requerido',
                'document_number.required' => 'El documento es requerido',
                'birth_date.required'      => 'La fecha de nacimiento es requerida',
            ]);

            //creo el usuario
            $newUser                    = User::find($userToken->id);
            if(empty($newUser->id)){
                return $this->sendError('No se ha encontrado el usuario .');
            }
            if(empty($newUser->person->id)){
                return $this->sendError('No se ha encontrado la persona .');
            }

            $newUser->name              = $person['name'];
            //$newUser->email             = $user['email'];
            //$newUser->password          = bcrypt($user['password']);
           // $newUser->confirmed         = 1;
            $newUser->save();

            //creo la persona
            if ($person['share_data']) {
                $person['share_data'] = 1;
            } else {
                $person['share_data'] = 0;
            }

            $newPerson                   = User::find($newUser->person->id);
            $newPerson->name             = $person['name'];
            $newPerson->surname          = $person['surname'];
            $newPerson->birth_date       = $person['birth_date'];
            $newPerson->document_type_id = $person['document_type_id'];
            $newPerson->document_number  = $person['document_number'];

            $newPerson->phone          = $person['phone'];
            $newPerson->email          = $newUser->email;
            $newPerson->study_level_id = $person['study_level_id'];
            $newPerson->profession_id  = $person['profession_id'];
            $newPerson->user_id        = $newUser->id;
            $newPerson->country_id     = $person['country_id'];
            $newPerson->province       = $person['province'];
            $newPerson->city           = $person['city'];
            $newPerson->street         = $person['street'];
            $newPerson->number         = $person['number'];
            $newPerson->postal_code    = $person['postal_code'];
            $newPerson->floor          = $person['floor'];
            $newPerson->dept           = $person['dept'];
            $newPerson->share_data     = $person['share_data'];
            $newPerson->avatar         = $person['avatar'];

            $newPerson->save();

            if(!empty($newUser->interest()->first())){
                $newUser->activities()->detach();
            }

            if(!empty($newUser->interest()->first())){
                $newUser->accounts()->detach();
            }

            foreach ($person['interests'] as $interest) {
                if ($interest['checked']) {
                    $newUser->interests()->attach($interest['id']);
                }
            }

            foreach ($person['accounts'] as $account) {
                if (!is_null($account['value'])) {
                    $newUser->accounts()->attach([$account['id'] => ['name' => $account['value']]]);
                }
            }            

            return $this->sendResponse($newPerson, 'Registro actualizado correctamente');

        } else {
            $organization = $input['organization'];

            $validatedOrganization = Validator::make($user, [
                'name'          => 'required|string',
                'phone'         => 'required',
                'contact_name'  => 'required',
                'contact_phone' => 'required',
                'country_id'    => 'required',
                'city'          => 'required',
                'street'        => 'required',
                'number'        => 'required',
            ], [
                'name.required'          => 'El nombre es requerido',
                'phone.required'         => 'El telefono es requerido',
                'contact_name.required'  => 'El nombre del contacto es requerido',
                'contact_phone.required' => 'El telefono del contacto es requerido',
                'country_id.required'    => 'El pais es requerido',
                'city.required'          => 'La ciudad es requerida',
                'street.required'        => 'La calle es requerida',
                'number.required'        => 'La altura de la calle es requerida',
            ]);

            $newUser                    = User::find($userToken->id);
            if(empty($newUser->id)){
                return $this->sendError('No se ha encontrado el usuario .');
            }

            if(empty($newUser->organization->id)){
                return $this->sendError('No se ha encontrado la organizacion.');
            }

            $newUser->name              = $organization['name'];
            //$newUser->email             = $user['email'];
            //$newUser->password          = bcrypt($user['password']);
            //$newUser->confirmed         = 1;
            $newUser->save();

            //creo la persona
            if ($organization['share_data']) {
                $organization['share_data'] = 1;
            } else {
                $organization['share_data'] = 0;
            }

            $newOrganization                = User::find($newUser->organization->id);
            $newOrganization->name          = $organization['name'];
            $newOrganization->phone         = $organization['phone'];
            $newOrganization->email         = $newUser->email;
            $newOrganization->user_id       = $newUser->id;
            $newOrganization->country_id    = $organization['country_id'];
            $newOrganization->province      = $organization['province'];
            $newOrganization->city          = $organization['city'];
            $newOrganization->street        = $organization['street'];
            $newOrganization->number        = $organization['number'];
            $newOrganization->postal_code   = $organization['postal_code'];
            $newOrganization->floor         = $organization['floor'];
            $newOrganization->dept          = $organization['dept'];
            $newOrganization->share_data    = $organization['share_data'];
            $newOrganization->avatar        = $organization['avatar'];
            $newOrganization->contact_name  = $organization['contact_name'];
            $newOrganization->contact_phone = $organization['contact_phone'];

            $newOrganization->save();

             if(!empty($newUser->interest()->first())){
                $newUser->activities()->detach();
            }

            if(!empty($newUser->interest()->first())){
                $newUser->accounts()->detach();
            }

            foreach ($organization['interests'] as $interest) {
                if ($interest['checked']) {
                    $newUser->interests()->attach($interest['id']);
                }
            }

            foreach ($organization['accounts'] as $account) {
                if (!is_null($account['value'])) {
                    $newUser->accounts()->attach([$account['id'] => ['name' => $account['value']]]);
                }
            }
            
            return $this->sendResponse($newOrganization, 'Registro actualizado correctamente');
        }
    }

    public function verify(Request $request)
    {
        $input = $request->all();
        $code  = $input['code'];
        $user  = User::where('confirmation_code', $code)->first();

        if (!$user) {
            return $this->sendError('No se ha podido confirmar el correo');
        }

        $user->confirmed         = true;
        $user->confirmation_code = null;
        $user->save();

        return $this->sendResponse($user, 'Has confirmado correctamente tu correo!');
    }

    public function login(Request $request)
    {
        $input     = $request->only('email', 'password');
        $jwt_token = null;

        $user = User::where('email', $input['email'])->first();

        if(empty($user->email))
            return $this->sendError('No existe un usuario registrado con ese email', 401);

        if (!$user->confirmed) {
            return $this->sendError('No se ha confirmado el correo');
        }

        if (!$jwt_token['token'] = JWTAuth::attempt($input)) {
            return $this->sendError('Email o Password incorrectos', 401);
        }

        $jwt_token['name']  = $user->name;
        $jwt_token['email'] = $user->email;

        return response()->json([
            'success' => true,
            'token'   => $jwt_token,
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->header('Authorization');
        /* $this->validate($request, [
        'token' => 'required'
        ]);
         */
        try {
            JWTAuth::invalidate($request->token);
            return $this->sendResponse(null, 'Se cerro sesión correctamente');
        } catch (JWTException $exception) {
            return $this->sendError('No se pudo cerrar sesión', 500);
        }
    }

    public function getAuthUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }
}
