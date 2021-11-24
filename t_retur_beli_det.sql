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

 Date: 24/11/2021 15:26:16
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_retur_beli_det
-- ----------------------------
DROP TABLE IF EXISTS `t_retur_beli_det`;
CREATE TABLE `t_retur_beli_det`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_retur_beli` int(11) NULL DEFAULT NULL,
  `id_stok` int(11) NULL DEFAULT NULL,
  `qty` int(11) NULL DEFAULT NULL,
  `harga` float(20, 2) NULL DEFAULT NULL,
  `harga_total` float(20, 2) NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  `is_terima` int(1) NULL DEFAULT NULL,
  `qty_terima` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of t_retur_beli_det
-- ----------------------------
INSERT INTO `t_retur_beli_det` VALUES (1, 1, 1, 10, 25000.00, 250000.00, '2021-11-21 17:44:51', NULL, '2021-11-21 20:38:52', NULL, NULL);
INSERT INTO `t_retur_beli_det` VALUES (2, 1, 1, 10, 25000.00, 250000.00, '2021-11-21 19:53:21', NULL, '2021-11-21 20:38:52', NULL, NULL);
INSERT INTO `t_retur_beli_det` VALUES (3, 1, 2, 5, 25000.00, 125000.00, '2021-11-21 20:36:43', NULL, '2021-11-21 20:38:52', NULL, NULL);
INSERT INTO `t_retur_beli_det` VALUES (4, 2, 1, 10, 25000.00, 250000.00, '2021-11-22 21:46:00', NULL, NULL, NULL, NULL);
INSERT INTO `t_retur_beli_det` VALUES (5, 2, 2, 10, 25000.00, 250000.00, '2021-11-22 21:46:04', NULL, NULL, NULL, NULL);
INSERT INTO `t_retur_beli_det` VALUES (6, 3, 2, 10, 10000.00, 100000.00, '2021-11-24 02:36:38', NULL, NULL, NULL, NULL);
INSERT INTO `t_retur_beli_det` VALUES (7, 4, 2, 5, 10000.00, 50000.00, '2021-11-24 03:30:26', NULL, NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
