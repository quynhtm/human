<?php
use App\Library\AdminFunction\FunctionLib;
use App\Library\AdminFunction\Define;
use App\Http\Models\Hr\Salary;
use App\Http\Models\Hr\Allowance;
use App\Http\Models\Hr\Person;
?>

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
                <li class="active">Báo cáo danh sách và tiền lương công chức {{isset($search['reportYear']) ? $search['reportYear'] : ''}}</li>
            </ul>
        </div>
        <div class="page-content">
            <div class="panel panel-default">
                {{ csrf_field() }}
                <div class="panel-body-ns">
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <div class="line">
                                <div class="panel-body">
                                    @if($is_root == 1 || $personExportTienLuongCongChuc == 1)
                                    <div class="col-md-2 pull-right">
                                        <div class="row">
                                            <div class="input-group-btn" style="text-align: right;">
                                                <a href="{{URL::route('hr.viewTienLuongCongChuc')}}" class="btn btn-warning btn-sm exportViewTienLuongCongChuc">
                                                    <i class="fa fa-file-excel-o"></i> Xuất ra file
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> nhân sự @endif </div>
                                            <br>
                                            <div class="listPayroll">
                                                <table style="width: 100%;" class="table table-bordered table-condensed">
                                                    <tbody>
                                                    <tr class="text-center">
                                                        <th rowspan="4" class="text-center">TT</th>
                                                        <th rowspan="4" class="text-center" width="35%">Họ và tên <br/>/Lương tháng</th>
                                                        <th rowspan="4" class="text-center" width="7%">Mã số ngạch lương</th>

                                                        <th colspan="9" class="text-center">Lương hệ số</th>

                                                        <th rowspan="4" class="text-center" width="5%">Cộng hệ số</th>
                                                        <th rowspan="4" class="text-center" width="7%">Lương cơ bản hiện hành</th>
                                                        <th rowspan="4" class="text-center" width="7%">Thành tiền</th>
                                                        <th rowspan="4" class="text-center" width="7%">Tổng tiền lương và BHXH được hưởng</th>
                                                        <th rowspan="4" class="text-center" width="7%">Các khoản trừ vào lương (BHXH)</th>
                                                        <th rowspan="4" class="text-center" width="7%">Tổng tiền lương thực nhận</th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <th rowspan="3" class="text-center" width="5%">Hệ số lương</th>
                                                        <th rowspan="3" class="text-center" width="5%">Hệ số phụ cấp chức vụ</th>
                                                        <th colspan="7" class="text-center">Hệ số phụ cấp khác</th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <th colspan="2" class="text-center">Phụ cấp thâm niên vượt khung</th>
                                                        <th rowspan="2" class="text-center">Phụ cấp trách nhiệm</th>
                                                        <th colspan="2" class="text-center">Phụ cấp thâm niên</th>
                                                        <th colspan="2" class="text-center">Phụ cấp ngành</th>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <th class="text-center" width="5%">%</th>
                                                        <th class="text-center" width="5%">Hệ số</th>
                                                        <th class="text-center" width="5%">%</th>
                                                        <th class="text-center" width="5%">Hệ số</th>
                                                        <th class="text-center" width="5%">%</th>
                                                        <th class="text-center" width="5%">Hệ số</th>
                                                    </tr>
                                                    <tr class="text-center" style="background-color: #62A8D1">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>

                                                        <td>1</td>
                                                        <td>2</td>
                                                        <td>3</td>
                                                        <td>4=1*3</td>
                                                        <td>5</td>

                                                        <td>6</td>
                                                        <td>7=(1+2 +4)*6</td>
                                                        <td>8</td>
                                                        <td>9=1*8</td>
                                                        <td>10=1+2+ 4+5+7+9</td>

                                                        <td>11</td>
                                                        <td>12=10*11</td>
                                                        <td>13=12</td>
                                                        <td>14(*)</td>
                                                        <td>15=13-14</td>
                                                    </tr>
                                                    <tr class="text-center" style="background-color: #62A8D1">
                                                        <td colspan="18" class="text-center">(*) 14= (1+2+4+5+7)*11*0.105 (10.5% BHXH + BHYT + BHTN)</td>
                                                    </tr>
                                                    @if(sizeof($data) > 0)
                                                        @foreach($data as $k=>$item)
                                                            <tr>
                                                                <td>{{$stt+$k+1}}</td>
                                                                <td class="text-left">
                                                                    {{isset($infoPerson->person_name) ? $infoPerson->person_name : ''}}
                                                                    <br/>
                                                                    @if($item->payroll_month > 0 && $item->payroll_year > 0)
                                                                        {{$item->payroll_month}}/{{$item->payroll_year}}
                                                                    @endif
                                                                </td>
                                                                <td>{{isset($arrWage[$item->ma_ngach]) ? $arrWage[$item->ma_ngach] : ''}}</td>
                                                                <td>{{$item->he_so_luong}}</td>
                                                                <td>{{$item->phu_cap_chuc_vu}}</td>
                                                                <td>{{$item->phu_cap_tham_nien_vuot}}</td>
                                                                <td>{{$item->phu_cap_tham_nien_vuot_heso}}</td>
                                                                <td>{{$item->phu_cap_trach_nhiem}}</td>

                                                                <td>{{$item->phu_cap_tham_nien}}</td>
                                                                <td>{{$item->phu_cap_tham_nien_heso}}</td>
                                                                <td>{{$item->phu_cap_nghanh}}</td>
                                                                <td>{{$item->phu_cap_nghanh_heso}}</td>
                                                                <td>{{$item->tong_he_so}}</td>

                                                                <td>{{FunctionLib::numberFormat($item->luong_co_so)}}</td>
                                                                <td>{{FunctionLib::numberFormat($item->tong_tien)}}</td>
                                                                <td>{{FunctionLib::numberFormat($item->tong_tien_luong)}}</td>
                                                                <td>{{FunctionLib::numberFormat($item->tong_tien_baohiem)}}</td>
                                                                <td>{{FunctionLib::numberFormat($item->tong_luong_thuc_nhan)}}</td>
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
    </div>
@stop