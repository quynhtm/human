<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\Bonus;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class BonusPersonController extends BaseAdminController
{
    //contracts
    private $personBonusView = 'personBonusView';
    private $personBonusFull = 'personBonusFull';
    private $personBonusDelete = 'personBonusDelete';
    private $personBonusCreate = 'personBonusCreate';

    private $arrStatus = array(1=>'hiển thị',2=>'Ẩn');
    private $viewPermission = array();//check quyen

    public function __construct()
    {
        parent::__construct();

    }
    public function getDataDefault()
    {
        $this->arrStatus = array(
            CGlobal::status_block => FunctionLib::controLanguage('status_choose', $this->languageSite),
            CGlobal::status_show => FunctionLib::controLanguage('status_show', $this->languageSite),
            CGlobal::status_hide => FunctionLib::controLanguage('status_hidden', $this->languageSite));
    }
    public function getPermissionPage()
    {
        return $this->viewPermission = [
            'is_root' => $this->is_root ? 1 : 0,
            //contracts
            'personBonusFull' => in_array($this->personBonusFull, $this->permission) ? 1 : 0,
            'personBonusView' => in_array($this->personBonusView, $this->permission) ? 1 : 0,
            'personBonusCreate' => in_array($this->personBonusCreate, $this->permission) ? 1 : 0,
            'personBonusDelete' => in_array($this->personBonusDelete, $this->permission) ? 1 : 0,
        ];
    }


    /************************************************************************************************************************************
     * Thông tin khen thưởng, danh hiệu, kỷ luật
     ************************************************************************************************************************************/
    public function viewBonus($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Thông tin hợp đồng lao động';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personBonusFull, $this->permission) && !in_array($this->personBonusView, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin hợp đồng
        $contracts = Bonus::getListContractsByPersonId($person_id);

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.InfoPerson.contractsView', array_merge([
            'person_id' => $person_id,
            'contracts' => $contracts,
            'total' => count($contracts),
            'infoPerson' => $infoPerson,
        ], $this->viewPermission));
    }
    public function editBonus()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personBonusFull, $this->permission) && !in_array($this->personBonusCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('person_id', '');
        $contractsId = Request::get('contracts_id', '');

        $person_id = FunctionLib::outputId($personId);
        $contracts_id = FunctionLib::outputId($contractsId);

        $data = array();
        $arrData = ['intReturn' => 0, 'msg' => ''];

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin hợp đồng
        $contracts = Bonus::find($contracts_id);
        //FunctionLib::debug($contracts);
        $optionShow = FunctionLib::getOption($this->arrStatus, isset($data['showcontent']) ? $data['showcontent'] : CGlobal::status_show);
        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.BonusPerson.contractsPopupAdd', [
            'contracts' => $contracts,
            'infoPerson' => $infoPerson,
            'optionShow' => $optionShow,
            'person_id' => $person_id,
            'contracts_id' => $contracts_id,
        ], $this->viewPermission)->render();
        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }
    public function postBonus()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personBonusFull, $this->permission) && !in_array($this->personBonusCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $data = $_POST;
        $person_id = Request::get('person_id', '');
        $contracts_id = Request::get('contracts_id', '');
        //$person_id = FunctionLib::outputId($personId);
        //$contracts_id = FunctionLib::outputId($contractsId);
        //FunctionLib::debug($data);
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if($data['contracts_sign_day'] == '' || $data['contracts_effective_date'] == ''){
            $arrData = ['intReturn' => 0, 'msg' => 'Dữ liệu nhập không đủ'];
        }else{
            if($person_id > 0){
                $dataBonus = array('contracts_code'=>$data['contracts_code'],
                    'contracts_type_define_id'=>$data['contracts_type_define_id'],
                    'contracts_payment_define_id'=>$data['contracts_payment_define_id'],
                    'contracts_money'=>$data['contracts_money'],
                    'contracts_describe'=>$data['contracts_describe'],
                    'contracts_sign_day'=>($data['contracts_sign_day'] != '')? strtotime($data['contracts_sign_day']):'',
                    'contracts_effective_date'=>($data['contracts_effective_date'] != '')? strtotime($data['contracts_effective_date']):'',
                    'contracts_person_id'=>$person_id,
                );
                if($contracts_id > 0){
                    $dataBonus['contracts_update_user_id'] = $this->user_id;
                    $dataBonus['contracts_update_user_name'] = $this->user_name;
                    $dataBonus['contracts_update_time'] = time();
                    Bonus::updateItem($contracts_id,$dataBonus);
                }else{
                    $dataBonus['contracts_creater_user_id'] = $this->user_id;
                    $dataBonus['contracts_creater_user_name'] = $this->user_name;
                    $dataBonus['contracts_creater_time'] = time();
                    Bonus::createItem($dataBonus);
                }
                $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
                //thông tin hợp đồng
                $contracts = Bonus::getListContractsByPersonId($person_id);
                $this->getDataDefault();
                $this->viewPermission = $this->getPermissionPage();
                $html = view('hr.BonusPerson.contractsList', array_merge([
                    'person_id' => $person_id,
                    'contracts' => $contracts,
                    'total' => count($contracts)
                ], $this->viewPermission))->render();
                $arrData['html'] = $html;
            }else{
                $arrData = ['intReturn' => 0, 'msg' => 'Lỗi cập nhật'.$person_id];
            }
        }
        return response()->json($arrData);
    }
    public function deleteBonus()
    {
        //Check phan quyen.
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if (!$this->is_root && !in_array($this->personBonusFull, $this->permission) && !in_array($this->personBonusDelete, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('person_id', '');
        $contractsId = Request::get('contracts_id', '');
        $person_id = FunctionLib::outputId($personId);
        $contracts_id = FunctionLib::outputId($contractsId);
        if ($contracts_id > 0 && Bonus::deleteItem($contracts_id)) {
            $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];
            //thông tin hợp đồng
            $contracts = Bonus::getListContractsByPersonId($person_id);
            $this->getDataDefault();
            $this->viewPermission = $this->getPermissionPage();
            $html = view('hr.BonusPerson.contractsList', array_merge([
                'person_id' => $person_id,
                'contracts' => $contracts,
                'total' => count($contracts)
            ], $this->viewPermission))->render();
            $arrData['html'] = $html;
        }
        return Response::json($arrData);
    }
}
