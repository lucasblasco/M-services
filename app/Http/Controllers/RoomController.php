<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    public function index()
    {
        $obj = Room::all();
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
            Log::critical('Error 422: No se pudo crear la sala. Faltan datos');
            return response()->json([
               "status"=>false, 
               "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }
        $obj=Room::create($request->all());

        Log::info('Create sala: '.$obj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(Room $room)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$room
        ], 200);
    }

    public function update(Request $request, Room $room)
    {
        $name=$request->input('name');
        $description=$request->input('description');
        $capacity=$request->input('capacity');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $room->name = $name;
                $band=true;
            }
            if ($description){
                $room->description = $description;
                $band=true;
            }
            if ($capacity){
                $room->capacity = $capacity;
                $band=true;
            }

            if ($band){
                $room->save();
                Log::info('Update sala: '.$room->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$room
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar la sala. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name || !$description || !$capacity)
        {
            Log::critical('Error 422: No se pudo actualizar la sala. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $room->name = $name;
        $room->description = $description;
        $room->capacity = $capacity;
        $room->save();
        Log::info('Update sala: '.$room->id);
        return response()->json([
            "status"=>true, 
            "message"=>$room
        ], 200);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        Log::info('Delete sala: '.$room->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
