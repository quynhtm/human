<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\HrWageStepConfig;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\URL;

class HrWageStepConfigController extends BaseAdminController{
    private $permission_view = 'wagestepconfig_view';
    private $permission_full = 'wagestepconfig_full';
    private $permission_delete = 'wagestepconfig_delete';
    private $permission_create = 'wagestepconfig_create';
    private $permission_edit = 'wagestepconfig_edit';
    private $arrStatus = array();
    private $arrWageStepConfigType = array();
    private $error = array();
    private $arrPersion = array();
    private $viewPermission = array();

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý thang bảng lương';
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
            'permission_full'=>in_array($this->permission_full, $this->permission) ? 1 : 0
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

        $dataSearch['wage_step_config_name'] = addslashes(Request::get('wage_step_config_name',''));
        $dataSearch['wage_step_config_type'] = Define::type_thang_bang_luong;
        $dataSearch['wage_step_config_status'] = (int)Request::get('wage_step_config_status', -2);
        $dataSearch['field_get'] = '';

        $data = HrWageStepConfig::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['wage_step_config_status']);
        $optionWageStepConfigType = FunctionLib::getOption($this->arrWageStepConfigType, isset($data['wage_step_config_type'])? $data['wage_step_config_type']: CGlobal::status_show);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.WageStepConfigType.view',array_merge([
            'data'=>$data,
            'search'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrStatus'=>$this->arrStatus,
            'arrWageStepConfigType'=>$this->arrWageStepConfigType,
            'optionWageStepConfigType'=>$optionWageStepConfigType,
            'arrPersion'=>$this->arrPersion,
        ],$this->viewPermission));
    }
    public function postItem($ids) {

        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $arrSucces = ['isOk' => 0];
        $id_hiden = (int)Request::get('id', 0);
        $data = $_POST;
        unset($data['id']);
        $data['wage_step_config_order'] = (int)($data['wage_step_config_order']);
        if ($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {
                $data['wage_step_config_type'] = Define::type_thang_bang_luong;
                HrWageStepConfig::updateItem($id, $data);
            } else {
                $data['wage_step_config_type'] = Define::type_thang_bang_luong;
                HrWageStepConfig::createItem($data);
            }
            $arrSucces['isOk'] = 1;
            $arrSucces['url'] = URL::route('hr.wageStepConfigView', array('wage_step_config_type' => Define::type_thang_bang_luong));
            return $arrSucces;
        }
        return $arrSucces;













        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;

        if(isset($data['wage_step_config_type'])) {
            $data['wage_step_config_type'] = (int)$data['wage_step_config_type'];
        }
        $data['wage_step_config_status'] = (int)($data['wage_step_config_status']);

        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if($id > 0) {
                if(HrWageStepConfig::updateItem($id, $data)) {
                    return Redirect::route('hr.wageStepConfigView');
                }
            }else{
                if(HrWageStepConfig::createItem($data)) {
                    return Redirect::route('hr.wageStepConfigView');
                }
            }
        }

        $this->getDataDefault();

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['wage_step_config_status'])? $data['wage_step_config_status']: CGlobal::status_show);
        $optionWageStepConfigType = FunctionLib::getOption($this->arrWageStepConfigType, isset($data['wage_step_config_type'])? $data['wage_step_config_type']: CGlobal::status_show);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.WageStepConfigType.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,
            'optionWageStepConfigType'=>$optionWageStepConfigType,

        ],$this->viewPermission));
    }
    public function deleteWageStepConfig(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if ($id > 0 && HrWageStepConfig::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['wage_step_config_type']) && trim($data['wage_step_config_type']) == '') {
                $this->error[] = 'Loại không được rỗng';
            }
            if(isset($data['wage_step_config_name']) && trim($data['wage_step_config_name']) == '') {
                $this->error[] = 'Tên không được rỗng';
            }
        }
        return true;
    }
    public function ajaxLoadForm(){
        $ids = $_POST['id'];
        $id = FunctionLib::outputId($ids);
        $data['wage_step_config_id'] = 0;
        if($id > 0) {
            $data = HrWageStepConfig::find($id);
        }
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['wage_step_config_status']) ? $data['wage_step_config_status'] : CGlobal::status_show);

        return view('hr.WageStepConfigType.ajaxLoadForm',
            array_merge([
                'data' => $data,
                'optionStatus' => $optionStatus,
            ], $this->viewPermission));
    }
}
