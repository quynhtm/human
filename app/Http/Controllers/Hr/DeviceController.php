<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\Device;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Hr\Person;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\Loader;
use App\Library\AdminFunction\Upload;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;
use Illuminate\Support\Facades\URL;

class DeviceController extends BaseAdminController{
    private $permission_view = 'device_view';
    private $permission_full = 'device_full';
    private $permission_delete = 'device_delete';
    private $permission_create = 'device_create';
    private $permission_edit = 'device_edit';
    private $arrStatus = array();
    private $error = array();
    private $arrDeviceType = array();
    private $arrDepartment = array();
    private $arrPersion = array();
    private $viewPermission = array();

    private $arrDepartmentType = array();
    public function __construct(){
        parent::__construct();
        CGlobal::$pageAdminTitle = 'Quản lý tài sản';
    }
    public function getDataDefault(){
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose',$this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show',$this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden',$this->languageSite)
        );
        $this->arrDeviceType = HrDefine::getArrayByType(Define::loai_thiet_bi);

        $totalPerson = 0;
        $dataSearchPerson['person_status'] = CGlobal::status_show;
        $dataSearchPerson['field_get'] = 'person_id,person_name';
        $listPerton = Person::searchByCondition($dataSearchPerson, 0, 0, $totalPerson);
        $arrPersion = array();
        if(sizeof($listPerton) > 0){
            foreach($listPerton as $persion){
                $arrPersion[$persion->person_id] = $persion->person_name;
            }
        }
        $this->arrPersion = array('--Chọn--') + $arrPersion;
    }
    public function getPermissionPage(){
        return $this->viewPermission = [
            'is_root'=> $this->is_root ? 1:0,
            'permission_edit'=>in_array($this->permission_edit, $this->permission) ? 1 : 0,
            'permission_create'=>in_array($this->permission_create, $this->permission) ? 1 : 0,
            'permission_remove'=>in_array($this->permission_delete, $this->permission) ? 1 : 0,
            'permission_full'=>in_array($this->permission_full, $this->permission) ? 1 : 0,
        ];
    }
    public function view(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['device_name'] = addslashes(Request::get('device_name',''));
        $dataSearch['device_type'] = addslashes(Request::get('device_type', -1));
        $dataSearch['device_status'] = (int)Request::get('device_status', -1);
        $dataSearch['field_get'] = '';

        $data = Device::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['device_status']);
        $optionDeviceType = FunctionLib::getOption($this->arrDeviceType, isset($data['device_type'])? $data['device_type']: CGlobal::status_show);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.view',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrStatus'=>$this->arrStatus,
            'arrDeviceType'=>$this->arrDeviceType,
            'optionDeviceType'=>$optionDeviceType,
            'arrPersion'=>$this->arrPersion,
        ],$this->viewPermission));
    }
    public function viewDeviceUse(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['device_name'] = addslashes(Request::get('device_name',''));
        $dataSearch['device_type'] = addslashes(Request::get('device_type', -1));
        $dataSearch['device_status'] = (int)Request::get('device_status', CGlobal::status_show);
        $dataSearch['device_person_id'] = (int)Request::get('device_person_id', 1);

        $dataSearch['field_get'] = '';

        $data = Device::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['device_status']);
        $optionDeviceType = FunctionLib::getOption($this->arrDeviceType, isset($data['device_type'])? $data['device_type']: CGlobal::status_show);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.view',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrStatus'=>$this->arrStatus,
            'arrDeviceType'=>$this->arrDeviceType,
            'optionDeviceType'=>$optionDeviceType,
            'arrPersion'=>$this->arrPersion,
        ],$this->viewPermission));
    }
    public function viewDeviceNotUse(){

        if(!$this->is_root && !in_array($this->permission_full,$this->permission)&& !in_array($this->permission_view,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $pageNo = (int) Request::get('page_no',1);
        $limit = CGlobal::number_limit_show;
        $total = 0;
        $offset = ($pageNo - 1) * $limit;

        $dataSearch['device_name'] = addslashes(Request::get('device_name',''));
        $dataSearch['device_type'] = addslashes(Request::get('device_type', -1));
        $dataSearch['device_status'] = (int)Request::get('device_status', CGlobal::status_show);
        $dataSearch['device_person_id'] = (int)Request::get('device_person_id', 0);

        $dataSearch['field_get'] = '';

        $data = Device::searchByCondition($dataSearch, $limit, $offset,$total);
        unset($dataSearch['field_get']);
        $paging = $total > 0 ? Pagging::getNewPager(3,$pageNo,$total,$limit,$dataSearch) : '';

        $this->getDataDefault();
        $optionStatus = FunctionLib::getOption($this->arrStatus, $dataSearch['device_status']);
        $optionDeviceType = FunctionLib::getOption($this->arrDeviceType, isset($data['device_type'])? $data['device_type']: CGlobal::status_show);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.view',array_merge([
            'data'=>$data,
            'dataSearch'=>$dataSearch,
            'total'=>$total,
            'stt'=>($pageNo - 1) * $limit,
            'paging'=>$paging,
            'optionStatus'=>$optionStatus,
            'arrStatus'=>$this->arrStatus,
            'arrDeviceType'=>$this->arrDeviceType,
            'optionDeviceType'=>$optionDeviceType,
            'arrPersion'=>$this->arrPersion,
        ],$this->viewPermission));
    }
    public function getItem($ids) {

        Loader::loadCSS('lib/upload/cssUpload.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/upload/jquery.uploadfile.js', CGlobal::$POS_END);
        Loader::loadJS('admin/js/baseUpload.js', CGlobal::$POS_END);
        Loader::loadJS('lib/dragsort/jquery.dragsort.js', CGlobal::$POS_HEAD);
        Loader::loadCSS('lib/jAlert/jquery.alerts.css', CGlobal::$POS_HEAD);
        Loader::loadJS('lib/jAlert/jquery.alerts.js', CGlobal::$POS_END);

        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }
        $data = array();
        $department_id = 0;
        if($id > 0) {
            $data = Device::getItemById($id);
            $department_id = $data->device_depart_id;
        }

        $this->getDataDefault();

        //Get data department
        $totalCat = 0;
        $strDeparment = '';
        $dataSearchCatDepartment['department_status'] = -1;
        $dataDepartmentCateSearch = Department::searchByCondition($dataSearchCatDepartment, 2000, 0, $totalCat);
        $this->showCategoriesOption($dataDepartmentCateSearch, 0, '', $strDeparment, $department_id);

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['device_status'])? $data['device_status']: CGlobal::status_show);
        $optionDeviceType = FunctionLib::getOption($this->arrDeviceType, isset($data['device_type'])? $data['device_type']: CGlobal::status_show);
        $optionPersion = FunctionLib::getOption($this->arrPersion, isset($data['device_person_id'])? $data['device_person_id'] : FunctionLib::inputId(-1));

        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'optionStatus'=>$optionStatus,
            'optionDeviceType'=>$optionDeviceType,
            'optionDepartment'=>$strDeparment,
            'optionPersion'=>$optionPersion,
        ],$this->viewPermission));
    }
    public function postItem($ids) {
        $id = FunctionLib::outputId($ids);

        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_edit,$this->permission) && !in_array($this->permission_create,$this->permission)){
            return Redirect::route('admin.dashboard',array('error'=>Define::ERROR_PERMISSION));
        }

        $id_hiden = (int)Request::get('id_hiden', 0);
        $data = $_POST;

        if(isset($data['device_type'])) {
            $data['device_type'] = (int)$data['device_type'];
        }

        if(isset($data['device_date_use'])) {
            $data['device_date_use'] = FunctionLib::convertDate($data['device_date_use']);
        }
        if(isset($data['device_date_return'])) {
            $data['device_date_return'] = FunctionLib::convertDate($data['device_date_return']);
        }
        if(isset($data['device_date_of_manufacture'])) {
            $data['device_date_of_manufacture'] = FunctionLib::convertDate($data['device_date_of_manufacture']);
        }
        if(isset($data['device_date_warranty'])) {
            $data['device_date_warranty'] = FunctionLib::convertDate($data['device_date_warranty']);
        }
        if(isset($data['device_depart_id'])) {
            $data['device_depart_id'] = FunctionLib::outputId($data['device_depart_id']);
        }

        $data['device_status'] = (int)($data['device_status']);
        $img_current = '';
        if($id > 0){
            $dataCurrent = Device::getItemById($id);
            if(sizeof($dataCurrent) > 0){
                $img_current = $dataCurrent->device_image;
            }
        }
        $data['device_image'] = Upload::check_upload_file('device_image', $img_current, Define::FOLDER_DEVICE);

        if($this->valid($data) && empty($this->error)) {
            $id = ($id == 0) ? $id_hiden : $id;
            if($id > 0) {
                if(Device::updateItem($id, $data)) {
                    if(isset($data['clickPostPageNext'])){
                        return Redirect::route('hr.deviceEdit', array('id'=>FunctionLib::inputId(0)));
                    }else{
                        return Redirect::route('hr.deviceView');
                    }
                }
            }else{
                if(Device::createItem($data)) {
                    if(isset($data['clickPostPageNext'])){
                        return Redirect::route('hr.deviceEdit', array('id'=>FunctionLib::inputId(0)));
                    }else{
                        return Redirect::route('hr.deviceView');
                    }
                }
            }
        }

        $this->getDataDefault();
        //Get data department
        $department_id = 0;
        if(isset($data['device_depart_id'])){
            $department_id = $data['device_depart_id'];
        }
        $totalCat = 0;
        $strDeparment = '';
        $dataSearchCatDepartment['department_status'] = -1;
        $dataDepartmentCateSearch = Department::searchByCondition($dataSearchCatDepartment, 2000, 0, $totalCat);
        $this->showCategoriesOption($dataDepartmentCateSearch, 0, '', $strDeparment, $department_id);

        $optionStatus = FunctionLib::getOption($this->arrStatus, isset($data['device_status'])? $data['device_status']: CGlobal::status_show);
        $optionDeviceType = FunctionLib::getOption($this->arrDeviceType, isset($data['device_type'])? $data['device_type']: CGlobal::status_show);
        $optionPersion = FunctionLib::getOption($this->arrPersion, isset($data['device_person_id'])? $data['device_person_id']: -1);
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.Device.add',array_merge([
            'data'=>$data,
            'id'=>$id,
            'error'=>$this->error,
            'optionStatus'=>$optionStatus,
            'optionDeviceType'=>$optionDeviceType,
            'optionDepartment'=>$strDeparment,
            'optionPersion'=>$optionPersion,

        ],$this->viewPermission));
    }
    public function deleteDevice(){
        $data = array('isIntOk' => 0);
        if(!$this->is_root && !in_array($this->permission_full,$this->permission) && !in_array($this->permission_delete,$this->permission)){
            return Response::json($data);
        }
        $id = isset($_GET['id'])?FunctionLib::outputId($_GET['id']):0;
        if ($id > 0 && Device::deleteItem($id)) {
            $data['isIntOk'] = 1;
        }
        return Response::json($data);
    }
    private function valid($data=array()) {
        if(!empty($data)) {
            if(isset($data['device_type']) && trim($data['device_type']) == '') {
                $this->error[] = 'Loại thiết bị không được rỗng';
            }
            if(isset($data['device_name']) && trim($data['device_name']) == '') {
                $this->error[] = 'Tên thiết bị không được rỗng';
            }
        }
        return true;
    }
    public static function showCategoriesOption($categories, $parent_id = 0, $char='-', &$str, $default=0){
        foreach($categories as $key => $item){
            if($item['department_parent_id'] == $parent_id){
                if($default == $item['department_id']){
                    $selected = 'selected="selected"';
                }else{
                    $selected = '';
                }
                $str .= '<option '.$selected.' value="'.FunctionLib::inputId($item['department_id']).'">'.$char. $item['department_name'].'</option>';
                unset($categories[$key]);

                self::showCategoriesOption($categories, $item['department_id'], $char.'---', $str, $default);
            }
        }
    }
    public function exportDevice(){

        $dataSearch['device_name'] = Request::get('device_name', '');
        $dataSearch['device_type'] = Request::get('device_type', -1);
        $dataSearch['device_status'] = Request::get('device_status', -1);
        $dataSearch['order_sort_device_id'] = 'asc';
        $total = 0;
        $data = Device::searchByCondition($dataSearch, 0, 0, $total);
        FunctionLib::bug($data);
        if(sizeof($data) > 0){
            //Error reporting
            error_reporting(E_ALL);
            ini_set('display_errors', TRUE);
            ini_set('display_startup_errors', TRUE);
            date_default_timezone_set('Asia/Ho_Chi_Minh');

            if (PHP_SAPI == 'cli'){
                die('This app should only be run from a Web Browser');
            }

            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getProperties()->setCreator("DuyNX")
                ->setLastModifiedBy("DuyNX")
                ->setTitle("Office 2007 XLSX Document")
                ->setSubject("Office 2007 XLSX Document")
                ->setDescription("Document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("View Result file");
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);

            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('DDDDDD');
            $objPHPExcel->getActiveSheet()->getStyle("A1:F1")->getFont()->setBold(true);
            $objPHPExcel->setActiveSheetIndex(0)->getRowDimension(1)->setRowHeight(20);
            $styleArray = array('borders' => array('allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN)));
            $objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'STT')
                ->setCellValue('B1', 'Tên khách hàng')
                ->setCellValue('C1', 'Địa chỉ')
                ->setCellValue('D1', 'SĐT')
                ->setCellValue('E1', 'COD')
                ->setCellValue('F1', 'Ghi chú');

            $i=1;
            $j=1;
            $r=1;//stt
            $row_merg = 0;
            $name_file = 'xuat_don_hang';

            foreach($data as $item) {
                $order_list_code = (isset($item->order_list_code) && $item->order_list_code != '') ? unserialize($item->order_list_code) : array();
                if(is_array($order_list_code) && sizeof($order_list_code) > 0){
                    $i++;
                    //$row = count($order_list_code);
                    //$row_merg = $i + $row - 1;

                    /*
                    if ($row_merg > $i) {
                        $objPHPExcel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $i . ':A' . $row_merg);
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $i . ':B' . $row_merg);
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $i . ':C' . $row_merg);
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('D' . $i . ':D' . $row_merg);
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('E' . $i . ':E' . $row_merg);
                        $objPHPExcel->setActiveSheetIndex(0)->mergeCells('H' . $i . ':H' . $row_merg);
                    }
                    */
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$i, $r)
                        ->setCellValue('B'.$i, $item->order_title)
                        ->setCellValue('C'.$i, stripcslashes($item->order_address))
                        ->setCellValue('D'.$i, $item->order_phone)
                        ->setCellValue('E'.$i, (int)$item->order_total_lst)
                        ->setCellValue('F'.$i, stripcslashes($item->order_note));

                    /*
                    foreach($order_list_code as $_item){
                       $j++;
                       $objPHPExcel->setActiveSheetIndex(0)
                           ->setCellValue('F'.$j, $_item['pcode'])
                           ->setCellValue('G'.$j, $_item['pnum']);
                    }
                    $i = $row_merg;
                    */
                    $r++;
                    $objPHPExcel->setActiveSheetIndex(0)->getRowDimension($i)->setRowHeight(15);
                }
            }

            $objPHPExcel->getActiveSheet()->setTitle($name_file.'.xls');
            $objPHPExcel->setActiveSheetIndex(0);

            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$name_file.'.xls"');
            header('Cache-Control: max-age=0');
            header('Cache-Control: max-age=1');
            header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
            header ('Cache-Control: cache, must-revalidate');
            header ('Pragma: public');

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            die;
        }else{
            echo 'Không tồn tại đơn hàng. <a href="'.Config::get('config.BASE_URL').'admin/order">Quay lại</a>';die;
        }
        die;
    }
}
