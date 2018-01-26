<?php

/*thông tin Department */
Route::match(['GET','POST'],'department/view', array('as' => 'hr.departmentView','uses' => HResources.'\HrDepartmentController@view'));
Route::get('department/edit/{id?}',array('as' => 'hr.departmentEdit','uses' => HResources.'\HrDepartmentController@getItem'));
Route::post('department/edit/{id?}', array('as' => 'hr.departmentEdit','uses' => HResources.'\HrDepartmentController@postItem'));
Route::get('department/deleteDepartment', array('as' => 'hr.deleteDepartment','uses' => HResources.'\HrDepartmentController@deleteDepartment'));


/*thông tin Nhân sự */
Route::match(['GET','POST'],'personnel/view', array('as' => 'hr.personnelView','uses' => HResources.'\PersonController@view'));
Route::get('personnel/edit/{id?}', array('as' => 'hr.personnelEdit','uses' => HResources.'\PersonController@getItem'));
Route::post('personnel/edit/{id?}', array('as' => 'hr.personnelEdit','uses' => HResources.'\PersonController@postItem'));

/*Thông tin hợp đồng lao động*/
Route::get('infoPerson/viewInfoPersonOther/{person_id?}', array('as' => 'hr.viewInfoPersonOther','uses' => HResources.'\InfoPersonController@viewInfoPersonOther'));
Route::get('infoPerson/viewContracts/{person_id?}', array('as' => 'hr.viewContracts','uses' => HResources.'\InfoPersonController@viewContracts'));
Route::get('infoPerson/viewTransferWork/{person_id?}', array('as' => 'hr.viewTransferWork','uses' => HResources.'\InfoPersonController@viewTransferWork'));
Route::get('infoPerson/viewTransferDepartment/{person_id?}', array('as' => 'hr.viewTransferDepartment','uses' => HResources.'\InfoPersonController@viewTransferDepartment'));
/*Thông tin tạo tài khoản từ person*/
Route::get('infoPerson/getInfoPerson/{person_id?}', array('as' => 'hr.getInfoPerson','uses' => HResources.'\InfoPersonController@getInfoPerson'));
Route::post('infoPerson/getInfoPerson/{person_id?}', array('as' => 'hr.getInfoPerson','uses' => HResources.'\InfoPersonController@postInfoPerson'));

/*Định nghĩa chung*/
Route::match(['GET','POST'],'defined/view',array('as' => 'hr.definedView','uses' => HResources.'\HrDefinedController@view'));
Route::post('defined/edit/{id?}',array('as' => 'hr.definedEdit','uses' => HResources.'\HrDefinedController@postItem'));
Route::get('defined/deleteDefined',array('as' => 'hr.deleteDefined','uses' => HResources.'\HrDefinedController@deleteDefined'));
Route::post('defined/ajaxLoadForm',array('as' => 'hr.loadForm','uses' => HResources.'\HrDefinedController@ajaxLoadForm'));


/*thông tin Device */
Route::match(['GET','POST'],'device/view', array('as' => 'hr.deviceView','uses' => HResources.'\DeviceController@view'));
Route::get('device/edit/{id?}',array('as' => 'hr.deviceEdit','uses' => HResources.'\DeviceController@getItem'));
Route::post('device/edit/{id?}', array('as' => 'hr.deviceEdit','uses' => HResources.'\DeviceController@postItem'));
Route::get('device/deleteDepartment', array('as' => 'hr.deleteDevice','uses' => HResources.'\DeviceController@deleteDevice'));

