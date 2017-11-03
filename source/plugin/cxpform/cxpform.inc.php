<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
loadcache('plugin');

if($_G['uid'] > 0){
}else{
	showmessage('', "", array(), array('showmsg' => 1, 'login' => 1));
}

include_once(DISCUZ_ROOT . 'source/function/function_misc.php');
include_once(dirname(__FILE__) . '/include/function.php');
$op = isset($_GET['op']) ? trim($_GET['op']) : '';
$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
$content_id = isset($_GET['content_id']) ? intval($_GET['content_id']) : 0;


if($op == 'count'){
	// 根据form_id知道表单提交内容的条数
	ob_end_clean();
	$ccount = DB::result_first("select count('id') from " . DB::table('cxpform_contents') . " where form_id=" . $form_id);
	echo $ccount;
	define(FOOTERDISABLED, false);
	exit();
}elseif($op == 'logs'){
	$results = DB::fetch_all('select * from ' . DB::table('cxpform_content_logs') . ' where form_id=' . $form_id . ' and content_id=' . $content_id . ' order by id desc');
	
	include template('cxpform:logs');	
}elseif($op == 'my_contents'){
	$navtitle = cxpform_lang('dashboard_nav2');
	// $perpage = 15;
	$perpage = !empty($_G['cache']['plugin']['cxpform']['front_perpage']) ? $_G['cache']['plugin']['cxpform']['front_perpage'] : 10;
	$page = intval($_G['gp_page']);
	$page = $page > 0 ? $page : 1;
	$start = !$pages ? 0 : ($page - 1) * $limit;
	$url = 'plugin.php?id=cxpform&op=my_contents';
	$condstr = ' where A.user_id=' . $_G['uid'];
	$count = DB::result_first('select count(A.id) from ' . DB::table('cxpform_contents') . ' A ' . $condstr);
	$my_history = DB::fetch_all('SELECT A.*, C.status_name, C.status_color, F.title, F.form_type FROM '.DB::table('cxpform_contents') . ' A left join ' . DB::table('cxpform_content_status') . ' C on (A.status = C.id and A.form_id = C.form_id) left join ' . DB::table('cxpforms') . ' F on A.form_id=F.id ' . $condstr . ' group by A.id order by A.id desc limit ' . intval(($page-1)*$perpage) . ',' . $perpage);

	$multi = multi($count, $perpage, $page, $url);	
	include template('cxpform:dashboard_my_contents');	
}elseif($op == 'contents'){	
	$fields =cxpform_field_list($form_id);
	$forminfo = cxpform_form_info($form_id);
	// 只能查看自己的
	if($forminfo['userid'] != $_G['uid']) showmessage(cxpform_lang('errformhash'), $_G['siteurl'] . 'plugin.php?id=cxpform');
	$navtitle = cxpform_lang('data_manage') . ' - ' . $forminfo['title'];
	// $perpage = 15;
	$perpage = !empty($_G['cache']['plugin']['cxpform']['front_perpage']) ? $_G['cache']['plugin']['cxpform']['front_perpage'] : 10;
	$page = intval($_G['gp_page']);
	$page = $page > 0 ? $page : 1;
	$start = !$pages ? 0 : ($page - 1) * $limit;
	$url = 'plugin.php?id=cxpform&form_id=' . $form_id . '&op=contents';
	$condstr = ' where A.form_id=' . $form_id;
	$count = DB::result_first('select count(A.id) from ' . DB::table('cxpform_contents') . ' A ' . $condstr);
	$my_history = DB::fetch_all('SELECT A.*, C.status_name, C.status_color FROM '.DB::table('cxpform_contents') . ' A left join ' . DB::table('cxpform_content_status') . ' C on (A.status = C.id and A.form_id = C.form_id) ' . $condstr . ' group by A.id order by A.id desc limit ' . intval(($page-1)*$perpage) . ',' . $perpage);

	$multi = multi($count, $perpage, $page, $url);	
	include template('cxpform:dashboard_contents');	
}elseif($op == 'charts'){
	$forminfo = cxpform_form_info($form_id);
	if($forminfo['userid'] != $_G['uid']) showmessage(cxpform_lang('errformhash'), $_G['siteurl'] . 'plugin.php?id=cxpform');
	$navtitle =  cxpform_lang('viewchart') . ' - ' . $forminfo['title'];
	
	$st = isset($_GET['st']) ? addslashes(trim($_GET['st'])) : '';
	$et = isset($_GET['et']) ? addslashes(trim($_GET['et'])) : '';
	$t = isset($_GET['t']) ? addslashes(trim($_GET['t'])) : '';

	if($st == ''){
		$st = dgmdate(TIMESTAMP, 'Y-m-d 00:00:00');
	}
	if($et == ''){
		$et = dgmdate(TIMESTAMP, 'Y-m-d 23:59:59');
	}

	if($t == ''){
		$t = 1;
	}

	$searchurl = 'plugin.php?id=cxpform&form_id=' . $form_id . '&op=charts';

$searchform = '
	<a href="' . $searchurl . '&st=' . urlencode(dgmdate(TIMESTAMP, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(TIMESTAMP, 'Y-m-d 23:59:59')) . '&t=1" ' . ($t=='1' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'today') . '</a> | 
	
	<a href="' . $searchurl . '&st=' . urlencode(dgmdate(TIMESTAMP - 86400 * 1, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(TIMESTAMP - 86400 * 1, 'Y-m-d 23:59:59')) . '&t=2" ' . ($t=='2' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'yesterday') . '</a> | 
	
	<a href="' . $searchurl . '&st=' . urlencode(dgmdate(TIMESTAMP - 86400 * 6, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(TIMESTAMP, 'Y-m-d 23:59:59')) . '&t=3" ' . ($t=='3' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'last7days') . '</a> | 
	
	<a href="' . $searchurl . '&st=' . urlencode(dgmdate(TIMESTAMP - 86400 * 29, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(TIMESTAMP, 'Y-m-d 23:59:59')) . '&t=4" ' . ($t=='4' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'last30days') . '</a> |
	
	<a href="' . $searchurl . '&st=' . urlencode(date('Y-m-01 00:00:00')) . '&et=' . urlencode(date('Y-m-' . cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) . ' 23:59:59')) . '&t=5" ' . ($t=='5' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'this_month') . '</a> ';
	
	switch($t){
		case '3':
			$searchform .= '<input type="button" value="' . lang('plugin/cxpform', '7daysbefore') . '" class="btn" onclick="location.href=\'' . $searchurl . '&st=' . urlencode(dgmdate(dmktime($st) - 86400 * 7, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) - 86400 * 7, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> 
	
	<input type="button" value="' . lang('plugin/cxpform', '7daysafter') . '" class="btn" onclick="location.href=\'' . $searchurl . '&st=' . urlencode(dgmdate(dmktime($st) + 86400 * 7, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) + 86400 * 7, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> ';
			break;
		case '4':
			// 前30天
			
			$searchform .= '<input type="button" value="' . lang('plugin/cxpform', '30daysbefore') . '" class="btn" onclick="location.href=\'' . $searchurl . '&st=' . urlencode(dgmdate(dmktime($st) - 86400 * 30, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) - 86400 * 30, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> 
	
	<input type="button" value="' . lang('plugin/cxpform', '30daysafter') . '" class="btn" onclick="location.href=\'' . $searchurl . '&st=' . urlencode(dgmdate(dmktime($st) + 86400 * 30, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) + 86400 * 30, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> ';
			break;
		case '5':
			// 上一个月
			$thismonth = date('m', dmktime($st));
			$thisyear = date('Y', dmktime($st));
			if($thismonth == 1) {
				$lastmonth = 12;
				$lastyear = $thisyear - 1;
			} else {
				$lastmonth = $thismonth - 1;
				$lastyear = $thisyear;
			}
			if($thismonth == 12){
				$nextmonth = 1;
				$nextyear = $thisyear + 1;
			}else{
				$nextmonth = $thismonth + 1;
				$nextyear = $thisyear;
			}
			$last_month_m = $lastmonth;
			$last_month_y = $lastyear;
			$next_month_m = $nextmonth;
			$next_month_y = $nextyear;
			
			$searchform .= '<input type="button" value="' . lang('plugin/cxpform', 'last_month') . '" class="btn" onclick="location.href=\'' . $searchurl . '&st=' . urlencode(date($last_month_y . '-' . $last_month_m . '-01 00:00:00')) . '&et=' . urlencode(date($last_month_y . '-' . $last_month_m . '-' . cal_days_in_month(CAL_GREGORIAN, $last_month_m, $last_month_y) . ' 23:59:59')) . '&t=' . $t . '\';" /> 
	
	<input type="button" value="' . lang('plugin/cxpform', 'next_month') . '" class="btn" onclick="location.href=\'' . $searchurl . '&st=' . urlencode(date($next_month_y . '-' . $next_month_m . '-01 00:00:00')) . '&et=' . urlencode(date($next_month_y . '-' . $next_month_m . '-' . cal_days_in_month(CAL_GREGORIAN, $next_month_m, $next_month_y) . ' 23:59:59')) . '&t=' . $t . '\';" /> ';
			break;
		case '6':
			break;
		case '1':
		case '2':
		default:
			$searchform .= '<input type="button" value="' . lang('plugin/cxpform', 'one_day') . '" class="btn" onclick="location.href=\'' . $searchurl . '&st=' . urlencode(dgmdate(dmktime($st) - 86400 * 1, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) - 86400 * 1, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> 
	
	<input type="button" value="' . lang('plugin/cxpform', 'next_day') . '" class="btn" onclick="location.href=\'' . $searchurl . '&st=' . urlencode(dgmdate(dmktime($st) + 86400 * 1, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) + 86400 * 1, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> ';
			break;
	}
	
	
	
	$searchform .= '<input type="checkbox" name="t" value="6" ' . ($t == '6' ? 'checked="checked"' : '') . ' />' . lang('plugin/cxpform', 'custime') .lang('plugin/cxpform', 'maohao') .'<input type="text" class="Wdate txt" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" name="st" value="' . $st . '" /> <input type="text" class="Wdate txt" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" name="et" value="' . $et . '" />';	
	
	
	$condstr = " WHERE form_id='" . $form_id . "' and addtime>=UNIX_TIMESTAMP(" . DB::quote($st) . ") and addtime<=UNIX_TIMESTAMP(" . DB::quote($et) . ") ";
	
	$dates = cxpform_prDates($st, $et);
	

	$results2 = DB::fetch_all("select FROM_UNIXTIME(addtime, '%Y-%m-%d') as d, count(id) as ccount from " . DB::table('cxpform_contents') . $condstr . " group by d");

	
	foreach($results2 as $row2){
		$results2[$row2['d']] = $row2['ccount'];
	}	
	
	$category_str = '';
	$hits_str = '';
	$submit_str = '';

	foreach($dates as $date){
		$category_str .= "'". $date . "',";

		if(array_key_exists($date, $results2)){
			$submit_str .= $results2[$date] . ',';
		}else{
			$submit_str .= '0,';
		}
	}
	$category_str = rtrim($category_str, ',');

	$submit_str = rtrim($submit_str, ',');	
	

	include template('cxpform:dashboard_charts');			
}else{
	$navtitle = cxpform_lang('dashboard_nav1');
	// $perpage = 10;
	$perpage = !empty($_G['cache']['plugin']['cxpform']['front_perpage']) ? $_G['cache']['plugin']['cxpform']['front_perpage'] : 10;
	$page = intval($_G['gp_page']);
	$page = $page > 0 ? $page : 1;
	$start = !$pages ? 0 : ($page - 1) * $limit;
	$url = 'plugin.php?id=cxpform';
	$condstr = ' where A.userid=' . $_G['uid'];
	
	$count = DB::result_first('select count(A.id) from ' . DB::table('cxpforms') . ' A ' . $condstr);
	$forms = DB::fetch_all('SELECT A.*,COUNT(B.id) as ccount,MAX(B.addtime) as addtime1 FROM '.DB::table('cxpforms') . ' A LEFT JOIN ' . DB::table('cxpform_contents') . ' B on A.id=B.form_id ' . $condstr . ' group by A.id order by A.id desc limit ' . intval(($page-1)*$perpage) . ',' . $perpage);

	$multi = multi($count, $perpage, $page, $url);	
	include template('cxpform:dashboard');	
}
// ==================================================================================
?>