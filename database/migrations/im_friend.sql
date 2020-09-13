/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50728
 Source Host           : 127.0.0.1:3306
 Source Schema         : newshop

 Target Server Type    : MySQL
 Target Server Version : 50728
 File Encoding         : 65001

 Date: 13/09/2020 20:55:52
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for im_friend
-- ----------------------------
DROP TABLE IF EXISTS `im_friend`;
CREATE TABLE `im_friend`  (
  `friend_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录ID ',
  `master_id` bigint(20) UNSIGNED NOT NULL COMMENT '主方',
  `slave_id` bigint(20) UNSIGNED NOT NULL COMMENT '从方',
  `created_at` int(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `deleted_at` int(10) UNSIGNED NOT NULL COMMENT '删除时间',
  PRIMARY KEY (`friend_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '即时通讯好友表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
