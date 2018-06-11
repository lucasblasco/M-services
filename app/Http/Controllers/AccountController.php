<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index()
    {
        return $this->sendResponse(Account::all(), 'Cuentas recuperadas correctamente');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Error de validación. Faltan datos necesarios para el proceso de alta.', $validator->errors());       
        }                  

        $obj = Account::create($input);
        Log::info('Create cuenta: '.$obj->toArray);
        return $this->sendResponse($obj, 'Registro creado correctamente');
    }

    public function show(Account $account)
    {
        if (is_null($account)) {
            return $this->sendError('La cuenta no existe');
        }
        return $this->sendResponse($account, 'Cuenta recuperada correctamente');
    }

    public function update(Request $request, Account $account)
    {
        $input = $request->all();


        $validator = Validator::make($input, [
            'name' => 'required',
            'enabled' => 'boolean'
        ]);

        if($validator->fails()){
             return $this->sendError('Error de validación. Faltan datos necesarios para el proceso de modificación.', $validator->errors());       
        }
        
        if ($request->method() === 'PATCH') {
            $band = false;
            if ($input['name']){
                $account->name = $input['name'];
                $band=true;
            }
            if ($input['name']){
                $account->enabled = $input['enabled'];
                $band=true;
            }

            if ($band){
                $account->save();
                Log::info('Update cuenta: '.$account->id);
                return $this->sendResponse($account, 'Registro modificado correctamente');
            } else 
                $this->sendError('No se pudo modificar el registro.', 304);            
        }
        //Es PUT
        if (!$input['name'] || !$input['enabled']) {
            $this->sendError('Faltan datos necesarios para el proceso de modificación.', $validator->errors());  
        }
        $account->name = $input['name'];
        $account->enabled = $input['enabled'];
        $account->save();
        Log::info('Update cuenta: '.$account->id);
        return $this->sendResponse($account, 'Cuenta modificado correctamente');
    }

    public function destroy(Account $account)
    {         
        $account->delete();
        Log::info('Delete cuenta: '.$account->id);
        return $this->sendResponse($account, 'Registro eliminado correctamente');
    }
}