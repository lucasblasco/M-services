<?php

use Illuminate\Http\Request;

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
	//Auth::routes();	
	//LOGIN
	Route::post('login', 'ApiController@login');
	Route::post('register', 'ApiController@register');
	Route::get('register/verify/{code}', 'ApiController@verify');

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

	Route::group(['middleware' => 'auth.jwt'], function(){		
		//Login
		Route::get('logout', 'ApiController@logout');
		
		//Evento
		Route::post('subscribeEvent', 'PersonController@subscribeEvent');
		Route::post('unsubscribeEvent', 'PersonController@unsubscribeEvent');
		Route::get('isParticipatingEvent', 'PersonController@isParticipatingEvent');
		Route::get('eventPerson', 'PersonController@eventPerson');

		//Actividades
		Route::post('subscribeActivity', 'PersonController@subscribeActivity');
		Route::post('unsubscribeActivity', 'PersonController@unsubscribeActivity');

		Route::get('activitiesPerson', 'PersonController@activitiesPerson');

		//Persona
		//Route::get('persons', 'PersonController@index')
		Route::get('persons/get', 'PersonController@get');

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