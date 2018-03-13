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
            <li class="active">Danh sách nhân sự</li>
        </ul>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="panel panel-info">
                    <form method="Post" action="" role="form">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group col-sm-2">
                                <label for="user_name" class="control-label"><i>Tên đăng nhập</i></label>
                                <input type="text" class="form-control input-sm" id="user_name" name="user_name" autocomplete="off" placeholder="Tên đăng nhập" @if(isset($dataSearch['user_name']))value="{{$dataSearch['user_name']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_email"><i>Email</i></label>
                                <input type="text" class="form-control input-sm" id="user_email" name="user_email" autocomplete="off" placeholder="Địa chỉ email" @if(isset($dataSearch['user_email']))value="{{$dataSearch['user_email']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_phone"><i>Di động</i></label>
                                <input type="text" class="form-control input-sm" id="user_phone" name="user_phone" autocomplete="off" placeholder="Số di động" @if(isset($dataSearch['user_phone']))value="{{$dataSearch['user_phone']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Nhóm quyền</i></label>
                                <select name="role_type" id="role_type" class="form-control input-sm" tabindex="12" data-placeholder="Chọn nhóm quyền">
                                    <option value="0">--- Chọn nhóm quyền ---</option>
                                    {!! $optionRoleType !!}
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                    <span class="">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.personnelEdit',array('id' => FunctionLib::inputId(0)))}}">
                            <i class="ace-icon fa fa-plus-circle"></i>
                            Thêm mới
                        </a>
                    </span>
                            <span class="">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i> Tìm kiếm</button>
                    </span>
                        </div>
                    </form>
                </div>
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> nhân sự @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="3%" class="text-center">STT</th>
                            <th width="8%">Chức năng</th>
                            <th width="20%">Họ tên</th>
                            <th width="5%" class="text-center">Giới tính</th>
                            <th width="10%" class="text-center">Ngày làm việc</th>
                            <th width="15%" class="text-center">Đơn vị/Bộ phận</th>
                            <th width="15%" class="text-center">Chức danh nghề nghiệp</th>
                            <th width="15%" class="text-center">Chức vụ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr @if($item['user_status'] == \App\Library\AdminFunction\Define::STATUS_BLOCK)class="red bg-danger middle" {else} class="middle" @endif>
                                <td class="text-center middle">{{ $stt+$key+1 }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            - Chọn -
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach($arrLinkEditPerson as $kl=>$val)
                                            <li><a title="Sửa" href="{{URL::to('/').$val['link_url'].FunctionLib::inputId($item['person_id'])}}" target="_blank"><i class="{{$val['icons']}}"></i> {{$val['name_url']}}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{URL::route('hr.personnelDetail',array('id' => FunctionLib::inputId($item['person_id'])))}}" title="Chi tiết nhân sự" target="_blank">
                                        {{ $item['person_name'] }}
                                    </a>
                                    <a class="viewItem" title="Chi tiết nhân sự" onclick="HR.getInfoPersonPopup('{{FunctionLib::inputId($item['person_id'])}}')">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <br/>SN:{{date('d-m-Y',time())}}
                                </td>
                                <td class="text-center middle">Nữ</td>
                                <td class="text-center middle">{{date('d-m-Y',time())}}</td>
                                <td class="text-center middle">Hành chính nhân sự</td>
                                <td class="text-center middle">Tuyển sinh</td>
                                <td class="text-center middle">Tuyển trách viên</td>
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
    </div><!-- /.page-content -->
</div>
@stop