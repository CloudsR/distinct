<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
// var_dump($_G);
define('CXPFORM_NAME1', lang('plugin/cxpform', 'cxpform1'));
define('CXPFORM_BUTTONTEXT', lang('plugin/cxpform', 'submit'));
define('CXPFORM_ICON', 'resource/images/icon.gif');

loadcache('plugin');
include_once(dirname(__FILE__) . '/include/function.php');
class threadplugin_cxpform{

	var $name = CXPFORM_NAME1;			//主题类型名称
	var $iconfile = CXPFORM_ICON;		//发布主题链接中的前缀图标
	var $buttontext = CXPFORM_BUTTONTEXT;	//发帖时按钮文字


	// 发主题时页面新增的表单项目，通过 return 返回即可输出到发帖页面中
	function newthread($fid) {
		global $_G, $cxpform_field_type;
		// require_once libfile('function/upload');
		// $swfconfig = getuploadconfig($_G['uid'], $_G['fid']);
		$visibilityform = 1;
		include template('cxpform:cxpform_newthread');
		return $return;
	}
	// 主题发布后的数据判断
	function newthread_submit($fid) {

	}
	// 主题发布后的数据处理
	function newthread_submit_end($fid, $tid) {
		global $_G,$pid;
		$form_id = $extra = 0;
		$subject = DB::result_first("SELECT subject FROM ".DB::table('forum_thread')." WHERE tid='$tid'");
		
		$startdate = isset($_GET['startdate']) ? addslashes(trim($_GET['startdate'])) : date('Y-m-d H:i');
		$enddate = isset($_GET['enddate']) ? addslashes(trim($_GET['enddate'])) : '';
		$visibilityform = isset($_GET['visibilityform']) ? intval($_GET['visibilityform']) : 1;
		$data = array(
			'tid' => $tid,
			'title' => $subject,
			'form_type' => 1,
			'description' => cxpform_lang('is_thread'),
			'status' => 1,
			'startdate' => strtotime($startdate),
			'enddate' => strtotime($enddate),
			'addtime' => $_SERVER['REQUEST_TIME'],
			'userid' => $_G['uid'],
			'username' => $_G['username'],
			'visibilityform' => $visibilityform,
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
			'ip_perday' => $_G['cache']['plugin']['cxpform']['ip_perday'] ? $_G['cache']['plugin']['cxpform']['ip_perday'] : 5,
			'iscaptcha' => 1,
			'islogin' => 1,
			'user_perday' => $_G['cache']['plugin']['cxpform']['user_perday'] ? $_G['cache']['plugin']['cxpform']['user_perday'] : 5,
		);
		DB::insert('cxpform_submit_setting', $data2);
		// 初始化提醒设置
		$data3 = array(
			'form_id' => $new_form_id,
			'status' => 0,
			'message_status' => 1,
			'message_to' => $_G['uid'],
			'message_title' => lang('plugin/cxpform', 'message_notice_title'),
			'message_content' => lang('plugin/cxpform', 'message_notice_content'),
		);
		DB::insert('cxpform_notice_setting', $data3);
		
		// 题目
		$field_id = isset($_GET['field_id']) ? $_GET['field_id'] : '';
		$field_label = isset($_GET['field_label']) ? $_GET['field_label'] : '';
		$field_type = isset($_GET['field_type']) ? $_GET['field_type'] : '';
		$sortid1 = isset($_GET['sortid1']) ? $_GET['sortid1'] : '';
		$isrequired = isset($_GET['isrequired']) ? $_GET['isrequired'] : '';
		$show_other = isset($_GET['show_other']) ? $_GET['show_other'] : '';
		
		// 这三个是数组请不要判定为没有安全过滤
		$option_id = isset($_GET['option_id']) ? $_GET['option_id'] : '';
		$option_name = isset($_GET['option_name']) ? $_GET['option_name'] : '';
		$picurl = isset($_GET['picurl']) ? $_GET['picurl'] : '';
		$sortid2 = isset($_GET['sortid2']) ? $_GET['sortid2'] : '';		
		
		if($field_label != ''){
			foreach($field_label as $k1=>$v1){
				$data = array(
					'tid' => $tid,
					'form_id' => $new_form_id,
					'field_label' => trim($field_label[$k1]),
					'field_desc' => '',
					'field_type' => $field_type[$k1],
					'select_num' => 0,
					'isrequired' => $isrequired[$k1],
					'show_other' => $show_other[$k1],
					'addtime' => $_SERVER['REQUEST_TIME'],
				);
				
				$new_field_id = DB::insert('cxpform_fields', $data, 1);
				
				// 是单选框组，复选框组，下拉框
				if(in_array($field_type[$k1], array('3', '4', '13'))){
					$option_id1 = $option_id[$k1];
					$option_name1 = $option_name[$k1];
					$picurl1 = $picurl[$k1];
					$sortid21 = $sortid2[$k1];
					if($option_id1 !== ''){
						foreach($option_id1 as $opk=>$opid){
							$data1 = array(
								'form_id' => $new_form_id,
								'field_id' => $new_field_id,
								'option_name' => trim($option_name1[$opk]),
								'picurl' => $picurl1[$opk],
								'sortid' => intval($sortid21[$opk]),
							);
							if($opid == ''){
								// 新增
								DB::insert('cxpform_field_options', $data1);
							}else{
								// 更新
								DB::update('cxpform_field_options', $data1, DB::field('id', $opid));
							}
						}
					}					
				}
			}
		}
		
	}
	// 编辑主题时页面新增的表单项目，通过 return 返回即可输出到编辑主题页面中
	function editpost($fid, $tid) {
		global $_G, $cxpform_field_type;
		
		// require_once libfile('function/upload');
		// $swfconfig = getuploadconfig($_G['uid'], $_G['fid']);
		$form_id = DB::result_first("SELECT id FROM ".DB::table('cxpforms')." WHERE tid='$tid'");
		$forminfo = cxpform_form_info($form_id);
		$visibilityform = $forminfo['visibilityform'];
		// 题目
		$fields =cxpform_field_list($form_id);
		foreach($fields as $k => $field){
			if(in_array($field['field_type'], array('3', '4', '13'))){
				$options = cxpform_field_option_list($field['id']);
				$fields[$k]['options'] = $options;
			}else{
				$fields[$k]['options'] = '';
			}
		}
		include template('cxpform:cxpform_newthread');
		return $return;
	}
	// 主题编辑后的数据判断
	function editpost_submit($fid, $tid) {

	}
	// 主题编辑后的数据处理
	function editpost_submit_end($fid, $tid) {
		global $_G,$pid;
		$subject = DB::result_first("SELECT subject FROM ".DB::table('forum_thread')." WHERE tid='$tid'");
		$form_id = DB::result_first("SELECT id FROM ".DB::table('cxpforms')." WHERE tid='$tid'");
		$startdate = isset($_GET['startdate']) ? addslashes(trim($_GET['startdate'])) : '';
		$enddate = isset($_GET['enddate']) ? addslashes(trim($_GET['enddate'])) : '';	
		$visibilityform = isset($_GET['visibilityform']) ? intval($_GET['visibilityform']) : 1;		
		$data = array(
			'tid' => $tid,
			'title' => $subject,
			'form_type' => 1,
			'description' => '',
			'status' => 1,
			'startdate' => strtotime($startdate),
			'enddate' => strtotime($enddate),
			'visibilityform' => $visibilityform,
		);
		DB::update('cxpforms', $data, DB::field('id', $form_id));
		
		// 题目
		$field_id = isset($_GET['field_id']) ? $_GET['field_id'] : '';
		$field_label = isset($_GET['field_label']) ? $_GET['field_label'] : '';
		$field_type = isset($_GET['field_type']) ? $_GET['field_type'] : '';
		$sortid1 = isset($_GET['sortid1']) ? $_GET['sortid1'] : '';
		$isrequired = isset($_GET['isrequired']) ? $_GET['isrequired'] : '';
		$show_other = isset($_GET['show_other']) ? $_GET['show_other'] : '';
		
		// 这三个是数组请不要判定为没有安全过滤
		$option_id = isset($_GET['option_id']) ? $_GET['option_id'] : '';
		$option_name = isset($_GET['option_name']) ? $_GET['option_name'] : '';
		$picurl = isset($_GET['picurl']) ? $_GET['picurl'] : '';
		$sortid2 = isset($_GET['sortid2']) ? $_GET['sortid2'] : '';		
		
		$todelfieldids = isset($_GET['todelfieldids']) ? trim($_GET['todelfieldids']) : '';
		$todeloptionids = isset($_GET['todeloptionids']) ? trim($_GET['todeloptionids']) : '';
		if($todelfieldids !== ''){
			$delarr1 = explode(',', trim($todelfieldids, ','));
			// for($i=0;$i<XF_EXTRA_TABLE_NUM;$i++){
				// DB::delete('cxpform_content_extra_' . $i, DB::field('field_id', $delarr1));
			// }
			DB::delete('cxpform_content_extra', DB::field('field_id', $delarr1));
			DB::delete('cxpform_field_value', DB::field('field_id', $delarr1));
			DB::delete('cxpform_field_options', DB::field('field_id', $delarr1));
			DB::delete('cxpform_fields', DB::field('id', $delarr1));
		}
		if($todeloptionids !== ''){
			$delarr2 = explode(',', trim($todeloptionids, ','));
			DB::delete('cxpform_field_value', DB::field('option_id', $delarr2));
			DB::delete('cxpform_field_options', DB::field('id', $delarr2));
		}				

		if($field_label != ''){
			
			foreach($field_label as $k1=>$v1){
				$data = array(
					'tid' => $tid,
					'form_id' => $form_id,
					'field_label' => trim($field_label[$k1]),
					'field_desc' => '',
					'field_type' => $field_type[$k1],
					'select_num' => 0,
					'isrequired' => $isrequired[$k1],
					'show_other' => $show_other[$k1],
					'addtime' => $_SERVER['REQUEST_TIME'],
				);
				
				if($field_id[$k1] == ''){
					$new_field_id = DB::insert('cxpform_fields', $data, 1);
				}else{
					DB::update('cxpform_fields', $data, DB::field('id', $field_id[$k1]));
					$new_field_id = $field_id[$k1];
				}
				
				// 是单选框组，复选框组，下拉框
				if(in_array($field_type[$k1], array('3', '4', '13'))){
					$option_id1 = $option_id[$k1];
					$option_name1 = $option_name[$k1];
					$picurl1 = $picurl[$k1];
					$sortid21 = $sortid2[$k1];
					if($option_name1 !== ''){

						foreach($option_name1 as $opk=>$opname){
							$data1 = array(
								'form_id' => $form_id,
								'field_id' => $new_field_id,
								'option_name' => trim($option_name1[$opk]),
								'picurl' => trim($picurl1[$opk]),
								'sortid' => intval($sortid21[$opk]),
							);
							
							
							if($option_id1[$opk] == ''){
								// 新增
								DB::insert('cxpform_field_options', $data1);
							}else{
								// 更新
								DB::update('cxpform_field_options', $data1, DB::field('id', $option_id1[$opk]));
							}
						}
					}					
				}
				
			}
		}		
		
	}
	// 回帖后的数据处理
	function newreply_submit_end($fid, $tid) {

	}
	// 查看主题时页面新增的内容，通过 return 返回即可输出到主题首贴页面中
	function viewthread($tid) {
		global $_G;
		$tid = $tid;
		$forminfo = DB::fetch_first("SELECT * FROM ".DB::table('cxpforms')." WHERE tid='$tid'");
		if($forminfo){
			
			$form_id = $forminfo['id'];
			// 更新选择数
			DB::query('update ' . DB::table('cxpforms') . ' set hits=hits+1 where id=' . $form_id);
			// $forminfo = cxpform_form_info($form_id);
			$fields =cxpform_field_list($form_id);
			$form_element = '';
			foreach($fields as $field){
				$form_element .= '<tr><td><strong>' . $field['field_label'] . '</strong>' . ($field['isrequired'] ? '<span class="rq">*</span>' : '') . '</td></tr>' . ($field['field_desc'] == '' ? '' : '<tr><td>' . $field['field_desc'] . '</td></tr>') . '<tr><td>' . cxpform_render_field($field) . '<p class="d">' . $field['field_desc'] . '</p></td></tr>';
			}
			include template('cxpform:cxpform_viewthread');
		}else{
			$return = '';
		}
		return $return;
	}
}
?>