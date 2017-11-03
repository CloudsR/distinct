--
-- Table structure for table `pre_cxpforms`
--
DROP TABLE IF EXISTS `pre_cxpforms`;
CREATE TABLE IF NOT EXISTS `pre_cxpforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '表单编号',
  `title` varchar(255) NOT NULL COMMENT '表单名称',
  `description` text NOT NULL COMMENT '表单描述',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '表单状态',
  `hits` int(11) NOT NULL DEFAULT '0',
  `startdate` int(11) DEFAULT '0',
  `enddate` int(11) DEFAULT '0',
  `tid` int(11) NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL,
  `userid` int(11) NOT NULL DEFAULT '0',
  `form_type` int(11) NOT NULL DEFAULT '0',
  `visibilityform` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `tid` (`tid`),
  KEY `addtime` (`addtime`),
  KEY `form_type` (`form_type`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pre_cxpform_contents`
--
DROP TABLE IF EXISTS `pre_cxpform_contents`;
CREATE TABLE IF NOT EXISTS `pre_cxpform_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `form_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL,
  `fromurl` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `bookingnumber` varchar(255) NOT NULL,
  `bookingtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `form_id` (`form_id`),
  KEY `status` (`status`)
) ENGINE=MyISAM  AUTO_INCREMENT=1;



--
-- Table structure for table `pre_cxpform_content_extra`
--
DROP TABLE IF EXISTS `pre_cxpform_content_extra`;
CREATE TABLE IF NOT EXISTS `pre_cxpform_content_extra` (
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '提交时间',
  `form_id` int(11) NOT NULL DEFAULT '0' COMMENT '表单编号',
  `field_id` int(11) NOT NULL DEFAULT '0' COMMENT '字段编号',
  `field_value` text NOT NULL COMMENT '字段值',
  `content_id` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `form_id_2` (`form_id`,`field_id`,`content_id`),
  KEY `form_id` (`form_id`),
  KEY `field_id` (`field_id`),
  KEY `content_id` (`content_id`)
) ENGINE=MyISAM COMMENT='表单内容';


--
-- Table structure for table `pre_cxpform_content_logs`
--
DROP TABLE IF EXISTS `pre_cxpform_content_logs`;
CREATE TABLE IF NOT EXISTS `pre_cxpform_content_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `status_log` text NOT NULL,
  `content` text NOT NULL,
  `form_id` int(11) NOT NULL DEFAULT '0',
  `content_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_id` (`admin_id`),
  KEY `addtime` (`addtime`),
  KEY `form_id` (`form_id`),
  KEY `content_id` (`content_id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pre_cxpform_content_status`
--
DROP TABLE IF EXISTS `pre_cxpform_content_status`;
CREATE TABLE IF NOT EXISTS `pre_cxpform_content_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL DEFAULT '0',
  `status_name` varchar(255) NOT NULL,
  `status_color` varchar(255) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `sortid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `is_default` (`is_default`),
  KEY `sortid` (`sortid`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pre_cxpform_fields`
--
DROP TABLE IF EXISTS `pre_cxpform_fields`;
CREATE TABLE `pre_cxpform_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '字段编号',
  `field_label` varchar(255) NOT NULL COMMENT '字段标签',
  `field_name` varchar(255) NOT NULL COMMENT '字段名称',
  `field_type` varchar(255) NOT NULL COMMENT '字段类型',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `sortid` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `form_id` int(11) NOT NULL DEFAULT '0' COMMENT '表单编号',
  `isrequired` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否必选',
  `field_desc` text NOT NULL,
  `select_num` tinyint(1) NOT NULL DEFAULT '1',
  `tid` int(11) NOT NULL DEFAULT '0',
  `show_other` tinyint(1) NOT NULL DEFAULT '0',
  `is_search` tinyint(1) NOT NULL DEFAULT '1',
  `is_header` tinyint(1) NOT NULL DEFAULT '1',
  `inline` tinyint(1) NOT NULL DEFAULT '0',
  `is_editable` tinyint(1) NOT NULL DEFAULT '1',
  `regex_model` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `fid` (`tid`),
  KEY `isrequired` (`isrequired`),
  KEY `sortid` (`sortid`),
  KEY `field_type` (`field_type`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `pre_cxpform_field_options`
--
DROP TABLE IF EXISTS `pre_cxpform_field_options`;
CREATE TABLE IF NOT EXISTS `pre_cxpform_field_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL DEFAULT '0',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `field_id` int(11) NOT NULL DEFAULT '0',
  `option_name` varchar(255) NOT NULL,
  `sortid` int(11) NOT NULL DEFAULT '0',
  `picurl` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `field_id` (`field_id`),
  KEY `sortid` (`sortid`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pre_cxpform_field_value`
--
DROP TABLE IF EXISTS `pre_cxpform_field_value`;
CREATE TABLE IF NOT EXISTS `pre_cxpform_field_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL DEFAULT '0',
  `field_id` int(11) NOT NULL DEFAULT '0',
  `field_value` text NOT NULL,
  `option_id` int(11) NOT NULL DEFAULT '0',
  `content_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `field_id` (`field_id`),
  KEY `option_id` (`option_id`),
  KEY `content_id` (`content_id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pre_cxpform_hits`
--
DROP TABLE IF EXISTS `pre_cxpform_hits`;
CREATE TABLE IF NOT EXISTS `pre_cxpform_hits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL DEFAULT '0',
  `addtime` int(11) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `user_id` (`user_id`),
  KEY `addtime` (`addtime`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pre_cxpform_more_content`
--
DROP TABLE IF EXISTS `pre_cxpform_more_content`;
CREATE TABLE IF NOT EXISTS `pre_cxpform_more_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `sortid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pre_cxpform_more_content_acls`
--
DROP TABLE IF EXISTS `pre_cxpform_more_content_acls`;
CREATE TABLE IF NOT EXISTS `pre_cxpform_more_content_acls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `logical` varchar(255) NOT NULL,
  `filtertype` varchar(255) NOT NULL,
  `comparison` varchar(255) NOT NULL,
  `data` varchar(255) NOT NULL,
  `executionorder` int(11) NOT NULL DEFAULT '0',
  `form_id` int(11) NOT NULL DEFAULT '0',
  `content_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `executionorder` (`executionorder`),
  KEY `form_id` (`form_id`),
  KEY `content_id` (`content_id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pre_cxpform_notice_setting`
--
DROP TABLE IF EXISTS `pre_cxpform_notice_setting`;
CREATE TABLE IF NOT EXISTS `pre_cxpform_notice_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '提醒总开关',
  `email_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '邮件提醒开关',
  `sms_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '短信提醒开关',
  `weixin_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '微信提醒开关',
  `email_to` text NOT NULL COMMENT '接收邮箱',
  `email_content` text NOT NULL COMMENT '邮件内容',
  `email_title` varchar(255) NOT NULL COMMENT '邮件标题',
  `sms_to` text NOT NULL,
  `sms_content` text NOT NULL,
  `weixin_content` text NOT NULL,
  `weixin_to` text NOT NULL,
  `form_id` int(11) NOT NULL DEFAULT '0',
  `message_status` tinyint(1) NOT NULL DEFAULT '1',
  `message_to` varchar(255) NOT NULL,
  `message_content` text NOT NULL,
  `message_title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `status` (`status`),
  KEY `email_status` (`email_status`),
  KEY `sms_status` (`sms_status`),
  KEY `weixin_status` (`weixin_status`),
  KEY `message_status` (`message_status`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pre_cxpform_queue`
--
DROP TABLE IF EXISTS `pre_cxpform_queue`;
CREATE TABLE IF NOT EXISTS `pre_cxpform_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form_id` int(11) NOT NULL,
  `bookingtime` int(11) NOT NULL DEFAULT '0',
  `ucount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pre_cxpform_submit_setting`
--
DROP TABLE IF EXISTS `pre_cxpform_submit_setting`;
CREATE TABLE IF NOT EXISTS `pre_cxpform_submit_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `limit_ip` text NOT NULL,
  `ip_perday` int(11) NOT NULL DEFAULT '0',
  `form_id` int(11) NOT NULL DEFAULT '0',
  `iscaptcha` tinyint(1) NOT NULL DEFAULT '0',
  `islogin` tinyint(1) DEFAULT '0',
  `user_perday` int(11) NOT NULL DEFAULT '0',
  `show_history` tinyint(1) NOT NULL DEFAULT '0',
  `show_title` tinyint(1) NOT NULL DEFAULT '1',
  `show_description` tinyint(1) NOT NULL DEFAULT '1',
  `success_message` text NOT NULL,
  `prefix1` varchar(255) NOT NULL,
  `allowshowall` tinyint(1) NOT NULL DEFAULT '1',
  `allowoutsite` tinyint(1) NOT NULL DEFAULT '0',
  `startnum` varchar(50) NOT NULL,
  `endnum` varchar(50) NOT NULL,
  `gentype` tinyint(1) NOT NULL DEFAULT '1',
  `early_days` tinyint(1) NOT NULL DEFAULT '7',
  `workday` varchar(255) NOT NULL,
  `bookingcontent` text NOT NULL,
  `send_bookingmsg` tinyint(1) NOT NULL DEFAULT '0',
  `show_submit_count` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `form_id` (`form_id`),
  KEY `islogin` (`islogin`),
  KEY `iscaptcha` (`iscaptcha`)
) ENGINE=MyISAM AUTO_INCREMENT=1 ;