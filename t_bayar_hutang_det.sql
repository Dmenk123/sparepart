/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50731
 Source Host           : localhost:3306
 Source Schema         : sparepart

 Target Server Type    : MySQL
 Target Server Version : 50731
 File Encoding         : 65001

 Date: 18/11/2021 16:13:41
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_bayar_hutang_det
-- ----------------------------
DROP TABLE IF EXISTS `t_bayar_hutang_det`;
CREATE TABLE `t_bayar_hutang_det`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_bayar_hutang` int(11) NULL DEFAULT NULL,
  `id_user` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nilai_bayar` float(20, 2) NULL DEFAULT NULL,
  `tanggal_bayar` date NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_bayar_hutang_det
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
