<?php

namespace App\Http\Controllers;

use App\Profession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfessionController extends Controller
{
    public function index()
    {
        $obj = Profession::all();
        return response()->json([
            'status'=>true, 
            'message'=>"success", 
            'data'=>$obj
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$request->input('name'))
        {
            Log::critical('Error 422: No se pudo crear la profesion. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }

        $newObj=Profession::create($request->all());
        Log::info('Create profesion: '.$newObj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(Profession $profession)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$profession
        ], 200);
    }

    public function update(Request $request, Profession $profession)
    {
        $name=$request->input('name');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $profession->name = $name;
                $band=true;
            }

            if ($band){
                $profession->save();
                Log::info('Update profesion: '.$profession->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$profession
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar la profesion. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name)
        {
            Log::critical('Error 422: No se pudo actualizar la profesion. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $profession->name = $name;
        $profession->save();
        Log::info('Update profesion: '.$profession->id);
        return response()->json([
            "status"=>true, 
            "message"=>$profession
        ], 200);
    }

    public function destroy(Profession $profession)
    {
        $profession->delete();
        Log::info('Delete profesion: '.$profession->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
