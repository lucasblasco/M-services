<?php

namespace App\Http\Controllers;

use App\EventFormat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventFormatController extends Controller
{
    public function index()
    {
        $obj = EventFormat::all();
        return response()->json([
            'status'=>true, 
            'message'=>"success", 
            'data'=>$obj
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$request->input('name') || !$request->input('duration'))
        {
            Log::critical('Error 422: No se pudo crear el formato del evento. Faltan datos');
            return response()->json([
               "status"=>false, 
               "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }
        $obj=EventFormat::create($request->all());

        Log::info('Create formato del evento: '.$obj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(EventFormat $eventFormat)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$eventFormat
        ], 200);
    }

    public function update(Request $request, EventFormat $eventFormat)
    {
        $name=$request->input('name');
        $duration=$request->input('duration');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $eventFormat->name = $name;
                $band=true;
            }
            if ($duration){
                $eventFormat->duration = $duration;
                $band=true;
            }

            if ($band){
                $eventFormat->save();
                Log::info('Update formato del evento: '.$eventFormat->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$eventFormat
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar el formato del evento. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name || !$duration)
        {
            Log::critical('Error 422: No se pudo actualizar el formato del evento. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $eventFormat->name = $name;
        $eventFormat->duration = $duration;
        $eventFormat->save();
        Log::info('Update formato del evento: '.$eventFormat->id);
        return response()->json([
            "status"=>true, 
            "message"=>$eventFormat
        ], 200);
    }

    public function destroy(EventFormat $eventFormat)
    {
        $eventFormat->delete();
        Log::info('Delete formato del evento: '.$eventFormat->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
