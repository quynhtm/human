<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\HrContracts;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class InfoPersonController extends BaseAdminController
{
    //contracts
    private $permission_view = 'personContracts_view';
    private $permission_full = 'personContracts_full';
    private $permission_delete = 'personContracts_delete';
    private $permission_create = 'personContracts_create';

    //tao user login
    private $permission_createrUser_view = 'personCreaterUser_view';
    private $permission_createrUser_full = 'personCreaterUser_full';
    private $permission_createrUser_delete = 'personCreaterUser_delete';
    private $permission_createrUser_create = 'personCreaterUser_create';

    private $arrStatus = array();
    private $error = array();
    private $arrMenuParent = array();
    private $viewPermission = array();//check quyen

    public function __construct()
    {
        parent::__construct();
        $this->arrMenuParent = array();

    }

    public function getDataDefault()
    {
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show', $this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden', $this->languageSite));
    }

    public function getPermissionPage()
    {
        return $this->viewPermission = [
            'is_root' => $this->is_root ? 1 : 0,
            //contracts
            'personContracts_view' => in_array($this->permission_view, $this->permission) ? 1 : 0,
            'personContracts_create' => in_array($this->permission_create, $this->permission) ? 1 : 0,
            'personContracts_delete' => in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'personContracts_full' => in_array($this->permission_full, $this->permission) ? 1 : 0,

            //creater User
            'personCreaterUser_view' => in_array($this->permission_createrUser_view, $this->permission) ? 1 : 0,
            'personCreaterUser_create' => in_array($this->permission_createrUser_create, $this->permission) ? 1 : 0,
            'personCreaterUser_delete' => in_array($this->permission_createrUser_delete, $this->permission) ? 1 : 0,
            'personCreaterUser_full' => in_array($this->permission_createrUser_full, $this->permission) ? 1 : 0,
        ];
    }

    /************************************************************************************************************************************
     * Thông tin thêm của nhân sự
     ************************************************************************************************************************************/
    public function viewInfoPersonOther($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Thông tin thêm của nhân sự';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $contracts = HrContracts::getListContractsByPersonId($person_id);

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.InfoPerson.editInfoPersonOtherView', array_merge([
            'contracts' => $contracts,
            'total' => count($contracts),
        ], $this->viewPermission));
    }

    /************************************************************************************************************************************
     * Thông tin hợp đồng lao động
     ************************************************************************************************************************************/
    public function viewContracts($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Thông tin hợp đồng lao động';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $contracts = HrContracts::getListContractsByPersonId($person_id);

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.InfoPerson.contractsView', array_merge([
            'contracts' => $contracts,
            'total' => count($contracts),
        ], $this->viewPermission));
    }

    /************************************************************************************************************************************
     * Chuyển đổi công tác
     ************************************************************************************************************************************/
    public function viewTransferWork($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Chuyển đổi công tác';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $contracts = HrContracts::getListContractsByPersonId($person_id);

        $optionDepart = Department::getDepartmentAll();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.InfoPerson.transferWorkView', array_merge([
            'optionDepart' => $optionDepart,
            'total' => count($contracts),
        ], $this->viewPermission));
    }


    /************************************************************************************************************************************
     * Chuyển đổi khoa ngành
     ************************************************************************************************************************************/
    public function viewTransferDepartment($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Chuyển đổi công tác';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $contracts = HrContracts::getListContractsByPersonId($person_id);

        $optionDepart = Department::getDepartmentAll();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.InfoPerson.transferDepartmentView', array_merge([
            'optionDepart' => $optionDepart,
            'total' => count($contracts),
        ], $this->viewPermission));
    }

    /************************************************************************************************************************************
     * Tạo tài khoản sử dụng hệ thống
     ************************************************************************************************************************************/
    public function getInfoPerson($personId)
    {
        CGlobal::$pageAdminTitle = 'Tạo tài khoản sử dụng hệ thống';
        $id = FunctionLib::outputId($personId);
        if (!$this->is_root && !in_array($this->permission_createrUser_full, $this->permission) && !in_array($this->permission_createrUser_create, $this->permission)) {
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
        return view('hr.InfoPerson.addUserToPerson', array_merge([
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

    public function postInfoPerson($personId)
    {
        CGlobal::$pageAdminTitle = 'Tạo tài khoản sử dụng hệ thống';
        $id = FunctionLib::outputId($personId);
        if (!$this->is_root && !in_array($this->permission_createrUser_full, $this->permission) && !in_array($this->permission_createrUser_create, $this->permission)) {
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
        return view('hr.InfoPerson.addUserToPerson', array_merge([
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

}
