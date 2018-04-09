/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : human

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-04-09 18:15:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for hr_department_config
-- ----------------------------
DROP TABLE IF EXISTS `hr_department_config`;
CREATE TABLE `hr_department_config` (
  `department_config_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Cấu hình khung tuổi nghỉ hưu, tăng lương theo depart',
  `department_config_project` int(11) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `department_retired_age_min_girl` int(5) DEFAULT NULL COMMENT 'Tuổi tối thiểu của nữ để nghỉ hưu',
  `department_retired_age_max_girl` int(5) DEFAULT NULL,
  `department_retired_age_min_boy` int(5) DEFAULT NULL,
  `department_retired_age_max_boy` int(5) DEFAULT NULL,
  `month_regular_wage_increases` int(5) DEFAULT '36' COMMENT 'Số tháng cần thiết xét tăng lương thường xuyên',
  `month_raise_the_salary_ahead_of_time` int(5) DEFAULT '24' COMMENT 'số tháng tối thiểu để xét tăng luong trước thời hạn',
  `department_config_status` tinyint(2) DEFAULT '1',
  PRIMARY KEY (`department_config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of hr_department_config
-- ----------------------------

-- ----------------------------
-- Table structure for hr_wage_step_config
-- ----------------------------
DROP TABLE IF EXISTS `hr_wage_step_config`;
CREATE TABLE `hr_wage_step_config` (
  `wage_step_config_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'BẢNG PHÂN TÍCH CẤU HÌNH LƯƠNG',
  `wage_step_config_project` int(11) DEFAULT NULL,
  `wage_step_config_parent_id` int(11) DEFAULT NULL,
  `wage_step_config_name` varchar(250) CHARACTER SET utf8 DEFAULT NULL,
  `wage_step_config_value` varchar(191) DEFAULT NULL COMMENT 'Hệ số tương ứng, type = 4',
  `wage_step_config_type` tinyint(2) DEFAULT '1' COMMENT '1:thang bảng lương, 2: nghạch công chức, 3: mã ngạch, 4: bậc lương',
  `wage_step_config_order` tinyint(5) DEFAULT '1',
  `wage_step_config_status` tinyint(2) DEFAULT '1',
  PRIMARY KEY (`wage_step_config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of hr_wage_step_config
-- ----------------------------
