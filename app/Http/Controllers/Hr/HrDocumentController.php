<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 02/2018
* @Version   : 1.0
*/
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Hr\HrDocument;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Loader;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class HrDocumentController extends BaseAdminController{
    private $permission_view = 'hr_document_view';
    private $permission_full = 'hr_document_full';
    private $permission_delete = 'hr_document_delete';
    private $permission_create = 'hr_document_create';
    private $permission_edit = 'hr_document_edit';
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
        $this->arrPromulgate = array(-1 => '-- Chọn --') + HrDefine::getArrayByType(Define::co_quan_ban_hanh);
        $this->arrType = array(-1 => '-- Chọn --') + HrDefine::getArrayByType(Define::loai_van_ban);
        $this->arrField = array(-1 => '-- Chọn --') + HrDefine::getArrayByType(Define::linh_vuc);
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
    public function view(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['hr_document_name'] = addslashes(Request::get('hr_document_name',''));
        $dataSearch['hr_document_promulgate'] = addslashes(Request::get('hr_document_promulgate', -1));
        $dataSearch['hr_document_field'] = addslashes(Request::get('hr_document_field', -1));
        $dataSearch['hr_document_type'] = (int)Request::get('hr_document_type', -1);
        $dataSearch['hr_document_status'] = (int)Request::get('hr_document_status', -1);
        $dataSearch['field_get'] = '';

        $data = HrDocument::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['hr_document_status']);
        $optionPromulgate = FunctionLib::getOption($this->arrPromulgate, $dataSearch['hr_document_promulgate']);
        $optionType = FunctionLib::getOption($this->arrType, $dataSearch['hr_document_type']);
        $optionField = FunctionLib::getOption($this->arrField, $dataSearch['hr_document_field']);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Document.view',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrStatus'=>$this->arrStatus,
            'optionPromulgate'=>$optionPromulgate,
            'arrPromulgate'=>$this->arrPromulgate,
            'optionType'=>$optionType,
            'arrType'=>$this->arrType,
            'optionField'=>$optionField,
            'arrField'=>$this->arrField,
        ],$this->viewPermission));
    }
    public function getItem($ids) {

        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        if($id > 0) {
            $data = HrDocument::getItemById($id);
        }
        $this->getDataDefault();

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['hr_document_status'])? $data['hr_document_status']: CGlobal::status_show);
        $optionPromulgate = FunctionLib::getOption($this->arrPromulgate, isset($data['hr_document_promulgate'])? $data['hr_document_promulgate']: -1);
        $optionType = FunctionLib::getOption($this->arrType, isset($data['hr_document_type'])? $data['hr_document_type']: -1);
        $optionField = FunctionLib::getOption($this->arrField, isset($data['hr_document_field'])? $data['hr_document_field']: -1);
        $this->viewPermission = $this->getPermissionPage();

        return view('hr.Document.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'optionStatus'=>$optionStatus,
            'optionPromulgate'=>$optionPromulgate,
            'optionType'=>$optionType,
            'optionField'=>$optionField,
        ],$this->viewPermission));
    }
    public function postItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $data = $_POST;
        $id_hiden = (int)FunctionLib::outputId($data['id_hiden']);

        if(isset($data['hr_document_type'])) {
            $data['hr_document_type'] = (int)$data['hr_document_type'];
        }
        if(isset($data['hr_document_created'])) {
            $data['hr_document_created'] = FunctionLib::convertDate($data['hr_document_created']);
        }
        if(isset($data['hr_document_date_issued'])) {
            $data['hr_document_date_issued'] = FunctionLib::convertDate($data['hr_document_date_issued']);
        }
        if(isset($data['hr_document_effective_date'])) {
            $data['hr_document_effective_date'] = FunctionLib::convertDate($data['hr_document_effective_date']);
        }
        if(isset($data['hr_document_date_expired'])) {
            $data['hr_document_date_expired'] = FunctionLib::convertDate($data['hr_document_date_expired']);
        }
        if(isset($data['hr_document_delease_date'])) {
            $data['hr_document_delease_date'] = FunctionLib::convertDate($data['hr_document_delease_date']);
        }
        if(isset($data['hr_document_update'])) {
            $data['hr_document_update'] = FunctionLib::convertDate($data['hr_document_update']);
        }
        if(isset($data['hr_document_status'])) {
            $data['hr_document_status'] = (int)($data['hr_document_status']);
        }

        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if($id > 0) {
                if(HrDocument::updateItem($id, $data)) {
                    if(isset($data['clickPostPageNext'])){
                        return Redirect::route('hr.HrDocumentEdit', array('id'=>FunctionLib::inputId(0)));
                    }else{
                        return Redirect::route('hr.HrDocumentView');
                    }
                }
            }else{
                if(HrDocument::createItem($data)) {
                    if(isset($data['clickPostPageNext'])){
                        return Redirect::route('hr.HrDocumentEdit', array('id'=>FunctionLib::inputId(0)));
                    }else{
                        return Redirect::route('hr.HrDocumentView');
                    }
                }
            }
        }

        $this->getDataDefault();

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['hr_document_status'])? $data['hr_document_status']: CGlobal::status_show);
        $optionPromulgate = FunctionLib::getOption($this->arrPromulgate, isset($data['hr_document_promulgate'])? $data['hr_document_promulgate']: -1);
        $optionType = FunctionLib::getOption($this->arrType, isset($data['hr_document_type'])? $data['hr_document_type']: -1);
        $optionField = FunctionLib::getOption($this->arrField, isset($data['hr_document_field'])? $data['hr_document_field']: -1);
        $this->viewPermission = $this->getPermissionPage();

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Document.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,
            'optionPromulgate'=>$optionPromulgate,
            'optionType'=>$optionType,
            'optionField'=>$optionField,

        ],$this->viewPermission));
    }
    public function deleteHrDocument(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if ($id > 0 && HrDocument::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['hr_document_type']) && trim($data['hr_document_type']) == '') {
                $this->error[] = 'Loại văn bản không được rỗng';
            }
            if(isset($data['hr_document_name']) && trim($data['hr_document_name']) == '') {
                $this->error[] = 'Tên văn bản không được rỗng';
            }
        }
        return true;
    }
}
