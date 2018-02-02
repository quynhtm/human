<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
@extends('admin.AdminLayouts.index')
@section('content')
    <div class="main-content-inner">
        <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
            <ul class="breadcrumb">
                <li>
                    <i class="ace-icon fa fa-home home-icon"></i>
                    <a href="{{URL::route('admin.dashboard')}}">Home</a>
                </li>
                <li><a href="{{URL::route('hr.personnelView')}}"> Danh sách nhân sự</a></li>
                <li class="active">Thông tin khen thưởng</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    {{ csrf_field() }}
                    @if(isset($infoPerson))
                        <div class="span clearfix">Họ và tên:<b> {{$infoPerson->person_name}}</b> </div>
                        <div class="span clearfix">Số CMTND:<b> {{$infoPerson->person_chung_minh_thu}}</b> </div>
                        <div class="span clearfix">Số cán bộ:<b> {{$infoPerson->person_code}}</b> </div>
                    @endif

                    <div id="show_list_contracts">
                        <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> nhân sự @endif </div>
                        <br>
                        <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="">
                                <th width="3%" class="text-center">STT</th>
                                <th width="12%">Loại hợp đồng</th>
                                <th width="20%">Chế độ thanh toán(trả lương)</th>
                                <th width="10%" class="text-center">Mã hợp đồng</th>
                                <th width="10%" class="text-center">Mức lương</th>
                                <th width="10%" class="text-center">Ngày ký</th>
                                <th width="10%" class="text-center">Ngày hiệu lực</th>
                                <th width="15%" class="text-center">Thỏa thuận khác</th>
                                <th width="10%" class="text-center">Thao tác</th>
                            </tr>
                            </thead>
                            @if(sizeof($contracts) > 0)
                                <tbody>
                                @foreach ($contracts as $key => $item)
                                    <tr>
                                        <td class="text-center middle">{{ $key+1 }}</td>
                                        <td class="text-center middle">{{ $item['contracts_type_define_name'] }}</td>
                                        <td class="text-center middle">{{ $item['contracts_payment_define_name'] }}</td>
                                        <td class="text-center middle">{{$item['contracts_code']}}</td>
                                        <td class="text-center middle">{{ number_format($item['contracts_money'])}}</td>
                                        <td class="text-center middle">{{date('d-m-Y',$item['contracts_sign_day'])}}</td>
                                        <td class="text-center middle">{{date('d-m-Y',$item['contracts_effective_date'])}}</td>
                                        <td class="text-center middle">{{$item['contracts_describe']}}</td>
                                        <td class="text-center middle">
                                            @if($is_root== 1 || $personContracts_full== 1 || $personContracts_create == 1)
                                                    <a href="#" onclick="HR.getInfoContractsPerson('{{FunctionLib::inputId($item['contracts_person_id'])}}','{{FunctionLib::inputId($item['contracts_id'])}}')"
                                                       title="Sửa"><i class="fa fa-edit fa-2x"></i></a>
                                            @endif
                                            @if($is_root== 1 || $personContracts_full== 1 || $personContracts_delete == 1)
                                                <a class="deleteItem" title="Xóa" onclick="HR.deleteComtracts('{{FunctionLib::inputId($item['contracts_person_id'])}}','{{FunctionLib::inputId($item['contracts_id'])}}')"><i class="fa fa-trash fa-2x"></i></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <td colspan="8"> Chưa có dữ liệu</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    <a class="btn btn-success" href="#" onclick="HR.getInfoContractsPerson('{{FunctionLib::inputId($person_id)}}','{{FunctionLib::inputId(0)}}')"><i class="fa fa-reply"></i> Thêm mới khen thưởng</a>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
@stop