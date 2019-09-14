<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	$user = Auth::user();
	if ($user){
		return redirect('/home');			
	}else{
		return view('welcome');
	}
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('story', 'StoryController');
Route::get('/story/{id}/duplicate', 'StoryController@duplicate');
Route::get('/story/{id}/share', 'StoryController@share')->name("share");
Route::post('/story/{id}/share', 'StoryController@share');



Route::get('/story/{id}/character', 'CharacterController@index')->name('character');
Route::resource('character', 'CharacterController');
Route::get('/story/{id}/character/create', 'CharacterController@create');
Route::get('/story/{id}/character/{character_id}',  function ($id, $character_id) {
	return redirect("/story/".$id."/character/". $character_id."/edit");
});


Route::get('/story/{id}/background', 'BackgroundController@index')->name('background');
Route::resource('background', 'BackgroundController');
Route::get('/story/{id}/background/create', 'BackgroundController@create');


Route::get('/story/{id}/music', 'MusicController@index')->name('music');
Route::resource('music', 'MusicController');
Route::get('/story/{id}/music/create', 'MusicController@create');


Route::get('/story/{id}/thing', 'ThingController@index')->name('thing');
Route::resource('thing', 'ThingController');
Route::get('/story/{id}/thing/create', 'ThingController@create');


Route::get('/story/{id}/scene', 'SceneController@index')->name('scene');
Route::resource('scene', 'SceneController');
Route::get('/story/{id}/scene/create', 'SceneController@create');
Route::get('/story/{id}/scene/addone', 'SceneController@addone');
Route::get('/scene/{id}/duplicate', 'SceneController@duplicate');


Route::get('/story/{id}/character/{character_id}/behaviour', 'BehaviourController@index')->name('behaviour');
Route::resource('behaviour', 'BehaviourController');
Route::get('/story/{id}/character/{character_id}/behaviour/create', 'BehaviourController@create');
Route::get('/story/{id}/character/{character_id}/behaviours', 'BehaviourController@show');


Route::get('/story/{id}/background/{character_id}/different', 'DifferentController@index')->name('different');
Route::resource('different', 'DifferentController');
Route::get('/story/{id}/background/{background_id}/different/create', 'DifferentController@create');
Route::get('/story/{id}/background/{background_id}/differents', 'DifferentController@show');


Route::get('/story/{id}/action', 'ActionController@index')->name('action');
Route::resource('action', 'ActionController');
Route::get('/story/{id}/action/create', 'ActionController@create');
Route::post('/story/{story_id}/scene/{scene_id}/add_action', 'ActionController@add_action');
Route::get('/story/{story_id}/scene/{scene_id}', 'ActionController@show');
Route::post('/story/{story_id}/scene/{scene_id}/delete_action/{action_id}', 'ActionController@delete_action');
Route::get('/story/{story_id}/scene/{scene_id}/edit_action/{action_id}', 'ActionController@edit_action');