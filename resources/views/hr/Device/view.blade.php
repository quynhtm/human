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
            <li class="active">Quản lý tài sản</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="line">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix paddingTop1 paddingBottom1">
                    <div class="panel-title pull-left">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Quản lý tài sản</h4>
                    </div>
                    <div class="btn-group btn-group-sm pull-right mgt3">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.deviceEdit',array('id' => FunctionLib::inputId(0)))}}"><i class="fa fa-file"></i>&nbsp;Thêm mới</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if(sizeof($data) > 0)
                            <table class="table table-bordered not-bg">
                                <thead>
                                <tr>
                                    <th width="2%" class="text-center">STT</th>
                                    <th width="20%">Tên thiết bị</th>
                                    <th width="10%">Mã</th>
                                    <th width="12%">Thuộc loại</th>
                                    <th width="10%">Ngày bàn giao</th>
                                    <th width="10%">Người sử dụng</th>
                                    <th width="8%">Trạng thái</th>
                                    <th width="10%">Chức năng</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $k=>$item)
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>{{$item->device_name}}</td>
                                            <td>{{$item->device_code}}</td>
                                            <td>
                                                @if(isset($arrDeviceType[$item['device_type']]))
                                                    {{$arrDeviceType[$item['device_type']]}}
                                                @else
                                                    Chưa xác định
                                                @endif
                                            </td>
                                            <td>{{date('d-m-Y', $item['device_date_return'])}}</td>
                                            <td>
                                                @if(isset($arrPersion[$item['device_person_id']]))
                                                    {{$arrPersion[$item['device_person_id']]}}
                                                @else
                                                    Chưa xác định
                                                @endif
                                            </td>
                                            <td>
                                                @if(isset($arrStatus[$item['device_status']]))
                                                    {{$arrStatus[$item['device_status']]}}
                                                @else
                                                    Chưa xác định
                                                @endif
                                            </td>
                                            <td align="center">
                                                <div class="dropdown">
                                                    <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                        - Chọn -
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        @if($is_root || $permission_edit)
                                                            <li><a href="{{URL::route('hr.deviceEdit',array('id' => FunctionLib::inputId($item['device_id'])))}}" title="Sửa">Sửa</a></li>
                                                        @endif
                                                        @if($is_boss || $permission_remove)
                                                            <li><a class="deleteItem" title="Xóa" onclick="HR.deleteItem('{{FunctionLib::inputId($item['device_id'])}}', WEB_ROOT + '/manager/device/deleteDevice')">Xóa</a></li>
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