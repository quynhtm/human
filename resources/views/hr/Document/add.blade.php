<?php
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;
use App\Library\PHPThumb\ThumbImg;
use App\Library\AdminFunction\CGlobal;
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
            <li class="active">Quản lý thư, tin nhắn</li>
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
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Thêm thư, tin nhắn</h4>
                    </div>
                    <div class="btn-group btn-group-sm pull-right mgt3">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.HrDocumentView')}}"><i class="fa fa-arrow-left"></i>&nbsp;Quay lại</a>
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
                                            <label>Người nhận(<span class="clred">*</span>)</label>
                                            <input class="form-control input-sm input-required" title="Người nhận" id="hr_document_person_recive" name="hr_document_person_recive" @isset($data['hr_document_person_recive'])value="{{$data['hr_document_person_recive']}}"@endif type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Chủ đề thư, tin nhắn(<span class="clred">*</span>)</label>
                                            <input class="form-control input-sm input-required" title="Tên thư, tin nhắn" id="hr_document_name" name="hr_document_name" @isset($data['hr_document_name'])value="{{$data['hr_document_name']}}"@endif type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Nội dung</label>
                                            <textarea class="form-control input-sm input-required" name="hr_document_content" id="hr_document_content" cols="30" rows="5">@isset($data['hr_document_content']){{$data['hr_document_content']}}@endif</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        {!! csrf_field() !!}
                                        <button type="submit" class="btn btn-success btn-sm submitFinish"><i class="fa fa-save"></i>&nbsp;Lưu hoàn thành</button>
                                    </div>
                                    <input id="id_hiden" name="id_hiden" @isset($data['hr_document_id'])rel="{{$data['hr_document_id']}}" value="{{FunctionLib::inputId($data['hr_document_id'])}}" @else rel="0" value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Popup Upload Img-->
<div class="modal fade" id="sys_PopupUploadImgOtherPro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Upload ảnh</h4>
            </div>
            <div class="modal-body">
                <form name="uploadImage" method="post" action="#" enctype="multipart/form-data">
                    <div class="form_group">
                        <div id="sys_show_button_upload">
                            <div id="sys_mulitplefileuploader" class="btn btn-primary">Upload ảnh</div>
                        </div>
                        <div id="status"></div>

                        <div class="clearfix"></div>
                        <div class="clearfix" style='margin: 5px 10px; width:100%;'>
                            <div id="div_image"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Popup Upload Img-->
@stop