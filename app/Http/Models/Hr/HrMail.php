<?php
/*
* @Created by: HaiAnhEm
* @Author    : nguyenduypt86@gmail.com
* @Date      : 02/2018
* @Version   : 1.0
*/
namespace App\Http\Models\Hr;
use App\Http\Models\BaseModel;

use App\Library\AdminFunction\Define;
use App\Library\AdminFunction\FunctionLib;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HrMail extends BaseModel{
    protected $table = Define::TABLE_HR_MAIL;
    protected $primaryKey = 'hr_mail_id';
    public $timestamps = false;

    protected $fillable = array('hr_mail_project', 'hr_mail_name', 'hr_mail_desc', 'hr_mail_content', 'hr_mail_person_recive',
        'hr_mail_person_send', 'hr_mail_send_cc','hr_mail_created','hr_mail_update',
        'hr_mail_type', 'hr_mail_files','hr_mail_date_send', 'hr_mail_status');

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new HrMail();
            $fieldInput = $checkData->checkField($data);
            $item = new HrMail();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->hr_mail_id,$item);
            return $item->hr_mail_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new HrMail();
            $fieldInput = $checkData->checkField($data);
            $item = HrMail::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->hr_mail_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }
    public static function getItemById($id=0){
        $result = (Define::CACHE_ON) ? Cache::get(Define::CACHE_HR_MAIL_ID . $id) : array();
        try {
            if (empty($result)) {
                $result = HrMail::where('hr_mail_id', $id)->first();
                if ($result && Define::CACHE_ON) {
                    Cache::put(Define::CACHE_HR_MAIL_ID . $id, $result, Define::CACHE_TIME_TO_LIVE_ONE_MONTH);
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
            $item = HrMail::find($id);
            if($item){
                //Remove file
                $arrFile = ($item->hr_mail_files != '') ? unserialize($item->hr_mail_files) : array();
                if(sizeof($arrFile) > 0){
                    foreach($arrFile as $k=>$v){
                        unset($arrFile[$k]);
                        FunctionLib::deleteFileUpload($v, Define::FOLDER_MAIL, true, $id);
                    }
                }
                //End Remove file
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->hr_mail_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }
    public static function removeCache($id = 0,$data){
        if($id > 0){
            Cache::forget(Define::CACHE_HR_MAIL_ID.$id);
        }
    }
    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = HrMail::where('hr_mail_id','>',0);
            if (isset($dataSearch['hr_mail_name']) && $dataSearch['hr_mail_name'] != '') {
                $query->where('hr_mail_name','LIKE', '%' . $dataSearch['hr_mail_name'] . '%');
            }
            if (isset($dataSearch['hr_mail_status']) && $dataSearch['hr_mail_status'] != -1) {
                $query->where('hr_mail_status',$dataSearch['hr_mail_status']);
            }
            if (isset($dataSearch['hr_mail_type']) && $dataSearch['hr_mail_type'] != -1) {
                $query->where('hr_mail_type',$dataSearch['hr_mail_type']);
            }



            $total = $query->count();
            $query->orderBy('hr_mail_id', 'desc');

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
}
