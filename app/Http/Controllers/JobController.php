<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobController extends Controller
{    
    public function index()
    {
        $obj = Job::all();
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
            Log::critical('Error 422: No se pudo crear el empleo. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }

        $newObj=Job::create($request->all());
        Log::info('Create empleo: '.$newObj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(Job $job)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$job
        ], 200);
    }

    public function update(Request $request, Job $job)
    {
        $name = $request->input('name');
            
        if ($request->method() === 'PATCH') {
            $band = false;
            if ($name){
                $job->name = $name;
                $band=true;
            }
            if ($band){
                $job->save();
                Log::info('Update empleo: '.$job->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$job
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar el empleo. Parametro: '.$job->name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }
        if (!$name) {
            Log::critical('Error 422: No se pudo actualizar el empleo. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }
        $job->name = $name;
        $job->save();
        Log::info('Update empleo: '.$job->id);
        return response()->json([
            "status"=>true, 
            "message"=>$job
        ], 200);
    }

    public function destroy(Job $job)
    {
        $job->delete();
        Log::info('Delete empleo: '.$job->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
