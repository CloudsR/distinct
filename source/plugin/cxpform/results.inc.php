<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
loadcache('plugin');

include_once(DISCUZ_ROOT . 'source/function/function_misc.php');
include_once(dirname(__FILE__) . '/include/function.php');
$op = isset($_GET['op']) ? trim($_GET['op']) : '';
$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
$content_id = isset($_GET['content_id']) ? intval($_GET['content_id']) : 0;

$forminfo = cxpform_form_info($form_id);

if($op == 'logs'){
	$results = DB::fetch_all('select * from ' . DB::table('cxpform_content_logs') . ' where form_id=' . $form_id . ' and content_id=' . $content_id . ' order by id desc');
	$count = count($results);
	include template('cxpform:logs');
}else{
	
	// $perpage = 5;
	$perpage = !empty($_G['cache']['plugin']['cxpform']['front_perpage']) ? $_G['cache']['plugin']['cxpform']['front_perpage'] : 10;
	$page = intval($_G['gp_page']);
	$page = $page > 0 ? $page : 1;
	$start = !$pages ? 0 : ($page - 1) * $limit;
	$url = 'plugin.php?id=cxpform:results&form_id=' . $form_id;
	$condstr = ' where A.user_id=' . $_G['uid'] . ' AND A.form_id=' . $form_id;
	$count = DB::result_first('select count(A.id) from ' . DB::table('cxpform_contents') . ' A ' . $condstr);
	$my_history = DB::fetch_all('SELECT A.*, C.status_name, C.status_color FROM '.DB::table('cxpform_contents') . ' A left join ' . DB::table('cxpform_content_status') . ' C on (A.status = C.id and A.form_id = C.form_id) ' . $condstr . ' group by A.id order by A.id desc limit ' . intval(($page-1)*$perpage) . ',' . $perpage);

	$multi = multi($count, $perpage, $page, $url);	
	include template('cxpform:results');	
}
// ==================================================================================
?>