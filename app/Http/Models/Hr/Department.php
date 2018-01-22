<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 01/2017
* @Version   : 1.0
*/
namespace App\Http\Models\Hr;
use App\Http\Models\BaseModel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;

class Department extends BaseModel{

    protected $table = Define::TABLE_HR_DEPARTMENT;
    protected $primaryKey = 'department_id';
    public $timestamps = false;

    protected $fillable = array('department_id', 'department_type', 'department_parent_id', 'department_name', 'department_project', 'department_level',
        'department_link', 'department_status','department_order','department_creater_time','department_user_id_creater','department_user_name_creater',
        'department_update_time','department_user_id_update','department_user_name_update',
        'department_leader', 'department_phone', 'department_email', 'department_fax', 'department_postion','department_num_tax',
        'department_num_bank', 'department_name_bank', 'department_position_bank'
        );

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Department();
            $fieldInput = $checkData->checkField($data);
            $item = new Department();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->department_id, $item);
            return $item->department_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Department();
            $fieldInput = $checkData->checkField($data);
            $item = Department::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->department_id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function getItemById($id=0){
        $result = (Define::CACHE_ON) ? Cache::get(Define::CACHE_DEPARTMENT_ID.$id) : array();
        try {
            if(empty($result)){
                $result = Department::where('department_id', $id)->first();
                if($result && Define::CACHE_ON){
                    Cache::put(Define::CACHE_DEPARTMENT_ID.$id, $result, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
        } catch (PDOException $e) {
            throw new PDOException();
        }
        return $result;
    }
    public function checkField($dataInput) {
        $fields = $this->fillable;
        $dataDB = array();
        if(!empty($fields)) {
            foreach($fields as $field) {
                if(isset($dataInput[$field])) {
                    $dataDB[$field] = $dataInput[$field];
                }
            }
        }
        return $dataDB;
    }
    public static function deleteItem($id){
        if($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = Department::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->department_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }
    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Department::where('department_id','>',0);
            if (isset($dataSearch['department_name']) && $dataSearch['department_name'] != '') {
                $query->where('department_name','LIKE', '%' . $dataSearch['department_name'] . '%');
            }
            if (isset($dataSearch['department_status']) && $dataSearch['department_status']!= -1) {
                $query->where('department_status', $dataSearch['department_status']);
            }

            $total = $query->count();
            $query->orderBy('department_order', 'asc');

            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();

            if($limit > 0){
                $query->take($limit);
            }
            if($offset > 0){
                $query->skip($offset);
            }
            if(!empty($fields)){
                $result = $query->get($fields);
            }else{
                $result = $query->get();
            }
            return $result;
        }catch (PDOException $e){
            throw new PDOException();
        }
    }
    public static function getAllParentMenu() {
        $data = Cache::get(Define::CACHE_ALL_DEPARTMENT);
        if (sizeof($data) == 0) {
            $list = Department::where('department_id', '>', 0)
                ->where('department_parent_id',0)
                ->where('department_status',Define::STATUS_SHOW)
                ->orderBy('department_order','asc')->get();
            if($list){
                foreach($list as $itm) {
                    $data[$itm['department_id']] = $itm['department_name'];
                }
            }
            if(!empty($data)){
                Cache::put(Define::CACHE_ALL_DEPARTMENT, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }
    public static function buildMenuAdmin(){
        $data = $menuTree = array();
        $menuTree = Cache::get(Define::CACHE_TREE_DEPARTMENT);
        if (sizeof($menuTree) == 0) {
            $search['active'] = Define::STATUS_SHOW;
            $dataSearch = Department::searchByCondition($search, 200, 0,$total);
            if(!empty($dataSearch)){
                $data = Department::getTreeMenu($dataSearch);
                $data = !empty($data)? $data :$dataSearch;
            }
            if(!empty($data)){
                foreach($data as $menu){
                    if($menu['department_parent_id'] == 0){
                        $menuTree[$menu['department_id']] = array(
                            'name'=>$menu['department_name'],
                            'link'=>'javascript:void(0)',
                        );
                    }else{
                        if(isset($menuTree[$menu['department_parent_id']]['arr_link_sub'])){
                            $tempLink = $menuTree[$menu['department_parent_id']]['arr_link_sub'];
                            array_push($tempLink,$menu['department_link']);
                            $menuTree[$menu['department_parent_id']]['arr_link_sub'] = $tempLink;

                            //sub
                            $tempSub = $menuTree[$menu['department_parent_id']]['sub'];
                            $arrSub = array('department_id'=>$menu['department_id'], 'name'=>$menu['department_name'], 'RouteName'=>$menu['department_link'], 'permission'=>'');
                            array_push($tempSub,$arrSub);
                            $menuTree[$menu['department_parent_id']]['sub'] = $tempSub;
                        }else{
                            $menuTree[$menu['department_parent_id']]['arr_link_sub'] = array($menu['department_link']);
                            $menuTree[$menu['department_parent_id']]['sub'] = array(
                                array('department_id'=>$menu['department_id'],'name'=>$menu['department_name'],'RouteName'=>$menu['department_link'], 'permission'=>''),);
                        }
                    }
                }
            }
            if(!empty($menuTree)){
                Cache::put(Define::CACHE_TREE_DEPARTMENT, $menuTree, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $menuTree;
    }
    public static function getTreeMenu($data){
        $max = 0;
        $aryCategoryProduct = $arrCategory = array();
        if(!empty($data)){
            foreach ($data as $k=>$value){
                $max = ($max < $value->department_parent_id)? $value->department_parent_id : $max;
                $arrCategory[$value->department_id] = array(
                    'department_id'=>$value->department_id,
                    'department_parent_id'=>$value->department_parent_id,
                    'department_order'=>$value->department_order,
                    'department_link'=>$value->department_link,
                    'department_status'=>$value->department_status,
                    'department_name'=>$value->department_name);
            }
        }

        if($max > 0){
            $aryCategoryProduct = self::showMenu($max, $arrCategory);
        }
        return $aryCategoryProduct;
    }
    public static function showMenu($max, $aryDataInput) {
        $aryData = array();
        if(is_array($aryDataInput) && count($aryDataInput) > 0) {
            foreach ($aryDataInput as $k => $val) {
                if((int)$val['department_parent_id'] == 0) {
                    $val['padding_left'] = '';
                    $val['menu_name_parent'] = '';
                    $aryData[] = $val;
                    self::showSubMenu($val['department_id'],$val['department_name'], $max, $aryDataInput, $aryData);
                }
            }
        }
        return $aryData;
    }
    public static function showSubMenu($cat_id,$cat_name, $max, $aryDataInput, &$aryData) {
        if($cat_id <= $max) {
            foreach ($aryDataInput as $chk => $chval) {
                if($chval['department_parent_id'] == $cat_id) {
                    $chval['padding_left'] = '--- ';
                    $chval['menu_name_parent'] = $cat_name;
                    $aryData[] = $chval;
                    self::showSubMenu($chval['department_id'],$chval['department_name'], $max, $aryDataInput, $aryData);
                }
            }
        }
    }

    public static function getListMenuPermission(){
        $data = (Define::CACHE_ON)? Cache::get(Define::CACHE_LIST_DEPARTMENT_PERMISSION) : array();
        if (sizeof($data) == 0) {
            $result = Department::where('department_id', '>', 0)
                ->where('department_status',Define::STATUS_SHOW)
                ->orderBy('department_parent_id','asc')->orderBy('department_order','asc')->get();
            if($result){
                foreach($result as $itm) {
                    $data[$itm['menu_id']] = $itm;
                }
            }
            if($data && Define::CACHE_ON){
                Cache::put(Define::CACHE_LIST_DEPARTMENT_PERMISSION, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            Cache::forget(Define::CACHE_DEPARTMENT_ID.$id);
           // Cache::forget(Define::CACHE_ALL_CHILD_DEPARTMENT_PARENT_ID.$id);
        }
        Cache::forget(Define::CACHE_LIST_DEPARTMENT_PERMISSION);
        Cache::forget(Define::CACHE_ALL_PARENT_DEPARTMENT);
        Cache::forget(Define::CACHE_TREE_DEPARTMENT);
    }
}
