<?php

namespace App\Http\Controllers;

use App\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $obj = DocumentType::all();
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
            Log::critical('Error 422: No se pudo crear el tipo de documento. Faltan datos');
            return response()->json([
               "status"=>false, 
               "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }
        $obj=DocumentType::create($request->all());

        Log::info('Create tipo de documento: '.$obj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(DocumentType $documentType)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$documentType
        ], 200);
    }
    
    public function update(Request $request, DocumentType $documentType)
    {
        $name=$request->input('name');
            
        if ($request->method() === 'PATCH')
        {
            $band = false;
            if ($name){
                $documentType->name = $name;
                $band=true;
            }

            if ($band){
                $documentType->save();
                Log::info('Update tipo de documento: '.$documentType->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$documentType
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar el tipo de documento. Parametro: '.$name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }

        if (!$name)
        {
            Log::critical('Error 422: No se pudo actualizar el tipo de documento. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }

        $documentType->name = $name;
        $documentType->save();
        Log::info('Update tipo de documento: '.$documentType->id);
        return response()->json([
            "status"=>true, 
            "message"=>$documentType
        ], 200);
    }

    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();
        Log::info('Delete tipo de documento: '.$documentType->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
