<?php
/**
 * Created by PhpStorm.
 * User: Quynhtm
 * Date: 29/05/2015
 * Time: 8:24 CH
 */
namespace App\Http\Controllers\Admin;

use App\Http\Models\Hr\Person;
use App\Library\AdminFunction\CGlobal;
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
        return view('admin.AdminDashBoard.index',[
            'user'=>$this->user,
            'menu'=>$this->menuSystem,
            'data'=>$data,
            'listLink'=>$listLink,
            'lang'=>$this->languageSite,
            'is_root'=>$this->is_root]);
    }

    public function getNotifyList(){
        $depar_id =  ($this->is_root) ? (int)Define::STATUS_HIDE : (int)$this->user_depart_id;
        $listLink = CGlobal::$arrLinkListDash;
        $arrCacheNotify = array();
        foreach ($listLink as $val){
            $arrCacheNotify[$val['cacheNotify']] = self::sumTotalData($val['cacheNotify'],$depar_id);
        }

    }

    public function sumTotalData($nameCache, $depart_id){
        if($nameCache != ''){
            $total_item = Cache::get($nameCache .'_'. $depart_id);
            if (!$total_item) {
                $total_item = 0;
                $limit = CGlobal::number_show_20;
                $offset = 0;
                switch ($nameCache){
                    case 'viewBirthday';
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::$arrStatusPersonAction;
                        $search['start_birth'] = time();
                        $search['end_birth'] = strtotime(time() . Define::add_one_week);
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item);
                    break;
                    case 'viewQuitJob';
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::PERSON_STATUS_NGHIVIEC;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item);
                    break;
                    case 'viewMoveJob';
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::PERSON_STATUS_CHUYENCONGTAC;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item);
                    break;
                    case 'viewRetired';
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::PERSON_STATUS_NGHIHUU;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item);
                    break;
                    case 'viewPreparingRetirement';
                        $search['person_depart_id'] = $depart_id;
                        $search['person_status'] = Define::PERSON_STATUS_SAPNGHIHUU;
                        $search['field_get'] = 'person_id';
                        $data = Person::searchByCondition($search, $limit, $offset, $total_item);
                    break;
                    default:
                        break;
                }
                if ($total_item) {
                    Cache::put($nameCache .'_'. $depart_id, $total_item, Define::CACHE_TIME_TO_LIVE_HALF_DAY_DAY);
                }
            }
            return $total_item;
        }
    }
}