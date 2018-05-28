<?php

namespace App\Http\Controllers;

use App\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    public function index()
    {
        $obj = Activity::all();
        return response()->json([
            'status'=>true, 
            'message'=>"success", 
            'data'=>$obj
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$request->input('name') ||
            !$request->input('description') ||
            !$request->input('event_id') ||
            !$request->input('room_id') ||
            !$request->input('event_format_id') ||
            !$request->input('day') ||
            !$request->input('start_time') ||
            !$request->input('end_time'))
        {
            Log::critical('Error 422: No se pudo crear la actividad. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }
        //$request->input('password') = bcrypt($request->input('password'));
        $newObj=Activity::create($request->all());
        Log::info('Create actividad: '.$newObj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(Activity $activity)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$activity
        ], 200);
    }

    public function update(Request $request, Activity $activity)
    {
        $name=$request->input('name');
        $description=$request->input('description');
        $event_id=$request->input('event_id');
        $room_id=$request->input('room_id');
        $event_format_id=$request->input('event_format_id');
        $day=$request->input('day');
        $start_time=$request->input('start_time');
        $end_time=$request->input('end_time');
        $status_id=$request->input('status_id');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $activity->name = $name;
                $band=true;
            }
            if ($description){
                $activity->description = $description;
                $band=true;
            }
            if ($event_id){
                $activity->event_id = $event_id;
                $band=true;
            }
            if ($room_id){
                $activity->room_id = $room_id;
                $band=true;
            }
            if ($event_format_id){
                $activity->event_format_id = $event_format_id;
                $band=true;
            }
            if ($day){
                $activity->day = $day;
                $band=true;
            }
            if ($start_time){
                $activity->start_time = $start_time;
                $band=true;
            }
            if ($end_time){
                $activity->end_time = $end_time;
                $band=true;
            }
            if ($status_id){
                $activity->status_id = $status_id;
                $band=true;
            }

            if ($band){
                $activity->save();
                Log::info('Update actividad: '.$activity->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$activity
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar la actividad. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name || 
            !$description ||
            !$event_id ||
            !$room_id ||
            !$event_format_id ||
            !$day ||
            !$start_time ||
            !$end_time ||
            !$status_id)
        {
            Log::critical('Error 422: No se pudo actualizar la actividad. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $activity->name = $name;
        $activity->description = $description;
        $activity->event_id = $event_id;
        $activity->room_id = $room_id;
        $activity->event_format_id = $event_format_id;
        $activity->day = $day;
        $activity->start_time = $start_time;
        $activity->end_time = $end_time;
        $activity->status_id = $status_id;
        $activity->save();
        Log::info('Update actividad: '.$activity->id);
        return response()->json([
            "status"=>true, 
            "message"=>$activity
        ], 200);
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        Log::info('Delete actividad: '.$activity->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
