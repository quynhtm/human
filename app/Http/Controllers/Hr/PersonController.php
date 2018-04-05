<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Districts;
use App\Http\Models\Admin\Province;
use App\Http\Models\Admin\Wards;
use App\Http\Models\Hr\Department;
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

use App\Library\AdminFunction\Loader;
use App\Library\AdminFunction\Upload;

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
    private $arrTonGiao = array();
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

        $this->arrTonGiao = array(
            CGlobal::status_hide => 'Không',
            CGlobal::status_show => 'Có');
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
        $search['person_mail'] = addslashes(Request::get('person_mail', ''));
        $search['person_code'] = addslashes(Request::get('person_code', ''));
        $search['person_status'] = Define::PERSON_STATUS_DANGLAMVIEC;
        $search['person_depart_id'] = (int)Request::get('person_depart_id', Define::STATUS_HIDE);
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = Person::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        //FunctionLib::debug($data);
        $this->getDataDefault();
        $depart = Department::getDepartmentAll();
        $optionDepart = FunctionLib::getOption($depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);

        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $arrChucDanhNgheNghiep = HrDefine::getArrayByType(Define::chuc_danh_nghe_nghiep);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Person.view', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'arrSex' => $this->arrSex,
            'arrDepart' => $depart,
            'arrChucVu' => $arrChucVu,
            'arrChucDanhNgheNghiep' => $arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    public function getItem($ids)
    {
        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadJS('lib/dragsort/jquery.dragsort.js', CGlobal::$POS_HEAD);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        CGlobal::$pageAdminTitle = 'Thông tin nhân sự';
        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($id > 0) {
            $data = Person::find($id);
        }
        $arr_thang_bangluong = HrDefine::getArrayByType(Define::thang_bang_luong);
        $arr_ngach_congchuc = HrDefine::getArrayByType(Define::ngach_cong_chuc);
        $arr_bacluong = HrDefine::getArrayByType(Define::bac_luong);

        $this->getDataDefault();

        //phần lương
        $optionThangBangLuong = FunctionLib::getOption($arr_thang_bangluong, 0);
        $optionNgachCongChuc = FunctionLib::getOption($arr_ngach_congchuc, 0);
        $optionBacLuong = FunctionLib::getOption($arr_bacluong, 0);
        $arrYears = FunctionLib::getListYears();
        $optionYears = FunctionLib::getOption($arrYears, (int)date('Y', time()));
        $arrMonth = FunctionLib::getListMonth();
        $optionMonth = FunctionLib::getOption($arrMonth, (int)date('m', time()));

        //thông tin của nhân sự
        $optionSex = FunctionLib::getOption($this->arrSex, isset($data['person_sex']) ? $data['person_sex'] : 0);
        $optionTonGiao = FunctionLib::getOption($this->arrTonGiao, isset($data['person_respect']) ? $data['person_respect'] : 0);
        $depart = Department::getDepartmentAll();
        $optionDepart = FunctionLib::getOption($depart, isset($data['person_depart_id']) ? $data['person_depart_id'] : 0);

        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $optionChucVu = FunctionLib::getOption($arrChucVu, isset($data['person_position_define_id']) ? $data['person_position_define_id'] : 0);

        $arrChucDanhNgheNghiep = HrDefine::getArrayByType(Define::chuc_danh_nghe_nghiep);
        $optionChucDanhNgheNghiep = FunctionLib::getOption($arrChucDanhNgheNghiep, isset($data['person_career_define_id']) ? $data['person_career_define_id'] : 0);

        $arrNhomMau = HrDefine::getArrayByType(Define::nhom_mau);
        $optionNhomMau = FunctionLib::getOption($arrNhomMau, isset($data['person_blood_group_define_id']) ? $data['person_blood_group_define_id'] : 0);

        $arrDanToc = HrDefine::getArrayByType(Define::dan_toc);
        $optionDanToc = FunctionLib::getOption($arrDanToc, isset($data['person_nation_define_id']) ? $data['person_nation_define_id'] : 0);

        $arrProvince = Province::getAllProvince();
        $optionProvincePlaceBirth = FunctionLib::getOption($arrProvince, isset($data['person_province_place_of_birth']) ? $data['person_province_place_of_birth'] : Define::PROVINCE_HANOI);
        $optionProvinceHomeTown = FunctionLib::getOption($arrProvince, isset($data['person_province_home_town']) ? $data['person_province_home_town'] : Define::PROVINCE_HANOI);

        $person_province_current = isset($data['person_province_current']) ? $data['person_province_current'] : Define::PROVINCE_HANOI;
        $optionProvinceCurrent = FunctionLib::getOption($arrProvince, $person_province_current );
        $arrDistricts = Districts::getDistrictByProvinceId($person_province_current);
        $optionDistrictsCurrent = FunctionLib::getOption($arrDistricts, isset($data['person_districts_current']) ? $data['person_districts_current'] : 0 );
        $person_districts_current = isset($data['person_districts_current']) ? $data['person_districts_current'] : 0;
        $arrWards = Wards::getWardsByDistrictId($person_districts_current);
        $optionWardsCurrent = FunctionLib::getOption($arrWards, isset($data['person_wards_current']) ? $data['person_wards_current'] : 0 );

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Person.add', array_merge([
            'data' => $data,
            'id' => $id,
            'arrStatus' => $this->arrStatus,
            'optionThangBangLuong' => $optionThangBangLuong,
            'optionNgachCongChuc' => $optionNgachCongChuc,
            'optionBacLuong' => $optionBacLuong,
            'optionMonth' => $optionMonth,
            'optionYears' => $optionYears,

            'optionSex' => $optionSex,
            'optionDepart' => $optionDepart,
            'optionChucVu' => $optionChucVu,
            'optionChucDanhNgheNghiep' => $optionChucDanhNgheNghiep,
            'optionNhomMau' => $optionNhomMau,
            'optionDanToc' => $optionDanToc,
            'optionTonGiao' => $optionTonGiao,
            'optionProvincePlaceBirth' => $optionProvincePlaceBirth,
            'optionProvinceHomeTown' => $optionProvinceHomeTown,
            'optionProvinceCurrent' => $optionProvinceCurrent,
            'optionDistrictsCurrent' => $optionDistrictsCurrent,
            'optionWardsCurrent' => $optionWardsCurrent,
        ], $this->viewPermission));
    }

    public function postItem($ids)
    {
        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadJS('lib/dragsort/jquery.dragsort.js', CGlobal::$POS_HEAD);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        CGlobal::$pageAdminTitle = 'Thông tin nhân sự';
        $id = FunctionLib::outputId($ids);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        $data['ordering'] = (isset($data['person_birth']) && $data['person_birth'] != '')? strtotime($data['person_birth']): 0;
        $data['person_date_trial_work'] = (isset($data['person_date_trial_work']) && $data['person_date_trial_work'] != '')? strtotime($data['person_date_trial_work']): 0;
        $data['person_date_start_work'] = (isset($data['person_date_start_work']) && $data['person_date_start_work'] != '')? strtotime($data['person_date_start_work']): 0;
        $data['person_date_range_cmt'] = (isset($data['person_date_range_cmt']) && $data['person_date_range_cmt'] != '')? strtotime($data['person_date_range_cmt']): 0;
        $data['person_avatar'] = (isset($data['img']) && $data['img'] != '')? trim($data['img']): '';
        $data['person_status'] = Define::STATUS_SHOW;
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

        $arr_thang_bangluong = HrDefine::getArrayByType(Define::thang_bang_luong);
        $arr_ngach_congchuc = HrDefine::getArrayByType(Define::ngach_cong_chuc);
        $arr_bacluong = HrDefine::getArrayByType(Define::bac_luong);

        $this->getDataDefault();

        //phần lương
        $optionThangBangLuong = FunctionLib::getOption($arr_thang_bangluong, 0);
        $optionNgachCongChuc = FunctionLib::getOption($arr_ngach_congchuc, 0);
        $optionBacLuong = FunctionLib::getOption($arr_bacluong, 0);
        $arrYears = FunctionLib::getListYears();
        $optionYears = FunctionLib::getOption($arrYears, (int)date('Y', time()));
        $arrMonth = FunctionLib::getListMonth();
        $optionMonth = FunctionLib::getOption($arrMonth, (int)date('m', time()));

        //thông tin của nhân sự
        $optionSex = FunctionLib::getOption($this->arrSex, isset($data['person_sex']) ? $data['person_sex'] : 0);
        $optionTonGiao = FunctionLib::getOption($this->arrTonGiao, isset($data['person_respect']) ? $data['person_respect'] : 0);
        $depart = Department::getDepartmentAll();
        $optionDepart = FunctionLib::getOption($depart, isset($data['person_depart_id']) ? $data['person_depart_id'] : 0);

        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $optionChucVu = FunctionLib::getOption($arrChucVu, isset($data['person_position_define_id']) ? $data['person_position_define_id'] : 0);

        $arrChucDanhNgheNghiep = HrDefine::getArrayByType(Define::chuc_danh_nghe_nghiep);
        $optionChucDanhNgheNghiep = FunctionLib::getOption($arrChucDanhNgheNghiep, isset($data['person_career_define_id']) ? $data['person_career_define_id'] : 0);

        $arrNhomMau = HrDefine::getArrayByType(Define::nhom_mau);
        $optionNhomMau = FunctionLib::getOption($arrNhomMau, isset($data['person_blood_group_define_id']) ? $data['person_blood_group_define_id'] : 0);

        $arrDanToc = HrDefine::getArrayByType(Define::dan_toc);
        $optionDanToc = FunctionLib::getOption($arrDanToc, isset($data['person_nation_define_id']) ? $data['person_nation_define_id'] : 0);

        $arrProvince = Province::getAllProvince();
        $optionProvincePlaceBirth = FunctionLib::getOption($arrProvince, isset($data['person_province_place_of_birth']) ? $data['person_province_place_of_birth'] : Define::PROVINCE_HANOI);
        $optionProvinceHomeTown = FunctionLib::getOption($arrProvince, isset($data['person_province_home_town']) ? $data['person_province_home_town'] : Define::PROVINCE_HANOI);

        $person_province_current = isset($data['person_province_current']) ? $data['person_province_current'] : Define::PROVINCE_HANOI;
        $optionProvinceCurrent = FunctionLib::getOption($arrProvince, $person_province_current );
        $arrDistricts = Districts::getDistrictByProvinceId($person_province_current);
        $optionDistrictsCurrent = FunctionLib::getOption($arrDistricts, isset($data['person_districts_current']) ? $data['person_districts_current'] : 0 );
        $person_districts_current = isset($data['person_districts_current']) ? $data['person_districts_current'] : 0;
        $arrWards = Wards::getWardsByDistrictId($person_districts_current);
        $optionWardsCurrent = FunctionLib::getOption($arrWards, isset($data['person_wards_current']) ? $data['person_wards_current'] : 0 );

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Person.add', array_merge([
            'data' => $data,
            'id' => $id,
            'error' => $this->error,
            'arrStatus' => $this->arrStatus,
            'optionThangBangLuong' => $optionThangBangLuong,
            'optionNgachCongChuc' => $optionNgachCongChuc,
            'optionBacLuong' => $optionBacLuong,
            'optionMonth' => $optionMonth,
            'optionYears' => $optionYears,

            'optionSex' => $optionSex,
            'optionDepart' => $optionDepart,
            'optionChucVu' => $optionChucVu,
            'optionChucDanhNgheNghiep' => $optionChucDanhNgheNghiep,
            'optionNhomMau' => $optionNhomMau,
            'optionDanToc' => $optionDanToc,
            'optionTonGiao' => $optionTonGiao,
            'optionProvincePlaceBirth' => $optionProvincePlaceBirth,
            'optionProvinceHomeTown' => $optionProvinceHomeTown,
            'optionProvinceCurrent' => $optionProvinceCurrent,
            'optionDistrictsCurrent' => $optionDistrictsCurrent,
            'optionWardsCurrent' => $optionWardsCurrent,
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

    //ajax get thong tin cơ bản của nhân sự
    public function getInfoPerson()
    {
        //Check phan quyen.
        $personId = Request::get('str_person_id', '');
        $person_id = FunctionLib::outputId($personId);
        $data = array();
        $arrData = ['intReturn' => 0, 'msg' => ''];

        //thong tin nhan sự
        $infoPerson = Person::getInfoPerson($person_id);
        //FunctionLib::debug($infoPerson->contracts->contracts_id);

        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.Person.infoPersonPopup', [
            'infoPerson' => $infoPerson,
            'person_id' => $person_id,
        ], $this->viewPermission)->render();

        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }

    //cap nhat trạng thái xóa của user
    public function statusDeletePerson($personId)
    {
        //check permission
        if (!$this->is_root && !in_array($this->permission_delete, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $person_id = FunctionLib::outputId($personId);

        $dataUpdate['person_status'] = Define::PERSON_STATUS_DAXOA;
        if (Person::updateItem($person_id,$dataUpdate)) {
            return Redirect::route('hr.personnelView');
        } else {
            return Redirect::route('hr.personnelView');
        }
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
