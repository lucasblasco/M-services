<?php

namespace App\Http\Controllers;

use App\Interest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class InterestController extends Controller
{
    public function index()
    {
        return $this->sendResponse(Interest::all(), 'Intereses recuperados correctamente');
    }

    public function store(Request $request)
    {           
        if (!$request->input('name'))
        {
            Log::critical('Error 422: No se pudo crear el interes. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }

        $newObj=Interest::create($request->all());
        Log::info('Create interes: '.$newObj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(Interest $interest)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$interest
        ], 200);
    }

    public function update(Request $request, Interest $interest)
    {
        $name = $request->input('name');
            
        if ($request->method() === 'PATCH') {
            $band = false;
            if ($name){
                $interest->name = $name;
                $band=true;
            }
            if ($band){
                $interest->save();
                Log::info('Update interes: '.$interest->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$interest
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar el interes. Parametro: '.$interest->name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }
        if (!$name) {
            Log::critical('Error 422: No se pudo actualizar el interes. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }
        $interest->name = $name;
        $interest->save();
        Log::info('Update interes: '.$interest->id);
        return response()->json([
            "status"=>true, 
            "message"=>$interest
        ], 200);
    }

    public function destroy(Interest $interest)
    {
        $interest->delete();
        Log::info('Delete interes: '.$interest->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
