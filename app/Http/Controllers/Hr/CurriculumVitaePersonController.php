<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\BaseAdminController;
use App\Http\Models\Hr\Department;
use App\Http\Models\Hr\HrDefine;
use App\Http\Models\Hr\Person;
use App\Http\Models\Hr\Bonus;
use App\Http\Models\Hr\Relationship;

use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\CGlobal;
use App\Library\AdminFunction\Define;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Library\AdminFunction\Pagging;

class CurriculumVitaePersonController extends BaseAdminController
{
    //contracts
    private $personCurriculumVitaeView = 'personCurriculumVitaeView';
    private $personCurriculumVitaeFull = 'personCurriculumVitaeFull';
    private $personCurriculumVitaeDelete = 'personCurriculumVitaeDelete';
    private $personCurriculumVitaeCreate = 'personCurriculumVitaeCreate';

    private $arrStatus = array(1 => 'hiển thị', 2 => 'Ẩn');
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
            'personCurriculumVitaeFull' => in_array($this->personCurriculumVitaeFull, $this->permission) ? 1 : 0,
            'personCurriculumVitaeView' => in_array($this->personCurriculumVitaeView, $this->permission) ? 1 : 0,
            'personCurriculumVitaeCreate' => in_array($this->personCurriculumVitaeCreate, $this->permission) ? 1 : 0,
            'personCurriculumVitaeDelete' => in_array($this->personCurriculumVitaeDelete, $this->permission) ? 1 : 0,
        ];
    }


    /************************************************************************************************************************************
     * Thông tin lý lịch 2C
     ************************************************************************************************************************************/
    public function viewCurriculumVitae($personId)
    {
        $person_id = FunctionLib::outputId($personId);
        CGlobal::$pageAdminTitle = 'Lý lịch 2C';
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personCurriculumVitaeFull, $this->permission) && !in_array($this->personCurriculumVitaeView, $this->permission)) {
            return Redirect::route('admin.dashboard', array('error' => Define::ERROR_PERMISSION));
        }
        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin khen thưởng
        $khenthuong = Bonus::getBonusByType($person_id, Define::BONUS_KHEN_THUONG);
        $arrTypeKhenthuong = HrDefine::getArrayByType(Define::khen_thuong);
        //thông tin danh hieu
        $danhhieu = Bonus::getBonusByType($person_id, Define::BONUS_DANH_HIEU);
        $arrTypeDanhhieu = HrDefine::getArrayByType(Define::danh_hieu);

        //thông tin kỷ luật
        $kyluat = Bonus::getBonusByType($person_id, Define::BONUS_KY_LUAT);
        $arrTypeKyluat = HrDefine::getArrayByType(Define::ky_luat);

        //quan he gia dinh
        $quanhegiadinh = Relationship::getRelationshipByPersonId($person_id);
        $arrQuanHeGiaDinh = HrDefine::getArrayByType(Define::quan_he_gia_dinh);

        $this->getDataDefault();
        $this->viewPermission = $this->getPermissionPage();
        return view('hr.CurriculumVitaePerson.CurriculumVitaeView', array_merge([
            'person_id' => $person_id,

            'khenthuong' => $khenthuong,
            'danhhieu' => $danhhieu,
            'kyluat' => $kyluat,
            'arrTypeKhenthuong' => $arrTypeKhenthuong,
            'arrTypeDanhhieu' => $arrTypeDanhhieu,
            'arrTypeKyluat' => $arrTypeKyluat,

            'infoPerson' => $infoPerson,
            'quanhegiadinh' => $quanhegiadinh,
            'arrQuanHeGiaDinh' => $arrQuanHeGiaDinh,
        ], $this->viewPermission));
    }

    /************************************************************************************************************************************
     * Quan hệ gia đình
     ************************************************************************************************************************************/
    public function editFamily()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personCurriculumVitaeFull, $this->permission) && !in_array($this->personCurriculumVitaeCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $relationshipId = Request::get('str_object_id', '');

        $person_id = FunctionLib::outputId($personId);
        $relationship_id = FunctionLib::outputId($relationshipId);

        $arrData = ['intReturn' => 0, 'msg' => ''];

        //thong tin nhan sự
        $infoPerson = Person::getPersonById($person_id);

        //thông tin chung
        $data = Relationship::find($relationship_id);
        //FunctionLib::debug($contracts);
        $template = 'quanhegiadinhPopupAdd';
        $arrQuanHeGiaDinh = HrDefine::getArrayByType(Define::quan_he_gia_dinh);

        $arrYears = FunctionLib::getListYears();
        $optionYears = FunctionLib::getOption($arrYears, isset($data['relationship_year_birth']) ? $data['relationship_year_birth'] : (int)date('Y', time()));
        $optionType = FunctionLib::getOption($arrQuanHeGiaDinh, isset($data['relationship_define_id']) ? $data['relationship_define_id'] : '');

        $this->viewPermission = $this->getPermissionPage();
        $html = view('hr.CurriculumVitaePerson.' . $template, [
            'data' => $data,
            'infoPerson' => $infoPerson,
            'optionType' => $optionType,
            'optionYears' => $optionYears,
            'person_id' => $person_id,
            'relationship_id' => $relationship_id,
        ], $this->viewPermission)->render();
        $arrData['intReturn'] = 1;
        $arrData['html'] = $html;
        return response()->json($arrData);
    }
    public function postFamily()
    {
        //Check phan quyen.
        if (!$this->is_root && !in_array($this->personCurriculumVitaeFull, $this->permission) && !in_array($this->personCurriculumVitaeCreate, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $data = $_POST;
        $person_id = Request::get('person_id', '');
        $relationship_id = Request::get('relationship_id', '');
        //FunctionLib::debug($data);
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if ($data['relationship_human_name'] == '') {
            $arrData = ['intReturn' => 0, 'msg' => 'Dữ liệu nhập không đủ'];
        } else {
            if ($person_id > 0) {
                $dataBonus = array(
                    'relationship_describe' => $data['relationship_describe'],
                    'relationship_year_birth' => $data['relationship_year_birth'],
                    'relationship_define_id' => $data['relationship_define_id'],
                    'relationship_human_name' => $data['relationship_human_name'],
                    'relationship_person_id' => $person_id,
                );
                if ($relationship_id > 0) {
                    Relationship::updateItem($relationship_id, $dataBonus);
                } else {
                    Relationship::createItem($dataBonus);
                }
                $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];

                $template = 'quanhegiadinhList';
                $quanhegiadinh = Relationship::getRelationshipByPersonId($person_id);
                $arrQuanHeGiaDinh = HrDefine::getArrayByType(Define::quan_he_gia_dinh);

                $this->getDataDefault();
                $this->viewPermission = $this->getPermissionPage();
                $html = view('hr.CurriculumVitaePerson.' . $template, array_merge([
                    'person_id' => $person_id,
                    'quanhegiadinh' => $quanhegiadinh,
                    'arrQuanHeGiaDinh' => $arrQuanHeGiaDinh,
                ], $this->viewPermission))->render();
                $arrData['html'] = $html;
            } else {
                $arrData = ['intReturn' => 0, 'msg' => 'Lỗi cập nhật' . $person_id];
            }
        }
        return response()->json($arrData);
    }
    public function deleteFamily()
    {
        //Check phan quyen.
        $arrData = ['intReturn' => 0, 'msg' => ''];
        if (!$this->is_root && !in_array($this->personCurriculumVitaeFull, $this->permission) && !in_array($this->personCurriculumVitaeDelete, $this->permission)) {
            $arrData['msg'] = 'Bạn không có quyền thao tác';
            return response()->json($arrData);
        }
        $personId = Request::get('str_person_id', '');
        $relationshipId = Request::get('str_object_id', '');

        $person_id = FunctionLib::outputId($personId);
        $relationship_id = FunctionLib::outputId($relationshipId);
        if ($relationship_id > 0 && Relationship::deleteItem($relationship_id)) {
            $arrData = ['intReturn' => 1, 'msg' => 'Cập nhật thành công'];

            $template = 'quanhegiadinhList';
            $quanhegiadinh = Relationship::getRelationshipByPersonId($person_id);
            $arrQuanHeGiaDinh = HrDefine::getArrayByType(Define::quan_he_gia_dinh);

            $this->getDataDefault();
            $this->viewPermission = $this->getPermissionPage();
            $html = view('hr.CurriculumVitaePerson.' . $template, array_merge([
                'person_id' => $person_id,
                'quanhegiadinh' => $quanhegiadinh,
                'arrQuanHeGiaDinh' => $arrQuanHeGiaDinh,
            ], $this->viewPermission))->render();
            $arrData['html'] = $html;
        }
        return Response::json($arrData);
    }
}
