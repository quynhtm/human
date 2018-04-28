<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Admin\Districts;
use App\Http\Models\Admin\Province;
use App\Http\Models\Admin\Wards;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Admin\Role;
use App\Http\Models\Hr\PersonExtend;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class PersonExtendController extends BaseAdminController
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
    private $viewOptionData = array();

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

    public function getItem($personId)
    {
        CGlobal::$pageAdminTitle = 'Thông tin nhân sự mở rộng';
        $person_id = FunctionLib::outputId($personId);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $data = array();
        if ($person_id > 0) {
            $data = PersonExtend::getPersonExtendByPersonId($person_id);
        }

        $this->getDataDefault();
        $this->viewOptionData($data);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonExtend.add', array_merge([
            'data' => $data,
            'id' => $person_id,
        ], $this->viewOptionData, $this->viewPermission));
    }

    public function postItem($personId)
    {
        CGlobal::$pageAdminTitle = 'Thông tin nhân sự mở rộng';
        $person_id = FunctionLib::outputId($personId);
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_edit, $this->permission) && !in_array($this->permission_create, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;
        $data['ordering'] = (isset($data['person_birth']) && $data['person_birth'] != '') ? strtotime($data['person_birth']) : 0;
        $data['person_date_trial_work'] = (isset($data['person_date_trial_work']) && $data['person_date_trial_work'] != '') ? strtotime($data['person_date_trial_work']) : 0;
        $data['person_date_start_work'] = (isset($data['person_date_start_work']) && $data['person_date_start_work'] != '') ? strtotime($data['person_date_start_work']) : 0;
        $data['person_date_range_cmt'] = (isset($data['person_date_range_cmt']) && $data['person_date_range_cmt'] != '') ? strtotime($data['person_date_range_cmt']) : 0;

        $data['person_birth'] = (isset($data['person_birth']) && $data['person_birth'] != '') ? strtotime($data['person_birth']) : 0;
        $data['person_avatar'] = (isset($data['img']) && $data['img'] != '') ? trim($data['img']) : '';
        $data['person_status'] = Define::STATUS_SHOW;

        if ($this->valid($data) && empty($this->error)) {
            $id = ($person_id == 0) ? $id_hiden : $person_id;
            $person_extend_id = 0;
            if ($id > 0) {
                $data = PersonExtend::getPersonExtendByPersonId($person_id);
                $person_extend_id = (isset($data->person_extend_id) && $data->person_extend_id > 0) ? $data->person_extend_id : $person_extend_id;
            }
            if ($person_extend_id > 0) {
                //cap nhat
                if (PersonExtend::updateItem($person_extend_id, $data)) {
                    return Redirect::route('hr.personnelView');
                }
            } else {
                //them moi
                if (PersonExtend::createItem($data)) {
                    return Redirect::route('hr.personnelView');
                }
            }
        }

        $this->getDataDefault();
        $this->viewOptionData($data);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonExtend.add', array_merge([
            'data' => $data,
            'id' => $person_id,
            'error' => $this->error,
        ], $this->viewOptionData, $this->viewPermission));
    }

    public function viewOptionData($data){
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
        $optionProvinceCurrent = FunctionLib::getOption($arrProvince, $person_province_current);
        $arrDistricts = Districts::getDistrictByProvinceId($person_province_current);
        $optionDistrictsCurrent = FunctionLib::getOption($arrDistricts, isset($data['person_districts_current']) ? $data['person_districts_current'] : 0);
        $person_districts_current = isset($data['person_districts_current']) ? $data['person_districts_current'] : 0;
        $arrWards = Wards::getWardsByDistrictId($person_districts_current);
        $optionWardsCurrent = FunctionLib::getOption($arrWards, isset($data['person_wards_current']) ? $data['person_wards_current'] : 0);

        return $this->viewOptionData = [
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
        ];
    }

    private function valid($data = array())
    {
        if (!empty($data)) {
            if (isset($data['person_name']) && trim($data['person_name']) == '') {
                $this->error[] = 'Họ và tên khai sinh KHÔNG được bỏ trống';
            }
            if (isset($data['person_chung_minh_thu']) && trim($data['person_chung_minh_thu']) == '') {
                $this->error[] = 'Số CMT KHÔNG được bỏ trống';
            }
            if (isset($data['person_depart_id']) && trim($data['person_depart_id']) <= 0) {
                $this->error[] = 'Chưa chọn Phòng ban đơn vị';
            }
            if (isset($data['person_date_range_cmt']) && trim($data['person_date_range_cmt']) <= 0) {
                $this->error[] = 'Chưa chọn ngày cấp CMT';
            }
            if (isset($data['person_date_start_work']) && trim($data['person_date_start_work']) <= 0) {
                $this->error[] = 'Chưa chọn ngày làm việc chính thức';
            }
            if (isset($data['person_wards_current']) && trim($data['person_wards_current']) <= 0) {
                $this->error[] = 'Chưa chọn phường xã hiện tại';
            }
            if (isset($data['person_address_place_of_birth']) && trim($data['person_address_place_of_birth']) == '') {
                $this->error[] = 'Địa chỉ nơi sinh KHÔNG được bỏ trống';
            }
            if (isset($data['person_address_home_town']) && trim($data['person_address_home_town']) == '') {
                $this->error[] = 'Địa chỉ quê quán KHÔNG được bỏ trống';
            }
            if (isset($data['person_address_current']) && trim($data['person_address_current']) == '') {
                $this->error[] = 'Địa chỉ hiện tại KHÔNG được bỏ trống';
            }
        }
        return true;
    }

}
