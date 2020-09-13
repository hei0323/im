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

 Date: 13/09/2020 20:56:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for im_msg_offline
-- ----------------------------
DROP TABLE IF EXISTS `im_msg_offline`;
CREATE TABLE `im_msg_offline`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
  `msg_id` bigint(20) UNSIGNED NOT NULL COMMENT '消息ID ',
  `contents` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '消息内容',
  `sender_id` int(10) UNSIGNED NOT NULL COMMENT '发送方ID ',
  `receiver_id` int(10) UNSIGNED NOT NULL COMMENT '接收方ID ',
  `group_id` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所属分组ID',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '类型 0游客 1个人 2群组 3通告',
  `created_at` int(10) UNSIGNED NOT NULL COMMENT '发送时间',
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `deleted_at` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '即时通讯消息离线存储表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
