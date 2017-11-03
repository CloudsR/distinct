<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
// loadcache('plugin');

include_once(DISCUZ_ROOT . 'source/function/function_misc.php');
include_once(dirname(__FILE__) . '/include/function.php');

$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
$tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;

// 提交设置
$submit_setting = cxpform_submit_setting($form_id);

if (!$submit_setting['allowoutsite'] && $formhash != FORMHASH) {
	echo lang('plugin/cxpform', 'errformhash');
}else{
	// 表单信息
	$forminfo = cxpform_form_info($form_id);
	$curtime = $_SERVER['REQUEST_TIME'];
	if(count($forminfo) > 0){
			
			// 提醒设置
			$notice_setting = cxpform_notice_setting($form_id);
			// 插到cxpform_contents

			// include_once(dirname(__FILE__) . '/include/securimage/securimage.php');
			$err_str = '';
			
			$bookingtime = isset($_GET['bookingtime']) ? trim($_GET['bookingtime']) : '';
			$bookingtime1 = $bookingtime;
			$bookingtime = dmktime($bookingtime);
			$bookingnumber = isset($_GET['bookingnumber']) ? trim($_GET['bookingnumber']) : '';				
			
			// if($submit_setting['iscaptcha']){
				// $securimage = new Securimage();
				// if ($securimage->check(trim($_GET['captcha_code'])) === false) {
					// showmessage('验证码不正确', $_G['siteurl'] . 'plugin.php?id=cxpform:test&form_id=' . $form_id);
					// echo '验证码不正确';
					// $err_str .= '<p>' . lang('plugin/cxpform', 'captcha') . lang('plugin/cxpform', 'errorelement') . '</p>';
				// }
			// }	

			// 表单状态是停止的
			if($forminfo['status'] == '0'){
				$err_str .= '<p>' . lang('plugin/cxpform', 'can_not_submit') . '</p>';
			}else{
				// 表单设了开始时间，并且开始时间大于当前时间，那就是还没开始
				if($forminfo['startdate'] != '0' && $forminfo['startdate'] > $curtime){
					$err_str .= '<p>' . lang('plugin/cxpform', 'can_not_submit1') . '</p>';
				}else{
					if($forminfo['enddate'] != '0' && $forminfo['enddate'] < $curtime){
						$err_str .= '<p>' . lang('plugin/cxpform', 'can_not_submit') . '</p>';
					}
				}
				
			}		
			
			// 是否需要登录
			if($submit_setting['islogin']){
				if($_G['uid'] > 0){
				}else{
					// showmessage('请先登录后填写表单', "", array(), array('showmsg' => 1, 'login' => 1));
					$err_str .= lang('plugin/cxpform', 'please_login');
					// echo '';
				}
			}
			// 是否在IP黑名单中
			if($submit_setting['limit_ip'] !== ''){
				$limit_ip_arr = explode("\r\n", $submit_setting['limit_ip']);
				if(in_array($_G['clientip'], $limit_ip_arr)){
					// showmessage('您的IP不能填写表单', $_G['siteurl']);
					$err_str .= lang('plugin/cxpform', 'ip_limit');
				}
			}
			
			// 帐号限制
			if($_G['uid'] > 0){
				$user_today_count = DB::result_first("select count(*) from " . DB::table('cxpform_contents') . " where form_id='" . $form_id . "' and user_id='" . $_G['uid'] . "' and addtime>='" . strtotime(date('Y-m-d 00:00:00')) . "'");

				if($user_today_count > $submit_setting['user_perday']){
					// showmessage('该帐号今天不能填写了', $_G['siteurl']);
					// echo '该帐号今天不能填写了';
					$err_str .= lang('plugin/cxpform', 'user_today_limit');
				}
			}			
			
			// IP次数限制
			$ip_today_count = DB::result_first("select count(*) from " . DB::table('cxpform_contents') . " where form_id='" . $form_id . "' and ip='" . $_G['clientip'] . "' and addtime>='" . strtotime(date('Y-m-d 00:00:00')) . "'");
			if($ip_today_count > $submit_setting['ip_perday']){
				// showmessage('该IP今天不能填写了', $_G['siteurl']);
				// echo '该IP今天不能填写了';
				$err_str .= lang('plugin/cxpform', 'ip_today_limit');
			}		
			

			
			// 程序上验证字段
			$fields = cxpform_field_list($form_id);
			
			//表单验证
			
			foreach($_GET as $k=>$v){
				$field_id = substr($k, 6);
				if(array_key_exists($field_id, $fields)){
					if($fields[$field_id]['isrequired']){
						if(trim($v) === ''){
							$err_str .= '<p>' . $fields[$field_id]['field_label'] . lang('plugin/cxpform', 'errorelement') .  '</p>';
						}	
					}
				}
			}
			
			// 如果是预约的表单，要生成预约号。
			if($forminfo['form_type'] == '3'){	
				// 总共分配到得号数
				$startnum = intval($submit_setting['startnum']);
				$endnum = intval($submit_setting['endnum']);
				$totalnum = $endnum - $startnum + 1;
				// 判断提前天数
				if(dmktime(dgmdate(TIMESTAMP + 86400 * $submit_setting['early_days'], 'Y-m-d')) < $bookingtime){
					$err_str .= cxpform_lang('bookingerror1');
				}
				// 判断星期
				$workday = maybe_unserialize($submit_setting['workday']);
				$w1 = dgmdate($bookingtime, 'w');
				$w1 = $w1==0 ? 7 : $w1;
				if(!in_array($w1, $workday)){
					$err_str .= cxpform_lang('bookingerror2');
				}
				// 预约号生成方式
				$gentype = $submit_setting['gentype'];
				// 前缀
				$prefix1 = $submit_setting['prefix1'];
				// 连续
				if(intval($gentype) === 1){
					// 判断预约日期的预约数
					$fbq = DB::fetch_first('select ucount from ' . DB::table('cxpform_queue') . ' where form_id='.$form_id.' and bookingtime='.$bookingtime);
					if($fbq){
						if(intval($fbq['ucount']) >= intval($totalnum)){
							$err_str .= cxpform_lang('bookingerror3');
						}else{
							// 更新预约当天的预约数
							$data01 = array(
								'ucount' => $fbq['ucount'] + 1,
							);
							DB::update('cxpform_queue', $data01, array('form_id' => $form_id, 'bookingtime' => $bookingtime));
							// 产生预约号
							$bookingnumber = $prefix1 .''. ($startnum + $fbq['ucount']);
						}
					}else{
						// 不存在记录，新建
						$data02 = array(
							'form_id' => $form_id,
							'ucount' => 1,
							'bookingtime' => $bookingtime,
						);
						DB::insert('cxpform_queue', $data02);
						// 产生预约号
						$bookingnumber = $prefix1 .''. $startnum;
					}					
				}
				// 随机
				if(intval($gentype) === 2){
					$bookingnumber = cxpform_genrandcode($bookingtime, $form_id, $prefix1, $startnum, $endnum);
				}	
			}			

			if($err_str !== ''){
				echo $err_str;
			}else{		
				
				$area = convertip($_G['clientip']);
				
				$fromurl = isset($_GET['fromurl']) ? dhtmlspecialchars(trim($_GET['fromurl'])) : '';
				// 处理状态
				$status_id = DB::result_first('select id from ' . DB::table('cxpform_content_status') . ' where form_id=' . $form_id . ' and is_default=1');
				
				$data = array(
					'ip' => $_G['clientip'],
					'area' => $area,
					'user_id' => $_G['uid'],
					'username' => $_G['username'],
					'form_id' => $form_id,
					'fromurl' => $fromurl,
					'addtime' => $curtime,
					'status' => $status_id,
				);
				$content_id = DB::insert('cxpform_contents', $data, 1);
				// 插到cxpform_content_extra
				$content_v = array();
				$content_v1 = array();
				
				// 扩展数据的分表名
				$extra_table_name = cxpform_get_hash_table('cxpform_content_extra', $content_id, XF_EXTRA_TABLE_NUM);
				
				foreach($_FILES as $k1=>$v1){
					$field_id = substr($k1, 6);
					if(array_key_exists($field_id, $fields)){
					
						if(file_exists(libfile('class/upload'))){
							require_once libfile('class/upload');
						}else{
							require_once libfile('discuz/upload', 'class');
						}
						$upload = new discuz_upload();	
						if($_FILES['field_' . $field_id]){
							if($upload->init($_FILES['field_' . $field_id], 'common') && $upload->save(1)){
								$v1 = $_G['setting']['attachurl'] . 'common/' . $upload->attach['attachment'];
							}else{
								$v1 = '';
							}						
						}else{
							$v1 = '';
						}	
						
						$data11 = array(
							'form_id' => $form_id,
							'field_id' => $field_id,
							'field_value' => $v1,
							'content_id' => $content_id,
						);	
						DB::insert($extra_table_name, $data11);
						$content_v[] = $v1;
						$content_v1[$field_id] = $v1;
					}
				}
				
				foreach($_GET as $k=>$v){
					$field_id = substr($k, 6);
					if(array_key_exists($field_id, $fields)){
						if($fields[$field_id]['field_type'] == '4'){
							// 是复选框
							foreach($v as $k1=>$v1){
								$v[$k1] = cxpform_in_charset($v1);
								// 其他选项
								if($v1 == 'other_field'){
									$v3 = cxpform_in_charset(addslashes(trim($_GET[$k . '_other'])));
									$v[] = $v3;
									$data2 = array(
										'form_id' => $form_id,
										'field_id' => $field_id,
										'content_id' => $content_id,
										'option_id' => 0,
										'field_value' => $v3,
									);
									DB::insert('cxpform_field_value', $data2);									
								}else{
									$data2 = array(
										'form_id' => $form_id,
										'field_id' => $field_id,
										'content_id' => $content_id,
										'option_id' => $v1
									);
									DB::insert('cxpform_field_value', $data2);								
								}
							}
							
							$v = maybe_serialize($v);
						}elseif($fields[$field_id]['field_type'] == '3' || $fields[$field_id]['field_type'] == '13'){
							$v = cxpform_in_charset($v);
							// 单选框或者下拉框
							if($v == 'other_field'){				
								$v = cxpform_in_charset(addslashes(trim($_GET[$k . '_other'])));
								$data2 = array(
									'form_id' => $form_id,
									'field_id' => $field_id,
									'content_id' => $content_id,
									'option_id' => 0,
									'field_value' => $v,
								);
								DB::insert('cxpform_field_value', $data2);								
							}else{
								$data2 = array(
									'form_id' => $form_id,
									'field_id' => $field_id,
									'content_id' => $content_id,
									'option_id' => $v
								);
								DB::insert('cxpform_field_value', $data2);							
								$v = $v;
							}
						}else{
							$v = cxpform_in_charset($v);
						}
						
						$data1 = array(
							'form_id' => $form_id,
							'field_id' => $field_id,
							'field_value' => $v,
							'content_id' => $content_id,
						);
						DB::insert($extra_table_name, $data1);
						
						$content_v[] = $v;
						$content_v1[$field_id] = $v;
					}
				}
				$defaultparams = $data;
				$defaultparams['form_name'] = $forminfo['title'];					
				$defaultparams['addtime'] = date('Y-m-d H:i:s', $curtime);

				$defaultparams['bookingtime'] = $bookingtime1;
				$defaultparams['bookingnumber'] = $bookingnumber;
				
				// 短消息
				if($notice_setting['status']){
					// 总开关
					if($notice_setting['message_status']){
						$message_content = cxpform_notice_content($fields, $notice_setting['message_content'], $content_v, $defaultparams);
						// var_dump($fields, $content_v, $message_content);
						// 短消息
						// sendpm($notice_setting['message_to'], $notice_setting['message_title'], $notice_setting['message_content']);
						// 应用提醒
						$message_to = $notice_setting['message_to'];
						if(strpos($message_to, ',') !== FALSE){
							$toarr = explode(',', $message_to);
							foreach($toarr as $v){
								notification_add($v, 'app', $notice_setting['message_title'] . '<br />' . $message_content);
							}
						}else{
							notification_add($notice_setting['message_to'], 'app', $notice_setting['message_title'] . '<br />' . $message_content);
						}
					}
					// 短信
					if($notice_setting['sms_status']){
						
					}
					// 邮件
					if($notice_setting['email_status']){
						require_once libfile('function/mail');
						$email_content = cxpform_notice_content($fields, $notice_setting['email_content'], $content_v, $defaultparams);
						sendmail($notice_setting['email_to'], $notice_setting['email_title'], $email_content);
					}
					// 微信
					if($notice_setting['weixin_status']){
						
					}
				}

				
				// showmessage('提交成功', $_G['siteurl']);
				if($forminfo['visibilityform'] == '1' && $_G['uid'] > 0){
					// 增加一条回复
					include_once libfile('function/forum');
					$thread = C::t('forum_thread')->fetch($tid);
					$forum = C::t('forum_forum')->fetch($thread['fid']);
					$pinvisible = $forum['modnewposts'] ? -2 : 0;
					// 帖子内容
					$form_id = DB::result_first("SELECT id FROM ".DB::table('cxpforms')." WHERE tid='$tid'");
					$forminfo = cxpform_form_info($form_id);
					$fields =cxpform_field_list($form_id);
					$form_element = '';
					foreach($fields as $field){
						$form_element .= '<tr><td><strong>' . $field['field_label'] . '</strong>' . ($field['isrequired'] ? '<span class="rq">*</span>' : '') . '</td></tr>' . ($field['field_desc'] == '' ? '' : '<tr><td>' . $field['field_desc'] . '</td></tr>') . '<tr><td>' . cxpform_render_field_value($field, $content_v1[$field['id']]) . '<p class="d">' . $field['field_desc'] . '</p></td></tr>';
					}
					include template('cxpform:cxpform_viewpost');
					$message = $return;
					$pid = insertpost(array(
						'fid' => $thread['fid'],
						'tid' => $thread['tid'],
						'first' => '0',
						'author' => $_G['username'],
						'authorid' => $_G['uid'],
						'subject' => '',
						'dateline' => $_G['timestamp'],
						'message' => $message,
						'useip' => '',
						'invisible' => $pinvisible,
						'anonymous' => '0',
						'usesig' => '0',
						'htmlon' => '1',
						'bbcodeoff' => '0',
						'smileyoff' => '0',
						'parseurloff' => '0',
						'attachment' => '0',
						'status' => 1024,
					));
					DB::query("UPDATE " . DB::table('common_member_count') . " SET posts=posts+1 WHERE uid='" . $_G['uid'] . "'");//更新数
					
					// 回复的积分策略
					$score   = C::t('common_member_count')->fetch($_G['uid']);
					$ruleinfo   = DB::fetch_first("SELECT * FROM " . DB::table('common_credit_rule') . " WHERE `action`='reply'");
					
					if(count($ruleinfo) > 0){
						foreach($_G['setting']['extcredits'] as $id => $credit) {
							if($ruleinfo['extcredits' . $id] > 0){
								DB::update(
									'common_member_count', 
								array('extcredits' . $id => $score['extcredits' . $id] + $ruleinfo['extcredits' . $id]),
								array('uid' => $_G['uid'])
								);							
							}
						}					
					}
					
					if($pinvisible) {
						updatemoderate('pid', array($pid));
						C::t('forum_forum')->update_forum_counter($thread['fid'], 0, 0, 1, 1);
					}else{
						$fieldarr = array(
							'lastposter' => array($_G['username']),
							'replies' => 1,
						);
						if($thread['lastpost'] < $_G['timestamp']) {
							$fieldarr['lastpost'] = array($_G['timestamp']);
						}
						C::t('forum_thread')->increase($tid, $fieldarr);
						$postionid = C::t('forum_post')->fetch_maxposition_by_tid($thread['posttableid'], $tid);
						C::t('forum_thread')->update($tid, array('maxposition' => $postionid));

						$lastpost = "$thread[tid]\t$thread[subject]\t$_G[timestamp]\t".'';
						C::t('forum_forum')->update($thread['fid'], array('lastpost' => $lastpost));
						C::t('forum_forum')->update_forum_counter($thread['fid'], 0, 1, 1);
						if($forum['type'] == 'sub') {
							C::t('forum_forum')->update($forum['fup'], array('lastpost' => $lastpost));
						}				
					}
				}
				
				// 如果是预约表单
				if($forminfo['form_type'] == '3'){
					if($submit_setting['send_bookingmsg'] == '1'){
					// 把预约号发送短信，应用通知给用户
						$bookingcontent = $submit_setting['bookingcontent'];
						$content1 = cxpform_get_sms($form_id, $addtime, $bookingtime1, $ip, $area);
						if($content1 !== FALSE) $bookingcontent = $content1;
						notification_add($_G['uid'], 'app', cxpform_notice_content($fields, $bookingcontent, $content_v, $defaultparams));
					}
				}				
				
				if($submit_setting['success_message'] != ''){
					echo $submit_setting['success_message'];
				}else{
					echo lang('plugin/cxpform', 'success');
				}
			}
		

		
	}else{
		// showmessage('不存在的表单', $_G['siteurl']);
		echo lang('plugin/cxpform', 'errorform');
	}

}
// ==================================================================================
?>