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
            'viewTienLuongCongChuc' => in_array($this->viewTienLuongCongChuc, $this->permission) ? 1 : 0,
            'exportTienLuongCongChuc' => in_array($this->exportTienLuongCongChuc, $this->permission) ? 1 : 0,
        ];
    }

    /*************************************************************************************************************************************
     * Báo cáo Tiền lương công chức
     ************************************************************************************************************************************/
    public function viewTienLuongCongChuc(){
        CGlobal::$pageAdminTitle = 'Báo cáo danh sách và tiền lương công chức';

        if (!$this->is_root && !in_array($this->viewTienLuongCongChuc, $this->permission) && !in_array($this->exportTienLuongCongChuc, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }

        //lấy mảng id NS có
        $searchPerson['person_status'] = array(Define::PERSON_STATUS_DANGLAMVIEC, Define::PERSON_STATUS_SAPNGHIHUU, Define::PERSON_STATUS_CHUYENCONGTAC);
        $searchPerson['field_get'] = 'person_id,person_name,person_depart_id,person_depart_name';
        $totalPerson = 0;
        $dataPerson = Person::searchByCondition($searchPerson, 0, 0, $totalPerson);
        $arrPerson = array();
        foreach($dataPerson as $_user){
            $arrPerson[$_user->person_id] = array(
                'person_name'=>$_user->person_name,
                'person_depart_id'=>$_user->person_depart_id,
                'person_depart_name'=>$_user->person_depart_name,
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

        //PayRoll
        $page_no = (int)Request::get('page_no', 1);
        $limit = CGlobal::number_show_40;
        $offset = ($page_no - 1) * $limit;
        $search = $data = array();
        $total = 0;
        $paging = '';

        $search['person_depart_id'] = (int)Request::get('person_depart_id', -1);
        $search['reportYear'] = (int)Request::get('reportYear', date('Y', time()));
        $search['reportMonth'] = (int)Request::get('reportMonth', date('m', time()));
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

        if (!$this->is_root && !in_array($this->viewTienLuongCongChuc, $this->permission) && !in_array($this->exportTienLuongCongChuc, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        ini_set('max_execution_time', 0);

        //lấy mảng id NS có
        $searchPerson['person_status'] = array(Define::PERSON_STATUS_DANGLAMVIEC, Define::PERSON_STATUS_SAPNGHIHUU, Define::PERSON_STATUS_CHUYENCONGTAC);
        $searchPerson['field_get'] = 'person_id,person_name,person_depart_id,person_depart_name';
        $totalPerson = 0;
        $dataPerson = Person::searchByCondition($searchPerson, 0, 0, $totalPerson);
        $arrPerson = array();
        foreach($dataPerson as $_user){
            $arrPerson[$_user->person_id] = array(
                'person_name'=>$_user->person_name,
                'person_code'=>$_user->person_code,
                'person_position_define_id'=>$_user->person_position_define_id,
                'person_depart_id'=>$_user->person_depart_id,
                'person_depart_name'=>$_user->person_depart_name,
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

        //PayRoll
        $search = $data = array();
        $total = 0;

        $search['person_depart_id'] = (int)Request::get('person_depart_id', -1);
        $search['reportYear'] = (int)Request::get('reportYear', date('Y', time()));
        $search['reportMonth'] = (int)Request::get('reportMonth', date('m', time()));
        $search['arrPerson'] = array_keys($arrPerson);
        $search['field_get'] = '';

        $data = Payroll::searchByCondition($search, 0, 0, $total);

        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load(Config::get('config.DIR_ROOT') ."app/Http/Controllers/Hr/report/reportTienLuongCongChuc.xls");
        $generatedDate = date("d-m-Y");
        $yearExport = $search['reportYear'];
        $titleReport = 'BÁO CÁO DANH SÁCH VÀ TIỀN LƯƠNG CÔNG CHỨC NĂM ' . $yearExport;

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', $titleReport);

        $i=6;
        $stt = 0;
        if($data){
            foreach ($data as $item){
                $i++;
                $stt++;
                $objPHPExcel->setActiveSheetIndex(0)->getRowDimension($i)->setRowHeight(15);

                $person_birth = (isset($item->person_birth) && $item->person_birth > 0) ? $item->person_birth : 0;
                $person_sex_1 = (isset($item->person_sex) && $item->person_sex == 1 && $person_birth > 0) ? date('d/m/Y', $person_birth)  : '';
                $person_sex_0 = (isset($item->person_sex) && $item->person_sex == 0 && $person_birth > 0) ? date('d/m/Y', $person_birth)  : '';

                if($person_sex_1 != ''){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, $person_sex_1);
                }
                if($person_sex_0 != ''){
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $person_sex_0);
                }

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$i, $stt)
                    ->setCellValue('B'.$i, isset($arrPerson[$item->payroll_person_id]['person_code']) ? $arrPerson[$item->payroll_person_id]['person_code'] : '')
                    ->setCellValue('C'.$i, isset($arrPerson[$item->payroll_person_id]['person_name']) ? $arrPerson[$item->payroll_person_id]['person_name'] : '')
                    ->setCellValue('E'.$i, isset($arrPerson[$item->payroll_person_id]['person_position_define_id']) ? $arrPerson[$item->payroll_person_id]['person_position_define_id'] : '')
                    ->setCellValue('F'.$i, isset($arrDepart[$item->person_depart_id]) ? $arrDepart[$item->person_depart_id] : '')
                    ->setCellValue('G'.$i, '')
                    ->setCellValue('H'.$i, '')
                    ->setCellValue('I'.$i, '')
                    ->setCellValue('J'.$i, '')
                    ->setCellValue('K'.$i, '')
                    ->setCellValue('L'.$i, '')
                    ->setCellValue('M'.$i, '')
                    ->setCellValue('N'.$i, '')
                    ->setCellValue('O'.$i, '');
            }
        }
        $filename = 'reportTienLuongCongChuc';
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$filename.'_'.$generatedDate.'.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');

        die;
    }
}
