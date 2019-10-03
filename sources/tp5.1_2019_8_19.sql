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

 Date: 19/08/2019 19:04:56
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yunzhi_classroom
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_classroom` VALUES (1, '十二教', 'A203', 5, 6, 0, 0);
INSERT INTO `yunzhi_classroom` VALUES (2, '五教', '106', 7, 8, 0, 0);
INSERT INTO `yunzhi_classroom` VALUES (3, '123', '123', 123, 123, 1566207635, 1566207635);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_course
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_course`;
CREATE TABLE `yunzhi_course` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `num` varchar(30) NOT NULL COMMENT '课程代码',
  `term_id` varchar(30) NOT NULL DEFAULT '' COMMENT '学期',
  `teacher_id` varchar(10) NOT NULL DEFAULT '' COMMENT '任课教师',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0选修，1必修',
  `klass` varchar(255) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yunzhi_course
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_course` VALUES (1, '数学', '123456', '1', '1', 1, NULL, 123123, 123213);
INSERT INTO `yunzhi_course` VALUES (2, '商业网站开发2', '654321', '1', '1', 0, NULL, 123213, 1566198094);
INSERT INTO `yunzhi_course` VALUES (4, '123123', '123', '1', '1', 0, NULL, 1566207615, 1566207619);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_courseinfo
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_courseinfo`;
CREATE TABLE `yunzhi_courseinfo` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `course_id` varchar(30) NOT NULL,
  `week` int(5) NOT NULL,
  `weekday` varchar(30) NOT NULL,
  `time1` int(30) NOT NULL,
  `time2` int(30) NOT NULL,
  `classroom_id` int(30) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yunzhi_courseinfo
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_courseinfo` VALUES (1, '1', 12, '1', 1, 2, 1, 0, 0);
INSERT INTO `yunzhi_courseinfo` VALUES (2, '2', 11, '1', 2, 2, 2, 0, 0);
INSERT INTO `yunzhi_courseinfo` VALUES (3, '1', 12, '12', 2, 3, 1, 1, 0);
INSERT INTO `yunzhi_courseinfo` VALUES (4, '1', 0, '', 0, 0, 2, 0, 0);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_klass
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_klass`;
CREATE TABLE `yunzhi_klass` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `academy` varchar(30) NOT NULL DEFAULT '' COMMENT '学院',
  `grade` varchar(30) DEFAULT NULL COMMENT '年级',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yunzhi_klass
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_klass` VALUES (1, '人工智能与数据科学学院', '12', '计1806', 123123, 123213);
INSERT INTO `yunzhi_klass` VALUES (2, '电信学院', '123', '电技卓181', 123123, 123213);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_klass_course
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_klass_course`;
CREATE TABLE `yunzhi_klass_course` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `klass_id` int(11) NOT NULL,
  `course_id` int(30) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yunzhi_klass_course
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_klass_course` VALUES (5, 1, 3);
INSERT INTO `yunzhi_klass_course` VALUES (6, 2, 3);
INSERT INTO `yunzhi_klass_course` VALUES (7, 1, 2);
INSERT INTO `yunzhi_klass_course` VALUES (8, 2, 2);
INSERT INTO `yunzhi_klass_course` VALUES (9, 1, 1);
INSERT INTO `yunzhi_klass_course` VALUES (10, 2, 1);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_oncourse
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_oncourse`;
CREATE TABLE `yunzhi_oncourse` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) unsigned NOT NULL,
  `courseinfo_id` int(11) unsigned NOT NULL,
  `column` int(11) unsigned NOT NULL COMMENT '列',
  `row` int(11) unsigned NOT NULL COMMENT '行',
  `arrival` int(11) NOT NULL,
  `respond` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yunzhi_oncourse
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_oncourse` VALUES (1, 1, 1, 7, 8, 1, 0);
INSERT INTO `yunzhi_oncourse` VALUES (2, 2, 1, 6, 5, 0, 0);
COMMIT;

-- ----------------------------
-- Table structure for yunzhi_score
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_score`;
CREATE TABLE `yunzhi_score` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(11) unsigned NOT NULL,
  `course_id` int(11) unsigned NOT NULL,
  `score1` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '平时成绩',
  `score2` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '期末成绩',
  `scoresum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '总成绩',
  `arrivals` int(11) NOT NULL DEFAULT '0' COMMENT '签到',
  `responds` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of yunzhi_score
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_score` VALUES (1, 1, 2, 1, 2, 3, 0, 0);
INSERT INTO `yunzhi_score` VALUES (2, 2, 1, 2, 2, 4, 0, 0);
INSERT INTO `yunzhi_score` VALUES (3, 2, 2, 4, 5, 6, 4, 2);
INSERT INTO `yunzhi_score` VALUES (4, 2, 2, 6, 6, 6, 20, 0);
INSERT INTO `yunzhi_score` VALUES (5, 1, 2, 6, 6, 6, 0, 0);
COMMIT;

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
-- Records of yunzhi_student
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_student` VALUES (1, '王五', 185555, '123', 1, '人文与法律学院', '法律', 1, 0, 0);
INSERT INTO `yunzhi_student` VALUES (2, '赵六', 184444, '456', 0, '电子信息工程学院', '通信工程', 2, 0, 0);
COMMIT;

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
-- Records of yunzhi_teacher
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_teacher` VALUES (1, '李四', 456, '456', 0, 0, 0);
INSERT INTO `yunzhi_teacher` VALUES (2, '张三', 123, '123', 0, 0, 0);
COMMIT;

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

-- ----------------------------
-- Records of yunzhi_term
-- ----------------------------
BEGIN;
INSERT INTO `yunzhi_term` VALUES (1, '2018-2019春', 0, 0, 18, 0, 0, 0);
INSERT INTO `yunzhi_term` VALUES (2, '2018-2019秋', 0, 0, 20, 1, 0, 0);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
