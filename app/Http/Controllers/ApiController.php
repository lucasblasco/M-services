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
use Tymon\JWTAuth\JWTAuth;

class ApiController extends Controller
{
    private $jwtAuth;

    public function __construct(JWTAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
    }

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

            $validatedPerson = Validator::make($person, [
                'name'    => 'required|string|max:50',
                'surname' => 'required|string|max:50',
                //    'document_number' => 'required|string|max:50|unique:persons',
                //    'birth_date'      => 'required|date',
            ], [
                'name.required'    => 'El nombre es requerido',
                'surname.required' => 'El apellido es requerido',
                //    'document_number.required' => 'El documento es requerido',
                //    'document_number.unique'   => 'El documento pertenece a un usuario registrado',
                //    'birth_date.required'      => 'La fecha de nacimiento es requerida',
            ]);

            if ($validatedPerson->fails()) {
                Log::error($validatedPerson->errors());
                return $this->sendError('Error de validacion.', $validatedPerson->errors());
            }

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

            Log::info($newUser->email);
            $newPerson               = new Person();
            $newPerson->name         = $person['name'];
            $newPerson->surname      = $person['surname'];
            $newPerson->share_data   = $person['share_data'];
            $newPerson->organization = $person['organization'];
            $newPerson->email        = $newUser->email;
            $newPerson->user_id      = $newUser->id;
            //    $newPerson->birth_date       = $person['birth_date'];
            //    $newPerson->document_type_id = 3;
            //    $newPerson->document_number  = $person['document_number'];

            /*           $newPerson->phone          = $person['phone'];

            $newPerson->study_level_id = $person['study_level_id'];
            $newPerson->profession_id  = $person['profession_id'];

            $newPerson->country_id     = $person['country_id'];
            $newPerson->province       = $person['province'];
            $newPerson->city           = $person['city'];
            $newPerson->street         = $person['street'];
            $newPerson->number         = $person['number'];
            $newPerson->postal_code    = $person['postal_code'];
            $newPerson->floor          = $person['floor'];
            $newPerson->dept           = $person['dept'];

            $newPerson->avatar         = $person['avatar'];
             */
            $newPerson->save();

            /*        foreach ($person['interests'] as $interest) {
            if ($interest['checked']) {
            $newUser->interests()->attach($interest['id']);
            }
            }

            foreach ($person['accounts'] as $account) {
            if (!is_null($account['value'])) {
            $newUser->accounts()->attach([$account['id'] => ['name' => $account['value']]]);
            }
            }*/

            Mail::to($newUser->email)->send(new EmailVerify($newUser->name, $newUser->confirmation_code));

            return $this->sendResponse($newPerson, 'Registro exitoso! Por favor revise su correo para activar su usuario. Si no lo encuentra en la bandeja de entrada, verifique en los correos no deseados');

        } /*else {
    $organization = $input['organization'];

    $validatedOrganization = Validator::make($organization, [
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

    if ($validatedOrganization->fails()) {
    Log::error($validatedOrganization->errors());
    return $this->sendError('Error de validacion.', $validatedOrganization->errors());
    }

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

    return $this->sendResponse($newOrganization, 'Registro exitoso! Por favor revise su correo para activar su usuario. Si no lo encuentra en la bandeja de entrada, verifique en los correos no deseados');
    }*/
    }

    public function registerUpdate(Request $request)
    {
        $input = $request->all();
        $user  = $input['user'];

        $userToken = $this->jwtAuth->authenticate($request->bearerToken());
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

            $validatedPerson = Validator::make($person, [
                'name'    => 'required|string|max:50',
                'surname' => 'required|string|max:50',
                //  'document_number' => 'unique:persons',
                //   'document_number' => 'required|string|max:50',
                //   'birth_date'      => 'required|date',
            ], [
                'name.required'    => 'El nombre es requerido',
                'surname.required' => 'El apellido es requerido',
                //     'document_number.unique'   => 'El documento pertenece a un usuario registrado',
                //     'document_number.required' => 'El documento es requerido',
                //     'birth_date.required'      => 'La fecha de nacimiento es requerida',
            ]);

            if ($validatedPerson->fails()) {
                Log::error($validatedPerson->errors());
                return $this->sendError('Error de validacion.', $validatedPerson->errors());
            }

            //creo el usuario
            $newUser = User::find($userToken->id);

            if (empty($newUser->id)) {
                return $this->sendError('No se ha encontrado el usuario .');
            }
            if (empty($newUser->person->id)) {
                return $this->sendError('No se ha encontrado la persona .');
            }

            $newUser->name = $person['name'];
            $newUser->save();

            //creo la persona
            if ($person['share_data']) {
                $person['share_data'] = 1;
            } else {
                $person['share_data'] = 0;
            }

            $newPerson                   = Person::find($newUser->person->id);
            $newPerson->name             = $person['name'];
            $newPerson->surname          = $person['surname'];
            $newPerson->birth_date       = $person['birth_date'];
            $newPerson->document_type_id = 3; //$person['document_type_id'];
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
            // $newPerson->avatar         = $person['avatar'];
            // $newPerson->organization   = $person['organization'];
            $newPerson->save();

            if (!empty($newUser->interests()->first())) {
                $newUser->interests()->detach();
            }

            if (!empty($newUser->accounts()->first())) {
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

        } /*else {
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

    $newUser = User::find($userToken->id);
    if (empty($newUser->id)) {
    return $this->sendError('No se ha encontrado el usuario .');
    }

    if (empty($newUser->organization->id)) {
    return $this->sendError('No se ha encontrado la organizacion.');
    }

    $newUser->name = $organization['name'];
    $newUser->save();

    //creo la persona
    if ($organization['share_data']) {
    $organization['share_data'] = 1;
    } else {
    $organization['share_data'] = 0;
    }

    $newOrganization                = Organization::find($newUser->organization->id);
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

    if (!empty($newUser->interests()->first())) {
    $newUser->interests()->detach();
    }

    if (!empty($newUser->accounts()->first())) {
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
    }*/
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
        $credentials = $request->only('email', 'password');
        $jwt_token   = null;

        $user = User::where('email', $credentials['email'])->first();
//Log::info($user);
        if (!$user) {
            return $this->sendError('El usuario no se encuentra registrado');
        }
        if (!$user->confirmed) {
            return $this->sendError('No se ha confirmado el correo');
        }

        if (!$jwt_token['token'] = $this->jwtAuth->attempt($credentials)) {
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
        $token = $this->jwtAuth->getToken();
        $this->jwtAuth->invalidate($token);
        return response()->json(['logout']);
    }

    public function refreshToken()
    {
        $token = $this->jwtAuth->getToken();
        $token = $this->jwtAuth->refresh($token);

        return response()->json(compact('token'));
    }

    public function getAuthUser(Request $request)
    {
        if (!$user = $this->jwtAuth->parseToken()->authenticate()) {
            return response()->json(['error' => 'user_not_found'], 404);
        }

        return response()->json(compact('user'));
    }
}
