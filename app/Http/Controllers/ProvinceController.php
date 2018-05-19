<?php

namespace App\Http\Controllers;

use App\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index()
    {
        $obj = Province::all();
        return response()->json([
            'status'=>true, 
            'message'=>"success", 
            'data'=>$obj
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$request->input('name', 'country_id'))
        {
            Log::critical('Error 422: No se pudo crear la provincia. Faltan datos');
            return response()->json([
               "status"=>false, 
               "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }
        $obj=Province::create($request->all());

        Log::info('Create provincia: '.$obj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(Province $province)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$province
        ], 200);
    }

    public function update(Request $request, Province $province)
    {
        $name=$request->input('name');
        $country_id=$request->input('country_id');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $province->name = $name;
                $band=true;
            }
            if ($country_id){
                $province->country_id = $country_id;
                $band=true;
            }

            if ($band){
                $province->save();
                Log::info('Update provincia: '.$province->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$province
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar la provincia. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name || !$country_id)
        {
            Log::critical('Error 422: No se pudo actualizar la provincia. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $province->name = $name;
        $province->country_id = $country_id;
        $province->save();
        Log::info('Update provincia: '.$province->id);
        return response()->json([
            "status"=>true, 
            "message"=>$province
        ], 200);
    }

    public function destroy(Province $province)
    {
        $province->delete();
        Log::info('Delete ciudad: '.$province->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
