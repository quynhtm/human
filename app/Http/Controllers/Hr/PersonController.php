<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\Bonus;
use App\Http\Models\Hr\HrDefine;

use App\Http\Models\Admin\User;
use App\Http\Models\Admin\Role;
use App\Http\Models\Admin\RoleMenu;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class PersonController extends BaseAdminController
{
    private $permission_view = 'person_view';
    private $permission_full = 'person_full';
    private $permission_delete = 'person_delete';
    private $permission_create = 'person_create';
    private $permission_edit = 'person_edit';
    private $person_creater_user = 'person_creater_user';
    private $arrStatus = array();
    private $error = array();
    private $arrMenuParent = array();
    private $arrRoleType = array();
    private $arrSex = array();
    private $viewPermission = array();//check quyen

    public function __construct()
    {
        parent::__construct();
        $this->arrMenuParent = array();
    }

    public function getDataDefault()
    {
        $this->arrRoleType = Role::getOptionRole();
        $this->arrStatus = array(
            CGlobal::status_hide => FunctionLib::controLanguage('status_all', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show', $this->languageSite),
            CGlobal::status_block => FunctionLib::controLanguage('status_block', $this->languageSite));
        $this->arrSex = array(
            CGlobal::status_hide => FunctionLib::controLanguage('sex_girl', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('sex_boy', $this->languageSite));
    }

    public function getPermissionPage()
    {
        return $this->viewPermission = [
            'is_root' => $this->is_root ? 1 : 0,
            'permission_edit' => in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create' => in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_delete' => in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full' => in_array($this->permission_full, $this->permission) ? 1 : 0,
            'person_creater_user' => in_array($this->person_creater_user, $this->permission) ? 1 : 0,
        ];
    }

    public function view()
    {
        CGlobal::$pageAdminTitle = 'Quản lý nhân sự';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $page_no = (int)Request::get('page_no', 1);
        $sbmValue = Request::get('submit', 1);
        $limit = CGlobal::number_show_20;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;

        $search['person_name'] = addslashes(Request::get('person_name', ''));
        $search['active'] = (int)Request::get('active', -1);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = Person::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['active']);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Person.view', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'optionStatus' => $optionStatus,
            'optionRoleType' => $optionStatus,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    public function getItem($ids)
    {
        CGlobal::$pageAdminTitle = 'Thông tin nhân sự';
        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($id > 0) {
            $data = Person::find($id);
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['active']) ? $data['active'] : CGlobal::status_show);
        $optionShowContent = FunctionLib::getOption($this->arrStatus, isset($data['showcontent']) ? $data['showcontent'] : CGlobal::status_show);
        $optionShowPermission = FunctionLib::getOption($this->arrStatus, isset($data['show_permission']) ? $data['show_permission'] : CGlobal::status_hide);
        $optionShowMenu = FunctionLib::getOption($this->arrStatus, isset($data['show_menu']) ? $data['show_menu'] : CGlobal::status_show);
        $optionMenuParent = FunctionLib::getOption($this->arrMenuParent, isset($data['parent_id']) ? $data['parent_id'] : 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Person.add', array_merge([
            'data' => $data,
            'id' => $id,
            'arrStatus' => $this->arrStatus,
            'optionStatus' => $optionStatus,
            'optionShowContent' => $optionShowContent,
            'optionShowPermission' => $optionShowPermission,
            'optionShowMenu' => $optionShowMenu,
            'optionRoleType' => $optionShowMenu,
            'optionSex' => $optionShowMenu,
            'optionMenuParent' => $optionMenuParent,
        ], $this->viewPermission));
    }

    public function postItem($ids)
    {
        CGlobal::$pageAdminTitle = 'Thông tin nhân sự';
        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        //$data['ordering'] = (int)($data['ordering']);
        if ($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if ($id > 0) {
                //cap nhat
                if (Person::updateItem($id, $data)) {
                    return Redirect::route('hr.personnelView');
                }
            } else {
                //them moi
                if (Person::createItem($data)) {
                    return Redirect::route('hr.personnelView');
                }
            }
        }

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['active']) ? $data['active'] : CGlobal::status_hide);
        $optionShowContent = FunctionLib::getOption($this->arrStatus, isset($data['showcontent']) ? $data['showcontent'] : CGlobal::status_show);
        $optionShowMenu = FunctionLib::getOption($this->arrStatus, isset($data['show_menu']) ? $data['show_menu'] : CGlobal::status_show);
        $optionShowPermission = FunctionLib::getOption($this->arrStatus, isset($data['show_permission']) ? $data['show_permission'] : CGlobal::status_hide);
        $optionMenuParent = FunctionLib::getOption($this->arrMenuParent, isset($data['parent_id']) ? $data['parent_id'] : 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Person.add', array_merge([
            'data' => $data,
            'id' => $id,
            'error' => $this->error,
            'arrStatus' => $this->arrStatus,
            'optionStatus' => $optionStatus,
            'optionShowContent' => $optionShowContent,
            'optionShowPermission' => $optionShowPermission,
            'optionShowMenu' => $optionShowMenu,
            'optionMenuParent' => $optionMenuParent,
        ], $this->viewPermission));
    }

    public function getDetail($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Thông tin chi tiết nhân sự';
        //Check phan quyen.
        if (!$this->is_root && ($this->user_id != $person_id)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin khen thưởng
        $khenthuong = Bonus::getBonusByType($person_id, Define::BONUS_KHEN_THUONG);
        $arrTypeKhenthuong = HrDefine::getArrayByType(Define::khen_thuong);
        //thông tin danh hieu
        $danhhieu = Bonus::getBonusByType($person_id, Define::BONUS_DANH_HIEU);
        $arrTypeDanhhieu = HrDefine::getArrayByType(Define::danh_hieu);

        //thông tin kỷ luật
        $kyluat = Bonus::getBonusByType($person_id, Define::BONUS_KY_LUAT);
        $arrTypeKyluat = HrDefine::getArrayByType(Define::ky_luat);

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Person.detail', array_merge([
            'person_id' => $person_id,
            'khenthuong' => $khenthuong,
            'danhhieu' => $danhhieu,
            'kyluat' => $kyluat,
            'arrTypeKhenthuong' => $arrTypeKhenthuong,
            'arrTypeDanhhieu' => $arrTypeDanhhieu,
            'arrTypeKyluat' => $arrTypeKyluat,
            'infoPerson' => $infoPerson,
        ], $this->viewPermission));
    }

    //get Person with account
    public function getPersonWithAccount($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = "Sửa Account nhân sự| " . CGlobal::web_name;
//        //check permission
        if (!$this->is_root && !in_array($this->person_creater_user, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($person_id > 0) {
            $data = User::getUserByPersonId($person_id);
            if (!$data && empty($data)) {
                $personInfo = Person::find($person_id);
                if ($personInfo) {
                    $data['user_name'] = FunctionLib::safe_title($personInfo['person_name'], '_');
                    $data['user_full_name'] = $personInfo['person_name'];
                    $data['user_email'] = $personInfo['person_mail'];
                    $data['user_sex'] = $personInfo['person_sex'];
                    $data['user_phone'] = $personInfo['person_phone'];
                    $data['telephone'] = $personInfo['person_telephone'];
                    $data['number_code'] = $personInfo['person_code'];
                    $data['address_register'] = $personInfo['person_address_current'];
                    $data['user_status'] = CGlobal::status_show;
                }
            }else{
                $this->error[] = 'Tài khoản này đã được tạo.';
            }
        }
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['user_status']) ? $data['user_status'] : CGlobal::status_show);
        $optionSex = FunctionLib::getOption($this->arrSex, isset($data['user_sex']) ? $data['user_sex'] : CGlobal::status_show);
        $optionRoleType = FunctionLib::getOption($this->arrRoleType, isset($data['role_type']) ? $data['role_type'] : Define::ROLE_TYPE_CUSTOMER);
        return view('hr.Person.addAccount', [
            'data' => $data,
            'person_id' => $person_id,
            'user_id' => isset($data['user_id']) ? $data['user_id'] : 0,
            'arrStatus' => $this->arrStatus,
            'optionStatus' => $optionStatus,
            'optionSex' => $optionSex,
            'optionRoleType' => $optionRoleType,
            'error' => $this->error,
        ]);
    }

    //get Person with account
    public function postPersonWithAccount($personId)
    {
        //check permission
        if (!$this->is_root && !in_array($this->person_creater_user, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $person_id = FunctionLib::outputId($personId);
        $data['user_status'] = (int)Request::get('user_status', -1);
        $data['user_sex'] = (int)Request::get('user_sex', CGlobal::status_show);
        $data['user_full_name'] = htmlspecialchars(trim(Request::get('user_full_name', '')));
        $data['user_email'] = htmlspecialchars(trim(Request::get('user_email', '')));
        $data['user_phone'] = htmlspecialchars(trim(Request::get('user_phone', '')));
        $data['user_name'] = Request::get('user_name', '');
        $data['user_password'] = Request::get('user_password', '');
        $data['telephone'] = Request::get('telephone', '');
        $data['address_register'] = Request::get('address_register', '');
        $data['number_code'] = Request::get('number_code', '');
        $data['role_type'] = Request::get('role_type', 0);
        $id = Request::get('user_id', 0);

        $this->validUser($id, $data);
        //FunctionLib::debug($this->error);

        //lấy phân quyền và menu view theo role
        if ($data['role_type'] > 0) {
            $infoPermiRole = RoleMenu::getInfoByRoleId((int)$data['role_type']);
            if ($infoPermiRole) {
                $dataInsert['user_group'] = (isset($infoPermiRole->role_group_permission) && trim($infoPermiRole->role_group_permission) != '') ? $infoPermiRole->role_group_permission : '';
                $dataInsert['user_group_menu'] = (isset($infoPermiRole->role_group_menu_id) && trim($infoPermiRole->role_group_menu_id) != '') ? $infoPermiRole->role_group_menu_id : '';
            }
        }
        if (empty($this->error)) {
            $groupRole = Role::getOptionRole();
            //Insert dữ liệu
            $dataInsert['user_name'] = $data['user_name'];
            $dataInsert['user_email'] = $data['user_email'];
            $dataInsert['user_phone'] = $data['user_phone'];
            $dataInsert['telephone'] = $data['telephone'];
            $dataInsert['address_register'] = $data['address_register'];
            $dataInsert['number_code'] = $data['number_code'];
            $dataInsert['role_type'] = $data['role_type'];
            $dataInsert['role_name'] = isset($groupRole[$data['role_type']]) ? $groupRole[$data['role_type']] : '';
            $dataInsert['user_full_name'] = $data['user_full_name'];
            $dataInsert['user_status'] = (int)$data['user_status'];
            $dataInsert['user_edit_id'] = User::user_id();
            $dataInsert['user_edit_name'] = User::user_name();
            $dataInsert['user_updated'] = time();

            if ($id > 0) {
                if (User::updateUser($id, $dataInsert)) {
                    return Redirect::route('hr.personnelView');
                } else {
                    $this->error[] = 'Lỗi truy xuất dữ liệu';;
                }
            } else {
                $dataInsert['user_create_id'] = User::user_id();
                $dataInsert['user_create_name'] = User::user_name();
                $dataInsert['user_created'] = time();
                $dataInsert['user_password'] = $data['user_password'];
                $dataInsert['user_object_id'] = $person_id;
                if (User::createNew($dataInsert)) {
                    return Redirect::route('hr.personnelView');
                } else {
                    $this->error[] = 'Lỗi truy xuất dữ liệu';;
                }
            }

        }
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['user_status']) ? $data['user_status'] : CGlobal::status_show);
        $optionSex = FunctionLib::getOption($this->arrSex, isset($data['user_sex']) ? $data['user_sex'] : CGlobal::status_show);
        $optionRoleType = FunctionLib::getOption($this->arrRoleType, isset($data['role_type']) ? $data['role_type'] : Define::ROLE_TYPE_CUSTOMER);
        return view('hr.Person.addAccount', [
            'data' => $data,
            'person_id' => $person_id,
            'user_id' => isset($data['user_id']) ? $data['user_id'] : 0,
            'arrStatus' => $this->arrStatus,
            'arrUserGroupMenu' => array(),
            'optionStatus' => $optionStatus,
            'optionSex' => $optionSex,
            'optionRoleType' => $optionRoleType,
            'error' => $this->error,
        ]);
    }

    private function validUser($user_id = 0, $data = array())
    {
        if (!empty($data)) {
            if (isset($data['user_name']) && trim($data['user_name']) == '') {
                $this->error[] = 'Tài khoản đăng nhập không được bỏ trống';
            } elseif (isset($data['user_name']) && trim($data['user_name']) != '') {
                $checkIssetUser = User::getUserByName($data['user_name']);
                if ($checkIssetUser && $checkIssetUser->user_id != $user_id) {
                    $this->error[] = 'Tài khoản này đã tồn tại, hãy tạo lại';
                }
            }

            if (isset($data['user_full_name']) && trim($data['user_full_name']) == '') {
                $this->error[] = 'Tên nhân viên không được bỏ trống';
            }
            if (isset($data['user_email']) && trim($data['user_email']) == '') {
                $this->error[] = 'Mail không được bỏ trống';
            }
        }
        return true;
    }

    private function valid($data = array())
    {
        if (!empty($data)) {
            if (isset($data['banner_name']) && trim($data['banner_name']) == '') {
                $this->error[] = 'Null';
            }
        }
        return true;
    }
}
