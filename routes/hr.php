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
Route::get('infoPerson/viewContracts/{person_id?}', array('as' => 'hr.viewContracts','uses' => HResources.'\InfoPersonController@viewContracts'));
Route::get('infoPerson/EditContracts', array('as' => 'hr.EditContracts','uses' => HResources.'\InfoPersonController@editContracts'));
Route::post('infoPerson/PostContracts', array('as' => 'hr.PostContracts','uses' => HResources.'\InfoPersonController@postContracts'));
Route::post('infoPerson/DeleteContracts', array('as' => 'hr.DeleteContracts','uses' => HResources.'\InfoPersonController@deleteContracts'));

/*Thông tin khen thuong*/
Route::get('bonusPerson/viewBonus/{person_id?}', array('as' => 'hr.viewBonus','uses' => HResources.'\BonusPersonController@viewBonus'));
Route::get('bonusPerson/editBonus', array('as' => 'hr.editBonus','uses' => HResources.'\BonusPersonController@editBonus'));
Route::post('bonusPerson/postBonus', array('as' => 'hr.postBonus','uses' => HResources.'\BonusPersonController@postBonus'));
Route::post('bonusPerson/deleteBonus', array('as' => 'hr.deleteBonus','uses' => HResources.'\BonusPersonController@deleteBonus'));


Route::get('infoPerson/viewInfoPersonOther/{person_id?}', array('as' => 'hr.viewInfoPersonOther','uses' => HResources.'\InfoPersonController@viewInfoPersonOther'));
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
Route::post('defined/importDataToExcel',array('as' => 'hr.importDataToExcel','uses' => HResources.'\HrDefinedController@importDataToExcel'));


/*thông tin Device */
Route::match(['GET','POST'],'device/view', array('as' => 'hr.deviceView','uses' => HResources.'\DeviceController@view'));
Route::match(['GET','POST'],'device/viewDeviceUse', array('as' => 'hr.viewDeviceUse','uses' => HResources.'\DeviceController@viewDeviceUse'));
Route::match(['GET','POST'],'device/viewDeviceNotUse', array('as' => 'hr.viewDeviceNotUse','uses' => HResources.'\DeviceController@viewDeviceNotUse'));
Route::get('device/edit/{id?}',array('as' => 'hr.deviceEdit','uses' => HResources.'\DeviceController@getItem'));
Route::post('device/edit/{id?}', array('as' => 'hr.deviceEdit','uses' => HResources.'\DeviceController@postItem'));
Route::get('device/deleteDevice', array('as' => 'hr.deleteDevice','uses' => HResources.'\DeviceController@deleteDevice'));
