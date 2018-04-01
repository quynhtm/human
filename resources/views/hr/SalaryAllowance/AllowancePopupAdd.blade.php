<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">Thêm mới / cập nhật phụ cấp</h4>
</div>
<img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/ajax-loader.gif" width="20" style="display: none" id="img_loading_district">
<div class="modal-body">
    @if(isset($infoPerson))
        <span class="span">Họ và tên:<b> {{$infoPerson->person_name}}</b></span>
        <span class="span">&nbsp;&nbsp;&nbsp;Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b></span>
        <span class="span">&nbsp;&nbsp;&nbsp;Số cán bộ:<b> {{$infoPerson->person_code}}</b></span>
    @endif
    <hr>
    <form method="POST" action="" role="form" id="form_poup_ajax">
        <input type="hidden" name="person_id" id="person_id" value="{{$person_id}}">
        <input type="hidden" name="allowance_id" id="allowance_id" value="{{$allowance_id}}">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="name" class="control-label">Chọn loại phụ cấp</label>
                    <select name="allowance_type" id="allowance_type"  class="form-control input-sm input-required">
                        {!! $optionAllowanceType !!}
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="name" class="control-label">Từ tháng</label>
                    <select name="allowance_month_start" id="allowance_month_start"  class="form-control input-sm input-required">
                        {!! $optionMonth2 !!}
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="name" class="control-label">Năm hưởng</label>
                    <select name="allowance_year_start" id="allowance_year_start" class="form-control input-sm input-required">
                        {!! $optionYears2 !!}
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="name" class="control-label">Đến tháng</label>
                    <select name="allowance_month_end" id="allowance_month_end"  class="form-control input-sm input-required">
                        {!! $optionMonth3 !!}
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="name" class="control-label">Năm kết thúc</label>
                    <select name="allowance_year_end" id="allowance_year_end" class="form-control input-sm input-required">
                        {!! $optionYears3 !!}
                    </select>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="form-group">
                    <label for="name" class="control-label col-sm-12 text-left textBold" style="text-align: left!important;">Phụ cấp trả theo hình thức</label>
                    <input type="radio" name="allowance_method_type" value="{{\App\Library\AdminFunction\Define::allowance_method_type_1}}" @if(isset($data['allowance_method_type']) && $data['allowance_method_type'] == Define::allowance_method_type_1) checked @endif > Phụ cấp trọn gói &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="allowance_method_type" value="{{\App\Library\AdminFunction\Define::allowance_method_type_2}}" @if(isset($data['allowance_method_type']) && $data['allowance_method_type'] == Define::allowance_method_type_2) checked @endif> Phụ cấp bằng % lương &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="allowance_method_type" value="{{\App\Library\AdminFunction\Define::allowance_method_type_3}}" @if(isset($data['allowance_method_type']) && $data['allowance_method_type'] == Define::allowance_method_type_3) checked @endif> Phụ cấp theo hệ số
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="name" class="control-label">Phụ cấp bằng tiền</label>
                    <input type="text" id="allowance_method_value_1" name="allowance_method_value_1"
                           class="form-control input-sm" value="@if(isset($data['allowance_method_value']) && isset($data['allowance_method_type']) && $data['allowance_method_type'] == Define::allowance_method_type_1){{$data['allowance_method_value']}}@endif">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="name" class="control-label">Phụ cấp bằng % lương</label>
                    <input type="text" id="allowance_method_value_2" name="allowance_method_value_2"
                           class="form-control input-sm" value="@if(isset($data['allowance_method_value']) && isset($data['allowance_method_type']) && $data['allowance_method_type'] == Define::allowance_method_type_2){{$data['allowance_method_value']}}@endif">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="name" class="control-label">Phụ cấp theo hệ số</label>
                    <input type="text" id="allowance_method_value_3" name="allowance_method_value_3"
                           class="form-control input-sm" value="@if(isset($data['allowance_method_value']) && isset($data['allowance_method_type']) && $data['allowance_method_type'] == Define::allowance_method_type_3){{$data['allowance_method_value']}}@endif">
                </div>
            </div>

            {!! csrf_field() !!}
            <div class="col-sm-6">
                <a class="btn btn-primary" href="javascript:void(0);" onclick="HR.submitPopupCommon('form#form_poup_ajax','salaryAllowance/postAllowance','div_list_phucap','submitPopup')" id="submitPopup"><i class="fa fa-floppy-o"></i> Lưu lại</a>
                <button type="button" class="btn btn-warning" data-dismiss="modal" aria-hidden="true"><i class="fa fa-reply"></i> Thoát</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        //var contracts_sign_day = $('#contracts_sign_day').datepicker({ });
        //var contracts_effective_date = $('#contracts_effective_date').datepicker({ });
    });
</script>