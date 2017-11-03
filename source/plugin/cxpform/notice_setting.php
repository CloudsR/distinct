<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

		$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
		cxpform_form_subnav($form_id, 'notice_setting');
		if(!submitcheck('formsubmit')) {
			
		
			$settinginfo = cxpform_notice_setting($form_id);
			if(count($settinginfo)>0){
				$status = $settinginfo['status'];
				$message_status = $settinginfo['message_status'];
				$message_to = $settinginfo['message_to'];
				$message_title = $settinginfo['message_title'];
				$message_content = $settinginfo['message_content'];
				$email_status = $settinginfo['email_status'];
				$email_to = $settinginfo['email_to'];
				$email_title = $settinginfo['email_title'];
				$email_content = $settinginfo['email_content'];
				$sms_status = $settinginfo['sms_status'];
				$sms_to = $settinginfo['sms_to'];
				$sms_content = $settinginfo['sms_content'];
				$weixin_status = $settinginfo['weixin_status'];
				$id = $settinginfo['id'];
			}else{
				$status = 0;
				$message_status = 1;
				$message_to = '1';
				$message_title = '';
				$message_content = '';
				$email_status = 0;
				$email_to = '';
				$email_title = '';
				$email_content = '';
				$sms_status = 0;
				$sms_to = '';
				$sms_content = '';
				$weixin_status = 0;
				$id = '';
			}		
			
			$fields = cxpform_field_list($form_id);
			$tags = '';
			foreach($fields as $field){
				$tags .= '<li><font style="color:blue;">{field_' . $field['id'] . '}</font>' . lang('plugin/cxpform', 'maohao') . $field['field_label'] . '</li>';
			}
			showtips('<ul><li>' . lang('plugin/cxpform', 'notice_setting_example') . '</li><li><strong>' . lang('plugin/cxpform', 'notice_setting_tip') . '</strong></li>' . $tags . '</ul>');			
			
			showformheader(XF_FORM_URL . 'pmod=form&op=notice_setting&form_id=' . $form_id . '&id=' . $id, 'formsubmit');
			showtableheader();
			showtitle(lang('plugin/cxpform', 'notice_setting'));
			showsetting(lang('plugin/cxpform', 'notice_setting_status'), 'status', $status ? 1 : 0, 'radio', '', 0, '', '', '', true);
			
			showtitle(lang('plugin/cxpform', 'notice_message'));
			showsetting(lang('plugin/cxpform', 'notice_message_status'), 'message_status', $message_status ? 1 : 0, 'radio', '', 0, '', '', '', true);
			showsetting(lang('plugin/cxpform', 'notice_message_to'), 'message_to', $message_to, 'textarea', '', 0, cxpform_lang('multi_uid'), '', '', true);
			showsetting(lang('plugin/cxpform', 'notice_message_title'), 'message_title', $message_title, 'text', '', 0, '', '', '', true);
			
			
			showsetting(lang('plugin/cxpform', 'notice_message_content'), 'message_content', $message_content, 'textarea', '', 0, '', '', '', true);
			
			showtitle(cxpform_lang('notice_email'));
			showsetting(cxpform_lang('notice_email_status'), 'email_status', $email_status ? 1 : 0, 'radio', '', 0, '', '', '', true);
			showsetting(cxpform_lang('notice_email_to'), 'email_to', $email_to, 'textarea', '', 0, '', '', '', true);
			showsetting(cxpform_lang('notice_email_title'), 'email_title', $email_title, 'text', '', 0, '', '', '', true);
			// showsetting(cxpform_lang('notice_email_content'), 'email_content', $email_content, 'textarea', '', 0, '', '', '', true);
			echo '<tr><td colspan="15" class="td27" s="1">' . cxpform_lang('notice_email_content') . ':</td></tr>';
			echo '<tr><td colspan="15" class="rowform"><script type="text/plain" name="email_content" id="myEditor_notice_email_content" style="width:100%;height:240px;">' . $email_content . '</script><script type="text/javascript">var ue = UE.getEditor(\'myEditor_notice_email_content\');</script></td></tr>';
			
			// showtitle('短信提醒');
			// showsetting('短信提醒开关', 'sms_status', $sms_status ? 1 : 0, 'radio', '', 0, '', '', '', true);
			// showsetting('接收号码', 'sms_to', $sms_to, 'textarea', '', 0, '', '', '', true);
			// showsetting('短信内容', 'sms_content', $sms_content, 'textarea', '', 0, '', '', '', true);
			
			// showtitle('微信提醒');
			// showsetting('微信提醒开关', 'weixin_status', $weixin_status ? 1 : 0, 'radio', '', 0, '', '', '', true);
			// showsetting('代码', '', '', 'textarea', '', 0, '', '', '', true);
			showsubmit('formsubmit');
			showtablefooter();
			showformfooter();
		}else{
			$id = intval($_GET['id']);
			$status = intval($_GET['status']);
			$message_status = intval($_GET['message_status']);
			$message_to = trim($_GET['message_to']);
			$message_title = trim($_GET['message_title']);
			$message_content = trim($_GET['message_content']); // 这个用户需要编辑html
			$email_status = trim($_GET['email_status']);
			$email_to = trim($_GET['email_to']);
			$email_title = trim($_GET['email_title']);
			$email_content = trim($_GET['email_content']);
			$sms_status = trim($_GET['sms_status']);
			$sms_to = trim($_GET['sms_to']);
			$sms_content = trim($_GET['sms_content']);
			$weixin_status = trim($_GET['weixin_status']);
			
			$data = array(
				'form_id' => $form_id,
				'status' => $status,
				'message_status' => $message_status,
				'message_to' => $message_to,
				'message_title' => $message_title,
				'message_content' => $message_content,
				'email_status' => $email_status,
				'email_to' => $email_to,
				'email_title' => $email_title,
				'email_content' => $email_content,
				'sms_status' => $sms_status,
				'sms_to' => $sms_to,
				'sms_content' => $sms_content,
				'weixin_status' => $weixin_status,
			);
			// var_dump($data);
			// exit;
			if($id == ''){
				DB::insert('cxpform_notice_setting', $data);
			}else{
				DB::update('cxpform_notice_setting', $data, DB::field('id', $id));
			}
			
			cpmsg(lang('plugin/cxpform', 'notice_setting_success'), XF_PLUGIN_URL . "pmod=form&op=notice_setting&form_id=" . $form_id . '', 'succeed');				
		}
?>