/*
 Navicat Premium Data Transfer

 Source Server         : local-mysql
 Source Server Type    : MySQL
 Source Server Version : 100413
 Source Host           : localhost:3306
 Source Schema         : sparepart

 Target Server Type    : MySQL
 Target Server Version : 100413
 File Encoding         : 65001

 Date: 22/02/2021 00:01:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for m_barang
-- ----------------------------
DROP TABLE IF EXISTS `m_barang`;
CREATE TABLE `m_barang`  (
  `id_barang` int(32) NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nama` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `harga` float(20, 0) NULL DEFAULT NULL,
  `gambar` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_kategori` int(32) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  `stok` int(32) NULL DEFAULT NULL,
  `shopee_link` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tokopedia_link` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `bukalapak_link` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `lazada_link` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `gambar_kedua` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `gambar_ketiga` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `gambar_keempat` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id_barang`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_barang
-- ----------------------------
INSERT INTO `m_barang` VALUES (1, 'SK001', 'Handle Break Rem', 15000, 'handle-break-rem-sk001.jpg', 1, '2020-12-10 09:47:13', '2021-02-12 23:37:17', NULL, 3, NULL, NULL, NULL, NULL, 'handle-break-rem-sk001-2.jpg', 'handle-break-rem-sk001-3.jpg', NULL, NULL);
INSERT INTO `m_barang` VALUES (2, 'SK002', 'Knalpot Racing R5344', 350000, 'knalpot-racing-r5344-sk002.jpg', 1, NULL, '2021-02-12 23:38:59', NULL, 4, NULL, NULL, NULL, NULL, 'knalpot-racing-r5344-sk002-2.jpg', 'knalpot-racing-r5344-sk002-3.jpg', NULL, NULL);
INSERT INTO `m_barang` VALUES (3, 'SK003', 'Stang Fitbar', 200000, 'stang-fitbar-sk003.jpg', 1, NULL, '2021-02-12 23:37:53', NULL, 2, NULL, NULL, NULL, NULL, 'stang-fitbar-sk003-2.jpg', 'stang-fitbar-sk003-3.jpg', NULL, NULL);
INSERT INTO `m_barang` VALUES (4, 'SK004', 'Stang Protapper coba', 150000, 'stang-protapper-coba-sk004.jpg', 1, NULL, '2021-02-21 23:18:34', NULL, 5, NULL, NULL, NULL, NULL, 'stang-protapper-coba-sk004-2.jpg', 'stang-protapper-coba-sk004-3.jpg', NULL, 'Stang istimewa coba coba, ukuran 49 x 29 cm, Harga Terjangkau Warna Polkadot');
INSERT INTO `m_barang` VALUES (7, 'SK005', 'Kaca Spion Racing', 35000, 'kaca-spion-racing-sk005.jpg', 2, NULL, '2021-02-12 23:40:14', NULL, 0, '', '', '', '', 'kaca-spion-racing-sk005-2.jpg', 'kaca-spion-racing-sk005-3.jpg', NULL, NULL);
INSERT INTO `m_barang` VALUES (12, 'SK006', 'Piston Brt', 500000, 'piston-brt-sk006.jpg', 2, NULL, '2021-02-12 23:40:54', NULL, 0, '', '', '', '', 'piston-brt-sk006-2.jpg', 'piston-brt-sk006-3.jpg', NULL, NULL);
INSERT INTO `m_barang` VALUES (15, 'SK007', 'Ban Motor Keren', 250000, 'ban-motor-keren-sk007.png', 2, NULL, '2021-02-12 23:41:18', NULL, 0, '', '', '', '', 'ban-motor-keren-sk007-2.jpg', 'ban-motor-keren-sk007-3.jpg', NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
