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
            <li class="active">Quản lý thư, tin nhắn</li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <div class="panel panel-info">
                    <form method="get" action="" role="form">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group col-sm-2">
                                <label for="hr_document_name" class="control-label"><i>Tên thư, tin nhắn</i></label>
                                <input type="text" class="form-control input-sm" id="hr_document_name" name="hr_document_name" autocomplete="off" placeholder="Tên thư, tin nhắn" @if(isset($dataSearch['hr_document_name']))value="{{$dataSearch['hr_document_name']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Trạng thái</i></label>
                                <select name="hr_document_status" id="hr_document_status" class="form-control input-sm" tabindex="12" data-placeholder="Trạng thái">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                    <span class="">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.HrDocumentEdit',array('id' => FunctionLib::inputId(0)))}}">
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
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> thư, tin nhắn @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr>
                            <th width="2%" class="text-center">STT</th>
                            <th width="20%">Chủ đề</th>
                            <th width="8%">Trạng thái</th>
                            <th width="10%" class="text-center">Chức năng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $k=>$item)
                            <tr>
                                <td class="text-center">1</td>
                                <td>{{$item->hr_document_name}}</td>
                                <td>
                                    @if(isset($arrStatus[$item['hr_document_status']]) && $arrStatus[$item['hr_document_status']] != -1)
                                        {{$arrStatus[$item['hr_document_status']]}}
                                    @else
                                        Chưa xác định
                                    @endif
                                </td>
                                <td align="center">
                                    @if($is_root || $permission_edit)
                                        <a href="{{URL::route('hr.HrDocumentEdit',array('id' => FunctionLib::inputId($item['hr_document_id'])))}}" title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                    @endif
                                    @if($is_boss || $permission_remove)
                                        <a class="deleteItem" title="Xóa" onclick="HR.deleteItem('{{FunctionLib::inputId($item['hr_document_id'])}}', WEB_ROOT + '/manager/document/deleteHrDocument')"><i class="fa fa-trash fa-2x"></i></a>
                                    @endif
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
@stop