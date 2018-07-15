<?php

namespace App\Http\Controllers;

use App\Person;
use App\User;
use App\Event;
use App\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use JWTAuth;

class PersonController extends Controller
{
    public function index()
    {
        $persons = Person::all();
        foreach ($persons as $person){
            $person->studyLevel; 
            $person->profession;
        }
        return $this->sendResponse($persons, 'Personas recuperadas correctamente');
    }

    public function store(Request $request)
    {
       if ( !$request->input('name') ||
            !$request->input('surname') ||
            !$request->input('documento_type_id') ||
            !$request->input('document_number') ||
            !$request->input('birth_date') ||
            !$request->input('email'))
        {
            Log::critical('Error 422: No se pudo crear la persona. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }

        $newObj=Person::create($request->all());
        Log::info('Create persona: '.$newObj->id);
        return $this->sendResponse($newObj, 'Registro creado correctamente');
    }

    public function show(Person $person)
    {   
        $person->studyLevel; 
        $person->profession;
        $person->interests;
        $person->accounts;
        $person->user;

        return $this->sendResponse($person, 'Persona recuperada correctamente');
    }

    public function update(Request $request, Person $person)
    {
        $name = $request->input('name');
        $surname = $request->input('surname');
        $birthDate = $request->input('birth_date');
        $documentTypeId = $request->input('document_type_id');
        $studyLevelId = $request->input('study_level_id');
        $phone = $request->input('phone');
        $documentNumber = $request->input('document_number');
        $cellphone = $request->input('cellphone');
        $email = $request->input('email');
        $cityId = $request->input('city_id');
        $provinceId = $request->input('province_id');
        $countryId = $request->input('country_id');
        $postalCode = $request->input('postal_code');
        $userId = $request->input('user_id');
            
        if ($request->method() === 'PATCH') {
            $band = false;
            if ($name){
                $person->name = $name;
                $band=true;
            }
            if ($surname){
                $person->surname = $surname;
                $band=true;
            }
            if ($birthDate){
                $person->birthDate = $birthDate;
                $band=true;
            }
            if ($documentTypeId){
                $person->documentTypeId = $documentTypeId;
                $band=true;
            }
            if ($studyLevelId){
                $person->studyLevelId = $studyLevelId;
                $band=true;
            }
            if ($phone){
                $person->phone = $phone;
                $band=true;
            }
            if ($documentNumber){
                $person->documentNumber = $documentNumber;
                $band=true;
            }
            if ($cellphone){
                $person->cellphone = $cellphone;
                $band=true;
            }
            if ($email){
                $person->email = $email;
                $band=true;
            }
            if ($userId){
                $person->userId = $userId;
                $band=true;
            }
            if ($cityId){
                $person->cityId = $cityId;
                $band=true;
            }
            if ($provinceId){
                $person->provinceId = $provinceId;
                $band=true;
            }
            if ($countryId){
                $person->countryId = $countryId;
                $band=true;
            }
            if ($postalCode){
                $person->postal_code = $postalCode;
                $band=true;
            }
            if ($band){
                $person->save();
                Log::info('Update persona: '.$person->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$person
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar la persona. Parametro: '.$person->name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }
        if (!$name || 
            !$surname ||
            !$birthDate ||
            !$documentTypeId ||
            !$studyLevelId ||
            !$phone || 
            !$documentNumber ||
            !$cellphone ||
            !$cityId ||
            !$provinceId ||
            !$countryId ||
            !$postalCode ||
            !$email ||
            !$userId) {
            Log::critical('Error 422: No se pudo actualizar la persona. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }
        $person->name = $name;
        $person->surname = $surname;
        $person->birthDate = $birthDate;
        $person->documentTypeId = $documentTypeId;
        $person->studyLevelId = $studyLevelId;
        $person->phone = $phone;
        $person->documentNumber = $documentNumber;
        $person->cellphone = $cellphone;
        $person->email = $email;
        $person->userId = $userId;
        $person->cityId = $cityId;
        $person->provinceId = $provinceId;
        $person->countryId = $countryId;
        $person->postal_code = $postalCode;
        $person->save();
        Log::info('Update persona: '.$person->id);
        return response()->json([
            "status"=>true, 
            "message"=>$person
        ], 200);
    }

    public function destroy(Person $person)
    {
        $person->delete();
        Log::info('Delete persona: '.$person->id);
        return $this->sendResponse($person, 'Registro eliminado correctamente');
    }    
}
