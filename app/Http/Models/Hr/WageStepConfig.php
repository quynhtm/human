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

class WageStepConfig extends BaseModel
{
    protected $table = Define::TABLE_HR_WAGE_STEP_CONFIG;
    protected $primaryKey = 'wage_step_config_id';
    public $timestamps = false;

    protected $fillable = array('wage_step_config_project', 'wage_step_config_parent_id', 'wage_step_config_name', 'wage_step_config_value', 'wage_step_config_type','wage_step_config_order','wage_step_config_status');
    public static function getWageStepConfigByPersonId($quit_job_person_id,$quit_job_type)
    {
        $data = WageStepConfig::where('quit_job_person_id', $quit_job_person_id)
            ->where('position_config_type', $quit_job_type)->first();
        return $data;
    }

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new WageStepConfig();
            $fieldInput = $checkData->checkField($data);
            $item = new WageStepConfig();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->position_config_id,$item);
            return $item->position_config_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new WageStepConfig();
            $fieldInput = $checkData->checkField($data);
            $item = WageStepConfig::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->position_config_id,$item);
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
            $item = WageStepConfig::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->position_config_id,$item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function removeCache($id = 0,$data){
        if($id > 0){
            //Cache::forget(Define::CACHE_CATEGORY_ID.$id);
        }
    }

    public static function searchByCondition($dataSearch = array(), $limit =0, $offset=0, &$total){
        try{
            $query = WageStepConfig::where('position_config_id','>',0);
            if (isset($dataSearch['position_config_name']) && $dataSearch['position_config_name'] != '') {
                $query->where('position_config_name','LIKE', '%' . $dataSearch['position_config_name'] . '%');
            }
            $total = $query->count();
            $query->orderBy('position_config_id', 'desc');

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
