<?php

namespace App\Http\Controllers;

use App\User;
use App\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $obj = User::all();
        return response()->json([
            'status'=>true, 
            'message'=>"success", 
            'data'=>$obj
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$request->input('name') ||
            !$request->input('password') ||
            !$request->input('email'))
        {
            Log::critical('Error 422: No se pudo crear el usuario. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }
        //$request->input('password') = bcrypt($request->input('password'));
        $newObj=User::create($request->all());
        Log::info('Create usuario: '.$newObj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(User $user)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$user
        ], 200);
    }

    public function update(Request $request, User $user)
    {
        $name=$request->input('name');
        $email=$request->input('email');
        $password=$request->input('password');
        $enabled=$request->input('enabled');
        $linkedinId=$request->input('linkedin_id');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $user->name = $name;
                $band=true;
            }
            if ($email){
                $user->email = $email;
                $band=true;
            }
            if ($password){
                $user->password = bcrypt($password);
                $band=true;
            }
            if ($enabled){
                $user->enabled = $enabled;
                $band=true;
            }
            if ($linkedinId){
                $user->linkedinId = $linkedinId;
                $band=true;
            }

            if ($band){
                $user->save();
                Log::info('Update usuario: '.$user->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$user
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar el usuario. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name || 
            !$email ||
            !$password ||
            !$enabled ||
            !$linkedinId)
        {
            Log::critical('Error 422: No se pudo actualizar el usuario. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->enabled = $enabled;
        $user->linkedinId = $linkedinId;
        $user->save();
        Log::info('Update usuario: '.$user->id);
        return response()->json([
            "status"=>true, 
            "message"=>$user
        ], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        Log::info('Delete usuario: '.$user->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }

    public function register(Request $request){
        $input = $request->all();
        $user = $input['user'];
        $person = $input['person'];
        
        $user['name'] = $person['name'].' '.$person['surname'];
        
        $validatedData = Validator::make($user, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3|same:password'
        ],[
            'email.required' => 'El email es requerido',
            'email.unique' => 'Este email ya esta registrado con otro usuario',
            'password.confirmed' => 'Las contraseñas no coinciden'
        ]);

        if($validatedData->fails()){
            return $this->sendError('Error de validacion.', $validatedData->errors());   
            Log::error($validatedData->errors());
        } else {
            $user['password'] = bcrypt($user['password']);
            //creo el usuario
            $newUser = User::create($user);
            if($person['share_data'] == 'true')
                $person['share_data'] = 1;
            else
                $person['share_data'] = 0;
            $person['user_id'] = $newUser->id;
            $person['email'] = $newUser->email;
            Log::info($person);
            //creo la persona
            $newPerson = Person::create($person);
            return $this->sendResponse($newPerson, 'Registro creado correctamente');
        }


        
        /*
        $email = $input['email'];
        $password = $input['password'];
        $name = $input['name'];   
        $surname = $input['surname']; 
        $birth_date = $input['birth_date']; 
        $document_number = $input['document_number']; 
        $empleo = $input['empleo']; 
        $study_level_id = $input['study_level_id']; 
        $intereses = $input['intereses']; 
        $cellphone = $input['cellphone']; 
        $country_id = $input['country_id']; 
        $province_id = $input['province_id']; 
        $city_id = $input['city_id']; 
        $street = $input['street']; 
        $number = $input['number']; 
        $postal_code = $input['postal_code']; 
        $floor = $input['floor']; 
        $dept = $input['dept']; 
        $terms = $input['terms']; 
        $share_data = $input['share_data'];


        $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = bcrypt($password);            
            $user->enabled = true;
            $user->save();

            if($user->id == null)
                return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    

        if($input['type'] == 'person'){            
            $person = new Person();
            $person->name = $name;
            $person->surname = $surname;
            $person->birth_date = $birth_date;
            $person->document_type_id = 1;
            //$person->studyLevelId = $study_level_id;
            $person->document_number = $document_number;
            $person->cellphone = $cellphone;
            $person->email = $email;
            $person->user_id = $user->id;
            //$person->cityId = $city_id;
           // $person->provinceId = $province_id;
           // $person->countryId = $country_id;
           // $person->postal_code = $postal_code;
            $person->save();
            return response()->json([
                "status"=>true, 
                "message"=>'Registro creado correctamente'
            ], 200);
        }*/
    }

}
