CREATE DATABASE db_onlineshopping DEFAULT CHARACTER SET utf8;

use db_onlineshopping;



--
-- 建立會員(tbl_member)資料表結構
--

CREATE TABLE `tbl_users` (
    `m_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `m_name` varchar(20) NOT NULL,
    `m_username`varchar(25) NOT NULL,
    `m_password` varchar(120) NOT NULL,
    `m_gender` enum('男','女') NOT NULL,
    `m_level` enum('admin','member','readonly','suspension') DEFAULT 'member',
    `m_email` varchar(100) DEFAULT NULL,
    `m_phone` varchar(100) DEFAULT NULL,
    `m_jointime` varchar(50) NOT NULL,
    `m_birthday` varchar(50) NOT NULL,
    PRIMARY KEY(`m_id`),
    UNIQUE KEY `m_email` (`m_email`),
    UNIQUE KEY `m_username` (`m_username`)

)ENGINE=InnoDB ;

--
-- 新增使用者(tbl_users)資料表記錄
--

INSERT INTO `tbl_users` (`m_name`, `m_username`, `m_password`, `m_gender`, `m_level`, `m_email`, `m_phone`, `m_jointime`,`m_birthday`)
VALUES
('林佐博', 'vki2388', '3dad07d9788c22561f37d9343d5e33dd6c123ae9d9617b3182873f7d7bccbb92', '男', 'admin', 'Arrom1989@gustr.com', '0911708319', '2015-01-03 16:35:37', '1992-07-23'),
('黃宜珊', 'ios820z', 'd8b763c8d6b3dde0960a49b144e418d4fe1edabc8e379ba1064adb40d2b1176d', '女', 'member', 'muveffone-1814@yopmail.com', '0924803904', '2017-08-16 11:41:03', '1994-03-14'),
('葉家銘', 'djc89oo22', 'f418dd08b196dfaf3444e1ff8ac3f9c4165f221876ddf0dadff8a2251f8eb03d', '男', 'member', 'tapeppave-9843@yopmail.com', '0929225336', '2017-01-20 14:11:40', '1982-10-23'),
('張世偉', 'xxc2jjy3', 'eb8536a5acde048fb1d542318119ea276122d3ac9fe6d9231fba53d18e3665d3', '男', 'member', 'xudokinneby-1466@yopmail.com', '0961922175', '2015-09-04 10:03:24', '1990-06-08'),
('王紹一', 'jib8cb2lk', '3b2966c0210e4e977d0b796cd1814e3545c2291c8884be1d90e7ec47c0f63d08', '男', 'member', 'unnosoleff-2307@yopmail.com', '0932919438', '2018-01-17 15:43:08', '1992-06-13');

--
-- 建立商品類別(tbl_category)資料表結構
--

CREATE TABLE `tbl_category` (
  `ca_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ca_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ca_id`)
)ENGINE=InnoDB;



--
-- 新增商品類別(tbl_category)資料表記錄
--

INSERT INTO `tbl_category` (`ca_name`)
VALUES
('全部產品'),('拜亞動力'),('森海塞爾'),('愛科技'),('歌德');





--
-- 建立商品清單(tbl_product)資料表結構
--

CREATE TABLE `tbl_product` (
  `prd_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ca_id` int(11) UNSIGNED NOT NULL,
  `prd_name` varchar(100) DEFAULT NULL,
  `prd_price` int(11) UNSIGNED DEFAULT NULL,
  `prd_images` varchar(250) DEFAULT NULL,
  `prd_description` text DEFAULT NULL,
  PRIMARY KEY (`prd_id`),
  constraint `fk_product_category_ca_id` foreign key (ca_id) references tbl_category (ca_id) ON UPDATE CASCADE
)ENGINE=InnoDB;


--
-- 新增商品清單(tbl_product)資料表記錄
--

INSERT INTO `tbl_product` (`ca_id`,`prd_name`,`prd_price`,`prd_images`,`prd_description`)
VALUES
(2,'【Beyerdynamic】拜亞動力 T1 2nd generation 旗艦級半開放式耳罩耳機',40000,'/PID_Assignment/img/beyerdynamic_T1 2nd generation.jpg','拜亞動力的旗艦級半開放式耳罩耳機'),
(3,'【SENNHEISER】森海塞爾 MOMENTUM 2.0 Wireless M2 AEBT 藍牙降噪無線耳罩式耳機',12500,'/PID_Assignment/img/momentum 2.0 wireless m2 aebt.jpg','森海塞爾的藍牙降噪無線耳罩式耳機'),
(4,'【AKG K701】 愛科技 K701 專業級 開放式監聽耳罩耳機',7200,'/PID_Assignment/img/akg k701.jpg','極深的頻率延伸,燦爛的高頻,和天鵝絨般柔順鬆軟的音色,強勁的低音,成為極完美的聲音!'),
(5,'【GRADO】 歌德 Headphones RS2E [RS2E] j4shAv New from Japan EMS',25361,'/PID_Assignment/img/grado rS2e.jpg','富有鮮明色彩的音色。飽滿且有生命力的聲音表現使曲子聽起來格
外熱情、歡快且栩栩如生。'),
(6,'【SONY】 索尼 WF-1000XM3',7490,'/PID_Assignment/img/WF-1000XM3_B_standard_b0026.jpg','全新 QN1e 晶片結合高音質和降噪處理器，並內建雙重雜訊感應器，提供最優異的音質和主動式降噪表現。');





CREATE TABLE `tbl_orders` (
  `ord_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, 
  `m_id` int(11) UNSIGNED NOT NULL,
  `ord_total` int(11) UNSIGNED DEFAULT NULL,
  `ord_deliverfee` int(11) UNSIGNED DEFAULT NULL,
  `ord_grandtotal` int(11) UNSIGNED DEFAULT NULL,
  `ord_customername` varchar(100) DEFAULT NULL,
  `ord_customeremail` varchar(100) DEFAULT NULL,
  `ord_customeraddress` varchar(100) DEFAULT NULL,
  `ord_customerphone`  varchar(100) DEFAULT NULL,
  `ord_paytype` enum('線上付款','ATM轉帳','貨到付款'),
  `ord_purchasetime` varchar(100) NOT NULL,
  `ord_status`  enum('處理中','已確認','已完成'),
  PRIMARY KEY (`ord_id`),
  constraint `fk_orders_users_m_id` foreign key (m_id) references tbl_users (m_id) ON UPDATE CASCADE
)ENGINE=InnoDB;

