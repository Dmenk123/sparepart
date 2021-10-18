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

 Date: 19/10/2021 06:19:18
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for m_agen
-- ----------------------------
DROP TABLE IF EXISTS `m_agen`;
CREATE TABLE `m_agen`  (
  `id_agen` int(32) NOT NULL AUTO_INCREMENT,
  `nama_perusahaan` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alamat` varchar(700) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `telp` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `produk` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_agen`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_agen
-- ----------------------------
INSERT INTO `m_agen` VALUES (1, 'PT. PRIMA PERKASA', 'Jl. Tanggulangin Pasuruan', '031736463', 'Stang Moptor Ninja', '2020-12-16 14:06:39', '2020-12-16 14:42:06', NULL);
INSERT INTO `m_agen` VALUES (2, 'PT. SANJAYA PRIMA', 'Jl. kaliurang sidoarjo', '08138743764', 'jok motor racing', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for m_barang
-- ----------------------------
DROP TABLE IF EXISTS `m_barang`;
CREATE TABLE `m_barang`  (
  `id_barang` int(32) NOT NULL AUTO_INCREMENT,
  `id_satuan` int(4) NULL DEFAULT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nama` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `harga` float(20, 0) NULL DEFAULT NULL,
  `gambar` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_kategori` int(32) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  `shopee_link` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tokopedia_link` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `bukalapak_link` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `lazada_link` varchar(350) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `gambar_kedua` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `gambar_ketiga` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `gambar_keempat` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`id_barang`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_barang
-- ----------------------------
INSERT INTO `m_barang` VALUES (3, 1, 'SKU001', 'Handle Kuning', 10000, 'handle-kuning-sku001.jpg', 2, NULL, '2021-10-14 23:40:52', NULL, '', '', '', '', 'handle-kuning-sku001-2.jpg', 'user_default.png', 'user_default.png', NULL);

-- ----------------------------
-- Table structure for m_barang_copy1
-- ----------------------------
DROP TABLE IF EXISTS `m_barang_copy1`;
CREATE TABLE `m_barang_copy1`  (
  `id_barang` int(32) NOT NULL AUTO_INCREMENT,
  `id_satuan` int(4) NULL DEFAULT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nama` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `harga` float(20, 0) NULL DEFAULT NULL,
  `gambar` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_kategori` int(32) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
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
-- Records of m_barang_copy1
-- ----------------------------
INSERT INTO `m_barang_copy1` VALUES (1, NULL, 'SK001', 'Handle Break Rem', 15000, 'handle-break-rem-sk001.jpg', 1, '2020-12-10 09:47:13', '2021-02-12 23:37:17', NULL, NULL, NULL, NULL, NULL, 'handle-break-rem-sk001-2.jpg', 'handle-break-rem-sk001-3.jpg', NULL, NULL);
INSERT INTO `m_barang_copy1` VALUES (2, NULL, 'SK002', 'Knalpot Racing R5344', 350000, 'knalpot-racing-r5344-sk002.jpg', 1, NULL, '2021-02-12 23:38:59', NULL, NULL, NULL, NULL, NULL, 'knalpot-racing-r5344-sk002-2.jpg', 'knalpot-racing-r5344-sk002-3.jpg', NULL, NULL);
INSERT INTO `m_barang_copy1` VALUES (3, NULL, 'SK003', 'Stang Fitbar', 200000, 'stang-fitbar-sk003.jpg', 1, NULL, '2021-02-12 23:37:53', NULL, NULL, NULL, NULL, NULL, 'stang-fitbar-sk003-2.jpg', 'stang-fitbar-sk003-3.jpg', NULL, NULL);
INSERT INTO `m_barang_copy1` VALUES (4, NULL, 'SK004', 'Stang Protapper coba', 150000, 'stang-protapper-coba-sk004.jpg', 1, NULL, '2021-02-21 23:18:34', NULL, NULL, NULL, NULL, NULL, 'stang-protapper-coba-sk004-2.jpg', 'stang-protapper-coba-sk004-3.jpg', NULL, 'Stang istimewa coba coba, ukuran 49 x 29 cm, Harga Terjangkau Warna Polkadot');
INSERT INTO `m_barang_copy1` VALUES (7, NULL, 'SK005', 'Kaca Spion Racing', 35000, 'kaca-spion-racing-sk005.jpg', 2, NULL, '2021-02-12 23:40:14', NULL, '', '', '', '', 'kaca-spion-racing-sk005-2.jpg', 'kaca-spion-racing-sk005-3.jpg', NULL, NULL);
INSERT INTO `m_barang_copy1` VALUES (12, NULL, 'SK006', 'Piston Brt', 500000, 'piston-brt-sk006.jpg', 2, NULL, '2021-02-12 23:40:54', NULL, '', '', '', '', 'piston-brt-sk006-2.jpg', 'piston-brt-sk006-3.jpg', NULL, NULL);
INSERT INTO `m_barang_copy1` VALUES (15, NULL, 'SK007', 'Ban Motor Keren', 250000, 'ban-motor-keren-sk007.png', 2, NULL, '2021-02-12 23:41:18', NULL, '', '', '', '', 'ban-motor-keren-sk007-2.jpg', 'ban-motor-keren-sk007-3.jpg', NULL, NULL);

-- ----------------------------
-- Table structure for m_gudang
-- ----------------------------
DROP TABLE IF EXISTS `m_gudang`;
CREATE TABLE `m_gudang`  (
  `id_gudang` int(4) NULL DEFAULT NULL,
  `nama_gudang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_gudang
-- ----------------------------
INSERT INTO `m_gudang` VALUES (1, 'Gudang Utama', '2021-10-14 22:42:35', NULL, NULL);

-- ----------------------------
-- Table structure for m_kategori
-- ----------------------------
DROP TABLE IF EXISTS `m_kategori`;
CREATE TABLE `m_kategori`  (
  `id_kategori` int(20) NOT NULL,
  `nama_kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_kategori`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of m_kategori
-- ----------------------------
INSERT INTO `m_kategori` VALUES (1, 'Honda');
INSERT INTO `m_kategori` VALUES (2, 'Yamaha');
INSERT INTO `m_kategori` VALUES (3, 'Kawasaki');
INSERT INTO `m_kategori` VALUES (4, 'Suzuki');
INSERT INTO `m_kategori` VALUES (5, 'KTM');

-- ----------------------------
-- Table structure for m_kategori_transaksi
-- ----------------------------
DROP TABLE IF EXISTS `m_kategori_transaksi`;
CREATE TABLE `m_kategori_transaksi`  (
  `id_kategori_trans` int(24) NOT NULL AUTO_INCREMENT,
  `nama_kategori_trans` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `is_penerimaan` int(1) NULL DEFAULT NULL COMMENT '1 : penerimaan, null : pengeluaran',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  `kode_akun` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_kategori_trans`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_kategori_transaksi
-- ----------------------------
INSERT INTO `m_kategori_transaksi` VALUES (1, 'Pembelian', NULL, '2021-10-12 00:58:51', NULL, NULL, NULL);
INSERT INTO `m_kategori_transaksi` VALUES (2, 'Penjualan', 1, '2021-10-12 00:58:51', NULL, NULL, NULL);
INSERT INTO `m_kategori_transaksi` VALUES (3, 'Stok Awal', 1, '2021-10-12 00:58:51', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for m_menu
-- ----------------------------
DROP TABLE IF EXISTS `m_menu`;
CREATE TABLE `m_menu`  (
  `id` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL,
  `nama` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `judul` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `link` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `aktif` int(1) NULL DEFAULT NULL,
  `tingkat` int(11) NULL DEFAULT NULL,
  `urutan` int(11) NULL DEFAULT NULL,
  `add_button` int(1) NULL DEFAULT NULL,
  `edit_button` int(1) NULL DEFAULT NULL,
  `delete_button` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of m_menu
-- ----------------------------
INSERT INTO `m_menu` VALUES (1, 0, 'Dashboard', 'Dashboard', 'home', 'flaticon2-architecture-and-city', 1, 1, 1, 0, 0, 0);
INSERT INTO `m_menu` VALUES (2, 0, 'Setting (Administrator)', 'Setting', '', 'flaticon2-gear', 1, 1, 100, 0, 0, 0);
INSERT INTO `m_menu` VALUES (3, 2, 'Setting Menu', 'Setting Menu', 'set_menu', 'flaticon-grid-menu', 1, 2, 2, 1, 1, 1);
INSERT INTO `m_menu` VALUES (4, 2, 'Setting Role', 'Setting Role', 'set_role', 'flaticon-network', 1, 2, 1, 1, 1, 1);
INSERT INTO `m_menu` VALUES (6, 0, 'Master', 'Master', '', 'flaticon-folder-1', 1, 1, 2, 0, 0, 0);
INSERT INTO `m_menu` VALUES (7, 6, 'Data User', 'Data User', 'master_user', 'flaticon-users', 1, 2, 1, 1, 1, 1);
INSERT INTO `m_menu` VALUES (8, 6, 'Data Barang', 'Master Barang', 'master_barang', 'flaticon-clock', 1, 1, 2, 1, 1, 1);
INSERT INTO `m_menu` VALUES (9, 6, 'Data Agen', 'Data Agen', 'master_agen', 'flaticon-book', 1, 1, 3, 1, 1, 1);
INSERT INTO `m_menu` VALUES (10, 6, 'Data Pelanggan', 'Data Pelanggan', 'master_pelanggan', 'flaticon-user', 1, 2, 5, 1, 1, 1);
INSERT INTO `m_menu` VALUES (11, 0, 'Invoice', 'Invoice', 'invoice', 'flaticon-cart', 0, 1, 3, 0, 0, 0);
INSERT INTO `m_menu` VALUES (12, 11, 'Invoice Penjualan', 'Invoice Penjualan', NULL, 'flaticon-price-tag', 0, 3, 1, 1, 1, 1);
INSERT INTO `m_menu` VALUES (13, 0, 'Inventory', 'Inventory', '', 'flaticon-open-box', 1, 1, 4, 0, 0, 0);
INSERT INTO `m_menu` VALUES (14, 13, 'Stok Barang', 'Stok Barang', 'stok_barang', 'flaticon-interface-3', 1, 1, 1, 1, 1, 1);
INSERT INTO `m_menu` VALUES (15, 0, 'Transaksi', 'Transaksi', '', 'flaticon-list-3', 1, 1, 5, 0, 0, 0);
INSERT INTO `m_menu` VALUES (16, 15, 'Pembelian', 'Pembelian', 'pembelian', 'flaticon2-shopping-cart-1', 1, 2, 1, 1, 1, 1);
INSERT INTO `m_menu` VALUES (17, 15, 'Penjualan', 'Penjualan', 'penjualan', 'flaticon-truck', 1, 2, 2, 1, 1, 1);
INSERT INTO `m_menu` VALUES (18, 13, 'Penerimaan Barang', 'Penerimaan Barang', 'barang_masuk', 'flaticon2-box-1', 1, 2, 4, 1, 1, 1);

-- ----------------------------
-- Table structure for m_pelanggan
-- ----------------------------
DROP TABLE IF EXISTS `m_pelanggan`;
CREATE TABLE `m_pelanggan`  (
  `id_pelanggan` int(32) NOT NULL AUTO_INCREMENT,
  `nama_pembeli` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `alamat` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_telp` varchar(14) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nama_toko` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_provinsi` int(10) NULL DEFAULT NULL,
  `id_kota` int(10) NULL DEFAULT NULL,
  `kecamatan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_pelanggan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_pelanggan
-- ----------------------------
INSERT INTO `m_pelanggan` VALUES (3, 'Icank', 'Jl. Raya Tubanan No. 40 Keluraha karangpoh', '08921484255', 'icankmotor@gmail.com', 'Icank Motor services', 35, 3578, 'Tandes', NULL, '2021-01-11 11:02:31', NULL);

-- ----------------------------
-- Table structure for m_role
-- ----------------------------
DROP TABLE IF EXISTS `m_role`;
CREATE TABLE `m_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `keterangan` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '',
  `aktif` int(1) NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of m_role
-- ----------------------------
INSERT INTO `m_role` VALUES (1, 'Administrator', 'Level Administrator Role', 1);
INSERT INTO `m_role` VALUES (2, 'Staff Admin', 'Role Untuk Staff Admin', 1);
INSERT INTO `m_role` VALUES (3, 'Kasir', 'Role Untuk Staff Kasir', 1);
INSERT INTO `m_role` VALUES (4, 'Staff Gudang', 'Role Untuk Staff Gudang', 1);
INSERT INTO `m_role` VALUES (5, 'Staff Keuangan', 'Role Untuk Staff Keuangan', 1);
INSERT INTO `m_role` VALUES (6, 'Sales', 'Role Untuk Sales', 1);

-- ----------------------------
-- Table structure for m_satuan
-- ----------------------------
DROP TABLE IF EXISTS `m_satuan`;
CREATE TABLE `m_satuan`  (
  `id_satuan` int(4) NULL DEFAULT NULL,
  `nama_satuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_satuan
-- ----------------------------
INSERT INTO `m_satuan` VALUES (1, 'Pcs', '2021-10-14 23:13:21', NULL, NULL);

-- ----------------------------
-- Table structure for m_user
-- ----------------------------
DROP TABLE IF EXISTS `m_user`;
CREATE TABLE `m_user`  (
  `id` int(64) NOT NULL AUTO_INCREMENT,
  `id_role` int(64) NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` int(1) NULL DEFAULT NULL,
  `last_login` datetime(0) NULL DEFAULT NULL,
  `kode_user` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  `nama` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of m_user
-- ----------------------------
INSERT INTO `m_user` VALUES (1, 1, 'admin', 'SnIvSVV6c2UwdWhKS1ZKMDluUlp4dz09', 1, '2021-10-19 05:41:19', 'USR-00001', 'admin-1610858192.jpg', NULL, '2021-01-17 11:36:32', NULL, 'admin');
INSERT INTO `m_user` VALUES (2, 1, 'coba', 'Tzg1eTllUlU2a2xNQk5yYktIM1pwUT09', NULL, NULL, 'USR-00002', 'coba-1602775328.jpg', '2020-10-15 22:22:08', '2020-10-15 22:43:54', '2020-10-15 22:58:50', 'coba saja');
INSERT INTO `m_user` VALUES (3, 6, 'alsyafin', 'SnIvSVV6c2UwdWhKS1ZKMDluUlp4dz09', 1, NULL, 'USR-00003', 'user_default.png', '2021-01-15 09:07:51', NULL, NULL, 'Alsuafinollah');
INSERT INTO `m_user` VALUES (4, 6, 'zamroni', 'SnIvSVV6c2UwdWhKS1ZKMDluUlp4dz09', 1, NULL, 'USR-00004', 'user_default.png', '2021-01-15 09:08:19', NULL, NULL, 'Moch Zamroni');

-- ----------------------------
-- Table structure for t_kota
-- ----------------------------
DROP TABLE IF EXISTS `t_kota`;
CREATE TABLE `t_kota`  (
  `id_kota` char(4) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `id_provinsi` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nama_kota` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_kota`) USING BTREE,
  INDEX `regencies_province_id_index`(`id_provinsi`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_kota
-- ----------------------------
INSERT INTO `t_kota` VALUES ('1101', '11', 'KABUPATEN SIMEULUE');
INSERT INTO `t_kota` VALUES ('1102', '11', 'KABUPATEN ACEH SINGKIL');
INSERT INTO `t_kota` VALUES ('1103', '11', 'KABUPATEN ACEH SELATAN');
INSERT INTO `t_kota` VALUES ('1104', '11', 'KABUPATEN ACEH TENGGARA');
INSERT INTO `t_kota` VALUES ('1105', '11', 'KABUPATEN ACEH TIMUR');
INSERT INTO `t_kota` VALUES ('1106', '11', 'KABUPATEN ACEH TENGAH');
INSERT INTO `t_kota` VALUES ('1107', '11', 'KABUPATEN ACEH BARAT');
INSERT INTO `t_kota` VALUES ('1108', '11', 'KABUPATEN ACEH BESAR');
INSERT INTO `t_kota` VALUES ('1109', '11', 'KABUPATEN PIDIE');
INSERT INTO `t_kota` VALUES ('1110', '11', 'KABUPATEN BIREUEN');
INSERT INTO `t_kota` VALUES ('1111', '11', 'KABUPATEN ACEH UTARA');
INSERT INTO `t_kota` VALUES ('1112', '11', 'KABUPATEN ACEH BARAT DAYA');
INSERT INTO `t_kota` VALUES ('1113', '11', 'KABUPATEN GAYO LUES');
INSERT INTO `t_kota` VALUES ('1114', '11', 'KABUPATEN ACEH TAMIANG');
INSERT INTO `t_kota` VALUES ('1115', '11', 'KABUPATEN NAGAN RAYA');
INSERT INTO `t_kota` VALUES ('1116', '11', 'KABUPATEN ACEH JAYA');
INSERT INTO `t_kota` VALUES ('1117', '11', 'KABUPATEN BENER MERIAH');
INSERT INTO `t_kota` VALUES ('1118', '11', 'KABUPATEN PIDIE JAYA');
INSERT INTO `t_kota` VALUES ('1171', '11', 'KOTA BANDA ACEH');
INSERT INTO `t_kota` VALUES ('1172', '11', 'KOTA SABANG');
INSERT INTO `t_kota` VALUES ('1173', '11', 'KOTA LANGSA');
INSERT INTO `t_kota` VALUES ('1174', '11', 'KOTA LHOKSEUMAWE');
INSERT INTO `t_kota` VALUES ('1175', '11', 'KOTA SUBULUSSALAM');
INSERT INTO `t_kota` VALUES ('1201', '12', 'KABUPATEN NIAS');
INSERT INTO `t_kota` VALUES ('1202', '12', 'KABUPATEN MANDAILING NATAL');
INSERT INTO `t_kota` VALUES ('1203', '12', 'KABUPATEN TAPANULI SELATAN');
INSERT INTO `t_kota` VALUES ('1204', '12', 'KABUPATEN TAPANULI TENGAH');
INSERT INTO `t_kota` VALUES ('1205', '12', 'KABUPATEN TAPANULI UTARA');
INSERT INTO `t_kota` VALUES ('1206', '12', 'KABUPATEN TOBA SAMOSIR');
INSERT INTO `t_kota` VALUES ('1207', '12', 'KABUPATEN LABUHAN BATU');
INSERT INTO `t_kota` VALUES ('1208', '12', 'KABUPATEN ASAHAN');
INSERT INTO `t_kota` VALUES ('1209', '12', 'KABUPATEN SIMALUNGUN');
INSERT INTO `t_kota` VALUES ('1210', '12', 'KABUPATEN DAIRI');
INSERT INTO `t_kota` VALUES ('1211', '12', 'KABUPATEN KARO');
INSERT INTO `t_kota` VALUES ('1212', '12', 'KABUPATEN DELI SERDANG');
INSERT INTO `t_kota` VALUES ('1213', '12', 'KABUPATEN LANGKAT');
INSERT INTO `t_kota` VALUES ('1214', '12', 'KABUPATEN NIAS SELATAN');
INSERT INTO `t_kota` VALUES ('1215', '12', 'KABUPATEN HUMBANG HASUNDUTAN');
INSERT INTO `t_kota` VALUES ('1216', '12', 'KABUPATEN PAKPAK BHARAT');
INSERT INTO `t_kota` VALUES ('1217', '12', 'KABUPATEN SAMOSIR');
INSERT INTO `t_kota` VALUES ('1218', '12', 'KABUPATEN SERDANG BEDAGAI');
INSERT INTO `t_kota` VALUES ('1219', '12', 'KABUPATEN BATU BARA');
INSERT INTO `t_kota` VALUES ('1220', '12', 'KABUPATEN PADANG LAWAS UTARA');
INSERT INTO `t_kota` VALUES ('1221', '12', 'KABUPATEN PADANG LAWAS');
INSERT INTO `t_kota` VALUES ('1222', '12', 'KABUPATEN LABUHAN BATU SELATAN');
INSERT INTO `t_kota` VALUES ('1223', '12', 'KABUPATEN LABUHAN BATU UTARA');
INSERT INTO `t_kota` VALUES ('1224', '12', 'KABUPATEN NIAS UTARA');
INSERT INTO `t_kota` VALUES ('1225', '12', 'KABUPATEN NIAS BARAT');
INSERT INTO `t_kota` VALUES ('1271', '12', 'KOTA SIBOLGA');
INSERT INTO `t_kota` VALUES ('1272', '12', 'KOTA TANJUNG BALAI');
INSERT INTO `t_kota` VALUES ('1273', '12', 'KOTA PEMATANG SIANTAR');
INSERT INTO `t_kota` VALUES ('1274', '12', 'KOTA TEBING TINGGI');
INSERT INTO `t_kota` VALUES ('1275', '12', 'KOTA MEDAN');
INSERT INTO `t_kota` VALUES ('1276', '12', 'KOTA BINJAI');
INSERT INTO `t_kota` VALUES ('1277', '12', 'KOTA PADANGSIDIMPUAN');
INSERT INTO `t_kota` VALUES ('1278', '12', 'KOTA GUNUNGSITOLI');
INSERT INTO `t_kota` VALUES ('1301', '13', 'KABUPATEN KEPULAUAN MENTAWAI');
INSERT INTO `t_kota` VALUES ('1302', '13', 'KABUPATEN PESISIR SELATAN');
INSERT INTO `t_kota` VALUES ('1303', '13', 'KABUPATEN SOLOK');
INSERT INTO `t_kota` VALUES ('1304', '13', 'KABUPATEN SIJUNJUNG');
INSERT INTO `t_kota` VALUES ('1305', '13', 'KABUPATEN TANAH DATAR');
INSERT INTO `t_kota` VALUES ('1306', '13', 'KABUPATEN PADANG PARIAMAN');
INSERT INTO `t_kota` VALUES ('1307', '13', 'KABUPATEN AGAM');
INSERT INTO `t_kota` VALUES ('1308', '13', 'KABUPATEN LIMA PULUH KOTA');
INSERT INTO `t_kota` VALUES ('1309', '13', 'KABUPATEN PASAMAN');
INSERT INTO `t_kota` VALUES ('1310', '13', 'KABUPATEN SOLOK SELATAN');
INSERT INTO `t_kota` VALUES ('1311', '13', 'KABUPATEN DHARMASRAYA');
INSERT INTO `t_kota` VALUES ('1312', '13', 'KABUPATEN PASAMAN BARAT');
INSERT INTO `t_kota` VALUES ('1371', '13', 'KOTA PADANG');
INSERT INTO `t_kota` VALUES ('1372', '13', 'KOTA SOLOK');
INSERT INTO `t_kota` VALUES ('1373', '13', 'KOTA SAWAH LUNTO');
INSERT INTO `t_kota` VALUES ('1374', '13', 'KOTA PADANG PANJANG');
INSERT INTO `t_kota` VALUES ('1375', '13', 'KOTA BUKITTINGGI');
INSERT INTO `t_kota` VALUES ('1376', '13', 'KOTA PAYAKUMBUH');
INSERT INTO `t_kota` VALUES ('1377', '13', 'KOTA PARIAMAN');
INSERT INTO `t_kota` VALUES ('1401', '14', 'KABUPATEN KUANTAN SINGINGI');
INSERT INTO `t_kota` VALUES ('1402', '14', 'KABUPATEN INDRAGIRI HULU');
INSERT INTO `t_kota` VALUES ('1403', '14', 'KABUPATEN INDRAGIRI HILIR');
INSERT INTO `t_kota` VALUES ('1404', '14', 'KABUPATEN PELALAWAN');
INSERT INTO `t_kota` VALUES ('1405', '14', 'KABUPATEN S I A K');
INSERT INTO `t_kota` VALUES ('1406', '14', 'KABUPATEN KAMPAR');
INSERT INTO `t_kota` VALUES ('1407', '14', 'KABUPATEN ROKAN HULU');
INSERT INTO `t_kota` VALUES ('1408', '14', 'KABUPATEN BENGKALIS');
INSERT INTO `t_kota` VALUES ('1409', '14', 'KABUPATEN ROKAN HILIR');
INSERT INTO `t_kota` VALUES ('1410', '14', 'KABUPATEN KEPULAUAN MERANTI');
INSERT INTO `t_kota` VALUES ('1471', '14', 'KOTA PEKANBARU');
INSERT INTO `t_kota` VALUES ('1473', '14', 'KOTA D U M A I');
INSERT INTO `t_kota` VALUES ('1501', '15', 'KABUPATEN KERINCI');
INSERT INTO `t_kota` VALUES ('1502', '15', 'KABUPATEN MERANGIN');
INSERT INTO `t_kota` VALUES ('1503', '15', 'KABUPATEN SAROLANGUN');
INSERT INTO `t_kota` VALUES ('1504', '15', 'KABUPATEN BATANG HARI');
INSERT INTO `t_kota` VALUES ('1505', '15', 'KABUPATEN MUARO JAMBI');
INSERT INTO `t_kota` VALUES ('1506', '15', 'KABUPATEN TANJUNG JABUNG TIMUR');
INSERT INTO `t_kota` VALUES ('1507', '15', 'KABUPATEN TANJUNG JABUNG BARAT');
INSERT INTO `t_kota` VALUES ('1508', '15', 'KABUPATEN TEBO');
INSERT INTO `t_kota` VALUES ('1509', '15', 'KABUPATEN BUNGO');
INSERT INTO `t_kota` VALUES ('1571', '15', 'KOTA JAMBI');
INSERT INTO `t_kota` VALUES ('1572', '15', 'KOTA SUNGAI PENUH');
INSERT INTO `t_kota` VALUES ('1601', '16', 'KABUPATEN OGAN KOMERING ULU');
INSERT INTO `t_kota` VALUES ('1602', '16', 'KABUPATEN OGAN KOMERING ILIR');
INSERT INTO `t_kota` VALUES ('1603', '16', 'KABUPATEN MUARA ENIM');
INSERT INTO `t_kota` VALUES ('1604', '16', 'KABUPATEN LAHAT');
INSERT INTO `t_kota` VALUES ('1605', '16', 'KABUPATEN MUSI RAWAS');
INSERT INTO `t_kota` VALUES ('1606', '16', 'KABUPATEN MUSI BANYUASIN');
INSERT INTO `t_kota` VALUES ('1607', '16', 'KABUPATEN BANYU ASIN');
INSERT INTO `t_kota` VALUES ('1608', '16', 'KABUPATEN OGAN KOMERING ULU SELATAN');
INSERT INTO `t_kota` VALUES ('1609', '16', 'KABUPATEN OGAN KOMERING ULU TIMUR');
INSERT INTO `t_kota` VALUES ('1610', '16', 'KABUPATEN OGAN ILIR');
INSERT INTO `t_kota` VALUES ('1611', '16', 'KABUPATEN EMPAT LAWANG');
INSERT INTO `t_kota` VALUES ('1612', '16', 'KABUPATEN PENUKAL ABAB LEMATANG ILIR');
INSERT INTO `t_kota` VALUES ('1613', '16', 'KABUPATEN MUSI RAWAS UTARA');
INSERT INTO `t_kota` VALUES ('1671', '16', 'KOTA PALEMBANG');
INSERT INTO `t_kota` VALUES ('1672', '16', 'KOTA PRABUMULIH');
INSERT INTO `t_kota` VALUES ('1673', '16', 'KOTA PAGAR ALAM');
INSERT INTO `t_kota` VALUES ('1674', '16', 'KOTA LUBUKLINGGAU');
INSERT INTO `t_kota` VALUES ('1701', '17', 'KABUPATEN BENGKULU SELATAN');
INSERT INTO `t_kota` VALUES ('1702', '17', 'KABUPATEN REJANG LEBONG');
INSERT INTO `t_kota` VALUES ('1703', '17', 'KABUPATEN BENGKULU UTARA');
INSERT INTO `t_kota` VALUES ('1704', '17', 'KABUPATEN KAUR');
INSERT INTO `t_kota` VALUES ('1705', '17', 'KABUPATEN SELUMA');
INSERT INTO `t_kota` VALUES ('1706', '17', 'KABUPATEN MUKOMUKO');
INSERT INTO `t_kota` VALUES ('1707', '17', 'KABUPATEN LEBONG');
INSERT INTO `t_kota` VALUES ('1708', '17', 'KABUPATEN KEPAHIANG');
INSERT INTO `t_kota` VALUES ('1709', '17', 'KABUPATEN BENGKULU TENGAH');
INSERT INTO `t_kota` VALUES ('1771', '17', 'KOTA BENGKULU');
INSERT INTO `t_kota` VALUES ('1801', '18', 'KABUPATEN LAMPUNG BARAT');
INSERT INTO `t_kota` VALUES ('1802', '18', 'KABUPATEN TANGGAMUS');
INSERT INTO `t_kota` VALUES ('1803', '18', 'KABUPATEN LAMPUNG SELATAN');
INSERT INTO `t_kota` VALUES ('1804', '18', 'KABUPATEN LAMPUNG TIMUR');
INSERT INTO `t_kota` VALUES ('1805', '18', 'KABUPATEN LAMPUNG TENGAH');
INSERT INTO `t_kota` VALUES ('1806', '18', 'KABUPATEN LAMPUNG UTARA');
INSERT INTO `t_kota` VALUES ('1807', '18', 'KABUPATEN WAY KANAN');
INSERT INTO `t_kota` VALUES ('1808', '18', 'KABUPATEN TULANGBAWANG');
INSERT INTO `t_kota` VALUES ('1809', '18', 'KABUPATEN PESAWARAN');
INSERT INTO `t_kota` VALUES ('1810', '18', 'KABUPATEN PRINGSEWU');
INSERT INTO `t_kota` VALUES ('1811', '18', 'KABUPATEN MESUJI');
INSERT INTO `t_kota` VALUES ('1812', '18', 'KABUPATEN TULANG BAWANG BARAT');
INSERT INTO `t_kota` VALUES ('1813', '18', 'KABUPATEN PESISIR BARAT');
INSERT INTO `t_kota` VALUES ('1871', '18', 'KOTA BANDAR LAMPUNG');
INSERT INTO `t_kota` VALUES ('1872', '18', 'KOTA METRO');
INSERT INTO `t_kota` VALUES ('1901', '19', 'KABUPATEN BANGKA');
INSERT INTO `t_kota` VALUES ('1902', '19', 'KABUPATEN BELITUNG');
INSERT INTO `t_kota` VALUES ('1903', '19', 'KABUPATEN BANGKA BARAT');
INSERT INTO `t_kota` VALUES ('1904', '19', 'KABUPATEN BANGKA TENGAH');
INSERT INTO `t_kota` VALUES ('1905', '19', 'KABUPATEN BANGKA SELATAN');
INSERT INTO `t_kota` VALUES ('1906', '19', 'KABUPATEN BELITUNG TIMUR');
INSERT INTO `t_kota` VALUES ('1971', '19', 'KOTA PANGKAL PINANG');
INSERT INTO `t_kota` VALUES ('2101', '21', 'KABUPATEN KARIMUN');
INSERT INTO `t_kota` VALUES ('2102', '21', 'KABUPATEN BINTAN');
INSERT INTO `t_kota` VALUES ('2103', '21', 'KABUPATEN NATUNA');
INSERT INTO `t_kota` VALUES ('2104', '21', 'KABUPATEN LINGGA');
INSERT INTO `t_kota` VALUES ('2105', '21', 'KABUPATEN KEPULAUAN ANAMBAS');
INSERT INTO `t_kota` VALUES ('2171', '21', 'KOTA B A T A M');
INSERT INTO `t_kota` VALUES ('2172', '21', 'KOTA TANJUNG PINANG');
INSERT INTO `t_kota` VALUES ('3101', '31', 'KABUPATEN KEPULAUAN SERIBU');
INSERT INTO `t_kota` VALUES ('3171', '31', 'KOTA JAKARTA SELATAN');
INSERT INTO `t_kota` VALUES ('3172', '31', 'KOTA JAKARTA TIMUR');
INSERT INTO `t_kota` VALUES ('3173', '31', 'KOTA JAKARTA PUSAT');
INSERT INTO `t_kota` VALUES ('3174', '31', 'KOTA JAKARTA BARAT');
INSERT INTO `t_kota` VALUES ('3175', '31', 'KOTA JAKARTA UTARA');
INSERT INTO `t_kota` VALUES ('3201', '32', 'KABUPATEN BOGOR');
INSERT INTO `t_kota` VALUES ('3202', '32', 'KABUPATEN SUKABUMI');
INSERT INTO `t_kota` VALUES ('3203', '32', 'KABUPATEN CIANJUR');
INSERT INTO `t_kota` VALUES ('3204', '32', 'KABUPATEN BANDUNG');
INSERT INTO `t_kota` VALUES ('3205', '32', 'KABUPATEN GARUT');
INSERT INTO `t_kota` VALUES ('3206', '32', 'KABUPATEN TASIKMALAYA');
INSERT INTO `t_kota` VALUES ('3207', '32', 'KABUPATEN CIAMIS');
INSERT INTO `t_kota` VALUES ('3208', '32', 'KABUPATEN KUNINGAN');
INSERT INTO `t_kota` VALUES ('3209', '32', 'KABUPATEN CIREBON');
INSERT INTO `t_kota` VALUES ('3210', '32', 'KABUPATEN MAJALENGKA');
INSERT INTO `t_kota` VALUES ('3211', '32', 'KABUPATEN SUMEDANG');
INSERT INTO `t_kota` VALUES ('3212', '32', 'KABUPATEN INDRAMAYU');
INSERT INTO `t_kota` VALUES ('3213', '32', 'KABUPATEN SUBANG');
INSERT INTO `t_kota` VALUES ('3214', '32', 'KABUPATEN PURWAKARTA');
INSERT INTO `t_kota` VALUES ('3215', '32', 'KABUPATEN KARAWANG');
INSERT INTO `t_kota` VALUES ('3216', '32', 'KABUPATEN BEKASI');
INSERT INTO `t_kota` VALUES ('3217', '32', 'KABUPATEN BANDUNG BARAT');
INSERT INTO `t_kota` VALUES ('3218', '32', 'KABUPATEN PANGANDARAN');
INSERT INTO `t_kota` VALUES ('3271', '32', 'KOTA BOGOR');
INSERT INTO `t_kota` VALUES ('3272', '32', 'KOTA SUKABUMI');
INSERT INTO `t_kota` VALUES ('3273', '32', 'KOTA BANDUNG');
INSERT INTO `t_kota` VALUES ('3274', '32', 'KOTA CIREBON');
INSERT INTO `t_kota` VALUES ('3275', '32', 'KOTA BEKASI');
INSERT INTO `t_kota` VALUES ('3276', '32', 'KOTA DEPOK');
INSERT INTO `t_kota` VALUES ('3277', '32', 'KOTA CIMAHI');
INSERT INTO `t_kota` VALUES ('3278', '32', 'KOTA TASIKMALAYA');
INSERT INTO `t_kota` VALUES ('3279', '32', 'KOTA BANJAR');
INSERT INTO `t_kota` VALUES ('3301', '33', 'KABUPATEN CILACAP');
INSERT INTO `t_kota` VALUES ('3302', '33', 'KABUPATEN BANYUMAS');
INSERT INTO `t_kota` VALUES ('3303', '33', 'KABUPATEN PURBALINGGA');
INSERT INTO `t_kota` VALUES ('3304', '33', 'KABUPATEN BANJARNEGARA');
INSERT INTO `t_kota` VALUES ('3305', '33', 'KABUPATEN KEBUMEN');
INSERT INTO `t_kota` VALUES ('3306', '33', 'KABUPATEN PURWOREJO');
INSERT INTO `t_kota` VALUES ('3307', '33', 'KABUPATEN WONOSOBO');
INSERT INTO `t_kota` VALUES ('3308', '33', 'KABUPATEN MAGELANG');
INSERT INTO `t_kota` VALUES ('3309', '33', 'KABUPATEN BOYOLALI');
INSERT INTO `t_kota` VALUES ('3310', '33', 'KABUPATEN KLATEN');
INSERT INTO `t_kota` VALUES ('3311', '33', 'KABUPATEN SUKOHARJO');
INSERT INTO `t_kota` VALUES ('3312', '33', 'KABUPATEN WONOGIRI');
INSERT INTO `t_kota` VALUES ('3313', '33', 'KABUPATEN KARANGANYAR');
INSERT INTO `t_kota` VALUES ('3314', '33', 'KABUPATEN SRAGEN');
INSERT INTO `t_kota` VALUES ('3315', '33', 'KABUPATEN GROBOGAN');
INSERT INTO `t_kota` VALUES ('3316', '33', 'KABUPATEN BLORA');
INSERT INTO `t_kota` VALUES ('3317', '33', 'KABUPATEN REMBANG');
INSERT INTO `t_kota` VALUES ('3318', '33', 'KABUPATEN PATI');
INSERT INTO `t_kota` VALUES ('3319', '33', 'KABUPATEN KUDUS');
INSERT INTO `t_kota` VALUES ('3320', '33', 'KABUPATEN JEPARA');
INSERT INTO `t_kota` VALUES ('3321', '33', 'KABUPATEN DEMAK');
INSERT INTO `t_kota` VALUES ('3322', '33', 'KABUPATEN SEMARANG');
INSERT INTO `t_kota` VALUES ('3323', '33', 'KABUPATEN TEMANGGUNG');
INSERT INTO `t_kota` VALUES ('3324', '33', 'KABUPATEN KENDAL');
INSERT INTO `t_kota` VALUES ('3325', '33', 'KABUPATEN BATANG');
INSERT INTO `t_kota` VALUES ('3326', '33', 'KABUPATEN PEKALONGAN');
INSERT INTO `t_kota` VALUES ('3327', '33', 'KABUPATEN PEMALANG');
INSERT INTO `t_kota` VALUES ('3328', '33', 'KABUPATEN TEGAL');
INSERT INTO `t_kota` VALUES ('3329', '33', 'KABUPATEN BREBES');
INSERT INTO `t_kota` VALUES ('3371', '33', 'KOTA MAGELANG');
INSERT INTO `t_kota` VALUES ('3372', '33', 'KOTA SURAKARTA');
INSERT INTO `t_kota` VALUES ('3373', '33', 'KOTA SALATIGA');
INSERT INTO `t_kota` VALUES ('3374', '33', 'KOTA SEMARANG');
INSERT INTO `t_kota` VALUES ('3375', '33', 'KOTA PEKALONGAN');
INSERT INTO `t_kota` VALUES ('3376', '33', 'KOTA TEGAL');
INSERT INTO `t_kota` VALUES ('3401', '34', 'KABUPATEN KULON PROGO');
INSERT INTO `t_kota` VALUES ('3402', '34', 'KABUPATEN BANTUL');
INSERT INTO `t_kota` VALUES ('3403', '34', 'KABUPATEN GUNUNG KIDUL');
INSERT INTO `t_kota` VALUES ('3404', '34', 'KABUPATEN SLEMAN');
INSERT INTO `t_kota` VALUES ('3471', '34', 'KOTA YOGYAKARTA');
INSERT INTO `t_kota` VALUES ('3501', '35', 'KABUPATEN PACITAN');
INSERT INTO `t_kota` VALUES ('3502', '35', 'KABUPATEN PONOROGO');
INSERT INTO `t_kota` VALUES ('3503', '35', 'KABUPATEN TRENGGALEK');
INSERT INTO `t_kota` VALUES ('3504', '35', 'KABUPATEN TULUNGAGUNG');
INSERT INTO `t_kota` VALUES ('3505', '35', 'KABUPATEN BLITAR');
INSERT INTO `t_kota` VALUES ('3506', '35', 'KABUPATEN KEDIRI');
INSERT INTO `t_kota` VALUES ('3507', '35', 'KABUPATEN MALANG');
INSERT INTO `t_kota` VALUES ('3508', '35', 'KABUPATEN LUMAJANG');
INSERT INTO `t_kota` VALUES ('3509', '35', 'KABUPATEN JEMBER');
INSERT INTO `t_kota` VALUES ('3510', '35', 'KABUPATEN BANYUWANGI');
INSERT INTO `t_kota` VALUES ('3511', '35', 'KABUPATEN BONDOWOSO');
INSERT INTO `t_kota` VALUES ('3512', '35', 'KABUPATEN SITUBONDO');
INSERT INTO `t_kota` VALUES ('3513', '35', 'KABUPATEN PROBOLINGGO');
INSERT INTO `t_kota` VALUES ('3514', '35', 'KABUPATEN PASURUAN');
INSERT INTO `t_kota` VALUES ('3515', '35', 'KABUPATEN SIDOARJO');
INSERT INTO `t_kota` VALUES ('3516', '35', 'KABUPATEN MOJOKERTO');
INSERT INTO `t_kota` VALUES ('3517', '35', 'KABUPATEN JOMBANG');
INSERT INTO `t_kota` VALUES ('3518', '35', 'KABUPATEN NGANJUK');
INSERT INTO `t_kota` VALUES ('3519', '35', 'KABUPATEN MADIUN');
INSERT INTO `t_kota` VALUES ('3520', '35', 'KABUPATEN MAGETAN');
INSERT INTO `t_kota` VALUES ('3521', '35', 'KABUPATEN NGAWI');
INSERT INTO `t_kota` VALUES ('3522', '35', 'KABUPATEN BOJONEGORO');
INSERT INTO `t_kota` VALUES ('3523', '35', 'KABUPATEN TUBAN');
INSERT INTO `t_kota` VALUES ('3524', '35', 'KABUPATEN LAMONGAN');
INSERT INTO `t_kota` VALUES ('3525', '35', 'KABUPATEN GRESIK');
INSERT INTO `t_kota` VALUES ('3526', '35', 'KABUPATEN BANGKALAN');
INSERT INTO `t_kota` VALUES ('3527', '35', 'KABUPATEN SAMPANG');
INSERT INTO `t_kota` VALUES ('3528', '35', 'KABUPATEN PAMEKASAN');
INSERT INTO `t_kota` VALUES ('3529', '35', 'KABUPATEN SUMENEP');
INSERT INTO `t_kota` VALUES ('3571', '35', 'KOTA KEDIRI');
INSERT INTO `t_kota` VALUES ('3572', '35', 'KOTA BLITAR');
INSERT INTO `t_kota` VALUES ('3573', '35', 'KOTA MALANG');
INSERT INTO `t_kota` VALUES ('3574', '35', 'KOTA PROBOLINGGO');
INSERT INTO `t_kota` VALUES ('3575', '35', 'KOTA PASURUAN');
INSERT INTO `t_kota` VALUES ('3576', '35', 'KOTA MOJOKERTO');
INSERT INTO `t_kota` VALUES ('3577', '35', 'KOTA MADIUN');
INSERT INTO `t_kota` VALUES ('3578', '35', 'KOTA SURABAYA');
INSERT INTO `t_kota` VALUES ('3579', '35', 'KOTA BATU');
INSERT INTO `t_kota` VALUES ('3601', '36', 'KABUPATEN PANDEGLANG');
INSERT INTO `t_kota` VALUES ('3602', '36', 'KABUPATEN LEBAK');
INSERT INTO `t_kota` VALUES ('3603', '36', 'KABUPATEN TANGERANG');
INSERT INTO `t_kota` VALUES ('3604', '36', 'KABUPATEN SERANG');
INSERT INTO `t_kota` VALUES ('3671', '36', 'KOTA TANGERANG');
INSERT INTO `t_kota` VALUES ('3672', '36', 'KOTA CILEGON');
INSERT INTO `t_kota` VALUES ('3673', '36', 'KOTA SERANG');
INSERT INTO `t_kota` VALUES ('3674', '36', 'KOTA TANGERANG SELATAN');
INSERT INTO `t_kota` VALUES ('5101', '51', 'KABUPATEN JEMBRANA');
INSERT INTO `t_kota` VALUES ('5102', '51', 'KABUPATEN TABANAN');
INSERT INTO `t_kota` VALUES ('5103', '51', 'KABUPATEN BADUNG');
INSERT INTO `t_kota` VALUES ('5104', '51', 'KABUPATEN GIANYAR');
INSERT INTO `t_kota` VALUES ('5105', '51', 'KABUPATEN KLUNGKUNG');
INSERT INTO `t_kota` VALUES ('5106', '51', 'KABUPATEN BANGLI');
INSERT INTO `t_kota` VALUES ('5107', '51', 'KABUPATEN KARANG ASEM');
INSERT INTO `t_kota` VALUES ('5108', '51', 'KABUPATEN BULELENG');
INSERT INTO `t_kota` VALUES ('5171', '51', 'KOTA DENPASAR');
INSERT INTO `t_kota` VALUES ('5201', '52', 'KABUPATEN LOMBOK BARAT');
INSERT INTO `t_kota` VALUES ('5202', '52', 'KABUPATEN LOMBOK TENGAH');
INSERT INTO `t_kota` VALUES ('5203', '52', 'KABUPATEN LOMBOK TIMUR');
INSERT INTO `t_kota` VALUES ('5204', '52', 'KABUPATEN SUMBAWA');
INSERT INTO `t_kota` VALUES ('5205', '52', 'KABUPATEN DOMPU');
INSERT INTO `t_kota` VALUES ('5206', '52', 'KABUPATEN BIMA');
INSERT INTO `t_kota` VALUES ('5207', '52', 'KABUPATEN SUMBAWA BARAT');
INSERT INTO `t_kota` VALUES ('5208', '52', 'KABUPATEN LOMBOK UTARA');
INSERT INTO `t_kota` VALUES ('5271', '52', 'KOTA MATARAM');
INSERT INTO `t_kota` VALUES ('5272', '52', 'KOTA BIMA');
INSERT INTO `t_kota` VALUES ('5301', '53', 'KABUPATEN SUMBA BARAT');
INSERT INTO `t_kota` VALUES ('5302', '53', 'KABUPATEN SUMBA TIMUR');
INSERT INTO `t_kota` VALUES ('5303', '53', 'KABUPATEN KUPANG');
INSERT INTO `t_kota` VALUES ('5304', '53', 'KABUPATEN TIMOR TENGAH SELATAN');
INSERT INTO `t_kota` VALUES ('5305', '53', 'KABUPATEN TIMOR TENGAH UTARA');
INSERT INTO `t_kota` VALUES ('5306', '53', 'KABUPATEN BELU');
INSERT INTO `t_kota` VALUES ('5307', '53', 'KABUPATEN ALOR');
INSERT INTO `t_kota` VALUES ('5308', '53', 'KABUPATEN LEMBATA');
INSERT INTO `t_kota` VALUES ('5309', '53', 'KABUPATEN FLORES TIMUR');
INSERT INTO `t_kota` VALUES ('5310', '53', 'KABUPATEN SIKKA');
INSERT INTO `t_kota` VALUES ('5311', '53', 'KABUPATEN ENDE');
INSERT INTO `t_kota` VALUES ('5312', '53', 'KABUPATEN NGADA');
INSERT INTO `t_kota` VALUES ('5313', '53', 'KABUPATEN MANGGARAI');
INSERT INTO `t_kota` VALUES ('5314', '53', 'KABUPATEN ROTE NDAO');
INSERT INTO `t_kota` VALUES ('5315', '53', 'KABUPATEN MANGGARAI BARAT');
INSERT INTO `t_kota` VALUES ('5316', '53', 'KABUPATEN SUMBA TENGAH');
INSERT INTO `t_kota` VALUES ('5317', '53', 'KABUPATEN SUMBA BARAT DAYA');
INSERT INTO `t_kota` VALUES ('5318', '53', 'KABUPATEN NAGEKEO');
INSERT INTO `t_kota` VALUES ('5319', '53', 'KABUPATEN MANGGARAI TIMUR');
INSERT INTO `t_kota` VALUES ('5320', '53', 'KABUPATEN SABU RAIJUA');
INSERT INTO `t_kota` VALUES ('5321', '53', 'KABUPATEN MALAKA');
INSERT INTO `t_kota` VALUES ('5371', '53', 'KOTA KUPANG');
INSERT INTO `t_kota` VALUES ('6101', '61', 'KABUPATEN SAMBAS');
INSERT INTO `t_kota` VALUES ('6102', '61', 'KABUPATEN BENGKAYANG');
INSERT INTO `t_kota` VALUES ('6103', '61', 'KABUPATEN LANDAK');
INSERT INTO `t_kota` VALUES ('6104', '61', 'KABUPATEN MEMPAWAH');
INSERT INTO `t_kota` VALUES ('6105', '61', 'KABUPATEN SANGGAU');
INSERT INTO `t_kota` VALUES ('6106', '61', 'KABUPATEN KETAPANG');
INSERT INTO `t_kota` VALUES ('6107', '61', 'KABUPATEN SINTANG');
INSERT INTO `t_kota` VALUES ('6108', '61', 'KABUPATEN KAPUAS HULU');
INSERT INTO `t_kota` VALUES ('6109', '61', 'KABUPATEN SEKADAU');
INSERT INTO `t_kota` VALUES ('6110', '61', 'KABUPATEN MELAWI');
INSERT INTO `t_kota` VALUES ('6111', '61', 'KABUPATEN KAYONG UTARA');
INSERT INTO `t_kota` VALUES ('6112', '61', 'KABUPATEN KUBU RAYA');
INSERT INTO `t_kota` VALUES ('6171', '61', 'KOTA PONTIANAK');
INSERT INTO `t_kota` VALUES ('6172', '61', 'KOTA SINGKAWANG');
INSERT INTO `t_kota` VALUES ('6201', '62', 'KABUPATEN KOTAWARINGIN BARAT');
INSERT INTO `t_kota` VALUES ('6202', '62', 'KABUPATEN KOTAWARINGIN TIMUR');
INSERT INTO `t_kota` VALUES ('6203', '62', 'KABUPATEN KAPUAS');
INSERT INTO `t_kota` VALUES ('6204', '62', 'KABUPATEN BARITO SELATAN');
INSERT INTO `t_kota` VALUES ('6205', '62', 'KABUPATEN BARITO UTARA');
INSERT INTO `t_kota` VALUES ('6206', '62', 'KABUPATEN SUKAMARA');
INSERT INTO `t_kota` VALUES ('6207', '62', 'KABUPATEN LAMANDAU');
INSERT INTO `t_kota` VALUES ('6208', '62', 'KABUPATEN SERUYAN');
INSERT INTO `t_kota` VALUES ('6209', '62', 'KABUPATEN KATINGAN');
INSERT INTO `t_kota` VALUES ('6210', '62', 'KABUPATEN PULANG PISAU');
INSERT INTO `t_kota` VALUES ('6211', '62', 'KABUPATEN GUNUNG MAS');
INSERT INTO `t_kota` VALUES ('6212', '62', 'KABUPATEN BARITO TIMUR');
INSERT INTO `t_kota` VALUES ('6213', '62', 'KABUPATEN MURUNG RAYA');
INSERT INTO `t_kota` VALUES ('6271', '62', 'KOTA PALANGKA RAYA');
INSERT INTO `t_kota` VALUES ('6301', '63', 'KABUPATEN TANAH LAUT');
INSERT INTO `t_kota` VALUES ('6302', '63', 'KABUPATEN KOTA BARU');
INSERT INTO `t_kota` VALUES ('6303', '63', 'KABUPATEN BANJAR');
INSERT INTO `t_kota` VALUES ('6304', '63', 'KABUPATEN BARITO KUALA');
INSERT INTO `t_kota` VALUES ('6305', '63', 'KABUPATEN TAPIN');
INSERT INTO `t_kota` VALUES ('6306', '63', 'KABUPATEN HULU SUNGAI SELATAN');
INSERT INTO `t_kota` VALUES ('6307', '63', 'KABUPATEN HULU SUNGAI TENGAH');
INSERT INTO `t_kota` VALUES ('6308', '63', 'KABUPATEN HULU SUNGAI UTARA');
INSERT INTO `t_kota` VALUES ('6309', '63', 'KABUPATEN TABALONG');
INSERT INTO `t_kota` VALUES ('6310', '63', 'KABUPATEN TANAH BUMBU');
INSERT INTO `t_kota` VALUES ('6311', '63', 'KABUPATEN BALANGAN');
INSERT INTO `t_kota` VALUES ('6371', '63', 'KOTA BANJARMASIN');
INSERT INTO `t_kota` VALUES ('6372', '63', 'KOTA BANJAR BARU');
INSERT INTO `t_kota` VALUES ('6401', '64', 'KABUPATEN PASER');
INSERT INTO `t_kota` VALUES ('6402', '64', 'KABUPATEN KUTAI BARAT');
INSERT INTO `t_kota` VALUES ('6403', '64', 'KABUPATEN KUTAI KARTANEGARA');
INSERT INTO `t_kota` VALUES ('6404', '64', 'KABUPATEN KUTAI TIMUR');
INSERT INTO `t_kota` VALUES ('6405', '64', 'KABUPATEN BERAU');
INSERT INTO `t_kota` VALUES ('6409', '64', 'KABUPATEN PENAJAM PASER UTARA');
INSERT INTO `t_kota` VALUES ('6411', '64', 'KABUPATEN MAHAKAM HULU');
INSERT INTO `t_kota` VALUES ('6471', '64', 'KOTA BALIKPAPAN');
INSERT INTO `t_kota` VALUES ('6472', '64', 'KOTA SAMARINDA');
INSERT INTO `t_kota` VALUES ('6474', '64', 'KOTA BONTANG');
INSERT INTO `t_kota` VALUES ('6501', '65', 'KABUPATEN MALINAU');
INSERT INTO `t_kota` VALUES ('6502', '65', 'KABUPATEN BULUNGAN');
INSERT INTO `t_kota` VALUES ('6503', '65', 'KABUPATEN TANA TIDUNG');
INSERT INTO `t_kota` VALUES ('6504', '65', 'KABUPATEN NUNUKAN');
INSERT INTO `t_kota` VALUES ('6571', '65', 'KOTA TARAKAN');
INSERT INTO `t_kota` VALUES ('7101', '71', 'KABUPATEN BOLAANG MONGONDOW');
INSERT INTO `t_kota` VALUES ('7102', '71', 'KABUPATEN MINAHASA');
INSERT INTO `t_kota` VALUES ('7103', '71', 'KABUPATEN KEPULAUAN SANGIHE');
INSERT INTO `t_kota` VALUES ('7104', '71', 'KABUPATEN KEPULAUAN TALAUD');
INSERT INTO `t_kota` VALUES ('7105', '71', 'KABUPATEN MINAHASA SELATAN');
INSERT INTO `t_kota` VALUES ('7106', '71', 'KABUPATEN MINAHASA UTARA');
INSERT INTO `t_kota` VALUES ('7107', '71', 'KABUPATEN BOLAANG MONGONDOW UTARA');
INSERT INTO `t_kota` VALUES ('7108', '71', 'KABUPATEN SIAU TAGULANDANG BIARO');
INSERT INTO `t_kota` VALUES ('7109', '71', 'KABUPATEN MINAHASA TENGGARA');
INSERT INTO `t_kota` VALUES ('7110', '71', 'KABUPATEN BOLAANG MONGONDOW SELATAN');
INSERT INTO `t_kota` VALUES ('7111', '71', 'KABUPATEN BOLAANG MONGONDOW TIMUR');
INSERT INTO `t_kota` VALUES ('7171', '71', 'KOTA MANADO');
INSERT INTO `t_kota` VALUES ('7172', '71', 'KOTA BITUNG');
INSERT INTO `t_kota` VALUES ('7173', '71', 'KOTA TOMOHON');
INSERT INTO `t_kota` VALUES ('7174', '71', 'KOTA KOTAMOBAGU');
INSERT INTO `t_kota` VALUES ('7201', '72', 'KABUPATEN BANGGAI KEPULAUAN');
INSERT INTO `t_kota` VALUES ('7202', '72', 'KABUPATEN BANGGAI');
INSERT INTO `t_kota` VALUES ('7203', '72', 'KABUPATEN MOROWALI');
INSERT INTO `t_kota` VALUES ('7204', '72', 'KABUPATEN POSO');
INSERT INTO `t_kota` VALUES ('7205', '72', 'KABUPATEN DONGGALA');
INSERT INTO `t_kota` VALUES ('7206', '72', 'KABUPATEN TOLI-TOLI');
INSERT INTO `t_kota` VALUES ('7207', '72', 'KABUPATEN BUOL');
INSERT INTO `t_kota` VALUES ('7208', '72', 'KABUPATEN PARIGI MOUTONG');
INSERT INTO `t_kota` VALUES ('7209', '72', 'KABUPATEN TOJO UNA-UNA');
INSERT INTO `t_kota` VALUES ('7210', '72', 'KABUPATEN SIGI');
INSERT INTO `t_kota` VALUES ('7211', '72', 'KABUPATEN BANGGAI LAUT');
INSERT INTO `t_kota` VALUES ('7212', '72', 'KABUPATEN MOROWALI UTARA');
INSERT INTO `t_kota` VALUES ('7271', '72', 'KOTA PALU');
INSERT INTO `t_kota` VALUES ('7301', '73', 'KABUPATEN KEPULAUAN SELAYAR');
INSERT INTO `t_kota` VALUES ('7302', '73', 'KABUPATEN BULUKUMBA');
INSERT INTO `t_kota` VALUES ('7303', '73', 'KABUPATEN BANTAENG');
INSERT INTO `t_kota` VALUES ('7304', '73', 'KABUPATEN JENEPONTO');
INSERT INTO `t_kota` VALUES ('7305', '73', 'KABUPATEN TAKALAR');
INSERT INTO `t_kota` VALUES ('7306', '73', 'KABUPATEN GOWA');
INSERT INTO `t_kota` VALUES ('7307', '73', 'KABUPATEN SINJAI');
INSERT INTO `t_kota` VALUES ('7308', '73', 'KABUPATEN MAROS');
INSERT INTO `t_kota` VALUES ('7309', '73', 'KABUPATEN PANGKAJENE DAN KEPULAUAN');
INSERT INTO `t_kota` VALUES ('7310', '73', 'KABUPATEN BARRU');
INSERT INTO `t_kota` VALUES ('7311', '73', 'KABUPATEN BONE');
INSERT INTO `t_kota` VALUES ('7312', '73', 'KABUPATEN SOPPENG');
INSERT INTO `t_kota` VALUES ('7313', '73', 'KABUPATEN WAJO');
INSERT INTO `t_kota` VALUES ('7314', '73', 'KABUPATEN SIDENRENG RAPPANG');
INSERT INTO `t_kota` VALUES ('7315', '73', 'KABUPATEN PINRANG');
INSERT INTO `t_kota` VALUES ('7316', '73', 'KABUPATEN ENREKANG');
INSERT INTO `t_kota` VALUES ('7317', '73', 'KABUPATEN LUWU');
INSERT INTO `t_kota` VALUES ('7318', '73', 'KABUPATEN TANA TORAJA');
INSERT INTO `t_kota` VALUES ('7322', '73', 'KABUPATEN LUWU UTARA');
INSERT INTO `t_kota` VALUES ('7325', '73', 'KABUPATEN LUWU TIMUR');
INSERT INTO `t_kota` VALUES ('7326', '73', 'KABUPATEN TORAJA UTARA');
INSERT INTO `t_kota` VALUES ('7371', '73', 'KOTA MAKASSAR');
INSERT INTO `t_kota` VALUES ('7372', '73', 'KOTA PAREPARE');
INSERT INTO `t_kota` VALUES ('7373', '73', 'KOTA PALOPO');
INSERT INTO `t_kota` VALUES ('7401', '74', 'KABUPATEN BUTON');
INSERT INTO `t_kota` VALUES ('7402', '74', 'KABUPATEN MUNA');
INSERT INTO `t_kota` VALUES ('7403', '74', 'KABUPATEN KONAWE');
INSERT INTO `t_kota` VALUES ('7404', '74', 'KABUPATEN KOLAKA');
INSERT INTO `t_kota` VALUES ('7405', '74', 'KABUPATEN KONAWE SELATAN');
INSERT INTO `t_kota` VALUES ('7406', '74', 'KABUPATEN BOMBANA');
INSERT INTO `t_kota` VALUES ('7407', '74', 'KABUPATEN WAKATOBI');
INSERT INTO `t_kota` VALUES ('7408', '74', 'KABUPATEN KOLAKA UTARA');
INSERT INTO `t_kota` VALUES ('7409', '74', 'KABUPATEN BUTON UTARA');
INSERT INTO `t_kota` VALUES ('7410', '74', 'KABUPATEN KONAWE UTARA');
INSERT INTO `t_kota` VALUES ('7411', '74', 'KABUPATEN KOLAKA TIMUR');
INSERT INTO `t_kota` VALUES ('7412', '74', 'KABUPATEN KONAWE KEPULAUAN');
INSERT INTO `t_kota` VALUES ('7413', '74', 'KABUPATEN MUNA BARAT');
INSERT INTO `t_kota` VALUES ('7414', '74', 'KABUPATEN BUTON TENGAH');
INSERT INTO `t_kota` VALUES ('7415', '74', 'KABUPATEN BUTON SELATAN');
INSERT INTO `t_kota` VALUES ('7471', '74', 'KOTA KENDARI');
INSERT INTO `t_kota` VALUES ('7472', '74', 'KOTA BAUBAU');
INSERT INTO `t_kota` VALUES ('7501', '75', 'KABUPATEN BOALEMO');
INSERT INTO `t_kota` VALUES ('7502', '75', 'KABUPATEN GORONTALO');
INSERT INTO `t_kota` VALUES ('7503', '75', 'KABUPATEN POHUWATO');
INSERT INTO `t_kota` VALUES ('7504', '75', 'KABUPATEN BONE BOLANGO');
INSERT INTO `t_kota` VALUES ('7505', '75', 'KABUPATEN GORONTALO UTARA');
INSERT INTO `t_kota` VALUES ('7571', '75', 'KOTA GORONTALO');
INSERT INTO `t_kota` VALUES ('7601', '76', 'KABUPATEN MAJENE');
INSERT INTO `t_kota` VALUES ('7602', '76', 'KABUPATEN POLEWALI MANDAR');
INSERT INTO `t_kota` VALUES ('7603', '76', 'KABUPATEN MAMASA');
INSERT INTO `t_kota` VALUES ('7604', '76', 'KABUPATEN MAMUJU');
INSERT INTO `t_kota` VALUES ('7605', '76', 'KABUPATEN MAMUJU UTARA');
INSERT INTO `t_kota` VALUES ('7606', '76', 'KABUPATEN MAMUJU TENGAH');
INSERT INTO `t_kota` VALUES ('8101', '81', 'KABUPATEN MALUKU TENGGARA BARAT');
INSERT INTO `t_kota` VALUES ('8102', '81', 'KABUPATEN MALUKU TENGGARA');
INSERT INTO `t_kota` VALUES ('8103', '81', 'KABUPATEN MALUKU TENGAH');
INSERT INTO `t_kota` VALUES ('8104', '81', 'KABUPATEN BURU');
INSERT INTO `t_kota` VALUES ('8105', '81', 'KABUPATEN KEPULAUAN ARU');
INSERT INTO `t_kota` VALUES ('8106', '81', 'KABUPATEN SERAM BAGIAN BARAT');
INSERT INTO `t_kota` VALUES ('8107', '81', 'KABUPATEN SERAM BAGIAN TIMUR');
INSERT INTO `t_kota` VALUES ('8108', '81', 'KABUPATEN MALUKU BARAT DAYA');
INSERT INTO `t_kota` VALUES ('8109', '81', 'KABUPATEN BURU SELATAN');
INSERT INTO `t_kota` VALUES ('8171', '81', 'KOTA AMBON');
INSERT INTO `t_kota` VALUES ('8172', '81', 'KOTA TUAL');
INSERT INTO `t_kota` VALUES ('8201', '82', 'KABUPATEN HALMAHERA BARAT');
INSERT INTO `t_kota` VALUES ('8202', '82', 'KABUPATEN HALMAHERA TENGAH');
INSERT INTO `t_kota` VALUES ('8203', '82', 'KABUPATEN KEPULAUAN SULA');
INSERT INTO `t_kota` VALUES ('8204', '82', 'KABUPATEN HALMAHERA SELATAN');
INSERT INTO `t_kota` VALUES ('8205', '82', 'KABUPATEN HALMAHERA UTARA');
INSERT INTO `t_kota` VALUES ('8206', '82', 'KABUPATEN HALMAHERA TIMUR');
INSERT INTO `t_kota` VALUES ('8207', '82', 'KABUPATEN PULAU MOROTAI');
INSERT INTO `t_kota` VALUES ('8208', '82', 'KABUPATEN PULAU TALIABU');
INSERT INTO `t_kota` VALUES ('8271', '82', 'KOTA TERNATE');
INSERT INTO `t_kota` VALUES ('8272', '82', 'KOTA TIDORE KEPULAUAN');
INSERT INTO `t_kota` VALUES ('9101', '91', 'KABUPATEN FAKFAK');
INSERT INTO `t_kota` VALUES ('9102', '91', 'KABUPATEN KAIMANA');
INSERT INTO `t_kota` VALUES ('9103', '91', 'KABUPATEN TELUK WONDAMA');
INSERT INTO `t_kota` VALUES ('9104', '91', 'KABUPATEN TELUK BINTUNI');
INSERT INTO `t_kota` VALUES ('9105', '91', 'KABUPATEN MANOKWARI');
INSERT INTO `t_kota` VALUES ('9106', '91', 'KABUPATEN SORONG SELATAN');
INSERT INTO `t_kota` VALUES ('9107', '91', 'KABUPATEN SORONG');
INSERT INTO `t_kota` VALUES ('9108', '91', 'KABUPATEN RAJA AMPAT');
INSERT INTO `t_kota` VALUES ('9109', '91', 'KABUPATEN TAMBRAUW');
INSERT INTO `t_kota` VALUES ('9110', '91', 'KABUPATEN MAYBRAT');
INSERT INTO `t_kota` VALUES ('9111', '91', 'KABUPATEN MANOKWARI SELATAN');
INSERT INTO `t_kota` VALUES ('9112', '91', 'KABUPATEN PEGUNUNGAN ARFAK');
INSERT INTO `t_kota` VALUES ('9171', '91', 'KOTA SORONG');
INSERT INTO `t_kota` VALUES ('9401', '94', 'KABUPATEN MERAUKE');
INSERT INTO `t_kota` VALUES ('9402', '94', 'KABUPATEN JAYAWIJAYA');
INSERT INTO `t_kota` VALUES ('9403', '94', 'KABUPATEN JAYAPURA');
INSERT INTO `t_kota` VALUES ('9404', '94', 'KABUPATEN NABIRE');
INSERT INTO `t_kota` VALUES ('9408', '94', 'KABUPATEN KEPULAUAN YAPEN');
INSERT INTO `t_kota` VALUES ('9409', '94', 'KABUPATEN BIAK NUMFOR');
INSERT INTO `t_kota` VALUES ('9410', '94', 'KABUPATEN PANIAI');
INSERT INTO `t_kota` VALUES ('9411', '94', 'KABUPATEN PUNCAK JAYA');
INSERT INTO `t_kota` VALUES ('9412', '94', 'KABUPATEN MIMIKA');
INSERT INTO `t_kota` VALUES ('9413', '94', 'KABUPATEN BOVEN DIGOEL');
INSERT INTO `t_kota` VALUES ('9414', '94', 'KABUPATEN MAPPI');
INSERT INTO `t_kota` VALUES ('9415', '94', 'KABUPATEN ASMAT');
INSERT INTO `t_kota` VALUES ('9416', '94', 'KABUPATEN YAHUKIMO');
INSERT INTO `t_kota` VALUES ('9417', '94', 'KABUPATEN PEGUNUNGAN BINTANG');
INSERT INTO `t_kota` VALUES ('9418', '94', 'KABUPATEN TOLIKARA');
INSERT INTO `t_kota` VALUES ('9419', '94', 'KABUPATEN SARMI');
INSERT INTO `t_kota` VALUES ('9420', '94', 'KABUPATEN KEEROM');
INSERT INTO `t_kota` VALUES ('9426', '94', 'KABUPATEN WAROPEN');
INSERT INTO `t_kota` VALUES ('9427', '94', 'KABUPATEN SUPIORI');
INSERT INTO `t_kota` VALUES ('9428', '94', 'KABUPATEN MAMBERAMO RAYA');
INSERT INTO `t_kota` VALUES ('9429', '94', 'KABUPATEN NDUGA');
INSERT INTO `t_kota` VALUES ('9430', '94', 'KABUPATEN LANNY JAYA');
INSERT INTO `t_kota` VALUES ('9431', '94', 'KABUPATEN MAMBERAMO TENGAH');
INSERT INTO `t_kota` VALUES ('9432', '94', 'KABUPATEN YALIMO');
INSERT INTO `t_kota` VALUES ('9433', '94', 'KABUPATEN PUNCAK');
INSERT INTO `t_kota` VALUES ('9434', '94', 'KABUPATEN DOGIYAI');
INSERT INTO `t_kota` VALUES ('9435', '94', 'KABUPATEN INTAN JAYA');
INSERT INTO `t_kota` VALUES ('9436', '94', 'KABUPATEN DEIYAI');
INSERT INTO `t_kota` VALUES ('9471', '94', 'KOTA JAYAPURA');

-- ----------------------------
-- Table structure for t_log_harga_jual
-- ----------------------------
DROP TABLE IF EXISTS `t_log_harga_jual`;
CREATE TABLE `t_log_harga_jual`  (
  `id_log_harga_jual` int(64) NOT NULL AUTO_INCREMENT,
  `id_barang` int(64) NULL DEFAULT NULL,
  `harga_jual` float(20, 2) NULL DEFAULT NULL,
  `tanggal` date NULL DEFAULT NULL,
  `is_harga_awal` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id_log_harga_jual`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_log_harga_jual
-- ----------------------------
INSERT INTO `t_log_harga_jual` VALUES (1, 3, 10000.00, '2021-10-14', 1);

-- ----------------------------
-- Table structure for t_pembelian
-- ----------------------------
DROP TABLE IF EXISTS `t_pembelian`;
CREATE TABLE `t_pembelian`  (
  `id_pembelian` int(32) NOT NULL,
  `kode_pembelian` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_agen` int(32) NULL DEFAULT NULL,
  `id_user` int(11) NULL DEFAULT NULL,
  `tanggal` date NULL DEFAULT NULL,
  `total_pembelian` float(20, 2) NULL DEFAULT NULL,
  `total_disc` float(20, 2) NULL DEFAULT NULL,
  `is_terima_all` int(1) NULL DEFAULT NULL COMMENT '1: jika det sudah diterima semua, null jika ada yg belum',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_pembelian`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_pembelian
-- ----------------------------

-- ----------------------------
-- Table structure for t_pembelian_det
-- ----------------------------
DROP TABLE IF EXISTS `t_pembelian_det`;
CREATE TABLE `t_pembelian_det`  (
  `id_pembelian_det` int(32) NULL DEFAULT NULL,
  `id_pembelian` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_barang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `qty` int(11) NULL DEFAULT NULL,
  `harga` float(20, 2) NULL DEFAULT NULL,
  `disc` float(20, 2) NULL DEFAULT NULL,
  `disc_persen` float(20, 2) NULL DEFAULT NULL,
  `harga_fix` float(20, 2) NULL DEFAULT NULL,
  `harga_total_fix` float(20, 2) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  `is_terima` int(1) NULL DEFAULT NULL,
  `tgl_terima` date NULL DEFAULT NULL,
  `reff_terima` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_pembelian_det
-- ----------------------------

-- ----------------------------
-- Table structure for t_penjualan
-- ----------------------------
DROP TABLE IF EXISTS `t_penjualan`;
CREATE TABLE `t_penjualan`  (
  `id_penjualan` int(32) NOT NULL AUTO_INCREMENT,
  `no_faktur` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_pelanggan` int(32) NULL DEFAULT NULL,
  `id_sales` int(32) NULL DEFAULT NULL,
  `tgl_jatuh_tempo` datetime(0) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_penjualan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_penjualan
-- ----------------------------
INSERT INTO `t_penjualan` VALUES (1, 'J120012021', 3, 4, '2021-10-12 00:00:00', '2021-10-12 00:22:32', NULL, NULL);
INSERT INTO `t_penjualan` VALUES (2, 'J120022021', 3, 3, '1970-01-01 07:00:00', '2021-10-12 23:21:34', NULL, NULL);
INSERT INTO `t_penjualan` VALUES (3, 'J140012021', 3, 3, '1970-01-01 07:00:00', '2021-10-14 00:13:32', NULL, NULL);
INSERT INTO `t_penjualan` VALUES (4, 'J160012021', 3, 3, '1970-01-01 07:00:00', '2021-10-16 12:24:08', NULL, NULL);
INSERT INTO `t_penjualan` VALUES (5, 'J180012021', 3, 3, '1970-01-01 07:00:00', '2021-10-18 21:41:10', NULL, NULL);

-- ----------------------------
-- Table structure for t_penjualan_det
-- ----------------------------
DROP TABLE IF EXISTS `t_penjualan_det`;
CREATE TABLE `t_penjualan_det`  (
  `id_penjualan_det` int(32) NOT NULL AUTO_INCREMENT,
  `id_penjualan` int(32) NULL DEFAULT NULL,
  `id_barang` int(32) NULL DEFAULT NULL,
  `harga_awal` float NULL DEFAULT NULL,
  `harga_diskon` float NULL DEFAULT NULL,
  `besaran_diskon` float NULL DEFAULT NULL COMMENT 'dalam % (persen)',
  `sub_total` float NULL DEFAULT NULL,
  `qty` int(32) NULL DEFAULT NULL,
  PRIMARY KEY (`id_penjualan_det`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_penjualan_det
-- ----------------------------
INSERT INTO `t_penjualan_det` VALUES (8, 3, 0, NULL, 0, 0, 0, 2);
INSERT INTO `t_penjualan_det` VALUES (9, 3, 1, 15000, 15000, 0, 30000, 2);
INSERT INTO `t_penjualan_det` VALUES (25, 3, 1, 15000, 15000, 0, 30000, 2);

-- ----------------------------
-- Table structure for t_provinsi
-- ----------------------------
DROP TABLE IF EXISTS `t_provinsi`;
CREATE TABLE `t_provinsi`  (
  `id_provinsi` char(2) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nama_provinsi` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_provinsi`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_provinsi
-- ----------------------------
INSERT INTO `t_provinsi` VALUES ('11', 'ACEH');
INSERT INTO `t_provinsi` VALUES ('12', 'SUMATERA UTARA');
INSERT INTO `t_provinsi` VALUES ('13', 'SUMATERA BARAT');
INSERT INTO `t_provinsi` VALUES ('14', 'RIAU');
INSERT INTO `t_provinsi` VALUES ('15', 'JAMBI');
INSERT INTO `t_provinsi` VALUES ('16', 'SUMATERA SELATAN');
INSERT INTO `t_provinsi` VALUES ('17', 'BENGKULU');
INSERT INTO `t_provinsi` VALUES ('18', 'LAMPUNG');
INSERT INTO `t_provinsi` VALUES ('19', 'KEPULAUAN BANGKA BELITUNG');
INSERT INTO `t_provinsi` VALUES ('21', 'KEPULAUAN RIAU');
INSERT INTO `t_provinsi` VALUES ('31', 'DKI JAKARTA');
INSERT INTO `t_provinsi` VALUES ('32', 'JAWA BARAT');
INSERT INTO `t_provinsi` VALUES ('33', 'JAWA TENGAH');
INSERT INTO `t_provinsi` VALUES ('34', 'DI YOGYAKARTA');
INSERT INTO `t_provinsi` VALUES ('35', 'JAWA TIMUR');
INSERT INTO `t_provinsi` VALUES ('36', 'BANTEN');
INSERT INTO `t_provinsi` VALUES ('51', 'BALI');
INSERT INTO `t_provinsi` VALUES ('52', 'NUSA TENGGARA BARAT');
INSERT INTO `t_provinsi` VALUES ('53', 'NUSA TENGGARA TIMUR');
INSERT INTO `t_provinsi` VALUES ('61', 'KALIMANTAN BARAT');
INSERT INTO `t_provinsi` VALUES ('62', 'KALIMANTAN TENGAH');
INSERT INTO `t_provinsi` VALUES ('63', 'KALIMANTAN SELATAN');
INSERT INTO `t_provinsi` VALUES ('64', 'KALIMANTAN TIMUR');
INSERT INTO `t_provinsi` VALUES ('65', 'KALIMANTAN UTARA');
INSERT INTO `t_provinsi` VALUES ('71', 'SULAWESI UTARA');
INSERT INTO `t_provinsi` VALUES ('72', 'SULAWESI TENGAH');
INSERT INTO `t_provinsi` VALUES ('73', 'SULAWESI SELATAN');
INSERT INTO `t_provinsi` VALUES ('74', 'SULAWESI TENGGARA');
INSERT INTO `t_provinsi` VALUES ('75', 'GORONTALO');
INSERT INTO `t_provinsi` VALUES ('76', 'SULAWESI BARAT');
INSERT INTO `t_provinsi` VALUES ('81', 'MALUKU');
INSERT INTO `t_provinsi` VALUES ('82', 'MALUKU UTARA');
INSERT INTO `t_provinsi` VALUES ('91', 'PAPUA BARAT');
INSERT INTO `t_provinsi` VALUES ('94', 'PAPUA');

-- ----------------------------
-- Table structure for t_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `t_role_menu`;
CREATE TABLE `t_role_menu`  (
  `id_menu` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `add_button` int(1) NULL DEFAULT 0,
  `edit_button` int(1) NULL DEFAULT 0,
  `delete_button` int(1) NULL DEFAULT 0,
  INDEX `f_level_user`(`id_role`) USING BTREE,
  INDEX `id_menu`(`id_menu`) USING BTREE,
  CONSTRAINT `t_role_menu_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `m_role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `t_role_menu_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `m_menu` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = COMPACT;

-- ----------------------------
-- Records of t_role_menu
-- ----------------------------
INSERT INTO `t_role_menu` VALUES (1, 1, 0, 0, 0);
INSERT INTO `t_role_menu` VALUES (6, 1, 0, 0, 0);
INSERT INTO `t_role_menu` VALUES (7, 1, 1, 1, 1);
INSERT INTO `t_role_menu` VALUES (8, 1, 1, 1, 1);
INSERT INTO `t_role_menu` VALUES (9, 1, 1, 1, 1);
INSERT INTO `t_role_menu` VALUES (10, 1, 1, 1, 1);
INSERT INTO `t_role_menu` VALUES (11, 1, 0, 0, 0);
INSERT INTO `t_role_menu` VALUES (12, 1, 1, 1, 1);
INSERT INTO `t_role_menu` VALUES (13, 1, 0, 0, 0);
INSERT INTO `t_role_menu` VALUES (14, 1, 1, 1, 1);
INSERT INTO `t_role_menu` VALUES (2, 1, 0, 0, 0);
INSERT INTO `t_role_menu` VALUES (4, 1, 1, 1, 1);
INSERT INTO `t_role_menu` VALUES (3, 1, 1, 1, 1);
INSERT INTO `t_role_menu` VALUES (15, 1, 0, 0, 0);
INSERT INTO `t_role_menu` VALUES (16, 1, 1, 1, 1);
INSERT INTO `t_role_menu` VALUES (17, 1, 1, 1, 1);
INSERT INTO `t_role_menu` VALUES (18, 1, 1, 1, 1);

-- ----------------------------
-- Table structure for t_stok
-- ----------------------------
DROP TABLE IF EXISTS `t_stok`;
CREATE TABLE `t_stok`  (
  `id_stok` int(64) NOT NULL AUTO_INCREMENT,
  `id_barang` int(11) NOT NULL,
  `id_gudang` int(4) NULL DEFAULT NULL,
  `qty` int(11) NULL DEFAULT NULL,
  `qty_min` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_stok`) USING BTREE,
  INDEX `id_barang`(`id_barang`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_stok
-- ----------------------------
INSERT INTO `t_stok` VALUES (1, 3, 1, 24, 2, '2021-10-19 06:17:54', NULL, NULL);

-- ----------------------------
-- Table structure for t_stok_mutasi
-- ----------------------------
DROP TABLE IF EXISTS `t_stok_mutasi`;
CREATE TABLE `t_stok_mutasi`  (
  `id_stok` int(64) NOT NULL,
  `id_stok_mutasi_det` int(64) NOT NULL,
  `id_barang` int(64) NULL DEFAULT NULL,
  `id_kategori_trans` int(64) NULL DEFAULT NULL,
  `id_gudang` int(64) NULL DEFAULT NULL,
  `qty` int(64) NULL DEFAULT 0,
  `qty_pakai` int(64) NULL DEFAULT 0,
  `qty_sisa` int(64) NULL DEFAULT 0,
  `qty_expired` int(64) NULL DEFAULT 0,
  `hpp` float(20, 2) NULL DEFAULT NULL,
  `kode_reff` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tanggal` date NULL DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id_stok`, `id_stok_mutasi_det`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_stok_mutasi
-- ----------------------------
INSERT INTO `t_stok_mutasi` VALUES (1, 1, 3, 3, 1, 24, 0, 24, 0, 12000.00, 'STOK AWAL', '2021-10-19', 'PENAMBAHAN', '2021-10-19 06:17:54', NULL, NULL);

-- ----------------------------
-- Function structure for dm
-- ----------------------------
DROP FUNCTION IF EXISTS `dm`;
delimiter ;;
CREATE FUNCTION `dm`(st VARCHAR(55))
 RETURNS varchar(128) CHARSET utf8
  NO SQL 
BEGIN
	DECLARE length, first, last, pos, prevpos, is_slavo_germanic SMALLINT;
	DECLARE pri, sec VARCHAR(45) DEFAULT '';
	DECLARE ch CHAR(1);
	-- returns the double metaphone code OR codes for given string
	-- if there is a secondary dm it is separated with a semicolon
	-- there are no checks done on the input string, but it should be a single word OR name.
	--  st is short for string. I usually prefer descriptive over short, but this var is used a lot!
	SET first = 3;
	SET length = CHAR_LENGTH(st);
	SET last = first + length -1;
	SET st = CONCAT(REPEAT('-', first -1), UCASE(st), REPEAT(' ', 5)); --  pad st so we can index beyond the begining AND end of the input string
	SET is_slavo_germanic = (st LIKE '%W%' OR st LIKE '%K%' OR st LIKE '%CZ%');  -- the check for '%W%' will catch WITZ
	SET pos = first; --  pos is short for position
	-- skip these silent letters when at start of word
	IF SUBSTRING(st, first, 2) IN ('GN', 'KN', 'PN', 'WR', 'PS') THEN
		SET pos = pos + 1;
	END IF;
	--  Initial 'X' is pronounced 'Z' e.g. 'Xavier'
	IF SUBSTRING(st, first, 1) = 'X' THEN
		SET pri = 'S', sec = 'S', pos = pos  + 1; -- 'Z' maps to 'S'
	END IF;
	--  main loop through chars IN st
	WHILE pos <= last DO
		-- print str(pos) + '\t' + SUBSTRING(st, pos)
    SET prevpos = pos;
		SET ch = SUBSTRING(st, pos, 1); --  ch is short for character
		CASE
		WHEN ch IN ('A', 'E', 'I', 'O', 'U', 'Y') THEN
			IF pos = first THEN --  all init vowels now map to 'A'
				SET pri = CONCAT(pri, 'A'), sec = CONCAT(sec, 'A'), pos = pos  + 1; -- nxt = ('A', 1)
			ELSE
				SET pos = pos + 1;
			END IF;
-- 		WHEN ch = 'B' THEN
-- 			-- '-mb', e.g', 'dumb', already skipped over... see 'M' below
-- 			IF SUBSTRING(st, pos+1, 1) = 'B' THEN
-- 				SET pri = CONCAT(pri, 'P'), sec = CONCAT(sec, 'P'), pos = pos  + 2; -- nxt = ('P', 2)
-- 			ELSE
-- 				SET pri = CONCAT(pri, 'P'), sec = CONCAT(sec, 'P'), pos = pos  + 1; -- nxt = ('P', 1)
-- 			END IF;
			WHEN ch = 'B' THEN
			-- '-mb', e.g', 'dumb', already skipped over... see 'M' below
			IF SUBSTRING(st, pos+1, 1) = 'B' THEN
				SET pri = CONCAT(pri, 'B'), sec = CONCAT(sec, 'B'), pos = pos  + 2; -- nxt = ('P', 2)
			ELSE
				SET pri = CONCAT(pri, 'B'), sec = CONCAT(sec, 'B'), pos = pos  + 1; -- nxt = ('P', 1)
			END IF;
		WHEN ch = 'C' THEN
			--  various germanic
			IF (pos > (first + 1) AND SUBSTRING(st, pos-2, 1) NOT IN ('A', 'E', 'I', 'O', 'U', 'Y') AND SUBSTRING(st, pos-1, 3) = 'ACH' AND
			   (SUBSTRING(st, pos+2, 1) NOT IN ('I', 'E') OR SUBSTRING(st, pos-2, 6) IN ('BACHER', 'MACHER'))) THEN
				SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
			--  special case 'CAESAR'
			ELSEIF pos = first AND SUBSTRING(st, first, 6) = 'CAESAR' THEN
				SET pri = CONCAT(pri, 'S'), sec = CONCAT(sec, 'S'), pos = pos  + 2; -- nxt = ('S', 2)
			ELSEIF SUBSTRING(st, pos, 4) = 'CHIA' THEN -- italian 'chianti'
				SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
			ELSEIF SUBSTRING(st, pos, 2) = 'CH' THEN
				--  find 'michael'
				IF pos > first AND SUBSTRING(st, pos, 4) = 'CHAE' THEN
					SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'X'), pos = pos  + 2; -- nxt = ('K', 'X', 2)
				ELSEIF pos = first AND (SUBSTRING(st, pos+1, 5) IN ('HARAC', 'HARIS') OR
				   SUBSTRING(st, pos+1, 3) IN ('HOR', 'HYM', 'HIA', 'HEM')) AND SUBSTRING(st, first, 5) != 'CHORE' THEN
					SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
				-- germanic, greek, OR otherwise 'ch' for 'kh' sound
				ELSEIF SUBSTRING(st, first, 4) IN ('VAN ', 'VON ') OR SUBSTRING(st, first, 3) = 'SCH'
				   OR SUBSTRING(st, pos-2, 6) IN ('ORCHES', 'ARCHIT', 'ORCHID')
				   OR SUBSTRING(st, pos+2, 1) IN ('T', 'S')
				   OR ((SUBSTRING(st, pos-1, 1) IN ('A', 'O', 'U', 'E') OR pos = first)
				   AND SUBSTRING(st, pos+2, 1) IN ('L', 'R', 'N', 'M', 'B', 'H', 'F', 'V', 'W', ' ')) THEN
					SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
				ELSE
					IF pos > first THEN
						IF SUBSTRING(st, first, 2) = 'MC' THEN
							SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
						ELSE
							SET pri = CONCAT(pri, 'X'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('X', 'K', 2)
						END IF;
					ELSE
						SET pri = CONCAT(pri, 'X'), sec = CONCAT(sec, 'X'), pos = pos  + 2; -- nxt = ('X', 2)
					END IF;
				END IF;
			-- e.g, 'czerny'
			ELSEIF SUBSTRING(st, pos, 2) = 'CZ' AND SUBSTRING(st, pos-2, 4) != 'WICZ' THEN
				SET pri = CONCAT(pri, 'S'), sec = CONCAT(sec, 'X'), pos = pos  + 2; -- nxt = ('S', 'X', 2)
			-- e.g., 'focaccia'
			ELSEIF SUBSTRING(st, pos+1, 3) = 'CIA' THEN
				SET pri = CONCAT(pri, 'X'), sec = CONCAT(sec, 'X'), pos = pos  + 3; -- nxt = ('X', 3)
			-- double 'C', but not IF e.g. 'McClellan'
			ELSEIF SUBSTRING(st, pos, 2) = 'CC' AND NOT (pos = (first +1) AND SUBSTRING(st, first, 1) = 'M') THEN
				-- 'bellocchio' but not 'bacchus'
				IF SUBSTRING(st, pos+2, 1) IN ('I', 'E', 'H') AND SUBSTRING(st, pos+2, 2) != 'HU' THEN
					-- 'accident', 'accede' 'succeed'
					IF (pos = first +1 AND SUBSTRING(st, first) = 'A') OR
					   SUBSTRING(st, pos-1, 5) IN ('UCCEE', 'UCCES') THEN
						SET pri = CONCAT(pri, 'KS'), sec = CONCAT(sec, 'KS'), pos = pos  + 3; -- nxt = ('KS', 3)
					-- 'bacci', 'bertucci', other italian
					ELSE
						SET pri = CONCAT(pri, 'X'), sec = CONCAT(sec, 'X'), pos = pos  + 3; -- nxt = ('X', 3)
					END IF;
				ELSE
					SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
				END IF;
			ELSEIF SUBSTRING(st, pos, 2) IN ('CK', 'CG', 'CQ') THEN
				SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 'K', 2)
			ELSEIF SUBSTRING(st, pos, 2) IN ('CI', 'CE', 'CY') THEN
				-- italian vs. english
				IF SUBSTRING(st, pos, 3) IN ('CIO', 'CIE', 'CIA') THEN
					SET pri = CONCAT(pri, 'S'), sec = CONCAT(sec, 'X'), pos = pos  + 2; -- nxt = ('S', 'X', 2)
				ELSE
					SET pri = CONCAT(pri, 'S'), sec = CONCAT(sec, 'S'), pos = pos  + 2; -- nxt = ('S', 2)
				END IF;
			ELSE
				-- name sent IN 'mac caffrey', 'mac gregor
				IF SUBSTRING(st, pos+1, 2) IN (' C', ' Q', ' G') THEN
					SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 3; -- nxt = ('K', 3)
				ELSE
					IF SUBSTRING(st, pos+1, 1) IN ('C', 'K', 'Q') AND SUBSTRING(st, pos+1, 2) NOT IN ('CE', 'CI') THEN
						SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
					ELSE --  default for 'C'
						SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 1; -- nxt = ('K', 1)
					END IF;
				END IF;
			END IF;
		-- ELSEIF ch = 'Ç' THEN --  will never get here with st.encode('ascii', 'replace') above
			-- SET pri = CONCAT(pri, '5'), sec = CONCAT(sec, '5'), pos = pos  + 1; -- nxt = ('S', 1)
		WHEN ch = 'D' THEN
			IF SUBSTRING(st, pos, 2) = 'DG' THEN
				IF SUBSTRING(st, pos+2, 1) IN ('I', 'E', 'Y') THEN -- e.g. 'edge'
					SET pri = CONCAT(pri, 'J'), sec = CONCAT(sec, 'J'), pos = pos  + 3; -- nxt = ('J', 3)
				ELSE
					SET pri = CONCAT(pri, 'TK'), sec = CONCAT(sec, 'TK'), pos = pos  + 2; -- nxt = ('TK', 2)
				END IF;
			ELSEIF SUBSTRING(st, pos, 2) IN ('DT', 'DD') THEN
				SET pri = CONCAT(pri, 'T'), sec = CONCAT(sec, 'T'), pos = pos  + 2; -- nxt = ('T', 2)
			ELSE
				SET pri = CONCAT(pri, 'T'), sec = CONCAT(sec, 'T'), pos = pos  + 1; -- nxt = ('T', 1)
			END IF;
		WHEN ch = 'F' THEN
			IF SUBSTRING(st, pos+1, 1) = 'F' THEN
				SET pri = CONCAT(pri, 'F'), sec = CONCAT(sec, 'F'), pos = pos  + 2; -- nxt = ('F', 2)
			ELSE
				SET pri = CONCAT(pri, 'F'), sec = CONCAT(sec, 'F'), pos = pos  + 1; -- nxt = ('F', 1)
			END IF;
		WHEN ch = 'G' THEN
			IF SUBSTRING(st, pos+1, 1) = 'H' THEN
				IF (pos > first AND SUBSTRING(st, pos-1, 1) NOT IN ('A', 'E', 'I', 'O', 'U', 'Y'))
					OR ( pos = first AND SUBSTRING(st, pos+2, 1) != 'I') THEN
					SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
				ELSEIF pos = first AND SUBSTRING(st, pos+2, 1) = 'I' THEN
					 SET pri = CONCAT(pri, 'J'), sec = CONCAT(sec, 'J'), pos = pos  + 2; -- nxt = ('J', 2)
				-- Parker's rule (with some further refinements) - e.g., 'hugh'
				ELSEIF (pos > (first + 1) AND SUBSTRING(st, pos-2, 1) IN ('B', 'H', 'D') )
				   OR (pos > (first + 2) AND SUBSTRING(st, pos-3, 1) IN ('B', 'H', 'D') )
				   OR (pos > (first + 3) AND SUBSTRING(st, pos-4, 1) IN ('B', 'H') ) THEN
					SET pos = pos + 2; -- nxt = (None, 2)
				ELSE
					--  e.g., 'laugh', 'McLaughlin', 'cough', 'gough', 'rough', 'tough'
					IF pos > (first + 2) AND SUBSTRING(st, pos-1, 1) = 'U'
					   AND SUBSTRING(st, pos-3, 1) IN ('C', 'G', 'L', 'R', 'T') THEN
						SET pri = CONCAT(pri, 'F'), sec = CONCAT(sec, 'F'), pos = pos  + 2; -- nxt = ('F', 2)
					ELSEIF pos > first AND SUBSTRING(st, pos-1, 1) != 'I' THEN
						SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
          ELSE
              SET pos = pos + 1;
					END IF;
				END IF;
			ELSEIF SUBSTRING(st, pos+1, 1) = 'N' THEN
				IF pos = (first +1) AND SUBSTRING(st, first, 1) IN ('A', 'E', 'I', 'O', 'U', 'Y') AND NOT is_slavo_germanic THEN
					SET pri = CONCAT(pri, 'KN'), sec = CONCAT(sec, 'N'), pos = pos  + 2; -- nxt = ('KN', 'N', 2)
				ELSE
					--  not e.g. 'cagney'
					IF SUBSTRING(st, pos+2, 2) != 'EY' AND SUBSTRING(st, pos+1, 1) != 'Y'
						AND NOT is_slavo_germanic THEN
						SET pri = CONCAT(pri, 'N'), sec = CONCAT(sec, 'KN'), pos = pos  + 2; -- nxt = ('N', 'KN', 2)
					ELSE
						SET pri = CONCAT(pri, 'KN'), sec = CONCAT(sec, 'KN'), pos = pos  + 2; -- nxt = ('KN', 2)
					END IF;
				END IF;
			--  'tagliaro'
			ELSEIF SUBSTRING(st, pos+1, 2) = 'LI' AND NOT is_slavo_germanic THEN
				SET pri = CONCAT(pri, 'KL'), sec = CONCAT(sec, 'L'), pos = pos  + 2; -- nxt = ('KL', 'L', 2)
			--  -ges-,-gep-,-gel-, -gie- at beginning
			ELSEIF pos = first AND (SUBSTRING(st, pos+1, 1) = 'Y'
			   OR SUBSTRING(st, pos+1, 2) IN ('ES', 'EP', 'EB', 'EL', 'EY', 'IB', 'IL', 'IN', 'IE', 'EI', 'ER')) THEN
				SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'J'), pos = pos  + 2; -- nxt = ('K', 'J', 2)
			--  -ger-,  -gy-
			ELSEIF (SUBSTRING(st, pos+1, 2) = 'ER' OR SUBSTRING(st, pos+1, 1) = 'Y')
			   AND SUBSTRING(st, first, 6) NOT IN ('DANGER', 'RANGER', 'MANGER')
			   AND SUBSTRING(st, pos-1, 1) not IN ('E', 'I') AND SUBSTRING(st, pos-1, 3) NOT IN ('RGY', 'OGY') THEN
				SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'J'), pos = pos  + 2; -- nxt = ('K', 'J', 2)
			--  italian e.g, 'biaggi'
			ELSEIF SUBSTRING(st, pos+1, 1) IN ('E', 'I', 'Y') OR SUBSTRING(st, pos-1, 4) IN ('AGGI', 'OGGI') THEN
				--  obvious germanic
				IF SUBSTRING(st, first, 4) IN ('VON ', 'VAN ') OR SUBSTRING(st, first, 3) = 'SCH'
				   OR SUBSTRING(st, pos+1, 2) = 'ET' THEN
					SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
				ELSE
					--  always soft IF french ending
					IF SUBSTRING(st, pos+1, 4) = 'IER ' THEN
						SET pri = CONCAT(pri, 'J'), sec = CONCAT(sec, 'J'), pos = pos  + 2; -- nxt = ('J', 2)
					ELSE
						SET pri = CONCAT(pri, 'J'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('J', 'K', 2)
					END IF;
				END IF;
			ELSEIF SUBSTRING(st, pos+1, 1) = 'G' THEN
				SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
			ELSE
				SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 1; -- nxt = ('K', 1)
			END IF;
		WHEN ch = 'H' THEN
			--  only keep IF first & before vowel OR btw. 2 ('A', 'E', 'I', 'O', 'U', 'Y')
			IF (pos = first OR SUBSTRING(st, pos-1, 1) IN ('A', 'E', 'I', 'O', 'U', 'Y'))
				AND SUBSTRING(st, pos+1, 1) IN ('A', 'E', 'I', 'O', 'U', 'Y') THEN
				SET pri = CONCAT(pri, 'H'), sec = CONCAT(sec, 'H'), pos = pos  + 2; -- nxt = ('H', 2)
			ELSE --  (also takes care of 'HH')
				SET pos = pos + 1; -- nxt = (None, 1)
			END IF;
		WHEN ch = 'J' THEN
			--  obvious spanish, 'jose', 'san jacinto'
			IF SUBSTRING(st, pos, 4) = 'JOSE' OR SUBSTRING(st, first, 4) = 'SAN ' THEN
				IF (pos = first AND SUBSTRING(st, pos+4, 1) = ' ') OR SUBSTRING(st, first, 4) = 'SAN ' THEN
					SET pri = CONCAT(pri, 'H'), sec = CONCAT(sec, 'H'); -- nxt = ('H',)
				ELSE
					SET pri = CONCAT(pri, 'J'), sec = CONCAT(sec, 'H'); -- nxt = ('J', 'H')
				END IF;
			ELSEIF pos = first AND SUBSTRING(st, pos, 4) != 'JOSE' THEN
				SET pri = CONCAT(pri, 'J'), sec = CONCAT(sec, 'A'); -- nxt = ('J', 'A') --  Yankelovich/Jankelowicz
			ELSE
				--  spanish pron. of e.g. 'bajador'
				IF SUBSTRING(st, pos-1, 1) IN ('A', 'E', 'I', 'O', 'U', 'Y') AND NOT is_slavo_germanic
				   AND SUBSTRING(st, pos+1, 1) IN ('A', 'O') THEN
					SET pri = CONCAT(pri, 'J'), sec = CONCAT(sec, 'H'); -- nxt = ('J', 'H')
				ELSE
					IF pos = last THEN
						SET pri = CONCAT(pri, 'J'); -- nxt = ('J', ' ')
					ELSE
						IF SUBSTRING(st, pos+1, 1) not IN ('L', 'T', 'K', 'S', 'N', 'M', 'B', 'Z')
						   AND SUBSTRING(st, pos-1, 1) not IN ('S', 'K', 'L') THEN
							SET pri = CONCAT(pri, 'J'), sec = CONCAT(sec, 'J'); -- nxt = ('J',)
						END IF;
					END IF;
				END IF;
			END IF;
			IF SUBSTRING(st, pos+1, 1) = 'J' THEN
				SET pos = pos + 2;
			ELSE
				SET pos = pos + 1;
			END IF;
		WHEN ch = 'K' THEN
			IF SUBSTRING(st, pos+1, 1) = 'K' THEN
				SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
			ELSE
				SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 1; -- nxt = ('K', 1)
			END IF;
		WHEN ch = 'L' THEN
			IF SUBSTRING(st, pos+1, 1) = 'L' THEN
				--  spanish e.g. 'cabrillo', 'gallegos'
				IF (pos = (last - 2) AND SUBSTRING(st, pos-1, 4) IN ('ILLO', 'ILLA', 'ALLE'))
				   OR ((SUBSTRING(st, last-1, 2) IN ('AS', 'OS') OR SUBSTRING(st, last) IN ('A', 'O'))
				   AND SUBSTRING(st, pos-1, 4) = 'ALLE') THEN
					SET pri = CONCAT(pri, 'L'), pos = pos  + 2; -- nxt = ('L', ' ', 2)
				ELSE
					SET pri = CONCAT(pri, 'L'), sec = CONCAT(sec, 'L'), pos = pos  + 2; -- nxt = ('L', 2)
				END IF;
			ELSE
				SET pri = CONCAT(pri, 'L'), sec = CONCAT(sec, 'L'), pos = pos  + 1; -- nxt = ('L', 1)
			END IF;
		WHEN ch = 'M' THEN
			IF SUBSTRING(st, pos-1, 3) = 'UMB'
			   AND (pos + 1 = last OR SUBSTRING(st, pos+2, 2) = 'ER')
			   OR SUBSTRING(st, pos+1, 1) = 'M' THEN
				SET pri = CONCAT(pri, 'M'), sec = CONCAT(sec, 'M'), pos = pos  + 2; -- nxt = ('M', 2)
			ELSE
				SET pri = CONCAT(pri, 'M'), sec = CONCAT(sec, 'M'), pos = pos  + 1; -- nxt = ('M', 1)
			END IF;
		WHEN ch = 'N' THEN
			IF SUBSTRING(st, pos+1, 1) = 'N' THEN
				SET pri = CONCAT(pri, 'N'), sec = CONCAT(sec, 'N'), pos = pos  + 2; -- nxt = ('N', 2)
			ELSE
				SET pri = CONCAT(pri, 'N'), sec = CONCAT(sec, 'N'), pos = pos  + 1; -- nxt = ('N', 1)
			END IF;
		-- ELSEIF ch = u'Ñ' THEN
			-- SET pri = CONCAT(pri, '5'), sec = CONCAT(sec, '5'), pos = pos  + 1; -- nxt = ('N', 1)
		WHEN ch = 'P' THEN
			IF SUBSTRING(st, pos+1, 1) = 'H' THEN
				SET pri = CONCAT(pri, 'F'), sec = CONCAT(sec, 'F'), pos = pos  + 2; -- nxt = ('F', 2)
			ELSEIF SUBSTRING(st, pos+1, 1) IN ('P', 'B') THEN --  also account for 'campbell', 'raspberry'
				SET pri = CONCAT(pri, 'P'), sec = CONCAT(sec, 'P'), pos = pos  + 2; -- nxt = ('P', 2)
			ELSE
				SET pri = CONCAT(pri, 'P'), sec = CONCAT(sec, 'P'), pos = pos  + 1; -- nxt = ('P', 1)
			END IF;
		WHEN ch = 'Q' THEN
			IF SUBSTRING(st, pos+1, 1) = 'Q' THEN
				SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 2; -- nxt = ('K', 2)
			ELSE
				SET pri = CONCAT(pri, 'K'), sec = CONCAT(sec, 'K'), pos = pos  + 1; -- nxt = ('K', 1)
			END IF;
		WHEN ch = 'R' THEN
			--  french e.g. 'rogier', but exclude 'hochmeier'
			IF pos = last AND not is_slavo_germanic
			   AND SUBSTRING(st, pos-2, 2) = 'IE' AND SUBSTRING(st, pos-4, 2) NOT IN ('ME', 'MA') THEN
				SET sec = CONCAT(sec, 'R'); -- nxt = ('', 'R')
			ELSE
				SET pri = CONCAT(pri, 'R'), sec = CONCAT(sec, 'R'); -- nxt = ('R',)
			END IF;
			IF SUBSTRING(st, pos+1, 1) = 'R' THEN
				SET pos = pos + 2;
			ELSE
				SET pos = pos + 1;
			END IF;
		WHEN ch = 'S' THEN
			--  special cases 'island', 'isle', 'carlisle', 'carlysle'
			IF SUBSTRING(st, pos-1, 3) IN ('ISL', 'YSL') THEN
				SET pos = pos + 1;
			--  special case 'sugar-'
			ELSEIF pos = first AND SUBSTRING(st, first, 5) = 'SUGAR' THEN
				SET pri = CONCAT(pri, 'X'), sec = CONCAT(sec, 'S'), pos = pos  + 1; --  nxt =('X', 'S', 1)
			ELSEIF SUBSTRING(st, pos, 2) = 'SH' THEN
				--  germanic
				IF SUBSTRING(st, pos+1, 4) IN ('HEIM', 'HOEK', 'HOLM', 'HOLZ') THEN
					SET pri = CONCAT(pri, 'S'), sec = CONCAT(sec, 'S'), pos = pos  + 2; -- nxt = ('S', 2)
				ELSE
					SET pri = CONCAT(pri, 'X'), sec = CONCAT(sec, 'X'), pos = pos  + 2; -- nxt = ('X', 2)
				END IF;
			--  italian & armenian
			ELSEIF SUBSTRING(st, pos, 3) IN ('SIO', 'SIA') OR SUBSTRING(st, pos, 4) = 'SIAN' THEN
				IF NOT is_slavo_germanic THEN
					SET pri = CONCAT(pri, 'S'), sec = CONCAT(sec, 'X'), pos = pos  + 3; -- nxt = ('S', 'X', 3)
				ELSE
					SET pri = CONCAT(pri, 'S'), sec = CONCAT(sec, 'S'), pos = pos  + 3; -- nxt = ('S', 3)
				END IF;
			--  german & anglicisations, e.g. 'smith' match 'schmidt', 'snider' match 'schneider'
			--  also, -sz- IN slavic language altho IN hungarian it is pronounced 's'
			ELSEIF (pos = first AND SUBSTRING(st, pos+1, 1) IN ('M', 'N', 'L', 'W')) OR SUBSTRING(st, pos+1, 1) = 'Z' THEN
				SET pri = CONCAT(pri, 'S'), sec = CONCAT(sec, 'X'); -- nxt = ('S', 'X')
				IF SUBSTRING(st, pos+1, 1) = 'Z' THEN
					SET pos = pos + 2;
				ELSE
					SET pos = pos + 1;
				END IF;
			ELSEIF SUBSTRING(st, pos, 2) = 'SC' THEN
				--  Schlesinger's rule
				IF SUBSTRING(st, pos+2, 1) = 'H' THEN
					--  dutch origin, e.g. 'school', 'schooner'
					IF SUBSTRING(st, pos+3, 2) IN ('OO', 'ER', 'EN', 'UY', 'ED', 'EM') THEN
						--  'schermerhorn', 'schenker'
						IF SUBSTRING(st, pos+3, 2) IN ('ER', 'EN') THEN
							SET pri = CONCAT(pri, 'X'), sec = CONCAT(sec, 'SK'), pos = pos  + 3; -- nxt = ('X', 'SK', 3)
						ELSE
							SET pri = CONCAT(pri, 'SK'), sec = CONCAT(sec, 'SK'), pos = pos  + 3; -- nxt = ('SK', 3)
						END IF;
					ELSE
						IF pos = first AND SUBSTRING(st, first+3, 1) not IN ('A', 'E', 'I', 'O', 'U', 'Y') AND SUBSTRING(st, first+3, 1) != 'W' THEN
							SET pri = CONCAT(pri, 'X'), sec = CONCAT(sec, 'S'), pos = pos  + 3; -- nxt = ('X', 'S', 3)
						ELSE
							SET pri = CONCAT(pri, 'X'), sec = CONCAT(sec, 'X'), pos = pos  + 3; -- nxt = ('X', 3)
						END IF;
					END IF;
				ELSEIF SUBSTRING(st, pos+2, 1) IN ('I', 'E', 'Y') THEN
					SET pri = CONCAT(pri, 'S'), sec = CONCAT(sec, 'S'), pos = pos  + 3; -- nxt = ('S', 3)
				ELSE
					SET pri = CONCAT(pri, 'SK'), sec = CONCAT(sec, 'SK'), pos = pos  + 3; -- nxt = ('SK', 3)
				END IF;
			--  french e.g. 'resnais', 'artois'
			ELSEIF pos = last AND SUBSTRING(st, pos-2, 2) IN ('AI', 'OI') THEN
				SET sec = CONCAT(sec, 'S'), pos = pos  + 1; -- nxt = ('', 'S')
			ELSE
				SET pri = CONCAT(pri, 'S'), sec = CONCAT(sec, 'S'); -- nxt = ('S',)
				IF SUBSTRING(st, pos+1, 1) IN ('S', 'Z') THEN
					SET pos = pos + 2;
				ELSE
					SET pos = pos + 1;
				END IF;
			END IF;
		WHEN ch = 'T' THEN
			IF SUBSTRING(st, pos, 4) = 'TION' THEN
				SET pri = CONCAT(pri, 'X'), sec = CONCAT(sec, 'X'), pos = pos  + 3; -- nxt = ('X', 3)
			ELSEIF SUBSTRING(st, pos, 3) IN ('TIA', 'TCH') THEN
				SET pri = CONCAT(pri, 'X'), sec = CONCAT(sec, 'X'), pos = pos  + 3; -- nxt = ('X', 3)
			ELSEIF SUBSTRING(st, pos, 2) = 'TH' OR SUBSTRING(st, pos, 3) = 'TTH' THEN
				--  special case 'thomas', 'thames' OR germanic
				IF SUBSTRING(st, pos+2, 2) IN ('OM', 'AM') OR SUBSTRING(st, first, 4) IN ('VON ', 'VAN ')
				   OR SUBSTRING(st, first, 3) = 'SCH' THEN
					SET pri = CONCAT(pri, 'T'), sec = CONCAT(sec, 'T'), pos = pos  + 2; -- nxt = ('T', 2)
				ELSE
					SET pri = CONCAT(pri, '0'), sec = CONCAT(sec, 'T'), pos = pos  + 2; -- nxt = ('0', 'T', 2)
				END IF;
			ELSEIF SUBSTRING(st, pos+1, 1) IN ('T', 'D') THEN
				SET pri = CONCAT(pri, 'T'), sec = CONCAT(sec, 'T'), pos = pos  + 2; -- nxt = ('T', 2)
			ELSE
				SET pri = CONCAT(pri, 'T'), sec = CONCAT(sec, 'T'), pos = pos  + 1; -- nxt = ('T', 1)
			END IF;
		WHEN ch = 'V' THEN
			IF SUBSTRING(st, pos+1, 1) = 'V' THEN
				SET pri = CONCAT(pri, 'F'), sec = CONCAT(sec, 'F'), pos = pos  + 2; -- nxt = ('F', 2)
			ELSE
				SET pri = CONCAT(pri, 'F'), sec = CONCAT(sec, 'F'), pos = pos  + 1; -- nxt = ('F', 1)
			END IF;
		WHEN ch = 'W' THEN
			--  can also be IN middle of word
			IF SUBSTRING(st, pos, 2) = 'WR' THEN
				SET pri = CONCAT(pri, 'R'), sec = CONCAT(sec, 'R'), pos = pos  + 2; -- nxt = ('R', 2)
			ELSEIF pos = first AND (SUBSTRING(st, pos+1, 1) IN ('A', 'E', 'I', 'O', 'U', 'Y')
				OR SUBSTRING(st, pos, 2) = 'WH') THEN
				--  Wasserman should match Vasserman
				IF SUBSTRING(st, pos+1, 1) IN ('A', 'E', 'I', 'O', 'U', 'Y') THEN
					SET pri = CONCAT(pri, 'A'), sec = CONCAT(sec, 'F'), pos = pos  + 1; -- nxt = ('A', 'F', 1)
				ELSE
					SET pri = CONCAT(pri, 'A'), sec = CONCAT(sec, 'A'), pos = pos  + 1; -- nxt = ('A', 1)
				END IF;
			--  Arnow should match Arnoff
			ELSEIF (pos = last AND SUBSTRING(st, pos-1, 1) IN ('A', 'E', 'I', 'O', 'U', 'Y'))
			   OR SUBSTRING(st, pos-1, 5) IN ('EWSKI', 'EWSKY', 'OWSKI', 'OWSKY')
			   OR SUBSTRING(st, first, 3) = 'SCH' THEN
				SET sec = CONCAT(sec, 'F'), pos = pos  + 1; -- nxt = ('', 'F', 1)
			-- END IF;
			--  polish e.g. 'filipowicz'
			ELSEIF SUBSTRING(st, pos, 4) IN ('WICZ', 'WITZ') THEN
				SET pri = CONCAT(pri, 'TS'), sec = CONCAT(sec, 'FX'), pos = pos  + 4; -- nxt = ('TS', 'FX', 4)
			ELSE --  default is to skip it
				SET pos = pos + 1;
			END IF;
		WHEN ch = 'X' THEN
			--  french e.g. breaux
			IF not(pos = last AND (SUBSTRING(st, pos-3, 3) IN ('IAU', 'EAU')
			   OR SUBSTRING(st, pos-2, 2) IN ('AU', 'OU'))) THEN
				SET pri = CONCAT(pri, 'KS'), sec = CONCAT(sec, 'KS'); -- nxt = ('KS',)
			END IF;
			IF SUBSTRING(st, pos+1, 1) IN ('C', 'X') THEN
				SET pos = pos + 2;
			ELSE
				SET pos = pos + 1;
			END IF;
		WHEN ch = 'Z' THEN
			--  chinese pinyin e.g. 'zhao'
			IF SUBSTRING(st, pos+1, 1) = 'H' THEN
				SET pri = CONCAT(pri, 'J'), sec = CONCAT(sec, 'J'), pos = pos  + 1; -- nxt = ('J', 2)
			ELSEIF SUBSTRING(st, pos+1, 3) IN ('ZO', 'ZI', 'ZA')
			   OR (is_slavo_germanic AND pos > first AND SUBSTRING(st, pos-1, 1) != 'T') THEN
				SET pri = CONCAT(pri, 'S'), sec = CONCAT(sec, 'TS'); -- nxt = ('S', 'TS')
			ELSE
				SET pri = CONCAT(pri, 'S'), sec = CONCAT(sec, 'S'); -- nxt = ('S',)
			END IF;
			IF SUBSTRING(st, pos+1, 1) = 'Z' THEN
				SET pos = pos + 2;
			ELSE
				SET pos = pos + 1;
			END IF;
		ELSE
			SET pos = pos + 1; -- DEFAULT is to move to next char
		END CASE;
    IF pos = prevpos THEN
       SET pos = pos +1;
       SET pri = CONCAT(pri,'<didnt incr>'); -- it might be better to throw an error here if you really must be accurate
    END IF;
	END WHILE;
	IF pri != sec THEN
		SET pri = CONCAT(pri, ';', sec);
  END IF;
	RETURN (pri);
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
