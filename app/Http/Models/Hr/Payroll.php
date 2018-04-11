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

class Payroll extends BaseModel
{
    protected $table = Define::TABLE_HR_PAYROLL;
    protected $primaryKey = 'payroll_id';
    public $timestamps = false;

    protected $fillable = array('payroll_project', 'payroll_person_id', 'payroll_month', 'payroll_year',
        'he_so_luong',                  //1
        'phu_cap_chuc_vu',              //2
        'phu_cap_tham_nien_vuot',       //3
        'phu_cap_tham_nien_vuot_heso',  //4=1*3
        'phu_cap_trach_nhiem',          //5
        'phu_cap_tham_nien',            //6
        'phu_cap_tham_nien_heso',       //7=(1+2+4)*6
        'phu_cap_nghanh',               //8
        'phu_cap_nghanh_heso',          //9=1*8
        'tong_he_so',                   //10=1+2+4+5+7+9
        'luong_co_so',                  //11
        'tong_tien',                    //12=10*11
        'tong_tien_luong',              //13=12
        'tong_tien_baohiem',            //14= (1+2+4+5+7)*11*0.105 (10.5% BHXH + BHYT + BHTN)
        'tong_luong_thuc_nhan'          //15=13-14
    );
    public static function getPayrollByPersonId($payroll_person_id)
    {
        $data = Payroll::where('payroll_person_id', $payroll_person_id)->first();
        return $data;
    }

    public static function createItem($data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Payroll();
            $fieldInput = $checkData->checkField($data);
            $item = new Payroll();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->payroll_id,$item);
            return $item->payroll_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id,$data){
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new Payroll();
            $fieldInput = $checkData->checkField($data);
            $item = Payroll::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->payroll_id,$item);
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
            $item = Payroll::find($id);
            if($item){
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($item->payroll_id,$item);
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
            $query = Payroll::where('payroll_id','>',0);
            if (isset($dataSearch['menu_name']) && $dataSearch['menu_name'] != '') {
                $query->where('menu_name','LIKE', '%' . $dataSearch['menu_name'] . '%');
            }
            $total = $query->count();
            $query->orderBy('payroll_id', 'desc');

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
