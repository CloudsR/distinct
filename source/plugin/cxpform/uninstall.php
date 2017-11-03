<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: uninstall.php 24473 2011-09-21 03:53:05Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$sql = <<<EOF

DROP TABLE IF EXISTS `pre_cxpforms`,`pre_cxpform_contents`,`pre_cxpform_content_extra`,`pre_cxpform_content_extra_0`,`pre_cxpform_content_extra_1`,`pre_cxpform_content_extra_2`,`pre_cxpform_content_extra_3`,`pre_cxpform_content_extra_4`,`pre_cxpform_content_extra_5`,`pre_cxpform_content_extra_6`,`pre_cxpform_content_extra_7`,`pre_cxpform_content_extra_8`,`pre_cxpform_content_extra_9`,`pre_cxpform_content_logs`,`pre_cxpform_content_status`,`pre_cxpform_fields` ,`pre_cxpform_field_options`,`pre_cxpform_field_value`,`pre_cxpform_hits`,`pre_cxpform_more_content`,`pre_cxpform_more_content_acls`,`pre_cxpform_notice_setting`,`pre_cxpform_queue`,`pre_cxpform_submit_setting`;

EOF;

runquery($sql);

$finish = TRUE;