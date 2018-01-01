<?php

/*thông tin Department */
Route::match(['GET','POST'],'department/view', array('as' => 'admin.user_view','uses' => Admin.'\AdminUserController@view'));
Route::get('department/edit/{id?}', array('as' => 'admin.departmentEdit','uses' => HResources.'\AdminManageMenuController@getItem'));
Route::post('department/edit/{id?}', array('as' => 'admin.departmentEdit','uses' => HResources.'\AdminManageMenuController@postItem'));
Route::post('department/deleteMenu', array('as' => 'admin.deleteDepartment','uses' => HResources.'\AdminManageMenuController@deleteMenu'));//ajax
