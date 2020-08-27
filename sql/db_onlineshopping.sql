CREATE DATABASE db_onlineshopping DEFAULT CHARACTER SET utf8;

use db_onlineshopping;



--
-- 建立會員(tbl_member)資料表結構
--

CREATE TABLE `tbl_users` (
    `m_id` int(11) NOT NULL AUTO_INCREMENT,
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
-- 建立地址(tbl_users)資料表結構
--

CREATE TABLE `tbl_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `address` varchar(200) NOT NULL,
  `zipcode` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  constraint `fk_address_user_id` foreign key (user_id) references tbl_users (id) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;












