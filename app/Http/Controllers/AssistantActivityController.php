<?php

namespace App\Http\Controllers;

use App\AssistantActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AssistantActivityController extends Controller
{
    public function index()
    {
        $obj = AssistantActivity::all();
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
            Log::critical('Error 422: No se pudo crear la actividad del asistente. Faltan datos');
            return response()->json([
               "status"=>false, 
               "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }
        $obj=AssistantActivity::create($request->all());

        Log::info('Create actividad del asistente: '.$obj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(AssistantActivity $assistantActivity)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$assistantActivity
        ], 200);
    }

    public function update(Request $request, AssistantActivity $assistantActivity)
    {
        $name=$request->input('name');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $assistantActivity->name = $name;
                $band=true;
            }

            if ($band){
                $assistantActivity->save();
                Log::info('Update rango de edad: '.$assistantActivity->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$assistantActivity
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar la actividad del asistente. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name)
        {
            Log::critical('Error 422: No se pudo actualizar la actividad del asistente. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $assistantActivity->name = $name;
        $assistantActivity->save();
        Log::info('Update actividad del asistente: '.$assistantActivity->id);
        return response()->json([
            "status"=>true, 
            "message"=>$assistantActivity
        ], 200);
    }

    public function destroy(AssistantActivity $assistantActivity)
    {
        $assistantActivity->delete();
        Log::info('Delete actividad del asistente: '.$assistantActivity->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
