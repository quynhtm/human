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

                    <div class="clear"></div>
                    <div class="col-xs-4">
                        <div class="box-body widget-box bd-blue" style="min-height: 144px; border: 1px solid #6fb3e0; border-radius: 5px">
                            <div class="widget-header widget-header-flat infobox-blue infobox-dark" style="margin: 0px!important;">
                                <h4 class="widget-title ng-binding">
                                    <i class="icon-tags"></i>
                                    Danh sách nhân sự
                                </h4>
                            </div>

                            <div class="widget-body">
                                <ul class="link-dash">
                                    @foreach(\App\Library\AdminFunction\CGlobal::$arrLinkListDash as $kl=>$val)
                                        <li><a title="{{$val['name_url']}}" href="{{URL::to('/').$val['link_url']}}" @if($val['blank']==1) target="_blank"@endif>{{$val['name_url']}}@if(isset($arrNotify[$val['cacheNotify']]) && $arrNotify[$val['cacheNotify']] > 0)<b style="color: red"> ({{$arrNotify[$val['cacheNotify']]}})</b> @endif</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-4">
                        <div class="box-body widget-box bd-orange " style="min-height: 144px; border: 1px solid #e8b110; border-radius: 5px">
                            <div class="widget-header widget-header-flat infobox-orange  infobox-dark" style="margin: 0px!important;">
                                <h4 class="widget-title ng-binding">
                                    <i class="icon-tags"></i>
                                    Danh sách nhân sự
                                </h4>
                            </div>

                            <div class="widget-body">
                                <ul class="link-dash">
                                    @foreach(\App\Library\AdminFunction\CGlobal::$arrLinkListDash_2 as $kl=>$val)
                                        <li><a title="{{$val['name_url']}}" href="{{URL::to('/').$val['link_url']}}" @if($val['blank']==1) target="_blank"@endif>{{$val['name_url']}}@if(isset($arrNotify[$val['cacheNotify']]) && $arrNotify[$val['cacheNotify']] > 0)<b style="color: red"> ({{$arrNotify[$val['cacheNotify']]}})</b> @endif</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-4">
                        <div class="box-body widget-box bd-green" style="min-height: 144px; border: 1px solid #9abc32; border-radius: 5px">
                            <div class="widget-header widget-header-flat infobox-green infobox-dark" style="margin: 0px!important;">
                                <h4 class="widget-title ng-binding">
                                    <i class="icon-tags"></i>
                                    Danh sách nhân sự
                                </h4>
                            </div>

                            <div class="widget-body">
                                <ul class="link-dash">
                                    @foreach(\App\Library\AdminFunction\CGlobal::$arrLinkListDash_3 as $kl=>$val)
                                        <li><a title="{{$val['name_url']}}" href="{{URL::to('/').$val['link_url']}}" @if($val['blank']==1) target="_blank"@endif>{{$val['name_url']}}@if(isset($arrNotify[$val['cacheNotify']]) && $arrNotify[$val['cacheNotify']] > 0)<b style="color: red"> ({{$arrNotify[$val['cacheNotify']]}})</b> @endif</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</div>
@stop