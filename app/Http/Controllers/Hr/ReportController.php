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
    public function viewTienLuongCongChuc()
    {
        CGlobal::$pageAdminTitle = 'Báo cáo danh sách và tiền lương công chức';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->viewTienLuongCongChuc, $this->permission) && !in_array($this->exportTienLuongCongChuc, $this->permission)) {
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

        if($sbmValue == 2){
            $this->ExportTienLuongCongChuc($data);
        }

        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $search['active']);

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Report.reportTienLuongCongChuc', array_merge([
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
    public function ExportTienLuongCongChuc($data){
        if (!$this->is_root && !in_array($this->viewTienLuongCongChuc, $this->permission) && !in_array($this->exportTienLuongCongChuc, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        ini_set('max_execution_time', 0);

        //Helper::debugData($projects);
        require(dirname(__FILE__) . '/../../../Library/ClassPhpExcel/PHPExcel/IOFactory.php');
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load(dirname(__FILE__) ."/report/reportTienLuongCongChuc.xls");
        $generatedDate = date("d-m-Y");

        $objPHPExcel->getActiveSheet()->mergeCells('B2:F2')->setCellValue('B2', __("Report on receivables"));

        /*if($request->get('startdate') !='' && $request->get('enddate')){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B3', '('. date("d/m/Y", strtotime($request->get('startdate'))) .' - '. date("d/m/Y", strtotime($request->get('enddate'))).')');
        }elseif ($request->get('startdate1') !='' && $request->get('enddate1')){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B3', '('. date("d/m/Y", strtotime($request->get('startdate1'))) .' - '. date("d/m/Y", strtotime($request->get('enddate1'))).')');
        }elseif ($request->get('start_payment_date') !='' && $request->get('enddate1')){
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B3', '('. date("d/m/Y", strtotime($request->get('start_payment_date'))) .' - '. date("d/m/Y", strtotime($request->get('end_payment_date'))).')');
        }*/
        //title table excel
        $row_title = 5;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$row_title, '#')
            ->setCellValue('B'.$row_title, __("Name"))
            ->setCellValue('C'.$row_title, __("Vimo code"))//mã giao dịch vimo
            ->setCellValue('D'.$row_title, __("Contract code"))//mã khoản vay
            ->setCellValue('E'.$row_title, __("BU"))
            ->setCellValue('F'.$row_title, __("Lender"))//NĐT
            ->setCellValue('G'.$row_title, __("Approve duration"))//số ngày vay
            ->setCellValue('H'.$row_title, __("Disbursement date"))//ngày giải ngân
            ->setCellValue('I'.$row_title, __("Approve amount"))//Sô tiền vay
            ->setCellValue('J'.$row_title, __("Repayment date"))//ngày đến hạn
            ->setCellValue('K'.$row_title, __("Period amount"))//tổng phải thu cuối kỳ
            ->setCellValue('L'.$row_title, __("Payment date"))//ngày thu thực tế
            ->setCellValue('M'.$row_title, __("Receipt code"))//mã phiếu thu
            ->setCellValue('N'.$row_title, __("Amount paid"))//số tiền đã trả
            ->setCellValue('O'.$row_title, __("The borrowed floor fee is borrowed"))//Phí sàn vay mượn được hưởng
            ->setCellValue('P'.$row_title, __("The principal paid for lender"))//số tiền gốc trả NĐT
            ->setCellValue('Q'.$row_title, __("The amount of interest paid lender"))//số tiền lãi trả cho NĐT
            ->setCellValue('R'.$row_title, __("The amount of money the investor is entitled to"))//số tiền NĐT được hưởng
            ->setCellValue('S'.$row_title, __("Additional income"))//lãi thu thêm
            ->setCellValue('T'.$row_title, __("Status"))
            ->setCellValue('U'.$row_title, __("Group debt"));//nhóm nợ

        $baseRow = 7;
        /*$check = ($projects->isEmpty())? false: true;*/

        if($data){
            foreach ($data as $r =>$value){
                $row = $baseRow + $r;
                $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$row, $r+1)
                    ->setCellValue('B'.$row, $r+1)
                    //->setCellValue('C'.$row, (isset($value->receipt()->first()->transaction_code) )? $value->receipt()->first()->transaction_code:'')//mã giao dịch vimo
                    ->setCellValue('C'.$row, $r+1)//mã giao dịch vimo
                    ->setCellValue('D'.$row, $r+1)//mã khoản vay
                    ->setCellValue('E'.$row, '')
                    ->setCellValue('F'.$row, '')
                    ->setCellValue('G'.$row, $r+1)//số ngày vay
                    ->setCellValue('H'.$row, $r+1 )//ngày giải ngân
                    ->setCellValue('I'.$row, $r+1)//Sô tiền vay
                    ->setCellValue('J'.$row, $r+1)//ngày đến hạn
                    ->setCellValue('K'.$row, $r+1)//tổng phải thu cuối kỳ
                    ->setCellValue('L'.$row, $r+1)//ngày thu thực tế
                    ->setCellValue('M'.$row, $r+1)//mã phiếu thu
                    ->setCellValue('N'.$row, $r+1)//số tiền đã trả
                    ->setCellValue('O'.$row, '')
                    ->setCellValue('P'.$row, '')
                    ->setCellValue('Q'.$row, '')
                    ->setCellValue('R'.$row, '')
                    ->setCellValue('S'.$row, '')//lãi thu thêm
                    ->setCellValue('T'.$row, $r+1)
                    ->setCellValue('U'.$row, '');//nhóm nợ
            }
            $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
        }

        $filename = 'reportTienLuongCongChuc';
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        // It will be called file.xls
        header('Content-Disposition: attachment; filename="'.$filename.'_'.$generatedDate.'.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        // Write file to the browser
        $objWriter->save('php://output');
        die;
    }
}
