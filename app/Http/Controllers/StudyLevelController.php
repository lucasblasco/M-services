<?php

namespace App\Http\Controllers;

use App\StudyLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudyLevelController extends Controller
{
    public function index()
    {
        $obj = StudyLevel::all();
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
            Log::critical('Error 422: No se pudo crear el nivel de estudio. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }

        $newObj=StudyLevel::create($request->all());
        Log::info('Create nivel de estudio: '.$newObj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(StudyLevel $studyLevel)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$studyLevel
        ], 200);
    }

    public function update(Request $request, StudyLevel $studyLevel)
    {
        $name=$request->input('name');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $studyLevel->name = $name;
                $band=true;
            }

            if ($band){
                $studyLevel->save();
                Log::info('Update nivel de estudio: '.$studyLevel->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$studyLevel
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar el nivel de estudio. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name)
        {
            Log::critical('Error 422: No se pudo actualizar el nivel de estudio. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $studyLevel->name = $name;
        $studyLevel->save();
        Log::info('Update nivel de estudio: '.$studyLevel->id);
        return response()->json([
            "status"=>true, 
            "message"=>$studyLevel
        ], 200);
    }

    public function destroy(StudyLevel $studyLevel)
    {
        $studyLevel->delete();
        Log::info('Delete nivel de estudio: '.$studyLevel->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
