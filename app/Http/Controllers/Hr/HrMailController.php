<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 02/2018
* @Version   : 1.0
*/
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\User;
use App\Http\Models\Hr\HrMail;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Loader;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class HrMailController extends BaseAdminController{
    private $permission_view = 'hr_mail_view';
    private $permission_full = 'hr_mail_full';
    private $permission_delete = 'hr_mail_delete';
    private $permission_create = 'hr_mail_create';
    private $permission_edit = 'hr_mail_edit';
    private $arrStatus = array();
    private $error = array();
    private $arrPersion = array();
    private $viewPermission = array();

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý văn bản, thư gửi';
    }
    public function getDataDefault(){
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden',$this->languageSite)
        );
    }
    public function getPermissionPage(){
        return $this->viewPermission = [
            'is_root'=> $this->is_root ? 1:0,
            'permission_edit'=>in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create'=>in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_remove'=>in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full'=>in_array($this->permission_full, $this->permission) ? 1 : 0,
        ];
    }

    public function viewSend(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['hr_mail_name'] = addslashes(Request::get('hr_mail_name',''));
        $dataSearch['hr_mail_status'] = Define::mail_da_gui;
        $dataSearch['hr_mail_person_send'] = $this->user['user_id'];
        $dataSearch['hr_mail_type'] = Define::mail_type_0;
        $dataSearch['field_get'] = '';

        $data = HrMail::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Mail.viewSend',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'arrStatus'=>$this->arrStatus,
            'arrPersion'=>$this->arrPersion,
        ],$this->viewPermission));
    }
    public function viewGet(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['hr_mail_name'] = addslashes(Request::get('hr_mail_name',''));
        $dataSearch['hr_mail_status'] = (int)Request::get('hr_mail_status', -1);
        $dataSearch['hr_mail_person_recive'] = $this->user['user_id'];
        $dataSearch['hr_mail_type'] = Define::mail_type_1;
        $dataSearch['field_get'] = '';
        $data = HrMail::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Mail.viewGet',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'arrStatus'=>$this->arrStatus,
            'arrPersion'=>$this->arrPersion,
        ],$this->viewPermission));
    }
    public function viewDraft(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['hr_mail_name'] = addslashes(Request::get('hr_mail_name',''));
        $dataSearch['hr_mail_status'] = Define::mail_nhap;
        $dataSearch['hr_mail_type'] = -1;
        $dataSearch['hr_mail_person_send'] = $this->user['user_id'];
        $dataSearch['field_get'] = '';

        $data = HrMail::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Mail.viewDraft',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'arrStatus'=>$this->arrStatus,
            'arrPersion'=>$this->arrPersion,
        ],$this->viewPermission));
    }

    public function viewItemSend($ids){
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);

        if($id > 0) {
            $user_id = $this->user['user_id'];
            $data = HrMail::getItemByIdAndPersonSendId($id, $user_id);
            if(sizeof($data) == 0){
                return Redirect::route('hr.HrMailViewSend');
            }
        }else{
            return Redirect::route('hr.HrMailViewSend');
        }
        $this->getDataDefault();

       $this->viewPermission = $this->getPermissionPage();

        return view('hr.Mail.viewItemSend',array_merge([
            'data'=>$data,
            'arrUser'=>$arrUser,
            'id'=>$id,
        ],$this->viewPermission));
    }
    public function viewItemGet($ids) {

        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);
        if($id > 0) {
            $user_id = $this->user['user_id'];
            $data = HrMail::getItemByIdAndPersonReciveId($id, $user_id);
            if(sizeof($data) == 0){
                return Redirect::route('hr.HrMailViewGet');
            }else{
                $dataUpdate['hr_mail_status'] = Define::mail_da_doc;
                HrMail::updateItem($id, $dataUpdate);
            }
        }else{
            return Redirect::route('hr.HrMailViewGet');
        }
        $this->getDataDefault();

        $this->viewPermission = $this->getPermissionPage();

        return view('hr.Mail.viewItemGet',array_merge([
            'data'=>$data,
            'arrUser'=>$arrUser,
            'id'=>$id,
        ],$this->viewPermission));
    }
    public function viewItemDraft($ids) {

        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        Loader::loadCSS('lib/multiselect/fastselect.min.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/multiselect/fastselect.min.js', CGlobal::$POS_HEAD);

        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        if($id > 0) {
            $user_id = $this->user['user_id'];
            $data = HrMail::getItemDraftById($id, $user_id);
            if(sizeof($data) == 0){
                return Redirect::route('hr.HrMailViewDraft');
            }
        }

        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);

        $this->getDataDefault();

        $this->viewPermission = $this->getPermissionPage();

        return view('hr.Mail.viewItemDraft',array_merge([
            'data'=>$data,
            'id'=>$id,
            'arrUser'=>$arrUser,
        ],$this->viewPermission));
    }

    public function getItem($ids) {

        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        Loader::loadCSS('lib/multiselect/fastselect.min.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/multiselect/fastselect.min.js', CGlobal::$POS_HEAD);

        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        if($id > 0) {
            $data = HrMail::getItemById($id);
            if(sizeof($data) > 0){
                $user_id = $this->user['user_id'];
               if($data->hr_mail_person_send != $user_id){
                   return Redirect::route('hr.HrMailEdit', array('id'=>FunctionLib::inputId(0)));
               }
            }
        }

        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);

        $this->getDataDefault();

        $this->viewPermission = $this->getPermissionPage();

        return view('hr.Mail.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'arrUser'=>$arrUser,
        ],$this->viewPermission));
    }
    public function postItem($ids) {

        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        Loader::loadCSS('lib/multiselect/fastselect.min.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/multiselect/fastselect.min.js', CGlobal::$POS_HEAD);

        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $data = $_POST;
        $id_hiden = (int)FunctionLib::outputId($data['id_hiden']);
        $data['hr_mail_status'] = Define::mail_nhap;

        if(isset($data['submitMailSend']) && $data['submitMailSend'] == 'submitMailSend'){
            $this->valid($data);
        }

        if(sizeof($this->error) == 0) {
            $id = ($id == 0) ? $id_hiden : $id;
            if($id > 0) {
                $data['hr_mail_status'] = -1;
                unset($data['hr_mail_person_send']);
                $data['hr_mail_person_recive_list'] = (isset($data['hr_mail_person_recive_list']) &&  sizeof($data['hr_mail_person_recive_list']) > 0 ) ? implode(',', $data['hr_mail_person_recive_list']) : '';
                $data['hr_mail_send_cc'] = (isset($data['hr_mail_send_cc']) &&  sizeof($data['hr_mail_send_cc']) > 0 ) ? implode(',', $data['hr_mail_send_cc']) : '';
                if(isset($data['submitMailDraft'])){
                    $data['hr_mail_status'] = Define::mail_nhap;
                    $data['hr_mail_type'] = -1;
                    HrMail::updateItem($id, $data);
                }else{
                    $data['hr_mail_date_send'] = time();
                    $data['hr_mail_type'] = Define::mail_type_0;
                    $data['hr_mail_status'] = Define::mail_da_gui;
                    $mailId = HrMail::updateItem($id, $data);
                    if($mailId > 0){
                        $getItem = HrMail::getItemById($mailId);
                        //To
                        $hr_mail_person_recive = (isset($getItem['hr_mail_person_recive_list']) &&  $getItem['hr_mail_person_recive_list'] != '') ? explode(',', $getItem['hr_mail_person_recive_list']) : array();
                        $this->sendDataToUsers($hr_mail_person_recive, $getItem);
                        //CC
                        $hr_mail_send_cc = (isset($getItem['hr_mail_send_cc']) && $getItem['hr_mail_send_cc'] != '') ? explode(',', $getItem['hr_mail_send_cc']) : array();
                        $this->sendDataToUsers($hr_mail_send_cc, $getItem);
                    }
                }
                return Redirect::route('hr.HrMailViewGet');
            }else{
                $data['hr_mail_created'] = time();
                $data['hr_mail_person_send'] = $this->user['user_id'];
                if(isset($data['submitMailDraft'])){
                    $data['hr_mail_status'] = Define::mail_nhap;
                    $data['hr_mail_type'] = -1;
                }else{
                    $data['hr_mail_type'] = Define::mail_type_0;
                    $data['hr_mail_status'] = Define::mail_da_gui;
                    $data['hr_mail_date_send'] = time();
                }

                $data['hr_mail_person_recive_list'] = (isset($data['hr_mail_person_recive_list']) && sizeof($data['hr_mail_person_recive_list']) > 0) ? implode(',', $data['hr_mail_person_recive_list']) : '';
                $data['hr_mail_send_cc'] = (isset($data['hr_mail_send_cc']) && sizeof($data['hr_mail_send_cc']) > 0) ? implode(',', $data['hr_mail_send_cc']) : '';

                $mailId = HrMail::createItem($data);

                if(!isset($data['submitMailDraft'])){
                    $getItem = HrMail::getItemById($mailId);
                    //To
                    $hr_mail_person_recive = (isset($getItem['hr_mail_person_recive_list']) &&  $getItem['hr_mail_person_recive_list'] != '') ? explode(',', $getItem['hr_mail_person_recive_list']) : array();
                    $this->sendDataToUsers($hr_mail_person_recive, $getItem);
                    //CC
                    $hr_mail_send_cc = (isset($getItem['hr_mail_send_cc']) && $getItem['hr_mail_send_cc'] != '') ? explode(',', $getItem['hr_mail_send_cc']) : array();
                    $this->sendDataToUsers($hr_mail_send_cc, $getItem);
                }

                return Redirect::route('hr.HrMailViewGet');
            }
        }

        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);

        $this->getDataDefault();

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['hr_mail_status'])? $data['hr_mail_status']: CGlobal::status_show);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Mail.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,
            'arrUser'=>$arrUser,

        ],$this->viewPermission));
    }
    public function deleteHrMail(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if ($id > 0) {
            $getItem = HrMail::getItemById($id);
            $user_id = $this->user['user_id'];
            $data['isIntOk'] = 0;
            if(sizeof($getItem) > 0){
                if(($getItem->hr_mail_type == Define::mail_type_0 || $getItem->hr_mail_type == -1) && $getItem->hr_mail_person_send == $user_id){
                    HrMail::deleteItem($id);
                    $data['isIntOk'] = 1;
                }
                if($getItem->hr_mail_type == Define::mail_type_1 && $getItem->hr_mail_person_recive == $user_id){
                    HrMail::deleteItem($id);
                    $data['isIntOk'] = 1;
                }
            }
        }
        return Response::json($data);
    }
    public function ajaxItemForward() {

        $id = FunctionLib::outputId(Request::get('current_id', 0));if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        Loader::loadCSS('lib/multiselect/fastselect.min.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/multiselect/fastselect.min.js', CGlobal::$POS_HEAD);

        $data = array();
        if($id > 0) {
            $data = HrMail::getItemById($id);
        }

        $dataUser = User::getList();
        $arrUser = $this->getArrayUserFromData($dataUser);

        $this->getDataDefault();

        $this->viewPermission = $this->getPermissionPage();

        return view('hr.Mail.ajaxForward',array_merge([
            'data'=>$data,
            'id'=>$id,
            'arrUser'=>$arrUser,
        ],$this->viewPermission));
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(!isset($data['hr_mail_person_recive_list'])) {
                $this->error[] = 'Người nhận không được trống.';
            }elseif(isset($data['hr_mail_send_cc']) && sizeof($data['hr_mail_send_cc']) == 0){
                    $this->error[] = 'CC không được trống.';
            }else{
                if(sizeof($data['hr_mail_person_recive_list']) == 0){
                    $this->error[] = 'Người nhận không được trống.';
                }
            }
        }else{
            $this->error[] = 'Dữ liệu không được trống.';
        }
        return $this->error;
    }
    public function getArrayUserFromData($data=array()){
        $result = array();
        if(sizeof($data) > 0){
            foreach($data as $item){
                if(!in_array($item->user_id, Define::mail_user_unset)){
                    if($item->user_full_name != ''){
                        $result[$item->user_id] = $item->user_full_name;
                    }else{
                        $result[$item->user_id] = $item->user_name;
                    }
                }
            }
        }
        return $result;
    }
    public function sendDataToUsers($dataUser, $getItem){
        if(sizeof($dataUser) > 0 && sizeof($getItem) >0){
            foreach($dataUser as $key=>$recive) {
                $dataRecive['hr_mail_name'] = $getItem->hr_mail_name;
                $dataRecive['hr_mail_content'] = $getItem->hr_mail_content;
                $dataRecive['hr_mail_person_recive'] = (int)$recive;
                $dataRecive['hr_mail_person_recive_list'] = $getItem->hr_mail_person_recive_list;
                $dataRecive['hr_mail_person_send'] = $this->user['user_id'];
                $dataRecive['hr_mail_send_cc'] = $getItem->hr_mail_send_cc;
                $dataRecive['hr_mail_created'] = time();
                $dataRecive['hr_mail_date_send'] = time();
                $dataRecive['hr_mail_files'] = $getItem->hr_mail_files;
                $dataRecive['hr_mail_type'] = Define::mail_type_1;
                $dataRecive['hr_mail_status'] = Define::mail_chua_doc;
                $idMailOther = HrMail::createItem($dataRecive);
                if($getItem->hr_mail_files != '') {
                    $hr_mail_files = ($getItem->hr_mail_files != '') ? unserialize($getItem->hr_mail_files) : array();
                    if(sizeof($hr_mail_files) > 0) {
                        foreach ($hr_mail_files as $key => $file) {
                            $folder_mail = Config::get('config.DIR_ROOT').'uploads/'.Define::FOLDER_MAIL;
                            $path_current = $folder_mail . '/' . $getItem->hr_mail_id . '/' . $file;
                            if(file_exists($path_current)){
                                $folder_copy = $folder_mail . '/' .$idMailOther;
                                $path_copy = $folder_copy . '/' .$file;
                                if(!is_dir($folder_copy)){
                                    @mkdir($folder_copy,0777,true);
                                    @chmod($folder_copy,0777);
                                }
                                @copy($path_current, $path_copy);
                            }
                        }
                    }
                }
            }
        }
    }
}