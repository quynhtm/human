<?php
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;
?>
<div class="main-content-inner">
    <div class="page-content">
        <div class="line">
            <div class="panel panel-primary">
                <div class="panel-body">
                    <div class="col-md-12">
                        <form id="adminForm" name="adminForm adminFormDevidetAdd" method="post" enctype="multipart/form-data" action="{{URL::route('hr.HrMailEdit')}}/{{FunctionLib::inputId($data['hr_mail_id'])}}" novalidate="novalidate">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Người nhận</label>
                                        <div class="multipleSelectRecive" multiple style="display: none">
                                            <?php
                                            $hr_mail_person_recive_list = isset($data['hr_mail_person_recive_list']) ? explode(',', $data['hr_mail_person_recive_list']) : array();
                                            ?>
                                            @foreach($arrUser as $k=>$val)
                                                <option value="{{$k}}" @if(in_array($k, $hr_mail_person_recive_list)) selected="selected" @endif>{{$val}}</option>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>CC</label>
                                        <div class="multipleSelectCC" multiple style="display: none">
                                            <?php
                                            $hr_mail_send_cc = isset($data['hr_mail_send_cc']) ? explode(',', $data['hr_mail_send_cc']) : array();
                                            ?>
                                            @foreach($arrUser as $k=>$val)
                                                <option value="{{$k}}" @if(in_array($k, $hr_mail_send_cc)) selected="selected" @endif>{{$val}}</option>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Chủ đề</label>
                                        <input class="form-control input-sm input-required" title="Tên thư, tin nhắn" id="hr_mail_name" name="hr_mail_name" @isset($data['hr_mail_name'])value="{{$data['hr_mail_name']}}"@endif type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nội dung</label>
                                        <textarea class="form-control input-sm input-required" name="hr_mail_content" id="hr_mail_content" cols="30" rows="5">@isset($data['hr_mail_content']){{$data['hr_mail_content']}}@endif</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label">&nbsp;</label>
                                        <div class="controls">
                                            <a href="javascript:;"class="btn btn-primary link-button" onclick="baseUpload.uploadDocumentAdvanced(9);">Tải tệp đính kèm</a>
                                            <div id="sys_show_file">
                                                @if(isset($data['hr_mail_files']) && $data['hr_mail_files'] !='')
                                                    <?php $arrfiles = ($data['hr_mail_files'] != '') ? unserialize($data['hr_mail_files']) : array(); ?>
                                                    @foreach($arrfiles as $_key=>$file)
                                                        <div class="item-file item_{{$_key}}"><a target="_blank" href="{{Config::get('config.WEB_ROOT').'uploads/'.Define::FOLDER_MAIL.'/'.$id.'/'.$file}}">{{$file}}</a><span data="{{$file}}" class="remove_file" onclick="baseUpload.deleteDocumentUpload('{{FunctionLib::inputId($id)}}', {{$_key}}, '{{$file}}',9)">X</span></div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! csrf_field() !!}
                                    <button type="submit" class="btn btn-success btn-sm submitMailSend"><i class="fa fa-save"></i>&nbsp;Gửi</button>
                                    <button type="submit" class="btn btn-success btn-sm submitMailDraft"><i class="fa fa-save"></i>&nbsp;Lưu nháp</button>
                                    <input id="id_hiden" name="id_hiden" @isset($data['hr_mail_id'])rel="{{$data['hr_mail_id']}}" value="{{FunctionLib::inputId($data['hr_mail_id'])}}" @else rel="0" value="{{FunctionLib::inputId(0)}}" @endif type="hidden">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Popup Upload File-->
<div class="modal fade" id="sys_PopupUploadDocumentOtherPro" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Tải tệp đính kèm</h4>
            </div>
            <div class="modal-body">
                <form name="uploadImage" method="post" action="#" enctype="multipart/form-data">
                    <div class="form_group">
                        <div id="sys_show_button_upload_file">
                            <div id="sys_mulitplefileuploaderFile" class="btn btn-primary">Tải tệp đính kèm</div>
                        </div>
                        <div id="status_file"></div>

                        <div class="clearfix"></div>
                        <div class="clearfix" style='margin: 5px 10px; width:100%;'>
                            <div id="div_image_file"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Popup Upload File-->