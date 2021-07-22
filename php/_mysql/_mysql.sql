CREATE TABLE `wdja_aboutus` (
  `abid` int NOT NULL AUTO_INCREMENT,
  `ab_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ab_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ab_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ab_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ab_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ab_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ab_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ab_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ab_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ab_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ab_hidden` int DEFAULT '0',
  `ab_good` int DEFAULT '0',
  `ab_tpl` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ab_gourl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ab_count` int DEFAULT '0',
  `ab_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`abid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_admin` (
  `aid` int NOT NULL AUTO_INCREMENT,
  `a_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `a_pword` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `a_popedom` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `a_lock` int DEFAULT '0',
  `a_lasttime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `a_lastip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `wdja_admin` VALUES('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '-1', '0', '2021-08-01 08:00:00', '127.0.0.1');

CREATE TABLE `wdja_admin_log` (
  `lid` int NOT NULL AUTO_INCREMENT,
  `l_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `l_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `l_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `l_islogin` int DEFAULT '0',
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_ask` (
  `aid` int NOT NULL AUTO_INCREMENT,
  `a_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `a_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `a_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `a_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `a_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `a_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `a_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `a_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `a_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `a_cls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `a_class` int DEFAULT '0',
  `a_class_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `a_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `a_hidden` int DEFAULT '0',
  `a_good` int DEFAULT '0',
  `a_count` int DEFAULT '0',
  `a_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_ask_answer` (
  `aid` int NOT NULL AUTO_INCREMENT,
  `a_author` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `a_authorip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `a_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `a_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `a_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `a_fid` int DEFAULT '0',
  `a_good` int DEFAULT '0',
  `a_hidden` int DEFAULT '0',
  `a_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_check` (
  `cid` int NOT NULL AUTO_INCREMENT,
  `c_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_gid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_sex` int DEFAULT '0',
  `c_mobile` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `c_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_title` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `c_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `c_reply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `c_replytime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `c_hidden` int DEFAULT '0',
  `c_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`cid`),
  UNIQUE KEY `cid` (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_download` (
  `did` int NOT NULL AUTO_INCREMENT,
  `d_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `d_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `d_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `d_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `d_cls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `d_class` int DEFAULT '0',
  `d_class_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `d_scont` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `d_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `d_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `d_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `d_size` float DEFAULT '0',
  `d_urls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `d_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `d_hidden` int DEFAULT '0',
  `d_good` int DEFAULT '0',
  `d_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `d_count` int DEFAULT '0',
  `d_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `d_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`did`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_baidupush` (
  `bid` int NOT NULL AUTO_INCREMENT,
  `b_genre` varchar(152) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `b_gid` int NOT NULL,
  `b_topic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `b_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `b_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `b_count` int DEFAULT '0',
  `b_type` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `b_state` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `b_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `b_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `b_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_baidupush_data` (
  `bdid` int NOT NULL AUTO_INCREMENT,
  `bd_bid` int NOT NULL,
  `bd_order` int DEFAULT '0',
  `bd_type` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `bd_state` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `bd_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `bd_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bd_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`bdid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_fields` (
  `fid` int NOT NULL AUTO_INCREMENT,
  `f_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `f_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `f_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `f_type` int DEFAULT '0',
  `f_count` varchar(9) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `f_hidden` int DEFAULT '0',
  `f_hidden_list` int DEFAULT '0',
  `f_hidden_detail` int DEFAULT '0',
  `f_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `f_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `f_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_fields_data` (
  `fdid` int NOT NULL AUTO_INCREMENT,
  `fd_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fd_fid` int DEFAULT '0',
  `fd_oid` int DEFAULT '0',
  PRIMARY KEY (`fdid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_fields_gid` (
  `fgid` int NOT NULL AUTO_INCREMENT,
  `fg_fid` int DEFAULT '0',
  `fg_gid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fg_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `fg_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fg_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`fgid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_iplock` (
  `ipid` int NOT NULL AUTO_INCREMENT,
  `ip_area` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ip_robots` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ip_ip` varchar(152) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ip_come` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ip_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ip_lock` int DEFAULT '0',
  `ip_out` int DEFAULT '0',
  `ip_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_count` int DEFAULT '0',
  `ip_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`ipid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_label` (
  `elid` int NOT NULL AUTO_INCREMENT,
  `el_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `el_type` int NOT NULL DEFAULT '0',
  `el_images_tpl` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `el_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `el_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `el_inputs_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'text',
  `el_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `el_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `el_hidden` int DEFAULT '0',
  `el_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`elid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_tags` (
  `etid` int NOT NULL AUTO_INCREMENT,
  `et_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `et_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `et_keywords` varchar(152) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `et_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `et_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `et_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `et_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `et_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `et_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `et_hidden` int DEFAULT '0',
  `et_good` int DEFAULT '0',
  `et_gourl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `et_count` int DEFAULT '0',
  `et_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`etid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_tags_data` (
  `tdid` int NOT NULL AUTO_INCREMENT,
  `td_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `td_gid` int DEFAULT '0',
  `td_tid` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`tdid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_timer` (
  `etid` int NOT NULL AUTO_INCREMENT,
  `et_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `et_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `et_gid` int NOT NULL DEFAULT '0',
  `et_event` int DEFAULT '0',
  `et_timer_switch` int DEFAULT '0',
  `et_timer` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `et_state` int DEFAULT '0' COMMENT,
  `et_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `et_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `et_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`etid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_expansion_vuser` (
  `evid` int NOT NULL AUTO_INCREMENT,
  `ev_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ev_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ev_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ev_count` int DEFAULT '0',
  `ev_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`evid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_forum_blacklist` (
  `bid` int NOT NULL AUTO_INCREMENT,
  `b_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `b_sid` int DEFAULT '0',
  `b_admin` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `b_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `b_remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_forum_sort` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `s_sort` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_fid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_fsid` int DEFAULT '0',
  `s_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_order` int DEFAULT '0',
  `s_type` int DEFAULT '0',
  `s_mode` int DEFAULT '0',
  `s_ispop` int DEFAULT '0',
  `s_popedom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_images` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_admin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_rule` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_explain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_attestation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_hidden` int DEFAULT '0',
  `s_ntopic` int DEFAULT '0',
  `s_nnote` int DEFAULT '0',
  `s_today_ntopic` int DEFAULT '0',
  `s_today_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `s_last_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_last_tid` int DEFAULT '0',
  `s_last_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_forum_topic` (
  `tid` int NOT NULL AUTO_INCREMENT,
  `t_sid` int DEFAULT '0',
  `t_fid` int DEFAULT '0',
  `t_icon` int DEFAULT '0',
  `t_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_color` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_b` int DEFAULT '0',
  `t_author` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_authorip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `t_edit_info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_content_files_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `t_voteid` int DEFAULT '0',
  `t_ubb` int DEFAULT '0',
  `t_reply` int DEFAULT '0',
  `t_count` int DEFAULT '0',
  `t_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `t_htop` int DEFAULT '0',
  `t_top` int DEFAULT '0',
  `t_lock` int DEFAULT '0',
  `t_elite` int DEFAULT '0',
  `t_hidden` int DEFAULT '0',
  `t_lasttime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `t_lastuser` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_forum_vote` (
  `vid` int NOT NULL AUTO_INCREMENT,
  `v_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `v_type` int DEFAULT '0',
  `v_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `v_day` int DEFAULT '0',
  PRIMARY KEY (`vid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_forum_vote_data` (
  `vdid` int NOT NULL AUTO_INCREMENT,
  `vd_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vd_fid` int DEFAULT '0',
  `vd_vid` int DEFAULT '0',
  `vd_count` int DEFAULT '0',
  PRIMARY KEY (`vdid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_forum_vote_voter` (
  `vuid` int NOT NULL AUTO_INCREMENT,
  `vu_fid` int DEFAULT '0',
  `vu_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vu_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vu_data` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `vu_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`vuid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_message` (
  `mid` int NOT NULL AUTO_INCREMENT,
  `m_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `m_ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `m_sex` int DEFAULT '0',
  `m_mobile` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `m_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `m_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `m_title` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `m_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `m_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `m_reply` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `m_replytime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `m_hidden` int DEFAULT '0',
  `m_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `m_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_news` (
  `nid` int NOT NULL AUTO_INCREMENT,
  `n_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `n_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `n_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `n_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `n_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `n_gallery` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `n_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `n_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `n_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `n_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `n_cls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `n_class` int DEFAULT '0',
  `n_class_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `n_shop_lists` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `n_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `n_vuser` int DEFAULT '0',
  `n_vuid` int DEFAULT '0',
  `n_hidden` int DEFAULT '0',
  `n_good` int DEFAULT '0',
  `n_count` int DEFAULT '0',
  `n_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_pages` (
  `pid` int NOT NULL AUTO_INCREMENT,
  `p_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_fid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `p_fsid` int DEFAULT '0',
  `p_type` int DEFAULT '0',
  `p_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `p_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `p_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `p_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `p_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_hidden` int DEFAULT '0',
  `p_good` int DEFAULT '0',
  `p_count` int DEFAULT '0',
  `p_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_product` (
  `pid` int NOT NULL AUTO_INCREMENT,
  `p_snum` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `p_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `p_infos` varchar(1200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '{:::}{|||}{:::}',
  `p_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `p_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `p_cls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `p_class` int DEFAULT '0',
  `p_class_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `p_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `p_hidden` int DEFAULT '0',
  `p_good` int DEFAULT '0',
  `p_count` int DEFAULT '0',
  `p_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_question` (
  `qid` int NOT NULL AUTO_INCREMENT,
  `q_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `q_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `q_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `q_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `q_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `q_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `q_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `q_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `q_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `q_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `q_hidden` int DEFAULT '0',
  `q_good` int DEFAULT '0',
  `q_count` int DEFAULT '0',
  `q_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_search` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `s_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_ip` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_content` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_infos` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `s_hidden` int NOT NULL DEFAULT '0',
  `s_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `s_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `s_count` int DEFAULT '0',
  `s_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_shop` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `s_snum` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_price` float DEFAULT '0',
  `s_wprice` float DEFAULT '0',
  `s_limitnum` int DEFAULT '0',
  `s_unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `s_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `s_infos` varchar(1200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '{:::}{|||}{:::}',
  `s_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `s_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `s_cls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `s_class` int DEFAULT '0',
  `s_class_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `s_hidden` int DEFAULT '0',
  `s_good` int DEFAULT '0',
  `s_count` int DEFAULT '0',
  `s_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_shop_price` (
  `spid` int NOT NULL AUTO_INCREMENT,
  `sp_shop_id` int DEFAULT NULL,
  `sp_group_price` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sp_group_id` int DEFAULT NULL,
  `sp_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sp_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sp_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`spid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_support_collect` (
  `cid` int NOT NULL AUTO_INCREMENT,
  `c_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_replace` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c_hidden` int DEFAULT '0',
  `c_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `c_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `c_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_support_dict` (
  `did` int NOT NULL AUTO_INCREMENT,
  `d_pid` int NOT NULL DEFAULT '0',
  `d_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `d_alt` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `d_fid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `d_fsid` int DEFAULT '0',
  `d_lid` int DEFAULT '0',
  `d_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `d_hidden` int DEFAULT '0',
  `d_order` int DEFAULT '0',
  `d_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `d_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `d_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`did`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_support_linktext` (
  `lid` int NOT NULL AUTO_INCREMENT,
  `l_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `l_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `l_keyword` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `l_intro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `l_hidden` int DEFAULT '0',
  `l_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `l_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `l_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_support_menu` (
  `mid` int NOT NULL AUTO_INCREMENT,
  `m_pid` int NOT NULL DEFAULT '0',
  `m_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `m_title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `m_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `m_alt` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `m_fid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `m_fsid` int DEFAULT '0',
  `m_lid` int DEFAULT '0',
  `m_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `m_hidden` int DEFAULT '0',
  `m_gourl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `m_order` int DEFAULT '0',
  `m_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `m_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `m_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_support_slide` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `s_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_intro` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s_order` int NOT NULL DEFAULT '0',
  `s_hidden` int DEFAULT '0',
  `s_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `s_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `s_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_support_sort` (
  `sortid` int NOT NULL AUTO_INCREMENT,
  `sort_pid` int NOT NULL DEFAULT '0',
  `sort_sort` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_keywords` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_description` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_fid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_fsid` int DEFAULT '0',
  `sort_lid` int DEFAULT '0',
  `sort_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_hidden` int DEFAULT '0',
  `sort_gourl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_tpl_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_tpl_detail` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `sort_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sort_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sort_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`sortid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_sys_note` (
  `nid` int NOT NULL AUTO_INCREMENT,
  `n_topic` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `n_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `n_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `n_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `n_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `n_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `n_hidden` int DEFAULT '0',
  `n_good` int DEFAULT '0',
  `n_count` int DEFAULT '0',
  `n_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'chinese',
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_sys_upload` (
  `upid` int NOT NULL AUTO_INCREMENT,
  `up_genre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `up_upident` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `up_filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `up_field` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `up_fid` int DEFAULT '0',
  `up_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `up_user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `up_valid` int DEFAULT '0',
  `up_voidreason` int DEFAULT '0',
  `up_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`upid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_tutorial` (
  `tid` int NOT NULL AUTO_INCREMENT,
  `t_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `t_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `t_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `t_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `t_cls` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `t_class` int DEFAULT '0',
  `t_class_list` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `t_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `t_utid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `t_vuser` int DEFAULT '0',
  `t_vuid` int DEFAULT '0',
  `t_hidden` int DEFAULT '0',
  `t_good` int DEFAULT '0',
  `t_count` int DEFAULT '0',
  `t_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_tutorial_chapter` (
  `tcid` int NOT NULL AUTO_INCREMENT,
  `tc_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tc_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tc_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tc_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tc_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tc_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `tc_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `tc_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tc_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tc_tutorial_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0',
  `tc_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tc_vuser` int DEFAULT '0',
  `tc_vuid` int DEFAULT '0',
  `tc_hidden` int DEFAULT '0',
  `tc_good` int DEFAULT '0',
  `tc_count` int DEFAULT '0',
  `tc_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`tcid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_tutorial_data` (
  `tdid` int NOT NULL AUTO_INCREMENT,
  `td_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `td_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `td_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `td_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `td_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `td_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `td_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `td_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `td_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `td_tutorial_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `td_ischapter` int DEFAULT '1',
  `td_chapter_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `td_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `td_vuser` int DEFAULT '0',
  `td_vuid` int DEFAULT '0',
  `td_hidden` int DEFAULT '0',
  `td_good` int DEFAULT '0',
  `td_tpl` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `td_gourl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `td_count` int DEFAULT '0',
  `td_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `td_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`tdid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_tutorial_section` (
  `tsid` int NOT NULL AUTO_INCREMENT,
  `ts_topic` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ts_titles` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ts_keywords` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ts_description` varchar(252) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ts_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ts_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ts_content_atts_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `ts_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ts_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ts_tutorial_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `ts_ischapter` int DEFAULT '1',
  `ts_chapter_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  `ts_ucode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ts_vuser` int DEFAULT '0',
  `ts_vuid` int DEFAULT '0',
  `ts_hidden` int DEFAULT '0',
  `ts_good` int DEFAULT '0',
  `ts_tpl` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ts_gourl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ts_count` int DEFAULT '0',
  `ts_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`tsid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_user` (
  `uid` int NOT NULL AUTO_INCREMENT,
  `u_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `u_password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `u_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `u_openid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_headimgurl` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_sex` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_province` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_language` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '微信用户信息',
  `u_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `u_qq` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `u_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `u_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `u_money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额字段,不能为负数',
  `u_emoney` int DEFAULT '0',
  `u_integral` int DEFAULT '0',
  `u_topic` int DEFAULT '0',
  `u_face` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `u_sign` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `u_forum_admin` int DEFAULT '0',
  `u_utype` int DEFAULT '0',
  `u_lock` int DEFAULT '0',
  `u_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `u_lasttime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `u_pretime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_user_address` (
  `uaid` int NOT NULL AUTO_INCREMENT,
  `ua_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ua_dictid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ua_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ua_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ua_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ua_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ua_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ua_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ua_order` int DEFAULT '0',
  `ua_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ua_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uaid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_user_recharge` (
  `urid` int NOT NULL AUTO_INCREMENT,
  `ur_orderid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ur_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ur_price` float DEFAULT '0',
  `ur_payment` int DEFAULT NULL,
  `ur_prepaid` int DEFAULT '0',
  `ur_trade_no` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ur_seller_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ur_timestamp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ur_state` int DEFAULT '0',
  `ur_lock` int DEFAULT '0',
  `ur_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ur_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ur_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`urid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_user_shopcart` (
  `scid` int NOT NULL AUTO_INCREMENT,
  `sc_fid` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `sc_allprice` float unsigned DEFAULT '0',
  `sc_merchandiseprice` float DEFAULT '0',
  `sc_trafficprice` float DEFAULT '0',
  `sc_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sc_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sc_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sc_phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sc_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sc_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `sc_payment` int DEFAULT '0',
  `sc_traffic` int DEFAULT '0',
  `sc_orderid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sc_prepaid` int DEFAULT '0',
  `sc_payid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sc_state` int DEFAULT '0',
  `sc_express` int DEFAULT '0',
  `sc_expressid` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0 ',
  `sc_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sc_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sc_dtime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sc_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sc_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`scid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `wdja_user_vip` (
  `uvid` int NOT NULL AUTO_INCREMENT,
  `uv_orderid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uv_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uv_utype` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uv_outype` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uv_nutype` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uv_price` float DEFAULT '0',
  `uv_payment` int DEFAULT NULL,
  `uv_payid` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uv_state` int DEFAULT '0',
  `uv_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uv_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uv_lng` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'chinese',
  PRIMARY KEY (`uvid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;