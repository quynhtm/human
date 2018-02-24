<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 02/2018
* @Version   : 1.0
*/
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\HrMail;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Loader;
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
    public function view(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['hr_mail_name'] = addslashes(Request::get('hr_mail_name',''));
        $dataSearch['hr_mail_type'] = addslashes(Request::get('hr_mail_type', -1));
        $dataSearch['hr_mail_status'] = (int)Request::get('hr_mail_status', -1);
        $dataSearch['field_get'] = '';

        $data = HrMail::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['hr_mail_status']);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Mail.view',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrStatus'=>$this->arrStatus,
            'arrPersion'=>$this->arrPersion,
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
            $data = HrMail::getItemById($id);
        }
        $this->getDataDefault();

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['hr_mail_status'])? $data['hr_mail_status']: CGlobal::status_show);
        $this->viewPermission = $this->getPermissionPage();

        return view('hr.Mail.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'optionStatus'=>$optionStatus,
        ],$this->viewPermission));
    }
    public function postItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $data = $_POST;
        $id_hiden = (int)FunctionLib::outputId($data['id_hiden']);

        if(isset($data['hr_mail_type'])) {
            $data['hr_mail_type'] = (int)$data['hr_mail_type'];
        }
        if(isset($data['hr_mail_created'])) {
            $data['hr_mail_created'] = FunctionLib::convertDate($data['hr_mail_created']);
        }
        if(isset($data['hr_mail_date_send'])) {
            $data['hr_mail_date_send'] = FunctionLib::convertDate($data['hr_mail_date_send']);
        }
        if(isset($data['hr_mail_update'])) {
            $data['hr_mail_update'] = FunctionLib::convertDate($data['hr_mail_update']);
        }
        if(isset($data['hr_mail_status'])) {
            $data['hr_mail_status'] = (int)($data['hr_mail_status']);
        }

        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if($id > 0) {
                if(HrMail::updateItem($id, $data)) {
                    if(isset($data['clickPostPageNext'])){
                        return Redirect::route('hr.HrMailEdit', array('id'=>FunctionLib::inputId(0)));
                    }else{
                        return Redirect::route('hr.HrMailView');
                    }
                }
            }else{
                if(HrMail::createItem($data)) {
                    if(isset($data['clickPostPageNext'])){
                        return Redirect::route('hr.HrMailEdit', array('id'=>FunctionLib::inputId(0)));
                    }else{
                        return Redirect::route('hr.HrMailView');
                    }
                }
            }
        }

        $this->getDataDefault();


        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['hr_mail_status'])? $data['hr_mail_status']: CGlobal::status_show);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Mail.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,

        ],$this->viewPermission));
    }
    public function deleteHrMail(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if ($id > 0 && HrMail::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['hr_mail_type']) && trim($data['hr_mail_type']) == '') {
                $this->error[] = 'Loại văn bản không được rỗng';
            }
            if(isset($data['hr_mail_name']) && trim($data['hr_mail_name']) == '') {
                $this->error[] = 'Tên văn bản không được rỗng';
            }
        }
        return true;
    }
}
