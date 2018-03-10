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
                <li class="active">Chi tiết nhân sự</li>
            </ul>
        </div>

        <div class="page-content">
            <div class="row">
                <div class="col-xs-12">
                    <!-- PAGE CONTENT BEGINS -->
                    {{ csrf_field() }}
                    <div class="marginTop20">
                        <div class="block_title">KHEN THƯỞNG</div>
                        <div id="div_list_khenthuong">
                            <table class="table table-bordered table-hover">
                                <thead class="thin-border-bottom">
                                <tr class="">
                                    <th width="20%">Thành tích</th>
                                    <th width="10%" class="text-center">Năm đạt</th>
                                    <th width="20%" class="text-center">Quyết định đính kèm</th>
                                    <th width="10%" class="text-center">Thưởng</th>
                                    <th width="30%" class="text-center">Ghi chú</th>
                                </tr>
                                </thead>
                                @if(sizeof($khenthuong) > 0)
                                    <tbody>
                                    @foreach ($khenthuong as $key => $item)
                                        <tr>
                                            <td>@if(isset($arrTypeKhenthuong[$item['bonus_define_id']])){{ $arrTypeKhenthuong[$item['bonus_define_id']] }}@endif</td>
                                            <td class="text-center middle"> {{ $item['bonus_year'] }}</td>
                                            <td class="text-center middle">{{$item['bonus_decision']}}</td>
                                            <td class="text-center middle">{{ number_format($item['bonus_number'])}}</td>
                                            <td class="text-center middle">{{$item['bonus_note']}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                @else
                                    <tr>
                                        <td colspan="7"> Chưa có dữ liệu</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    {{----danh hiệu--}}
                    <div class="marginTop20">
                        <div class="block_title">DANH HIỆU</div>
                        <div id="div_list_danhhieu">
                            <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="">
                                <th width="30%">Danh hiệu</th>
                                <th width="10%" class="text-center">Năm đạt</th>
                                <th width="20%" class="text-center">Quyết định đính kèm</th>
                                <th width="30%" class="text-center">Ghi chú</th>
                            </tr>
                            </thead>
                            @if(sizeof($danhhieu) > 0)
                                <tbody>
                                @foreach ($danhhieu as $key2 => $item2)
                                    <tr>
                                        <td>@if(isset($arrTypeDanhhieu[$item2['bonus_define_id']])){{ $arrTypeDanhhieu[$item2['bonus_define_id']] }}@endif</td>
                                        <td class="text-center middle"> {{ $item2['bonus_year'] }}</td>
                                        <td class="text-center middle">{{$item2['bonus_decision']}}</td>
                                        <td class="text-center middle">{{$item2['bonus_note']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <td colspan="7"> Chưa có dữ liệu</td>
                                </tr>
                            @endif
                        </table>
                        </div>
                    </div>

                    {{----Kỷ luật--}}
                    <div class="marginTop20">
                        <div class="block_title">KỶ LUẬT</div>
                        <div id="div_list_kyluat">
                            <table class="table table-bordered table-hover">
                            <thead class="thin-border-bottom">
                            <tr class="">
                                <th width="30%">Hình thức</th>
                                <th width="10%" class="text-center">Năm bị kỷ luật</th>
                                <th width="20%" class="text-center">Quyết định đính kèm</th>
                                <th width="30%" class="text-center">Ghi chú</th>
                            </tr>
                            </thead>
                            @if(sizeof($kyluat) > 0)
                                <tbody>
                                @foreach ($kyluat as $key3 => $item3)
                                    <tr>
                                        <td>@if(isset($arrTypeKyluat[$item3['bonus_define_id']])){{ $arrTypeKyluat[$item3['bonus_define_id']] }}@endif</td>
                                        <td class="text-center middle">{{ $item3['bonus_year'] }}</td>
                                        <td class="text-center middle">{{$item3['bonus_decision']}}</td>
                                        <td class="text-center middle">{{$item3['bonus_note']}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @else
                                <tr>
                                    <td colspan="7"> Chưa có dữ liệu</td>
                                </tr>
                            @endif
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.page-content -->
    </div>
@stop