<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4 class="modal-title" id="myModalLabel">Thêm mới / cập nhật quá trình lương</h4>
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
            <input type="hidden" name="salary_id" id="salary_id" value="{{$salary_id}}">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Từ tháng</label>
                        <select name="salary_month" id="salary_month"  class="form-control input-sm input-required">
                            {!! $optionMonth !!}
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Năm </label>
                        <select name="salary_year" id="salary_year" class="form-control input-sm input-required">
                            {!! $optionYears !!}
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">% lương thực hưởng</label>
                        <input type="text" id="salary_percent" name="salary_percent" class="form-control input-sm"
                               value="@if(isset($data->salary_percent)){{$data->salary_percent}}@endif">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Lương cứng</label>
                        <input type="text" id="salary_salaries" name="salary_salaries"
                               class="form-control input-sm"
                               value="@if(isset($data->salary_salaries)){{$data->salary_salaries}}@endif">
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Thang bảng lương</label>
                        <select name="salary_wage_table" id="salary_wage_table"  class="form-control input-sm input-required">
                            {!! $optionThangbangluong !!}
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Nghạch công chức </label>
                        <select name="salary_civil_servants" id="salary_civil_servants" class="form-control input-sm input-required">
                            {!! $optionNghachcongchuc !!}
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Bậc lương</label>
                        <select name="salary_wage" id="salary_wage"  class="form-control input-sm input-required">
                            {!! $optionBacluong !!}
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="name" class="control-label">Hệ số</label>
                        <input type="text" id="salary_coefficients" name="salary_coefficients"
                               class="form-control input-sm"
                               value="@if(isset($data->salary_coefficients)){{$data->salary_coefficients}}@endif">
                    </div>
                </div>

                {!! csrf_field() !!}
                <div class="col-sm-6">
                    <a class="btn btn-primary" href="javascript:void(0);" onclick="HR.submitPopupCommon('form#form_poup_ajax','salaryAllowance/postSalary','div_list_lương','submitPopup')" id="submitPopup"><i class="fa fa-floppy-o"></i> Lưu lại</a>
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