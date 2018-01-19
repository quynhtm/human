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
                                    <label for="name" class="control-label">Số hiệu cán bộ công chức</label>
                                    <input type="text" placeholder="Số hiệu cán bộ công chức" id="person_code" name="person_code"  class="form-control input-sm" value="@if(isset($data['person_code'])){{$data['person_code']}}@endif">
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
                                    <label for="name" class="control-label">Telephone</label>
                                    <input type="text" placeholder="Telephone" id="telephone" name="telephone"  class="form-control input-sm" value="@if(isset($data['telephone'])){{$data['telephone']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Số đăng ký kinh doanh</label>
                                    <input type="text" placeholder="Số đăng ký kinh doanh" id="number_code" name="number_code"  class="form-control input-sm" value="@if(isset($data['number_code'])){{$data['number_code']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Địa chỉ kinh doanh</label>
                                    <input type="text" placeholder="Địa chỉ kinh doanh" id="address_register" name="address_register"  class="form-control input-sm" value="@if(isset($data['address_register'])){{$data['address_register']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Giới tính</label>
                                    <select name="user_sex" id="user_sex" class="form-control input-sm">
                                        {!! $optionSex !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Trạng thái</label>
                                    <select name="user_status" id="user_status" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Trạng thái</label>
                                    <select name="user_status" id="user_status" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>
                        </div>

                    <!--Block 2--->
                        <div class="form-group">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tên đăng nhập<span class="red"> (*) </span></label>
                                    <input type="text" placeholder="Tên đăng nhập" id="user_name" name="user_name"  class="form-control input-sm" value="@if(isset($data['user_name'])){{$data['user_name']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Mật khẩu<span class="red"> (*) </span></label>
                                    <input type="password"  id="user_password" name="user_password" class="form-control input-sm" value="Sms@!2017">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tên nhân viên<span class="red"> (*) </span></label>
                                    <input type="text" placeholder="Tên nhân viên" id="user_full_name" name="user_full_name"  class="form-control input-sm" value="@if(isset($data['user_full_name'])){{$data['user_full_name']}}@endif">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Kiểu User<span class="red"> (*) </span></label>
                                    <select name="role_type" id="role_type" class="form-control input-sm">
                                        {!! $optionRoleType !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Email<span class="red"> (*) </span></label>
                                    <input type="text" placeholder="Email" id="user_email" name="user_email"  class="form-control input-sm" value="@if(isset($data['user_email'])){{$data['user_email']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Phone</label>
                                    <input type="text" placeholder="Phone" id="user_phone" name="user_phone"  class="form-control input-sm" value="@if(isset($data['user_phone'])){{$data['user_phone']}}@endif">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Telephone</label>
                                    <input type="text" placeholder="Telephone" id="telephone" name="telephone"  class="form-control input-sm" value="@if(isset($data['telephone'])){{$data['telephone']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Số đăng ký kinh doanh</label>
                                    <input type="text" placeholder="Số đăng ký kinh doanh" id="number_code" name="number_code"  class="form-control input-sm" value="@if(isset($data['number_code'])){{$data['number_code']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Địa chỉ kinh doanh</label>
                                    <input type="text" placeholder="Địa chỉ kinh doanh" id="address_register" name="address_register"  class="form-control input-sm" value="@if(isset($data['address_register'])){{$data['address_register']}}@endif">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Giới tính</label>
                                    <select name="user_sex" id="user_sex" class="form-control input-sm">
                                        {!! $optionSex !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Trạng thái</label>
                                    <select name="user_status" id="user_status" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Trạng thái</label>
                                    <select name="user_status" id="user_status" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>
                        </div>

                    <!--Block 3--->
                        <div class="form-group">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tên đăng nhập<span class="red"> (*) </span></label>
                                    <input type="text" placeholder="Tên đăng nhập" id="user_name" name="user_name"  class="form-control input-sm" value="@if(isset($data['user_name'])){{$data['user_name']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Mật khẩu<span class="red"> (*) </span></label>
                                    <input type="password"  id="user_password" name="user_password" class="form-control input-sm" value="Sms@!2017">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Tên nhân viên<span class="red"> (*) </span></label>
                                    <input type="text" placeholder="Tên nhân viên" id="user_full_name" name="user_full_name"  class="form-control input-sm" value="@if(isset($data['user_full_name'])){{$data['user_full_name']}}@endif">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Kiểu User<span class="red"> (*) </span></label>
                                    <select name="role_type" id="role_type" class="form-control input-sm">
                                        {!! $optionRoleType !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Email<span class="red"> (*) </span></label>
                                    <input type="text" placeholder="Email" id="user_email" name="user_email"  class="form-control input-sm" value="@if(isset($data['user_email'])){{$data['user_email']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Phone</label>
                                    <input type="text" placeholder="Phone" id="user_phone" name="user_phone"  class="form-control input-sm" value="@if(isset($data['user_phone'])){{$data['user_phone']}}@endif">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Telephone</label>
                                    <input type="text" placeholder="Telephone" id="telephone" name="telephone"  class="form-control input-sm" value="@if(isset($data['telephone'])){{$data['telephone']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Số đăng ký kinh doanh</label>
                                    <input type="text" placeholder="Số đăng ký kinh doanh" id="number_code" name="number_code"  class="form-control input-sm" value="@if(isset($data['number_code'])){{$data['number_code']}}@endif">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Địa chỉ kinh doanh</label>
                                    <input type="text" placeholder="Địa chỉ kinh doanh" id="address_register" name="address_register"  class="form-control input-sm" value="@if(isset($data['address_register'])){{$data['address_register']}}@endif">
                                </div>
                            </div>

                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Giới tính</label>
                                    <select name="user_sex" id="user_sex" class="form-control input-sm">
                                        {!! $optionSex !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Trạng thái</label>
                                    <select name="user_status" id="user_status" class="form-control input-sm">
                                        {!! $optionStatus !!}
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label for="name" class="control-label">Trạng thái</label>
                                    <select name="user_status" id="user_status" class="form-control input-sm">
                                        {!! $optionStatus !!}
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
        });
    </script>
@stop