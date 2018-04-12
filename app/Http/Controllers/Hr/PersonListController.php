<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;

use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\HrContracts;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\HrDefine;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet_PageSetup;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Fill;
use PHPExcel_Style_Border;

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
    private $arrSex = array();
    private $depart = array();
    private $arrChucVu = array();
    private $arrChucDanhNgheNghiep = array();
    private $viewPermission = array();//check quyen

    public function __construct()
    {
        parent::__construct();
        $this->arrMenuParent = array();
    }

    public function getDataDefault()
    {
        $this->arrStatus = array(
            CGlobal::status_hide => FunctionLib::controLanguage('status_all', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show', $this->languageSite),
            CGlobal::status_block => FunctionLib::controLanguage('status_block', $this->languageSite));

        $this->arrSex = array(
            CGlobal::status_hide => FunctionLib::controLanguage('sex_girl', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('sex_boy', $this->languageSite));

        $this->depart = Department::getDepartmentAll();
        $this->arrChucVu = HrDefine::getArrayByType(Define::chuc_vu);
        $this->arrChucDanhNgheNghiep = HrDefine::getArrayByType(Define::chuc_danh_nghe_nghiep);
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

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle);
        }
        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
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

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle);
        }
        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
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

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle);
        }
        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
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

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle);
        }

        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
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

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle);
        }

        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    /******************************************************************************************************************
     * NS sắp sinh nhật
     ******************************************************************************************************************/
    public function viewDealineContract()
    {
        CGlobal::$pageAdminTitle = 'Nhân sự sắp hết Hợp đồng';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->permission_full, $this->permission) && !in_array($this->permission_view, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }

        $searchContract['start_dealine_date'] = time();
        $searchContract['end_dealine_date'] = strtotime(time() . " +1 month");
        $searchContract['orderBy'] = 'contracts_dealine_date';
        $searchContract['sortOrder'] = 'asc';
        $search['field_get'] = 'contracts_person_id,contracts_id';//cac truong can lay
        $dataContract = HrContracts::searchByCondition($search, 5000, 0, $total2);
        $arrPersonId = array();
        if(count($dataContract) > 0){
            foreach ($dataContract as $contr){
                $arrPersonId[$contr->contracts_person_id] = $contr->contracts_person_id;
            }
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
        $search['list_person_id'] = $arrPersonId;

        $data = (count($arrPersonId) > 0)? Person::searchByCondition($search, $limit, $offset, $total): array();
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle);
        }
        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    /******************************************************************************************************************
     * NS sắp đến hạn tăng lương
     ******************************************************************************************************************/
    public function viewDealineSalary()
    {
        CGlobal::$pageAdminTitle = 'NS sắp tăng lương';
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
        $search['start_dealine_salary'] = time();
        $search['end_dealine_salary'] = strtotime(time() . " +1 month");
        $search['orderBy'] = 'person_date_salary_increase';
        $search['sortOrder'] = 'asc';
        //$search['field_get'] = 'menu_name,menu_id,parent_id';//cac truong can lay

        $data = Person::searchByCondition($search, $limit, $offset, $total);
        $paging = $total > 0 ? Pagging::getNewPager(3, $page_no, $total, $limit, $search) : '';

        if($sbmValue == 2){
            $this->exportData($data,'Danh sách '.CGlobal::$pageAdminTitle);
        }
        //FunctionLib::debug($data);
        $this->getDataDefault();
        $optionDepart = FunctionLib::getOption($this->depart, isset($search['person_depart_id']) ? $search['person_depart_id'] : 0);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.PersonList.viewCommon', array_merge([
            'data' => $data,
            'search' => $search,
            'total' => $total,
            'stt' => ($page_no - 1) * $limit,
            'paging' => $paging,
            'titlePage' => CGlobal::$pageAdminTitle,
            'arrSex' => $this->arrSex,
            'arrDepart' => $this->depart,
            'arrChucVu' => $this->arrChucVu,
            'arrChucDanhNgheNghiep' => $this->arrChucDanhNgheNghiep,
            'optionDepart' => $optionDepart,
            'arrLinkEditPerson' => CGlobal::$arrLinkEditPerson,
        ], $this->viewPermission));
    }

    public function exportData($data,$title ='') {
        if(empty($data)){
            return;
        }
        //FunctionLib::debug($data);
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();

        // Set Orientation, size and scaling
        $sheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);

        // Set font
        $sheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $sheet->getStyle('A1')->getFont()->setSize(16)->setBold(true)->getColor()->setRGB('000000');
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue("A1", $title );
        $sheet->getRowDimension("1")->setRowHeight(32);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        // setting header
        $position_hearder = 3;
        $sheet->getRowDimension($position_hearder)->setRowHeight(30);
        $val10 = 5; $val18 = 18; $val35 = 35;$val45 = 60; $val25 = 25;$val55 = 55;
        $ary_cell = array(
            'A'=>array('w'=>$val10,'val'=>'STT','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'B'=>array('w'=>$val35,'val'=>'Họ tên','align'=>PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
            'C'=>array('w'=>$val10,'val'=>'Giới tính','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'D'=>array('w'=>$val18,'val'=>'Ngày sinh','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'E'=>array('w'=>$val18,'val'=>'Ngày làm việc','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'F'=>array('w'=>$val35,'val'=>'Đơn vị bộ phận','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'G'=>array('w'=>$val35,'val'=>'Chức danh nghề nghiệp','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'H'=>array('w'=>$val35,'val'=>'Chức vụ','align'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        );

        //build header title
        foreach($ary_cell as $col => $attr){
            $sheet->getColumnDimension($col)->setWidth($attr['w']);
            $sheet->setCellValue("$col{$position_hearder}",$attr['val']);
            $sheet->getStyle($col)->getAlignment()->setWrapText(true);
            $sheet->getStyle($col . $position_hearder)->applyFromArray(
                array(
                    'fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '05729C'),
                        'style' => array('font-weight' => 'bold')
                    ),
                    'font'  => array(
                        'bold'  => true,
                        'color' => array('rgb' => 'FFFFFF'),
                        'size'  => 10,
                        'name'  => 'Verdana'
                    ),
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '333333')
                        )
                    ),
                    'alignment' => array(
                        'horizontal' => $attr['align'],
                    )
                )
            );
        }
        //hien thị dũ liệu
        $rowCount = $position_hearder+1; // hang bat dau xuat du lieu
        $i = 1;
        $break="\r";
        foreach ($data as $k => $v) {
            $sheet->getRowDimension($rowCount)->setRowHeight(30);//chiều cao của row

            $sheet->getStyle('A' . $rowCount)->getAlignment()->applyFromArray(
                array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('A' . $rowCount, $i);

            $sheet->getStyle('B' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
            $sheet->SetCellValue('B' . $rowCount, $v['person_name']);

            $sheet->getStyle('C' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('C' . $rowCount, isset($this->arrSex[$v['person_sex']])? $this->arrSex[$v['person_sex']]: '' );

            $sheet->getStyle('D' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('D' . $rowCount, date('d-m-Y',$v['person_birth']));

            $sheet->getStyle('E' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('E' . $rowCount, date('d-m-Y',$v['person_date_start_work']));

            $sheet->getStyle('F' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('F' . $rowCount, isset($this->depart[$v['person_depart_id']])? $this->depart[$v['person_depart_id']]: '');

            $sheet->getStyle('G' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('G' . $rowCount, isset($this->arrChucDanhNgheNghiep[$v['person_career_define_id']])? $this->arrChucDanhNgheNghiep[$v['person_career_define_id']]: '');

            $sheet->getStyle('H' . $rowCount)->getAlignment()->applyFromArray(array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,));
            $sheet->SetCellValue('H' . $rowCount, isset($this->arrChucVu[$v['person_position_define_id']])? $this->arrChucVu[$v['person_position_define_id']]: '');

            $rowCount++;
            $i++;
        }

        // output file
        ob_clean();
        $filename = "Danh sách nhân sự" . "_" . date("_d/m_") . '.xls';
        @header("Cache-Control: ");
        @header("Pragma: ");
        @header("Content-type: application/octet-stream");
        @header("Content-Disposition: attachment; filename=\"{$filename}\"");

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save("php://output");
        exit();
    }
}
