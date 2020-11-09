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

 Date: 13/09/2020 20:56:23
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for im_group_member
-- ----------------------------
DROP TABLE IF EXISTS `im_group_member`;
CREATE TABLE `im_group_member`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录ID ',
  `group_id` int(10) UNSIGNED NOT NULL COMMENT '群组id',
  `member_id` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `created_at` int(10) UNSIGNED NOT NULL COMMENT '加入群组时间',
  `deleted_at` int(10) UNSIGNED NOT NULL COMMENT '退出群组时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '即时通讯群组成员表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
