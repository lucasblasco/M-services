<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerify;
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

        if (!is_null($input['person'])) {
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

            Mail::to($newUser->email)->send(new EmailVerify($newUser->name, $newUser->confirmation_code));

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
            $newPerson->document_type_id = 1;
            $newPerson->document_number  = $person['document_number'];
            
            //$newPerson->phone          = $person['phone'];
            $newPerson->email          = $newUser->email;
            $newPerson->study_level_id = $person['study_level_id'];
            //$newPerson->profession_id  = $person['profession_id'];
            $newPerson->user_id        = $newUser->id;
            $newPerson->country_id     = $person['country_id'];
            //$newPerson->province       = $person['province'];
            //$newPerson->city           = $person['city'];
            $newPerson->street         = $person['street'];
            $newPerson->number         = $person['number'];
            $newPerson->postal_code    = $person['postal_code'];
            $newPerson->floor          = $person['floor'];
            $newPerson->dept           = $person['dept'];
            $newPerson->share_data     = $person['share_data'];
            //$newPerson->avatar         = $person['avatar'];

            $newPerson->save();

            foreach ($person['interests'] as $interest) {
                if ($interest['checked']) {
                    $newPerson->interests()->attach($interest['id']);
                }
            }

            foreach ($person['accounts'] as $account) {
                if (!is_null($account['value'])) {
                    $newPerson->accounts()->attach([$account['id'] => ['name' => $account['value']]]);
                }
            }

        } else {
            $organization = $input['organization'];

            $validatedOrganization = Validator::make($user, [
                'name'          => 'required|string',
                'phone'         => 'required',
                'contact_name'  => 'required',
                'contact_phone' => 'required',
            ], [
                'name.required'          => 'El nombre es requerido',
                'phone.required'         => 'El telefono es requerido',
                'contact_name.required'  => 'El nombre del contacto es requerido',
                'contact_phone.required' => 'El telefono del contacto es requerido',
            ]);

            $user['name'] = $organization['name'];
        }

        /* if ($this->loginAfterSignUp) {
        return $this->login($user);
        }*/

        return $this->sendResponse($newPerson, 'Registro creado correctamente');
    }

    public function verify($code)
    {
        $user = User::where('confirmation_code', $code)->first();

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

        if (!$jwt_token['token'] = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Email o Password inválidos',
            ], 401);
        }

        $user               = User::where('email', $input['email'])->first();
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

            return response()->json([
                'success' => true,
                'message' => 'Se cerro sesión correctamente',
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo cerrar sesión',
            ], 500);
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
