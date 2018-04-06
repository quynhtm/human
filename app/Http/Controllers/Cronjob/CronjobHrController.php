<?php
/**
 * QuynhTM
 */

namespace App\Http\Controllers\Cronjob;

use App\Http\Controllers\BaseCronjobController;
use App\Http\Models\Admin\Cronjob;

use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\QuitJob;

use App\Http\Models\Hr\Retirement;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Curl;
use Illuminate\Support\Facades\URL;

class CronjobHrController extends BaseCronjobController{

    private $limit = CGlobal::number_show_1000;
    private $total = 0;
    private $offset = 0;

	public function __construct(){
		parent::__construct();
	}

	//quét cac cronjob để run
	public function callRunCronjob(){
        $listCronjob = Cronjob::getListData();
        if(!empty($listCronjob)){
            foreach ($listCronjob as $val){
                if($val['cronjob_router'] != ''){
                    $dateRun = $val['cronjob_date_run'];
                    $timeRun = date('Ymd',$dateRun);
                    $timeNow = date('Ymd',time());
                    if($timeNow != $timeRun){
                        $curl = Curl::getInstance();
                        $call = $curl->get(URL::route($val['cronjob_router']));
                        $dataCurl = json_decode($call, true);
                        if(!empty($dataCurl)){
                            //cap nhat bang cronjob
                            $dataUpdateCronjob['cronjob_date_run'] = time();
                            $dataUpdateCronjob['cronjob_number_running'] = $val->cronjob_number_running+1;
                            $dataUpdateCronjob['cronjob_result'] = $val->cronjob_number_running.'<br/>'.$call;
                            Cronjob::updateItem($val->cronjob_id,$dataUpdateCronjob);
                        }
                    }
                }
            }
        }
    }

	//nghỉ việc
    public function runCronjobQuitJob(){
        $dataSearch['person_status'] = Define::PERSON_STATUS_DANGLAMVIEC;
        $dataSearch['field_get'] = 'person_id';
        $dataPerson = Person::searchByCondition($dataSearch, $this->limit, $this->offset, $this->total);
        $arrPersonId = array();
        if(count($dataPerson) > 0){
            foreach ($dataPerson as $va){
                $arrPersonId [$va->person_id] = $va->person_id;
            }

            if(!empty($arrPersonId)){
                $dataQuitJob = QuitJob::whereIn('quit_job_person_id', $arrPersonId)
                    ->where('quit_job_type', Define::QUITJOB_THOI_VIEC)->get();
                if(count($dataQuitJob) > 0){
                    foreach ($dataQuitJob as $val){
                        $person_id = $val->quit_job_person_id;
                        $dateAction = $val->quit_job_date_creater;
                        $timeRun = date('Ymd',$dateAction);
                        $timeNow = date('Ymd',time());
                        if($timeRun <= $timeNow){
                            $updateStatusPerson['person_status'] = Define::PERSON_STATUS_NGHIVIEC;
                            if(Person::updateItem($person_id,$updateStatusPerson)){
                                $updateStatusPerson['name_job'] = 'Cập nhật thành công NS nghỉ việc';
                                $updateStatusPerson['person_id'] = $person_id;
                                $updateStatusPerson['date'] = date('d-m-Y H:i:s', time());
                                return $this->returnResultSuccess($updateStatusPerson);
                            }
                        }
                    }
                }else{
                    $data['name_job'] = 'Không có thông tin nghỉ việc';
                    $data['person_id'] = $arrPersonId;
                    $data['date'] = date('d-m-Y H:i:s', time());
                    return $this->returnResultSuccess($data);
                }

            }else{
                $data['name_job'] = 'Không có thông tin nhân sự 1';
                $data['person_id'] = $arrPersonId;
                $data['date'] = date('d-m-Y H:i:s', time());
                return $this->returnResultSuccess($data);
            }

        }else{
            $data['name_job'] = 'Không có thông tin nhân sự 2';
            $data['person_id'] = $arrPersonId;
            $data['date'] = date('d-m-Y H:i:s', time());
            return $this->returnResultSuccess($data);
        }
        return $this->returnResultError($dataQuitJob);
    }

