<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
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
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Quản lý đơn vị - phòng ban</h4>
                    </div>
                    <div class="btn-group btn-group-sm pull-right mgt3">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.departmentEdit',array('id' => FunctionLib::inputId(0)))}}"><i class="fa fa-file"></i>&nbsp;Thêm mới</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
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
                        <div class="col-md-9">
                            @if(sizeof($data) > 0)
                            <table class="table table-bordered not-bg">
                                <thead>
                                <tr>
                                    <th class="text-center w10">STT</th>
                                    <th>Tên đơn vị/ Phòng ban</th>
                                    <th class="text-center">Điện thoại</th>
                                    <th class="text-center">Fax</th>
                                    <th class="text-center">Phân loại</th>
                                    <th class="text-center">Cập nhật</th>
                                    <th class="text-center">Chức năng</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $item)
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>
                                            <a href="{{URL::route('hr.departmentEdit',array('id' => FunctionLib::inputId($item['department_id'])))}}" title="{{$item->department_name}}">{{$item->department_name}}</a>
                                        </td>
                                        <td>{{$item->department_phone}}</td>
                                        <td>{{$item->department_fax}}</td>
                                        <td>
                                            @if(isset($arrDepartmentType[$item['department_type']]))
                                                {{$arrDepartmentType[$item['department_type']]}}
                                            @else
                                                Chưa xác định
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($item['department_update_time'] > 0)
                                                {{date('d/m/Y', $item['department_update_time'])}}
                                            @else
                                                {{date('d/m/Y', $item['department_creater_time'])}}
                                            @endif
                                        </td>
                                        <td align="center">
                                            <div class="dropdown">
                                                <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_591baf3a473c2c1e80535bf9" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    - Chọn -
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($is_root || $permission_edit)
                                                    <li><a href="{{URL::route('hr.departmentEdit',array('id' => FunctionLib::inputId($item['department_id'])))}}" title="Sửa">Sửa</a></li>
                                                    @endif
                                                    @if($is_boss || $permission_remove)
                                                    <li><a class="deleteItem" title="Xóa" onclick="HR.deleteItem('{{FunctionLib::inputId($item['department_id'])}}', WEB_ROOT + '/manager/department/deleteDepartment')">Xóa</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-right">
                                {!! $paging !!}
                            </div>
                            @else
                                <div class="alert">
                                    Không có dữ liệu
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop