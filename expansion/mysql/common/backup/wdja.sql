CREATE TABLE `wdja_aboutus` (
  `abid` int NOT NULL AUTO_INCREMENT,
  `ab_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ab_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ab_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ab_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ab_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ab_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `ab_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `ab_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `ab_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `ab_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ab_hidden` int DEFAULT '0',
  `ab_good` int DEFAULT '0',
  `ab_tpl` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ab_gourl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ab_count` int DEFAULT '0',
  `ab_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`abid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_aboutus` VALUES('1', '春天里', '', '', '', 'common/upload/noimg.gif', '<p>这里可以写一下介绍内容</p>', '', '2022-06-16 15:41:53', '2022-06-16 15:41:53', '', '0', '0', '', '', '0', 'chinese');

CREATE TABLE `wdja_admin` (
  `aid` int NOT NULL AUTO_INCREMENT,
  `a_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `a_pword` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `a_popedom` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `a_lock` int DEFAULT '0',
  `a_lasttime` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `a_lastip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_admin` VALUES('1', 'admin', '926b4f1d65e19d81680d8f2b7449e627', '-1', '0', '2022-07-05 16:34:10', '127.0.0.1');

CREATE TABLE `wdja_admin_log` (
  `lid` int NOT NULL AUTO_INCREMENT,
  `l_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `l_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `l_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `l_islogin` int DEFAULT '0',
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_admin_log` VALUES('1', 'admin', '2021-11-23 17:22:52', '127.0.0.1', '1'),('2', 'admin', '2021-11-24 09:56:57', '127.0.0.1', '1'),('3', 'admin', '2021-12-08 13:47:07', '127.0.0.1', '0'),('4', 'admin', '2021-12-08 13:47:14', '127.0.0.1', '1'),('5', 'admin', '2021-12-14 08:57:04', '127.0.0.1', '0'),('6', 'admin', '2021-12-14 08:57:09', '127.0.0.1', '0'),('7', 'admin', '2021-12-14 08:57:16', '127.0.0.1', '1'),('8', 'admin', '2021-12-21 12:58:12', '127.0.0.1', '1'),('9', 'admin', '2021-12-21 12:58:14', '127.0.0.1', '1'),('10', 'admin', '2021-12-22 11:19:11', '127.0.0.1', '1'),('11', 'admin', '2021-12-24 10:32:19', '127.0.0.1', '1'),('12', 'admin', '2022-01-05 11:41:12', '127.0.0.1', '1'),('13', 'admin', '2022-01-06 09:11:02', '127.0.0.1', '1'),('14', 'admin', '2022-01-18 08:58:43', '127.0.0.1', '1'),('15', 'admin', '2022-02-14 09:50:51', '127.0.0.1', '1'),('16', 'admin', '2022-04-09 09:49:01', '127.0.0.1', '1'),('17', 'admin', '2022-06-13 16:48:00', '127.0.0.1', '1'),('18', 'admin', '2022-06-14 09:20:05', '127.0.0.1', '1'),('19', 'admin', '2022-06-14 10:20:11', '127.0.0.1', '1'),('20', 'admin', '2022-06-15 14:47:41', '127.0.0.1', '1'),('21', 'admin', '2022-06-16 15:37:49', '127.0.0.1', '1'),('22', 'admin', '2022-06-17 10:26:12', '127.0.0.1', '1'),('23', 'admin', '2022-06-20 10:50:15', '127.0.0.1', '1'),('24', 'admin', '2022-06-21 14:41:09', '127.0.0.1', '1'),('25', 'admin', '2022-06-23 11:28:41', '127.0.0.1', '1'),('26', 'admin', '2022-07-05 15:44:52', '127.0.0.1', '1'),('27', 'admin', '2022-07-05 16:34:10', '127.0.0.1', '1');

CREATE TABLE `wdja_ask` (
  `aid` int NOT NULL AUTO_INCREMENT,
  `a_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `a_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `a_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `a_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `a_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `a_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `a_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `a_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `a_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `a_cls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `a_class` int DEFAULT '0',
  `a_class_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `a_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `a_hidden` int DEFAULT '0',
  `a_good` int DEFAULT '0',
  `a_count` int DEFAULT '0',
  `a_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_ask` VALUES('1', '关联内容输出优化', '', '', '', 'common/upload/noimg.gif', '<p>1人关联内容输出优化</p>', '', '2022-06-16 16:03:40', '2022-06-16 16:03:40', '|0|,|6|', '6', '6', '', '0', '0', '5', 'chinese');

CREATE TABLE `wdja_ask_answer` (
  `aid` int NOT NULL AUTO_INCREMENT,
  `a_author` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `a_authorip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `a_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `a_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `a_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `a_fid` int DEFAULT '0',
  `a_good` int DEFAULT '0',
  `a_hidden` int DEFAULT '0',
  `a_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_ask_answer` VALUES('1', '*游客*', '127.0.0.1', '关联内容输出优化', '2022-06-16 16:03:52', '2021-08-01 08:00:00', '1', '0', '1', 'chinese');

CREATE TABLE `wdja_check` (
  `cid` int NOT NULL AUTO_INCREMENT,
  `c_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_gid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_sex` int DEFAULT '0',
  `c_mobile` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `c_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_title` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `c_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `c_reply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `c_replytime` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `c_hidden` int DEFAULT '0',
  `c_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`cid`),
  UNIQUE KEY `cid` (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_check` VALUES('1', 'http://www.hosts.run/news/?type=detail&id=2', 'news', '2', '李', '127.0.0.1', '0', '', 'se@22.com', '', '', '测试一下', '2022-06-14 11:46:45', '好的，已修正。', '2021-08-01 08:00:00', '0', 'chinese');

CREATE TABLE `wdja_download` (
  `did` int NOT NULL AUTO_INCREMENT,
  `d_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `d_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `d_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `d_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `d_cls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `d_class` int DEFAULT '0',
  `d_class_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `d_scont` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `d_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `d_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `d_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `d_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `d_size` float DEFAULT '0',
  `d_urls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `d_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `d_hidden` int DEFAULT '0',
  `d_good` int DEFAULT '0',
  `d_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `d_count` int DEFAULT '0',
  `d_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `d_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`did`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_download` VALUES('1', '下载一4', '', '', '', '|0|,|7|', '7', '7', '', 'common/upload/noimg.gif', '', '', '', '3', '{"0":["1","网盘","#"]}', '', '0', '0', '2022-06-17 13:53:33', '1', '2021-08-01 08:00:00', 'chinese'),('2', '下载二', '', '', '', '|0|,|7|', '7', '7', '', 'common/upload/noimg.gif', '', '', '', '0', '{"0":["1","网盘","#"]}', '', '0', '0', '2022-06-17 13:54:13', '15', '2021-08-01 08:00:00', 'chinese');

CREATE TABLE `wdja_expansion_baidupush` (
  `bid` int NOT NULL AUTO_INCREMENT,
  `b_genre` varchar(152) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `b_gid` int NOT NULL,
  `b_topic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `b_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `b_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `b_count` int DEFAULT '0',
  `b_type` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `b_state` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `b_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `b_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `b_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_baidupush_data` (
  `bdid` int NOT NULL AUTO_INCREMENT,
  `bd_bid` int NOT NULL,
  `bd_order` int DEFAULT '0',
  `bd_type` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `bd_state` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `bd_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `bd_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `bd_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`bdid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_fields` (
  `fid` int NOT NULL AUTO_INCREMENT,
  `f_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `f_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `f_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `f_type` int DEFAULT '0',
  `f_count` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `f_hidden` int DEFAULT '0',
  `f_hidden_list` int DEFAULT '0',
  `f_hidden_detail` int DEFAULT '0',
  `f_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `f_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `f_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_expansion_fields` VALUES('1', 'product', 'xck', '相册diy', '6', '2', '0', '0', '0', '2022-06-14 09:43:31', '2022-06-14 09:44:27', 'chinese'),('2', 'tutorial', 'free', '免费', '0', '2', '0', '0', '0', '2022-06-14 15:11:55', '2022-06-14 15:11:55', 'chinese');

CREATE TABLE `wdja_expansion_fields_data` (
  `fdid` int NOT NULL AUTO_INCREMENT,
  `fd_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fd_fid` int DEFAULT '0',
  `fd_oid` int DEFAULT '0',
  PRIMARY KEY (`fdid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_expansion_fields_data` VALUES('1', '', '1', '1'),('2', '', '1', '2'),('3', '免费', '2', '1'),('4', '收费', '2', '2');

CREATE TABLE `wdja_expansion_fields_gid` (
  `fgid` int NOT NULL AUTO_INCREMENT,
  `fg_fid` int DEFAULT '0',
  `fg_gid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `fg_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `fg_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `fg_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  PRIMARY KEY (`fgid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_expansion_fields_gid` VALUES('1', '2', '1', '1', '2022-06-21 14:44:16', '2022-06-21 14:44:16'),('2', '1', '1', '', '2022-06-23 14:08:29', '2022-06-23 14:08:29');

CREATE TABLE `wdja_expansion_iplock` (
  `ipid` int NOT NULL AUTO_INCREMENT,
  `ip_area` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ip_robots` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ip_ip` varchar(152) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ip_come` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ip_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `ip_lock` int DEFAULT '0',
  `ip_out` int DEFAULT '0',
  `ip_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `ip_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `ip_count` int DEFAULT '0',
  `ip_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ipid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_expansion_iplock` VALUES('1', '本机地址本机地址', 'admin', '127.0.0.1', 'http://wdja.hosts.run/admin/', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36 Edg/96.0.1054.29', '0', '1', '2021-11-23 17:22:52', '2022-07-05 16:26:35', '1024', 'chinese');

CREATE TABLE `wdja_expansion_label` (
  `elid` int NOT NULL AUTO_INCREMENT,
  `el_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `el_type` int NOT NULL DEFAULT '0',
  `el_images_tpl` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `el_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `el_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `el_inputs_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'text',
  `el_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `el_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `el_hidden` int DEFAULT '0',
  `el_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`elid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_expansion_label` VALUES('1', '3', '0', '', '3866', '', 'text', '2022-06-14 09:44:01', '2022-06-14 09:48:06', '0', 'chinese');

CREATE TABLE `wdja_expansion_timer` (
  `etid` int NOT NULL AUTO_INCREMENT,
  `et_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `et_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '模块',
  `et_gid` int NOT NULL DEFAULT '0' COMMENT '内容ID',
  `et_event` int DEFAULT '0' COMMENT '定时事件:发布,删除,上下架',
  `et_timer_switch` int DEFAULT '0' COMMENT '定时开关',
  `et_timer` datetime NOT NULL DEFAULT '2021-08-01 08:00:00' COMMENT '任务启动时间',
  `et_state` int DEFAULT '0' COMMENT '任务状态:中止,暂停,进行中,结束',
  `et_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `et_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `et_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`etid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_expansion_timer` VALUES('1', '', 'news', '2', '0', '0', '2022-06-14 10:45:00', '2', '2022-06-14 10:43:02', '2022-06-23 11:38:50', 'chinese');

CREATE TABLE `wdja_expansion_vuser` (
  `evid` int NOT NULL AUTO_INCREMENT,
  `ev_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ev_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `ev_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `ev_count` int DEFAULT '0',
  `ev_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`evid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_expansion_vuser` VALUES('1', '542gftt', '2022-06-14 09:49:21', '2022-06-14 09:49:21', '0', 'chinese');

CREATE TABLE `wdja_forum_blacklist` (
  `bid` int NOT NULL AUTO_INCREMENT,
  `b_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `b_sid` int DEFAULT '0',
  `b_admin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `b_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `b_remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_forum_sort` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `s_sort` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_fid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_fsid` int DEFAULT '0',
  `s_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_order` int DEFAULT '0',
  `s_type` int DEFAULT '0',
  `s_mode` int DEFAULT '0',
  `s_ispop` int DEFAULT '0',
  `s_popedom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_admin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_rule` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_explain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_attestation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_hidden` int DEFAULT '0',
  `s_ntopic` int DEFAULT '0',
  `s_nnote` int DEFAULT '0',
  `s_today_ntopic` int DEFAULT '0',
  `s_today_date` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `s_last_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_last_tid` int DEFAULT '0',
  `s_last_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_forum_sort` VALUES('1', '资讯区', '0', '0', 'chinese', '0', '0', '0', '0', '', '', '', '', '', '', '0', '0', '0', '0', '2021-08-01 08:00:00', '', '0', '2021-08-01 08:00:00'),('2', '本站', '1', '1', 'chinese', '0', '0', '0', '0', '', 'common/upload/noimg.gif', '', '', '', '', '0', '0', '0', '0', '2021-08-01 08:00:00', '', '0', '2021-08-01 08:00:00'),('3', '行业', '1', '1', 'chinese', '1', '0', '0', '0', '', 'common/upload/noimg.gif', '', '', '', '', '0', '1', '3', '1', '2022-06-20 00:00:00', '[灌水]春天的小鸟', '1', '2022-06-20 15:22:36');

CREATE TABLE `wdja_forum_topic` (
  `tid` int NOT NULL AUTO_INCREMENT,
  `t_sid` int DEFAULT '0',
  `t_fid` int DEFAULT '0',
  `t_icon` int DEFAULT '0',
  `t_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_b` int DEFAULT '0',
  `t_author` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_authorip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `t_edit_info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_content_files_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `t_voteid` int DEFAULT '0',
  `t_ubb` int DEFAULT '0',
  `t_reply` int DEFAULT '0',
  `t_count` int DEFAULT '0',
  `t_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `t_htop` int DEFAULT '0',
  `t_top` int DEFAULT '0',
  `t_lock` int DEFAULT '0',
  `t_elite` int DEFAULT '0',
  `t_hidden` int DEFAULT '0',
  `t_lasttime` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `t_lastuser` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_forum_topic` VALUES('1', '3', '0', '0', '[灌水]春天的小鸟', '', '0', 'admin', '127.0.0.1', '<p>春天的小鸟</p>
<p>&nbsp;</p>
<p>春天的小鸟</p>', '帖子被 admin 于 2022-06-20 15:19:50 编辑过', '', '0', '0', '2', '5', '2022-06-20 15:19:23', '0', '0', '0', '0', '0', '2022-06-20 15:22:35', 'admin', ''),('2', '3', '1', '0', '', '', '0', 'admin', '127.0.0.1', '<p>夏天</p>', '', '', '0', '0', '0', '0', '2022-06-20 15:22:18', '0', '0', '0', '0', '0', '2022-06-20 15:22:18', '', ''),('3', '3', '1', '0', '无标题', '', '0', 'admin', '127.0.0.1', '<p>1234</p>', '', '', '0', '0', '0', '0', '2022-06-20 15:22:36', '0', '0', '0', '0', '0', '2022-06-20 15:22:36', '', '');

CREATE TABLE `wdja_forum_vote` (
  `vid` int NOT NULL AUTO_INCREMENT,
  `v_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `v_type` int DEFAULT '0',
  `v_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `v_day` int DEFAULT '0',
  PRIMARY KEY (`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_forum_vote_data` (
  `vdid` int NOT NULL AUTO_INCREMENT,
  `vd_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `vd_fid` int DEFAULT '0',
  `vd_vid` int DEFAULT '0',
  `vd_count` int DEFAULT '0',
  PRIMARY KEY (`vdid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_forum_vote_voter` (
  `vuid` int NOT NULL AUTO_INCREMENT,
  `vu_fid` int DEFAULT '0',
  `vu_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `vu_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `vu_data` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `vu_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  PRIMARY KEY (`vuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_message` (
  `mid` int NOT NULL AUTO_INCREMENT,
  `m_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `m_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `m_sex` int DEFAULT '0',
  `m_mobile` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `m_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `m_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `m_title` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `m_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `m_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `m_reply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `m_replytime` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `m_hidden` int DEFAULT '0',
  `m_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `m_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_message` VALUES('2', '3344', '127.0.0.1', '0', '', '', '', '', '', '2022-06-14 11:29:39', '', '2021-08-01 08:00:00', '0', 'admin', 'chinese'),('3', '找', '127.0.0.1', '0', '13145555555', '324445@ee.com', '', '', '334', '2022-06-14 11:31:24', '4rwww', '2021-08-01 08:00:00', '0', '6cf70717954daca5e61dff98bfc54e0b', 'chinese');

CREATE TABLE `wdja_news` (
  `nid` int NOT NULL AUTO_INCREMENT,
  `n_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `n_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `n_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `n_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `n_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `n_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `n_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `n_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `n_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `n_cls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `n_class` int DEFAULT '0',
  `n_class_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `n_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `n_vuser` int DEFAULT '0',
  `n_vuid` int DEFAULT '0',
  `n_hidden` int DEFAULT '0',
  `n_good` int DEFAULT '0',
  `n_count` int DEFAULT '0',
  `n_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_news` VALUES('1', '海曼科技邀您参加CEIS2021中国应急安全(消防)产业峰会！', '', '', '', 'common/upload/noimg.gif', '<p class="layoutFV" style="text-align: justify; text-indent: 2em; line-height: 1.5;">&ldquo;汇聚产业 智联未来&rdquo; CEIS2021中国应急安全(消防)产业峰会暨颁奖盛典将于12月16日在深圳龙岗盛大开幕。本届峰会主办单位、各地消防协会代表、行业权威专家及优秀企业代表将围绕&ldquo;树立高质量发展理念,增强消防产业的竞争力&rdquo;议题，以&ldquo;1+5&rdquo;的论坛形式，进行面对面探讨交流。</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">作为峰会亮点之一，2021智慧消防调研报告将重磅首发，该报告汇集了大量基础调研数据，涵盖智慧消防政策背景、应用现状、行业发展及生态建设等内容，为各消防企业发展提供决策参考。同时,&ldquo;2021年度中国消防行业十大品牌&rdquo;榜单将隆重揭晓，表彰一批为消防行业发展做出突出成绩、卓越贡献的优秀企业。</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">海曼科技总经理刘亚、副总经理杨志将应邀出席峰会，并以《数字赋能 创新监管 共建智慧消防生态新格局》为主题,就智慧消防发展现状、问题痛点、相关政策、海曼科技智慧消防解决方案、产品优势等内容进行分享，同各方共探消防产业发展新风向。</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">本次峰会，海曼科技将集中展示自主研发的燃气报警器、烟雾报警器、感温火灾报警器、一氧化碳报警器、水浸报警器等消防产品，以及NB-IoT智慧消防整体解决方案，欢迎各业界同仁莅临参观！12月16日,海曼科技与您携手，竭诚合作，共赢新未来！</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">&nbsp;</p>
<p style="text-indent: 2em; line-height: 1.5;"><img style="display: block; margin-left: auto; margin-right: auto;" title="/uploads/2021/12/241524568233.jpg" src="/news/common/upload/2022/06/23/51c5c3419fa5bc974a5e92ab657ce5320.jpg" alt="/uploads/2021/12/241524568233.jpg" width="600" height="300" border="0" /></p>', '', '2021-12-21 17:04:53', '2022-06-23 11:37:35', '|0|,|8|', '8', '8', '', '0', '0', '0', '0', '41', 'chinese'),('2', '海曼科技中标联通数科Cat.1烟感公开比选项目', '', '', '', 'common/upload/noimg.gif', '<p class="layoutFV" style="text-align: justify; text-indent: 2em; line-height: 1.5;">近日,海曼科技中标中国联通物联网有限责任公司联通数科物联网事业部雁飞Cat.1烟感公开比选项目。</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">&nbsp;</p>
<p style="text-indent: 2em; line-height: 1.5;"><img style="display: block; margin-left: auto; margin-right: auto;" title="/uploads/2022/05/161622082094.jpg" src="/news/common/upload/2022/06/23/e9d5a19650c561712d0fe99d1f526d410.jpg" alt="/uploads/2022/05/161622082094.jpg" /></p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">&nbsp;</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">在移动物联网领域,Cat.1继承LTE的特性,具有移动及漫游特性好,网络覆盖优等特点,能满足除电池供电外的绝大部分物联网场景,尤其是中低速率、较高移动性的应用场景需求。</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">此次海曼科技中标中国联通雁飞Cat.1烟感型号HM-618PH-4G ,是基于4G Cat.1通信技术,采用新一代探测迷宫,结合红蓝光探测技术,敏锐捕捉、识别烟雾及水雾,避免误报。同时具备本地自检、离墙防拆、低压提醒等功能。当电池低压时,自动推送通知,提醒用户及时更换电池。</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">&nbsp;</p>
<p style="text-indent: 2em; line-height: 1.5;"><img style="display: block; margin-left: auto; margin-right: auto;" title="/uploads/2022/05/161622084006.jpg" src="/news/common/upload/2022/06/23/e9d5a19650c561712d0fe99d1f526d411.jpg" alt="/uploads/2022/05/161622084006.jpg" width="600" height="679" /></p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">&nbsp;</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">危险发生时,探测系统将自动发出高分贝报警声,同时通过4G Cat.1通讯,将时间、位置等信息以APP推送、电话、短信、微信小程序、微信推送等形式发送至社区监控中心、网格巡查人员、业主。接到报警信息后,相关人员前往报警地点确认警情,从源头杜绝危险事故的发生。</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">&nbsp;</p>
<p style="text-indent: 2em; line-height: 1.5;"><img style="display: block; margin-left: auto; margin-right: auto;" title="/uploads/2022/05/161622085403.jpg" src="/news/common/upload/2022/06/23/e9d5a19650c561712d0fe99d1f526d412.jpg" alt="/uploads/2022/05/161622085403.jpg" width="600" height="520" /></p>
<p style="text-indent: 2em; line-height: 1.5;"><img style="display: block; margin-left: auto; margin-right: auto;" title="/uploads/2022/05/161622083482.jpg" src="/news/common/upload/2022/06/23/e9d5a19650c561712d0fe99d1f526d413.jpg" alt="/uploads/2022/05/161622083482.jpg" width="600" height="598" /></p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">&nbsp;</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">作为国内<a href="http://www.heiman.com.cn/" target="_blank" rel="noopener">智慧消防</a>行业领军企业,海曼科技在该领域成绩斐然,此次中标再次展现了联通数科对公司实力的高度认可。与此同时,海曼科技将持续创新研发,促进<a href="http://www.heiman.com.cn/" target="_blank" rel="noopener">智慧消防</a>事业发展,旨在为&ldquo;让人们的生活更安全&rdquo;做出更大的努力与贡献。</p>', '', '2022-06-14 11:14:49', '2022-06-23 11:38:49', '|0|,|1|', '1', '1', '', '0', '0', '0', '0', '41', 'chinese'),('3', '齐心抗疫 | 海曼科技解决方案助力方舱医院建设隔离管控', '', '', '', 'common/upload/noimg.gif', '<p class="layoutFV" style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">近期,我国多地多点发生本土聚集性疫情,主要为奥密克戎变异株,传播快、隐匿性强。截至目前,全国累计报告本土感染者505815例,波及31省(自治区、直辖市)含港澳台,整体呈现点多、面广、频发的特点。</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">国家卫健委:3月22日,国务院联防联控机制综合组要求,各省现在要根据疫情形势建设或者拿出建设方案, 保证每个省份能够至少有2至3家方舱医院,即便现在没有建设,也要拿出建设方案,确保在需要启用方舱医院时,能够在两天之内建成并且投入使用。</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">&nbsp;</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;"><img style="box-sizing: border-box; border-width: 0px; max-width: 90%; margin: 0px auto; display: block;" title="/uploads/2022/05/071129424978.png" src="/news/common/upload/2022/06/23/6b2a4c8d757858f0f3b10ade5ad1f9c40.png" alt="/uploads/2022/05/071129424978.png" width="650" height="432" /></p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">图片来源于网络</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">&nbsp;</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">国务院:4月5日,中共中央政治局委员、国务院副总理孙春兰在上海调研指导疫情防控工作,孙春兰强调,要深入贯彻习总书记重要指示精神,按照党中央、国务院决策部署,以更坚决的态度、更彻底的措施、更迅速的行动,加快建设方舱医院,加快拓展集中隔离用房,有力推进应检尽检、应收尽收、应隔尽隔、应治尽治,坚决打赢这场疫情防控攻坚战。</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">&nbsp;</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;"><img style="box-sizing: border-box; border-width: 0px; max-width: 90%; margin: 0px auto; height: auto; display: block;" title="/uploads/2022/05/071129423163.png" src="/news/common/upload/2022/06/23/6b2a4c8d757858f0f3b10ade5ad1f9c41.png" alt="/uploads/2022/05/071129423163.png" /></p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">图片来源于网络</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">&nbsp;</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">基于以上政策,海曼科技推出方舱医院隔离解决方案,综合<a style="box-sizing: border-box; margin: 0px; padding: 0px; color: #535353; text-decoration-line: none;" href="http://www.heiman.com.cn/product/smart_nbiot.html" target="_blank" rel="noopener">NB-IoT</a>、云平台等技术,实现多维探测、多方报警、准确定位、统一管理等监控功能,为场所管控、消防预警、紧急求助赋能,促进方舱医院隔离监管系统升级。</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">&nbsp;</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;"><img style="box-sizing: border-box; border-width: 0px; max-width: 90%; margin: 0px auto; height: 386px; width: 706px; display: block;" title="/uploads/2022/05/071129432196.png" src="/news/common/upload/2022/06/23/6b2a4c8d757858f0f3b10ade5ad1f9c42.png" alt="/uploads/2022/05/071129432196.png" width="706" height="386" /></p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">&nbsp;</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">该方案运用<a style="box-sizing: border-box; margin: 0px; padding: 0px; color: #535353; text-decoration-line: none;" href="http://www.heiman.com.cn/product/smart_nbiot.html" target="_blank" rel="noopener">NB-IoT</a>物联网、大数据、云计算等技术,有效整合各方力量,可第一时间发现隐患并通过无线网络精准定位、多方预警,达到早发现、早报警、早施救的效果。同时,支持在PC后台对设备进行统一管理、历史记录查可询、数据分析等操作。</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">&nbsp;</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">1.&nbsp;方案构成</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">①<a style="box-sizing: border-box; margin: 0px; padding: 0px; color: #535353; text-decoration-line: none;" href="http://www.heiman.com.cn/product/smart_noiot_door_sensor.html" target="_blank" rel="noopener">NB-IoT门磁报警器</a>:在需强制关闭的门窗、临时关闭却不能封锁的楼梯门、窗户等安装门磁报警器,通过APP监测,能及时接收门窗异常打开的信息。</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">②<a style="box-sizing: border-box; margin: 0px; padding: 0px; color: #535353; text-decoration-line: none;" href="http://www.heiman.com.cn/product/nbiot_smoke_sensor.html" target="_blank" rel="noopener">NB-IoT烟雾报警器</a>:可精准感应场所内着火时产生的烟雾,具备高温报警、离散上报、红外探测、远程自检等功能。</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">③<a style="box-sizing: border-box; margin: 0px; padding: 0px; color: #535353; text-decoration-line: none;" href="http://www.heiman.com.cn/product/348.html" target="_blank" rel="noopener">NB-IoT紧急按钮</a>:有无症状感染患者或密接者需要集中隔离观察,当患者感觉身体不适或其他紧急情况时,可拉动紧急绳或按下紧急按钮,并将通过<a style="box-sizing: border-box; margin: 0px; padding: 0px; color: #535353; text-decoration-line: none;" href="http://www.heiman.com.cn/product/smart_nbiot.html" target="_blank" rel="noopener">NB-IoT</a>物联网将信号传输到方舱服务平台,由工作人员及时处理。</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">2.&nbsp;方案特点</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">①安装方便、操作简单</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">方案产品采用了无工具安装设计,通过双面胶固定即可,用户可以自行确定安装位置,安装过程中不需要借助螺丝刀等工具,也不需要进行布线等操作,安装难度为零,固定后,在手机APP、微信小程序或者海曼云平台上输入地址等相关信息即可使用,操作非常简单。</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">②无需电源,超低功耗,续航能力强</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;"><a style="box-sizing: border-box; margin: 0px; padding: 0px; color: #535353; text-decoration-line: none;" href="http://www.heiman.com.cn/product/smart_nbiot.html" target="_blank" rel="noopener">NB-IoT</a>协议具有天然的超低功耗优势,待机时间长,有着极强的续航能力,同时配置了可拆卸电池设计,不需要考虑充电问题,待机时间长达一年以上,当电池电量使用完后,直接更换即可。</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">③运营商主推,无需布网</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">方案产品基于NB-IoT技术打造,构建于蜂窝网络,可直接部署于GSM网络、UMTS网络或LTE网络,可以直接通过电信移动基站联网,实时传输报警信息,摆脱了对WiFi的依赖,同时也摆脱了对智能网关的依赖,独立使用,大大的降低了部署成本,实现平滑升级。</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">④设备、数据管理更加直观便捷</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">对方案产品进行绑定管理时,可以直接使用微信小程序来完成,无需下载手机APP,占用手机内存,可清晰的展示产品的详细信息。</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 2em; line-height: 1.5;">3.&nbsp;多场景应用</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">&nbsp;</p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;"><img style="box-sizing: border-box; border-width: 0px; max-width: 90%; margin: 0px auto; height: 826px; width: 631px; display: block;" title="/uploads/2022/05/071129449722.gif" src="/news/common/upload/2022/06/23/6b2a4c8d757858f0f3b10ade5ad1f9c43.gif" alt="/uploads/2022/05/071129449722.gif" width="631" height="826" /></p>
<p style="box-sizing: border-box; color: #424242; font-family: webfont, \'SF Pro Display\', \'SF Pro Icons\', \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 17px; background-color: #ffffff; text-align: justify; text-indent: 32px; line-height: 1.5;">&nbsp;</p>
<p style="text-align: justify; text-indent: 2em; line-height: 1.5;">海曼科技方舱医院隔离监管解决方案的推出,能为疫情隔离场所管控、消防预警、紧急求助提供多重保障,助力疫情防控,为人民的生命健康保驾护航!</p>', '', '2022-06-23 11:41:49', '2022-06-23 11:41:49', '|0|,|1|', '1', '1', '', '0', '0', '0', '0', '3', 'chinese');

CREATE TABLE `wdja_pages` (
  `pid` int NOT NULL AUTO_INCREMENT,
  `p_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_fid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `p_fsid` int DEFAULT '0',
  `p_type` int DEFAULT '0',
  `p_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `p_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `p_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `p_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `p_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_hidden` int DEFAULT '0',
  `p_good` int DEFAULT '0',
  `p_count` int DEFAULT '0',
  `p_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_pages` VALUES('1', '市场1', '', '', '', '0', '0', '0', 'common/upload/noimg.gif', '', '', '2022-06-14 11:50:17', '2022-06-14 11:50:17', '', '0', '0', '0', 'chinese'),('2', '摊位1', '', '', '', '1', '1', '1', 'common/upload/noimg.gif', '<p>454555</p>', '', '2022-06-14 11:50:38', '2022-06-14 12:06:53', '', '0', '0', '23', 'chinese');

CREATE TABLE `wdja_product` (
  `pid` int NOT NULL AUTO_INCREMENT,
  `p_snum` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_gallery` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `p_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `p_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `p_infos` varchar(1200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '{:::}{|||}{:::}',
  `p_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `p_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `p_cls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `p_class` int DEFAULT '0',
  `p_class_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `p_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `p_hidden` int DEFAULT '0',
  `p_good` int DEFAULT '0',
  `p_count` int DEFAULT '0',
  `p_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_product` VALUES('1', '22061603313635094', '智能紧急按钮 海曼HS8EB-86', '', '', '', 'common/upload/2022/06/23/2022062311484464.jpg', 'common/upload/2022/06/23/20220623115927vW.jpg#:#common/upload/2022/06/23/202206231159389L.jpg#:#', '<p style="text-align: center;"><img src="/product/common/upload/2022/06/23/dd0d14f3b23199e32388fc3d8a1e7d850.jpg" alt="" /><img src="/product/common/upload/2022/06/23/dd0d14f3b23199e32388fc3d8a1e7d851.jpg" alt="" /><img src="/product/common/upload/2022/06/23/dd0d14f3b23199e32388fc3d8a1e7d852.jpg" alt="" /><img src="/product/common/upload/2022/06/23/dd0d14f3b23199e32388fc3d8a1e7d853.jpg" alt="" /></p>', '', '{"0":["1","标题","智能紧急按钮"],"1":["2","品牌","海曼"],"2":["3","型号","HS8EB-86"]}', '2022-06-16 17:05:58', '2022-06-23 14:08:29', '|0|,|3|', '3', '3', '', '0', '0', '22', 'chinese');

CREATE TABLE `wdja_question` (
  `qid` int NOT NULL AUTO_INCREMENT,
  `q_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `q_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `q_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `q_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `q_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `q_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `q_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `q_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `q_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `q_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `q_hidden` int DEFAULT '0',
  `q_good` int DEFAULT '0',
  `q_count` int DEFAULT '0',
  `q_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_question` VALUES('1', '3dee', '', '', '', 'common/upload/noimg.gif', '', '', '2022-06-17 16:40:52', '2022-06-17 16:41:00', '', '0', '0', '2', 'chinese');

CREATE TABLE `wdja_search` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `s_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_ip` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_content` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_infos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `s_hidden` int NOT NULL DEFAULT '0',
  `s_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `s_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `s_count` int DEFAULT '0',
  `s_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_search` VALUES('1', '2', '127.0.0.1', '2', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 Edg/96.0.1054.62', '0', '2021-12-21 17:19:35', '2021-12-21 17:19:35', '1', 'chinese'),('2', '内容', '127.0.0.1', '内容', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 Edg/96.0.1054.62', '0', '2021-12-21 17:19:46', '2021-12-21 17:19:46', '1', 'chinese'),('3', 'ss', '127.0.0.1', 'ss', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 Edg/96.0.1054.62', '0', '2022-01-08 14:08:08', '2022-01-08 14:08:08', '1', 'chinese'),('4', '内容', '127.0.0.1', '内容', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 Edg/96.0.1054.62', '0', '2022-01-08 14:08:15', '2022-01-08 14:08:15', '2', 'chinese'),('5', '内容', '127.0.0.1', '内容', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 Edg/96.0.1054.62', '0', '2022-01-08 14:08:39', '2022-01-08 14:08:39', '3', 'chinese'),('6', '内容', '127.0.0.1', '内容', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.110 Safari/537.36 Edg/96.0.1054.62', '0', '2022-01-08 14:10:16', '2022-01-08 14:10:16', '4', 'chinese'),('7', '工', '127.0.0.1', '工', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.63 Safari/537.36 Edg/102.0.1245.39', '1', '2022-06-14 16:01:50', '2022-06-14 16:01:50', '1', 'chinese');

CREATE TABLE `wdja_shop` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `s_snum` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_price` float DEFAULT '0',
  `s_wprice` float DEFAULT '0',
  `s_limitnum` int DEFAULT '0',
  `s_unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `s_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `s_infos` varchar(1200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '{:::}{|||}{:::}',
  `s_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `s_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `s_cls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `s_class` int DEFAULT '0',
  `s_class_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `s_hidden` int DEFAULT '0',
  `s_good` int DEFAULT '0',
  `s_count` int DEFAULT '0',
  `s_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_shop` VALUES('1', '22010630258786084', '测试商品', '', '', '', 'common/upload/noimg.gif', '100', '100', '86', '个', '<p>测试商品内容</p>', '', '{}', '', '2022-01-06 09:37:42', '2022-07-05 15:48:52', '|0|,|2|', '2', '2', '0', '0', '26', 'chinese'),('2', '22061748535202061', '商品一', '', '', '', 'common/upload/noimg.gif', '0', '0', '0', '个', '', '', '{"0":["1","品牌","深明"],"1":["2","型号","SM－332"],"2":["3","免费","是"]}', '', '2022-06-17 16:34:55', '2022-06-20 10:52:56', '|0|,|4|', '4', '4', '0', '0', '3', 'chinese');

CREATE TABLE `wdja_shop_price` (
  `spid` int NOT NULL AUTO_INCREMENT,
  `sp_shop_id` int DEFAULT NULL,
  `sp_group_price` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sp_group_id` int DEFAULT NULL,
  `sp_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sp_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `sp_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  PRIMARY KEY (`spid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_shop_price` VALUES('1', '1', '100', '0', 'chinese', '2022-01-06 09:37:42', '2022-07-05 15:48:52'),('2', '1', '99', '1', 'chinese', '2022-01-06 09:37:42', '2022-07-05 15:48:52'),('3', '1', '98', '2', 'chinese', '2022-01-06 09:37:42', '2022-07-05 15:48:52'),('4', '2', '0', '0', 'chinese', '2022-06-17 16:34:55', '2022-06-20 10:52:56'),('5', '2', '0', '1', 'chinese', '2022-06-17 16:34:56', '2022-06-20 10:52:57'),('6', '2', '0', '2', 'chinese', '2022-06-17 16:34:56', '2022-06-20 10:52:57');

CREATE TABLE `wdja_support_collect` (
  `cid` int NOT NULL AUTO_INCREMENT,
  `c_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_replace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `c_hidden` int DEFAULT '0',
  `c_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `c_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `c_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_support_dict` (
  `did` int NOT NULL AUTO_INCREMENT,
  `d_pid` int NOT NULL DEFAULT '0',
  `d_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `d_alt` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `d_fid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `d_fsid` int DEFAULT '0',
  `d_lid` int DEFAULT '0',
  `d_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `d_hidden` int DEFAULT '0',
  `d_order` int DEFAULT '0',
  `d_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `d_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `d_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`did`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_support_dict` VALUES('1', '0', '广东', '', '0', '0', '0', '0', '0', '0', '2022-06-14 09:56:17', '2021-08-01 08:00:00', 'chinese'),('2', '0', '深圳', '', '1', '1', '0', '0', '0', '0', '2022-06-14 09:56:25', '2021-08-01 08:00:00', 'chinese'),('3', '0', '龙华', '', '1,2', '2', '0', '0', '0', '0', '2022-06-14 09:56:31', '2021-08-01 08:00:00', 'chinese'),('4', '0', '大浪', '', '1,2', '2', '0', '0', '0', '0', '2022-06-14 09:56:37', '2021-08-01 08:00:00', 'chinese');

CREATE TABLE `wdja_support_linktext` (
  `lid` int NOT NULL AUTO_INCREMENT,
  `l_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `l_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `l_keyword` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `l_intro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `l_hidden` int DEFAULT '0',
  `l_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `l_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `l_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_support_menu` (
  `mid` int NOT NULL AUTO_INCREMENT,
  `m_pid` int NOT NULL DEFAULT '0',
  `m_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `m_title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `m_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `m_alt` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `m_fid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `m_fsid` int DEFAULT '0',
  `m_lid` int DEFAULT '0',
  `m_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `m_hidden` int DEFAULT '0',
  `m_gourl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `m_order` int DEFAULT '0',
  `m_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `m_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `m_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_support_menu` VALUES('1', '0', '首页', '', 'common/upload/noimg.gif', '', '0', '0', '0', '0', '0', '/', '0', '2022-06-14 14:17:29', '2021-08-01 08:00:00', 'chinese'),('2', '0', '新闻', '', 'common/upload/noimg.gif', '', '0', '0', '0', '0', '0', '/news', '0', '2022-06-14 14:17:42', '2021-08-01 08:00:00', 'chinese'),('3', '0', '产品', '', 'common/upload/noimg.gif', '', '0', '0', '0', '0', '0', '/product', '0', '2022-06-14 14:18:01', '2021-08-01 08:00:00', 'chinese'),('4', '0', '教程', '', 'common/upload/noimg.gif', '', '0', '0', '0', '0', '0', '/tutorial', '0', '2022-06-14 14:37:55', '2021-08-01 08:00:00', 'chinese'),('5', '0', '商城', '', 'common/upload/noimg.gif', '', '0', '0', '0', '0', '0', '/shop', '0', '2022-06-14 14:38:11', '2021-08-01 08:00:00', 'chinese'),('6', '0', '下载', '', 'common/upload/noimg.gif', '', '0', '0', '0', '0', '0', '/download', '0', '2022-06-14 14:38:30', '2021-08-01 08:00:00', 'chinese');

CREATE TABLE `wdja_support_slide` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `s_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_intro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `s_order` int NOT NULL DEFAULT '0',
  `s_hidden` int DEFAULT '0',
  `s_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `s_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `s_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_support_sort` (
  `sortid` int NOT NULL AUTO_INCREMENT,
  `sort_pid` int NOT NULL DEFAULT '0',
  `sort_sort` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sort_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sort_keywords` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sort_description` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sort_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sort_fid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sort_fsid` int DEFAULT '0',
  `sort_lid` int DEFAULT '0',
  `sort_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sort_hidden` int DEFAULT '0',
  `sort_gourl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sort_tpl_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sort_tpl_detail` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `sort_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `sort_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `sort_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`sortid`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_support_sort` VALUES('1', '0', '公司动态', '', '', '', 'common/upload/noimg.gif', '0', '0', '0', 'news', '0', '', '', '', '0', '2022-06-23 11:35:25', '2022-06-23 11:35:25', 'chinese'),('2', '0', '测试购物', '', '', '', 'common/upload/noimg.gif', '0', '0', '0', 'shop', '0', '', '', '', '0', '2022-01-06 09:37:01', '2022-01-06 09:37:01', 'chinese'),('3', '0', '智能家居', '', '', '', 'common/upload/noimg.gif', '0', '0', '0', 'product', '0', '', '', '', '0', '2022-06-23 11:42:46', '2022-06-23 11:42:46', 'chinese'),('4', '0', '商城分类', '', '', '', 'common/upload/noimg.gif', '0', '0', '0', 'shop', '0', '', '', '', '1', '2022-06-14 15:06:44', '2022-06-14 15:06:44', 'chinese'),('5', '0', '教程分类', '', '', '', 'common/upload/noimg.gif', '0', '0', '0', 'tutorial', '0', '', '', '', '0', '2022-06-14 15:06:53', '2022-06-14 15:06:53', 'chinese'),('6', '0', '问答分类', '', '', '', 'common/upload/noimg.gif', '0', '0', '0', 'ask', '0', '', '', '', '0', '2022-06-14 15:07:04', '2022-06-14 15:07:04', 'chinese'),('7', '0', '下载分类', '', '', '', 'common/upload/noimg.gif', '0', '0', '0', 'download', '0', '', '', '', '0', '2022-06-14 15:07:14', '2022-06-14 15:07:14', 'chinese'),('8', '0', '展会动态', '', '', '', 'common/upload/noimg.gif', '0', '0', '0', 'news', '0', '', '', '', '1', '2022-06-23 11:35:41', '2022-06-23 11:35:41', 'chinese'),('9', '0', '智慧消防', '', '', '', 'common/upload/noimg.gif', '0', '0', '0', 'product', '0', '', '', '', '1', '2022-06-23 11:43:02', '2022-06-23 11:43:02', 'chinese'),('10', '0', '安防消防报警', '', '', '', 'common/upload/noimg.gif', '0', '0', '0', 'product', '0', '', '', '', '2', '2022-06-23 11:43:20', '2022-06-23 11:43:20', 'chinese');

CREATE TABLE `wdja_sys_note` (
  `nid` int NOT NULL AUTO_INCREMENT,
  `n_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `n_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `n_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `n_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `n_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `n_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `n_finish` int DEFAULT '0',
  `n_count` int DEFAULT '0',
  `n_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'chinese',
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_sys_note` VALUES('1', '会员价格设置添加', 'common/upload/noimg.gif', '', '', '2022-06-14 10:34:53', '2022-06-14 10:34:53', '1', '0', 'chinese'),('2', '33', 'common/upload/noimg.gif', '<p>33<img title="33" src="common/upload/2022/06/14/202206141037058m.png" alt="32sew2" border="0" /></p>', '33#:#32sew2#:#common/upload/2022/06/14/202206141037058m.png', '2022-06-23 11:32:01', '2022-06-23 11:32:01', '0', '0', 'chinese');

CREATE TABLE `wdja_sys_related` (
  `rid` int NOT NULL AUTO_INCREMENT,
  `r_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `r_gid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `r_source` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `r_title` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `r_sid` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `r_time` datetime DEFAULT '2021-08-01 08:00:00',
  `r_update` datetime DEFAULT '2021-08-01 08:00:00',
  `r_lng` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'chinese',
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_sys_related` VALUES('1', 'news', '1', 'product', '', '0', '2021-12-21 17:04:53', '2022-06-23 11:37:35', 'chinese'),('2', 'news', '1', 'shop', '', '0', '2021-12-21 17:04:53', '2022-06-23 11:37:35', 'chinese'),('3', 'news', '2', 'product', '关联产品', '0', '2022-06-14 10:43:02', '2022-06-23 11:38:50', 'chinese'),('4', 'news', '2', 'shop', '', '1', '2022-06-14 10:43:02', '2022-06-23 11:38:50', 'chinese'),('9', 'pages', '2', 'news', '', '2', '2022-06-14 11:59:24', '2022-06-14 12:06:53', 'chinese'),('10', 'pages', '2', 'product', '的', '0', '2022-06-14 11:59:24', '2022-06-14 12:06:53', 'chinese'),('11', 'product', '1', 'news', '', '2', '2022-06-16 17:05:58', '2022-06-23 14:08:29', 'chinese'),('12', 'product', '1', 'shop', '', '2', '2022-06-16 17:05:59', '2022-06-23 14:08:29', 'chinese'),('13', 'news', '3', 'product', '', '', '2022-06-23 11:41:49', '2022-06-23 11:41:49', 'chinese'),('14', 'news', '3', 'shop', '', '', '2022-06-23 11:41:50', '2022-06-23 11:41:50', 'chinese');

CREATE TABLE `wdja_sys_upload` (
  `upid` int NOT NULL AUTO_INCREMENT,
  `up_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `up_upident` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `up_filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `up_field` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `up_fid` int DEFAULT '0',
  `up_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `up_user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `up_valid` int DEFAULT '0',
  `up_voidreason` int DEFAULT '0',
  `up_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`upid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_sys_upload` VALUES('1', 'admin/note', '', '/admin/note/common/upload/2022/06/14/202206141037058m.png', 'img_url', '0', '2022-06-14 10:37:06', 'admin', '0', '0', 'chinese'),('2', 'admin/note', '', '/admin/note/common/upload/2022/06/15/20220615145527En.jpg', 'img_url', '0', '2022-06-15 14:55:27', 'admin', '0', '0', 'chinese'),('3', 'product', '', '/product/common/upload/2022/06/16/20220616170913LY.jpg', 'gallery', '0', '2022-06-16 17:09:13', 'admin', '0', '0', 'chinese'),('4', 'product', '', '/product/common/upload/2022/06/23/2022062311484464.jpg', 'image', '0', '2022-06-23 11:48:44', 'admin', '0', '0', 'chinese'),('5', 'product', '', '/product/common/upload/2022/06/23/20220623115927vW.jpg', 'gallery', '0', '2022-06-23 11:59:27', 'admin', '0', '0', 'chinese'),('6', 'product', '', '/product/common/upload/2022/06/23/202206231159389L.jpg', 'gallery', '0', '2022-06-23 11:59:38', 'admin', '0', '0', 'chinese');

CREATE TABLE `wdja_tags` (
  `tid` int NOT NULL AUTO_INCREMENT,
  `t_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_keywords` varchar(152) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `t_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `t_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `t_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `t_hidden` int DEFAULT '0',
  `t_good` int DEFAULT '0',
  `t_gourl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_count` int DEFAULT '0',
  `t_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_tags` VALUES('1', '强大', '', '', '', '', '', '', '2022-06-14 10:40:46', '2022-06-14 10:40:46', '0', '0', '', '19', 'chinese'),('2', 'CEIS', '', '', '', '', '', '', '2022-06-23 11:37:14', '2021-08-01 08:00:00', '0', '0', '', '0', 'chinese'),('3', '智慧消防', '', '', '', '', '', '', '2022-06-23 11:37:15', '2021-08-01 08:00:00', '0', '0', '', '2', 'chinese'),('4', 'Cat.1', '', '', '', '', '', '', '2022-06-23 11:38:49', '2021-08-01 08:00:00', '0', '0', '', '0', 'chinese'),('5', '紧急按钮', '', '', '', '', '', '', '2022-06-23 11:58:14', '2021-08-01 08:00:00', '0', '0', '', '2', 'chinese'),('6', '海曼', '', '', '', '', '', '', '2022-06-23 11:58:14', '2021-08-01 08:00:00', '0', '0', '', '2', 'chinese');

CREATE TABLE `wdja_tags_data` (
  `tdid` int NOT NULL AUTO_INCREMENT,
  `td_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `td_gid` int DEFAULT '0',
  `td_tid` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`tdid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_tags_data` VALUES('1', 'news', '2', '4'),('2', 'news', '1', '2,3'),('3', 'product', '1', '5,6');

CREATE TABLE `wdja_tutorial` (
  `tid` int NOT NULL AUTO_INCREMENT,
  `t_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `t_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `t_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `t_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `t_cls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `t_class` int DEFAULT '0',
  `t_class_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `t_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `t_utid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `t_vuser` int DEFAULT '0',
  `t_vuid` int DEFAULT '0',
  `t_hidden` int DEFAULT '0',
  `t_good` int DEFAULT '0',
  `t_count` int DEFAULT '0',
  `t_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_tutorial` VALUES('1', '教程一', '', '', '', 'common/upload/noimg.gif', '<p>公开教程，不限制访问。</p>', '', '2022-06-14 15:07:51', '2022-06-21 14:44:16', '|0|,|5|', '5', '5', '', '-99', '0', '0', '0', '0', '24', 'chinese');

CREATE TABLE `wdja_tutorial_chapter` (
  `tcid` int NOT NULL AUTO_INCREMENT,
  `tc_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tc_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tc_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tc_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tc_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tc_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `tc_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `tc_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `tc_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `tc_tutorial_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '0',
  `tc_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tc_vuser` int DEFAULT '0',
  `tc_vuid` int DEFAULT '0',
  `tc_hidden` int DEFAULT '0',
  `tc_good` int DEFAULT '0',
  `tc_count` int DEFAULT '0',
  `tc_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`tcid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_tutorial_chapter` VALUES('1', '篇章一', '', '', '', '/tutorial/chapter/common/upload/noimg.gif', '', '', '2022-06-14 15:16:23', '2022-06-14 15:16:23', '1', '', '0', '0', '0', '0', '0', 'chinese');

CREATE TABLE `wdja_tutorial_data` (
  `tdid` int NOT NULL AUTO_INCREMENT,
  `td_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `td_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `td_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `td_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `td_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `td_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `td_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `td_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `td_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `td_tutorial_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `td_ischapter` int DEFAULT '1',
  `td_chapter_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `td_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `td_vuser` int DEFAULT '0',
  `td_vuid` int DEFAULT '0',
  `td_hidden` int DEFAULT '0',
  `td_good` int DEFAULT '0',
  `td_tpl` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `td_gourl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `td_count` int DEFAULT '0',
  `td_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `td_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`tdid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_tutorial_data` VALUES('1', '教程一资料', '', '', '', 'common/upload/noimg.gif', '', '', '2022-06-14 15:41:35', '2022-06-14 15:41:35', '1', '0', '0', '', '0', '0', '0', '0', '', '', '6', 'chinese', '');

CREATE TABLE `wdja_tutorial_section` (
  `tsid` int NOT NULL AUTO_INCREMENT,
  `ts_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ts_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ts_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ts_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ts_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ts_video` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ts_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `ts_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `ts_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `ts_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `ts_tutorial_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `ts_ischapter` int DEFAULT '1',
  `ts_chapter_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0',
  `ts_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ts_vuser` int DEFAULT '0',
  `ts_vuid` int DEFAULT '0',
  `ts_hidden` int DEFAULT '0',
  `ts_good` int DEFAULT '0',
  `ts_tpl` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ts_gourl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ts_count` int DEFAULT '0',
  `ts_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`tsid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_tutorial_section` VALUES('1', '教程一说明', '', '', '', 'common/upload/noimg.gif', '', '', '', '2022-06-14 15:40:31', '2022-06-14 15:40:31', '1', '0', '0', '', '0', '0', '0', '0', '', '', '4', 'chinese');

CREATE TABLE `wdja_user` (
  `uid` int NOT NULL AUTO_INCREMENT,
  `u_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `u_password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `u_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `u_openid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_headimgurl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_sex` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_language` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `u_qq` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `u_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `u_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `u_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额字段,不能为负数',
  `u_emoney` int DEFAULT '0',
  `u_integral` int DEFAULT '0',
  `u_topic` int DEFAULT '0',
  `u_face` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `u_sign` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `u_forum_admin` int DEFAULT '0',
  `u_utype` int DEFAULT '0',
  `u_lock` int DEFAULT '0',
  `u_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `u_lasttime` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `u_pretime` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_user` VALUES('1', 'admin', '926b4f1d65e19d81680d8f2b7449e627', 's@22.com', '', '', '', '0', '', '', '', '', '', '0', '', '', '0.00', '0', '14', '3', '', '', '0', '0', '0', '2022-01-06 09:31:59', '2022-06-20 15:23:11', '2022-06-20 15:23:11');

CREATE TABLE `wdja_user_address` (
  `uaid` int NOT NULL AUTO_INCREMENT,
  `ua_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ua_dictid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ua_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ua_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ua_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ua_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ua_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ua_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ua_order` int DEFAULT '0',
  `ua_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `ua_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  PRIMARY KEY (`uaid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_user_address` VALUES('1', '李白4', '', '广东深圳大浪', '', '1314222222', '', 'admin', 'chinese', '0', '2022-06-14 09:57:26', '2022-06-20 15:01:03');

CREATE TABLE `wdja_user_recharge` (
  `urid` int NOT NULL AUTO_INCREMENT,
  `ur_orderid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ur_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ur_price` float DEFAULT '0',
  `ur_payment` int DEFAULT NULL,
  `ur_prepaid` int DEFAULT '0',
  `ur_trade_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ur_seller_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ur_timestamp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ur_state` int DEFAULT '0',
  `ur_lock` int DEFAULT '0',
  `ur_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `ur_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `ur_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`urid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_user_recharge` VALUES('1', '202206141501550', 'admin', '1', '1', '0', '', '', '', '0', '0', '2022-06-14 15:01:55', '2022-06-14 15:01:55', 'chinese');

CREATE TABLE `wdja_user_shopcart` (
  `scid` int NOT NULL AUTO_INCREMENT,
  `sc_fid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `sc_allprice` float unsigned DEFAULT '0',
  `sc_merchandiseprice` float DEFAULT '0',
  `sc_trafficprice` float DEFAULT '0',
  `sc_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sc_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sc_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sc_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sc_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sc_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `sc_payment` int DEFAULT '0',
  `sc_traffic` int DEFAULT '0',
  `sc_orderid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sc_prepaid` int DEFAULT '0',
  `sc_payid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sc_state` int DEFAULT '0',
  `sc_express` int DEFAULT '0',
  `sc_expressid` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '0 ',
  `sc_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `sc_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `sc_dtime` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `sc_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `sc_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`scid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_user_shopcart` VALUES('1', '1:3:100', '300', '300', '0', '33', '33', '33', '33', '33', '', '5', '0', '20220106940081', '0', '', '0', '0', '0 ', '2022-01-06 09:40:07', '2022-01-06 09:40:07', '2022-01-06 09:40:07', 'admin', 'chinese'),('2', '1:9:100', '900', '900', '0', '3', '3', '33', '3', '3', '', '5', '0', '20220106942342', '1', '', '1', '0', '0', '2022-01-06 09:42:34', '2022-06-14 09:51:45', '2022-06-14 09:51:45', 'admin', 'chinese'),('3', '1:2:100', '208', '200', '8', '李白', '广东深圳大浪', '', '1314222222', '', '', '5', '1', '202206141500093', '0', '', '0', '0', '0 ', '2022-06-14 15:00:09', '2022-06-14 15:00:09', '2022-06-14 15:00:09', 'admin', 'chinese');

CREATE TABLE `wdja_user_vip` (
  `uvid` int NOT NULL AUTO_INCREMENT,
  `uv_orderid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `uv_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `uv_utype` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `uv_outype` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `uv_nutype` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `uv_price` float DEFAULT '0',
  `uv_payment` int DEFAULT NULL,
  `uv_payid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `uv_state` int DEFAULT '0',
  `uv_time` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `uv_update` datetime NOT NULL DEFAULT '2021-08-01 08:00:00',
  `uv_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`uvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;