<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::group(['middleware' => 'cors'], function(){
	Route::get('prueba', 'UserController@prueba');
	//Auth::routes();	
	//LOGIN
	Route::post('login', 'ApiController@login');
	Route::post('register', 'ApiController@register');
	Route::post('refreshToken', 'ApiController@refreshToken');
	Route::get('register/verify', 'ApiController@verify');

	//Intereses
	Route::get('interests', 'InterestController@index');

	//Profesiones
	Route::get('professions', 'ProfessionController@index');
	Route::get('professions/{id}', 'ProfessionController@show');

	//Paises
	Route::get('countries', 'CountryController@index');
	
	//Niveles de estudio
	Route::get('studyLevels', 'StudyLevelController@index');
	
	//Tipos de cuentas
	Route::get('accounts', 'AccountController@index');
	
	//Eventos
	Route::get('events', 'EventController@index');
	Route::get('events/{id}', 'EventController@show');
	
	//Actividades
	Route::apiResource('activities', 'ActivityController');
	Route::get('activities_event', 'ActivityController@indexByEvent');
	
	//Mail partner
	Route::get('partnerContact', 'PartnerContactController@send');

	Route::get('summit/{file}', function ($file) {
		return response()->download(storage_path("app/public/summit/{$file}"));
			//    return Storage::download("summit/".$file);
			});

	Route::group(['middleware' => 'auth.jwt'], function(){		
		//Login
		Route::get('logout', 'ApiController@logout');
		Route::post('registerUpdate', 'ApiController@registerUpdate');
		
		//Evento
		Route::post('subscribeEvent', 'UserController@subscribeEvent');
		Route::post('unsubscribeEvent', 'UserController@unsubscribeEvent');
		Route::get('isParticipatingEvent', 'UserController@isParticipatingEvent');
		
		Route::get('eventPerson', 'UserController@eventPerson');

		//Actividades
		Route::post('subscribeActivity', 'UserController@subscribeActivity');
		Route::post('unsubscribeActivity', 'UserController@unsubscribeActivity');

		Route::get('activitiesPerson', 'UserController@activitiesPerson');

		//Persona
		//Route::get('persons', 'PersonController@index')
		Route::get('persons/get', 'UserController@get');
		Route::post('changeAvatar', 'UserController@changeAvatar');
		
		//Mail summit query
		Route::get('summitContact', 'ActivityController@summitContact');

		Route::post('summitUploadTemplete', 'ActivityController@summitUploadTemplete');


		//Coffee
		Route::get('coffeeParticipants', 'CoffeeInvitationController@coffeeParticipants');
		Route::get('myCoffeeList', 'CoffeeInvitationController@myCoffeeList');
		Route::post('sendInvitation', 'CoffeeInvitationController@sendInvitation');
		Route::post('acceptInvitation', 'CoffeeInvitationController@acceptInvitation');
		
		/*Route::apiResource('users', 'UserController');
		Route::apiResource('interests', 'InterestController');
		Route::apiResource('documentTypes', 'documentTypeController');
		Route::apiResource('professions', 'ProfessionController');
		Route::apiResource('accounts', 'AccountController');
		Route::apiResource('jobs', 'UserController');
		Route::apiResource('studyLevels', 'StudyLevelController');
		Route::apiResource('persons', 'PersonController');
		Route::apiResource('cities', 'CityController');
		Route::apiResource('countries', 'CountryController');
		Route::apiResource('provinces', 'ProvinceController');
		Route::apiResource('events', 'EventController');
		Route::apiResource('eventFormats', 'EventFormatController');
		Route::apiResource('ageRanges', 'AgeRangeController');
		Route::apiResource('assistantActivities', 'AssistantActivityController');
		Route::apiResource('rooms', 'RoomController');
		Route::apiResource('activities', 'ActivityController');*/
	});
});