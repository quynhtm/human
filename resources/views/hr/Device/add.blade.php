<?php
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;
use App\Library\PHPThumb\ThumbImg;
?>
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
        @if(isset($error))
            <div class="alert alert-warning">
                <?= implode('<br>', $error) ?>
            </div>
        @endif
        <div class="line">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix paddingTop1 paddingBottom1">
                    <div class="panel-title pull-left">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Thêm thiết bị</h4>
                    </div>
                    <div class="btn-group btn-group-sm pull-right mgt3">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.deviceView')}}"><i class="fa fa-arrow-left"></i>&nbsp;Quay lại</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p>(<span class="clred">*</span>) Là trường bắt buộc phải nhập</p>
                            <form id="adminForm" name="adminForm adminFormDevidetAdd" method="post" enctype="multipart/form-data" action="" novalidate="novalidate">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Loại thiết bị (<span class="clred">*</span>)</label>
                                            <select class="form-control input-sm"  id="device_type" name="device_type">
                                                {!! $optionDeviceType !!}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tên thiết bị(<span class="clred">*</span>)</label>
                                            <input class="form-control input-sm input-required" title="Tên thiết bị" id="device_name" name="device_name" @isset($data['device_name'])value="{{$data['device_name']}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Mã thiết bị</label>
                                            <input class="form-control input-sm input-required" title="Mã thiết bị" id="device_code" name="device_code" @isset($data['device_code'])value="{{$data['device_code']}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Người sử dụng</label>
                                            <select class="form-control input-sm"  id="device_person_id" name="device_person_id">
                                                {!! $optionPersion !!}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Thuộc phòng ban</label>
                                            <select class="form-control input-sm"  id="device_depart_id" name="device_depart_id">
                                                <option value="">--Chọn--</option>
                                                {!! $optionDepartment !!}}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ngày bàn giao thiết bị</label>
                                            <input class="date form-control input-sm input-required" title="Ngày bàn giao thiết" id="device_date_return" name="device_date_return" @isset($data['device_date_return'])value="{{date('d-m-Y', $data['device_date_return'])}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ngày sử dụng bàn giao cho nhân sự</label>
                                            <input class="date form-control input-sm input-required" title="Ngày sử dụng bàn giao cho nhân sự" id="device_date_use" name="device_date_use" @isset($data['device_date_use'])value="{{date('d-m-Y', $data['device_date_use'])}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ngày sản xuất</label>
                                            <input class="date form-control input-sm input-required" title="Hình ảnh" id="device_date_of_manufacture" name="device_date_of_manufacture" @isset($data['device_date_of_manufacture'])value="{{date('d-m-Y', $data['device_date_of_manufacture'])}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ngày bảo hành</label>
                                            <input class="date form-control input-sm input-required" title="Hình ảnh" id="device_date_warranty" name="device_date_warranty" @isset($data['device_date_warranty'])value="{{date('d-m-Y', $data['device_date_warranty'])}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Trạng thái</label>
                                            <select class="form-control input-sm" name="device_status" id="device_status">
                                                {!! $optionStatus !!}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Hình ảnh</label>
                                            <input type="file" name="device_image" id="device_image">
                                            @if(isset($data['device_image']) && $data['device_image'] != '')
                                                <div class="mgt10"><img src="{{ThumbImg::thumbBaseNormal(Define::FOLDER_DEVICE, $data->device_image, Define::sizeImage_100, Define::sizeImage_100, $alt='', $isThumb=true, $returnPath=true)}}" alt=""></div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Mô tả</label>
                                            <textarea class="form-control input-sm input-required" name="device_describe" id="device_describe" cols="30" rows="5">@isset($data['device_describe']){{$data['device_describe']}}@endif</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Thông số kỹ thuật</label>
                                            <textarea class="form-control input-sm input-required" name="device_infor_technical" id="device_infor_technical" cols="30" rows="5">@isset($data['device_infor_technical']){{$data['device_infor_technical']}}@endif</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! csrf_field() !!}
                                        <button type="button" class="btn btn-primary btn-sm submitNext"><i class="fa fa-forward"></i>&nbsp;Lưu và tiếp tục nhập</button>
                                        <button type="submit" class="btn btn-success btn-sm submitFinish"><i class="fa fa-save"></i>&nbsp;Lưu hoàn thành</button>
                                    </div>
                                    <input id="id_hiden" name="id_hiden" @isset($data['device_id'])rel="{{$data['device_id']}}" value="{{FunctionLib::inputId($data['device_id'])}}" @else rel="0" value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
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