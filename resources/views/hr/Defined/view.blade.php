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
                <li class="active">Quản lý định nghĩa</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="col-md-8 panel-content">
                <div class="panel panel-primary">
                    <div class="panel-heading paddingTop1 paddingBottom1">
                        <h4><i class="fa fa-list" aria-hidden="true"></i> Danh sách</h4>
                    </div>
                    {{ Form::open(array('method' => 'GET', 'role'=>'form')) }}
                    <div style="margin-top: 10px">
                        <div class="col-sm-4" >
                            <input @if(isset($search['define_name'])) value="{{$search['define_name']}}" @endif placeholder="Tên định nghĩa" name="define_name" class="form-control" id="define_name_s">
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control input-sm" name="define_type" id="define_type">
                                {!! $optionDefinedType !!}
                            </select>
                        </div>
                        <div style="float: left" class="form-group">
                            <button class="btn btn-primary btn-sm" type="submit" name="submit" value="1">
                                <i class="fa fa-search"></i> {{FunctionLib::viewLanguage('search')}}
                            </button>
                            <a class="btn btn-warning btn-sm" onclick="edit_item('{{FunctionLib::inputId(0)}}')" title="Sửa item">Thêm mới</a>

                        </div>
                    </div>
                    {{ Form::close() }}
                    <div class="panel-body line" id="element">
                        @if(sizeof($data) > 0)
                            <table class="table table-bordered bg-head-table">
                                <thead>
                                <tr>
                                    <th class="text-center w10">STT</th>
                                    <th>Tên định nghĩa</th>
                                    <th>Kiểu định nghĩa</th>
                                    <th>Thông tin</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Chức năng</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key => $item)
                                    <tr>
                                        <td>{{ $stt + $key+1 }}</td>
                                        <td>{{$item->define_name}}</td>
                                        <td>{{isset($arrDefinedType[$item->define_type]) ? $arrDefinedType[$item->define_type] : 'Chưa xác định'}}</td>
                                        <td>
                                            Thứ tự: {{$item->define_order}}
                                            @if($item->user_name_creater != '')
                                                Người tạo: {{$item->user_name_creater}}
                                                Ngày tạo: {{date('d/m/Y', $item->creater_time)}}
                                            @endif

                                            @if($item->user_name_update != '')
                                                Người cập nhật: {{$item->user_name_update}}
                                                Ngày cập nhật: {{date('d/m/Y', $item->update_time)}}
                                            @endif
                                        </td>
                                        <td class="text-center">{{isset($arrStatus[$item->define_status]) ? $arrStatus[$item->define_status] : 'Chưa xác định'}}</td>
                                        <td class="text-center middle" align="center">
                                            @if($is_root || $permission_edit)
                                               <a class="editItem" onclick="edit_item('{{FunctionLib::inputId($item['define_id'])}}')" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>
                                            @endif
                                            @if($is_boss || $permission_remove)
                                               <a class="deleteItem" onclick="delete_item('{{FunctionLib::inputId($item['define_id'])}}')"><i class="fa fa-trash fa-2x"></i></a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert">
                                {{FunctionLib::viewLanguage('no_data')}}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4 panel-content loadForm">
                <div class="panel panel-primary">
                    <div class="panel-heading paddingTop1 paddingBottom1">
                        <h4><i class="fa fa-plus-square" aria-hidden="true"></i> Thêm mới</h4>
                    </div>
                    <div class="panel-body">
                        <form id="formAdd" method="post">
                            <input type="hidden" name="id" value="{{FunctionLib::inputId(0)}}" class="form-control" id="id">
                            <div class="form-group">
                                <label for="define_name">Tên định nghĩa</label>
                                <input type="text" name="define_name" title="Tên định nghĩa" class="form-control input-required" id="define_name">
                            </div>
                            <div class="form-group">
                                <label for="define_order">Thứ tự hiển thị</label>
                                <input type="text" name="define_order" title="Thứ tự hiển thị" class="form-control" id="define_order">
                            </div>
                            <div class="form-group">
                                <label for="define_status">Kiểu định nghĩa</label>
                                <select class="form-control input-sm" name="define_type" id="define_type">
                                    {!! $optionDefinedType !!}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="define_status">Trạng thái</label>
                                <select class="form-control input-sm" name="define_status" id="define_status">
                                    {!! $optionStatus !!}
                                </select>
                            </div>
                            <a class="btn btn-success" id="submit" onclick="add_item()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Lưu</a>
                            <a class="btn btn-default" id="cancel" onclick="reset()"><i class="fa fa-undo" aria-hidden="true"></i> Làm lại</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function reset() {
            $("#define_name").val("");
            $("#define_order").val("");
            $("#id").val('{{\App\Library\AdminFunction\FunctionLib::inputId(0)}}');
            $('.frmHead').text('Thêm mới');
            $('.icChage').removeClass('fa-edit').addClass('fa-plus-square');
        }
        function delete_item(id) {
            var a = confirm(lng['txt_mss_confirm_delete']);
            var _token = $('meta[name="csrf-token"]').attr('content');
            if(a){
                $.ajax({
                    type: 'get',
                    url: WEB_ROOT+'/manager/defined/deleteDefined',
                    data: {
                        'id':id
                    },
                    headers: {
                        'X-CSRF-TOKEN': _token
                    },
                    success: function(data) {
                        if ((data.errors)) {
                            alert(data.errors)
                        }else {
                            window.location.reload();
                        }
                    },
                });
            }
        }
        function add_item() {
            var is_error = false;
            var msg = {};
            $("form#formAdd :input").each(function(){
                var input = $(this);
                if ($(this).hasClass("input-required") && input.val() == "") {
                    msg[$(this).attr("name")] = "※" + $(this).attr("title") + lng['is_required'];
                    is_error = true;
                }
            });

            if (is_error == true) {
                var error_msg = "";
                $.each(msg, function (key, value) {
                    error_msg = error_msg + value + "\n";
                });
                alert(error_msg);
                return false;
            }else {
                $("#submit").attr("disabled","true");
                var data = getFormData('#formAdd');
                var id = $("#id").val()
                $.ajax({
                    type: 'post',
                    url: WEB_ROOT+'/manager/defined/edit/'+id,
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#submit').removeAttr("disabled")
                        if ((data.isOk == 0)) {
                            alert(data.errors)
                        }else {
                            window.location.href=data.url;
                        }
                    },
                });
            }
        }
        function edit_item(id) {
            $.ajax({
                type: "POST",
                url: WEB_ROOT+'/manager/defined/ajaxLoadForm',
                data: {id:id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data){
                    $('.loadForm').html(data);
                    return false;
                }
            });
        }
        function getFormData(dom_query){
            var out = {};
            var s_data = $(dom_query).serializeArray();
            for(var i = 0; i<s_data.length; i++){
                var record = s_data[i];
                out[record.name] = record.value;
            }
            return out;
        }
    </script>
@stop