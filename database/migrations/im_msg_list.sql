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

 Date: 13/09/2020 20:56:41
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for im_msg_list
-- ----------------------------
DROP TABLE IF EXISTS `im_msg_list`;
CREATE TABLE `im_msg_list`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `msg_id` bigint(20) UNSIGNED NOT NULL COMMENT '最后消息ID',
  `sender_id` bigint(20) NOT NULL COMMENT '发送方ID',
  `receiver_id` bigint(20) UNSIGNED NOT NULL COMMENT '接收方ID ',
  `group_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '所属分组id',
  `contents` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '消息内容',
  `type` tinyint(4) NOT NULL COMMENT '消息类型 1个人 2群组 3通告',
  `created_at` int(10) UNSIGNED NOT NULL COMMENT '创建时间',
  `updated_at` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
  `deleted_at` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_receive_send`(`sender_id`, `receiver_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '即时通讯消息列表' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
