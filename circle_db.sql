/*
Navicat MySQL Data Transfer

Source Server         : 47.99.117.126-dataface
Source Server Version : 50641
Source Host           : 47.99.117.126:3306
Source Database       : circle_db

Target Server Type    : MYSQL
Target Server Version : 50641
File Encoding         : 65001

Date: 2020-07-13 14:13:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `circle`
-- ----------------------------
DROP TABLE IF EXISTS `circle`;
CREATE TABLE `circle` (
  `circle_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '圈子ID',
  `circle_name` varchar(32) DEFAULT NULL COMMENT '圈子名称',
  `circle_img` varchar(256) DEFAULT NULL COMMENT '圈子图像',
  `circle_des` varchar(256) DEFAULT NULL COMMENT '圈子描述',
  `user_id` int(11) DEFAULT NULL COMMENT '创建人',
  `created_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`circle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COMMENT='圈子 ';

-- ----------------------------
-- Records of circle
-- ----------------------------
INSERT INTO `circle` VALUES ('19', '新番提醒组', 'https://circle.dataface.vip/upload/20200629/b4Xybee6WdFsquEIXulvg1gC8y4hWq62BIbuQMkP.jpeg', '我会定期发相关图片哦', '8', '2020-06-29 10:24:06');
INSERT INTO `circle` VALUES ('20', null, null, null, null, '2020-06-29 16:18:43');

-- ----------------------------
-- Table structure for `circle_user_merge`
-- ----------------------------
DROP TABLE IF EXISTS `circle_user_merge`;
CREATE TABLE `circle_user_merge` (
  `circle_user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `circle_id` int(11) NOT NULL DEFAULT '0' COMMENT '圈子ID',
  `is_master` char(2) NOT NULL DEFAULT 'N' COMMENT '是否是圈主',
  `created_time` datetime DEFAULT NULL COMMENT '创建时间',
  `user_remarks_name` varchar(32) DEFAULT NULL COMMENT '备注名',
  `user_is_push` char(2) NOT NULL DEFAULT 'N' COMMENT '是否订阅该圈子消息',
  PRIMARY KEY (`circle_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COMMENT='圈子用户连接 ';

-- ----------------------------
-- Records of circle_user_merge
-- ----------------------------
INSERT INTO `circle_user_merge` VALUES ('27', '8', '19', 'Y', '2020-06-29 11:29:16', '志扬?', 'N');
INSERT INTO `circle_user_merge` VALUES ('28', '9', '19', 'N', '2020-06-29 13:23:39', '有志飞扬', 'Y');
INSERT INTO `circle_user_merge` VALUES ('29', '10', '19', 'N', '2020-06-29 13:37:23', 'Vicky', 'Y');

-- ----------------------------
-- Table structure for `topic`
-- ----------------------------
DROP TABLE IF EXISTS `topic`;
CREATE TABLE `topic` (
  `topic_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `topic_content` varchar(3072) DEFAULT NULL COMMENT '内容',
  `circle_id` int(11) DEFAULT NULL COMMENT '圈子主键',
  `user_id` int(11) DEFAULT NULL COMMENT '创建人',
  `created_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`topic_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COMMENT='主题帖 ';

-- ----------------------------
-- Records of topic
-- ----------------------------
INSERT INTO `topic` VALUES ('17', '福利图片哦', '19', '8', '2020-06-29 13:31:13');
INSERT INTO `topic` VALUES ('18', '福利图片二', '19', '8', '2020-06-29 13:38:03');
INSERT INTO `topic` VALUES ('19', '文件', '19', '8', '2020-06-29 14:19:17');

-- ----------------------------
-- Table structure for `topic_comment`
-- ----------------------------
DROP TABLE IF EXISTS `topic_comment`;
CREATE TABLE `topic_comment` (
  `read_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `topic_id` int(11) DEFAULT NULL COMMENT '主题帖ID',
  `user_id` int(11) DEFAULT NULL COMMENT '用户ID',
  `comment_content` varchar(1024) DEFAULT NULL COMMENT '评论内容',
  `comment_time` datetime DEFAULT NULL COMMENT '阅读时间',
  `created_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`read_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='主题用户评论 ';

-- ----------------------------
-- Records of topic_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `topic_file`
-- ----------------------------
DROP TABLE IF EXISTS `topic_file`;
CREATE TABLE `topic_file` (
  `file_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `file_name` varchar(128) DEFAULT NULL,
  `file_src` varchar(256) DEFAULT NULL COMMENT '文件路径',
  `file_extension` varchar(32) DEFAULT NULL COMMENT '文件类型',
  `file_size` int(4) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `topic_id` int(11) NOT NULL DEFAULT '0' COMMENT '话题ID',
  `is_image` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否是图片，1图片，2文件',
  `created_time` datetime NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COMMENT='主题帖文件图片 ';

-- ----------------------------
-- Records of topic_file
-- ----------------------------
INSERT INTO `topic_file` VALUES ('24', 'jI94nI0UiqkLZoIV1S4rSOvPiSnh6MrjbQnDvQAB.jpeg', 'https://circle.dataface.vip/upload/20200629/jI94nI0UiqkLZoIV1S4rSOvPiSnh6MrjbQnDvQAB.jpeg', 'jpeg', '27123', '17', '1', '2020-06-29 13:31:13');
INSERT INTO `topic_file` VALUES ('25', '6gDgMZt8OOI3YdgdKPulslNvb98o5W44CFwX6LN5.jpeg', 'https://circle.dataface.vip/upload/20200629/6gDgMZt8OOI3YdgdKPulslNvb98o5W44CFwX6LN5.jpeg', 'jpeg', '27123', '18', '1', '2020-06-29 13:38:03');
INSERT INTO `topic_file` VALUES ('26', 'AZYujNQ36MUbWc6Sl4g8BbYcnrR1N3tbC2NWrUXj.png', 'https://circle.dataface.vip/upload/20200629/AZYujNQ36MUbWc6Sl4g8BbYcnrR1N3tbC2NWrUXj.png', 'png', '9275', '18', '1', '2020-06-29 13:38:03');
INSERT INTO `topic_file` VALUES ('27', '拉数据.xls', 'https://circle.dataface.vip/upload/20200629/拉数据.xls', 'xls', '19456', '19', '2', '2020-06-29 14:19:17');
INSERT INTO `topic_file` VALUES ('28', '2019年终总结-杨志.docx', 'https://circle.dataface.vip/upload/20200629/2019年终总结-杨志.docx', 'docx', '15978', '19', '2', '2020-06-29 14:19:17');

-- ----------------------------
-- Table structure for `topic_user_merge`
-- ----------------------------
DROP TABLE IF EXISTS `topic_user_merge`;
CREATE TABLE `topic_user_merge` (
  `topic_user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `topic_id` int(11) NOT NULL DEFAULT '0' COMMENT '主题ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '接受用户ID',
  `is_read` char(1) NOT NULL DEFAULT 'N' COMMENT '是否阅读',
  `read_time` datetime DEFAULT NULL COMMENT '阅读时间',
  `created_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`topic_user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COMMENT='主题用户连接 ';

-- ----------------------------
-- Records of topic_user_merge
-- ----------------------------
INSERT INTO `topic_user_merge` VALUES ('13', '17', '8', 'N', null, '2020-06-29 13:31:13');
INSERT INTO `topic_user_merge` VALUES ('14', '17', '9', 'N', null, '2020-06-29 13:31:13');
INSERT INTO `topic_user_merge` VALUES ('15', '18', '8', 'Y', '2020-06-29 13:51:02', '2020-06-29 13:38:03');
INSERT INTO `topic_user_merge` VALUES ('16', '18', '9', 'Y', '2020-06-29 13:41:32', '2020-06-29 13:38:03');
INSERT INTO `topic_user_merge` VALUES ('17', '18', '10', 'Y', '2020-06-29 14:20:31', '2020-06-29 13:38:03');
INSERT INTO `topic_user_merge` VALUES ('18', '19', '8', 'Y', '2020-06-29 14:20:24', '2020-06-29 14:19:17');
INSERT INTO `topic_user_merge` VALUES ('19', '19', '9', 'N', null, '2020-06-29 14:19:17');
INSERT INTO `topic_user_merge` VALUES ('20', '19', '10', 'N', null, '2020-06-29 14:19:17');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `wx_id` varchar(32) DEFAULT NULL COMMENT '微信ID',
  `wx_nickname` varchar(32) DEFAULT NULL COMMENT '微信昵称',
  `wx_photo` varchar(256) DEFAULT NULL COMMENT '微信图像',
  `wx_sex` int(11) DEFAULT NULL COMMENT '性别 0未知、1男、2女',
  `wx_country` varchar(32) DEFAULT NULL COMMENT '国家',
  `wx_province` varchar(32) DEFAULT NULL COMMENT '省份',
  `wx_city` varchar(32) DEFAULT NULL COMMENT '城市',
  `user_mobile` varchar(32) DEFAULT NULL COMMENT '联系电话',
  `user_qq` varchar(32) DEFAULT NULL COMMENT '联系QQ',
  `created_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COMMENT='用户 ';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('8', 'oKfWv4rorBeo7BR-6fKzf_oP7PXY', '志扬?', 'https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83ep9W6iaibfWRcm3Kr4Rib5jqUZwNCyahNgAfwbYSUG5kDLc3eciaX92MT2j3DSC9SOH8WwwsNjX2FNwWA/132', '1', 'China', 'Shanghai', 'Pudong New District', null, null, '2020-06-29 10:22:58');
INSERT INTO `user` VALUES ('9', 'oKfWv4i18-s4SZAcifeY1Tws377U', '有志飞扬', 'https://wx.qlogo.cn/mmopen/vi_32/39IyTgN0RQEQs9qIkFlA0MVMjETNpFwHMk0Jzm87gKyricIGruQnK9uxd12iatDUKgQ9SkwIMcHPvX89ic4HtcNDw/132', '0', '', '', '', null, null, '2020-06-29 10:51:29');
INSERT INTO `user` VALUES ('10', 'oKfWv4hgyUm1lb6CoQJZi7aeAw1Y', 'Vicky', 'https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKV8bDlRopmEAn7j55ZJZIJ51MQM5ymFGUJeW5NhUMcIuGFMicZp8jRQ1bibjRwCiahiceAdCSrs7svag/132', '0', '', '', '', null, null, '2020-06-29 13:27:24');
INSERT INTO `user` VALUES ('11', 'oKfWv4iJb5PxfavUipG5P1bcl7D8', '低调', 'https://wx.qlogo.cn/mmopen/vi_32/zEzJYYyj4pmr202bTLqX0VAZmXv6v2o4Az76f14T3frO4sOdfo6ia2sLD69NLsRVhaV1N0knbdcHHOzGbHhc6iaw/132', '1', 'China', 'Gansu', 'Lanzhou', null, null, '2020-06-29 16:18:21');
INSERT INTO `user` VALUES ('12', 'oKfWv4s1zFBtnUIKURV-mq6mvsZQ', '超_越梦想', 'https://wx.qlogo.cn/mmopen/vi_32/qYIqibSGuqs0PWqXRVibqDuCbjAv8eCHRicEXfhgjWDM94ulI9VYOasicicicMDYgoFvfDIypZiaUicWrxFyWMTmvSrr8g/132', '2', 'China', 'Shandong', 'Yantai', null, null, '2020-06-29 16:18:21');
INSERT INTO `user` VALUES ('13', 'oKfWv4nT9NhXZA-64qsd71c6-Rus', '蓝俊宏', 'https://wx.qlogo.cn/mmhead/YfoU4PWbyQagkcEHThht5SCtdib0TRibOzfwVaEEXHgVY/132', '0', '', '', '', null, null, '2020-06-29 16:38:20');
