<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
<div class="span clearfix"> @if($total >0) Có tổng số <b>{{$total}}</b> {{$nameTem}} @endif </div>
<table class="table table-bordered table-hover">
    <thead class="thin-border-bottom">
    <tr class="">
        <th width="5%" class="text-center">STT</th>
        <th width="20%">Thành tích</th>
        <th width="10%" class="text-center">Năm đạt</th>
        <th width="20%" class="text-center">Quyết định đính kèm</th>
        <th width="10%" class="text-center">Thưởng</th>
        <th width="30%" class="text-center">Ghi chú</th>
        <th width="5%" class="text-center">Xóa</th>
    </tr>
    </thead>
    @if(sizeof($dataList) > 0)
        <tbody>
        @foreach ($dataList as $key => $item)
            <tr>
                <td class="text-center middle">{{ $key+1 }}</td>
                <td>{{ $item['bonus_define_id'] }}</td>
                <td class="text-center middle"> @if($item['bonus_year'] != ''){{date('d-m-Y',$item['bonus_year'])}}@endif</td>
                <td class="text-center middle">{{$item['bonus_decision']}}</td>
                <td class="text-center middle">{{ number_format($item['bonus_number'])}}</td>
                <td class="text-center middle">{{$item['bonus_note']}}</td>
                <td class="text-center middle">
                    @if($is_root== 1 || $personContracts_full== 1 || $personContracts_delete == 1)
                        <a class="deleteItem" title="Xóa" onclick="HR.deleteAjaxCommon('{{FunctionLib::inputId($item['bonus_person_id'])}}','{{FunctionLib::inputId($item['bonus_id'])}}','bonusPerson/deleteBonus','div_list_khenthuong',{{\App\Library\AdminFunction\Define::BONUS_KHEN_THUONG}})"><i class="fa fa-trash fa-2x"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    @else
        <tr>
            <td colspan="7"> Chưa có dữ liệu</td>
        </tr>
    @endif
</table>