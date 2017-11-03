<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}


echo '<script type="text/javascript" src="source/plugin/cxpform/resource/js/jquery.js" language="javascript"></script><script type="text/javascript">var jq = jQuery.noConflict();</script>';
echo '<script type="text/javascript" src="source/plugin/cxpform/resource/js/My97DatePicker/WdatePicker.js" language="javascript"></script>';
echo '<link rel="stylesheet" type="text/css" href="source/plugin/cxpform/resource/css/style.css" />';

include_once(dirname(__FILE__) . '/include/function.php');
showtableheader();
showtitle(cxpform_lang('help_title1'));
echo cxpform_lang('help_content1');

showtitle(cxpform_lang('help_title2'));
echo cxpform_lang('help_content2');
showtablefooter();
?>