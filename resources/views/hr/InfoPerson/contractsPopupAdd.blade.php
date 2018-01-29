<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">Thông tin hợp đồng</h4>
</div>
<img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/ajax-loader.gif" width="20" style="display: none"
     id="img_loading_district">
<div class="modal-body">
    @if(isset($infoPerson))
        <span class="span">Họ và tên:<b> {{$infoPerson->person_name}}</b></span>
        <span class="span">&nbsp;&nbsp;&nbsp;Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b></span>
        <span class="span">&nbsp;&nbsp;&nbsp;Số cán bộ:<b> {{$infoPerson->person_code}}</b></span>
    @endif
    <hr>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Tên gọi khác</label>
                <input type="text" id="person_name_other" name="person_name_other" class="form-control input-sm"
                       value="@if(isset($data['person_name_other'])){{$data['person_name_other']}}@endif">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Số di động</label>
                <input type="text" placeholder="Số di động" id="person_phone" name="person_phone"
                       class="form-control input-sm"
                       value="@if(isset($data['person_phone'])){{$data['person_phone']}}@endif">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Tên gọi khác</label>
                <input type="text" id="person_name_other" name="person_name_other" class="form-control input-sm"
                       value="@if(isset($data['person_name_other'])){{$data['person_name_other']}}@endif">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Số di động</label>
                <input type="text" placeholder="Số di động" id="person_phone" name="person_phone"
                       class="form-control input-sm"
                       value="@if(isset($data['person_phone'])){{$data['person_phone']}}@endif">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Tên gọi khác</label>
                <input type="text" id="person_name_other" name="person_name_other" class="form-control input-sm"
                       value="@if(isset($data['person_name_other'])){{$data['person_name_other']}}@endif">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Số di động</label>
                <input type="text" placeholder="Số di động" id="person_phone" name="person_phone"
                       class="form-control input-sm"
                       value="@if(isset($data['person_phone'])){{$data['person_phone']}}@endif">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Tên gọi khác</label>
                <input type="text" id="person_name_other" name="person_name_other" class="form-control input-sm"
                       value="@if(isset($data['person_name_other'])){{$data['person_name_other']}}@endif">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Số di động</label>
                <input type="text" placeholder="Số di động" id="person_phone" name="person_phone"
                       class="form-control input-sm"
                       value="@if(isset($data['person_phone'])){{$data['person_phone']}}@endif">
            </div>
        </div>
        {!! csrf_field() !!}
        <div class="col-sm-6">
            <button class="btn btn-primary"><i class="fa fa-floppy-o"></i> Lưu lại</button>
            <button type="button" class="btn btn-warning" data-dismiss="modal" aria-hidden="true"><i class="fa fa-reply"></i> Thoát</button>
        </div>
    </div>
</div>