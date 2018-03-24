<?php
Route::get('/', array('as' => 'admin.login','uses' => Admin.'\AdminLoginController@loginInfo'));
Route::post('/', array('as' => 'admin.login','uses' => Admin.'\AdminLoginController@login'));