<?php use App\Library\AdminFunction\CGlobal; ?>
<?php use App\Library\AdminFunction\Define; ?>
<?php use App\Library\AdminFunction\FunctionLib; ?>
@extends('admin.AdminLayouts.index')
@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                {{FunctionLib::viewLanguage('home')}}
            </li>
        </ul>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box-header">
                    <h3 class="box-title" style="text-align: center; color:#438eb9">{{CGlobal::web_title_dashboard}} </h3>
                </div>
                @if(isset($error) && !empty($error))
                    <div class="alert alert-danger" role="alert">
                        @foreach($error as $itmError)
                            <p><b>{{ $itmError }}</b></p>
                        @endforeach
                    </div>
                @endif
                <div class="box-body" style="margin-top: 35px">
                    @if(!empty($menu))
                        @foreach($menu as $item)
                            @if(isset($item['sub']) && !empty($item['sub']))
                                @foreach($item['sub'] as $sub)
                                    @if($is_boss || (!empty($aryPermissionMenu) && in_array($sub['menu_id'],$aryPermissionMenu) && $sub['show_menu'] == 1))
                                        @if(isset($sub['showcontent']) && $sub['showcontent'] == 1)
                                            <div class="col-sm-6 col-md-3">
                                                <a class="quick-btn a_control"  href="{{ URL::route($sub['RouteName']) }}">
                                                    <div class="thumbnail text-center">
                                                        <i class="{{ $sub['icon'] }} fa-5x"></i>
                                                        <br>
                                                        @if(isset($languageSite) && $languageSite == Define::VIETNAM_LANGUAGE)
                                                            {{ $sub['name'] }}
                                                        @else
                                                            {{ $sub['name_en'] }}
                                                        @endif
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                        @if(isset($sub['clear']) && $sub['clear'] == 1)
                                            <div class="clear"></div>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                 </div>
            </div>
            @if(count($listLink)>0)
            <div class="clear"></div>
            <hr style="border-top: 1px solid #ccc; width: 90%; height: 1px; margin: auto"/>
            <div class="col-xs-12">
                <ul style="list-style: none; font-size: 18px; padding: 20px 0;">
                    @foreach($listLink as $kl=>$val)
                        <li style="width: 45%; display: inline-block"><a title="{{$val['name_url']}}" href="{{URL::to('/').$val['link_url']}}" @if($val['blank']==1) target="_blank"@endif>{{$val['name_url']}}</a></li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</div>
@stop