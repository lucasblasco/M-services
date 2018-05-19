<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    public function index()
    {
        $obj = Account::all();
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
            Log::critical('Error 422: No se pudo crear la cuenta. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }

        $newObj=Account::create($request->all());
        Log::info('Create cuenta: '.$newObj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(Account $account)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$account
        ], 200);
    }

    public function update(Request $request, Account $account)
    {
        $name = $request->input('name');
        $enabled = $request->input('enabled');
        
        if ($request->method() === 'PATCH') {
            $band = false;
            if ($name){
                $account->name = $name;
                $band=true;
            }
            if ($enabled){
                $account->enabled = $enabled;
                $band=true;
            }

            if ($band){
                $account->save();
                Log::info('Update cuenta: '.$account->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$account
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar la cuenta. Parametro: '.$account->name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }
        //Es PUT
        if (!$name || !$enabled) {
            Log::critical('Error 422: No se pudo actualizar la cuenta. Faltan datos.');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }
        $account->name = $name;
        $account->enabled = $enabled;
        $account->save();
        Log::info('Update cuenta: '.$account->id);
        return response()->json([
            "status"=>true, 
            "message"=>$account
        ], 200);
    }

    public function destroy(Account $account)
    {         
        $account->delete();
        Log::info('Delete cuenta: '.$account->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}