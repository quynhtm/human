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

class PersonListController extends BaseAdminController
{
    private $permission_view = 'person_view';
    private $permission_full = 'person_full';
    private $permission_delete = 'person_delete';
    private $permission_create = 'person_create';
    private $permission_edit = 'person_edit';
    private $person_creater_user = 'person_creater_user';
    private $arrStatus = array();
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

    /******************************************************************************************************************
     * NS sắp sinh nhật
     ******************************************************************************************************************/
    public function viewBirthday()
    {
        CGlobal::$pageAdminTitle = 'Nhân sự sắp sinh nhật';
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
        $search['person_depart_id'] = (int)Request::get('person_depart_id', Define::STATUS_HIDE);
        $search['person_status'] = Define::PERSON_STATUS_DANGLAMVIEC;
        $search['start_birth'] = time();
        $search['end_birth'] = strtotime(time() . " +1 month");
        $search['orderBy'] = 'person_birth';
        $search['sortOrder'] = 'asc';
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
        return view('hr.PersonList.viewBirthday', array_merge([
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

    /******************************************************************************************************************
     * NS nghỉ việc
     * job chạy quét bảng quit_job để cập nhật lại bảng personal trạng thái nghỉ việc
     ******************************************************************************************************************/
    public function viewQuitJob()
    {
        CGlobal::$pageAdminTitle = 'Nhân sự buộc thôi việc';
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
        $search['person_depart_id'] = (int)Request::get('person_depart_id', Define::STATUS_HIDE);
        $search['person_status'] = Define::PERSON_STATUS_NGHIVIEC;
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
        return view('hr.PersonList.viewQuitJob', array_merge([
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

    /******************************************************************************************************************
     * NS chuyển công tác
     * job chạy quét bảng quit_job để cập nhật lại bảng personal trạng thái nghỉ việc
     ******************************************************************************************************************/
    public function viewMoveJob()
    {
        CGlobal::$pageAdminTitle = 'Nhân sự chuyển công tác';
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
        $search['person_depart_id'] = (int)Request::get('person_depart_id', Define::STATUS_HIDE);
        $search['person_status'] = Define::PERSON_STATUS_CHUYENCONGTAC;
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
        return view('hr.PersonList.viewMoveJob', array_merge([
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

    /******************************************************************************************************************
     * NS Đã nghỉ hưu
     * job chạy quét bảng retirement để cập nhật lại bảng personal trạng thái nghi huu
     ******************************************************************************************************************/
    public function viewRetired()
    {
        CGlobal::$pageAdminTitle = 'Nhân sự đã nghỉ hưu';
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
        $search['person_depart_id'] = (int)Request::get('person_depart_id', Define::STATUS_HIDE);
        $search['person_status'] = Define::PERSON_STATUS_NGHIHUU;
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
        return view('hr.PersonList.viewRetired', array_merge([
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

    /******************************************************************************************************************
     * NS sắp nghỉ hưu
     * job chạy quét bảng retirement để cập nhật lại bảng personal trạng thái sắp nghi huu
     ******************************************************************************************************************/
    public function viewPreparingRetirement()
    {
        CGlobal::$pageAdminTitle = 'Nhân sự sắp nghỉ hưu';
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
        $search['person_depart_id'] = (int)Request::get('person_depart_id', Define::STATUS_HIDE);
        $search['person_status'] = Define::PERSON_STATUS_SAPNGHIHUU;
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
        return view('hr.PersonList.viewPreparingRetirement', array_merge([
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

}
