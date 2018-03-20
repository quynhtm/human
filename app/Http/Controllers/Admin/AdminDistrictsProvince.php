<?php
/*
* @Created by: HSS
* @Author    : nguyenduypt86@gmail.com
* @Date      : 08/2016
* @Version   : 1.0
*/
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Districts;
use App\Http\Models\Admin\GroupUser;
use App\Http\Models\Admin\Province;
use App\Http\Models\Admin\User;
use App\Http\Models\Admin\MenuSystem;
use App\Http\Models\Admin\RoleMenu;
use App\Http\Models\Admin\Role;

use App\Http\Models\Admin\Wards;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class AdminDistrictsProvince extends BaseAdminController{
    private $permission_view = 'DistrictsProvinceView';
    private $permission_create = 'DistrictsProvinceCreate';
    private $permission_edit = 'DistrictsProvinceEdit';
    private $permission_remove = 'DistrictsProvinceDelete';
    private $arrStatus = array();
    private $arrRoleType = array();
    private $arrSex = array();
    private $error = array();

    public function __construct(){
        parent::__construct();

    }

    public function getDataDefault(){
        $this->arrRoleType = Role::getOptionRole();
        $this->arrStatus = array(
            CGlobal::status_hide => FunctionLib::controLanguage('status_all',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_block => FunctionLib::controLanguage('status_block',$this->languageSite));
        $this->arrSex = array(
            CGlobal::status_hide => FunctionLib::controLanguage('sex_girl',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('sex_boy',$this->languageSite));
    }
    public function view(){
        CGlobal::$pageAdminTitle  = "Quản trị User | Admin CMS";
        //check permission
        if (!$this->is_root && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $page_no = Request::get('page_no', 1);
        $dataSearch['user_status'] = Request::get('user_status', 0);
        $dataSearch['user_email'] = Request::get('user_email', '');
        $dataSearch['user_name'] = Request::get('user_name', '');
        $dataSearch['user_phone'] = Request::get('user_phone', '');
        $dataSearch['user_group'] = Request::get('user_group', 0);
        $dataSearch['role_type'] = Request::get('role_type', 0);
        $dataSearch['user_view'] = ($this->is_boss)? 1: 0;
        //FunctionLib::debug($dataSearch);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($page_no - 1) * $limit;
        $data = User::searchByCondition($dataSearch, $limit, $offset, $total);
        $arrGroupUser = GroupUser::getListGroupUser();

        $paging = $total > 0 ? Pagging::getNewPager(3,$page_no,$total,$limit,$dataSearch) : '';
        $this->getDataDefault();
        $optionRoleType = FunctionLib::getOption($this->arrRoleType, isset($dataSearch['role_type'])? $dataSearch['role_type']: 0);
        return view('admin.AdminUser.view',[
                'data'=>$data,
                'dataSearch'=>$dataSearch,
                'size'=>$total,
                'start'=>($page_no - 1) * $limit,
                'paging'=>$paging,
                'arrStatus'=>$this->arrStatus,
                'arrGroupUser'=>$arrGroupUser,
                'optionRoleType'=>$optionRoleType,
                'is_root'=>$this->is_root,
                'permission_edit'=>in_array($this->permission_edit, $this->permission) ? 1 : 0,
                'permission_create'=>in_array($this->permission_create, $this->permission) ? 1 : 0,
                'permission_change_pass'=>in_array($this->permission_change_pass, $this->permission) ? 1 : 0,
                'permission_remove'=>in_array($this->permission_remove, $this->permission) ? 1 : 0,
            ]);
    }

    public function remove($ids){
        $id = FunctionLib::outputId($ids);
        $data['success'] = 0;
        if(!$this->is_root && !in_array($this->permission_remove, $this->permission)){
            return Response::json($data);
        }
        $user = User::find($id);
        if($user){
            if(User::remove($user)){
                $data['success'] = 1;
            }
        }
        return Response::json($data);
    }

    //ajax get option tỉnh thành, quận huyện hoặc phường xã
    public function ajaxGetOption(){
        $object_id = Request::get('object_id',0);
        $type = Request::get('type',1);
        $option = '';
        switch ($type){
            case 1:// quận huyển theo tỉnh thành
                $arrData = Districts::getDistrictByProvinceId($object_id);
                $option = FunctionLib::getOption($arrData,0);
                break;
            case 2: // xã phường theo quân huyện
                $arrData = Wards::getWardsByDistrictId($object_id);
                $option = FunctionLib::getOption($arrData,0);
                break;
        }
        $arrData['optionSelect'] = $option;
        $arrData['isOk'] = 1;
        return response()->json( $arrData );
    }

}