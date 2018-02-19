<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
@extends('admin.AdminLayouts.index')
@section('content')
    <div class="main-content-inner">
        <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{URL::route('admin.dashboard')}}">Home</a>
                </li>
                <li><a href="{{URL::route('hr.personnelView')}}"> Danh sách nhân sự</a></li>
                <li class="active">Lý lịch 2C</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    {{ csrf_field() }}
                    @if(isset($infoPerson))
                        <span class="span">Họ và tên:<b> {{$infoPerson->person_name}}</b></span>
                        <span class="span">&nbsp;&nbsp;&nbsp;Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b></span>
                        <span class="span">&nbsp;&nbsp;&nbsp;Số cán bộ:<b> {{$infoPerson->person_code}}</b></span>
                    @endif
                    <!---ĐÀO TẠO BỒI DƯỠNG VỀ CHUYÊN MÔN NGHIỆP VỤ, LÝ LUÂN CHÍNH TRỊ, NGOẠI NGỮ-->
                    <div class="marginTop20">
                        <div class="block_title">ĐÀO TẠO BỒI DƯỠNG VỀ CHUYÊN MÔN NGHIỆP VỤ, LÝ LUÂN CHÍNH TRỊ, NGOẠI NGỮ</div>
                        <div id="div_list_khenthuong">
                            <div class="span clearfix"> <b>Các khóa đào tạo có chuyên nghành trong hệ thống</b></div>
                            <table class="table table-bordered table-hover">
                                <thead class="thin-border-bottom">
                                <tr class="">
                                    <th width="5%" class="text-center">STT</th>
                                    <th width="20%">Tên trường địa điểm</th>
                                    <th width="10%" class="text-center">Nghành học, lớp học</th>
                                    <th width="20%" class="text-center">Thời gian học</th>
                                    <th width="10%" class="text-center">Hình thức học</th>
                                    <th width="30%" class="text-center">Văn bằng, chứng chỉ, trình độ</th>
                                    <th width="5%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                @if(sizeof($khenthuong) > 0)
                                    <tbody>
                                    @foreach ($khenthuong as $key => $item)
                                        <tr>
                                            <td class="text-center middle">{{ $key+1 }}</td>
                                            <td>@if(isset($arrTypeKhenthuong[$item['bonus_define_id']])){{ $arrTypeKhenthuong[$item['bonus_define_id']] }}@endif</td>
                                            <td class="text-center middle"> {{ $item['bonus_year'] }}</td>
                                            <td class="text-center middle">{{$item['bonus_decision']}}</td>
                                            <td class="text-center middle">{{ number_format($item['bonus_number'])}}</td>
                                            <td class="text-center middle">{{$item['bonus_note']}}</td>
                                            <td class="text-center middle">
                                                @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personContracts_delete == 1)
                                                    <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item['bonus_person_id'])}}','{{FunctionLib::inputId($item['bonus_id'])}}','bonusPerson/deleteBonus','div_list_khenthuong',{{\App\Library\AdminFunction\Define::BONUS_KHEN_THUONG}})"><i class="fa fa-trash fa-2x"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                @else
                                    <tr>
                                        <td colspan="7"> Chưa có dữ liệu</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <a class="btn btn-success" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','bonusPerson/editBonus',{{\App\Library\AdminFunction\Define::BONUS_KHEN_THUONG}})"><i class="fa fa-reply"></i> Thêm mới khen thưởng</a>

                        <div id="div_list_khenthuong">
                            <div class="span clearfix"> <b>Các khóa đào tạo có chuyên nghành khác</b></div>
                            <table class="table table-bordered table-hover">
                                <thead class="thin-border-bottom">
                                <tr class="">
                                    <th width="5%" class="text-center">STT</th>
                                    <th width="20%">Tên trường địa điểm</th>
                                    <th width="10%" class="text-center">Nghành học, lớp học</th>
                                    <th width="20%" class="text-center">Thời gian học</th>
                                    <th width="10%" class="text-center">Tổ chức cấp</th>
                                    <th width="30%" class="text-center">Văn bằng, chứng chỉ, trình độ</th>
                                    <th width="5%" class="text-center">Thao tác</th>
                                </tr>
                                </thead>
                                @if(sizeof($khenthuong) > 0)
                                    <tbody>
                                    @foreach ($khenthuong as $key => $item)
                                        <tr>
                                            <td class="text-center middle">{{ $key+1 }}</td>
                                            <td>@if(isset($arrTypeKhenthuong[$item['bonus_define_id']])){{ $arrTypeKhenthuong[$item['bonus_define_id']] }}@endif</td>
                                            <td class="text-center middle"> {{ $item['bonus_year'] }}</td>
                                            <td class="text-center middle">{{$item['bonus_decision']}}</td>
                                            <td class="text-center middle">{{ number_format($item['bonus_number'])}}</td>
                                            <td class="text-center middle">{{$item['bonus_note']}}</td>
                                            <td class="text-center middle">
                                                @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personContracts_delete == 1)
                                                    <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item['bonus_person_id'])}}','{{FunctionLib::inputId($item['bonus_id'])}}','bonusPerson/deleteBonus','div_list_khenthuong',{{\App\Library\AdminFunction\Define::BONUS_KHEN_THUONG}})"><i class="fa fa-trash fa-2x"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                @else
                                    <tr>
                                        <td colspan="7"> Chưa có dữ liệu</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <a class="btn btn-success" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','bonusPerson/editBonus',{{\App\Library\AdminFunction\Define::BONUS_KHEN_THUONG}})"><i class="fa fa-reply"></i> Thêm mới khen thưởng</a>
                    </div>

                    {{----TÓM TẮT QUÁ TRÌNH CÔNG TÁC--}}
                    <div class="marginTop20">
                        <div class="block_title">TÓM TẮT QUÁ TRÌNH CÔNG TÁC</div>
                        <div id="div_list_danhhieu">
                            <div class="span clearfix"> @if(count($danhhieu) >0) Có tổng số <b>{{count($danhhieu)}}</b> danh hiệu @endif </div>
                            <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="">
                                <th width="5%" class="text-center">STT</th>
                                <th width="10%">Thời gian</th>
                                <th width="75%">Chức danh, chức vụ, công tác</th>
                                <th width="10%" class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            @if(sizeof($danhhieu) > 0)
                                <tbody>
                                @foreach ($danhhieu as $key2 => $item2)
                                    <tr>
                                        <td class="text-center middle">{{ $key2+1 }}</td>
                                        <td> {{ $item2['bonus_year'] }}</td>
                                        <td>{{$item2['bonus_decision']}}</td>
                                        <td class="text-center middle">
                                            @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personContracts_delete == 1)
                                                <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item2['bonus_person_id'])}}','{{FunctionLib::inputId($item2['bonus_id'])}}','bonusPerson/deleteBonus','div_list_danhhieu',{{\App\Library\AdminFunction\Define::BONUS_DANH_HIEU}})"><i class="fa fa-trash fa-2x"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <td colspan="7"> Chưa có dữ liệu</td>
                                </tr>
                            @endif
                        </table>
                        </div>
                        <a class="btn btn-success" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','bonusPerson/editBonus',{{\App\Library\AdminFunction\Define::BONUS_DANH_HIEU}})"><i class="fa fa-reply"></i> Thêm mới danh hiệu</a>
                    </div>

                    {{----TÓM TẮT QUÁ TRÌNH HOẠT ĐỘNG ĐẢNG, CHÍNH QUYỀN, ĐOÀN THỂ--}}
                    <div class="marginTop20">
                        <div class="block_title">TÓM TẮT QUÁ TRÌNH HOẠT ĐỘNG ĐẢNG, CHÍNH QUYỀN, ĐOÀN THỂ</div>
                        <div id="div_list_kyluat">
                            <div class="span clearfix"> @if(count($kyluat) >0) Có tổng số <b>{{count($kyluat)}}</b> kỷ luật @endif </div>
                            <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="">
                                <th width="5%" class="text-center">STT</th>
                                <th width="30%">Chi bộ</th>
                                <th width="10%" class="text-center">Thời gian</th>
                                <th width="20%" class="text-center">Chức vụ</th>
                                <th width="30%" class="text-center">Cấp ủy kiêm</th>
                                <th width="5%" class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            @if(sizeof($kyluat) > 0)
                                <tbody>
                                @foreach ($kyluat as $key3 => $item3)
                                    <tr>
                                        <td class="text-center middle">{{ $key3+1 }}</td>
                                        <td>@if(isset($arrTypeKyluat[$item3['bonus_define_id']])){{ $arrTypeKyluat[$item3['bonus_define_id']] }}@endif</td>
                                        <td class="text-center middle">{{ $item3['bonus_year'] }}</td>
                                        <td class="text-center middle">{{$item3['bonus_decision']}}</td>
                                        <td class="text-center middle">{{$item3['bonus_note']}}</td>
                                        <td class="text-center middle">
                                            @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personContracts_delete == 1)
                                                <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item3['bonus_person_id'])}}','{{FunctionLib::inputId($item3['bonus_id'])}}','bonusPerson/deleteBonus','div_list_kyluat',{{\App\Library\AdminFunction\Define::BONUS_KY_LUAT}})"><i class="fa fa-trash fa-2x"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <td colspan="7"> Chưa có dữ liệu</td>
                                </tr>
                            @endif
                        </table>
                        </div>
                        <a class="btn btn-success" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','bonusPerson/editBonus',{{\App\Library\AdminFunction\Define::BONUS_KY_LUAT}})"><i class="fa fa-reply"></i> Thêm mới kỷ luật</a>
                    </div>

                    {{----ĐẶC ĐIỂM LỊCH SỬ BẢN THÂN--}}
                    <div class="marginTop20">
                        <div class="block_title">ĐẶC ĐIỂM LỊCH SỬ BẢN THÂN</div>
                        <div id="div_lich_su_ban_than">
                            <div class="form-group">
                                <label for="device_name" class="control-label"><i>Khai rõ: Bị bắt, bị tù (từ ngày, tháng, năm nào đến ngày, tháng, năm nào, ở đâu), đã khai báo cho ai, những vấn đề gì</i></label>
                                <textarea class="form-control input-sm" id="device_name" name="device_name" rows="5">Chua co gi</textarea>
                            </div>
                            <div class="form-group">
                                <label for="device_name" class="control-label"><i>Bản thân có làm việc trong chế độ cũ (Cơ quan, đơn vị nào, địa điểm, chức danh, chức vụ, thời gian làm việc)</i></label>
                                <textarea class="form-control input-sm" id="device_name" name="device_name" rows="5">Chua co gi</textarea>
                            </div>
                        </div>
                    </div>

                    {{----QUAN HỆ VỚI NƯỚC NGOÀI--}}
                    <div class="marginTop20">
                        <div class="block_title">QUAN HỆ VỚI NƯỚC NGOÀI</div>
                        <div id="div_quan_he_nuoc_ngoai">
                            <div class="form-group">
                                <label for="device_name" class="control-label"><i>Tham gia hoặc có quan hệ với các tổ chức chính trị, kinh tế, xã hội nào ở nước ngoài (làm gì, tổ chức nào, đặt trụ sở ở đâu)</i></label>
                                <textarea class="form-control input-sm" id="device_name" name="device_name" rows="5">Chua co gi</textarea>
                            </div>
                            <div class="form-group">
                                <label for="device_name" class="control-label"><i>Có thân nhân (bố mẹ, vợ chồng, con, anh chị em ruột) ở nước ngoài (làm gì, địa chỉ)...</i></label>
                                <textarea class="form-control input-sm" id="device_name" name="device_name" rows="5">Chua co gi</textarea>
                            </div>
                        </div>
                    </div>

                    {{----QUAN HỆ GIA ĐÌNH--}}
                    <div class="marginTop20">
                        <div class="block_title">QUAN HỆ GIA ĐÌNH</div>
                        <div id="div_quan_he_gia_dinh">
                            <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="">
                                <th width="10%">Quan hệ</th>
                                <th width="20%">Họ và tên</th>
                                <th width="8%" class="text-center">Năm sinh</th>
                                <th width="52%">Quê quán, nghề nghiệp, chức danh, chức vụ, đơn vị công tác, học tập, nơi ở (trong, ngoài nước); Thành viên các tổ chức chính trị xã hội</th>
                                <th width="10%" class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            @if(sizeof($quanhegiadinh) > 0)
                                <tbody>
                                @foreach ($quanhegiadinh as $k_qhgd => $item_qhgd)
                                    <tr>
                                        <td>@if(isset($arrQuanHeGiaDinh[$item_qhgd['relationship_define_id']])){{ $arrQuanHeGiaDinh[$item_qhgd['relationship_define_id']] }}@endif</td>
                                        <td>{{ $item_qhgd['relationship_human_name'] }}</td>
                                        <td class="text-center middle">{{$item_qhgd['relationship_year_birth']}}</td>
                                        <td>{{$item_qhgd['relationship_describe']}}</td>
                                        <td class="text-center middle">
                                            @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personCurriculumVitaeCreate == 1)
                                                <a href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($item_qhgd['relationship_person_id'])}}','{{FunctionLib::inputId($item_qhgd['relationship_id'])}}','curriculumVitaePerson/editFamily',0)"title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                            @endif
                                            @if($is_root== 1 || $personCurriculumVitaeFull== 1 || $personContracts_delete == 1)
                                                <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item_qhgd['relationship_person_id'])}}','{{FunctionLib::inputId($item_qhgd['relationship_id'])}}','curriculumVitaePerson/deleteFamily','div_quan_he_gia_dinh',0)"><i class="fa fa-trash fa-2x"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <td colspan="7"> Chưa có dữ liệu</td>
                                </tr>
                            @endif
                        </table>
                        </div>
                        <a class="btn btn-success" href="#" onclick="HR.getAjaxCommonInfoPopup('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}','curriculumVitaePerson/editFamily',0)"><i class="fa fa-reply"></i> Thêm mới quan hệ gia đình</a>
                    </div>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
@stop