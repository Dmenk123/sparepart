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

 Date: 15/11/2021 15:48:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_lap_keuangan
-- ----------------------------
DROP TABLE IF EXISTS `t_lap_keuangan`;
CREATE TABLE `t_lap_keuangan`  (
  `id_laporan` int(64) NOT NULL AUTO_INCREMENT,
  `id_laporan_det` int(64) NOT NULL,
  `tgl_laporan` date NULL DEFAULT NULL,
  `bulan_laporan` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tahun_laporan` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `penerimaan` float(20, 2) NULL DEFAULT 0.00,
  `pengeluaran` float(20, 2) NULL DEFAULT 0.00,
  `hutang` float(20, 2) NULL DEFAULT 0.00,
  `piutang` float(20, 2) NULL DEFAULT 0.00,
  `id_kategori_trans` int(11) NULL DEFAULT NULL,
  `kode_reff` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'jika ada (referensi transaksi : disarankan menggunakan kode transaksi (bukan id transaksi))',
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  `kode_reff2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_laporan`, `id_laporan_det`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of t_lap_keuangan
-- ----------------------------
INSERT INTO `t_lap_keuangan` VALUES (1, 1, '2021-11-15', '11', '2021', 0.00, 525000.00, 0.00, 525000.00, 1, 'ORD-K152021001', '2021-11-15 10:56:13', '2021-11-15 10:56:30', NULL, NULL);
INSERT INTO `t_lap_keuangan` VALUES (1, 2, '2021-11-15', '11', '2021', 0.00, 0.00, 0.00, -25000.00, 4, 'ORD-K152021001', '2021-11-15 15:12:35', NULL, NULL, 'RCV-K152021006');
INSERT INTO `t_lap_keuangan` VALUES (1, 3, '2021-11-15', '11', '2021', 0.00, 0.00, 0.00, -250000.00, 4, 'ORD-K152021001', '2021-11-15 15:12:35', NULL, NULL, 'RCV-K152021006');
INSERT INTO `t_lap_keuangan` VALUES (1, 5, '2021-11-15', '11', '2021', 0.00, 0.00, 0.00, -125000.00, 4, 'ORD-K152021001', '2021-11-15 15:13:02', NULL, NULL, 'RCV-K152021008');
INSERT INTO `t_lap_keuangan` VALUES (1, 6, '2021-11-15', '11', '2021', 0.00, 0.00, 0.00, -125000.00, 4, 'ORD-K152021001', '2021-11-15 15:16:37', NULL, NULL, 'RCV-K152021009');

SET FOREIGN_KEY_CHECKS = 1;
