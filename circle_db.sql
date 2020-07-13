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
