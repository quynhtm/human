<?php
/**
 * Created by JetBrains PhpStorm.
 * User: QuynhTM
 */
namespace App\Library\AdminFunction;

class ArrayPermission{
    public static $arrPermit = array(
        'root' => array('name_permit'=>'Quản trị site','group_permit'=>'Quản trị site'),//admin site
        'is_boss' => array('name_permit'=>'Boss','group_permit'=>'Boss'),//tech dùng quyen cao nhat

        'user_view' => array('name_permit'=>'Xem danh sách user Admin','group_permit'=>'Tài khoản Admin'),
        'user_create' => array('name_permit'=>'Tạo user Admin','group_permit'=>'Tài khoản Admin'),
        'user_edit' => array('name_permit'=>'Sửa user Admin','group_permit'=>'Tài khoản Admin'),
        'user_change_pass' => array('name_permit'=>'Thay đổi user Admin','group_permit'=>'Tài khoản Admin'),
        'user_remove' => array('name_permit'=>'Xóa user Admin','group_permit'=>'Tài khoản Admin'),

        'group_user_view' => array('name_permit'=>'Xem nhóm quyền','group_permit'=>'Nhóm quyền'),
        'group_user_create' => array('name_permit'=>'Tạo nhóm quyền','group_permit'=>'Nhóm quyền'),
        'group_user_edit' => array('name_permit'=>'Sửa nhóm quyền','group_permit'=>'Nhóm quyền'),

        'permission_full' => array('name_permit'=>'Full tạo quyền','group_permit'=>'Tạo quyền'),
        'permission_create' => array('name_permit'=>'Tạo tạo quyền','group_permit'=>'Tạo quyền'),
        'permission_edit' => array('name_permit'=>'Sửa tạo quyền','group_permit'=>'Tạo quyền'),

        'province_full' => array('name_permit'=>'Full tỉnh thành','group_permit'=>'Quyền tỉnh thành'),
        'province_view' => array('name_permit'=>'Xem tỉnh thành','group_permit'=>'Quyền tỉnh thành'),
        'province_delete' => array('name_permit'=>'Xóa tỉnh thành','group_permit'=>'Quyền tỉnh thành'),
        'province_create' => array('name_permit'=>'Tạo tỉnh thành','group_permit'=>'Quyền tỉnh thành'),
        'province_edit' => array('name_permit'=>'Sửa tỉnh thành','group_permit'=>'Quyền tỉnh thành'),

        'user_customer_full' => array('name_permit'=>'Full khách hàng','group_permit'=>'Quyền khách hàng'),
        'user_customer_view' => array('name_permit'=>'Xem khách hàng','group_permit'=>'Quyền khách hàng'),
        'user_customer_delete' => array('name_permit'=>'Xóa khách hàng','group_permit'=>'Quyền khách hàng'),
        'user_customer_create' => array('name_permit'=>'Tạo khách hàng','group_permit'=>'Quyền khách hàng'),
        'user_customer_edit' => array('name_permit'=>'Sửa khách hàng','group_permit'=>'Quyền khách hàng'),

        'adminCronjob_full' => array('name_permit'=>'Full cronjob','group_permit'=>'Quyền cronjob'),
        'adminCronjob_view' => array('name_permit'=>'Xem cronjob','group_permit'=>'Quyền cronjob'),
        'adminCronjob_delete' => array('name_permit'=>'Xóa cronjob','group_permit'=>'Quyền cronjob'),
        'adminCronjob_create' => array('name_permit'=>'Tạo cronjob','group_permit'=>'Quyền cronjob'),
        'adminCronjob_edit' => array('name_permit'=>'Sửa cronjob','group_permit'=>'Quyền cronjob'),

        /**
         *  private $permission_view = 'menu_view';
        private $permission_full = 'menu_full';
        private $permission_delete = 'menu_delete';
        private $permission_create = 'menu_create';
        private $permission_edit = 'menu_edit';
         */
        'menu_full' => array('name_permit'=>'Full menu','group_permit'=>'Quyền menu'),
        'menu_view' => array('name_permit'=>'Xem menu','group_permit'=>'Quyền menu'),
        'menu_delete' => array('name_permit'=>'Xóa menu','group_permit'=>'Quyền menu'),
        'menu_create' => array('name_permit'=>'Tạo menu','group_permit'=>'Quyền menu'),
        'menu_edit' => array('name_permit'=>'Sửa menu','group_permit'=>'Quyền menu'),

        /********************************************************************************************************
         * Quyền HR
         * ******************************************************************************************************/
        'hr_document_full' => array('name_permit'=>'Full văn bản','group_permit'=>'Quyền văn bản'),
        'hr_document_view' => array('name_permit'=>'Xem văn bản','group_permit'=>'Quyền văn bản'),
        'hr_document_delete' => array('name_permit'=>'Xóa văn bản','group_permit'=>'Quyền văn bản'),
        'hr_document_create' => array('name_permit'=>'Tạo văn bản','group_permit'=>'Quyền văn bản'),
        'hr_document_edit' => array('name_permit'=>'Sửa văn bản','group_permit'=>'Quyền văn bản'),
    );

}