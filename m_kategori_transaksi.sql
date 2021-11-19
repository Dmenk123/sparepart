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

 Date: 19/11/2021 15:17:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for m_kategori_transaksi
-- ----------------------------
DROP TABLE IF EXISTS `m_kategori_transaksi`;
CREATE TABLE `m_kategori_transaksi`  (
  `id_kategori_trans` int(24) NOT NULL AUTO_INCREMENT,
  `nama_kategori_trans` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_penerimaan` int(1) NULL DEFAULT NULL COMMENT '1 : penerimaan, null : pengeluaran',
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `deleted_at` datetime NULL DEFAULT NULL,
  `kode_akun` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_lain` int(1) NULL DEFAULT NULL,
  `singkatan` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `enable_update` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id_kategori_trans`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of m_kategori_transaksi
-- ----------------------------
INSERT INTO `m_kategori_transaksi` VALUES (1, 'Pembelian', NULL, '2021-10-12 00:58:51', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `m_kategori_transaksi` VALUES (2, 'Penjualan', 1, '2021-10-12 00:58:51', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `m_kategori_transaksi` VALUES (3, 'Stok Awal', 1, '2021-10-12 00:58:51', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `m_kategori_transaksi` VALUES (4, 'Penerimaan Pembelian', 1, '2021-10-12 00:58:51', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `m_kategori_transaksi` VALUES (5, 'Penerimaan Retur', NULL, '2021-10-12 00:58:51', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `m_kategori_transaksi` VALUES (6, 'Retur', NULL, '2021-10-12 00:58:51', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `m_kategori_transaksi` VALUES (7, 'Penyesuaian Stok', NULL, '2021-10-12 00:58:51', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `m_kategori_transaksi` VALUES (8, 'Penerimaan Lain-Lain', NULL, '2021-10-12 00:58:51', NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `m_kategori_transaksi` VALUES (9, 'Biaya Karyawan', NULL, NULL, NULL, NULL, NULL, 1, 'BPG', 1);
INSERT INTO `m_kategori_transaksi` VALUES (10, 'Biaya Listrik', NULL, NULL, NULL, NULL, NULL, 1, 'BLS', 1);
INSERT INTO `m_kategori_transaksi` VALUES (11, 'Biaya Air', NULL, NULL, NULL, NULL, NULL, 1, 'BAR', 1);
INSERT INTO `m_kategori_transaksi` VALUES (12, 'Santunan Yatim Piatu', NULL, NULL, NULL, NULL, NULL, 1, 'BYP', 1);
INSERT INTO `m_kategori_transaksi` VALUES (13, 'Investasi Pemilik', 1, NULL, NULL, NULL, NULL, 1, 'IVT', 1);
INSERT INTO `m_kategori_transaksi` VALUES (14, 'Bantuan Pemerintah', 1, NULL, NULL, NULL, NULL, 1, 'BTP', 1);
INSERT INTO `m_kategori_transaksi` VALUES (15, 'Penerimaan Lainnya', 1, NULL, NULL, NULL, NULL, 1, 'PIL', 1);
INSERT INTO `m_kategori_transaksi` VALUES (16, 'Pembayaran Hutang', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
