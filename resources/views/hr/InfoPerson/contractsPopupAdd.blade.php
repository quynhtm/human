<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">Thông tin hợp đồng</h4>
</div>
<img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/ajax-loader.gif" width="20" style="display: none" id="img_loading_district">
<div class="modal-body">
    @if(isset($infoPerson))
        <span class="span">Họ và tên:<b> {{$infoPerson->person_name}}</b></span>
        <span class="span">&nbsp;&nbsp;&nbsp;Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b></span>
        <span class="span">&nbsp;&nbsp;&nbsp;Số cán bộ:<b> {{$infoPerson->person_code}}</b></span>
    @endif
    <hr>
    <form method="POST" action="" role="form" id="form_contracts">
        <input type="hidden" name="person_id" id="person_id" value="{{$person_id}}">
        <input type="hidden" name="contracts_id" id="contracts_id" value="{{$contracts_id}}">
        <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Loại hợp đồng<span class="red"> (*) </span></label>
                <select name="contracts_type_define_id" id="contracts_type_define_id" title = 'Loại hợp đồng' class="form-control input-sm input-required">
                    {!! $optionShow !!}
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Chế độ thanh toán (Trả lương)<span class="red"> (*) </span></label>
                <select name="contracts_payment_define_id" title = 'Chế độ thanh toán (Trả lương)' id="contracts_payment_define_id" class="form-control input-sm input-required">
                    {!! $optionShow !!}
                </select>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Mã hợp đồng/ số quyết định</label>
                <input type="text" id="contracts_code" name="contracts_code" class="form-control input-sm"
                       value="@if(isset($contracts->contracts_code)){{$contracts->contracts_code}}@endif">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Mức lương</label>
                <input type="text" id="contracts_money" name="contracts_money"
                       class="form-control input-sm" value="@if(isset($contracts->contracts_money)){{$contracts->contracts_money}}@endif">
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Ngày ký<span class="red"> (*) </span></label>
                <input type="text" class="form-control input-required" title = 'Ngày ký' id="contracts_sign_day" name="contracts_sign_day"  data-date-format="dd-mm-yyyy" value="@if(isset($contracts->contracts_sign_day)){{date('d-m-Y',$contracts->contracts_sign_day)}}@endif">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="name" class="control-label">Ngày có hiệu lực<span class="red"> (*) </span></label>
                <input type="text" class="form-control input-required" title = 'Ngày có hiệu lực' id="contracts_effective_date" name="contracts_effective_date"  data-date-format="dd-mm-yyyy" value="@if(isset($contracts->contracts_effective_date)){{date('d-m-Y',$contracts->contracts_effective_date)}}@endif">
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="name" class="control-label">Thỏa thuận khác</label>
                <input type="text" id="contracts_describe" name="contracts_describe"
                       class="form-control input-sm"
                       value="@if(isset($contracts->contracts_describe)){{$contracts->contracts_describe}}@endif">
            </div>
        </div>
        {!! csrf_field() !!}
        <div class="col-sm-6">
            <a class="btn btn-primary" href="javascript:void(0);" onclick="HR.contractsSubmit('form#form_contracts','form#form_contracts','submitContracts')" id="submitContracts"><i class="fa fa-floppy-o"></i> Lưu lại</a>
            <button type="button" class="btn btn-warning" data-dismiss="modal" aria-hidden="true"><i class="fa fa-reply"></i> Thoát</button>
        </div>
    </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        var contracts_sign_day = $('#contracts_sign_day').datepicker({ });
        var contracts_effective_date = $('#contracts_effective_date').datepicker({ });
    });
</script>