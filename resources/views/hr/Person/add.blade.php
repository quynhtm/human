<?php use App\Library\AdminFunction\CGlobal; ?>
<?php use App\Library\AdminFunction\Define; ?>
<?php use App\Library\AdminFunction\FunctionLib; ?>
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
                <li class="active">@if($id == 0) Thêm mới nhân sự @else Sửa thông tin nhân sự@endif</li>
            </ul><!-- /.breadcrumb -->
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    <form method="POST" action="" role="form">
                        @if(isset($error))
                            <div class="alert alert-danger" role="alert">
                                @foreach($error as $itmError)
                                    <p>{!! $itmError !!}</p>
                                @endforeach
                            </div>
                    @endif
                    <!--Block 1--->
                        <div class="form-group">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Họ và tên khai sinh<span class="red"> (*) </span></label>
                                    <input type="text" placeholder="Họ và tên khai sinh" id="person_name" name="person_name"  class="form-control input-sm" value="@if(isset($data['person_name'])){{$data['person_name']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tên gọi khác</label>
                                    <input type="text"  id="person_name_other" name="person_name_other" class="form-control input-sm" value="@if(isset($data['person_name_other'])){{$data['person_name_other']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Số di động</label>
                                    <input type="text" placeholder="Số di động" id="person_phone" name="person_phone"  class="form-control input-sm" value="@if(isset($data['person_phone'])){{$data['person_phone']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">ĐT nhà riêng/cơ quan</label>
                                    <input type="text" placeholder="ĐT nhà riêng/cơ quan" id="person_telephone" name="person_telephone"  class="form-control input-sm" value="@if(isset($data['person_telephone'])){{$data['person_telephone']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ngày sinh</label>
                                    <input type="text" class="form-control" id="person_birth" name="person_birth"  data-date-format="dd-mm-yyyy" value="@if(isset($data['person_birth'])){{$data['person_birth']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Giới tính</label>
                                    <select name="person_sex" id="person_sex" class="form-control input-sm">
                                        {!! $optionSex !!}
                                    </select>
                                </div>
                            </div>

                            <div class="clear"></div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="name" class="control-label">Phòng ban đơn vị<span class="red"> (*) </span></label>
                                    <select name="person_depart_id" id="person_depart_id" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Số hiệucông chức</label>
                                    <input type="text" placeholder="Số hiệu công chức" id="person_code" name="person_code"  class="form-control input-sm" value="@if(isset($data['person_code'])){{$data['person_code']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Email</label>
                                    <input type="text" placeholder="Email" id="person_mail" name="person_mail"  class="form-control input-sm" value="@if(isset($data['person_mail'])){{$data['person_mail']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ngày thử việc</label>
                                    <input type="text" class="form-control" id="person_date_trial_work" name="person_date_trial_work"  data-date-format="dd-mm-yyyy" value="@if(isset($data['person_date_trial_work'])){{$data['person_date_trial_work']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ngày làm chính thức</label>
                                    <input type="text" class="form-control" id="person_date_start_work" name="person_date_start_work"  data-date-format="dd-mm-yyyy" value="@if(isset($data['person_date_start_work'])){{$data['person_date_start_work']}}@endif">
                                </div>
                            </div>

                            <div class="clear"></div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Số CMT<span class="red"> (*) </span></label>
                                    <input type="text" placeholder="Số CMT" id="person_chung_minh_thu" name="person_chung_minh_thu"  class="form-control input-sm" value="@if(isset($data['person_chung_minh_thu'])){{$data['person_chung_minh_thu']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Ngày cấp<span class="red"> (*) </span></label>
                                    <input type="text" class="form-control" id="person_date_range_cmt" name="person_date_range_cmt"  data-date-format="dd-mm-yyyy" value="@if(isset($data['person_date_range_cmt'])){{$data['person_date_range_cmt']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Nơi cấp</label>
                                    <input type="text" placeholder="Nơi cấp" id="person_issued_cmt" name="person_issued_cmt"  class="form-control input-sm" value="@if(isset($data['person_issued_cmt'])){{$data['person_issued_cmt']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Chức vụ</label>
                                    <select name="person_position_define_id" id="person_position_define_id" class="form-control input-sm">
                                        {!! $optionSex !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Chức danh nghề nghiệp</label>
                                    <select name="person_career_define_id" id="person_career_define_id" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Nhóm máu</label>
                                    <select name="person_blood_group_define_id" id="person_blood_group_define_id" class="form-control input-sm">
                                        {!! $optionSex !!}
                                    </select>
                                </div>
                            </div>
                        </div>

                    <!--Block 2--->
                        <div class="clear"></div>
                        <div class="form-group">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Địa chỉ nơi sinh<span class="red"> (*) </span></label>
                                    <input type="text" placeholder="Địa chỉ nơi sinh" id="person_address_place_of_birth" name="person_address_place_of_birth"  class="form-control input-sm" value="@if(isset($data['person_address_place_of_birth'])){{$data['person_address_place_of_birth']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tỉnh thành nơi sinh<span class="red"> (*) </span></label>
                                    <select name="person_province_place_of_birth" id="person_province_place_of_birth" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Địa chỉ quê quán<span class="red"> (*) </span></label>
                                    <input type="text" placeholder="Địa chỉ quê quán" id="person_address_home_town" name="person_address_home_town"  class="form-control input-sm" value="@if(isset($data['person_address_home_town'])){{$data['person_address_home_town']}}@endif">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tỉnh thành quê quán<span class="red"> (*) </span></label>
                                    <select name="person_province_home_town" id="person_province_home_town" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Dân tộc</label>
                                    <select name="person_nation_define_id" id="person_nation_define_id" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tôn giáo</label>
                                    <select name="person_respect" id="person_respect" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>

                            <div class="clear marginTop20"></div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Địa chỉ hiện tại<span class="red"> (*) </span></label>
                                    <input type="text" placeholder="Email" id="person_address_current" name="person_address_current"  class="form-control input-sm" value="@if(isset($data['person_address_current'])){{$data['person_address_current']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tỉnh thành hiện tại<span class="red"> (*) </span></label>
                                    <select name="person_province_current" id="person_province_current" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Quận huyện hiện tại<span class="red"> (*) </span></label>
                                    <select name="person_wards_current" id="person_wards_current" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Phường xã hiện tại<span class="red"> (*) </span></label>
                                    <select name="person_districts_current" id="person_districts_current" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>


                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Chiều cao</label>
                                    <input type="text" placeholder="Chiều cao" id="person_height" name="person_height"  class="form-control input-sm" value="@if(isset($data['person_height'])){{$data['person_height']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Cân nặng</label>
                                    <input type="text" placeholder="Cân nặng" id="person_weight" name="person_weight"  class="form-control input-sm" value="@if(isset($data['person_weight'])){{$data['person_weight']}}@endif">
                                </div>
                            </div>
                        </div>

                    <!--Block 3--->
                        <div class="clear marginTop20"></div>
                        <div class="form-group">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Thang bảng lương<span class="red"> (*) </span></label>
                                    <select name="role_type" id="role_type" class="form-control input-sm">
                                        {!! $optionRoleType !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Nghạch công chức<span class="red"> (*) </span></label>
                                    <select name="role_type" id="role_type" class="form-control input-sm">
                                        {!! $optionRoleType !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Bậc lương<span class="red"> (*) </span></label>
                                    <select name="role_type" id="role_type" class="form-control input-sm">
                                        {!! $optionRoleType !!}
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Hệ số<span class="red"> (*) </span></label>
                                    <select name="role_type" id="role_type" class="form-control input-sm">
                                        {!! $optionRoleType !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Lương thực hưởng<span class="red"> (*) </span></label>
                                    <input type="text" placeholder="Email" id="user_email" name="user_email"  class="form-control input-sm" value="@if(isset($data['user_email'])){{$data['user_email']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label for="name" class="control-label">Từ tháng</label>
                                    <select name="role_type" id="role_type" class="form-control input-sm">
                                        {!! $optionRoleType !!}
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label for="name" class="control-label">Năm</label>
                                    <select name="role_type" id="role_type" class="form-control input-sm">
                                        {!! $optionRoleType !!}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="form-group col-sm-12 text-left">
                            {!! csrf_field() !!}
                            <a class="btn btn-warning" href="{{URL::route('hr.personnelView')}}"><i class="fa fa-reply"></i> Trở lại</a>
                            <button  class="btn btn-primary"><i class="fa fa-floppy-o"></i> Lưu lại</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var person_birth = $('#person_birth').datepicker({ });
            var person_date_trial_work = $('#person_date_trial_work').datepicker({ });
            var person_date_start_work = $('#person_date_start_work').datepicker({ });
            var person_date_range_cmt = $('#person_date_range_cmt').datepicker({ });
        });
    </script>
@stop