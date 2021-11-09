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

 Date: 09/11/2021 16:22:44
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_pengeluaran_lain_det
-- ----------------------------
DROP TABLE IF EXISTS `t_pengeluaran_lain_det`;
CREATE TABLE `t_pengeluaran_lain_det`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengeluaran_lain` int(11) NULL DEFAULT NULL,
  `id_barang` int(11) NULL DEFAULT NULL,
  `qty` int(11) NULL DEFAULT NULL,
  `nilai` float(20, 2) NULL DEFAULT NULL,
  `sub_total` float(20, 2) NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of t_pengeluaran_lain_det
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
