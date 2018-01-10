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
            <li class="active">Quản lý role</li>
        </ul><!-- /.breadcrumb -->
    </div>

    <div class="page-content">
        <div class="col-md-8 panel-content">
            <div class="panel panel-primary">
                <div class="panel-heading paddingTop1 paddingBottom1">
                    <h4><i class="fa fa-list" aria-hidden="true"></i> Danh sách</h4>
                </div> <!-- /widget-header -->
                {{ Form::open(array('method' => 'GET', 'role'=>'form')) }}
                <div style="margin-top: 10px">
                    <div class="col-sm-4" >
                        <input @if(isset($search['role_name'])) value="{{$search['role_name']}}" @endif placeholder="Tên Role" name="role_name_s" class="form-control" id="role_name_s">
                    </div>
                    <div style="float: left" class="form-group">
                        <button class="btn btn-primary btn-sm" type="submit" name="submit" value="1"><i
                                    class="fa fa-search"></i> {{FunctionLib::viewLanguage('search')}}</button>
                    </div>
                </div>
                {{ Form::close() }}
                <div class="panel-body" id="element">
                    @if(sizeof($data) > 0)
                        <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="">
                                <th width="5%" class="text-center center">Stt</th>
                                <th width="55%" class="center ">Tên role</th>
                                <th width="15%" class="center ">Trạng thái</th>
                                <th width="10%" class="center ">Order</th>
                                <th width="15%" class="center ">Thao tác</th>
                            </tr>
                            </thead>
                            <tbodys>
                            @foreach ($data as $key => $item)
                                <td class="text-center middle">{{$key+1 }}</td>
                                <td>{{$item['role_name']}}</td>
                                <td>{{ $item['role_status']}}</td>
                                <td>{{ $item['role_order'] }}
                                </td>
                                <td class="center">
                                    <a onclick="edit_item('{{FunctionLib::inputId($item['role_id'])}}','{{$item['role_name']}}','{{$item['role_order']}}','{{$item['role_status']}}')" title="Sửa item"><i class="fa fa-edit fa-2x"></i></a>
                                    <a onclick="delete_item('{{FunctionLib::inputId($item['role_id'])}}')"><i class="fa fa-trash fa-2x"></i></a>
                                </td>
                                </tr>
                            @endforeach
                            </tbodys>
                        </table>
                    @else
                        <div class="alert">
                            {{FunctionLib::viewLanguage('no_data')}}
                        </div>
                    @endif
                </div> <!-- /widget-content -->
            </div> <!-- /widget -->
        </div>
        <div class="col-md-4 panel-content">
            <div class="panel panel-primary">
                <div class="panel-heading paddingTop1 paddingBottom1">
                    <h4><i class="fa fa-plus-square" aria-hidden="true"></i> Thêm mới</h4>
                </div> <!-- /widget-header -->
                <div class="panel-body">
                    <form id="form" method="post">
                        <input type="hidden" name="id" value="{{\App\Library\AdminFunction\FunctionLib::inputId(0)}}" class="form-control" id="id">
                        <div class="form-group">
                            <label for="role_name">Tên role</label>
                            <input type="text" name="role_name" title="Tên role" class="form-control input-required" id="role_name" value="">
                        </div>
                        <div class="form-group">
                            <label for="role_order">Thứ tự hiển thị</label>
                            <input type="text" name="role_order" title="Thứ tự hiển thị" class="form-control input-required" id="role_order">
                        </div>
                        <div class="form-group">
                            <label for="role_status">Trạng thái</label>
                            <input type="text" name="role_status" title="Tên role" class="form-control" id="role_status">
                        </div>
                        <a class="btn btn-success" id="submit" onclick="add_item()"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</a>
                        <a class="btn btn-default" id="cancel" onclick="reset()"><i class="fa fa-undo" aria-hidden="true"></i> Reset</a>
                    </form>
                </div> <!-- /widget-content -->
            </div>
        </div>
        <div class="row">
        </div>
    </div>
</div>
    <script>

        function reset() {
            $("#role_name").val("");
            $("#role_order").val("");
            $("#id").val('{{\App\Library\AdminFunction\FunctionLib::inputId(0)}}');
        }
        function delete_item(id) {
            var a = confirm(lng['txt_mss_confirm_delete']);
            if (a){
                $.ajax({
                    type: 'get',
                    url: WEB_ROOT+'/manager/role/deleteRole',
                    data: {
                        'id':id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
            $("form#form :input").each(function(){
                var input = $(this); // This is the jquery object of the input, do what you will
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
                var role_name = $("#role_name").val()
                var role_order = $("#role_order").val()
                var role_status = $("#role_status").val()
                var id = $("#id").val()
                $.ajax({
                    type: 'post',
                    url: WEB_ROOT+'/manager/role/addRole',
                    data: {
                        'role_name':role_name,
                        'role_order':role_order,
                        'role_status':role_status,
                        'id':id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#submit').removeAttr("disabled")
                        if ((data.errors)) {
                            alert(data.errors)
                        }else {
                            window.location.reload();
                        }
                    },
                });
            }
        }

        function edit_item(id,role_name,role_order,role_status) {
            $("#role_name").val(role_name);
            $("#role_order").val(role_order);
            $("#role_status").val(role_status);
            $("#id").val(id);
        }

    </script>
@stop
