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

class Person extends BaseModel
{
    protected $table = Define::TABLE_HR_PERSON;
    protected $primaryKey = 'person_id';
    public $timestamps = false;

    protected $fillable = array('person_project', 'person_depart_id', 'person_depart_name', 'person_date_trial_work', 'person_date_start_work',
        'person_name', 'person_name_other','person_chung_minh_thu','person_date_range_cmt','person_issued_cmt',
        'person_birth','person_sex','person_mail','person_code','person_phone','person_telephone','person_position_define_id','person_position_define_name',
        'person_career_define_id','person_career_define_name','person_address_place_of_birth','person_province_place_of_birth','person_address_home_town','person_province_home_town',
        'person_address_current','person_province_current','person_wards_current','person_districts_current',
        'person_nation_define_id','person_nation_define_name','person_respect','person_height','person_weight',
        'person_blood_group_define_id','person_date_salary_increase','person_status','person_avatar',
        'person_creater_time','person_creater_user_id','person_creater_user_name',
        'person_update_time','person_update_user_id','person_update_user_name');

    public static function getPersonById($person_id){
        if((int)$person_id > 0){
            $data = Cache::get(Define::CACHE_PERSON.$person_id);
            if (sizeof($data) == 0) {
                $data = Person::find($person_id);
                if ($data && !empty($data)) {
                    Cache::put(Define::CACHE_PERSON.$person_id, $data, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
                }
            }
            return $data;
        }
        return false;
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = Person::where('person_id','>',0);
            if (isset($dataSearch['person_name']) && $dataSearch['person_name'] != '') {
                $query->where('person_name','LIKE', '%' . $dataSearch['person_name'] . '%');
            }
            if (isset($dataSearch['person_mail']) && $dataSearch['person_mail'] != '') {
                $query->where('person_mail','LIKE', '%' . $dataSearch['person_mail'] . '%');
            }

            if (isset($dataSearch['person_code']) && $dataSearch['person_code'] != '') {
                $query->where('person_code','LIKE', '%' . $dataSearch['person_code'] . '%');
            }

            if (isset($dataSearch['person_depart_id']) && $dataSearch['person_depart_id'] >0) {
                $query->where('person_depart_id', $dataSearch['person_depart_id'] );
            }

            if (isset($dataSearch['start_birth']) && $dataSearch['start_birth'] > 0) {
                $query->where('person_birth', '>=', $dataSearch['start_birth']);
            }

            if (isset($dataSearch['end_birth']) && $dataSearch['end_birth'] > 0) {
                $query->where('person_birth', '<=', $dataSearch['end_birth']);
            }

            if (isset($dataSearch['person_status']) && is_array($dataSearch['person_status'])) {
                $query->whereIn('person_status',  $dataSearch['person_status']);
            }
            elseif (isset($dataSearch['person_status']) && $dataSearch['person_status'] != '') {
                $query->where('person_status',  $dataSearch['person_status']);
            }

            if (isset($dataSearch['list_person_id']) && is_array($dataSearch['list_person_id']) && count($dataSearch['list_person_id']) > 0) {
                $query->where('person_id',  $dataSearch['list_person_id']);
            }

            if (isset($dataSearch['reportYear']) && $dataSearch['reportYear'] > 0) {
                $timeCurrentLast = date('d', time()).'-'.date('m', time()).'-'.$dataSearch['reportYear'].' '.date('H', time()).':'.date('i', time()) ;
                $timeCurrentLast = strtotime($timeCurrentLast);
                $timeCurrentFirst = '01-01-'.$dataSearch['reportYear'].' 00:00';
                $timeCurrentFirst = strtotime($timeCurrentFirst);
                $query->whereBetween('person_creater_time', array($timeCurrentFirst, $timeCurrentLast));
            }

            $total = $query->count();

            if(isset($dataSearch['orderBy']) && $dataSearch['orderBy'] != '' && isset($dataSearch['sortOrder']) && $dataSearch['sortOrder'] != ''){
                $query->orderBy($dataSearch['orderBy'], $dataSearch['sortOrder']);
            }else{
                $query->orderBy('person_id', 'desc');
            }

            //get field can lay du lieu
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

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Person();
            $fieldInput = $checkData->checkField($data);
            $item = new Person();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->person_id,$item);
            return $item->person_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Person();
            $fieldInput = $checkData->checkField($data);
            $item = Person::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->person_id,$item);
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
            $item = Person::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->person_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            Cache::forget(Define::CACHE_PERSON.$id);
        }
    }

    public static function getInfoPerson($person_id){
        if($person_id > 0){
            $person = Person::find($person_id);
            $person->contracts;
            return $person;
        }
    }

    public function contracts()
    {
        return $this->hasOne('App\Http\Models\Hr\HrContracts', 'contracts_person_id','person_id');
    }

    public function salary()
    {
        return $this->hasOne('App\Http\Models\Hr\Salary', 'salary_person_id','person_id');
    }
    public function allowance()
    {
        return $this->hasOne('App\Http\Models\Hr\Allowance', 'allowance_person_id','person_id');
    }

    public function passport()
    {
        return $this->hasOne('App\Http\Models\Hr\Passport', 'passport_person_id','person_id');
    }
}
