<?php use App\Library\AdminFunction\FunctionLib; ?>
@extends('admin.AdminLayouts.index')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">{{FunctionLib::viewLanguage('home')}}</a>
            </li>
            <li class="active">Quản lý đơn vị - phòng ban</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="line">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix paddingTop1 paddingBottom1">
                    <div class="panel-title pull-left">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Thêm đơn vị</h4>
                    </div>
                    <div class="btn-group btn-group-sm pull-right mgt3">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.departmentView')}}"><i class="fa fa-arrow-left"></i>&nbsp;Quay lại</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p class="boxtitle">Chọn đơn vị/ phòng ban quản lý trực tiếp</p>
                            <div id="treeview" class="treeview">
                                <ul class="list-group">
                                    <li class="list-group-item node-treeview node-selected" data-nodeid="0"><a href="">Phòng tổ chức</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="1" ><a href="">Phòng Y Tế</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="2" ><a href="">Phòng Kinh doanh</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="3" ><a href="">Phòng Kế toán</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="4" ><a href="">p.tchc</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="5" ><a href="">Phòng vật tư xuất nhập khẩu</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="6" ><a href="">Khoa CDHA</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="7" ><a href="">Khoa duoc</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="8" ><a href="">Khoa dieu duong</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="9" ><a href="">Phòng kỹ thuật</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="10" ><a href="">Bệnh viện Tuệ Tĩnh (Đơn vị độc lập)</a><span class="badge">1</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="11" ><a href="">Khoa chấn thương chỉnh hình</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="12" ><a href="">Khoa thần kinh</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="13" ><a href="">phong test a</a><span class="badge">0</span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <p>(<span class="clred">*</span>) Là trường bắt buộc phải nhập</p>
                            <form id="adminForm" name="adminForm" method="post" enctype="multipart/form-data" action="" novalidate="novalidate">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p><span class="lbl">Đơn vị/ Phòng ban quản lý trực tiếp</span>:&nbsp;<span id="orgname" class="val">Phòng tổ chức</span></p>
                                        <input id="department_parent_id" name="department_parent_id" @isset($data['department_parent_id'])value="{{FunctionLib::inputId($data['department_parent_id'])}}" @else value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Loại đơn vị/ phòng ban (<span class="clred">*</span>)</label>
                                            <select class="form-control input-sm"  id="department_type" name="department_type">
                                                {!! $optionDepartmentType !!}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tên đơn vị/ Phòng ban(<span class="clred">*</span>)</label>
                                            <input class="form-control input-sm input-required" title="Tên đơn vị/ Phòng ban" id="department_name" name="department_name" @isset($data['department_name'])value="{{$data['department_name']}}"@endif type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Họ tên lãnh đạo</label>
                                            <input class="form-control input-sm" id="department_leader" name="department_leader" placeholder="Họ tên lãnh đạo" @isset($data['department_leader'])value="{{$data['department_leader']}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Danh sách số điện thoại</label>
                                            <input class="form-control input-sm" id="department_phone" name="department_phone" placeholder="Nhập danh sách các số điện thoại, cách nhau bởi dấu ','" @isset($data['department_phone'])value="{{$data['department_phone']}}"@endif type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Danh sách email</label>
                                            <input class="form-control input-sm" id="department_email" name="department_email" placeholder="Nhập danh sách các email, cách nhau bởi dấu ','" @isset($data['department_email'])value="{{$data['department_email']}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Danh sách số fax</label>
                                            <input class="form-control input-sm" id="department_fax" name="department_fax" placeholder="Nhập danh sách các số fax, cách nhau bởi dấu ','" @isset($data['department_fax'])value="{{$data['department_fax']}}"@endif type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Danh sách địa điểm bố trí tài sản</label>
                                            <input class="form-control input-sm" id="department_postion" name="department_postion" placeholder="Nhập danh sách địa điểm bố trí tài sản, cách dâu bởi dấu ','" @isset($data['department_postion'])value="{{$data['department_postion']}}"@endif type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Mã số thuế</label>
                                            <input class="form-control input-sm" id="department_num_tax" name="department_num_tax" placeholder="Mã số thuế" @isset($data['department_num_tax'])value="{{$data['department_num_tax']}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tài khoản ngân hàng</label>
                                            <input class="form-control input-sm" id="department_num_bank" name="department_num_bank" placeholder="Tài khoản ngân hàng" @isset($data['department_num_bank'])value="{{$data['department_num_bank']}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Ngân hàng</label>
                                            <input class="form-control input-sm" id="department_name_bank" name="department_name_bank" placeholder="Ngân hàng" @isset($data['department_name_bank'])value="{{$data['department_name_bank']}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Chi nhánh ngân hàng</label>
                                            <input class="form-control input-sm" id="department_position_bank" name="department_position_bank" placeholder="Chi nhánh ngân hàng" @isset($data['department_position_bank'])value="{{$data['department_position_bank']}}"@endif type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Thứ tự</label>
                                            <input class="form-control input-sm" id="department_order" name="department_order" placeholder="Thứ tự" @isset($data['department_order'])value="{{$data['department_order']}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Trạng thái</label>
                                            <select class="form-control input-sm" name="department_status" id="department_status">
                                                {!! $optionStatus !!}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! csrf_field() !!}
                                        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-forward"></i>&nbsp;Lưu và tiếp tục nhập</button>
                                        <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-save"></i>&nbsp;Lưu hoàn thành</button>
                                    </div>
                                    <input id="id_hiden" name="id_hiden" @isset($data['department_id'])value="{{FunctionLib::inputId($data['department_id'])}}" @else value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop