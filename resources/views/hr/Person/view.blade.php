<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
@extends('admin.AdminLayouts.index')
@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="{{URL::route('admin.dashboard')}}">Trang chủ</a>
            </li>
            <li class="active">Danh sách nhân sự</li>
        </ul>
    </div>

    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <div class="panel panel-info">
                    <form method="Post" action="" role="form">
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group col-sm-2">
                                <label for="user_name" class="control-label"><i>Họ tên nhân sự</i></label>
                                <input type="text" class="form-control input-sm" id="person_name" name="person_name" @if(isset($dataSearch['person_name']))value="{{$dataSearch['person_name']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_email"><i>Email</i></label>
                                <input type="text" class="form-control input-sm" id="person_mail" name="person_mail" placeholder="Địa chỉ email" @if(isset($dataSearch['person_mail']))value="{{$dataSearch['person_mail']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_phone"><i>Mã nhân sự</i></label>
                                <input type="text" class="form-control input-sm" id="person_code" name="person_code" placeholder="Mã nhân sự" @if(isset($dataSearch['person_code']))value="{{$dataSearch['person_code']}}"@endif>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Phòng ban</i></label>
                                <select name="person_depart_id" id="person_depart_id" class="form-control input-sm" tabindex="12" data-placeholder="Chọn phòng ban">
                                    <option value="0">--- Chọn phòng ban---</option>
                                    {!! $optionDepart !!}
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Nhân sự là Đảng viên</i></label>
                                <select name="person_is_dangvien" id="person_is_dangvien" class="form-control input-sm">
                                    {!! $optionDangVien !!}
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="user_group"><i>Loại hợp đồng</i></label>
                                <select name="person_type_contracts" id="person_type_contracts" class="form-control input-sm">
                                    {!! $optionLoaihopdong !!}
                                </select>
                            </div>
                        </div>
                        <div class="panel-footer text-right">
                            <a class="btn btn-danger btn-sm" href="{{URL::route('hr.personnelEdit',array('id' => FunctionLib::inputId(0)))}}">
                                <i class="ace-icon fa fa-plus-circle"></i>
                                Thêm mới
                            </a>
                            <button class="btn btn-warning btn-sm" type="submit" name="submit" value="2"><i class="fa fa-file-excel-o"></i> Xuất excel</button>
                            <button class="btn btn-primary btn-sm" type="submit" name="submit" value="1"><i class="fa fa-search"></i> Tìm kiếm</button>
                        </div>
                    </form>
                </div>
                @if(sizeof($data) > 0)
                    <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> nhân sự @endif </div>
                    <br>
                    <table class="table table-bordered table-hover">
                        <thead class="thin-border-bottom">
                        <tr class="">
                            <th width="3%" class="text-center">STT</th>
                            <th width="8%">Chức năng</th>
                            <th width="20%">Họ tên</th>
                            <th width="5%" class="text-center">Giới tính</th>
                            <th width="10%" class="text-center">Ngày làm việc</th>
                            <th width="15%" class="text-center">Đơn vị/Bộ phận</th>
                            <th width="15%" class="text-center">Chức danh nghề nghiệp</th>
                            <th width="15%" class="text-center">Chức vụ</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $key => $item)
                            <tr class="middle">
                                <td class="text-center middle">{{ $stt+$key+1 }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            - Chọn -
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach($arrLinkEditPerson as $kl=>$val)
                                                @if(isset($val['javascript']) && $val['javascript'] >0)
                                                    @if($val['javascript'] == 1)
                                                        <?php $msg = 'Bạn có chắc muốn xóa thông tin Nhân sự này?';?>
                                                    @else
                                                        <?php $msg = 'Bạn có chắc muốn khôi phục thông tin Nhân sự này?';?>
                                                    @endif
                                                    <li><a title="{{$val['name_url']}}" href="javascript:void(0);" onclick="HR.onclickActionDeletePerson('{{$msg}}','{{URL::to('/').$val['link_url'].FunctionLib::inputId($item['person_id'])}}');"><i class="{{$val['icons']}}"></i> {{$val['name_url']}}</a></li>
                                                @else
                                                    <li><a title="{{$val['name_url']}}" href="{{URL::to('/').$val['link_url'].FunctionLib::inputId($item['person_id'])}}" @if($val['blank']==1) target="_blank"@endif><i class="{{$val['icons']}}"></i> {{$val['name_url']}}</a></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{URL::route('hr.personnelDetail',array('id' => FunctionLib::inputId($item['person_id'])))}}" title="Chi tiết nhân sự" target="_blank">
                                        {{ $item['person_name'] }}
                                    </a>
                                    <a class="viewItem" title="Chi tiết nhân sự" onclick="HR.getInfoPersonPopup('{{FunctionLib::inputId($item['person_id'])}}')">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <br/>SN: @if($item['person_birth'] != 0){{date('d-m-Y',$item['person_birth'])}}@endif
                                </td>
                                <td class="text-center middle">
                                    @if(isset($arrSex[$item['person_sex']])){{$arrSex[$item['person_sex']]}}@endif
                                </td>
                                <td class="text-center middle">
                                    @if($item['person_date_start_work'] != 0){{date('d-m-Y',$item['person_date_start_work'])}}@endif
                                </td>
                                <td class="text-center middle">
                                    @if(isset($arrDepart[$item['person_depart_id']])){{$arrDepart[$item['person_depart_id']]}}@endif
                                </td>
                                <td class="text-center middle">
                                    @if(isset($arrChucDanhNgheNghiep[$item['person_career_define_id']])){{$arrChucDanhNgheNghiep[$item['person_career_define_id']]}}@endif
                                </td>
                                <td class="text-center middle">
                                    @if(isset($arrChucVu[$item['person_position_define_id']])){{$arrChucVu[$item['person_position_define_id']]}}@endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="text-right">
                        {!! $paging !!}
                    </div>
                @else
                    <div class="alert">
                        Không có dữ liệu
                    </div>
                @endif
            </div>
        </div>
    </div><!-- /.page-content -->
</div>

@stop