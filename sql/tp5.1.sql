/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100316
 Source Host           : localhost:3306
 Source Schema         : tp5.1

 Target Server Type    : MySQL
 Target Server Version : 100316
 File Encoding         : 65001

 Date: 12/08/2019 20:41:30
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yunzhi_classroom
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_classroom`;
CREATE TABLE `yunzhi_classroom`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `classroomplace` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '教室区域',
  `classroomname` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '教室名称',
  `row` int(100) NOT NULL COMMENT '行',
  `column` int(100) NOT NULL COMMENT '列',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_classroom
-- ----------------------------
INSERT INTO `yunzhi_classroom` VALUES (1, '十二教', 'A203', 5, 6, 0, 0);
INSERT INTO `yunzhi_classroom` VALUES (2, '五教', '106', 7, 8, 0, 0);

-- ----------------------------
-- Table structure for yunzhi_course
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_course`;
CREATE TABLE `yunzhi_course`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '名称',
  `num` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '课程代码',
  `term` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '学期',
  `Teacher_id` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '任课教师',
  `课程类型` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '0选修，1必修',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_course
-- ----------------------------
INSERT INTO `yunzhi_course` VALUES (1, '数学', '123456', '2018-2019春', '某某某', 1, 123123, 123213);
INSERT INTO `yunzhi_course` VALUES (2, '商业网站开发', '654321', '2018-2019秋', '某某某', 0, 123213, 1232);

-- ----------------------------
-- Table structure for yunzhi_course_student
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_course_student`;
CREATE TABLE `yunzhi_course_student`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(11) UNSIGNED NOT NULL,
  `courseinfo_id` int(11) UNSIGNED NOT NULL,
  `column` int(11) UNSIGNED NOT NULL COMMENT '列',
  `row` int(11) UNSIGNED NOT NULL COMMENT '行',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_course_student
-- ----------------------------
INSERT INTO `yunzhi_course_student` VALUES (1, 123, 123, 7, 8);
INSERT INTO `yunzhi_course_student` VALUES (2, 456, 456, 6, 5);

-- ----------------------------
-- Table structure for yunzhi_courseinfo
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_courseinfo`;
CREATE TABLE `yunzhi_courseinfo`  (
  `id` int(30) NOT NULL,
  `course_num` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `week` int(5) NULL DEFAULT NULL,
  `weekday` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `time` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `klassroom_id` int(30) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_courseinfo
-- ----------------------------
INSERT INTO `yunzhi_courseinfo` VALUES (1, '123456', 12, '星期一', '第四大节', 0, 0);
INSERT INTO `yunzhi_courseinfo` VALUES (2, '654321', 11, '星期二', '第二大节', 0, 0);

-- ----------------------------
-- Table structure for yunzhi_klass
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_klass`;
CREATE TABLE `yunzhi_klass`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `academy` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '学院',
  `major` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '专业',
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '名称',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_klass
-- ----------------------------
INSERT INTO `yunzhi_klass` VALUES (1, '人工智能与数据科学学院', '计算机', '1806', 123123, 123213);
INSERT INTO `yunzhi_klass` VALUES (2, '电信学院', '电子', '181', 123123, 123213);

-- ----------------------------
-- Table structure for yunzhi_klass_course
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_klass_course`;
CREATE TABLE `yunzhi_klass_course`  (
  `id` int(30) NOT NULL,
  `klass_id` int(11) NOT NULL,
  `course_id` int(30) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_klass_course
-- ----------------------------
INSERT INTO `yunzhi_klass_course` VALUES (1, 123, 123);
INSERT INTO `yunzhi_klass_course` VALUES (2, 456, 456);

-- ----------------------------
-- Table structure for yunzhi_score_student
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_score_student`;
CREATE TABLE `yunzhi_score_student`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(11) UNSIGNED NOT NULL,
  `course_id` int(11) UNSIGNED NOT NULL,
  `score1` int(11) UNSIGNED NOT NULL COMMENT '平时成绩',
  `score2` int(11) UNSIGNED NOT NULL COMMENT '期末成绩',
  `scoresum` int(11) UNSIGNED NOT NULL COMMENT '总成绩',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_score_student
-- ----------------------------
INSERT INTO `yunzhi_score_student` VALUES (1, 123, 123, 1, 1, 2);
INSERT INTO `yunzhi_score_student` VALUES (2, 456, 456, 2, 2, 4);

-- ----------------------------
-- Table structure for yunzhi_student
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_student`;
CREATE TABLE `yunzhi_student`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `num` int(11) NOT NULL COMMENT '学号',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '姓名',
  `sex` tinyint(2) NOT NULL COMMENT '性别',
  `klass_id` int(11) UNSIGNED NOT NULL COMMENT '班级',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_student
-- ----------------------------
INSERT INTO `yunzhi_student` VALUES (1, 185555, '123', '王五', 1, 123, 0, 0);
INSERT INTO `yunzhi_student` VALUES (2, 184444, '456', '赵六', 0, 456, 0, 0);

-- ----------------------------
-- Table structure for yunzhi_teacher
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_teacher`;
CREATE TABLE `yunzhi_teacher`  (
  `id` int(30) NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `num` int(30) NOT NULL,
  `password` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sex` tinyint(1) NULL DEFAULT NULL,
  `create_time` int(11) NULL DEFAULT NULL,
  `update_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`, `num`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_teacher
-- ----------------------------
INSERT INTO `yunzhi_teacher` VALUES (0, '李四', 456, '456', 0, 0, 0);
INSERT INTO `yunzhi_teacher` VALUES (1, '张三', 123, '123', 1, 0, 0);

-- ----------------------------
-- Table structure for yunzhi_term
-- ----------------------------
DROP TABLE IF EXISTS `yunzhi_term`;
CREATE TABLE `yunzhi_term`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '名称',
  `start` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '学期起点',
  `end` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '学期终点',
  `length` int(100) NOT NULL COMMENT '学期长度/周',
  `state` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '0冻结，1激活',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yunzhi_term
-- ----------------------------
INSERT INTO `yunzhi_term` VALUES (1, '2018-2019春', 0, 0, 18, 0, 0, 0);
INSERT INTO `yunzhi_term` VALUES (2, '2018-2019秋', 0, 0, 20, 1, 0, 0);

SET FOREIGN_KEY_CHECKS = 1;