    //Chuyển công tác, nghỉ việc
    public function runCronjobMoveJob(){
        $dataSearch['person_status'] = Define::PERSON_STATUS_DANGLAMVIEC;
        $dataSearch['field_get'] = 'person_id';
        $dataPerson = Person::searchByCondition($dataSearch, $this->limit, $this->offset, $this->total);
        $arrPersonId = array();
        if(count($dataPerson) > 0){
            foreach ($dataPerson as $va){
                $arrPersonId [$va->person_id] = $va->person_id;
            }

            if(!empty($arrPersonId)){
                $dataQuitJob = QuitJob::whereIn('quit_job_person_id', $arrPersonId)
                    ->where('quit_job_type', Define::QUITJOB_CHUYEN_CONGTAC)->get();
                if(count($dataQuitJob) > 0){
                    foreach ($dataQuitJob as $val){
                        $person_id = $val->quit_job_person_id;
                        $dateAction = $val->quit_job_date_creater;
                        $timeRun = date('Ymd',$dateAction);
                        $timeNow = date('Ymd',time());
                        if($timeRun <= $timeNow){
                            $updateStatusPerson['person_status'] = Define::PERSON_STATUS_CHUYENCONGTAC;
                            if(Person::updateItem($person_id,$updateStatusPerson)){
                                $updateStatusPerson['name_job'] = 'Cập nhật thành công NS chuyển công tác';
                                $updateStatusPerson['person_id'] = $person_id;
                                $updateStatusPerson['date'] = date('d-m-Y H:i:s', time());
                                return $this->returnResultSuccess($updateStatusPerson);
                            }
                        }
                    }
                }else{
                    $data['name_job'] = 'Không có thông tin chuyển công tác';
                    $data['person_id'] = $arrPersonId;
                    $data['date'] = date('d-m-Y H:i:s', time());
                    return $this->returnResultSuccess($data);
                }

            }else{
                $data['name_job'] = 'Không có thông tin nhân sự 1';
                $data['person_id'] = $arrPersonId;
                $data['date'] = date('d-m-Y H:i:s', time());
                return $this->returnResultSuccess($data);
            }

        }else{
            $data['name_job'] = 'Không có thông tin nhân sự 2';
            $data['person_id'] = $arrPersonId;
            $data['date'] = date('d-m-Y H:i:s', time());
            return $this->returnResultSuccess($data);
        }
        return $this->returnResultError($dataQuitJob);
    }

    //Sắp nghỉ hưu và nghỉ hưu
    public function runCronjobRetirement(){
        $dataSearch['person_status'] = array(Define::PERSON_STATUS_DANGLAMVIEC, Define::PERSON_STATUS_SAPNGHIHUU);
        $dataSearch['field_get'] = 'person_id';
        $dataPerson = Person::searchByCondition($dataSearch, $this->limit, $this->offset, $this->total);
        $arrPersonId = array();
        if(count($dataPerson) > 0){
            foreach ($dataPerson as $va){
                $arrPersonId [$va->person_id] = $va->person_id;
            }

            if(!empty($arrPersonId)){
                $dataRetirement = Retirement::whereIn('retirement_person_id', $arrPersonId)->get();
                $msg = ' cronjob nghỉ hưu';
                if(count($dataRetirement) > 0){
                    foreach ($dataRetirement as $val){
                        $person_id = $val->retirement_person_id;
                        $date1 = $val->retirement_date_creater;//ngày ra quyết định nghỉ hưu
                        $date2 = $val->retirement_date_notification;//ngày ra thông báo nghỉ hưu
                        $date3 = $val->retirement_date;//ngày nghỉ hưu chính thức

                        $time1 = date('Ymd',$date1);
                        $time2 = date('Ymd',$date2);
                        $time3 = date('Ymd',$date3);
                        $timeNow = date('Ymd',time());
                        $time7day = date('Ymd',time()-7*24*60*60);

                        $update_flg = false;
                        $person_status = Define::PERSON_STATUS_DANGLAMVIEC;
                        if(($time7day <= $time1 && $time1 <= $timeNow) || $time7day <= $time2 && $time2 <= $timeNow){
                            $update_flg = true;
                            $person_status = Define::PERSON_STATUS_SAPNGHIHUU;
                            $msg = ' sắp nghỉ hưu';
                        }
                        if(($timeNow <= $time1) || ($timeNow <= $time2) || ($timeNow <= $time3)){
                            $update_flg = true;
                            $person_status = Define::PERSON_STATUS_NGHIHUU;
                            $msg = ' đã nghỉ hưu';
                        }
                        if($update_flg){
                            $updateStatusPerson['person_status'] = $person_status;
                            if(Person::updateItem($person_id,$updateStatusPerson)){
                                $updateStatusPerson['name_job'] = 'Cập nhật thành công NS'. $msg;
                                $updateStatusPerson['person_id'] = $person_id;
                                $updateStatusPerson['date'] = date('d-m-Y H:i:s', time());
                                return $this->returnResultSuccess($updateStatusPerson);
                            }
                        }
                    }
                }else{
                    $data['name_job'] = 'Không có thông tin '. $msg;
                    $data['person_id'] = $arrPersonId;
                    $data['date'] = date('d-m-Y H:i:s', time());
                    return $this->returnResultSuccess($data);
                }

            }else{
                $data['name_job'] = 'Không có thông tin nhân sự 1';
                $data['person_id'] = $arrPersonId;
                $data['date'] = date('d-m-Y H:i:s', time());
                return $this->returnResultSuccess($data);
            }

        }else{
            $data['name_job'] = 'Không có thông tin nhân sự 2';
            $data['person_id'] = $arrPersonId;
            $data['date'] = date('d-m-Y H:i:s', time());
            return $this->returnResultSuccess($data);
        }
        return $this->returnResultError($dataRetirement);
    }
}
