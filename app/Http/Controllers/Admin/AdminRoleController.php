<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Role;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use View;

class AdminRoleController extends BaseAdminController{

    private $permission_view = 'role_view';
    private $permission_full = 'role_full';
    private $permission_delete = 'role_delete';
    private $permission_create = 'role_create';
    private $permission_edit = 'role_edit';

    private $error = array();
    private $viewPermission = array();

    private $arrStatus = array(-1 => '--Chọn--', CGlobal::status_hide => 'Ẩn', CGlobal::status_show => 'Hiện');

    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý Role';
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

    public function view() {
        //Check phan quyen.
        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $page_no = (int) Request::get('page_no',1);
        $dataSearch['role_name'] = addslashes(Request::get('role_name_s',''));
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($page_no - 1) * $limit;
        $data = Role::searchByCondition($dataSearch, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3,$page_no,$total,$limit,$dataSearch) : '';

        $this->viewPermission = $this->getPermissionPage();

        $optionStatus = FunctionLib::getOption($this->arrStatus, '');

        return view('admin.AdminRole.view',array_merge([
            'data'=>$data,
            'search'=>$dataSearch,
            'size'=>$total,
            'start'=>($page_no - 1) * $limit,
            'paging'=>$paging,
            'arrStatus'=>$this->arrStatus,
            'optionStatus'=>$optionStatus,
        ],$this->viewPermission));
    }

    public function addRole(){
        $id = isset($_POST['id'])?FunctionLib::outputId($_POST['id']):0;
        $data = $_POST;
        if ($id!=0 && $id!="0" && $id>0){
            Role::updateItem($id,$data);
        }else{
            Role::createItem($data);
        }
        return Redirect::route('admin.roleView');
    }

    public function deleteRole(){
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if ($id>0){
            Role::deleteItem($id);
        }
        return Redirect::route('admin.roleView');
    }

    public function ajaxLoadForm(){
        $data = $_POST;
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['role_status'])? $data['role_status'] : CGlobal::status_show);
        return view('admin.AdminRole.ajaxLoadForm',
            array_merge([
                'data'=>$data,
                'optionStatus'=>$optionStatus,
            ],$this->viewPermission));
    }
}
