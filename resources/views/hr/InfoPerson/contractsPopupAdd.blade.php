<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">Thông tin hợp đồng</h4>
</div>
<img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/ajax-loader.gif" width="20" style="display: none" id="img_loading_district">
<div class="modal-body">
    @if(isset($infoPerson))
        <div class="span clearfix">Họ và tên:<b> {{$infoPerson->person_name}}</b> </div>
        <div class="span clearfix">Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b> </div>
        <div class="span clearfix">Số cán bộ:<b> {{$infoPerson->person_code}}</b> </div>
    @endif
</div>