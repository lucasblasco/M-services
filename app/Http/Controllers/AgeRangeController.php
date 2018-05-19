<?php

namespace App\Http\Controllers;

use App\AgeRange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgeRangeController extends Controller
{
    public function index()
    {
        $obj = AgeRange::all();
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
            Log::critical('Error 422: No se pudo crear el rango de edad. Faltan datos');
            return response()->json([
               "status"=>false, 
               "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }
        $obj=AgeRange::create($request->all());

        Log::info('Create rango de edad: '.$obj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(AgeRange $ageRange)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$ageRange
        ], 200);
    }

    public function update(Request $request, AgeRange $ageRange)
    {
        $name=$request->input('name');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $ageRange->name = $name;
                $band=true;
            }

            if ($band){
                $ageRange->save();
                Log::info('Update rango de edad: '.$ageRange->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$ageRange
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar el rango de edad. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name)
        {
            Log::critical('Error 422: No se pudo actualizar el rango de edad. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $ageRange->name = $name;
        $ageRange->save();
        Log::info('Update rango de edad: '.$ageRange->id);
        return response()->json([
            "status"=>true, 
            "message"=>$ageRange
        ], 200);
    }

    public function destroy(AgeRange $ageRange)
    {
        $ageRange->delete();
        Log::info('Delete rango de edad: '.$ageRange->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
