<?php

/*thông tin Department */
Route::match(['GET','POST'],'department/view', array('as' => 'hr.departmentView','uses' => HResources.'\HrDepartmentController@view'));
Route::get('department/edit/{id?}', array('as' => 'hr.departmentEdit','uses' => HResources.'\HrDepartmentController@getItem'));
Route::post('department/edit/{id?}', array('as' => 'hr.departmentEdit','uses' => HResources.'\HrDepartmentController@postItem'));
Route::post('department/deleteMenu', array('as' => 'hr.deleteDepartment','uses' => HResources.'\HrDepartmentController@deleteMenu'));//ajax


/*thông tin Nhân sự */
Route::match(['GET','POST'],'personnel/view', array('as' => 'hr.personnelView','uses' => HResources.'\HrPersonnelController@view'));