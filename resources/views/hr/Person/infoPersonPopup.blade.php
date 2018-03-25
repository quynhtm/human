<?php
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;
use App\Library\PHPThumb\ThumbImg;
use App\Library\AdminFunction\CGlobal;
?>

<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Thông tin nhân sự</h4>
    </div>

    <div class="modal-body" style="height: 300px">
        <div class="float_left col-sm-3">
            <div style="width: 200px; height: 250px; overflow: hidden">
                @if($infoPerson->person_avatar != '')
                    <img src="{{ThumbImg::thumbBaseNormal(Define::FOLDER_PERSONAL, $infoPerson->person_avatar, Define::sizeImage_240, Define::sizeImage_300, '', true, true)}}"/>
                @else
                    <img src="{{Config::get('config.WEB_ROOT')}}assets/admin/img/icon/no-profile-image.gif"/>
                @endif
            </div>
        </div>

        <div class="float_left col-sm-3">
            <div class="form-group">
                Họ và tên: <span class="color_msg">{{ $infoPerson->person_name }}</span>
            </div>
            <div class="form-group">
                Điện thoại: <span class="color_msg">{{ $infoPerson->person_name }}</span>
            </div>
                <div class="form-group">
                Email: <span class="color_msg">{{ $infoPerson->person_name }}</span>
                </div>
            <div class="form-group">
                Số CMT: <span class="color_msg">{{ $infoPerson->person_name }}</span>
            </div>
            <div class="form-group">
                Ngày cấp: <span class="color_msg">{{ $infoPerson->person_name }}</span>
            </div>
            <div class="form-group">
                Nơi cấp: <span class="color_msg">{{ $infoPerson->person_name }}</span>
            </div>
        </div>

        <div class="float_left col-sm-3">
            <div class="form-group">
            Ngày nâng lương: <span class="color_msg">{{ $infoPerson->person_code }}</span>
            </div>
            <div class="form-group">
                Nghạch bậc: <span class="color_msg">{{ $infoPerson->person_code }}</span>
            </div>
            <div class="form-group">
                Hệ số lương: <span class="color_msg">{{ $infoPerson->person_code }}</span>
            </div>
            <div class="form-group">
                Phụ cấp: <span class="color_msg">{{ $infoPerson->person_code }}</span>
            </div>
            <div class="form-group">
                Mã số thuế: <span class="color_msg">{{ $infoPerson->person_code }}</span>
            </div>
        </div>

        <div class="float_left col-sm-3">
            <div class="form-group">
                Chức danh KHCN: <span class="color_msg">{{ $infoPerson->person_code }}</span>
            </div>
            <div class="form-group">
                Cấp ủy hiện tại, cấp ủy kiêm: <span class="color_msg">{{ $infoPerson->person_code }}</span>
            </div>
            <div class="form-group">
                Nơi ở hiện nay: <span class="color_msg">{{ $infoPerson->person_code }}</span>
            </div>
            <div class="form-group">
                Lý luận chính trị: <span class="color_msg">{{ $infoPerson->person_code }}</span>
            </div>
            <div class="form-group">
                Hộ chiếu phổ thông: <span class="color_msg">{{ $infoPerson->person_code }}</span>
            </div>
            <div class="form-group">
                Ngày hết hạn: <span class="color_msg">{{ $infoPerson->person_code }}</span>
            </div>
        </div>
    </div>
</div>

