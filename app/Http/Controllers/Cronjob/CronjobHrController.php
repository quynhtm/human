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
        if($dataPerson){
            foreach ($dataPerson as $va){
                $arrPersonId [$va->person_id] = $va->person_id;
            }
            if(!empty($arrPersonId)){

            }
        }
        FunctionLib::debug($dataPerson);
    }
}
