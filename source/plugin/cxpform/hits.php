<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}


echo '<script type="text/javascript" src="source/plugin/cxpform/resource/js/jquery.js" language="javascript"></script><script type="text/javascript">var jq = jQuery.noConflict();</script>';
echo '<script type="text/javascript" src="source/plugin/cxpform/resource/js/My97DatePicker/WdatePicker.js" language="javascript"></script>';
echo '<link rel="stylesheet" type="text/css" href="source/plugin/cxpform/resource/css/style.css" /><script type="text/javascript">  function selectAll(elename){
    var chkobj = document.getElementsByName(elename);
    if(chkobj!=null){
		for(i=0;i<chkobj.length;i++){
			if(chkobj[i].checked==true){
				chkobj[i].checked=false;
				
			}else{
				chkobj[i].checked=true;
			}
		}
    } 
}</script>';

include_once(dirname(__FILE__) . '/include/function.php');

$op1 = isset($_GET['op1']) ? trim($_GET['op1']) : '';
$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
if($op1 == 'delete'){
	$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
	if($formhash == formhash()){
		//删除 表单
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$delid = isset($_GET['delid']) ? $_GET['delid'] : 0;
		if($delid != 0){
			$id = $delid;
		}
		DB::delete('cxpform_hits', DB::field('id', $id));
		cpmsg(lang('plugin/cxpform', 'delete_hits_success'), XF_PLUGIN_URL . "pmod=form&op=hits&form_id=" . $form_id, 'succeed');
	}
	
}else{
	
	// 内容列表
	cxpform_form_subnav($form_id, 'hits');
	
	
	$addtime1 = isset($_GET['addtime1']) ? addslashes(trim($_GET['addtime1'])) : '';
	$addtime2 = isset($_GET['addtime2']) ? addslashes(trim($_GET['addtime2'])) : '';
	$ip = isset($_GET['ip']) ? addslashes(trim($_GET['ip'])) : '';
	$area = isset($_GET['area']) ? addslashes(trim($_GET['area'])) : '';
	$username = isset($_GET['username']) ? addslashes(trim($_GET['username'])) : '';
	
	
	$ppp = 10;
	$resultempty = FALSE;
	$page = max(1, intval($_GET['page']));

	showtableheader();
	showtitle(lang('plugin/cxpform', 'hitshistory'));
	showformheader(XF_FORM_URL. 'pmod=form&op=hits&form_id=' . $form_id, 'formsubmit');
	
	
	
	
	$selectform = '';
	$formarr = cxpform_form_list();
	foreach($formarr as $fk=>$fv){
		$selectform .= '<option value="' . $fk . '" ' . ($form_id == $fk ? 'selected="selected"' : '') . '>' . $fv['title'] . '</option>';
	}
	
	$selectform = '<option value="">' . lang('plugin/cxpform', 'all') . '</option>' . $selectform;
	
	showsubmit('formsubmit', lang('plugin/cxpform', 'search'), lang('plugin/cxpform', 'belong_form') . lang('plugin/cxpform', 'maohao').'<select name="form_id">' . $selectform . '</select>&nbsp;&nbsp;' . lang('plugin/cxpform', 'time') .lang('plugin/cxpform', 'maohao'). '<input name="addtime1" class="txt Wdate" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" value="' . $addtime1 . '" /> - <input type="text" class="txt Wdate" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" value="' . $addtime2 . '" name="addtime2" />&nbsp;&nbsp;IP'.lang('plugin/cxpform', 'maohao').'<input type="text" name="ip" value="' . $ip . '" class="txt" />  ' . lang('plugin/cxpform', 'area') . lang('plugin/cxpform', 'maohao').'<input type="text" name="area" value="' . $area . '" class="txt" />  ' . lang('plugin/cxpform', 'username') .lang('plugin/cxpform', 'maohao'). '<input type="text" name="username" value="' . $username . '" class="txt" />', $searchtext);
	showformfooter();

	echo '<tr class="header">
		<th class="td25"><input type="checkbox" onclick="selectAll(\'delid[]\')" /></th>
		<th>' . lang('plugin/cxpform', 'belong_form') . '</th>
		<th>' . lang('plugin/cxpform', 'time') . '</th>
		<th>IP</th>
		<th>' . lang('plugin/cxpform', 'area') . '</th>
		<th>' . lang('plugin/cxpform', 'fromurl') . '</th>
		<th>' . lang('plugin/cxpform', 'username') . '</th>
		<th>' . lang('plugin/cxpform', 'op') . '</th>
		</tr>';
	
	// showtablerow('class="header"', '', array('编号', '表单名称'));
	showtagheader('tbody', '', true, '');
	
	$condstr = ' WHERE 1=1';
	$extrasearch = '';
	
	if($form_id){
		$condstr .= " AND A.form_id=" . DB::quote($form_id);
		$extrasearch .= '&form_id=' . $form_id;
	}
	if($addtime1){
		$condstr .= " AND A.addtime>=" . strtotime($addtime1);
		$extrasearch .= '&addtime1=' . urlencode($addtime1);
	}
	if($addtime2){
		$condstr .= " AND A.addtime<=" . strtotime($addtime2);
		$extrasearch .= '&addtime2=' . urlencode($addtime2);
	}
	if($ip){
		$condstr .= " AND A.ip=" . DB::quote($ip);
		$extrasearch .= '&ip=' . $ip;
	}
	if($area){
		$condstr .= " AND A.area like " . DB::quote('%' . $area . '%');
		$extrasearch .= '&area=' . $area;
	}
	if($username){
		$condstr .= " AND A.username like " . DB::quote('%' . $username . '%');
		$extrasearch .= '&username=' . $username;
	}
	
	
	$count = DB::result_first('select count(A.id) from ' . DB::table('cxpform_hits') . ' A ' . $condstr);
	
	$contents = DB::fetch_all('SELECT A.*, B.title FROM '.DB::table('cxpform_hits') . ' A left join ' . DB::table('cxpforms') . ' B on A.form_id=B.id ' . $condstr . ' order by A.id desc limit ' . intval(($page-1)*$ppp) . ',' . $ppp);		
	
	if($count > 0){
		foreach($contents as $content) {
			echo '<tr class="hover">
				<td><input type="checkbox" name="delid[]" value="' . $content['id'] . '" /></td>
				<td>' . $content['title'] . '</td>
				<td>' . date('Y-m-d H:i:s', $content['addtime']) . '</td>
				<td>' . $content['ip'] . '</td>
				<td>' . $content['area'] . '</td>
				<td>' . $content['fromurl'] . '</td>
				<td>' . $content['username'] . '</td>
				<td><a href="' . XF_PLUGIN_URL . 'pmod=form&op=hits&form_id=' . $content['form_id'] . '&op1=delete&id=' . $content['id'] . '&formhash=' . FORMHASH . '" onclick="if(confirm(\'' . lang('plugin/cxpform', 'delconfirm') . '\')){return true;}else{return false;}">' . lang('plugin/cxpform', 'delete') . '</a></td>
				</tr>';
		}
	}else{
		echo '<tr><td colspan="8">' . lang('plugin/cxpform', 'nodata') . '</td></tr>';
	}
	showtagfooter('tbody');
	showtablefooter();	
	echo multi($count, $ppp, $page, XF_PLUGIN_URL . "pmod=form&op=hits" . $extrasearch);
}
?>