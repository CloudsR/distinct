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
if($op1 == 'add_data'){
	if($form_id != ''){
		cxpform_form_subnav($form_id, 'form_content');
	}
	if(!submitcheck('formsubmit')) {
		showformheader(XF_FORM_URL . 'pmod=form&op=form_content&op1=add_data&form_id=' . $form_id, 'formsubmit');
		showtips(cxpform_lang('data_tip'));
		showtableheader();
		showtitle(cxpform_lang('add_data'));
		
		// 字段列表
		$fields = cxpform_field_list($form_id);
		foreach($fields as $field){
			echo cxpform_render_cp_field($field);
		}
		
		showsubmit('formsubmit');
		showtablefooter();
		showformfooter();		
	}else{
		// 添加主内容
		$data1 = array(
			'form_id' => $form_id,
			'addtime' => $_SERVER['REQUEST_TIME'],
		);
		$new_content_id = DB::insert('cxpform_contents', $data1, 1);
		
		// 字段内容
		$fields = cxpform_field_list($form_id);
		foreach($fields as $field){
			$data2 = array(
				'form_id' => $form_id,
				'content_id' => $new_content_id,
				'field_id' => $field['id'],
				'field_value' => addslashes(trim($_GET['field_' . $field['id']])),
			);
			$extra_table_name = cxpform_get_hash_table('cxpform_content_extra', $new_content_id, XF_EXTRA_TABLE_NUM);
			DB::insert($extra_table_name, $data2);
		}

		cpmsg(lang('plugin/cxpform', 'add_data_success'), XF_PLUGIN_URL . "pmod=form&op=form_content&form_id=" . $form_id, 'succeed');
	}	
}elseif($op1 == 'edit_data'){
	$content_id = isset($_GET['content_id']) ? intval($_GET['content_id']) : 0;
	
	if($form_id != ''){
		cxpform_form_subnav($form_id, 'form_content');
	}
	if(!submitcheck('formsubmit')) {
		showformheader(XF_FORM_URL . 'pmod=form&op=form_content&op1=edit_data&form_id=' . $form_id . '&content_id=' . $content_id, 'formsubmit');
		
		showtableheader();
		showtitle(cxpform_lang('edit_data'));
		$content_extra = DB::fetch_all('select A.* from ' . DB::table('cxpform_content_extra') . ' as A where A.content_id=' . $content_id);
		$content_extra_arr = array();
		foreach($content_extra as $cextra){
			$content_extra_arr[$cextra['field_id']] = $cextra['field_value'];
		}
		// 字段列表
		$fields = cxpform_field_list($form_id);
		// if(count($fields) > 0){
		foreach($fields as $field){
			echo cxpform_render_cp_field($field, $content_extra_arr[$field['id']]);
		}
		
		showsubmit('formsubmit');
		showtablefooter();
		showformfooter();		
	}else{

		// 字段内容
		
		foreach($_GET as $k1 => $v1){
			if(strpos($k1, 'field_') === FALSE) continue;
			$field_id = substr($k1, 6);
			$data2 = array(
				'field_value' => addslashes(trim($v1)),
			);
			$data3 = array(
				'form_id' => $form_id,
				'content_id' => $content_id,
				'field_id' => $field_id,			
			);
			$extra_table_name = cxpform_get_hash_table('cxpform_content_extra', $content_id, XF_EXTRA_TABLE_NUM);
			DB::update($extra_table_name, $data2, $data3);			
		}

		cpmsg(lang('plugin/cxpform', 'edit_data_success'), XF_PLUGIN_URL . "pmod=form&op=form_content&form_id=" . $form_id, 'succeed');
	}	
}elseif($op1 == 'import_data'){ // 导入数据
	if($form_id != ''){
		cxpform_form_subnav($form_id, 'form_content');
	}
	if(!submitcheck('formsubmit')) {
		showformheader(XF_FORM_URL . 'pmod=form&op=form_content&op1=import_data&form_id=' . $form_id, 'enctype');
		showtips(cxpform_lang('data_tip'));
		showtableheader();
		
		showtitle(cxpform_lang('import_data'));
		$fields = cxpform_field_list($form_id);
		$ii = 0;
		if(count($fields) > 0){
			foreach($fields as $field){		
				showsetting($field['field_label'] . ' ' . cxpform_lang('field_column'), 'column_' . $field['id'], $ii, 'text', '', 0, cxpform_lang('empty'), '', '', true);
				$ii++;
			}
			
			// 字段列表
			showsetting(cxpform_lang('upfile'), 'file', '', 'file', '', 0, cxpform_lang('empty') .' '. cxpform_lang('file_tip'), '', '', true);
		}
		
		showsubmit('formsubmit');
		showtablefooter();
		showformfooter();		
	}else{
	
		$filename = $_FILES['file']['tmp_name']; 
		
		if (empty ($filename)) { 
			cpmsg(cxpform_lang('import_data_error1'), XF_PLUGIN_URL . "pmod=form&op=form_content&form_id=" . $form_id, 'error');
			exit; 
		} 
		// Validate the file type
		$fileTypes = array('csv', 'txt'); // File extensions
		$fileParts = pathinfo($_FILES['file']['name']);
		
		if (in_array($fileParts['extension'],$fileTypes)) {		
			
			$handle = fopen($filename, 'r'); 
			$result = cxpform_input_csv($handle); //解析csv 
			$len_result = count($result); 
			if($len_result==0){ 
				cpmsg(cxpform_lang('import_data_error2'), XF_PLUGIN_URL . "pmod=form&op=form_content&form_id=" . $form_id, 'error');
				exit; 
			}
			fclose($handle);
			$field_id_arr = array();
			foreach($_GET as $k1=>$v1){
				if(strpos($k1, 'column_') === FALSE) continue;
				$field_id = substr($k1, 7);
				$field_id_arr[$field_id] = $v1; 
			}
			for ($i = 0; $i < $len_result; $i++) {
				// 添加主内容
				$data1 = array(
					'form_id' => $form_id,
					'addtime' => $_SERVER['REQUEST_TIME'],
				);
				
				$new_content_id = DB::insert('cxpform_contents', $data1, 1);		
				$data_values = '';
				foreach($field_id_arr as $k11 => $v11){
					$data_values .= '(' . $form_id . ',' . $new_content_id . ',' . $k11 . ',' . DB::quote($result[$i][$v11]) . '),';
				}
				$data_values = rtrim($data_values, ',');
				if($data_values != ''){
					$extra_table_name = cxpform_get_hash_table('cxpform_content_extra', $new_content_id, XF_EXTRA_TABLE_NUM);
					DB::query("insert into " . DB::table($extra_table_name) . "(form_id, content_id, field_id, field_value) values $data_values");
				}
			} 
			cpmsg(cxpform_lang('import_data_success'), XF_PLUGIN_URL . "pmod=form&op=form_content&form_id=" . $form_id, 'succeed');
		
		}else{
			cpmsg(cxpform_lang('import_data_error3'), XF_PLUGIN_URL . "pmod=form&op=form_content&form_id=" . $form_id, 'error');
		}

	}
}elseif($op1 == 'delete'){
	$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
	if($formhash == formhash()){
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
		$delid = isset($_GET['delid']) ? $_GET['delid'] : 0;
		if($delid != 0){
			$id = $delid;
		}
		DB::delete('cxpform_content_logs', DB::field('content_id', $id));
		DB::delete('cxpform_field_value', DB::field('content_id', $id));
		$extra_table_name = cxpform_get_hash_table('cxpform_content_extra', $id, XF_EXTRA_TABLE_NUM);
		DB::delete($extra_table_name, DB::field('content_id', $id));
		DB::delete('cxpform_contents', DB::field('id', $id));

		cpmsg(lang('plugin/cxpform', 'delete_content_success'), XF_PLUGIN_URL . "pmod=form&op=form_content&form_id=" . $form_id, 'succeed');	
	}
}elseif($op1 == 'handle_content'){
	// 处理内容
	$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
	$content_id = isset($_GET['content_id']) ? intval($_GET['content_id']) : 0;
	
	
	
	if($form_id != ''){
		cxpform_form_subnav($form_id, 'form_content');
	}
	// 处理记录
	showtableheader();
	showtitle(lang('plugin/cxpform', 'results'));
	echo '<tr class="header">
		<th class="td25">#</th>
		<th nowrap>' . cxpform_lang('log_status') . '</th>
		<th nowrap>' . cxpform_lang('log_content') . '</th>
		<th nowrap>' . cxpform_lang('log_time') . '</th>
		<th nowrap>' . cxpform_lang('log_user') . '</th>
		</tr>';
		
	$results = DB::fetch_all('select * from ' . DB::table('cxpform_content_logs') . ' where form_id=' . $form_id . ' and content_id=' . $content_id . ' order by id desc');
	
	foreach($results as $row){
		echo '<tr>
			<td>' . $row['admin_id'] . '</td>
			<td>' . $row['status_log'] . '</td>
			<td>' . $row['content'] . '</td>
			<td>' . date('Y-m-d H:i:s', $row['addtime']) . '</td>
			<td>' . $row['username'] . '</td>
		</tr>';
	}
		
	showtablefooter();
	
	$content_info = cxpform_content_info($content_id);
	
	$content_status = cxpform_content_status_list($form_id);
	$arr1 = array();
	foreach($content_status as $k1 => $status1){
		$arr1[$k1][0] = $status1['id'];
		$arr1[$k1][1] = $status1['status_name'];
	}
	
	// 处理提交表单
	if(!submitcheck('formsubmit')) {
		showformheader(XF_FORM_URL . 'pmod=form&op=form_content&op1=handle_content&form_id=' . $form_id . '&content_id=' . $content_id, 'formsubmit');
		showtableheader();
		// echo '<tr><td></td></tr>';
		showtitle(lang('plugin/cxpform', 'handle'));
		showsetting(cxpform_lang('log_status'), array('status', $arr1), $content_info['status'], 'select', '', 0, lang('plugin/cxpform', 'empty'), '', '', true);
		showsetting(cxpform_lang('log_content'), 'content', '', 'textarea', '', 0, lang('plugin/cxpform', 'empty'), '', '', true);
		
		showsubmit('formsubmit');
		
		showtablefooter();
		showformfooter();	
			
	}else{
		$status = intval(trim($_GET['status']));
		$content = addslashes(trim($_GET['content']));
		if(!$content){
			cpmsg(lang('plugin/cxpform', 'log_content') . lang('plugin/cxpform', 'empty'), '', 'error');
		}else{
		}
		
		$status_log = '<font color="' . $content_status[$content_info['status']]['status_color'] . '">' . $content_status[$content_info['status']]['status_name'] . '</font> => <font color="' . $content_status[$status]['status_color'] . '">' . $content_status[$status]['status_name'] . '</font>';
		
		$data = array(
			'form_id' => $form_id,
			'content_id' => $content_id,
			'status_log' => $status_log,
			'content' => $content,
			'addtime' => $_SERVER['REQUEST_TIME'],
			'admin_id' => $_G['uid'],
			'username' => $_G['username'],
		);
		
		DB::insert('cxpform_content_logs', $data, 1);
		
		$data1 = array(
			'status' => $status
		);
		DB::update('cxpform_contents', $data1, DB::field('id', $content_id));
		
		// 通知用户
		$forminfo = cxpform_form_info($form_id);
		notification_add($content_info['user_id'], 'app', sprintf(cxpform_lang('result_content'), $form_id, $forminfo['title']));
		
		require_once libfile('function/mail');
		$email_title = sprintf(cxpform_lang('result_notice_title'), $forminfo['title']);
		$email_content = sprintf(cxpform_lang('result_notice_content'), $form_id);
		$contentinfo = cxpform_content_info($content_id);
		$user = getuserbyuid($contentinfo['user_id'], 1);
		sendmail($user['email'], $email_title, $email_content);
		
		cpmsg(lang('plugin/cxpform', 'add_log_success'), XF_PLUGIN_URL . "pmod=form&op=form_content&op1=handle_content&form_id=" . $form_id . "&content_id=" . $content_id, 'succeed');
	}	
}elseif($op1== 'export'){
	$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
	if($formhash == formhash()){
		ob_end_clean();
		//ob_clean();
		
		$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
		$addtime1 = isset($_GET['addtime1']) ? addslashes(trim($_GET['addtime1'])) : '';
		$addtime2 = isset($_GET['addtime2']) ? addslashes(trim($_GET['addtime2'])) : '';
		$ip = isset($_GET['ip']) ? addslashes(trim($_GET['ip'])) : '';
		$area = isset($_GET['area']) ? addslashes(trim($_GET['area'])) : '';
		$username = isset($_GET['username']) ? addslashes(trim($_GET['username'])) : '';
		
		$bookingtime1 = isset($_GET['bookingtime1']) ? addslashes(trim($_GET['bookingtime1'])) : '';
		$bookingtime2 = isset($_GET['bookingtime2']) ? addslashes(trim($_GET['bookingtime2'])) : '';
		$bookingnumber = isset($_GET['bookingnumber']) ? addslashes(trim($_GET['bookingnumber'])) : '';		
		
		$forminfo = cxpform_form_info($form_id);
	

		$condstr = ' WHERE 1=1';
		$extrasearch = '';
		$arg = array();
		if($form_id){
			$condstr .= " AND A.form_id = " . DB::quote($form_id);
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
		if($bookingtime1){
			$condstr .= " AND A.bookingtime>=" . strtotime($bookingtime1);
			$extrasearch .= '&bookingtime1=' . urlencode($bookingtime1);
		}
		if($bookingtime2){
			$condstr .= " AND A.bookingtime<=" . strtotime($bookingtime2);
			$extrasearch .= '&bookingtime2=' . urlencode($bookingtime2);
		}			
		if($ip){
			$condstr .= " AND A.ip=" . DB::quote($ip);
			$extrasearch .= '&ip=' . $ip;
			// $arg[] = $ip;
		}
		if($area){
			$condstr .= " AND A.area like " . DB::quote('%' . $area . '%');
			$extrasearch .= '&area=' . $area;
		}
		if($username){
			$condstr .= " AND A.username like " . DB::quote('%' . $username . '%');
			$extrasearch .= '&username=' . $username;
		}
		if($bookingnumber){
			$condstr .= " AND A.bookingnumber like " . DB::quote('%' . $bookingnumber . '%');
			$extrasearch .= '&bookingnumber=' . $bookingnumber;
		}


		
		$count = DB::result_first("select count(A.id) from " . DB::table('cxpform_contents') . " A " . $condstr);
		
		$contents = DB::fetch_all("SELECT A.*, B.title, C.status_name, C.status_color FROM ".DB::table('cxpform_contents') . " A left join " . DB::table('cxpforms') . " B on A.form_id=B.id left join " . DB::table('cxpform_content_status') . " C on (A.status = C.id and A.form_id = C.form_id) " . $condstr . " order by A.id desc");		
		$fields = cxpform_field_list($form_id);
		$file_str="";
		
		
		include(dirname(__FILE__) . '/include/ExcelWriterXML/ExcelWriterXML.php');
		$xml = new ExcelWriterXML("cxpformcontent" . TIMESTAMP . ".xls");
		$xml->docAuthor('cxpcms.com');
		
		$format = $xml->addStyle('StyleHeader');
		$format->alignHorizontal('Center');
		
		$format = $xml->addStyle('StyleBold');
		$format->fontBold();
		
		$sheet = $xml->addSheet('data');
		
		$tempheader = '';
		foreach($fields as $field){
			$tempheader .= cxpform_csv_charset($field['field_label']) . ',';
		}
		$tempheader = rtrim($tempheader, ',');
		
		if($forminfo['form_type'] == '3'){
		
			$sheet->writeString(1,1,cxpform_csv_charset(lang('plugin/cxpform', 'belong_form')));
			$sheet->writeString(1,2,cxpform_csv_charset(lang('plugin/cxpform', 'content_addtime')));
			$sheet->writeString(1,3,'IP');
			$sheet->writeString(1,4,cxpform_csv_charset(lang('plugin/cxpform', 'area')));
			$sheet->writeString(1,5,cxpform_csv_charset(lang('plugin/cxpform', 'fromurl')));
			$sheet->writeString(1,6,cxpform_csv_charset(lang('plugin/cxpform', 'username')));
			$sheet->writeString(1,7,cxpform_csv_charset(cxpform_lang('defaultfield3')));
			$sheet->writeString(1,8,cxpform_csv_charset(cxpform_lang('bookingnumber')));
			$sheet->writeString(1,9,cxpform_csv_charset(cxpform_lang('status')));
			$tempi = 9;
			foreach($fields as $field){
				$sheet->writeString(1,$tempi+1,cxpform_csv_charset($field['field_label']));
				$tempi++;
			}
			
		}else{
			
			$sheet->writeString(1,1,cxpform_csv_charset(lang('plugin/cxpform', 'belong_form')));
			$sheet->writeString(1,2,cxpform_csv_charset(lang('plugin/cxpform', 'content_addtime')));
			$sheet->writeString(1,3,'IP');
			$sheet->writeString(1,4,cxpform_csv_charset(lang('plugin/cxpform', 'area')));
			$sheet->writeString(1,5,cxpform_csv_charset(lang('plugin/cxpform', 'fromurl')));
			$sheet->writeString(1,6,cxpform_csv_charset(lang('plugin/cxpform', 'username')));
			$sheet->writeString(1,7,cxpform_csv_charset(cxpform_lang('status')));
			$tempi = 7;
			foreach($fields as $field){
				$sheet->writeString(1,$tempi+1,cxpform_csv_charset($field['field_label']));
				$tempi++;
			}			
		}	
		
		if($count > 0){
			$tempj = 1;
			foreach($contents as $content) {
				
				
				if($forminfo['form_type'] == '3'){
					
					$sheet->writeString($tempj+1,1,cxpform_csv_charset($content['title']));
					$sheet->writeString($tempj+1,2,date('Y-m-d H:i:s', $content['addtime']));
					$sheet->writeString($tempj+1,3,$content['ip']);
					$sheet->writeString($tempj+1,4,cxpform_csv_charset($content['area']));
					$sheet->writeString($tempj+1,5,$content['fromurl']);
					$sheet->writeString($tempj+1,6,cxpform_csv_charset($content['username']));
					$sheet->writeString($tempj+1,7,dgmdate($content['bookingtime'], 'Y-m-d'));
					$sheet->writeString($tempj+1,8,$content['bookingnumber']);
					$sheet->writeString($tempj+1,9,cxpform_csv_charset($content['status_name']));
					$tempjj = 9;
					
				}else{
					
					$sheet->writeString($tempj+1,1,cxpform_csv_charset($content['title']));
					$sheet->writeString($tempj+1,2,date('Y-m-d H:i:s', $content['addtime']));
					$sheet->writeString($tempj+1,3,$content['ip']);
					$sheet->writeString($tempj+1,4,cxpform_csv_charset($content['area']));
					$sheet->writeString($tempj+1,5,$content['fromurl']);
					$sheet->writeString($tempj+1,6,cxpform_csv_charset($content['username']));
					$sheet->writeString($tempj+1,7,cxpform_csv_charset($content['status_name']));	
					$tempjj = 7;
				}
				
				$tempstr = '';
				$content_extra = DB::fetch_all('select B.*, A.field_label, A.field_type, A.show_other from ' . DB::table('cxpform_fields') . ' as A left join ' . DB::table('cxpform_content_extra') . ' B on A.id=B.field_id where A.form_id=' . $content['form_id'] . ' and B.content_id=' . $content['id'] . ' order by A.sortid asc, A.id asc');
				$file_str .= '';
				
				$content_extra_arr = array();
				foreach($content_extra as $cextra){
					$content_extra_arr[$cextra['field_id']] = $cextra;
				}
				
				foreach($fields as $field){
					
					$v = $content_extra_arr[$field['id']];
					$valstr = '';
					$arr = maybe_unserialize($v['field_value']);
					// 单选组 复选组 下拉框
					if(in_array($v['field_type'], array('3', '4', '13'))){
						$field_option = cxpform_field_option_list($v['field_id']);
						
						if(is_array($arr)){
							foreach($arr as $v1){
								$valstr .= cxpform_csv_charset($field_option[$v1]['option_name']) . ' ';
							}
							if(array_search('other_field', $arr) !== FALSE){
								$valstr .= cxpform_csv_charset(cxpform_lang('other') . ':' . $arr[count($arr) - 1]);
							}
						}else{
							if(!array_key_exists($arr, $field_option)){
								$valstr = cxpform_csv_charset(cxpform_lang('other') . ':' . $arr);
							}else{
								$valstr = cxpform_csv_charset($field_option[$arr]['option_name']);
							}
						}
					}else{
				
						$valstr = cxpform_csv_charset($arr);
						
					}
					
					// $tempstr .= $valstr . ",";		
					$sheet->writeString($tempj+1,$tempjj+1,$valstr);
					$tempjj++;
				}				

				// }					
				// $file_str .= ',' . $tempstr . "\n";
				
				$tempj++;
				
			}
		}
		

		
		
		
		$xml->sendHeaders();
		$xml->writeData();

		define(FOOTERDISABLED, false);
		exit;	
	}
}else{
	// 内容列表

	
	$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
	if($form_id != ''){
		cxpform_form_subnav($form_id, 'form_content');
	}
	
	$forminfo = cxpform_form_info($form_id);
	
	if($forminfo['form_type'] == '2'){
		echo '<a href="' . XF_PLUGIN_URL . 'pmod=form&op=form_content&op1=import_data&form_id=' . $form_id . '" class="addtr">' . cxpform_lang('import_data') . '</a> ';
	
		echo '<a href="' . XF_PLUGIN_URL . 'pmod=form&op=form_content&op1=add_data&form_id=' . $form_id . '" class="addtr">' . cxpform_lang('add_data') . '</a>';
		
		echo '&nbsp;&nbsp;<a href="' .  XF_PLUGIN_URL . "pmod=form&op=form_content&op1=export&form_id=" . $form_id . '&formhash=' . FORMHASH . '" style="white-space:nowrap;">' . lang('plugin/cxpform', 'export') . '</a>';
		
		showtableheader();
		showtitle(lang('plugin/cxpform', 'content_list'));
		showformheader(XF_FORM_URL . 'pmod=form&op=form_content&op1=delete&form_id=' . $form_id, 'formsubmit');		
		
		$fields = cxpform_field_list($form_id);
		
		
		
		echo '<tr class="header">
			<th class="td25"><input type="checkbox" onclick="selectAll(\'delid[]\')" />#</th>
			<!--<th nowrap>' . lang('plugin/cxpform', 'belong_form') . '</th>-->
			<th nowrap>' . lang('plugin/cxpform', 'content_addtime') . '</th>';
			
			foreach($fields as $field){
				echo '<th nowrap>' . $field['field_label'] . '</th>';
			}
			
		echo '<th nowrap>' . lang('plugin/cxpform', 'status') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'op') . '</th></tr>';
			
		
		$ppp = !empty($_G['cache']['plugin']['cxpform']['backend_perpage']) ? $_G['cache']['plugin']['cxpform']['backend_perpage'] : 10;
		$resultempty = FALSE;
		$page = max(1, intval($_GET['page']));
		
		$condstr = ' WHERE A.form_id=' . $form_id;
		$extrasearch = '&form_id=' . $form_id;		
		
		$count = DB::result_first('select count(A.id) from ' . DB::table('cxpform_contents') . ' A ' . $condstr);
		
		$contents = DB::fetch_all('SELECT A.*, B.title, C.status_name, C.status_color FROM '.DB::table('cxpform_contents') . ' A left join ' . DB::table('cxpforms') . ' B on A.form_id=B.id left join ' . DB::table('cxpform_content_status') . ' C on (A.status = C.id and A.form_id = C.form_id) ' . $condstr . ' order by A.id desc limit ' . intval(($page-1)*$ppp) . ',' . $ppp);	

		foreach($contents as $content){
			echo '<tr>
				<td><input name="delid[]" type="checkbox" value="' . $content['id'] . '" />' . $content['id'] . '</td>
				<!--<td>' . $content['title'] . '</td>-->
				<td>' . date('Y-m-d H:i:s', $content['addtime']) . '</td>';
				// 扩展内容
				$content_extra = DB::fetch_all('select A.* from ' . DB::table('cxpform_content_extra') . ' as A where A.form_id=' . $content['form_id'] . ' and A.content_id=' . $content['id']);
				$content_extra_arr = array();
				foreach($content_extra as $cextra){
					$content_extra_arr[$cextra['field_id']] = $cextra['field_value'];
				}
				foreach($fields as $field){
					echo '<td>' . $content_extra_arr[$field['id']] . '</td>';
				}
				
			echo '<td><font color="' . $content['status_color'] . '">' . $content['status_name'] . '</font></td>
				<td>
					<a href="' . XF_PLUGIN_URL . 'pmod=form&op=form_content&op1=edit_data&form_id=' . $content['form_id'] . '&content_id=' . $content['id'] . '">' . lang('plugin/cxpform', 'edit') . '</a>
				
					<a href="' . XF_PLUGIN_URL . 'pmod=form&op=form_content&op1=delete&form_id=' . $content['form_id'] . '&id=' . $content['id'] . '&formhash=' . FORMHASH . '" onclick="if(confirm(\'' . lang('plugin/cxpform', 'delconfirm') . '\')){return true;}else{return false;}">' . lang('plugin/cxpform', 'delete') . '</a> </td>
			</tr>';
		}
		echo '<tr><td colspan="15"><input type="submit" value="' . lang('plugin/cxpform', 'delete') . '" class="btn" /></td></tr>';	
		showtagfooter('tbody');
		showformfooter();
		showtablefooter();	
		echo multi($count, $ppp, $page, XF_PLUGIN_URL . "pmod=form&op=form_content" . $extrasearch);		
	}elseif($forminfo['form_type'] == '3'){
		$addtime1 = isset($_GET['addtime1']) ? addslashes(trim($_GET['addtime1'])) : '';
		$addtime2 = isset($_GET['addtime2']) ? addslashes(trim($_GET['addtime2'])) : '';		
		$bookingtime1 = isset($_GET['bookingtime1']) ? addslashes(trim($_GET['bookingtime1'])) : '';
		$bookingtime2 = isset($_GET['bookingtime2']) ? addslashes(trim($_GET['bookingtime2'])) : '';
		$ip = isset($_GET['ip']) ? addslashes(trim($_GET['ip'])) : '';
		$area = isset($_GET['area']) ? addslashes(trim($_GET['area'])) : '';
		$username = isset($_GET['username']) ? addslashes(trim($_GET['username'])) : '';
		$bookingnumber = isset($_GET['bookingnumber']) ? addslashes(trim($_GET['bookingnumber'])) : '';
		$status = isset($_GET['status']) ? intval($_GET['status']) : '';
		
		$ppp = !empty($_G['cache']['plugin']['cxpform']['backend_perpage']) ? $_G['cache']['plugin']['cxpform']['backend_perpage'] : 10;
		$resultempty = FALSE;
		$page = max(1, intval($_GET['page']));

		showtableheader();
		showtitle(lang('plugin/cxpform', 'content_list'));
		showformheader(XF_FORM_URL . 'pmod=form&op=form_content', 'formsubmit');
		
		$form_info = cxpform_form_info($form_id);
		
		
		$selectform = '';
		$formarr = cxpform_form_list();
		
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
		if($bookingtime1){
			$condstr .= " AND A.bookingtime>=" . strtotime($bookingtime1);
			$extrasearch .= '&bookingtime1=' . urlencode($bookingtime1);
		}
		if($bookingtime2){
			$condstr .= " AND A.bookingtime<=" . strtotime($bookingtime2);
			$extrasearch .= '&bookingtime2=' . urlencode($bookingtime2);
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
		
		if($bookingnumber){
			$condstr .= " AND A.bookingnumber like " . DB::quote('%' . $bookingnumber . '%');
			$extrasearch .= '&bookingnumber=' . $bookingnumber;
		}	
		
		if($status){
			$condstr .= " AND A.status=" . DB::quote('status');
			$extrasearch .= '&status=' . $status;
		}
		
		
		// foreach($formarr as $fk=>$fv){
			// $selectform .= '<option value="' . $fk . '" ' . ($form_id == $fk ? 'selected="selected"' : '') . '>' . $fv['title'] . '</option>';
		// }
		
		// $selectform = '<option value="">' . lang('plugin/cxpform', 'all') . '</option>' . $selectform;
		
		// 状态
		$content_status = cxpform_content_status_list($form_id);
		$content_status_str = '';
		foreach($content_status as $status1){
			$content_status_str .= '<option value="' . $status1['id'] . '">' . $status1['status_name'] . '</option>';
		}
		
		$content_status_str = '<option value="">' . lang('plugin/cxpform', 'all') . '</option>' . $content_status_str;
		
		showsubmit('formsubmit', lang('plugin/cxpform', 'search'), lang('plugin/cxpform', 'content_addtime') .lang('plugin/cxpform', 'maohao'). '<input name="addtime1" class="txt Wdate"  onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" value="' . $addtime1 . '" /> - <input type="text" class="txt Wdate" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" value="' . $addtime2 . '" name="addtime2" />IP'.lang('plugin/cxpform', 'maohao').'<input type="text" name="ip" value="' . $ip . '" class="txt" />  ' . lang('plugin/cxpform', 'area') .lang('plugin/cxpform', 'maohao') .'<input type="text" name="area" value="' . $area . '" class="txt" /> <p></p> ' . lang('plugin/cxpform', 'username') . lang('plugin/cxpform', 'maohao').'<input type="text" name="username" value="' . $username . '" class="txt" /> ' . cxpform_lang('bookingnumber') . ':<input type="text" name="bookingnumber" value="' . $bookingnumber . '" />' . cxpform_lang('defaultfield3') . ':<input name="bookingtime1" class="txt Wdate"  onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" value="' . $bookingtime1 . '" /> - <input type="text" class="txt Wdate" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" value="' . $bookingtime2 . '" name="bookingtime2" />' . lang('plugin/cxpform', 'status') . ':<select name="status">' . $content_status_str . '</select>', '<a href="' .  XF_PLUGIN_URL . "pmod=form&op=form_content&op1=export" . $extrasearch . '&formhash=' . FORMHASH . '" style="white-space:nowrap;">' . lang('plugin/cxpform', 'export') . '</a>');
		showformfooter();
		showformheader(XF_FORM_URL . 'pmod=form&op=form_content&op1=delete&form_id=' . $form_id, 'formsubmit');
		echo '<tr class="header">
			<th class="td25"><input type="checkbox" onclick="selectAll(\'delid[]\')" />#</th>
			<!--<th nowrap>' . lang('plugin/cxpform', 'belong_form') . '</th>-->
			<th nowrap>' . lang('plugin/cxpform', 'content_addtime') . '</th>
			<th nowrap>IP</th>
			<th nowrap>' . lang('plugin/cxpform', 'area') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'fromurl') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'username') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'defaultfield3') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'bookingnumber') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'status') . '</th>
			
			<th nowrap>' . lang('plugin/cxpform', 'op') . '</th></tr>';
		
		// showtablerow('class="header"', '', array('编号', '表单名称'));
		showtagheader('tbody', '', true, '');
		
		
		$count = DB::result_first('select count(A.id) from ' . DB::table('cxpform_contents') . ' A ' . $condstr);
		
		$contents = DB::fetch_all('SELECT A.*, B.title, C.status_name, C.status_color FROM '.DB::table('cxpform_contents') . ' A left join ' . DB::table('cxpforms') . ' B on A.form_id=B.id left join ' . DB::table('cxpform_content_status') . ' C on (A.status = C.id and A.form_id = C.form_id) ' . $condstr . ' order by A.id desc limit ' . intval(($page-1)*$ppp) . ',' . $ppp);		
		
		if($count > 0){
			foreach($contents as $content) {
				echo '<tr class="hover">
					<td><input name="delid[]" type="checkbox" value="' . $content['id'] . '" /></td>
					<!--<td>' . $content['title'] . '</td>-->
					<td>' . date('Y-m-d H:i:s', $content['addtime']) . '</td>
					<td>' . $content['ip'] . '</td>
					<td>' . $content['area'] . '</td>
					<td>' . $content['fromurl'] . '</td>
					<td>' . $content['username'] . '</td>
					<td>' . dgmdate($content['bookingtime'], 'Y-m-d') . '</td>
					<td>' . $content['bookingnumber'] . '</td>
					<td><font color="' . $content['status_color'] . '">' . $content['status_name'] . '</font></td>
					<td>
					 <a href="' . XF_PLUGIN_URL . 'pmod=form&op=form_content&op1=handle_content&form_id=' . $content['form_id'] . '&content_id=' . $content['id'] . '">' . cxpform_lang('handle') . '</a> 
					<a href="#" onclick="jq(\'#detail' . $content['id'] . '\').show();return false;">' . lang('plugin/cxpform', 'detail') . '</a>
				
					<a href="' . XF_PLUGIN_URL . 'pmod=form&op=form_content&op1=delete&form_id=' . $content['form_id'] . '&id=' . $content['id'] . '&formhash=' . FORMHASH . '" onclick="if(confirm(\'' . lang('plugin/cxpform', 'delconfirm') . '\')){return true;}else{return false;}">' . lang('plugin/cxpform', 'delete') . '</a> 
					
				</td></tr>';
				
				echo '<tr style="display:none;" id="detail' . $content['id'] . '"><td colspan="7">'; 
				echo '<table style="margin-left:30px;">';
				echo '<tr>
					<th><strong>' . lang('plugin/cxpform', 'field_label') . '</strong></th>
					<th><strong>' . lang('plugin/cxpform', 'field_value') . '</strong></th>
					</tr>';
				$content_extra = DB::fetch_all('select A.*, B.field_label, B.field_type, B.show_other from ' . DB::table('cxpform_content_extra') . ' as A left join ' . DB::table('cxpform_fields') . ' B on A.field_id=B.id where A.form_id=' . $content['form_id'] . ' and A.content_id=' . $content['id'] . ' order by B.sortid asc, B.id asc');
				foreach($content_extra as $k=>$v){
					$valstr = '';
					$arr = maybe_unserialize($v['field_value']);
					
					// 单选组 复选组 下拉框
					if(in_array($v['field_type'], array('3', '4', '13'))){
						$field_option = cxpform_field_option_list($v['field_id']);
						
						if(is_array($arr)){
							foreach($arr as $v1){
								$valstr .= $field_option[$v1]['option_name'] . ' ';
							}
							if(array_search('other_field', $arr) !== FALSE){
								$valstr .= cxpform_lang('other') . ':' . $arr[count($arr) - 1];
							}
						}else{
							if(!array_key_exists($arr, $field_option)){
								$valstr = cxpform_lang('other') . ':' . $arr;
							}else{
								$valstr = $field_option[$arr]['option_name'];
							}
						}
					}elseif($v['field_type'] == '14'){	
						$valstr = '<a href="' . $arr . '" target="_blank">' . $arr . '</a>';
					}else{
						$valstr = $arr;
					}
					echo '<tr><td>' . $v['field_label'] . '</td><td>' . $valstr . '</td></tr>';
				}
				echo '</table>';
				echo '</td></tr>';
			}
		}else{
			echo '<tr><td colspan="11">' . lang('plugin/cxpform', 'nodata') . '</td></tr>';
		}
		echo '<tr><td colspan="11"><input type="submit" value="' . lang('plugin/cxpform', 'delete') . '" class="btn" /></td></tr>';
		showtagfooter('tbody');
		showformfooter();
		showtablefooter();	
		echo multi($count, $ppp, $page, XF_PLUGIN_URL . "pmod=form&op=form_content" . $extrasearch);		
	}else{
	
		$addtime1 = isset($_GET['addtime1']) ? addslashes(trim($_GET['addtime1'])) : '';
		$addtime2 = isset($_GET['addtime2']) ? addslashes(trim($_GET['addtime2'])) : '';
		$ip = isset($_GET['ip']) ? addslashes(trim($_GET['ip'])) : '';
		$area = isset($_GET['area']) ? addslashes(trim($_GET['area'])) : '';
		$username = isset($_GET['username']) ? addslashes(trim($_GET['username'])) : '';
		$status = isset($_GET['status']) ? intval($_GET['status']) : '';
		
		$ppp = !empty($_G['cache']['plugin']['cxpform']['backend_perpage']) ? $_G['cache']['plugin']['cxpform']['backend_perpage'] : 10;
		$resultempty = FALSE;
		$page = max(1, intval($_GET['page']));

		showtableheader();
		showtitle(lang('plugin/cxpform', 'content_list'));
		showformheader(XF_FORM_URL . 'pmod=form&op=form_content', 'formsubmit');
		
		$form_info = cxpform_form_info($form_id);
		
		
		$selectform = '';
		$formarr = cxpform_form_list();
		
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
		
		if($status){
			$condstr .= " AND A.status=" . DB::quote('status');
			$extrasearch .= '&status=' . $status;
		}
		
		
		// foreach($formarr as $fk=>$fv){
			// $selectform .= '<option value="' . $fk . '" ' . ($form_id == $fk ? 'selected="selected"' : '') . '>' . $fv['title'] . '</option>';
		// }
		
		// $selectform = '<option value="">' . lang('plugin/cxpform', 'all') . '</option>' . $selectform;
		
		// 状态
		$content_status = cxpform_content_status_list($form_id);
		$content_status_str = '';
		foreach($content_status as $status1){
			$content_status_str .= '<option value="' . $status1['id'] . '">' . $status1['status_name'] . '</option>';
		}
		
		$content_status_str = '<option value="">' . lang('plugin/cxpform', 'all') . '</option>' . $content_status_str;
		
		showsubmit('formsubmit', lang('plugin/cxpform', 'search'), lang('plugin/cxpform', 'content_addtime') .lang('plugin/cxpform', 'maohao'). '<input name="addtime1" class="txt Wdate"  onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" value="' . $addtime1 . '" /> - <input type="text" class="txt Wdate" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" value="' . $addtime2 . '" name="addtime2" />IP'.lang('plugin/cxpform', 'maohao').'<input type="text" name="ip" value="' . $ip . '" class="txt" />  ' . lang('plugin/cxpform', 'area') .lang('plugin/cxpform', 'maohao') .'<input type="text" name="area" value="' . $area . '" class="txt" /> <p></p> ' . lang('plugin/cxpform', 'username') . lang('plugin/cxpform', 'maohao').'<input type="text" name="username" value="' . $username . '" class="txt" /> ' . lang('plugin/cxpform', 'status') . ':<select name="status">' . $content_status_str . '</select>', '<a href="' .  XF_PLUGIN_URL . "pmod=form&op=form_content&op1=export" . $extrasearch . '&formhash=' . FORMHASH . '" style="white-space:nowrap;">' . lang('plugin/cxpform', 'export') . '</a>');
		showformfooter();
		showformheader(XF_FORM_URL . 'pmod=form&op=form_content&op1=delete&form_id=' . $form_id, 'formsubmit');
		echo '<tr class="header">
			<th class="td25"><input type="checkbox" onclick="selectAll(\'delid[]\')" />#</th>
			<!--<th nowrap>' . lang('plugin/cxpform', 'belong_form') . '</th>-->
			<th nowrap>' . lang('plugin/cxpform', 'content_addtime') . '</th>
			<th nowrap>IP</th>
			<th nowrap>' . lang('plugin/cxpform', 'area') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'fromurl') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'username') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'status') . '</th>
			<th nowrap>' . lang('plugin/cxpform', 'op') . '</th></tr>';
		
		// showtablerow('class="header"', '', array('编号', '表单名称'));
		showtagheader('tbody', '', true, '');
		
		
		$count = DB::result_first('select count(A.id) from ' . DB::table('cxpform_contents') . ' A ' . $condstr);
		
		$contents = DB::fetch_all('SELECT A.*, B.title, C.status_name, C.status_color FROM '.DB::table('cxpform_contents') . ' A left join ' . DB::table('cxpforms') . ' B on A.form_id=B.id left join ' . DB::table('cxpform_content_status') . ' C on (A.status = C.id and A.form_id = C.form_id) ' . $condstr . ' order by A.id desc limit ' . intval(($page-1)*$ppp) . ',' . $ppp);		
		
		if($count > 0){
			foreach($contents as $content) {
				echo '<tr class="hover">
					<td><input name="delid[]" type="checkbox" value="' . $content['id'] . '" />' . $content['id'] . '</td>
					<!--<td>' . $content['title'] . '</td>-->
					<td>' . date('Y-m-d H:i:s', $content['addtime']) . '</td>
					<td>' . $content['ip'] . '</td>
					<td>' . $content['area'] . '</td>
					<td>' . $content['fromurl'] . '</td>
					<td><a href="home.php?mod=space&uid=' . $content['user_id'] . '" target="_blank">' . $content['username'] . '</a></td>
					<td><font color="' . $content['status_color'] . '">' . $content['status_name'] . '</font></td>
					<td>
					 <a href="' . XF_PLUGIN_URL . 'pmod=form&op=form_content&op1=handle_content&form_id=' . $content['form_id'] . '&content_id=' . $content['id'] . '">' . cxpform_lang('handle') . '</a> 
					<a href="#" onclick="jq(\'#detail' . $content['id'] . '\').show();return false;">' . lang('plugin/cxpform', 'detail') . '</a>
				
					<a href="' . XF_PLUGIN_URL . 'pmod=form&op=form_content&op1=delete&form_id=' . $content['form_id'] . '&id=' . $content['id'] . '&formhash=' . FORMHASH . '" onclick="if(confirm(\'' . lang('plugin/cxpform', 'delconfirm') . '\')){return true;}else{return false;}">' . lang('plugin/cxpform', 'delete') . '</a> 
					
				</td></tr>';
				
				echo '<tr style="display:none;" id="detail' . $content['id'] . '"><td colspan="7">'; 
				echo '<table style="margin-left:30px;">';
				echo '<tr>
					<th><strong>' . lang('plugin/cxpform', 'field_label') . '</strong></th>
					<th><strong>' . lang('plugin/cxpform', 'field_value') . '</strong></th>
					</tr>';
				$content_extra = DB::fetch_all('select A.*, B.field_label, B.field_type, B.show_other from ' . DB::table('cxpform_content_extra') . ' as A left join ' . DB::table('cxpform_fields') . ' B on A.field_id=B.id where A.form_id=' . $content['form_id'] . ' and A.content_id=' . $content['id'] . ' order by B.sortid asc, B.id asc');
				foreach($content_extra as $k=>$v){
					$valstr = '';
					$arr = maybe_unserialize($v['field_value']);
					
					// 单选组 复选组 下拉框
					if(in_array($v['field_type'], array('3', '4', '13'))){
						$field_option = cxpform_field_option_list($v['field_id']);
						
						if(is_array($arr)){
							foreach($arr as $v1){
								$valstr .= $field_option[$v1]['option_name'] . ' ';
							}
							if(array_search('other_field', $arr) !== FALSE){
								$valstr .= cxpform_lang('other') . ':' . $arr[count($arr) - 1];
							}
						}else{
							if(!array_key_exists($arr, $field_option)){
								$valstr = cxpform_lang('other') . ':' . $arr;
							}else{
								$valstr = $field_option[$arr]['option_name'];
							}
						}
					}elseif($v['field_type'] == '14'){	
						$valstr = '<a href="' . $arr . '" target="_blank">' . $arr . '</a>';
					}else{
						$valstr = $arr;
					}
					echo '<tr><td>' . $v['field_label'] . '</td><td>' . $valstr . '</td></tr>';
				}
				echo '</table>';
				echo '</td></tr>';
			}
		}else{
			echo '<tr><td colspan="9">' . lang('plugin/cxpform', 'nodata') . '</td></tr>';
		}
		echo '<tr><td colspan="9"><input type="submit" value="' . lang('plugin/cxpform', 'delete') . '" class="btn" /></td></tr>';
		showtagfooter('tbody');
		showformfooter();
		showtablefooter();	
		echo multi($count, $ppp, $page, XF_PLUGIN_URL . "pmod=form&op=form_content" . $extrasearch);
	}
}
?>