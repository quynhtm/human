<?php

Route::match(['GET','POST'],'cronjob/view', array('as' => 'cr.CronjobView','uses' => Cronjob.'\CronjobUserController@view'));
Route::get('cronjob/edit/{id?}',array('as' => 'cr.CronjobEdit','uses' => Cronjob.'\CronjobUserController@getItem'));
Route::post('cronjob/edit/{id?}', array('as' => 'cr.CronjobEdit','uses' => Cronjob.'\CronjobUserController@postItem'));
Route::get('cronjob/deleteCronjob', array('as' => 'cr.deleteCronjob','uses' => Cronjob.'\CronjobUserController@deleteCronjob'));

Route::match(['GET','POST'],'callRunCronjob', array('as' => 'cr.callRunCronjob','uses' => Cronjob.'\CronjobHrController@callRunCronjob'));

//CronjobHrController
Route::match(['GET','POST'],'runCronjobQuitJob', array('as' => 'cr.runCronjobQuitJob','uses' => Cronjob.'\CronjobHrController@runCronjobQuitJob'));
Route::match(['GET','POST'],'runCronjobMoveJob', array('as' => 'cr.runCronjobMoveJob','uses' => Cronjob.'\CronjobHrController@runCronjobMoveJob'));
Route::match(['GET','POST'],'runCronjobRetirement', array('as' => 'cr.runCronjobRetirement','uses' => Cronjob.'\CronjobHrController@runCronjobRetirement'));
