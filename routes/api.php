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
	Auth::routes();
	Route::apiResource('users', 'UserController')->middleware('auth:api');
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
});