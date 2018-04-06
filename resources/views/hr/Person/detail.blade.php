<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
@extends('admin.AdminLayouts.index')
@section('content')
    <div class="main-content-inner">
        <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{URL::route('admin.dashboard')}}">Trang chủ</a>
                </li>
                <li class="active">Chi tiết nhân sự 2C</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="panel panel-default">
                {{ csrf_field() }}
                <div class="panel-body-ns">
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <div class="line">
                                <div class="panel-heading clearfix">
                                    <h4 class="panel-title pull-left">Chi tiết nhân sự 2C</h4>
                                    <div class="btn-group btn-group-sm pull-right">
                                        <span>
                                            <a class="btn btn-warning btn-sm" href="{{URL::route('hr.personnelEdit',array('id' => FunctionLib::inputId($infoPerson['person_id'])))}}"><i class="fa fa-edit"></i> Sửa thông tin nhân sự</a>&nbsp;
                                        </span>
                                        <span>
                                            <a class="btn btn-danger btn-sm" href="{{URL::route('hr.personnelView')}}"><i class="fa fa-arrow-left"></i> Quay lại</a>
                                        </span>

                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="line">
                                        <table class="table table-bordered table-condensed">
                                            <tbody>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Phòng ban/ Đơn vị</span></td>
                                                <td><span class="val">@if(isset($arrDepart[$infoPerson['person_depart_id']])){{$arrDepart[$infoPerson['person_depart_id']]}}@endif</span></td>
                                                <td><span class="lbl text-nowrap">Số hiệu cán bộ, công chức</span></td>
                                                <td><span class="val">{{$infoPerson->person_code}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Họ và tên khai sinh</span></td>
                                                <td><span class="val">{{$infoPerson->person_name}}</span></td>
                                                <td><span class="lbl text-nowrap">Tên gọi khác</span></td>
                                                <td><span class="val">{{$infoPerson->person_name_other}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Email</span></td>
                                                <td><span class="val">{{$infoPerson->person_mail}}</span></td>
                                                <td><span class="lbl text-nowrap">Điện thoại</span></td>
                                                <td><span class="val">{{$infoPerson->person_phone}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Ngày sinh</span></td>
                                                <td><span class="val">{{($infoPerson['person_birth'] > 0) ? date('d/m/Y', $infoPerson['person_birth']) : ''}}</span></td>
                                                <td><span class="lbl text-nowrap">Giới tính</span></td>
                                                <td><span class="val">{{(isset($item->person_sex) && $item->person_sex == 1) ? 'Nam' : 'Nữ'}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Chức vụ (Vị trí việc làm)</span></td>
                                                <td><span class="val">@if(isset($arrChucVu[$infoPerson['person_position_define_id']])){{$arrChucVu[$infoPerson['person_position_define_id']]}}@endif</span></td>
                                                <td><span class="lbl text-nowrap">Chức danh</span></td>
                                                <td><span class="val">@if(isset($arrChucDanhNgheNghiep[$infoPerson['person_career_define_id']])){{$arrChucDanhNgheNghiep[$infoPerson['person_career_define_id']]}}@endif</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Chức danh KHCN</span></td>
                                                <td><span class="val">---------</span></td>
                                                <td><span class="lbl text-nowrap">Cấp ủy hiện tại, cấp ủy kiêm</span></td>
                                                <td><span class="val">----</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Nơi sinh</span></td>
                                                <td><span class="val">{{$infoPerson['person_address_place_of_birth']}}</span></td>
                                                <td><span class="lbl text-nowrap">Quê quán</span></td>
                                                <td><span class="val">{{$infoPerson['person_address_home_town']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Nơi ở hiện nay</span></td>
                                                <td><span class="val">{{$infoPerson['person_address_current']}}</span></td>
                                                <td><span class="lbl text-nowrap">Dân tộc</span></td>
                                                <td><span class="val">@if(isset($arrDanToc[$infoPerson['person_nation_define_id']])){{$arrDanToc[$infoPerson['person_nation_define_id']]}}@endif</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Tôn giáo</span></td>
                                                <td><span class="val">@if(isset($arrTonGiao[$infoPerson['person_respect']])){{$arrTonGiao[$infoPerson['person_respect']]}}@endif</span></td>
                                                <td><span class="lbl text-nowrap">Thành phần gia đình xuất thân</span></td>
                                                <td><span class="val">------</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Nghề nghiệp bản thân trước khi được tuyển dụng</span></td>
                                                <td><span class="val">-----</span></td>
                                                <td><span class="lbl text-nowrap">Ngày được tuyển dụng</span></td>
                                                <td><span class="val">{{($infoPerson['person_date_trial_work']  > 0) ? date('d/m/Y', $infoPerson['person_date_trial_work']) : ''}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Vào cơ quan nào, ở đâu</span></td>
                                                <td><span class="val">-----</span></td>
                                                <td><span class="lbl text-nowrap">Ngày vào cơ quan đang công tác</span></td>
                                                <td><span class="val">{{($infoPerson['person_date_trial_work']  > 0) ? date('d/m/Y', $infoPerson['person_date_trial_work']) : ''}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Ngày tham gia cách mạng</span></td>
                                                <td><span class="val">----</span></td>
                                                <td><span class="lbl text-nowrap">Ngày vào Đảng</span></td>
                                                <td><span class="val">-----</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Ngày tham gia các tổ chức chính trị, xã hội</span></td>
                                                <td><span class="val">----</span></td>
                                                <td><span class="lbl text-nowrap">Ngày nhập ngũ</span></td>
                                                <td><span class="val">----</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Ngày xuất ngũ</span></td>
                                                <td><span class="val">----</span></td>
                                                <td><span class="lbl text-nowrap">Quân hàm, Chức vụ cao nhất (năm)</span></td>
                                                <td><span class="val">-----</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Trình độ học vấn: GDPT</span></td>
                                                <td><span class="val">----</span></td>
                                                <td><span class="lbl text-nowrap">Học hàm</span></td>
                                                <td><span class="val">----</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Học vị</span></td>
                                                <td><span class="val">-----</span></td>
                                                <td><span class="lbl text-nowrap">Lý luận chính trị</span></td>
                                                <td><span class="val">------</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Trình độ ngoại ngữ</span></td>
                                                <td><span class="val">------</span></td>
                                                <td><span class="lbl text-nowrap">Danh hiệu được phong</span></td>
                                                <td><span class="val">------</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Khen thưởng</span></td>
                                                <td><span class="val">-------<span></td>
                                                <td><span class="lbl text-nowrap">Công tác chính đang làm</span></td>
                                                <td><span class="val">-------</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Sở trường công tác</span></td>
                                                <td><span class="val">------</span></td>
                                                <td><span class="lbl text-nowrap">Công việc làm lâu nhất</span></td>
                                                <td><span class="val">-------</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Kỷ luật</span></td>
                                                <td><span class="val">------</span></td>
                                                <td><span class="lbl text-nowrap">Tình trạng sức khỏe</span></td>
                                                <td><span class="val">-------</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Chiều cao</span></td>
                                                <td><span class="val">{{$infoPerson->person_height}}</span></td>
                                                <td><span class="lbl text-nowrap">Cân nặng</span></td>
                                                <td><span class="val">{{$infoPerson->person_weight}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Nhóm máu</span></td>
                                                <td><span class="val">@if(isset($arrNhomMau[$infoPerson['person_blood_group_define_id']])){{$arrNhomMau[$infoPerson['person_blood_group_define_id']]}}@endif</span></td>
                                                <td><span class="lbl text-nowrap">Số chứng minh thư</span></td>
                                                <td><span class="val">{{$infoPerson['person_chung_minh_thu']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Ngày cấp</span></td>
                                                <td><span class="val">{{($infoPerson['person_date_range_cmt']  > 0) ? date('d/m/Y', $infoPerson['person_date_range_cmt']) : ''}}</span></td>
                                                <td><span class="lbl text-nowrap">Nơi cấp</span></td>
                                                <td><span class="val">{{$infoPerson['person_issued_cmt']}}</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Thương binh hạng</span></td>
                                                <td><span class="val">----</span></td>
                                                <td><span class="lbl text-nowrap">Gia đình chính sách</span></td>
                                                <td><span class="val">----</span></td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Hộ chiếu phổ thông</span></td>
                                                <td>
                                                    <span class="val">---</span><span class="lbl"> - Cấp từ ngày: </span>
                                                    <span class="val">----</span> <span class="lbl"> - Đến ngày:</span><span class="val ">----</span>
                                                </td>
                                                <td><span class="lbl text-nowrap">Hộ chiếu công vụ</span></td>
                                                <td>-----</td>
                                            </tr>
                                            <tr>
                                                <td><span class="lbl text-nowrap">Mã số thuế cá nhân</span></td>
                                                <td><span class="val">-----</span></td>
                                                <td><span class="lbl text-nowrap">Tài khoản ngân hàng</span></td>
                                                <td><span class="val">----</span></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12  table-responsive">
                                            <div class="tit mgt-20">ĐÀO TẠO BỒI DƯỠNG VỀ CHUYÊN MÔN NGHIỆP VỤ, LÝ LUÂN CHÍNH TRỊ, NGOẠI NGỮ</div>
                                            <p><strong class="text-uppercase">Các khóa đào tạo có chuyên ngành trong hệ thống</strong></p>
                                            <table class="table table-bordered" id="tblDiploma">
                                                <tbody>
                                                <tr>
                                                    <th>Tên trường</th>
                                                    <th>Ngành học hoặc tên lớp học</th>
                                                    <th>Thời gian học</th>
                                                    <th>Hình thức học</th>
                                                    <th>Văng bằng, chứng chỉ, trình độ gì</th>
                                                </tr>
                                                @if(sizeof($arrCurriculumVitaeMain) > 0)
                                                    @foreach($arrCurriculumVitaeMain as $item)
                                                    <tr>
                                                        <td>{{$item->curriculum_address_train}}</td>
                                                        <td>@if(isset($arrChuyenNghanhDaoTao[$item['curriculum_training_id']])){{$arrChuyenNghanhDaoTao[$item['curriculum_training_id']]}}@endif, Lớp học: {{$item->curriculum_classic}}</td>
                                                        <td>{{$item['curriculum_month_in']}}/{{$item['curriculum_year_in']}} -{{$item['curriculum_month_out']}}/{{$item['curriculum_year_out']}}</td>
                                                        <td>@if(isset($arrHinhThucHoc[$item['curriculum_formalities_id']])){{$arrHinhThucHoc[$item['curriculum_formalities_id']]}}@endif</td>
                                                        <td>
                                                            @if(isset($arrVanBangChungChi[$item['curriculum_certificate_id']])){{$arrVanBangChungChi[$item['curriculum_certificate_id']]}}@endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="marginTop10 row">
                                        <div class="col-md-12">
                                            <p><strong class="text-uppercase">Các khóa đào tạo có chuyên ngành khác</strong></p>
                                            <table class="table table-bordered" id="tblDiploma">
                                                <tbody>
                                                    <tr>
                                                        <th>Tên trường, địa điểm</th>
                                                        <th>Ngành học hoặc tên lớp học</th>
                                                        <th>Thời gian học</th>
                                                        <th>Văng bằng, chứng chỉ, trình độ gì</th>
                                                        <th>Tổ chức cấp</th>
                                                    </tr>
                                                    @if(sizeof($arrCurriculumVitaeOther) > 0)
                                                        @foreach($arrCurriculumVitaeOther as $item)
                                                            <tr>
                                                                <td>{{$item->curriculum_address_train}}</td>
                                                                <td>{{$item->curriculum_training_name}}</td>
                                                                <td>{{$item['curriculum_month_in']}}/{{$item['curriculum_year_in']}} -{{$item['curriculum_month_out']}}/{{$item['curriculum_year_out']}}</td>
                                                                <td>{{$item->curriculum_certificate_name}}</td>
                                                                <td>{{$item->curriculum_formalities_name}}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12  table-responsive">
                                            <div class="tit mgt-20">TÓM TẮT QUÁ TRÌNH CÔNG TÁC</div>
                                            <table class="table table-bordered" id="tblWork">
                                                <tbody>
                                                    <tr>
                                                        <th>Từ tháng năm đến tháng năm</th>
                                                        <th>Chức danh, Chức vụ, Đơn vị công tác</th>
                                                    </tr>
                                                    @if(sizeof($arrQuaTrinhCongTac) > 0)
                                                        @foreach($arrQuaTrinhCongTac as $item)
                                                            <tr>
                                                                <td>{{$item['curriculum_month_in']}}/{{$item['curriculum_year_in']}} -{{$item['curriculum_month_out']}}/{{$item['curriculum_year_out']}}</td>
                                                                <td>{{$item->curriculum_name}}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12">
                                            <div class="tit">TÓM TẮT QUÁ TRÌNH HOẠT ĐỘNG ĐẢNG, CHÍNH QUYỀN, ĐOÀN THỂ</div>
                                            <table class="table table-bordered" id="tblPoliticActivity">
                                                <tbody>
                                                <tr>
                                                    <th>Chi bộ</th>
                                                    <th>Thời gian</th>
                                                    <th>Chức vụ</th>
                                                    <th>Cấp ủy kiêm</th>
                                                </tr>
                                                @if(sizeof($arrHoatDongDang) > 0)
                                                    @foreach($arrHoatDongDang as $item)
                                                        <td>{{$item->curriculum_chibo}}</td>
                                                        <td>{{$item['curriculum_month_in']}}/{{$item['curriculum_year_in']}} -{{$item['curriculum_month_out']}}/{{$item['curriculum_year_out']}}</td>
                                                        <td>@if(isset($arrChucVuDang[$item['curriculum_chucvu_id']])){{$arrChucVuDang[$item['curriculum_chucvu_id']]}}@endif</td>
                                                        <td>{{$item['curriculum_cap_uykiem']}}</td>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="marginTop20">
                                        <div class="block_title">KHEN THƯỞNG</div>
                                        <div id="div_list_khenthuong">
                                            <table class="table table-bordered" id="tblWork">
                                                <tbody>
                                                    <tr>
                                                        <th width="20%">Thành tích</th>
                                                        <th width="15%">Năm đạt</th>
                                                        <th>Quyết định đính kèm</th>
                                                        <th>Thưởng</th>
                                                        <th>Ghi chú</th>
                                                    </tr>
                                                    @if(sizeof($khenthuong) > 0)
                                                        @foreach ($khenthuong as $key => $item)
                                                        <tr>
                                                            <td>@if(isset($arrTypeKhenthuong[$item['bonus_define_id']])){{ $arrTypeKhenthuong[$item['bonus_define_id']] }}@endif</td>
                                                            <td>{{ $item['bonus_year'] }}</td>
                                                            <td>{{$item['bonus_decision']}}</td>
                                                            <td>{{ number_format($item['bonus_number'])}}</td>
                                                            <td>{{$item['bonus_note']}}</td>
                                                        </tr>
                                                        @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="5"> Chưa có dữ liệu</td>
                                                    </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{----danh hiệu--}}
                                    <div class="marginTop20">
                                        <div class="block_title">DANH HIỆU</div>
                                        <div id="div_list_danhhieu">
                                            <table class="table table-bordered" id="tblWork">
                                                <tbody>
                                                <tr>
                                                    <th width="20%">Danh hiệu</th>
                                                    <th width="15%">Năm đạt</th>
                                                    <th>Quyết định đính kèm</th>
                                                    <th>Ghi chú</th>
                                                </tr>
                                                @if(sizeof($danhhieu) > 0)
                                                    @foreach ($danhhieu as $key2 => $item2)
                                                        <tr>
                                                            <td>@if(isset($arrTypeDanhhieu[$item2['bonus_define_id']])){{ $arrTypeDanhhieu[$item2['bonus_define_id']] }}@endif</td>
                                                            <td>{{$item2['bonus_year'] }}</td>
                                                            <td>{{$item2['bonus_decision']}}</td>
                                                            <td>{{$item2['bonus_note']}}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4"> Chưa có dữ liệu</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{----Kỷ luật--}}
                                    <div class="marginTop20">
                                        <div class="block_title">KỶ LUẬT</div>
                                        <div id="div_list_kyluat">

                                            <table class="table table-bordered" id="tblWork">
                                                <tbody>
                                                <tr>
                                                    <th width="20%">Hình thức</th>
                                                    <th width="15%">Năm bị kỷ luật</th>
                                                    <th>Quyết định đính kèm</th>
                                                    <th>Ghi chú</th>
                                                </tr>
                                                @if(sizeof($kyluat) > 0)
                                                    @foreach ($kyluat as $key3 => $item3)
                                                        <tr>
                                                            <td>@if(isset($arrTypeKyluat[$item3['bonus_define_id']])){{ $arrTypeKyluat[$item3['bonus_define_id']] }}@endif</td>
                                                            <td>{{$item3['bonus_year'] }}</td>
                                                            <td>{{$item3['bonus_decision']}}</td>
                                                            <td>{{$item3['bonus_note']}}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4"> Chưa có dữ liệu</td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12">
                                            <div class="tit mgt-20">TÓM TẮT QUÁ TRÌNH HOẠT ĐỘNG ĐẢNG, CHÍNH QUYỀN, ĐOÀN THỂ</div>
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th>Chi bộ</th>
                                                        <th>Thời gian</th>
                                                        <th>Chức vụ</th>
                                                        <th>Cấp ủy kiêm</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12">
                                            <div class="tit mgt-20">ĐẶC ĐIỂM LỊCH SỬ BẢN THÂN</div>
                                            <div class="form-group">
                                                <label>Khai rõ: Bị bắt, bị tù (từ ngày, tháng, năm nào đến ngày, tháng, năm nào, ở đâu), đã khai báo cho ai, những vấn đề gì</label>
                                                <div class="mgt-15 lh22">

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Bản thân có làm việc trong chế độ cũ (Cơ quan, đơn vị nào, địa điểm, chức danh, chức vụ, thời gian làm việc)</label>
                                                <div class="mgt-15 lh22">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12">
                                            <div class="tit mgt-20">QUAN HỆ VỚI NƯỚC NGOÀI</div>
                                            <div class="form-group">
                                                <label>Tham gia hoặc có quan hệ với các tổ chức chính trị, kinh tế, xã hội nào ở nước ngoài (làm gì, tổ chức nào, đặt trụ sở ở đâu)</label>
                                                <div class="mgt-15 lh22">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Có thân nhân (bố mẹ, vợ chồng, con, anh chị em ruột) ở nước ngoài (làm gì, địa chỉ)...</label>
                                                <div class="mgt-15 lh22">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12">
                                            <div class="tit">ĐI NƯỚC NGOÀI</div>
                                            <div class="mgt-15 lh22">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12  table-responsive">
                                            <div class="tit mgt-20">QUAN HỆ GIA ĐÌNH</div>
                                            <table class="table table-bordered" id="tblWork">
                                                <tbody><tr>
                                                    <th class="text-nowrap">Quan hệ</th>
                                                    <th class="text-nowrap">Họ và tên</th>
                                                    <th class="text-nowrap">Năm sinh</th>
                                                    <th>Quê quán, nghề nghiệp, chức danh, chức vụ, đơn vị công tác, học tập, nơi ở; Thành viên các tổ chức chính trị xã hội</th>
                                                </tr>
                                                <tr>
                                                    <td>Bố đẻ</td>
                                                    <td>Hoàng Văn Nam</td>
                                                    <td>1954</td>
                                                    <td>Quảng Ninh</td>
                                                </tr>
                                                <tr>
                                                    <td>Mẹ đẻ</td>
                                                    <td>Nguyễn Thị Hiên</td>
                                                    <td>1964</td>
                                                    <td>Quảng Ninh</td>
                                                </tr>
                                                <tr>
                                                    <td>Anh</td>
                                                    <td>Hoàng Phúc Thái Linh</td>
                                                    <td>1992</td>
                                                    <td>Quảng Ninh</td>
                                                </tr>
                                                <tr>
                                                    <td>Bố đẻ</td>
                                                    <td>nguyên văn A</td>
                                                    <td>2016</td>
                                                    <td>đâsdas</td>
                                                </tr>
                                                </tbody></table>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12  table-responsive">
                                            <div class="tit mgt-20">LỊCH SỬ LƯƠNG</div>
                                            <table class="table table-bordered" id="tblDiploma">
                                                <tbody><tr>
                                                    <td><strong>Tháng năm</strong></td>
                                                    <td>01/02/2017</td>
                                                    <td>01/02/2015</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Ngạch/ Bậc</strong></td>
                                                    <td>01.003 - Bậc 2 - Chuyên viên</td>
                                                    <td>01.003 - Bậc 1 - Chuyên viên</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Hệ số lương</strong></td>
                                                    <td>2.67</td>
                                                    <td>2.34</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Phụ cấp</strong></td>
                                                    <td>
                                                    </td>
                                                    <td>
                                                        <div class="mgb-5">
                                                            <span>- Phụ cấp chức vụ - 0.4 - Ngày hưởng: 01/03/2015</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Lương thực nhận</strong></td>
                                                    <td>100 (%)</td>
                                                    <td>100 (%)</td>
                                                </tr>
                                                </tbody></table>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12">
                                            <div class="tit">KINH TẾ BẢN THÂN</div>
                                            <div class="mgt-15 lh22">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="marginTop20 row">
                                        <div class="col-md-12  table-responsive">
                                            <div class="tit mgt-20">DANH SÁCH HỢP ĐỒNG LAO ĐỘNG ĐÃ KÝ</div>
                                            <table class="table table-bordered" id="tblContact">
                                                <tbody><tr>
                                                    <th>STT</th>
                                                    <th>Loại hợp đồng</th>
                                                    <th>Chế độ thanh toán (Trả lương)</th>
                                                    <th>Mã hợp đồng</th>
                                                    <th>Mức lương</th>
                                                    <th>Ngày ký</th>
                                                    <th>Ngày hiệu lực</th>
                                                    <th>Thỏa thuận khác</th>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>12 tháng</td>
                                                    <td>Công chức</td>
                                                    <td>HD01</td>
                                                    <td>5,000,000</td>
                                                    <td>01/03/2016</td>
                                                    <td>02/03/2016</td>
                                                    <td>Thỏa thuận khác 2</td>
                                                </tr>
                                                </tbody></table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop