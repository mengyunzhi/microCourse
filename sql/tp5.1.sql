/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100113
 Source Host           : localhost:3306
 Source Schema         : tp5.1

 Target Server Type    : MySQL
 Target Server Version : 100113
 File Encoding         : 65001

 Date: 14/08/2019 17:08:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_classroom
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_classroom`;
CREATE TABLE `yunzhi_classroom` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `classroomplace` varchar(30) NOT NULL DEFAULT '' COMMENT '教室区域',
  `classroomname` varchar(30) NOT NULL DEFAULT '' COMMENT '教室名称',
  `row` int(100) NOT NULL COMMENT '行',
  `column` int(100) NOT NULL COMMENT '列',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_course
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_course`;
CREATE TABLE `yunzhi_course` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `num` varchar(30) NOT NULL COMMENT '课程代码',
  `term` varchar(30) NOT NULL DEFAULT '' COMMENT '学期',
  `Teacher_id` varchar(10) NOT NULL DEFAULT '' COMMENT '任课教师',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0选修，1必修',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_course_student
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_course_student`;
CREATE TABLE `yunzhi_course_student` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) unsigned NOT NULL,
  `courseinfo_id` int(11) unsigned NOT NULL,
  `column` int(11) unsigned NOT NULL COMMENT '列',
  `row` int(11) unsigned NOT NULL COMMENT '行',
  `arrival` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_courseinfo
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_courseinfo`;
CREATE TABLE `yunzhi_courseinfo` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `course_num` varchar(30) NOT NULL,
  `week` int(5) NOT NULL,
  `weekday` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `klassroom_id` int(30) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_klass
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_klass`;
CREATE TABLE `yunzhi_klass` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `academy` varchar(30) NOT NULL DEFAULT '' COMMENT '学院',
  `major` varchar(30) NOT NULL DEFAULT '' COMMENT '专业',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_klass_course
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_klass_course`;
CREATE TABLE `yunzhi_klass_course` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `klass_id` int(11) NOT NULL,
  `course_id` int(30) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_score_student
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_score_student`;
CREATE TABLE `yunzhi_score_student` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) unsigned NOT NULL,
  `course_id` int(11) unsigned NOT NULL,
  `score1` int(11) unsigned NOT NULL COMMENT '平时成绩',
  `score2` int(11) unsigned NOT NULL COMMENT '期末成绩',
  `scoresum` int(11) unsigned NOT NULL COMMENT '总成绩',
  `arrivals` int(11) NOT NULL COMMENT '签到',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_student
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_student`;
CREATE TABLE `yunzhi_student` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '姓名',
  `num` int(11) NOT NULL COMMENT '学号',
  `password` varchar(255) NOT NULL COMMENT '密码',
  `sex` tinyint(2) NOT NULL COMMENT '性别',
  `academy` varchar(255) NOT NULL COMMENT '学院',
  `major` varchar(255) NOT NULL DEFAULT '' COMMENT '专业全称',
  `klass_id` int(11) unsigned NOT NULL COMMENT '班号',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_teacher
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_teacher`;
CREATE TABLE `yunzhi_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `num` int(30) NOT NULL,
  `password` varchar(40) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for yunzhi_term
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_term`;
CREATE TABLE `yunzhi_term` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `start` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '学期起点',
  `end` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '学期终点',
  `length` int(100) NOT NULL COMMENT '学期长度/周',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0冻结，1激活',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;
