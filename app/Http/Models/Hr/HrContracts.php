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

class HrContracts extends BaseModel
{
    protected $table = Define::TABLE_HR_CONTRACTS;
    protected $primaryKey = 'contracts_id';
    public $timestamps = false;

    protected $fillable = array('contracts_project', 'contracts_code', 'contracts_person_id', 'contracts_type_define_id', 'contracts_type_define_name',
        'contracts_payment_define_id', 'contracts_payment_define_name', 'contracts_sign_day', 'contracts_effective_date', 'contracts_describe', 'contracts_money',
        'contracts_creater_time', 'contracts_creater_user_id', 'contracts_creater_user_name',
        'contracts_update_time', 'contracts_update_user_id', 'contracts_update_user_name');

    public static function getListContractsByPersonId($person_id)
    {
        $contracts = [];
        if ($person_id > 0) {
            $query = HrContracts::where('contracts_id', '>', 0);
            $query->where('contracts_person_id', '=', $person_id);
            $contracts = $query->orderBy('contracts_id', 'DESC')->get();
        }
        return $contracts;
    }

    public static function createItem($data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new HrContracts();
            $fieldInput = $checkData->checkField($data);
            $item = new HrContracts();
            if (is_array($fieldInput) && count($fieldInput) > 0) {
                foreach ($fieldInput as $k => $v) {
                    $item->$k = $v;
                }
            }
            $item->save();

            DB::connection()->getPdo()->commit();
            self::removeCache($item->contracts_id, $item);
            return $item->contracts_id;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public static function updateItem($id, $data)
    {
        try {
            DB::connection()->getPdo()->beginTransaction();
            $checkData = new HrContracts();
            $fieldInput = $checkData->checkField($data);
            $item = HrContracts::find($id);
            foreach ($fieldInput as $k => $v) {
                $item->$k = $v;
            }
            $item->update();
            DB::connection()->getPdo()->commit();
            self::removeCache($item->contracts_id, $item);
            return true;
        } catch (PDOException $e) {
            //var_dump($e->getMessage());
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
        }
    }

    public function checkField($dataInput)
    {
        $fields = $this->fillable;
        $dataDB = array();
        if (!empty($fields)) {
            foreach ($fields as $field) {
                if (isset($dataInput[$field])) {
                    $dataDB[$field] = $dataInput[$field];
                }
            }
        }
        return $dataDB;
    }

    public static function deleteItem($id)
    {
        if ($id <= 0) return false;
        try {
            DB::connection()->getPdo()->beginTransaction();
            $item = HrContracts::find($id);
            if ($item) {
                $item->delete();
            }
            DB::connection()->getPdo()->commit();
            self::removeCache($id, $item);
            return true;
        } catch (PDOException $e) {
            DB::connection()->getPdo()->rollBack();
            throw new PDOException();
            return false;
        }
    }

    public static function removeCache($id = 0, $data)
    {
        if ($id > 0) {
            //Cache::forget(Define::CACHE_CONTRACTS_PERSON_ID.$data->contracts_person_id);
        }
    }

    public static function searchByCondition($dataSearch = array(), $limit = 0, $offset = 0, &$total)
    {
        try {
            $query = HrContracts::where('contracts_id', '>', 0);
            if (isset($dataSearch['menu_name']) && $dataSearch['menu_name'] != '') {
                $query->where('menu_name', 'LIKE', '%' . $dataSearch['menu_name'] . '%');
            }
            $total = $query->count();
            $query->orderBy('contracts_id', 'desc');

            //get field can lay du lieu
            $fields = (isset($dataSearch['field_get']) && trim($dataSearch['field_get']) != '') ? explode(',', trim($dataSearch['field_get'])) : array();
            if (!empty($fields)) {
                $result = $query->take($limit)->skip($offset)->get($fields);
            } else {
                $result = $query->take($limit)->skip($offset)->get();
            }
            return $result;

        } catch (PDOException $e) {
            throw new PDOException();
        }
    }
}
