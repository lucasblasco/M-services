<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
}
