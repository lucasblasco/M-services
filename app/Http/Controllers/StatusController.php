<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StatusController extends Controller
{
    public function index()
    {
        $obj = Status::all();
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
            Log::critical('Error 422: No se pudo crear el estado. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }

        $newObj=Status::create($request->all());
        Log::info('Create estado: '.$newObj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(Status $status)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$status
        ], 200);
    }

    public function update(Request $request, Status $status)
    {
        $name=$request->input('name');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $status->name = $name;
                $band=true;
            }

            if ($band){
                $status->save();
                Log::info('Update estado: '.$status->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$status
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar el estado. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name)
        {
            Log::critical('Error 422: No se pudo actualizar el estado. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $status->name = $name;
        $status->save();
        Log::info('Update estado: '.$status->id);
        return response()->json([
            "status"=>true, 
            "message"=>$status
        ], 200);
    }

    public function destroy(Status $status)
    {
        $status->delete();
        Log::info('Delete estado: '.$status->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
