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
            <li class="active">Quản văn bản</li>
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
                                <label for="hr_document_name" class="control-label"><i>Tên văn bản</i></label>
                                <input type="text" class="form-control input-sm" id="hr_document_name" name="hr_document_name" autocomplete="off" placeholder="Tên văn bản" @if(isset($dataSearch['hr_document_name']))value="{{$dataSearch['hr_document_name']}}"@endif>
                            </div>

                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Cơ quan ban hành</i></label>
                                <select name="hr_document_promulgate" id="hr_document_promulgate" class="form-control input-sm" tabindex="12" data-placeholder="Cơ quan ban hành">
                                    {!! $optionPromulgate !!}
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Loại văn bản</i></label>
                                <select name="hr_document_type" id="hr_document_type" class="form-control input-sm" tabindex="12" data-placeholder="Loại văn bản">
                                    {!! $optionPromulgate !!}
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Lĩnh vực</i></label>
                                <select name="hr_document_promulgate" id="hr_document_field" class="form-control input-sm" tabindex="12" data-placeholder="Lĩnh vực">
                                    {!! $optionField !!}
                                </select>
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
                            <th width="20%">Tên văn bản</th>
                            <th width="5%">Số/ký hiệu</th>
                            <th width="10%">Cơ quan ban hành</th>
                            <th width="10%">Loại văn bản</th>
                            <th width="10%">Lĩnh vực</th>
                            <th width="8%">Trạng thái</th>
                            <th width="10%" class="text-center">Chức năng</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $k=>$item)
                            <tr>
                                <td class="text-center">1</td>
                                <td>{{$item->hr_document_name}}</td>
                                <td>{{$item->hr_document_code}}</td>
                                <td>
                                    @if(isset($arrPromulgate[$item['hr_document_promulgate']]) && $arrPromulgate[$item['hr_document_promulgate']] != -1)
                                        {{$arrPromulgate[$item['hr_document_promulgate']]}}
                                    @else
                                        Chưa xác định
                                    @endif
                                </td>
                                <td>
                                    @if(isset($arrType[$item['hr_document_type']]) && $arrType[$item['hr_document_type']] != -1)
                                        {{$arrType[$item['hr_document_type']]}}
                                    @else
                                        Chưa xác định
                                    @endif
                                </td>
                                <td>
                                    @if(isset($arrField[$item['hr_document_field']]) && $arrField[$item['hr_document_field']] != -1)
                                        {{$arrField[$item['hr_document_field']]}}
                                    @else
                                        Chưa xác định
                                    @endif
                                </td>
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