<?php
/**
 * Created by JetBrains PhpStorm.
 * User: QuynhTM
 */

namespace App\Library\AdminFunction;

use App\library\AdminFunction\Define;

class CGlobal
{
    static $css_ver = 1;
    static $js_ver = 1;
    public static $POS_HEAD = 1;
    public static $POS_END = 2;
    public static $extraHeaderCSS = '';
    public static $extraHeaderJS = '';
    public static $extraFooterCSS = '';
    public static $extraFooterJS = '';
    public static $extraMeta = '';
    public static $pageAdminTitle = 'Quản lý hành chính tổng hợp';
    public static $pageShopTitle = '';

    const project_name = 'manager_hr';
    const code_shop_share = 'PM QL HCTH';
    const web_name = 'Quản lý hành chính tổng hợp';
    const web_keywords = 'Quản lý hành chính tổng hợp';
    const web_description = 'Quản lý hành chính tổng hợp';
    public static $pageTitle = 'Quản lý hành chính tổng hợp';

    const phoneSupport = '';

    const num_scroll_page = 2;
    const number_limit_show = 30;
    const number_show_30 = 30;
    const number_show_40 = 40;
    const number_show_20 = 20;
    const number_show_15 = 15;
    const number_show_10 = 10;
    const number_show_5 = 5;
    const number_show_8 = 8;
    const number_show_1000 = 1000;

    const status_show = 1;
    const status_hide = 0;
    const status_block = -2;

    const concatenation_rule_first = 1;
    const concatenation_rule_center = 2;
    const concatenation_rule_end = 3;

    //is_login Customer
    const not_login = 0;
    const is_login = 1;

    const active = 1;
    const not_active = 0;

    public static $arrLinkEditPerson = [
          1 => ['icons' => 'fa fa-edit', 'name_url' => 'Sửa thông tin chung', 'link_url' => '/manager/personnel/edit/','blank'=>1],
          2 => ['icons' => 'fa fa-suitcase', 'name_url' => 'Thông tin đào tạo công tác', 'link_url' => '/manager/curriculumVitaePerson/viewCurriculumVitae/','blank'=>1],
          3 => ['icons' => 'fa fa-gift', 'name_url' => 'Thông tin khen thưởng kỷ luật', 'link_url' => '/manager/bonusPerson/viewBonus/','blank'=>1],
          4 => ['icons' => 'fa fa-money', 'name_url' => 'Cập nhật lương phụ cấp', 'link_url' => '/manager/salaryAllowance/viewSalaryAllowance/','blank'=>1],
          5 => ['icons' => 'fa fa-child', 'name_url' => 'Thông báo-bổ nhiệm chức vụ', 'link_url' => '/manager/jobAssignment/viewJobAssignment/','blank'=>1],
          6 => ['icons' => 'fa fa-file-o', 'name_url' => 'Hợp đồng lao động', 'link_url' => '/manager/infoPerson/viewContracts/','blank'=>1],
          7 => ['icons' => 'fa fa-plane', 'name_url' => 'Cập nhật thông tinh hộ chiếu,MST', 'link_url' => '/manager/passport/edit/','blank'=>1],
          8 => ['icons' => 'fa fa-retweet', 'name_url' => 'Chuyển bộ phận phòng ban', 'link_url' => '/manager/quitJob/editMoveDepart/','blank'=>1],
          9 => ['icons' => 'fa fa-clock-o', 'name_url' => 'Thiết lập thời gian nghỉ hưu', 'link_url' => '/manager/retirement/edit/','blank'=>1],
          10 => ['icons' => 'fa fa-level-up', 'name_url' => 'Kéo dài thời gian nghỉ hưu', 'link_url' => '/manager/retirement/editTime/','blank'=>1],
          11 => ['icons' => 'fa fa-exchange', 'name_url' => 'Nghỉ việc chuyển công tác', 'link_url' => '/manager/quitJob/editMove/','blank'=>1],
          12 => ['icons' => 'fa fa-thumbs-down', 'name_url' => 'Buộc thôi việc nhân sự', 'link_url' => '/manager/quitJob/editJob/','blank'=>1],
          13 => ['icons' => 'fa fa-user', 'name_url' => 'Tạo tài khoản sử dụng hệ thống', 'link_url' => '/manager/personnel/editAccount/','blank'=>1],
          14 => ['icons' => 'fa fa-trash', 'name_url' => 'Xoá nhân sự này', 'link_url' => '/manager/personnel/personStatusDelete/','blank'=>0]
    ];
}