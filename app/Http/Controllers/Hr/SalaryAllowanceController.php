<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\Bonus;
use App\Http\Models\Hr\Allowance;
use App\Http\Models\Hr\Salary;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class SalaryAllowanceController extends BaseAdminController
{
    //contracts
    private $salaryAllowanceFull = 'salaryAllowanceFull';
    private $salaryAllowanceView = 'salaryAllowanceView';
    private $salaryAllowanceDelete = 'salaryAllowanceDelete';
    private $salaryAllowanceCreate = 'salaryAllowanceCreate';

    private $arrStatus = array(1 => 'hiển thị', 2 => 'Ẩn');
    private $viewPermission = array();//check quyen

    public function __construct()
    {
        parent::__construct();

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
            'salaryAllowanceFull' => in_array($this->salaryAllowanceFull, $this->permission) ? 1 : 0,
            'salaryAllowanceView' => in_array($this->salaryAllowanceView, $this->permission) ? 1 : 0,
            'salaryAllowanceCreate' => in_array($this->salaryAllowanceCreate, $this->permission) ? 1 : 0,
            'salaryAllowanceDelete' => in_array($this->salaryAllowanceDelete, $this->permission) ? 1 : 0,
        ];
    }

    public function viewSalaryAllowance($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Lương, phụ cấp';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceView, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin lương
        $lương = Salary::getSalaryByPersonId($person_id);
        $arrNgachBac = HrDefine::getArrayByType(Define::nghach_bac);

        //thông tin phu cap
        $phucap = Salary::getSalaryByPersonId($person_id);

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.SalaryAllowance.View', array_merge([
            'person_id' => $person_id,
            'lương' => $lương,
            'phucap' => $phucap,
            'arrNgachBac' => $arrNgachBac,
            'infoPerson' => $infoPerson,
        ], $this->viewPermission));
    }

    /************************************************************************************************************************************
     * Thông tin lương
     ************************************************************************************************************************************/
    public function editSalary()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $str_object_id = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');

        $person_id = FunctionLib::outputId($personId);
        $salary_id = FunctionLib::outputId($str_object_id);

        $arrData = ['intReturn' => 0, 'msg' => ''];

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin chung
        $data = Salary::find($salary_id);

        $arrYears = FunctionLib::getListYears();
        $optionYears = FunctionLib::getOption($arrYears, isset($data['salary_year']) ? $data['salary_year'] : (int)date('Y', time()));

        $arrMonth = FunctionLib::getListMonth();
        $optionMonth = FunctionLib::getOption($arrMonth, isset($data['salary_month']) ? $data['salary_month'] : (int)date('m', time()));

        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.SalaryAllowance.SalaryPopupAdd', [
            'data' => $data,
            'infoPerson' => $infoPerson,
            'optionMonth' => $optionMonth,
            'optionYears' => $optionYears,
            'person_id' => $person_id,
            'salary_id' => $salary_id,
            'typeAction' => $typeAction,
        ], $this->viewPermission)->render();
        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }
    public function postSalary()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $data = $_POST;
        $person_id = Request::get('person_id', '');
        $salary_id = Request::get('salary_id', '');
        //FunctionLib::debug($data);
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if ($data['salary_salaries'] == '') {
            $arrData = ['intReturn' => 0, 'msg' => 'Dữ liệu nhập không đủ'];
        } else {
            if ($person_id > 0) {
                $data['salary_person_id'] = $person_id;
                if ($salary_id > 0) {
                    Salary::updateItem($salary_id, $data);
                } else {
                    Salary::createItem($data);
                }
                $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];

                //thông tin lương
                $lương = Salary::getSalaryByPersonId($person_id);
                $arrNgachBac = HrDefine::getArrayByType(Define::nghach_bac);

                $this->getDataDefault();
                $this->viewPermission = $this->getPermissionPage();
                $html = view('hr.SalaryAllowance.SalaryList' , array_merge([
                    'person_id' => $person_id,
                    'lương' => $lương,
                    'arrNgachBac' => $arrNgachBac,
                ], $this->viewPermission))->render();
                $arrData['html'] = $html;
            } else {
                $arrData = ['intReturn' => 0, 'msg' => 'Lỗi cập nhật' . $person_id];
            }
        }
        return response()->json($arrData);
    }
    public function deleteSalary()
    {
        //Check phan quyen.
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceDelete, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $str_object_id = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');
        $person_id = FunctionLib::outputId($personId);
        $salary_id = FunctionLib::outputId($str_object_id);
        if ($salary_id > 0 && Salary::deleteItem($salary_id)) {
            $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
            //thông tin view list\
            //thông tin lương
            $lương = Salary::getSalaryByPersonId($person_id);
            $arrNgachBac = HrDefine::getArrayByType(Define::nghach_bac);

            $this->getDataDefault();
            $this->viewPermission = $this->getPermissionPage();
            $html = view('hr.SalaryAllowance.SalaryList' , array_merge([
                'person_id' => $person_id,
                'lương' => $lương,
                '$arrNgachBac' => $arrNgachBac,
            ], $this->viewPermission))->render();
            $arrData['html'] = $html;
        }
        return Response::json($arrData);
    }

    /************************************************************************************************************************************
     * Thông tin phụ cấp
     ************************************************************************************************************************************/
    public function editAllowance()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $str_object_id = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');

        $person_id = FunctionLib::outputId($personId);
        $allowance_id = FunctionLib::outputId($str_object_id);

        $arrData = ['intReturn' => 0, 'msg' => ''];

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin chung
        $data = Allowance::find($allowance_id);

        $arrMonth = FunctionLib::getListMonth();
        $arrYears = FunctionLib::getListYears();
        $optionMonth2 = FunctionLib::getOption($arrMonth, isset($data['allowance_month_start']) ? $data['allowance_month_start'] : (int)date('m', time()));
        $optionYears2 = FunctionLib::getOption($arrYears, isset($data['allowance_year_start']) ? $data['allowance_year_start'] : (int)date('Y', time()));
        $optionMonth3 = FunctionLib::getOption($arrMonth, isset($data['allowance_month_end']) ? $data['allowance_month_end'] : (int)date('m', time()));
        $optionYears3 = FunctionLib::getOption($arrYears, isset($data['allowance_year_end']) ? $data['allowance_year_end'] : (int)date('Y', time()));

        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.SalaryAllowance.AllowancePopupAdd', [
            'data' => $data,
            'infoPerson' => $infoPerson,
            'optionMonth2' => $optionMonth2,
            'optionYears2' => $optionYears2,
            'optionMonth3' => $optionMonth3,
            'optionYears3' => $optionYears3,
            'person_id' => $person_id,
            'allowance_id' => $allowance_id,
            'typeAction' => $typeAction,
        ], $this->viewPermission)->render();
        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }
    public function postAllowance()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $data = $_POST;
        $person_id = Request::get('person_id', '');
        $salary_id = Request::get('salary_id', '');
        //FunctionLib::debug($data);
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if ($data['salary_salaries'] == '') {
            $arrData = ['intReturn' => 0, 'msg' => 'Dữ liệu nhập không đủ'];
        } else {
            if ($person_id > 0) {
                $data['salary_person_id'] = $person_id;
                if ($salary_id > 0) {
                    Allowance::updateItem($salary_id, $data);
                } else {
                    Allowance::createItem($data);
                }
                $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];

                //thông tin lương
                $phucap = Allowance::getAllowanceByPersonId($person_id);
                $arrNgachBac = HrDefine::getArrayByType(Define::nghach_bac);

                $this->getDataDefault();
                $this->viewPermission = $this->getPermissionPage();
                $html = view('hr.SalaryAllowance.AllowanceList' , array_merge([
                    'person_id' => $person_id,
                    'phucap' => $phucap,
                    'arrNgachBac' => $arrNgachBac,
                ], $this->viewPermission))->render();
                $arrData['html'] = $html;
            } else {
                $arrData = ['intReturn' => 0, 'msg' => 'Lỗi cập nhật' . $person_id];
            }
        }
        return response()->json($arrData);
    }
    public function deleteAllowance()
    {
        //Check phan quyen.
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if (!$this->is_root && !in_array($this->salaryAllowanceFull, $this->permission) && !in_array($this->salaryAllowanceDelete, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $str_object_id = Request::get('str_object_id', '');
        $typeAction = Request::get('typeAction', '');
        $person_id = FunctionLib::outputId($personId);
        $salary_id = FunctionLib::outputId($str_object_id);
        if ($salary_id > 0 && Allowance::deleteItem($salary_id)) {
            $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
            //thông tin view list\
            //thông tin lương
            $phucap = Allowance::getAllowanceByPersonId($person_id);
            $arrNgachBac = HrDefine::getArrayByType(Define::nghach_bac);

            $this->getDataDefault();
            $this->viewPermission = $this->getPermissionPage();
            $html = view('hr.SalaryAllowance.AllowanceList' , array_merge([
                'person_id' => $person_id,
                'phucap' => $phucap,
                'arrNgachBac' => $arrNgachBac,
            ], $this->viewPermission))->render();
            $arrData['html'] = $html;
        }
        return Response::json($arrData);
    }
}
