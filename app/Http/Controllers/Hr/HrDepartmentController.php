<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Department;

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
    private $arrMenuParent = array();
    private $viewPermission = array();

    private $arrDepartmentType = array(-1 => '- Chọn loại đơn vị/ phòng ban -', 0 => 'Phòng ban / bộ phận trực thuộc', 1 => 'Đơn vị hạch toán độc lập (trực thuộc)', 2 => 'Đơn vị độc lập');

    public function __construct(){
        parent::__construct();
        $this->arrMenuParent = Department::getAllParentMenu();
        CGlobal::$pageAdminTitle = 'Quản lý menu';
    }

    public function getDataDefault(){
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden',$this->languageSite));
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
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $pageNo = (int) Request::get('page_no',1);
        $sbmValue = Request::get('submit', 1);
        $limit = 200;
        $offset = ($pageNo - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['menu_name'] = addslashes(Request::get('menu_name',''));
        $search['active'] = (int)Request::get('active',-1);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $dataSearch = Department::searchByCondition($search, $limit, $offset,$total);
        if(!empty($dataSearch)){
            $data = Department::getTreeMenu($dataSearch);
            $data = !empty($data)? $data :$dataSearch;
        }
        $paging = '';

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['active']);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Department.view',array_merge([
            'data'=>$data,
            'search'=>$search,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'optionRoleType'=>$optionStatus,
        ],$this->viewPermission));
    }

    public function getItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        if($id > 0) {
            $data = Department::find($id);
        }

        $this->getDataDefault();
        $optionDepartmentType = FunctionLib::getOption($this->arrDepartmentType, isset($data['department_type'])? $data['department_type']: 0);


        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Department.add',array_merge([
            'data'=>$data,
            'id'=>$id,
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
        $data['ordering'] = (int)($data['ordering']);
        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0)?$id_hiden: $id;
            if($id > 0) {
                //cap nhat
                if(Department::updateItem($id, $data)) {
                    return Redirect::route('hr.departmentView');
                }
            }else{
                //them moi
                if(Department::createItem($data)) {
                    return Redirect::route('hr.departmentView');
                }
            }
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['active'])? $data['active']: CGlobal::status_hide);
        $optionShowContent = FunctionLib::getOption($this->arrStatus, isset($data['showcontent'])? $data['showcontent']: CGlobal::status_show);
        $optionShowMenu = FunctionLib::getOption($this->arrStatus, isset($data['show_menu'])? $data['show_menu']: CGlobal::status_show);
        $optionShowPermission = FunctionLib::getOption($this->arrStatus, isset($data['show_permission'])? $data['show_permission']: CGlobal::status_hide);
        $optionMenuParent = FunctionLib::getOption($this->arrMenuParent, isset($data['parent_id'])? $data['parent_id'] : 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Department.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'arrStatus'=>$this->arrStatus,
            'optionStatus'=>$optionStatus,
            'optionShowContent'=>$optionShowContent,
            'optionShowPermission'=>$optionShowPermission,
            'optionShowMenu'=>$optionShowMenu,
            'optionMenuParent'=>$optionMenuParent,
        ],$this->viewPermission));
    }

    public function deleteMenu(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = (int)Request::get('id', 0);
        if ($id > 0 && Department::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['banner_name']) && trim($data['banner_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}