INSERT INTO `tbl_orders` (`m_id`,`ord_total`,`ord_customername`,`ord_customeremail`,`ord_customeraddress`,`ord_customerphone`,`ord_paytype`,`ord_purchasetime`, `ord_status`)
VALUES
(12,12000,'鈴栖','chieri@gmail.com','日本','0823889299','線上刷卡','2018-02-12 18:50:08','處理中');




CREATE TABLE `tbl_orderdetail` (
  `dets_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ord_id` int(11) UNSIGNED NOT NULL,
  `prd_id` int(11) UNSIGNED NOT NULL,
  `dets_name` varchar(100) DEFAULT NULL,
  `dets_unitprice` int(11) UNSIGNED DEFAULT NULL,
  `dets_quantity` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`dets_id`),
  constraint `fk_orderdetail_orders_ord_id` foreign key (ord_id) references tbl_orders (ord_id) ON UPDATE CASCADE ON DELETE CASCADE,
  constraint `fk_orderdetail_product_prd_id` foreign key (prd_id) references tbl_product (prd_id) ON UPDATE CASCADE
)ENGINE=InnoDB;


INSERT INTO `tbl_orderdetail` (`ord_id`,`prd_id`,`dets_name`,`dets_unitprice`,`dets_quantity`)
VALUES
(1,1,'【Beyerdynamic】拜亞動力 T1 2nd generation 旗艦級半開放式耳罩耳機',40000,2);











