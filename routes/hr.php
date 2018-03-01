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

/*Hộ chiếu chưng minh thư*/
Route::get('passport/edit/{person_id?}', array('as' => 'hr.passportEdit','uses' => HResources.'\PassportController@getItem'));
Route::post('passport/edit/{person_id?}', array('as' => 'hr.passportEdit','uses' => HResources.'\PassportController@postItem'));

/*Nghỉ việc, chuyển công tác, chuyển phòng ban*/
Route::get('quitJob/editJob/{person_id?}', array('as' => 'hr.quitJobEdit','uses' => HResources.'\QuitJobController@getQuitJob'));
Route::post('quitJob/editJob/{person_id?}', array('as' => 'hr.quitJobEdit','uses' => HResources.'\QuitJobController@postQuitJob'));
Route::get('quitJob/editMove/{person_id?}', array('as' => 'hr.quitJobEditMove','uses' => HResources.'\QuitJobController@getJobMove'));
Route::post('quitJob/editMove/{person_id?}', array('as' => 'hr.quitJobEditMove','uses' => HResources.'\QuitJobController@postJobMove'));
Route::get('quitJob/editMoveDepart/{person_id?}', array('as' => 'hr.quitJobEditMoveDepart','uses' => HResources.'\QuitJobController@getJobMoveDepart'));
Route::post('quitJob/editMoveDepart/{person_id?}', array('as' => 'hr.quitJobEditMoveDepart','uses' => HResources.'\QuitJobController@postJobMoveDepart'));

/*Thiết lập thời gian nghỉ hưu*/
Route::get('retirement/edit/{person_id?}', array('as' => 'hr.retirementEdit','uses' => HResources.'\RetirementController@getItem'));
Route::post('retirement/edit/{person_id?}', array('as' => 'hr.retirementEdit','uses' => HResources.'\RetirementController@postItem'));
Route::get('retirement/editTime/{person_id?}', array('as' => 'hr.retirementEditTime','uses' => HResources.'\RetirementController@getItemTime'));
Route::post('retirement/editTime/{person_id?}', array('as' => 'hr.retirementEditTime','uses' => HResources.'\RetirementController@postItemTime'));

/*Bổ nhiêm nhiêm chức vụ*/
Route::get('jobAssignment/viewJobAssignment/{person_id?}', array('as' => 'hr.viewJobAssignment','uses' => HResources.'\JobAssignmentController@viewJobAssignment'));
Route::get('jobAssignment/editJobAssignment', array('as' => 'hr.editJobAssignment','uses' => HResources.'\JobAssignmentController@editJobAssignment'));
Route::post('jobAssignment/postJobAssignment', array('as' => 'hr.postJobAssignment','uses' => HResources.'\JobAssignmentController@postJobAssignment'));
Route::post('jobAssignment/deleteJobAssignment', array('as' => 'hr.deleteJobAssignment','uses' => HResources.'\JobAssignmentController@deleteJobAssignment'));

/*
 * Thông tin đào tạo, công tác: lý lịch 2C
 */
Route::get('curriculumVitaePerson/viewCurriculumVitae/{person_id?}', array('as' => 'hr.viewCurriculumVitae','uses' => HResources.'\CurriculumVitaePersonController@viewCurriculumVitae'));
/*Quan hệ gia đình*/
Route::get('curriculumVitaePerson/editFamily', array('as' => 'hr.editFamily','uses' => HResources.'\CurriculumVitaePersonController@editFamily'));
Route::post('curriculumVitaePerson/postFamily', array('as' => 'hr.postFamily','uses' => HResources.'\CurriculumVitaePersonController@postFamily'));
Route::post('curriculumVitaePerson/deleteFamily', array('as' => 'hr.deleteFamily','uses' => HResources.'\CurriculumVitaePersonController@deleteFamily'));
/*Quan ly dao tạo, học tập*/
Route::get('curriculumVitaePerson/editStudy', array('as' => 'hr.editStudy','uses' => HResources.'\CurriculumVitaePersonController@editStudy'));
Route::post('curriculumVitaePerson/postStudy', array('as' => 'hr.postStudy','uses' => HResources.'\CurriculumVitaePersonController@postStudy'));
Route::post('curriculumVitaePerson/deleteStudy', array('as' => 'hr.deleteStudy','uses' => HResources.'\CurriculumVitaePersonController@deleteStudy'));


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

/*thông tin Document: mail, document */
Route::match(['GET','POST'],'mail/viewsend', array('as' => 'hr.HrMailViewSend','uses' => HResources.'\HrMailController@viewSend'));
Route::match(['GET','POST'],'mail/viewget', array('as' => 'hr.HrMailViewGet','uses' => HResources.'\HrMailController@viewGet'));
Route::match(['GET','POST'],'mail/viewdraft', array('as' => 'hr.HrMailViewDraft','uses' => HResources.'\HrMailController@viewDraft'));
Route::get('mail/viewItemGet/{id?}',array('as' => 'hr.HrMailViewItemGet','uses' => HResources.'\HrMailController@viewItemGet'));
Route::get('mail/viewItemSend/{id?}',array('as' => 'hr.HrMailViewItemSend','uses' => HResources.'\HrMailController@viewItemSend'));
Route::get('mail/viewItemDraft/{id?}',array('as' => 'hr.HrMailViewItemDraft','uses' => HResources.'\HrMailController@viewItemDraft'));

Route::get('mail/edit/{id?}',array('as' => 'hr.HrMailEdit','uses' => HResources.'\HrMailController@getItem'));
Route::post('mail/edit/{id?}', array('as' => 'hr.HrMailEdit','uses' => HResources.'\HrMailController@postItem'));
Route::get('mail/deleteHrMail', array('as' => 'hr.deleteHrMail','uses' => HResources.'\HrMailController@deleteHrMail'));

Route::match(['GET','POST'],'document/view', array('as' => 'hr.HrDocumentView','uses' => HResources.'\HrDocumentController@view'));
Route::get('document/edit/{id?}',array('as' => 'hr.HrDocumentEdit','uses' => HResources.'\HrDocumentController@getItem'));
Route::post('document/edit/{id?}', array('as' => 'hr.HrDocumentEdit','uses' => HResources.'\HrDocumentController@postItem'));
Route::get('document/deleteHrDocument', array('as' => 'hr.deleteHrDocument','uses' => HResources.'\HrDocumentController@deleteHrDocument'));

