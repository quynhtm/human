<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Allowance;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\HrWageStepConfig;
use App\Http\Models\Hr\Payroll;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Admin\Role;
use App\Http\Models\Hr\Salary;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use App\Library\AdminFunction\Pagging;

class ReportController extends BaseAdminController
{
    private $viewTienLuongCongChuc = 'viewTienLuongCongChuc';
    private $exportTienLuongCongChuc = 'exportTienLuongCongChuc';

    private $personViewTienLuongCongChuc = 'personViewTienLuongCongChuc';
    private $personExportTienLuongCongChuc = 'personExportTienLuongCongChuc';
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
            'viewTienLuongCongChuc' => in_array($this->viewTienLuongCongChuc, $this->permission) ? 1 : 0,
            'exportTienLuongCongChuc' => in_array($this->exportTienLuongCongChuc, $this->permission) ? 1 : 0,

            'personViewTienLuongCongChuc' => in_array($this->personViewTienLuongCongChuc, $this->permission) ? 1 : 0,
            'personExportTienLuongCongChuc' => in_array($this->personExportTienLuongCongChuc, $this->permission) ? 1 : 0,
        ];
    }
    public function viewTienLuongCongChuc(){
        CGlobal::$pageAdminTitle = 'Báo cáo danh sách và tiền lương công chức';

        if (!$this->is_root && !in_array($this->viewTienLuongCongChuc, $this->permission) && !in_array($this->exportTienLuongCongChuc, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }

        //lấy mảng id NS có
        $searchPerson['person_status'] = array(Define::PERSON_STATUS_DANGLAMVIEC, Define::PERSON_STATUS_SAPNGHIHUU, Define::PERSON_STATUS_CHUYENCONGTAC);
        $search['person_depart_id'] = ($this->is_root) ? (int)Request::get('person_depart_id', Define::STATUS_HIDE) : $this->user_depart_id;
        $searchPerson['field_get'] = 'person_id,person_name,person_depart_id';
        $totalPerson = 0;
        $dataPerson = Person::searchByCondition($searchPerson, 0, 0, $totalPerson);
        $arrPerson = array();
        foreach($dataPerson as $_user){
            $arrPerson[$_user->person_id] = array(
                'person_name'=>$_user->person_name,
                'person_depart_id'=>$_user->person_depart_id,
            );
        }

        //lấy mảng all của mã nghạch
        $arrWage = HrWageStepConfig::getArrayByType(Define::type_ma_ngach);

        //PayRoll
        $page_no = (int)Request::get('page_no', 1);
        $limit = CGlobal::number_show_40;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;
        $paging = '';

        $search['person_depart_id'] = (int)Request::get('person_depart_id', -1);
        $search['reportYear'] = (int)Request::get('reportYear', 0);
        $search['reportMonth'] = (int)Request::get('reportMonth', 0);
        $search['arrPerson'] = array_keys($arrPerson);
        $search['field_get'] = '';

        $data = Payroll::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        $this->getDataDefault();

        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);

        $arrMonth = FunctionLib::getListMonth();
        $arrYears = FunctionLib::getListYears();
        $optionYear = FunctionLib::getOption($arrYears, isset($search['reportYear'])? $search['reportYear']: date('Y',time()));
        $optionMonth = FunctionLib::getOption($arrMonth, isset($search['reportMonth'])? $search['reportMonth']: date('m',time()));

        $depart = Department::getDepartmentAll();
        $optionDepart = FunctionLib::getOption($depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Report.reportTienLuongCongChuc', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'optionYear' => $optionYear,
            'optionMonth' => $optionMonth,
            'optionDepart' => $optionDepart,
            'arrChucVu' => $arrChucVu,
            'arrDepart' => $depart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
            'arrPerson' => $arrPerson,
            'arrWage' => $arrWage,
        ], $this->viewPermission));
    }
    public function exportTienLuongCongChuc(){

        if (!$this->is_root && !in_array($this->viewTienLuongCongChuc, $this->permission) && !in_array($this->exportTienLuongCongChuc, $this->permission)
            && !in_array($this->personViewTienLuongCongChuc, $this->permission)&& !in_array($this->personExportTienLuongCongChuc, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        ini_set('max_execution_time', 0);

        //lấy mảng id NS có
        $searchPerson['person_status'] = array(Define::PERSON_STATUS_DANGLAMVIEC, Define::PERSON_STATUS_SAPNGHIHUU, Define::PERSON_STATUS_CHUYENCONGTAC);
        $searchPerson['field_get'] = 'person_id,person_name,person_depart_id,person_code,person_position_define_id';
        $totalPerson = 0;
        $dataPerson = Person::searchByCondition($searchPerson, 0, 0, $totalPerson);
        $arrPerson = array();
        foreach($dataPerson as $_user){
            $arrPerson[$_user->person_id] = array(
                'person_name'=>$_user->person_name,
                'person_code'=>$_user->person_code,
                'person_position_define_id'=>$_user->person_position_define_id,
                'person_depart_id'=>$_user->person_depart_id,
            );
        }
        //lấy mảng all của mã nghạch
        $searchWage['wage_step_config_status'] = Define::STATUS_SHOW;
        $searchWage['wage_step_config_type'] = Define::type_ma_ngach;
        $searchWage['field_get'] = 'wage_step_config_id,wage_step_config_name';
        $totalWage = 0;
        $dataWage = HrWageStepConfig::searchByCondition($searchWage, 0, 0, $totalWage);
        $arrWage = array();
        foreach($dataWage as $_wage){
            $arrWage[$_wage->wage_step_config_id] = $_wage->wage_step_config_name;
        }
        //chucvu
        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);

        //Deparmement
        $depart = Department::getDepartmentAll();

        //PayRoll
        $search = $data = array();
        $total = 0;

        $search['person_depart_id'] = (int)Request::get('person_depart_id', -1);
        $search['reportYear'] = (int)Request::get('reportYear', date('Y', time()));
        $search['reportMonth'] = (int)Request::get('reportMonth', date('m', time()));
        $search['arrPerson'] = array_keys($arrPerson);
        $search['field_get'] = '';

        $data = Payroll::searchByCondition($search, 1000, 0, $total);

        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load(Config::get('config.DIR_ROOT') ."app/Http/Controllers/Hr/report/reportTienLuongCongChuc.xls");
        $generatedDate = date("d-m-Y");

        $yearExport = $search['reportYear'];
        if($yearExport > 0){
            $titleReport = 'BÁO CÁO DANH SÁCH VÀ TIỀN LƯƠNG CÔNG CHỨC NĂM ' . $yearExport;
        }else{
            $titleReport = 'BÁO CÁO THỐNG KÊ LƯƠNG';
        }

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $titleReport);

        $i=8;
        $stt = 0;
        if($data){
            foreach ($data as $item){
                $i++;
                $stt++;
                $objPHPExcel->setActiveSheetIndex(0)->getRowDimension($i)->setRowHeight(15);
                $_time = '';
                if(isset($item->payroll_month) && isset($item->payroll_year) && $item->payroll_month > 0 && $item->payroll_year > 0){
                    $_time = $item->payroll_month .'/'. $item->payroll_year;
                }

                $depart_name = isset($depart[$arrPerson[$item->payroll_person_id]['person_depart_id']]) ? $depart[$arrPerson[$item->payroll_person_id]['person_depart_id']] : '';

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $stt)
                    ->setCellValue('B'.$i, $_time)
                    ->setCellValue('C'.$i, isset($arrPerson[$item->payroll_person_id]['person_code']) ? $arrPerson[$item->payroll_person_id]['person_code'] : '')
                    ->setCellValue('D'.$i, isset($arrPerson[$item->payroll_person_id]['person_name']) ? $arrPerson[$item->payroll_person_id]['person_name'] : '')
                    ->setCellValue('E'.$i, $depart_name)
                    ->setCellValue('F'.$i, isset($arrChucVu[$arrPerson[$item->payroll_person_id]['person_position_define_id']]) ? $arrChucVu[$arrPerson[$item->payroll_person_id]['person_position_define_id']] : '')
                    ->setCellValue('G'.$i, isset($arrWage[$item->ma_ngach]) ? $arrWage[$item->ma_ngach] : '')
                    ->setCellValue('H'.$i, $item->he_so_luong)
                    ->setCellValue('I'.$i, $item->phu_cap_chuc_vu)
                    ->setCellValue('J'.$i, $item->phu_cap_tham_nien_vuot)
                    ->setCellValue('K'.$i, $item->phu_cap_tham_nien_vuot_heso)
                    ->setCellValue('L'.$i, $item->phu_cap_trach_nhiem)
                    ->setCellValue('M'.$i, $item->phu_cap_tham_nien)
                    ->setCellValue('N'.$i, $item->phu_cap_tham_nien_heso)
                    ->setCellValue('O'.$i, $item->phu_cap_nghanh)
                    ->setCellValue('P'.$i, $item->phu_cap_nghanh_heso)
                    ->setCellValue('Q'.$i, $item->tong_he_so)

                    ->setCellValue('R'.$i, FunctionLib::numberFormat($item->luong_co_so))
                    ->setCellValue('S'.$i, FunctionLib::numberFormat($item->tong_tien))
                    ->setCellValue('T'.$i, FunctionLib::numberFormat($item->tong_tien_luong))
                    ->setCellValue('U'.$i, FunctionLib::numberFormat($item->tong_tien_baohiem))
                    ->setCellValue('V'.$i, FunctionLib::numberFormat($item->tong_luong_thuc_nhan));
            }
        }
        $filename = 'reportTienLuongCongChuc';
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$filename.'_'.$generatedDate.'.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        die;
    }
    public function viewLuongDetailPerson(){
        CGlobal::$pageAdminTitle = 'Chi tiết lương';

        if (!$this->is_root && !in_array($this->personViewTienLuongCongChuc, $this->permission) && !in_array($this->personExportTienLuongCongChuc, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        $infoPerson = ($this->user_object_id > 0) ? Person::getPersonById($this->user_object_id): array();

        //lấy mảng all của mã nghạch
        $arrWage = HrWageStepConfig::getArrayByType(Define::type_ma_ngach);
        //PayRoll
        $page_no = (int)Request::get('page_no', 1);
        $limit = CGlobal::number_show_40;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;
        $paging = '';

        $search['person_depart_id'] = (int)Request::get('person_depart_id', -1);
        $search['reportYear'] = (int)Request::get('reportYear', 0);
        $search['reportMonth'] = (int)Request::get('reportMonth', 0);
        $search['payroll_person_id'] = ($this->user_object_id == 0) ? -1: $this->user_object_id;
        $search['field_get'] = '';

        $data = Payroll::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        $this->getDataDefault();

        $arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $arrMonth = FunctionLib::getListMonth();
        $arrYears = FunctionLib::getListYears();
        $optionYear = FunctionLib::getOption($arrYears, isset($search['reportYear'])? $search['reportYear']: date('Y',time()));
        $optionMonth = FunctionLib::getOption($arrMonth, isset($search['reportMonth'])? $search['reportMonth']: date('m',time()));

        $depart = Department::getDepartmentAll();
        $optionDepart = FunctionLib::getOption($depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Report.reportLuongDetailPerson', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'optionYear' => $optionYear,
            'optionMonth' => $optionMonth,
            'optionDepart' => $optionDepart,
            'arrChucVu' => $arrChucVu,
            'arrDepart' => $depart,
            'infoPerson' => $infoPerson,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
            'arrWage' => $arrWage,
        ], $this->viewPermission));
    }
}
