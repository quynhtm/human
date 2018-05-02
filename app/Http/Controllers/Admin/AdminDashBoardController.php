<?php
/**
 * Created by PhpStorm.
 * User: Quynhtm
 * Date: 29/05/2015
 * Time: 8:24 CH
 */
namespace App\Http\Controllers\Admin;

use App\Http\Models\Hr\HrContracts;
use App\Http\Models\Hr\Person;
use App\Library\AdminFunction\CGlobal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\BaseAdminController;
use Illuminate\Support\Facades\Session;
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;

class AdminDashBoardController extends BaseAdminController{
    private $error = array();
    public function __construct(){
        parent::__construct();
    }

    public function dashboard(){
        $total = 0;
        $data = array();
        $listLink = CGlobal::$arrLinkListDash;
        $arrNotify = $this->getNotifyList();
        return view('admin.AdminDashBoard.index',[
            'user'=>$this->user,
            'menu'=>$this->menuSystem,
            'data'=>$data,
            'listLink'=>$listLink,
            'arrNotify'=>$arrNotify,
            'lang'=>$this->languageSite,
            'is_root'=>$this->is_root]);
    }

    public function getNotifyList(){
        $depar_id =  ($this->is_root) ? (int)Define::STATUS_HIDE : (int)$this->user_depart_id;
        $listLink = CGlobal::$arrLinkListDash;
        $arrCacheNotify = array();
        foreach ($listLink as $val){
            $total = self::sumTotalData($val['cacheNotify'],$depar_id);
            $arrCacheNotify[$val['cacheNotify']] = ($total == Define::TOTAL_MAX)? 0: $total;
        }
        return $arrCacheNotify;
    }

    public function sumTotalData($nameCache, $depart_id){
        if($nameCache != ''){
            $total_item = Cache::get($nameCache .'_'. $depart_id);
            //$total_item = false;
            if (!$total_item) {
                $total_item = Define::TOTAL_MAX;
                $limit = CGlobal::number_show_20;
                $offset = 0;
                switch ($nameCache){
                    case 'viewBirthday';
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::$arrStatusPersonAction;
                        $search['start_birth'] = time();
                        $search['end_birth'] = strtotime(time() . Define::add_one_week);
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item,true);
                    break;
                    case 'viewQuitJob';// nghỉ việc
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::PERSON_STATUS_NGHIVIEC;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item,true);
                    break;
                    case 'viewMoveJob';//chuyển công tác
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::PERSON_STATUS_CHUYENCONGTAC;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item,true);
                    break;
                    case 'viewRetired';//nghỉ hưu
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::PERSON_STATUS_NGHIHUU;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item,true);
                    break;
                    case 'viewPreparingRetirement';//sắp nghỉ hưu
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::PERSON_STATUS_SAPNGHIHUU;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item,true);
                    break;
                    case 'viewDangVienPerson';//Đảng viên
                        $search['person_depart_id'] = $depart_id;
                        $search['person_is_dangvien'] = Define::DANG_VIEN;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item,true);
                    break;
                    case 'viewDealineSalary';//đến hạn tăng lương
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::$arrStatusPersonAction;
                        $search['start_dealine_salary'] = time();
                        $search['end_dealine_salary'] = strtotime(time() . Define::add_one_week);
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item,true);
                    break;
                    case 'viewDealineContract';//đến hạn tăng hợp đồng
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
                        if(!empty($arrPersonId)){
                            $search['person_depart_id'] = $depart_id;
                            $search['person_status'] = Define::$arrStatusPersonAction;
                            $search['list_person_id'] = $arrPersonId;
                            $search['field_get'] = 'person_id';
                            $data = Person::searchByCondition($search, $limit, $offset, $total_item,true);
                        }
                    break;
                    default:
                        break;
                }
                $total_item = ($total_item == 0)? Define::TOTAL_MAX : $total_item;
                if ($total_item != 0) {
                    Cache::put($nameCache .'_'. $depart_id, $total_item, Define::CACHE_TIME_TO_LIVE_HALF_DAY_DAY);
                }
            }
            return $total_item;
        }
    }
}