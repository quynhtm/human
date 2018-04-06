<?php

namespace App\Http\Controllers\Cronjob;

use App\Http\Controllers\BaseCronjobController;
use App\Http\Models\Admin\Cronjob;

use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\QuitJob;

use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class CronjobHrController extends BaseCronjobController{

    private $limit = CGlobal::number_show_1000;
    private $total = 0;
    private $offset = 0;

	public function __construct(){
		parent::__construct();
	}

	//quét nghỉ việc
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
                        $updateStatusPerson['person_status'] = Define::PERSON_STATUS_NGHIVIEC;
                        if(Person::updateItem($person_id,$updateStatusPerson)){
                            $updateStatusPerson['name_job'] = 'Cập nhật thành công NS nghỉ việc';
                            $updateStatusPerson['person_id'] = $person_id;
                            $updateStatusPerson['date'] = date('d-m-Y H:i:s', time());
                            return $this->returnResultSuccess($updateStatusPerson);
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
}
