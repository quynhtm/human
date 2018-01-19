<?php use App\Library\AdminFunction\CGlobal; ?>
<?php use App\Library\AdminFunction\Define; ?>
<?php use App\Library\AdminFunction\FunctionLib; ?>
@extends('admin.AdminLayouts.index')
@section('content')
<div class="main-content-inner">
    <div class="page-content">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="panel-title pull-left">Thêm đơn vị</div>
                    <div class="btn-group btn-group-sm pull-right">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('hr.departmentView')}}"><i class="fa fa-arrow-left"></i>&nbsp;Quay lại</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p class="boxtitle">Chọn đơn vị/ phòng ban quản lý trực tiếp</p>
                            <div id="treeview" class="treeview">
                                <ul class="list-group">
                                    <li class="list-group-item node-treeview node-selected" data-nodeid="0"><a href="">Phòng tổ chức</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="1" ><a href="">Phòng Y Tế</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="2" ><a href="">Phòng Kinh doanh</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="3" ><a href="">Phòng Kế toán</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="4" ><a href="">p.tchc</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="5" ><a href="">Phòng vật tư xuất nhập khẩu</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="6" ><a href="">Khoa CDHA</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="7" ><a href="">Khoa duoc</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="8" ><a href="">Khoa dieu duong</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="9" ><a href="">Phòng kỹ thuật</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="10" ><a href="">Bệnh viện Tuệ Tĩnh (Đơn vị độc lập)</a><span class="badge">1</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="11" ><a href="">Khoa chấn thương chỉnh hình</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="12" ><a href="">Khoa thần kinh</a><span class="badge">0</span></li>
                                    <li class="list-group-item node-treeview" data-nodeid="13" ><a href="">phong test a</a><span class="badge">0</span></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <p>(<span class="clred">*</span>) Là trường bắt buộc phải nhập</p>
                            <form id="adminForm" name="adminForm" method="post" enctype="multipart/form-data" action="" novalidate="novalidate">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p><span class="lbl">Đơn vị/ Phòng ban quản lý trực tiếp</span>:&nbsp;<span id="orgname" class="val">Phòng tổ chức</span></p>
                                        <input id="DepartmentParentId" name="DepartmentParentId" value="" type="hidden">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Loại đơn vị/ phòng ban (<span class="clred">*</span>)</label>
                                            <select class="form-control input-sm"  id="department_type" name="department_type">
                                                {!! $optionDepartmentType !!}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tên đơn vị/ Phòng ban(<span class="clred">*</span>)</label>
                                            <input class="form-control input-sm" id="department_name" name="department_name" @isset($data['department_name'])value="{{$data['department_name']}}"@endif type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Họ tên lãnh đạo</label>
                                            <input class="form-control input-sm" id="Organization_Leader_Name" name="Organization.Leader.Name" value="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Danh sách số điện thoại</label>
                                            <input class="form-control input-sm" id="Organization_MTel" name="Organization.MTel" placeholder="Nhập danh sách các số điện thoại, cách nhau bởi dấu ','" value="" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Danh sách email</label>
                                            <input class="form-control input-sm" id="Organization_MEmail" name="Organization.MEmail" placeholder="Nhập danh sách các email, cách nhau bởi dấu ','" value="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Danh sách số fax</label>
                                            <input class="form-control input-sm" id="Organization_MFax" name="Organization.MFax" placeholder="Nhập danh sách các số fax, cách nhau bởi dấu ','" value="" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Danh sách địa điểm bố trí tài sản</label>
                                            <input class="form-control input-sm" id="Organization_MPlace" name="Organization.MPlace" placeholder="Nhập danh sách địa điểm bố trí tài sản, cách dâu bởi dấu ','" value="" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Mã số thuế</label>
                                            <input class="form-control input-sm" id="Organization_AccountInfo_TaxCD" name="Organization.AccountInfo.TaxCD" placeholder="Mã số thuế" value="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tài khoản ngân hàng</label>
                                            <input class="form-control input-sm" id="Organization_AccountInfo_Number" name="Organization.AccountInfo.Number" placeholder="Tài khoản ngân hàng" value="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Ngân hàng</label>
                                            <input class="form-control input-sm" id="Organization_AccountInfo_BankInfo_Name" name="Organization.AccountInfo.BankInfo.Name" placeholder="Ngân hàng" value="" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Chi nhánh ngân hàng</label>
                                            <input class="form-control input-sm" id="Organization_AccountInfo_BranchInfo_Name" name="Organization.AccountInfo.BranchInfo.Name" placeholder="Chi nhánh ngân hàng" value="" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        {!! csrf_field() !!}
                                        <button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-forward"></i>&nbsp;Lưu và tiếp tục nhập</button>
                                        <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-save"></i>&nbsp;Lưu hoàn thành</button>
                                    </div>
                                    <input id="hdAction" name="hdAction" value="" type="hidden">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop