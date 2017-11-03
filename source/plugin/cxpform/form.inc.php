<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
loadcache('plugin');include_once(dirname(__FILE__) . '/include/function.php');
	echo '<script type="text/javascript" src="source/plugin/cxpform/resource/js/jquery.js" language="javascript"></script><script type="text/javascript">var jq = jQuery.noConflict();var cxpbooks_serverurl = \'' . XF_PLUGIN_URL . 'pmod=form&op=upload&formhash=' . FORMHASH . '\';</script>';
	echo '<script type="text/javascript" src="source/plugin/cxpform/resource/js/My97DatePicker/WdatePicker.js" language="javascript"></script>';
	echo '<link rel="stylesheet" type="text/css" href="source/plugin/cxpform/resource/css/style.css" /><script type="text/javascript" charset="utf-8" src="source/plugin/cxpform/resource/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="source/plugin/cxpform/resource/ueditor/ueditor.all.min.js"> </script>';



$op = isset($_GET['op']) ? trim($_GET['op']) : '';
$op1 = isset($_GET['op1']) ? trim($_GET['op1']) : '';

switch($op){
	case 'add':
		// 添加
		if(!submitcheck('formsubmit')) {
			
			showtips(cxpform_lang('form_tip'));
			showformheader(XF_FORM_URL . 'pmod=form&op=add', 'formsubmit');
			showtableheader();
			// echo '<tr><td></td></tr>';
			showtitle(lang('plugin/cxpform', 'new_form'));
			
			showsetting(lang('plugin/cxpform', 'form_name'), 'title', '', 'text', '', 0, lang('plugin/cxpform', 'empty'), '', '', true);showsetting(lang('plugin/cxpform', 'form_type'), array('form_type', $cxpform_type), '', 'select', '', 0, '', '', '', true);
			// showsetting(lang('plugin/cxpform', 'form_desc'), 'description', '', 'textarea', '', 0, lang('plugin/cxpform', 'empty') . lang('plugin/cxpform', 'support_html'), '', '', true);
			
			echo '<tr><td colspan="15" class="td27" s="1">' . cxpform_lang('form_desc') . ':</td></tr>';
			echo '<tr><td colspan="15" class="rowform"><script type="text/plain" name="description" id="myEditor" style="width:100%;height:240px;"></script><script type="text/javascript">var ue = UE.getEditor(\'myEditor\');</script></td></tr>';
			
			showsetting(lang('plugin/cxpform', 'form_status'), 'status', 1, 'radio', '', 0, '', '', '', true);
			// showsetting(cxpform_lang('showresult'), 'showresult', 1, 'radio', '', 0, '', '', '', true);
			showsetting(lang('plugin/cxpform', 'startdate'), 'startdate', '', 'text', '', 0, '', 'onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});"', '', true);
			showsetting(lang('plugin/cxpform', 'enddate'), 'enddate', '', 'text', '', 0, '', 'onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});"', '', true);
			showsubmit('formsubmit');
			
			
			showtablefooter();
			showformfooter();		
		}else{
			$title = trim($_GET['title']);
			$form_type = intval(trim($_GET['form_type']));
			$description = trim($_GET['description']); // 用户需要编辑带html
			$status = intval(trim($_GET['status']));
			$startdate = trim($_GET['startdate']);
			$enddate = trim($_GET['enddate']);
			if(!$title){
				cpmsg(lang('plugin/cxpform', 'form_name') . lang('plugin/cxpform', 'empty'), '', 'error');
			}else{
				if(!$description){
					cpmsg(lang('plugin/cxpform', 'form_desc') . lang('plugin/cxpform', 'empty'), '', 'error');
				}
			}
			
			$data = array(
				'title' => $title,
				'form_type' => $form_type,
				'description' => $description,
				'status' => $status,
				'startdate' => strtotime($startdate),
				'enddate' => strtotime($enddate),
				'addtime' => $_SERVER['REQUEST_TIME'],
				'userid' => $_G['uid'],
				'username' => $_G['username'],
			);
			$new_form_id = DB::insert('cxpforms', $data, 1);
			
			// 初始化状态
			$data00 = array(
				'form_id' => $new_form_id,
				'status_name' => cxpform_lang('status00'),
				'status_color' => '#FF0000',
				'is_default' => 1,
			);
			DB::insert('cxpform_content_status', $data00);
			$data01 = array(
				'form_id' => $new_form_id,
				'status_name' => cxpform_lang('status01'),
				'status_color' => '#00FF00',
			);
			DB::insert('cxpform_content_status', $data01);
			
			// 初始化提交设置
			$data2 = array(
				'form_id' => $new_form_id,
				'limit_ip' => '',
				'ip_perday' => !empty($_G['cache']['plugin']['cxpform']['ip_perday']) ? $_G['cache']['plugin']['cxpform']['ip_perday'] : 5,
				'iscaptcha' => 1,
				'islogin' => 1,
				'user_perday' => !empty($_G['cache']['plugin']['cxpform']['user_perday']) ? $_G['cache']['plugin']['cxpform']['user_perday'] : 5,
			);
			DB::insert('cxpform_submit_setting', $data2);
			// 初始化提醒设置
			$data3 = array(
				'form_id' => $new_form_id,
				'status' => 0,
				'message_status' => 1,
				'message_to' => 1,
				'message_title' => lang('plugin/cxpform', 'message_notice_title'),
				'message_content' => lang('plugin/cxpform', 'notice_setting_example'),
			);
			DB::insert('cxpform_notice_setting', $data3);
			
			if($form_type == '3'){
				// 如果是预约表单，建立三个默认字段
				// $fielddata1 = array(
					// 'form_id' => $new_form_id,
					// 'field_label' => cxpform_lang('defaultfield1'),
					// 'field_desc' => cxpform_lang('defaultfield1'),
					// 'field_name' => 'name',
					// 'field_type' => 1,
					// 'select_num' => 0,
					// 'isrequired' => 1,
					// 'show_other' => 0,
					// 'inline' => 0,
					// 'addtime' => TIMESTAMP,
				// );
				// DB::insert('cxpform_fields', $fielddata1);
				// $fielddata2 = array(
					// 'form_id' => $new_form_id,
					// 'field_label' => cxpform_lang('defaultfield2'),
					// 'field_desc' => cxpform_lang('defaultfield2'),
					// 'field_name' => 'mobile',
					// 'field_type' => 8,
					// 'select_num' => 0,
					// 'isrequired' => 1,
					// 'show_other' => 0,
					// 'inline' => 0,
					// 'addtime' => TIMESTAMP,
				// );
				// DB::insert('cxpform_fields', $fielddata2);
				$fielddata3 = array(
					'form_id' => $new_form_id,
					'field_label' => cxpform_lang('defaultfield3'),
					'field_desc' => cxpform_lang('defaultfield3'),
					'field_name' => 'bookingtime',
					'field_type' => 12,
					'select_num' => 0,
					'isrequired' => 1,
					'show_other' => 0,
					'inline' => 0,
					'addtime' => TIMESTAMP,
					'is_editable' => 0,
				);
				DB::insert('cxpform_fields', $fielddata3);				
			}
			
			cpmsg(lang('plugin/cxpform', 'add_form_success'), XF_PLUGIN_URL . "pmod=form", 'succeed');
		}
		break;
	case 'edit':
		// 编辑
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if(!submitcheck('formsubmit')) {
			cxpform_form_subnav($id, 'edit');
			$forminfo = cxpform_form_info($id);
			showformheader(XF_FORM_URL . 'pmod=form&op=edit&id=' . $id, 'formsubmit');
			showtableheader();
			// echo '<tr><td>编辑表单</td></tr>';
			showtitle(lang('plugin/cxpform', 'edit_form'));
			showsetting(lang('plugin/cxpform', 'form_name'), 'title', $forminfo['title'], 'text', '', 0, lang('plugin/cxpform', 'empty'), '', '', true);
			showsetting(lang('plugin/cxpform', 'form_type'), array('form_type', $cxpform_type), $forminfo['form_type'], 'select', '', 0, '', '', '', true);
			// showsetting(lang('plugin/cxpform', 'form_desc'), 'description', $forminfo['description'], 'textarea', '', 0, lang('plugin/cxpform', 'empty') . lang('plugin/cxpform', 'support_html'), '', '', true);
			
			echo '<tr><td colspan="15" class="td27" s="1">' . cxpform_lang('form_desc') . ':</td></tr>';
			echo '<tr><td colspan="15" class="rowform"><script type="text/plain" name="description" id="myEditor" style="width:100%;height:240px;">' . $forminfo['description'] . '</script><script type="text/javascript">var ue = UE.getEditor(\'myEditor\');</script></td></tr>';
			
			showsetting(lang('plugin/cxpform', 'form_status'), 'status', $forminfo['status'] ? 1 : 0, 'radio', '', 0, '', '', '', true);
			showsetting(lang('plugin/cxpform', 'startdate'), 'startdate', $forminfo['startdate'] ? date('Y-m-d H:i:s', $forminfo['startdate']) : '', 'text', '', 0, '', 'onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});"', '', true);
			showsetting(lang('plugin/cxpform', 'enddate'), 'enddate', $forminfo['enddate'] ? date('Y-m-d H:i:s', $forminfo['enddate']) : '', 'text', '', 0, '', 'onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});"', '', true);
			showsubmit('formsubmit');
			
			showtablefooter();
			showformfooter();		
		}else{
			$title = trim($_GET['title']);
			$form_type = intval(trim($_GET['form_type']));
			$description = trim($_GET['description']); // 用户需要编辑带html
			$status = intval(trim($_GET['status']));
			$startdate = trim($_GET['startdate']);
			$enddate = trim($_GET['enddate']);		
			if(!$title){
				cpmsg(lang('plugin/cxpform', 'form_name') .lang('plugin/cxpform', 'empty'), '', 'error');
			}else{
				if(!$description){
					cpmsg(lang('plugin/cxpform', 'form_desc') . lang('plugin/cxpform', 'empty'), '', 'error');
				}
			}
			
			$data = array(
				'title' => $title,
				'form_type' => $form_type,
				'description' => $description,
				'status' => $status,
				'startdate' => strtotime($startdate),
				'enddate' => strtotime($enddate),
			);
			
			DB::update('cxpforms', $data, DB::field('id', $id));
			
			cpmsg(lang('plugin/cxpform', 'edit_form_success'), XF_PLUGIN_URL . "pmod=form", 'succeed');
		}
		break;
	case 'delete':
		$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
		if($formhash == formhash()){
			//删除 表单
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			DB::delete('cxpform_notice_setting', DB::field('form_id', $id));
			DB::delete('cxpform_submit_setting', DB::field('form_id', $id));
			DB::delete('cxpform_hits', DB::field('form_id', $id));
			DB::delete('cxpform_field_value', DB::field('form_id', $id));
			// 删除状态
			DB::delete('cxpform_content_status', DB::field('form_id', $id));
			// 删除处理
			DB::delete('cxpform_content_logs', DB::field('form_id', $id));
			// 删除表单内容
			DB::delete('cxpform_contents', DB::field('form_id', $id));
			// 删除表单内容扩展字段
			// for($i=0;$i<XF_EXTRA_TABLE_NUM;$i++){
				// DB::delete('cxpform_content_extra_' . $i, DB::field('form_id', $id));
			// }
			DB::delete('cxpform_content_extra', DB::field('form_id', $id));
			// 删除表单字段
			DB::delete('cxpform_fields', DB::field('form_id', $id));
			// 删除字段选项
			DB::delete('cxpform_field_options', DB::field('form_id', $id));
			// 删除表单
			DB::delete('cxpforms', DB::field('id', $id));
			cpmsg(lang('plugin/cxpform', 'delete_form_success'), XF_PLUGIN_URL . "pmod=form", 'succeed');
		}
		break;
	case 'upload':
		$action11 = isset($_GET['action11']) ? trim($_GET['action11']) : '';
		if($action11 == 'config'){
			$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(dirname(__FILE__) . "/resource/ueditor/php/config.json")), true);
			$result =  json_encode($CONFIG);
		}else{
			if(file_exists(libfile('class/upload'))){
				require_once libfile('class/upload');
			}else{
				require_once libfile('discuz/upload', 'class');
			}
			$upload = new discuz_upload();	
			if($_FILES['upfile']){
				if($upload->init($_FILES['upfile'], 'common') && $upload->save(1)){
					$image_url = $_G['setting']['attachurl'] . 'common/' . $upload->attach['attachment'];
				}			
			}else{
				$image_url = daddslashes(trim($_GET['upfile']));
			}
			if($image_url){
				$result = json_encode(array(
					'state' => 'SUCCESS',
					'url' => $image_url,
					'title' => '',
					'original' => ''
				));			
			}else{
				$result = json_encode(array(
					'state'=> cxpform_lang('error_3')
				));
			}
		}

		
		ob_end_clean();
		/* 输出结果 */
		if (isset($_GET["callback"])) {
			if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
				echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
			} else {
				echo json_encode(array(
					'state'=> cxpform_lang('error_4')
				));
			}
		} else {
			echo $result;
		}	
		define(FOOTERDISABLED, false);
		exit();
		break;		
	case 'form_fields':// 表单题目管理
		include_once(dirname(__FILE__) . '/form_field.php');
		break;
	case 'content_status': // 状态管理
		include_once(dirname(__FILE__) . '/content_status.php');
		break;
	case 'form_content':
		include_once(dirname(__FILE__) . '/form_content.inc.php');
		break;
	case 'charts': // 查看报表
		include_once(dirname(__FILE__) . '/charts.php');
		break;
	case 'hits':
		// 访问记录
		include_once(dirname(__FILE__) . '/hits.php');
		break;
	case 'submit_setting': // 提交设置
		include_once(dirname(__FILE__) . '/submit_setting.php');
		break;
	case 'more_content':
		include_once(dirname(__FILE__) . '/more_content.php');
		break;
	case 'notice_setting': // 提醒设置
		include_once(dirname(__FILE__) . '/notice_setting.php');
		break;
	case 'get_code': // 生成代码
		include_once(dirname(__FILE__) . '/code.php');
		break;
	default:
		
		// 表单列表
		$ppp = 10;
		$resultempty = FALSE;
		$page = max(1, intval($_GET['page']));
		echo '<a href="' . XF_PLUGIN_URL . 'pmod=form&op=add" class="addtr">' . lang('plugin/cxpform', 'new_form') . '</a>';
		showtips(cxpform_lang('form_tip'));
		$title = isset($_GET['title']) ? addslashes(trim($_GET['title'])) : '';
		$addtime1 = isset($_GET['addtime1']) ? addslashes(trim($_GET['addtime1'])) : '';
		$addtime2 = isset($_GET['addtime2']) ? addslashes(trim($_GET['addtime2'])) : '';
		$status = isset($_GET['status']) ? addslashes(trim($_GET['status'])) : '';
		$form_type = isset($_GET['form_type']) ? intval($_GET['form_type']) : '';
		
		$type_sel = '';
		foreach($cxpform_type_arr as $k1 => $type1){
			$type_sel .= '<option value="' . $k1 . '" ' . ($form_type == $k1 ? 'selected="selected"' : '') . '>' . $type1 . '</option>';
		}
		$type_sel = '<option value="">' . cxpform_lang('all') . '</option>' . $type_sel;

		showtableheader();
		showtitle('' . lang('plugin/cxpform', 'form_list') . '');
		showformheader(XF_FORM_URL . 'pmod=form', 'formsubmit');
		showsubmit('formsubmit', '' . lang('plugin/cxpform', 'search') . '', '' . lang('plugin/cxpform', 'form_name') . lang('plugin/cxpform', 'maohao') . '<input name="title" value="'.$title.'" class="txt" /> ' . cxpform_lang('form_type') . ':<select name="form_type">' . $type_sel . '</select>&nbsp;&nbsp;' . lang('plugin/cxpform', 'time') . lang('plugin/cxpform', 'maohao'). '<input name="addtime1" class="txt Wdate" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" value="' . $addtime1 . '" /> - <input type="text" class="txt Wdate" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" value="' . $addtime2 . '" name="addtime2" /> ' . lang('plugin/cxpform', 'form_status') .lang('plugin/cxpform', 'maohao'). '<select name="status"><option value="">' . lang('plugin/cxpform', 'all') . '</option><option value="1" ' .($status === '1' ? 'selected="selected"' : ''). '>' . lang('plugin/cxpform', 'opened') . '</option><option value="0" ' . ($status === '0' ? 'selected="selected"' : '') . '>' . lang('plugin/cxpform', 'closed') . '</option></select>', $searchtext);
		showformfooter();

		echo '<tr class="header">
			<th nowrap>' . lang('plugin/cxpform', 'id') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'form_name') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'form_type') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'username') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'addtime') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'form_status') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'hits_count') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'submit_count') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'latest_submit_time') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'op') . '</th>
			</tr>';
		
		
		showtagheader('tbody', '', true, '');
		
		$condstr = ' WHERE 1=1 ';
		$extrasearch = '';
		
		if($title){
			$condstr .= " AND A.title like " . DB::quote('%' . $title . '%');
			$extrasearch .= "&title=" . $title;
		}
		if($addtime1){
			$condstr .= " AND A.addtime>=" . strtotime($addtime1);
			$extrasearch .= "&addtime1=" . urlencode($addtime1);
		}
		if($addtime2){
			$condstr .= " AND A.addtime<=" . strtotime($addtime2);
			$extrasearch .= "&addtime2=" . urlencode($addtime2);
		}
		if($status !== ''){
			$condstr .= " AND A.status=" . DB::quote($status);
			$extrasearch .= "&status=" . $status;
		}
		if($form_type !== ''){
			$condstr .= " AND A.form_type=" . DB::quote($form_type);
			$extrasearch .= "&form_type=" . $form_type;
		}
		
		$count = DB::result_first('select count(A.id) from ' . DB::table('cxpforms') . ' A ' . $condstr);
		$forms = DB::fetch_all('SELECT A.*,COUNT(B.id) as ccount,MAX(B.addtime) as addtime1 FROM '.DB::table('cxpforms') . ' A LEFT JOIN ' . DB::table('cxpform_contents') . ' B on A.id=B.form_id ' . $condstr . ' group by A.id order by A.id desc limit ' . intval(($page-1)*$ppp) . ',' . $ppp);
		if($count > 0){
			foreach($forms as $form) {
				if($form['tid'] > 0){
					$previewlink = 'forum.php?mod=viewthread&tid=' . $form['tid'];
				}else{
					$previewlink = 'plugin.php?id=cxpform:style1&form_id=' . $form['id'];
				}
				echo '<tr class="hover"><td>' . $form['id'] . '</td>
				<td>' . $form['title'] . '</td>
				<td>' . $cxpform_type_arr[$form['form_type']] . ($form['tid'] > 0 ? '/' . cxpform_lang('is_thread') : '') . '</td>
				<td>' . $form['username'] . '</td>
				<td nowrap>' . date('Y-m-d H:i:s', $form['addtime']) . '</td>
				<td>' . ($form['status'] ? '<font color="#0D0">' . lang('plugin/cxpform', 'opened') . '</font>' : '<font color="red">' . lang('plugin/cxpform', 'closed') . '</font>') . '</td>
				<td>' . $form['hits'] . '</td>
				<td>' . $form['ccount'] . '</td>
				<td nowrap>' . ($form['addtime1'] == '' ? '-' : date('Y-m-d H:i:s', $form['addtime1'])) . '</td>
				<td nowrap>
					<a href="' . $previewlink . '" target="_blank">' . lang('plugin/cxpform', 'preview') . '</a> 
					<a href="' . XF_PLUGIN_URL . 'pmod=form&op=edit&id=' . $form['id'] . '">' . lang('plugin/cxpform', 'edit') . '</a> 
					<!--<a href="javascript:void(0);" onclick="alert(\'comming soon\');return false;">' . lang('plugin/cxpform', 'copy') . '</a>-->
					<a href="' . XF_PLUGIN_URL . 'pmod=form&op=delete&id=' . $form['id'] . '&formhash=' . FORMHASH . '" onclick="if(confirm(\'' . lang('plugin/cxpform', 'delconfirm') . '\')){return true;}else{return false;}">' . lang('plugin/cxpform', 'delete') . '</a> 
					<a id="form_design' . $form['id'] . '"  href="' .XF_PLUGIN_URL . 'pmod=form&op=submit_setting&form_id=' . $form['id'] . '">' . cxpform_lang('form_design') . '</a> 
					
					<a href="' .XF_PLUGIN_URL . 'pmod=form&op=form_content&form_id=' . $form['id'] . '">' . lang('plugin/cxpform', 'viewresult') . '</a> 
					
					<a href="' . XF_PLUGIN_URL . 'pmod=form&op=get_code&form_id=' . $form['id'] . '">' . lang('plugin/cxpform', 'get_code') . '</a>
				</td></tr>';
			}
		}else{
			echo '<tr><td colspan="9">' . lang('plugin/cxpform', 'nodata') . '</td></tr>';
		}
		showtagfooter('tbody');
		showtablefooter();	
		echo multi($count, $ppp, $page, XF_PLUGIN_URL."pmod=form".$extrasearch);
		break;
}
?>