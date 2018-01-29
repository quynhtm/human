<?php
Auth::routes();

const Admin = "Admin";
const HResources = "Hr";

// Used for dev by Quynh
$isDev = Request::get('is_debug','');
if($isDev == 'tech_code'){
    Session::put('is_debug_of_tech', '13031984');
    Config::set('compile.debug',true);
}
if(Session::has('is_debug_of_tech')){
    Config::set('compile.debug',true);
}

//Quan tri CMS cho admin
Route::get('/', array('as' => 'admin.login','uses' => Admin.'\AdminLoginController@getLogin'));
Route::match(['GET','POST'], 'user/{url?}', array('as' => 'admin.login','uses' => Admin.'\AdminLoginController@postLogin'));

Route::group(array('prefix' => 'manager', 'before' => ''), function(){
	require __DIR__.'/admin.php';
});

//Router HR
Route::group(array('prefix' => 'manager', 'before' => ''), function(){
	require __DIR__.'/hr.php';
});

//Router Api
Route::group(array('prefix' => 'api', 'before' => ''), function () {
    require __DIR__.'/api.php';
});