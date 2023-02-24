SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `wdja_aboutus` (
  `abid` int NOT NULL AUTO_INCREMENT,
  `ab_topic` varchar(50) DEFAULT NULL,
  `ab_titles` varchar(250) DEFAULT NULL,
  `ab_keywords` varchar(252) DEFAULT NULL,
  `ab_description` varchar(252) DEFAULT NULL,
  `ab_image` varchar(255) DEFAULT NULL,
  `ab_content` text,
  `ab_content_atts_list` text,
  `ab_time` datetime DEFAULT '2021-08-01 08:00:00',
  `ab_update` datetime DEFAULT '2021-08-01 08:00:00',
  `ab_ucode` varchar(50) DEFAULT NULL,
  `ab_hidden` int DEFAULT '0',
  `ab_good` int DEFAULT '0',
  `ab_tpl` varchar(50) DEFAULT NULL,
  `ab_gourl` varchar(255) DEFAULT NULL,
  `ab_count` int DEFAULT '0',
  `ab_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`abid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_admin` (
  `aid` int NOT NULL AUTO_INCREMENT,
  `a_name` varchar(50) DEFAULT NULL,
  `a_pword` varchar(50) DEFAULT NULL,
  `a_popedom` text,
  `a_lock` int DEFAULT '0',
  `a_lasttime` datetime DEFAULT '2021-08-01 08:00:00',
  `a_lastip` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_admin_log` (
  `lid` int NOT NULL AUTO_INCREMENT,
  `l_name` varchar(50) DEFAULT NULL,
  `l_time` datetime DEFAULT '2021-08-01 08:00:00',
  `l_ip` varchar(50) DEFAULT NULL,
  `l_islogin` int DEFAULT '0',
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_ask` (
  `aid` int NOT NULL AUTO_INCREMENT,
  `a_topic` varchar(252) DEFAULT NULL,
  `a_titles` varchar(250) DEFAULT NULL,
  `a_keywords` varchar(252) DEFAULT NULL,
  `a_description` varchar(252) DEFAULT NULL,
  `a_image` varchar(255) DEFAULT NULL,
  `a_content` text,
  `a_content_atts_list` text,
  `a_time` datetime DEFAULT '2021-08-01 08:00:00',
  `a_update` datetime DEFAULT '2021-08-01 08:00:00',
  `a_cls` text,
  `a_class` int DEFAULT '0',
  `a_class_list` varchar(50) DEFAULT '0',
  `a_ucode` varchar(50) DEFAULT NULL,
  `a_hidden` int DEFAULT '0',
  `a_good` int DEFAULT '0',
  `a_count` int DEFAULT '0',
  `a_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_ask_answer` (
  `aid` int NOT NULL AUTO_INCREMENT,
  `a_author` varchar(50) DEFAULT NULL,
  `a_authorip` varchar(50) DEFAULT NULL,
  `a_content` text,
  `a_time` datetime DEFAULT '2021-08-01 08:00:00',
  `a_update` datetime DEFAULT '2021-08-01 08:00:00',
  `a_fid` int DEFAULT '0',
  `a_good` int DEFAULT '0',
  `a_hidden` int DEFAULT '0',
  `a_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_check` (
  `cid` int NOT NULL AUTO_INCREMENT,
  `c_url` varchar(255) DEFAULT NULL,
  `c_genre` varchar(50) DEFAULT NULL,
  `c_gid` varchar(50) DEFAULT NULL,
  `c_name` varchar(50) DEFAULT NULL,
  `c_ip` varchar(50) DEFAULT NULL,
  `c_sex` int DEFAULT '0',
  `c_mobile` varchar(50) DEFAULT '0',
  `c_email` varchar(50) DEFAULT NULL,
  `c_address` varchar(255) DEFAULT NULL,
  `c_title` varchar(252) DEFAULT NULL,
  `c_content` text,
  `c_time` datetime DEFAULT '2021-08-01 08:00:00',
  `c_reply` text,
  `c_replytime` datetime DEFAULT '2021-08-01 08:00:00',
  `c_hidden` int DEFAULT '0',
  `c_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cid`),
  UNIQUE KEY `cid` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_download` (
  `did` int NOT NULL AUTO_INCREMENT,
  `d_topic` varchar(252) DEFAULT NULL,
  `d_titles` varchar(250) DEFAULT NULL,
  `d_keywords` varchar(252) DEFAULT NULL,
  `d_description` varchar(252) DEFAULT NULL,
  `d_cls` text,
  `d_class` int DEFAULT '0',
  `d_class_list` varchar(50) DEFAULT '0',
  `d_scont` text,
  `d_image` varchar(255) DEFAULT NULL,
  `d_file` varchar(255) DEFAULT NULL,
  `d_content` text,
  `d_content_atts_list` text,
  `d_size` float DEFAULT '0',
  `d_urls` text,
  `d_ucode` varchar(50) DEFAULT NULL,
  `d_hidden` int DEFAULT '0',
  `d_good` int DEFAULT '0',
  `d_time` datetime DEFAULT '2021-08-01 08:00:00',
  `d_count` int DEFAULT '0',
  `d_update` datetime DEFAULT '2021-08-01 08:00:00',
  `d_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`did`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_expansion_baidupush` (
  `bid` int NOT NULL AUTO_INCREMENT,
  `b_genre` varchar(152) DEFAULT NULL,
  `b_gid` int NOT NULL,
  `b_topic` varchar(255) DEFAULT NULL,
  `b_url` varchar(255) DEFAULT NULL,
  `b_content` text,
  `b_count` int DEFAULT '0',
  `b_type` varchar(25) DEFAULT '0',
  `b_state` varchar(25) DEFAULT '0',
  `b_time` datetime DEFAULT '2021-08-01 08:00:00',
  `b_update` datetime DEFAULT '2021-08-01 08:00:00',
  `b_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_expansion_baidupush_data` (
  `bdid` int NOT NULL AUTO_INCREMENT,
  `bd_bid` int NOT NULL,
  `bd_order` int DEFAULT '0',
  `bd_type` varchar(25) DEFAULT '0',
  `bd_state` varchar(25) DEFAULT '0',
  `bd_content` text,
  `bd_time` datetime DEFAULT '2021-08-01 08:00:00',
  `bd_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`bdid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_expansion_fields` (
  `fid` int NOT NULL AUTO_INCREMENT,
  `f_genre` varchar(50) DEFAULT NULL,
  `f_name` varchar(50) DEFAULT NULL,
  `f_topic` varchar(50) DEFAULT NULL,
  `f_type` int DEFAULT '0',
  `f_count` varchar(9) DEFAULT '0',
  `f_hidden` int DEFAULT '0',
  `f_hidden_list` int DEFAULT '0',
  `f_hidden_detail` int DEFAULT '0',
  `f_time` datetime DEFAULT '2021-08-01 08:00:00',
  `f_update` datetime DEFAULT '2021-08-01 08:00:00',
  `f_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_expansion_fields_data` (
  `fdid` int NOT NULL AUTO_INCREMENT,
  `fd_topic` varchar(50) DEFAULT NULL,
  `fd_fid` int DEFAULT '0',
  `fd_oid` int DEFAULT '0',
  `fd_ooid` int DEFAULT '0',
  PRIMARY KEY (`fdid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_expansion_fields_gid` (
  `fgid` int NOT NULL AUTO_INCREMENT,
  `fg_fid` int DEFAULT '0',
  `fg_gid` varchar(50) DEFAULT NULL,
  `fg_data` text,
  `fg_time` datetime DEFAULT '2021-08-01 08:00:00',
  `fg_update` datetime DEFAULT '2021-08-01 08:00:00',
  PRIMARY KEY (`fgid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_expansion_iplock` (
  `ipid` int NOT NULL AUTO_INCREMENT,
  `ip_area` varchar(50) DEFAULT NULL,
  `ip_robots` varchar(25) DEFAULT NULL,
  `ip_ip` varchar(152) DEFAULT NULL,
  `ip_come` varchar(255) DEFAULT NULL,
  `ip_content` text,
  `ip_lock` int DEFAULT '0',
  `ip_out` int DEFAULT '0',
  `ip_time` datetime DEFAULT '2021-08-01 08:00:00',
  `ip_update` datetime DEFAULT '2021-08-01 08:00:00',
  `ip_count` int DEFAULT '0',
  `ip_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ipid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_expansion_label` (
  `elid` int NOT NULL AUTO_INCREMENT,
  `el_topic` varchar(50) DEFAULT NULL,
  `el_type` int DEFAULT '0',
  `el_images_tpl` varchar(50) DEFAULT NULL,
  `el_content` text,
  `el_content_atts_list` text,
  `el_inputs_type` varchar(50) DEFAULT 'text',
  `el_time` datetime DEFAULT '2021-08-01 08:00:00',
  `el_update` datetime DEFAULT '2021-08-01 08:00:00',
  `el_hidden` int DEFAULT '0',
  `el_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`elid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_expansion_timer` (
  `etid` int NOT NULL AUTO_INCREMENT,
  `et_topic` varchar(50) DEFAULT NULL,
  `et_genre` varchar(50) DEFAULT NULL COMMENT '模块',
  `et_gid` int DEFAULT '0' COMMENT '内容ID',
  `et_event` int DEFAULT '0' COMMENT '定时事件:发布,删除,上下架',
  `et_timer_switch` int DEFAULT '0' COMMENT '定时开关',
  `et_timer` datetime DEFAULT '2021-08-01 08:00:00' COMMENT '任务启动时间',
  `et_state` int DEFAULT '0' COMMENT '任务状态:中止,暂停,进行中,结束',
  `et_time` datetime DEFAULT '2021-08-01 08:00:00',
  `et_update` datetime DEFAULT '2021-08-01 08:00:00',
  `et_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`etid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_expansion_vuser` (
  `evid` int NOT NULL AUTO_INCREMENT,
  `ev_topic` varchar(50) DEFAULT NULL,
  `ev_time` datetime DEFAULT '2021-08-01 08:00:00',
  `ev_update` datetime DEFAULT '2021-08-01 08:00:00',
  `ev_count` int DEFAULT '0',
  `ev_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`evid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_forum_blacklist` (
  `bid` int NOT NULL AUTO_INCREMENT,
  `b_username` varchar(50) DEFAULT NULL,
  `b_sid` int DEFAULT '0',
  `b_admin` varchar(50) DEFAULT NULL,
  `b_time` datetime DEFAULT '2021-08-01 08:00:00',
  `b_remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`bid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_forum_sort` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `s_sort` varchar(50) DEFAULT NULL,
  `s_fid` varchar(255) DEFAULT NULL,
  `s_fsid` int DEFAULT '0',
  `s_lng` varchar(50) DEFAULT NULL,
  `s_order` int DEFAULT '0',
  `s_type` int DEFAULT '0',
  `s_mode` int DEFAULT '0',
  `s_ispop` int DEFAULT '0',
  `s_popedom` varchar(50) DEFAULT NULL,
  `s_images` varchar(255) DEFAULT NULL,
  `s_admin` varchar(255) DEFAULT NULL,
  `s_rule` varchar(255) DEFAULT NULL,
  `s_explain` varchar(255) DEFAULT NULL,
  `s_attestation` varchar(255) DEFAULT NULL,
  `s_hidden` int DEFAULT '0',
  `s_ntopic` int DEFAULT '0',
  `s_nnote` int DEFAULT '0',
  `s_today_ntopic` int DEFAULT '0',
  `s_today_date` datetime DEFAULT '2021-08-01 08:00:00',
  `s_last_topic` varchar(50) DEFAULT NULL,
  `s_last_tid` int DEFAULT '0',
  `s_last_time` datetime DEFAULT '2021-08-01 08:00:00',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_forum_topic` (
  `tid` int NOT NULL AUTO_INCREMENT,
  `t_sid` int DEFAULT '0',
  `t_fid` int DEFAULT '0',
  `t_icon` int DEFAULT '0',
  `t_topic` varchar(50) DEFAULT NULL,
  `t_color` varchar(50) DEFAULT NULL,
  `t_b` int DEFAULT '0',
  `t_author` varchar(50) DEFAULT NULL,
  `t_authorip` varchar(50) DEFAULT NULL,
  `t_content` text,
  `t_edit_info` varchar(255) DEFAULT NULL,
  `t_content_files_list` text,
  `t_voteid` int DEFAULT '0',
  `t_ubb` int DEFAULT '0',
  `t_reply` int DEFAULT '0',
  `t_count` int DEFAULT '0',
  `t_time` datetime DEFAULT '2021-08-01 08:00:00',
  `t_htop` int DEFAULT '0',
  `t_top` int DEFAULT '0',
  `t_lock` int DEFAULT '0',
  `t_elite` int DEFAULT '0',
  `t_hidden` int DEFAULT '0',
  `t_lasttime` datetime DEFAULT '2021-08-01 08:00:00',
  `t_lastuser` varchar(50) DEFAULT NULL,
  `t_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_forum_vote` (
  `vid` int NOT NULL AUTO_INCREMENT,
  `v_topic` varchar(50) DEFAULT NULL,
  `v_type` int DEFAULT '0',
  `v_time` datetime DEFAULT '2021-08-01 08:00:00',
  `v_day` int DEFAULT '0',
  PRIMARY KEY (`vid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_forum_vote_data` (
  `vdid` int NOT NULL AUTO_INCREMENT,
  `vd_topic` varchar(50) DEFAULT NULL,
  `vd_fid` int DEFAULT '0',
  `vd_vid` int DEFAULT '0',
  `vd_count` int DEFAULT '0',
  PRIMARY KEY (`vdid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_forum_vote_voter` (
  `vuid` int NOT NULL AUTO_INCREMENT,
  `vu_fid` int DEFAULT '0',
  `vu_ip` varchar(50) DEFAULT NULL,
  `vu_username` varchar(50) DEFAULT NULL,
  `vu_data` varchar(255) DEFAULT NULL,
  `vu_time` datetime DEFAULT '2021-08-01 08:00:00',
  PRIMARY KEY (`vuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_message` (
  `mid` int NOT NULL AUTO_INCREMENT,
  `m_name` varchar(50) DEFAULT NULL,
  `m_ip` varchar(50) DEFAULT NULL,
  `m_sex` int DEFAULT '0',
  `m_mobile` varchar(50) DEFAULT '0',
  `m_email` varchar(50) DEFAULT NULL,
  `m_address` varchar(255) DEFAULT NULL,
  `m_title` varchar(252) DEFAULT NULL,
  `m_content` text,
  `m_time` datetime DEFAULT '2021-08-01 08:00:00',
  `m_reply` text,
  `m_replytime` datetime DEFAULT '2021-08-01 08:00:00',
  `m_hidden` int DEFAULT '0',
  `m_token` varchar(255) NOT NULL,
  `m_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_news` (
  `nid` int NOT NULL AUTO_INCREMENT,
  `n_topic` varchar(252) DEFAULT NULL,
  `n_titles` varchar(250) DEFAULT NULL,
  `n_keywords` varchar(252) DEFAULT NULL,
  `n_description` varchar(252) DEFAULT NULL,
  `n_image` varchar(255) DEFAULT NULL,
  `n_content` text,
  `n_content_atts_list` text,
  `n_time` datetime DEFAULT '2021-08-01 08:00:00',
  `n_update` datetime DEFAULT '2021-08-01 08:00:00',
  `n_cls` text,
  `n_class` int DEFAULT '0',
  `n_class_list` varchar(50) DEFAULT '0',
  `n_ucode` varchar(50) DEFAULT NULL,
  `n_vuser` int DEFAULT '0',
  `n_vuid` int DEFAULT '0',
  `n_hidden` int DEFAULT '0',
  `n_good` int DEFAULT '0',
  `n_count` int DEFAULT '0',
  `n_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_pages` (
  `pid` int NOT NULL AUTO_INCREMENT,
  `p_topic` varchar(252) DEFAULT NULL,
  `p_titles` varchar(250) DEFAULT NULL,
  `p_keywords` varchar(252) DEFAULT NULL,
  `p_description` varchar(252) DEFAULT NULL,
  `p_fid` varchar(50) DEFAULT '0',
  `p_fsid` int DEFAULT '0',
  `p_type` int DEFAULT '0',
  `p_image` varchar(255) DEFAULT NULL,
  `p_content` text,
  `p_content_atts_list` text,
  `p_time` datetime DEFAULT '2021-08-01 08:00:00',
  `p_update` datetime DEFAULT '2021-08-01 08:00:00',
  `p_ucode` varchar(50) DEFAULT NULL,
  `p_hidden` int DEFAULT '0',
  `p_good` int DEFAULT '0',
  `p_count` int DEFAULT '0',
  `p_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_product` (
  `pid` int NOT NULL AUTO_INCREMENT,
  `p_snum` varchar(50) DEFAULT NULL,
  `p_topic` varchar(252) DEFAULT NULL,
  `p_titles` varchar(250) DEFAULT NULL,
  `p_keywords` varchar(252) DEFAULT NULL,
  `p_description` varchar(252) DEFAULT NULL,
  `p_image` varchar(255) DEFAULT NULL,
  `p_gallery` text,
  `p_content` text,
  `p_content_atts_list` text,
  `p_infos` varchar(1200) DEFAULT '{:::}{|||}{:::}',
  `p_time` datetime DEFAULT '2021-08-01 08:00:00',
  `p_update` datetime DEFAULT '2021-08-01 08:00:00',
  `p_cls` text,
  `p_class` int DEFAULT '0',
  `p_class_list` varchar(50) DEFAULT '0',
  `p_ucode` varchar(50) DEFAULT NULL,
  `p_hidden` int DEFAULT '0',
  `p_good` int DEFAULT '0',
  `p_count` int DEFAULT '0',
  `p_lng` varchar(50) DEFAULT 'chinese',
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_question` (
  `qid` int NOT NULL AUTO_INCREMENT,
  `q_topic` varchar(252) DEFAULT NULL,
  `q_titles` varchar(250) DEFAULT NULL,
  `q_keywords` varchar(252) DEFAULT NULL,
  `q_description` varchar(252) DEFAULT NULL,
  `q_image` varchar(255) DEFAULT NULL,
  `q_content` text,
  `q_content_atts_list` text,
  `q_time` datetime DEFAULT '2021-08-01 08:00:00',
  `q_update` datetime DEFAULT '2021-08-01 08:00:00',
  `q_ucode` varchar(50) DEFAULT NULL,
  `q_hidden` int DEFAULT '0',
  `q_good` int DEFAULT '0',
  `q_count` int DEFAULT '0',
  `q_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_search` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `s_topic` varchar(252) DEFAULT NULL,
  `s_ip` varchar(252) DEFAULT NULL,
  `s_content` varchar(252) DEFAULT NULL,
  `s_infos` text,
  `s_hidden` int DEFAULT '0',
  `s_update` datetime DEFAULT '2021-08-01 08:00:00',
  `s_time` datetime DEFAULT '2021-08-01 08:00:00',
  `s_count` int DEFAULT '0',
  `s_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_shop` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `s_snum` varchar(50) DEFAULT NULL,
  `s_topic` varchar(252) DEFAULT NULL,
  `s_titles` varchar(250) DEFAULT NULL,
  `s_keywords` varchar(252) DEFAULT NULL,
  `s_description` varchar(252) DEFAULT NULL,
  `s_image` varchar(255) DEFAULT NULL,
  `s_price` float DEFAULT '0',
  `s_wprice` float DEFAULT '0',
  `s_limitnum` int DEFAULT '0',
  `s_unit` varchar(50) DEFAULT NULL,
  `s_content` text,
  `s_content_atts_list` text,
  `s_infos` varchar(1200) DEFAULT '{:::}{|||}{:::}',
  `s_ucode` varchar(50) DEFAULT NULL,
  `s_time` datetime DEFAULT '2021-08-01 08:00:00',
  `s_update` datetime DEFAULT '2021-08-01 08:00:00',
  `s_cls` text,
  `s_class` int DEFAULT '0',
  `s_class_list` varchar(50) DEFAULT '0',
  `s_hidden` int DEFAULT '0',
  `s_good` int DEFAULT '0',
  `s_count` int DEFAULT '0',
  `s_lng` varchar(50) DEFAULT 'chinese',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_shop_price` (
  `spid` int NOT NULL AUTO_INCREMENT,
  `sp_shop_id` int DEFAULT NULL,
  `sp_group_price` varchar(50) DEFAULT NULL,
  `sp_group_id` int DEFAULT NULL,
  `sp_lng` varchar(50) DEFAULT NULL,
  `sp_time` datetime DEFAULT '2021-08-01 08:00:00',
  `sp_update` datetime DEFAULT '2021-08-01 08:00:00',
  PRIMARY KEY (`spid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_support_collect` (
  `cid` int NOT NULL AUTO_INCREMENT,
  `c_url` varchar(255) DEFAULT NULL,
  `c_image` varchar(255) DEFAULT NULL,
  `c_title` varchar(255) DEFAULT NULL,
  `c_author` varchar(255) DEFAULT NULL,
  `c_content` varchar(255) DEFAULT NULL,
  `c_replace` varchar(255) DEFAULT NULL,
  `c_hidden` int DEFAULT '0',
  `c_time` datetime DEFAULT '2021-08-01 08:00:00',
  `c_update` datetime DEFAULT '2021-08-01 08:00:00',
  `c_lng` varchar(50) DEFAULT 'chinese',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_support_dict` (
  `did` int NOT NULL AUTO_INCREMENT,
  `d_pid` int DEFAULT '0',
  `d_topic` varchar(50) DEFAULT NULL,
  `d_alt` varchar(250) DEFAULT NULL,
  `d_fid` varchar(255) DEFAULT NULL,
  `d_fsid` int DEFAULT '0',
  `d_lid` int DEFAULT '0',
  `d_group` varchar(50) DEFAULT NULL,
  `d_hidden` int DEFAULT '0',
  `d_order` int DEFAULT '0',
  `d_time` datetime DEFAULT '2021-08-01 08:00:00',
  `d_update` datetime DEFAULT '2021-08-01 08:00:00',
  `d_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`did`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_support_linktext` (
  `lid` int NOT NULL AUTO_INCREMENT,
  `l_topic` varchar(50) DEFAULT NULL,
  `l_url` varchar(255) DEFAULT NULL,
  `l_keyword` varchar(50) DEFAULT NULL,
  `l_intro` varchar(255) DEFAULT NULL,
  `l_hidden` int DEFAULT '0',
  `l_time` datetime DEFAULT '2021-08-01 08:00:00',
  `l_update` datetime DEFAULT '2021-08-01 08:00:00',
  `l_lng` varchar(50) DEFAULT 'chinese',
  PRIMARY KEY (`lid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_support_menu` (
  `mid` int NOT NULL AUTO_INCREMENT,
  `m_pid` int DEFAULT '0',
  `m_topic` varchar(50) DEFAULT NULL,
  `m_title` varchar(50) DEFAULT NULL,
  `m_image` varchar(255) DEFAULT NULL,
  `m_alt` varchar(250) DEFAULT NULL,
  `m_fid` varchar(255) DEFAULT NULL,
  `m_fsid` int DEFAULT '0',
  `m_lid` int DEFAULT '0',
  `m_group` varchar(50) DEFAULT NULL,
  `m_hidden` int DEFAULT '0',
  `m_gourl` varchar(255) DEFAULT NULL,
  `m_order` int DEFAULT '0',
  `m_time` datetime DEFAULT '2021-08-01 08:00:00',
  `m_update` datetime DEFAULT '2021-08-01 08:00:00',
  `m_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_support_slide` (
  `sid` int NOT NULL AUTO_INCREMENT,
  `s_topic` varchar(50) DEFAULT NULL,
  `s_url` varchar(255) DEFAULT NULL,
  `s_image` varchar(255) DEFAULT NULL,
  `s_intro` varchar(255) DEFAULT NULL,
  `s_order` int DEFAULT '0',
  `s_hidden` int DEFAULT '0',
  `s_time` datetime DEFAULT '2021-08-01 08:00:00',
  `s_update` datetime DEFAULT '2021-08-01 08:00:00',
  `s_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_support_sort` (
  `sortid` int NOT NULL AUTO_INCREMENT,
  `sort_pid` int DEFAULT '0',
  `sort_sort` varchar(50) DEFAULT NULL,
  `sort_titles` varchar(250) DEFAULT NULL,
  `sort_keywords` varchar(50) DEFAULT NULL,
  `sort_description` varchar(250) DEFAULT NULL,
  `sort_image` varchar(255) DEFAULT NULL,
  `sort_fid` varchar(255) DEFAULT NULL,
  `sort_fsid` int DEFAULT '0',
  `sort_lid` int DEFAULT '0',
  `sort_genre` varchar(50) DEFAULT NULL,
  `sort_hidden` int DEFAULT '0',
  `sort_gourl` varchar(255) DEFAULT NULL,
  `sort_tpl_list` varchar(50) DEFAULT NULL,
  `sort_tpl_detail` varchar(50) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `sort_time` datetime DEFAULT '2021-08-01 08:00:00',
  `sort_update` datetime DEFAULT '2021-08-01 08:00:00',
  `sort_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`sortid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_sys_note` (
  `nid` int NOT NULL AUTO_INCREMENT,
  `n_topic` varchar(50) DEFAULT NULL,
  `n_image` varchar(255) DEFAULT NULL,
  `n_content` text,
  `n_content_atts_list` text,
  `n_time` datetime DEFAULT '2021-08-01 08:00:00',
  `n_update` datetime DEFAULT '2021-08-01 08:00:00',
  `n_finish` int DEFAULT '0',
  `n_count` int DEFAULT '0',
  `n_lng` varchar(50) DEFAULT 'chinese',
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_sys_upload` (
  `upid` int NOT NULL AUTO_INCREMENT,
  `up_genre` varchar(50) DEFAULT NULL,
  `up_upident` varchar(50) DEFAULT NULL,
  `up_filename` varchar(255) DEFAULT NULL,
  `up_field` varchar(50) DEFAULT NULL,
  `up_fid` int DEFAULT '0',
  `up_time` datetime DEFAULT '2021-08-01 08:00:00',
  `up_user` varchar(50) DEFAULT NULL,
  `up_valid` int DEFAULT '0',
  `up_voidreason` int DEFAULT '0',
  `up_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`upid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_sys_related` (
  `rid` int NOT NULL AUTO_INCREMENT,
  `r_genre` varchar(50) DEFAULT NULL,
  `r_gid` varchar(50) DEFAULT NULL,
  `r_source` varchar(25) DEFAULT NULL,
  `r_title` varchar(250) DEFAULT NULL,
  `r_sid` varchar(250) DEFAULT NULL,
  `r_time` datetime DEFAULT '2021-08-01 08:00:00',
  `r_update` datetime DEFAULT '2021-08-01 08:00:00',
  `r_lng` varchar(25) DEFAULT 'chinese',
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_tags` (
  `tid` int NOT NULL AUTO_INCREMENT,
  `t_topic` varchar(50) DEFAULT NULL,
  `t_titles` varchar(250) DEFAULT NULL,
  `t_keywords` varchar(152) DEFAULT NULL,
  `t_description` varchar(252) DEFAULT NULL,
  `t_image` varchar(255) DEFAULT NULL,
  `t_content` text,
  `t_content_atts_list` text,
  `t_time` datetime DEFAULT '2021-08-01 08:00:00',
  `t_update` datetime DEFAULT '2021-08-01 08:00:00',
  `t_hidden` int DEFAULT '0',
  `t_good` int DEFAULT '0',
  `t_gourl` varchar(255) DEFAULT NULL,
  `t_count` int DEFAULT '0',
  `t_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_tags_data` (
  `tdid` int NOT NULL AUTO_INCREMENT,
  `td_genre` varchar(50) NOT NULL,
  `td_gid` int DEFAULT '0',
  `td_tid` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`tdid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_tutorial` (
  `tid` int NOT NULL AUTO_INCREMENT,
  `t_topic` varchar(252) DEFAULT NULL,
  `t_titles` varchar(250) DEFAULT NULL,
  `t_keywords` varchar(252) DEFAULT NULL,
  `t_description` varchar(252) DEFAULT NULL,
  `t_image` varchar(255) DEFAULT NULL,
  `t_content` text,
  `t_content_atts_list` text,
  `t_time` datetime DEFAULT '2021-08-01 08:00:00',
  `t_update` datetime DEFAULT '2021-08-01 08:00:00',
  `t_cls` text,
  `t_class` int DEFAULT '0',
  `t_class_list` varchar(50) DEFAULT '0',
  `t_ucode` varchar(50) DEFAULT NULL,
  `t_utid` varchar(50) DEFAULT '0',
  `t_vuser` int DEFAULT '0',
  `t_vuid` int DEFAULT '0',
  `t_hidden` int DEFAULT '0',
  `t_good` int DEFAULT '0',
  `t_count` int DEFAULT '0',
  `t_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_tutorial_chapter` (
  `tcid` int NOT NULL AUTO_INCREMENT,
  `tc_topic` varchar(252) DEFAULT NULL,
  `tc_titles` varchar(250) DEFAULT NULL,
  `tc_keywords` varchar(252) DEFAULT NULL,
  `tc_description` varchar(252) DEFAULT NULL,
  `tc_image` varchar(255) DEFAULT NULL,
  `tc_content` text,
  `tc_content_atts_list` text,
  `tc_time` datetime DEFAULT '2021-08-01 08:00:00',
  `tc_update` datetime DEFAULT '2021-08-01 08:00:00',
  `tc_tutorial_id` varchar(50) DEFAULT '0',
  `tc_ucode` varchar(50) DEFAULT NULL,
  `tc_vuser` int DEFAULT '0',
  `tc_vuid` int DEFAULT '0',
  `tc_hidden` int DEFAULT '0',
  `tc_good` int DEFAULT '0',
  `tc_count` int DEFAULT '0',
  `tc_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`tcid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_tutorial_data` (
  `tdid` int NOT NULL AUTO_INCREMENT,
  `td_topic` varchar(252) DEFAULT NULL,
  `td_titles` varchar(250) DEFAULT NULL,
  `td_keywords` varchar(252) DEFAULT NULL,
  `td_description` varchar(252) DEFAULT NULL,
  `td_image` varchar(255) DEFAULT NULL,
  `td_content` text,
  `td_content_atts_list` text,
  `td_time` datetime DEFAULT '2021-08-01 08:00:00',
  `td_update` datetime DEFAULT '2021-08-01 08:00:00',
  `td_tutorial_id` varchar(50) DEFAULT '0',
  `td_ischapter` int DEFAULT '1',
  `td_chapter_id` varchar(50) DEFAULT '0',
  `td_ucode` varchar(50) DEFAULT NULL,
  `td_vuser` int DEFAULT '0',
  `td_vuid` int DEFAULT '0',
  `td_hidden` int DEFAULT '0',
  `td_good` int DEFAULT '0',
  `td_tpl` varchar(50) DEFAULT NULL,
  `td_gourl` varchar(255) DEFAULT NULL,
  `td_count` int DEFAULT '0',
  `td_lng` varchar(50) DEFAULT NULL,
  `td_file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`tdid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_tutorial_section` (
  `tsid` int NOT NULL AUTO_INCREMENT,
  `ts_topic` varchar(252) DEFAULT NULL,
  `ts_titles` varchar(250) DEFAULT NULL,
  `ts_keywords` varchar(252) DEFAULT NULL,
  `ts_description` varchar(252) DEFAULT NULL,
  `ts_image` varchar(255) DEFAULT NULL,
  `ts_video` varchar(255) DEFAULT NULL,
  `ts_content` text,
  `ts_content_atts_list` text,
  `ts_time` datetime DEFAULT '2021-08-01 08:00:00',
  `ts_update` datetime DEFAULT '2021-08-01 08:00:00',
  `ts_tutorial_id` varchar(50) DEFAULT '0',
  `ts_ischapter` int DEFAULT '1',
  `ts_chapter_id` varchar(50) DEFAULT '0',
  `ts_ucode` varchar(50) DEFAULT NULL,
  `ts_vuser` int DEFAULT '0',
  `ts_vuid` int DEFAULT '0',
  `ts_hidden` int DEFAULT '0',
  `ts_good` int DEFAULT '0',
  `ts_tpl` varchar(50) DEFAULT NULL,
  `ts_gourl` varchar(255) DEFAULT NULL,
  `ts_count` int DEFAULT '0',
  `ts_lng` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`tsid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_user` (
  `uid` int NOT NULL AUTO_INCREMENT,
  `u_username` varchar(50) DEFAULT NULL,
  `u_password` varchar(50) DEFAULT NULL,
  `u_email` varchar(50) DEFAULT NULL,
  `u_openid` varchar(255) DEFAULT NULL COMMENT '微信用户信息',
  `u_nickname` varchar(50) DEFAULT NULL COMMENT '微信用户信息',
  `u_headimgurl` varchar(255) DEFAULT NULL COMMENT '微信用户信息',
  `u_sex` varchar(50) DEFAULT NULL COMMENT '微信用户信息',
  `u_city` varchar(50) DEFAULT NULL COMMENT '微信用户信息',
  `u_province` varchar(255) DEFAULT NULL COMMENT '微信用户信息',
  `u_country` varchar(255) DEFAULT NULL COMMENT '微信用户信息',
  `u_language` varchar(50) DEFAULT NULL COMMENT '微信用户信息',
  `u_name` varchar(50) DEFAULT NULL,
  `u_qq` varchar(50) DEFAULT NULL,
  `u_phone` varchar(50) DEFAULT NULL,
  `u_address` varchar(255) DEFAULT NULL,
  `u_money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '余额字段,不能为负数',
  `u_emoney` int DEFAULT '0',
  `u_integral` int DEFAULT '0',
  `u_topic` int DEFAULT '0',
  `u_face` varchar(255) DEFAULT NULL,
  `u_sign` varchar(255) DEFAULT NULL,
  `u_forum_admin` int DEFAULT '0',
  `u_utype` int DEFAULT '0',
  `u_lock` int DEFAULT '0',
  `u_time` datetime DEFAULT '2021-08-01 08:00:00',
  `u_lasttime` datetime DEFAULT '2021-08-01 08:00:00',
  `u_pretime` datetime DEFAULT '2021-08-01 08:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_user_address` (
  `uaid` int NOT NULL AUTO_INCREMENT,
  `ua_name` varchar(50) DEFAULT NULL,
  `ua_dictid` varchar(50) DEFAULT NULL,
  `ua_address` varchar(255) DEFAULT NULL,
  `ua_code` varchar(50) DEFAULT NULL,
  `ua_phone` varchar(50) DEFAULT NULL,
  `ua_email` varchar(50) DEFAULT NULL,
  `ua_username` varchar(50) DEFAULT NULL,
  `ua_lng` varchar(50) DEFAULT NULL,
  `ua_order` int DEFAULT '0',
  `ua_time` datetime DEFAULT '2021-08-01 08:00:00',
  `ua_update` datetime DEFAULT '2021-08-01 08:00:00',
  PRIMARY KEY (`uaid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_user_recharge` (
  `urid` int NOT NULL AUTO_INCREMENT,
  `ur_orderid` varchar(50) DEFAULT NULL,
  `ur_username` varchar(50) DEFAULT NULL,
  `ur_price` float DEFAULT '0',
  `ur_payment` int DEFAULT NULL,
  `ur_prepaid` int DEFAULT '0',
  `ur_trade_no` varchar(50) DEFAULT NULL,
  `ur_seller_id` varchar(50) DEFAULT NULL,
  `ur_timestamp` varchar(50) DEFAULT NULL,
  `ur_state` int DEFAULT '0',
  `ur_lock` int DEFAULT '0',
  `ur_time` datetime DEFAULT '2021-08-01 08:00:00',
  `ur_update` datetime DEFAULT '2021-08-01 08:00:00',
  `ur_lng` varchar(50) DEFAULT 'chinese',
  PRIMARY KEY (`urid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_user_shopcart` (
  `scid` int NOT NULL AUTO_INCREMENT,
  `sc_fid` text,
  `sc_allprice` float unsigned DEFAULT '0',
  `sc_merchandiseprice` float DEFAULT '0',
  `sc_trafficprice` float DEFAULT '0',
  `sc_name` varchar(50) DEFAULT NULL,
  `sc_address` varchar(255) DEFAULT NULL,
  `sc_code` varchar(50) DEFAULT NULL,
  `sc_phone` varchar(50) DEFAULT NULL,
  `sc_email` varchar(50) DEFAULT NULL,
  `sc_remark` text,
  `sc_payment` int DEFAULT '0',
  `sc_traffic` int DEFAULT '0',
  `sc_orderid` varchar(50) DEFAULT NULL,
  `sc_prepaid` int DEFAULT '0',
  `sc_payid` varchar(50) DEFAULT NULL,
  `sc_state` int DEFAULT '0',
  `sc_express` int DEFAULT '0',
  `sc_expressid` varchar(30) DEFAULT '0 ',
  `sc_time` datetime DEFAULT '2021-08-01 08:00:00',
  `sc_update` datetime DEFAULT '2021-08-01 08:00:00',
  `sc_dtime` datetime DEFAULT '2021-08-01 08:00:00',
  `sc_username` varchar(50) DEFAULT NULL,
  `sc_lng` varchar(50) DEFAULT 'chinese',
  PRIMARY KEY (`scid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `wdja_user_vip` (
  `uvid` int NOT NULL AUTO_INCREMENT,
  `uv_orderid` varchar(50) DEFAULT NULL,
  `uv_username` varchar(50) DEFAULT NULL,
  `uv_utype` varchar(50) DEFAULT NULL,
  `uv_outype` varchar(10) DEFAULT NULL,
  `uv_nutype` varchar(10) DEFAULT NULL,
  `uv_price` float DEFAULT '0',
  `uv_payment` int DEFAULT NULL,
  `uv_payid` varchar(50) DEFAULT NULL,
  `uv_state` int DEFAULT '0',
  `uv_time` datetime DEFAULT '2021-08-01 08:00:00',
  `uv_update` datetime DEFAULT '2021-08-01 08:00:00',
  `uv_lng` varchar(50) DEFAULT 'chinese',
  PRIMARY KEY (`uvid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `wdja_admin` (`a_name`, `a_pword`, `a_popedom`, `a_lock`, `a_lasttime`, `a_lastip`) VALUES ( 'admin', '21232f297a57a5a743894a0e4a801fc3', '-1', '0', '2021-08-01 08:00:00', '127.0.0.1');

