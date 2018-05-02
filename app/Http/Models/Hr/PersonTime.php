<?php
/**
 * QuynhTM
 */
namespace App\Http\Models\Hr;
use App\Http\Models\BaseModel;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;

class PersonTime extends BaseModel
{
    protected $table = Define::TABLE_HR_PERSONNEL_TIME;
    protected $primaryKey = 'person_time_id';
    public $timestamps = false;

    protected $fillable = array('person_time_project', 'person_time_person_id','person_time_type', 'person_time_day', 'person_time_month', 'person_time_year','person_time_full');

    public static function getPersonTimeByPersonId($person_time_person_id,$person_time_type)
    {
        $data = PersonTime::where('person_time_person_id', $person_time_person_id)
                        ->where('person_time_type', $person_time_type)->first();
        return $data;
    }

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new PersonTime();
            $fieldInput = $checkData->checkField($data);
            $item = new PersonTime();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->person_time_id,$item);
            return $item->person_time_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new PersonTime();
            $fieldInput = $checkData->checkField($data);
            $item = PersonTime::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->person_time_id,$item);
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
            $item = PersonTime::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->person_time_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function createDataPersonTime($person_id = 0, $dataInput){
        if(!empty($dataInput) && $person_id > 0){
            $dataTime = array();
            $person_time_type = 0;
            if(isset($dataInput->person_birth) && abs($dataInput->person_birth) > 0){
                $person_time_type = Define::PERSONNEL_TIME_TYPE_BIRTH;
                $dataTime['person_time_day'] = date('d',$dataInput->person_birth);
                $dataTime['person_time_month'] = date('m',$dataInput->person_birth);
                $dataTime['person_time_year'] = date('Y',$dataInput->person_birth);
                $dataTime['person_time_full'] = $dataInput->person_birth;
                $dataTime['person_time_person_id'] = $person_id;
                $dataTime['person_time_type'] = $person_time_type;
            }

            if(!empty($dataTime) && $person_time_type > 0){
                $personTime = PersonTime::getPersonTimeByPersonId($person_id,$person_time_type);
                if(isset($personTime->person_time_id)){
                    PersonTime::updateItem($personTime->person_time_id,$dataTime);
                }else{
                    PersonTime::createItem($dataTime);
                }
            }
        }
    }
    public static function removeCache($id = 0,$data){
        if($id > 0){
            //Cache::forget(Define::CACHE_CATEGORY_ID.$id);
        }
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = PersonTime::where('person_time_id','>',0);
            if (isset($dataSearch['menu_name']) && $dataSearch['menu_name'] != '') {
                $query->where('menu_name','LIKE', '%' . $dataSearch['menu_name'] . '%');
            }
            $total = $query->count();
            $query->orderBy('person_time_id', 'desc');

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
}
