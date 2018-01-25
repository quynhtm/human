<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Device;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\URL;

class DeviceController extends BaseAdminController{
    private $permission_view = 'device_view';
    private $permission_full = 'device_full';
    private $permission_delete = 'device_delete';
    private $permission_create = 'device_create';
    private $permission_edit = 'device_edit';
    private $arrStatus = array();
    private $error = array();
    private $arrDeviceType = array();
    private $viewPermission = array();

    private $arrDepartmentType = array();
    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý tài sản';
    }
    public function getDataDefault(){
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden',$this->languageSite)
        );
        $this->arrDeviceType = array();
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
        $search = array();

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['device_name'] = addslashes(Request::get('device_name',''));
        $dataSearch['device_status'] = (int)Request::get('device_status', -1);
        $dataSearch['field_get'] = '';

        $data = Device::searchByCondition($search, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['device_status']);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.view',array_merge([
            'data'=>$data,
            'search'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
        ],$this->viewPermission));
    }
    public function getItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        if($id > 0) {
            $data = Device::getItemById($id);
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['department_status'])? $data['department_status']: CGlobal::status_show);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.add',array_merge([
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

        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;

        if(isset($data['device_type'])) {
            $data['device_type'] = (int)$data['device_type'];
        }

        $data['device_status'] = (int)($data['device_status']);

        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if($id > 0) {
                if(Device::updateItem($id, $data)) {
                    if(isset($data['clickPostPageNext'])){
                        return Redirect::route('hr.deviceEdit', array('id'=>FunctionLib::inputId(0)));
                    }else{
                        return Redirect::route('hr.deviceView');
                    }
                }
            }else{
                if(Device::createItem($data)) {
                    if(isset($data['clickPostPageNext'])){
                        return Redirect::route('hr.deviceEdit', array('id'=>FunctionLib::inputId(0)));
                    }else{
                        return Redirect::route('hr.deviceView');
                    }
                }
            }
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['device_status'])? $data['device_status']: CGlobal::status_show);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,

        ],$this->viewPermission));
    }
    public function deleteDevice(){
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
            if(isset($data['department_type']) && trim($data['department_type']) == '') {
                $this->error[] = 'Loại đơn vị/ phòng ban không được rỗng';
            }
            if(isset($data['department_name']) && trim($data['department_name']) == '') {
                $this->error[] = 'Tên đơn vị/ Phòng ban không được rỗng';
            }
        }
        return true;
    }
    public static function showCategories($categories, $parent_id = 0, $char='-', &$str){
        foreach($categories as $key => $item){
            if($item['department_parent_id'] == $parent_id) {
                if($parent_id == 0){
                    $bold='txt-bold';
                }else{
                    $bold = '';
                }
                $str .= '<li class="list-group-item node-treeview '.$bold.'">' . $char . '<span class="icon glyphicon glyphicon-minus"></span> <a href="' . URL::route('hr.departmentEdit', array('id' => FunctionLib::inputId($item['department_id']))) . '" title="' . $item->department_name . '">' . $item['department_name'] . '</a></li>';
                unset($categories[$key]);
                self::showCategories($categories, $item['department_id'], $char.'<span class="indent"></span>', $str);
            }
        }
    }
    public static function showCategoriesView($categories, $parent_id = 0, $char='-', &$str){
        foreach($categories as $key => $item){
            if($item['department_parent_id'] == $parent_id){
                if($parent_id == 0){
                    $bold='txt-bold';
                }else{
                    $bold = '';
                }
                $str .= '<li class="list-group-item node-treeview '.$bold.'" title="'.$item['department_name'].'" rel="'.$item['department_id'].'" psrel="'.$item['department_parent_id'].'" data="'.FunctionLib::inputId($item['department_id']).'">'.$char. '<span class="icon glyphicon glyphicon-minus"></span> '.$item['department_name'].'</li>';
                unset($categories[$key]);

                self::showCategoriesView($categories, $item['department_id'], $char.'<span class="indent"></span>', $str);
            }
        }
    }
}
