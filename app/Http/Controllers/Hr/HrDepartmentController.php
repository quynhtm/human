<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\HrDefine;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class HrDepartmentController extends BaseAdminController
{
    private $permission_view = 'department_view';
    private $permission_full = 'department_full';
    private $permission_delete = 'department_delete';
    private $permission_create = 'department_create';
    private $permission_edit = 'department_edit';
    private $arrStatus = array();
    private $error = array();
    private $viewPermission = array();

    private $arrDepartmentType = array();
    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý đơn vị - phòng ban';
    }
    public function getDataDefault(){
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden',$this->languageSite)
        );
        $this->arrDepartmentType = HrDefine::getArrayByType(Define::loai_donvi_phongban);
    }
    public function getPermissionPage(){
        return $this->viewPermission = [
            'is_root'=> $this->is_root ? 1:0,
            'permission_edit'=>in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create'=>in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_delete'=>in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full'=>in_array($this->permission_full, $this->permission) ? 1 : 0,
        ];
    }
    public function view(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $pageNo = (int) Request::get('page_no',1);
        $limit = 200;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['department_name'] = addslashes(Request::get('department_name',''));
        $search['department_status'] = (int)Request::get('department_status', -1);
        $search['field_get'] = 'department_id,department_type,department_name,department_phone,department_fax,department_parent_id,department_creater_time,department_update_time';

        $dataSearch = Department::searchByCondition($search, $limit, $offset,$total);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['department_status']);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Department.view',array_merge([
            'data'=>$dataSearch,
            'search'=>$search,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrDepartmentType'=>$this->arrDepartmentType,
        ],$this->viewPermission));
    }
    public function getItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        if($id > 0) {
            $data = Department::getItemById($id);
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['department_status'])? $data['department_status']: CGlobal::status_show);
        $optionDepartmentType = FunctionLib::getOption($this->arrDepartmentType, isset($data['department_type'])? $data['department_type']: 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Department.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'optionStatus'=>$optionStatus,
            'optionDepartmentType'=>$optionDepartmentType,
        ],$this->viewPermission));
    }
    public function postItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;

        $data['department_parent_id'] = (int)FunctionLib::outputId($data['department_parent_id']);
        $data['department_type'] = (int)($data['department_type']);
        $data['department_order'] = (int)($data['department_order']);
        $data['department_status'] = (int)($data['department_status']);

        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0)?$id_hiden: $id;
            if($id > 0) {
                $data['department_update_time'] = time();
                $data['department_user_id_update'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;
                $data['department_user_name_update'] = isset($this->user['user_name']) ? $this->user['user_name'] : 0;
                if(Department::updateItem($id, $data)) {
                    return Redirect::route('hr.departmentView');
                }
            }else{
                $data['department_creater_time'] = time();
                $data['department_user_id_creater'] = isset($this->user['user_id']) ? $this->user['user_id'] : 0;
                $data['department_user_name_creater'] = isset($this->user['user_name']) ? $this->user['user_name'] : 0;

                if(Department::createItem($data)) {
                    return Redirect::route('hr.departmentView');
                }
            }
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['department_status'])? $data['department_status']: CGlobal::status_show);
        $optionDepartmentType = FunctionLib::getOption($this->arrDepartmentType, isset($data['department_type'])? $data['department_type']: 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Department.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,
            'optionDepartmentType'=>$optionDepartmentType,

        ],$this->viewPermission));
    }
    public function deleteDepartment(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if ($id > 0 && Department::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['department_name']) && trim($data['department_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}
