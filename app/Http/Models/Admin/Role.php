<?php

namespace App\Http\Models\Admin;
use App\Http\Models\BaseModel;

use App\Library\AdminFunction\FunctionLib;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\library\AdminFunction\Define;
use App\library\AdminFunction\Memcache;
use Illuminate\Support\Facades\Cache;

class Role extends BaseModel
{
    protected $table = Define::TABLE_ROLE;
    protected $primaryKey = 'role_id';
    public $timestamps = false;

    protected $fillable = array('role_name','role_project', 'role_order', 'role_status');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Role();
            $fieldInput = $checkData->checkField($data);
            $item = new Role();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->role_id,$item);
            return $item->role_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Role();
            $fieldInput = $checkData->checkField($data);
            $item = Role::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->role_id,$item);
            return true;
        } catch (PDOException $e) {
            //var_dump($e->getMessage());
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
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
            $item = Role::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->role_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
//        FunctionLib::debug($dataSearch);
        try{
            $query = Role::where('role_id','>',0);
            if (isset($dataSearch['role_name']) && $dataSearch['role_name'] != '') {
                $query->where('role_name','LIKE', '%' . $dataSearch['role_name'] . '%');
            }

            $total = $query->count();
            $query->orderBy('role_id', 'desc');

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',',trim($dataSearch['field_get'])): array();
            if(!empty($fields)){
                $result = $query->take($limit)->skip($offset)->get($fields);
            }else{
                $result = $query->take($limit)->skip($offset)->get();
            }
            return $result;
        }catch (PDOException $e){
            throw new PDOException();
        }
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            //Cache::forget(Define::CACHE_CATEGORY_ID.$id);
        }
        Cache::forget(Define::CACHE_OPTION_CARRIER);
    }

    public static function getListAll() {
        $query = Role::where('role_id','>',0);
        $query->where('status','=', 1);
        $list = $query->get();
        return $list;
    }

    public static function getOptionCarrier() {
        $data = Cache::get(Define::CACHE_OPTION_CARRIER);
        if (sizeof($data) == 0) {
            $arr =  Role::getListAll();
            foreach ($arr as $value){
                $data[$value->carrier_setting_id] = $value->carrier_name;
            }
            if(!empty($data)){
                Cache::put(Define::CACHE_OPTION_CARRIER, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
            }
        }
        return $data;
    }

}
