<?php use App\Library\AdminFunction\FunctionLib; ?>
<?php use App\Library\AdminFunction\Define; ?>
@extends('admin.AdminLayouts.index')
@section('content')
<div class="main-content-inner">
    <div class="page-content">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <div class="panel-title pull-left">Đơn vị</div>
                    <div class="btn-group btn-group-sm pull-right">
                        <a class="btn btn-danger btn-sm" href="{{URL::route('admin.user_edit',array('id' => FunctionLib::inputId(0)))}}"><i class="fa fa-file"></i>&nbsp;Thêm mới</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3">
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
                        <div class="col-md-9">
                            <table class="table table-bordered not-bg">
                                <thead>
                                <tr>
                                    <th class="text-center w10">STT</th>
                                    <th class="text-center">Tên đơn vị/ Phòng ban</th>
                                    <th class="text-center">Điện thoại</th>
                                    <th class="text-center">Fax</th>
                                    <th class="text-center">Phân loại</th>
                                    <th class="text-center">Cập nhật</th>
                                    <th class="text-center">Chức năng</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-center">1</td>
                                    <td><a href="">Bệnh viện Tuệ Tĩnh</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Đơn vị độc lập</td>
                                    <td class="text-center">17/05/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_591baf3a473c2c1e80535bf9" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="">Sửa</a></li>
                                                <li><a title="Xóa" href="">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>
>>>>>>> DUY

                                </tr>
                                <tr>
                                    <td class="text-center">2</td>
                                    <td><a href="/58db8d7c473c2c2f68199aab">Khoa CDHA</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">29/03/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_58db8d7c473c2c2f68199aab" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/58db8d7c473c2c2f68199aab">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/58db8d7c473c2c2f68199aab">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-center">3</td>
                                    <td><a href="/591baf89473c2c1e80535bfa">Khoa chấn thương chỉnh hình</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">17/05/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_591baf89473c2c1e80535bfa" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/591baf89473c2c1e80535bfa">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/591baf89473c2c1e80535bfa">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-center">4</td>
                                    <td><a href="/58db8fe1473c2c2f68199ab0">Khoa dieu duong</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">29/03/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_58db8fe1473c2c2f68199ab0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/58db8fe1473c2c2f68199ab0">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/58db8fe1473c2c2f68199ab0">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-center">5</td>
                                    <td><a href="/58db8d91473c2c2f68199aac">Khoa duoc</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">29/03/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_58db8d91473c2c2f68199aac" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/58db8d91473c2c2f68199aac">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/58db8d91473c2c2f68199aac">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-center">6</td>
                                    <td><a href="/591bafc3473c2c1e80535bfb">Khoa thần kinh</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">17/05/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_591bafc3473c2c1e80535bfb" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/591bafc3473c2c1e80535bfb">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/591bafc3473c2c1e80535bfb">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-center">7</td>
                                    <td><a href="/58ca555a473c2c1b6c6fd549">p.tchc</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">16/03/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_58ca555a473c2c1b6c6fd549" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/58ca555a473c2c1b6c6fd549">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/58ca555a473c2c1b6c6fd549">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-center">8</td>
                                    <td><a href="/58c6601a473c2c1a7490bd0c">Phòng Kế toán</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">13/03/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_58c6601a473c2c1a7490bd0c" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/58c6601a473c2c1a7490bd0c">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/58c6601a473c2c1a7490bd0c">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-center">9</td>
                                    <td><a href="/58c65fcc473c2c1a7490bd0b">Phòng Kinh doanh</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">13/03/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_58c65fcc473c2c1a7490bd0b" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/58c65fcc473c2c1a7490bd0b">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/58c65fcc473c2c1a7490bd0b">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-center">10</td>
                                    <td><a href="/591bae9d473c2c1e80535bf8">Phòng kỹ thuật</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">17/05/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_591bae9d473c2c1e80535bf8" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/591bae9d473c2c1e80535bf8">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/591bae9d473c2c1e80535bf8">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-center">11</td>
                                    <td><a href="/59245a1f473c2c234c52ec9c">phong test a</a></td>
                                    <td>032323232323,0123556565</td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">23/05/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_59245a1f473c2c234c52ec9c" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/59245a1f473c2c234c52ec9c">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/59245a1f473c2c234c52ec9c">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-center">12</td>
                                    <td><a href="/58720613473c2c2708012a0f">Phòng tổ chức</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">08/01/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_58720613473c2c2708012a0f" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/58720613473c2c2708012a0f">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/58720613473c2c2708012a0f">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-center">13</td>
                                    <td><a href="/58db8c94473c2c2f68199aaa">Phòng vật tư xuất nhập khẩu</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">29/03/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_58db8c94473c2c2f68199aaa" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/58db8c94473c2c2f68199aaa">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/58db8c94473c2c2f68199aaa">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-center">14</td>
                                    <td><a href="/5880c707473c2c26e0e91e61">Phòng Y Tế</a></td>
                                    <td></td>
                                    <td></td>
                                    <td>Phòng ban / bộ phận trực thuộc</td>
                                    <td class="text-center">19/01/2017</td>
                                    <td align="center">
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle btn-block" type="button" id="dropdownMenu_5880c707473c2c26e0e91e61" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                - Chọn -
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a title="Sửa" href="/5880c707473c2c26e0e91e61">Sửa</a></li>
                                                <li><a title="Xóa" href="/organization/delete/5880c707473c2c26e0e91e61">Xóa</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@stop