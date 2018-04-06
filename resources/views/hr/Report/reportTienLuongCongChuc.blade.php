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
                <li class="active">Quản lý lương</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="panel panel-default">
                {{ csrf_field() }}
                <div class="panel-body-ns">
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <div class="line">
                                <div class="panel-heading clearfix">
                                    <h4 class="panel-title pull-left">BÁO CÁO DANH SÁCH VÀ TIỀN LƯƠNG CÔNG CHỨC {{isset($search['reportYear']) ? $search['reportYear'] : ''}}</h4>
                                    <div class="btn-group btn-group-sm pull-right">
                                        <span>
                                            <a href="{{URL::route('hr.viewTienLuongCongChuc')}}" class="btn btn-default btn-sm exportViewTienLuongCongChuc">
                                                <i class="fa fa-file-excel-o"></i> Xuất ra file</a>
                                        </span>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <form class="form-horizontal" action="" method="get" id="adminFormExportViewTienLuongCongChuc" name="adminFormExportViewTienLuongCongChuc">
                                        <div class="form-group">
                                            <div class="col-md-4">
                                                <label>Chọn Đơn vị/ Phòng ban</label>
                                                <select class="form-control input-sm" name="person_depart_id">
                                                    <option value="">- Đơn vị/ Phòng ban -</option>
                                                   {!! $optionDepart !!}
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Chọn năm báo cáo</label>
                                                <select class="required form-control input-sm" name="reportYear">
                                                    <option value="">- Chọn năm báo cáo -</option>
                                                    {!! $optionYear !!}
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label>&nbsp;</label>
                                                <div class="input-group-btn">
                                                    <button class="btn btn-primary btn-sm clickFormReportLuong" type="submit"><i class="fa fa-area-chart"></i>&nbsp;Thống kê</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> thiết bị @endif </div>
                                            <br>
                                            <table style="width: 100%;" class="table table-bordered table-condensed">
                                                <tbody>
                                                <tr class="text-center">
                                                    <th rowspan="2">TT</th>
                                                    <th rowspan="2">Họ Và tên</th>
                                                    <th colspan="2">Ngày, tháng năm sinh</th>
                                                    <th rowspan="2">Chức vụ hoặc chức danh công tác</th>
                                                    <th rowspan="2">Cơ quan, đơn vị đang làm việc</th>
                                                    <th rowspan="2">Thời gian giữ ngạch (kể cả ngạch tương đương)</th>
                                                    <th colspan="2">Mức lương hiện hưởng</th>
                                                    <th colspan="5">Phụ cấp</th>
                                                    <th rowspan="2">Ghi chú</th>
                                                </tr>
                                                <tr class="text-center">
                                                    <th>Nam</th>
                                                    <th>Nữ</th>
                                                    <th>Hệ số lương</th>
                                                    <th>Mã ngạch</th>
                                                    <th>Chức vụ</th>
                                                    <th>Trách nhiệm</th>
                                                    <th>Khu vực</th>
                                                    <th>Phụ cấp vượt khung</th>
                                                    <th>Tổng phụ cấp</th>
                                                </tr>
                                                @if(sizeof($data) > 0)
                                                @foreach($data as $k => $item)
                                                <tr>
                                                    <td>{{$stt+$k+1}}</td>
                                                    <td class="text-nowrap">{{$item->person_name}}</td>
                                                    <?php
                                                        $person_birth = (isset($item->person_birth) && $item->person_birth > 0) ? $item->person_birth : 0;
                                                    ?>
                                                    <td>{{(isset($item->person_sex) && $item->person_sex == 1 && $person_birth > 0) ? date('d/m/Y', $person_birth)  : ''}}</td>
                                                    <td>{{(isset($item->person_sex) && $item->person_sex == 0 && $person_birth > 0) ? date('d/m/Y', $person_birth)  : ''}}</td>
                                                    <td>@if(isset($arrChucVu[$item['person_position_define_id']])){{$arrChucVu[$item['person_position_define_id']]}}@endif</td>
                                                    <td>@if(isset($arrDepart[$item['person_depart_id']])){{$arrDepart[$item['person_depart_id']]}}@endif</td>

                                                    <td>01/01/2016</td>
                                                    <td class="text-right">4.4</td>
                                                    <td>01.002</td>

                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td class="text-right">0</td>
                                                    <td></td>
                                                </tr>
                                                @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop