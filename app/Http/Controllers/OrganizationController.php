<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $obj = Organization::all();
        return response()->json([
            'status'=>true, 
            'message'=>"success", 
            'data'=>$obj
        ], 200);
    }

    public function store(Request $request)
    {
        if ( !$request->input('name') ||
            !$request->input('phone') ||
            !$request->input('email') ||
            !$request->input('city_id') ||
            !$request->input('province_id') ||
            !$request->input('country_id') ||
            !$request->input('postal_code') ||
            !$request->input('street') ||
            !$request->input('number') ||
            !$request->input('floor') ||
            !$request->input('dept') ||
            !$request->input('contact_name') ||
            !$request->input('contact_phone'))
        {
            Log::critical('Error 422: No se pudo crear la organización. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de alta.'
            ], 422);                
        }

        $newObj=Organization::create($request->all());
        Log::info('Create organización: '.$newObj->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro creado correctamente'
        ], 200);
    }

    public function show(Organization $organization)
    {
        return response()->json([
            "status"=>true, 
            "message"=>$organization
        ], 200);
    }

    public function update(Request $request, Organization $organization)
    {
        $name = $request->input('name');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $cityId = $request->input('city_id');
        $provinceId = $request->input('province_id');
        $countryId = $request->input('country_id');
        $postalCode = $request->input('postal_code');
        $street = $request->input('street');
        $number = $request->input('number');
        $floor = $request->input('floor');
        $dept = $request->input('dept');
        $contactName = $request->input('contact_name');
        $contactPhone = $request->input('contact_phone');
        $userId = $request->input('user_id');
            
        if ($request->method() === 'PATCH') {
            $band = false;
            if ($name){
                $organization->name = $name;
                $band=true;
            }
            if ($phone){
                $organization->phone = $phone;
                $band=true;
            }
            if ($email){
                $organization->email = $email;
                $band=true;
            }
            if ($cityId){
                $organization->cityId = $cityId;
                $band=true;
            }
            if ($provinceId){
                $organization->provinceId = $provinceId;
                $band=true;
            }
            if ($countryId){
                $organization->countryId = $countryId;
                $band=true;
            }
            if ($postalCode){
                $organization->postalCode = $postalCode;
                $band=true;
            }
            if ($street){
                $organization->street = $street;
                $band=true;
            }
            if ($number){
                $organization->number = $number;
                $band=true;
            }
            if ($floor){
                $organization->floor = $floor;
                $band=true;
            }
            if ($dept){
                $organization->dept = $dept;
                $band=true;
            }
            if ($contactName){
                $organization->contactName = $contactName;
                $band=true;
            }
            if ($contactPhone){
                $organization->contactPhone = $contactPhone;
                $band=true;
            }
            if ($userId){
                $organization->userId = $userId;
                $band=true;
            }
            if ($band){
                $organization->save();
                Log::info('Update organización: '.$organization->id);
                return response()->json([
                    "status"=>true, 
                    "message"=>$organization
                ], 200);
            } else {
                Log::critical('Error 304: No se pudo modificar la organización. Parametro: '.$organization->name);
                return response()->json([
                    "status"=>false, 
                    "message"=>'No se pudo modificar el registro.'
                ], 304);
            }
        }
        if (!$name || 
            !$phone ||
            !$email ||
            !$cityId ||
            !$provinceId ||
            !$countryId || 
            !$postalCode ||
            !$street ||
            !$number ||
            !$floor ||
            !$dept ||
            !$contactName ||
            !$contactPhone ||
            !$userId) {
            Log::critical('Error 422: No se pudo actualizar la organización. Faltan datos');
            return response()->json([
                "status"=>false, 
                "message"=>'Faltan datos necesarios para el proceso de actualización.'
            ], 422);    
        }
        $organization->name = $name;
        $organization->phone = $phone;
        $organization->email = $email;
        $organization->cityId = $cityId;
        $organization->provinceId = $provinceId;
        $organization->countryId = $countryId;
        $organization->postalCode = $postalCode;
        $organization->street = $street;
        $organization->number = $number;
        $organization->floor = $floor;
        $organization->dept = $dept;
        $organization->contactName = $contactName;
        $organization->contactPhone = $contactPhone;
        $organization->userId = $userId;
        $organization->save();
        Log::info('Update organización: '.$organization->id);
        return response()->json([
            "status"=>true, 
            "message"=>$organization
        ], 200);
    }

    public function destroy(Organization $organization)
    {
        $organization->delete();
        Log::info('Delete organización: '.$organization->id);
        return response()->json([
            "status"=>true, 
            "message"=>'Registro eliminado correctamente'
        ], 200); 
    }
}
