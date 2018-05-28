<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CityController extends Controller
{
    public function index()
    {
        $obj = City::all();
        return response()->json([
            'status'=>true, 
            'message'=>"success", 
            'data'=>$obj
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$request->input('name', 'province_id'))
        {
            Log::critical('Error 422: No se pudo crear la ciudad. Faltan datos');
            return response()->json([
               "status"=>false, 
               "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }
        $obj=City::create($request->all());

        Log::info('Create ciudad: '.$obj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(City $city)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$city
        ], 200);
    }

    public function update(Request $request, City $city)
    {
        $name=$request->input('name');
        $province_id=$request->input('province_id');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $city->name = $name;
                $band=true;
            }
            if ($province_id){
                $city->province_id = $province_id;
                $band=true;
            }

            if ($band){
                $city->save();
                Log::info('Update ciudad: '.$city->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$city
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar la ciudad. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name || !$province_id)
        {
            Log::critical('Error 422: No se pudo actualizar la ciudad. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $city->name = $name;
        $city->province_id = $province_id;
        $city->save();
        Log::info('Update ciudad: '.$city->id);
        return response()->json([
            "status"=>true, 
            "message"=>$city
        ], 200);
    }

    public function destroy(City $city)
    {
        $city->delete();
        Log::info('Delete ciudad: '.$city->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
