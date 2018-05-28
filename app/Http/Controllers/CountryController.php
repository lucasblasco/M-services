<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{
    public function index()
    {
        $obj = Country::all();
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
            Log::critical('Error 422: No se pudo crear el pais. Faltan datos');
            return response()->json([
               "status"=>false, 
               "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }
        $obj=Country::create($request->all());

        Log::info('Create pais: '.$obj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(Country $country)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$country
        ], 200);
    }

    public function update(Request $request, Country $country)
    {
        $name=$request->input('name');
        $demonym=$request->input('demonym');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $country->name = $name;
                $band=true;
            }
            if ($demonym){
                $country->demonym = $demonym;
                $band=true;
            }

            if ($band){
                $country->save();
                Log::info('Update pais: '.$country->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$country
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar el pais. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name || !$demonym)
        {
            Log::critical('Error 422: No se pudo actualizar el pais. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $country->name = $name;
        $country->demonym = $demonym;
        $country->save();
        Log::info('Update pais: '.$country->id);
        return response()->json([
            "status"=>true, 
            "message"=>$country
        ], 200);
    }

    public function destroy(Country $country)
    {
        $country->delete();
        Log::info('Delete pais: '.$country->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
