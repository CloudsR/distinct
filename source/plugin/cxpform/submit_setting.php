<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

		$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
		cxpform_form_subnav($form_id, 'submit_setting');
		if(!submitcheck('formsubmit')) {
			
			$settinginfo = cxpform_submit_setting($form_id);
			
			if(count($settinginfo)>0){
				$islogin = $settinginfo['islogin'];
				$ip_perday = $settinginfo['ip_perday'];
				$user_perday = $settinginfo['user_perday'];
				$limit_ip = $settinginfo['limit_ip'];
				$iscaptcha = $settinginfo['iscaptcha'];
				$show_history = $settinginfo['show_history'];
				$show_title = $settinginfo['show_title'];
				$show_description = $settinginfo['show_description'];
				$success_message = $settinginfo['success_message'];
				$prefix1 = $settinginfo['prefix1'];
				$allowshowall = $settinginfo['allowshowall'];
				$startnum = $settinginfo['startnum'];
				$endnum = $settinginfo['endnum'];
				$gentype = $settinginfo['gentype'];
				$workday = maybe_unserialize($settinginfo['workday']);
				$early_days = $settinginfo['early_days'];
				$bookingcontent = $settinginfo['bookingcontent'];
				$send_bookingmsg = $settinginfo['send_bookingmsg'];
				$show_submit_count = $settinginfo['show_submit_count'];
				$allowoutsite = $settinginfo['allowoutsite'];
				$id = $settinginfo['id'];
			}else{
				$islogin = 0;
				$ip_perday = 5;
				$user_perday = 5;
				$limit_ip = '';
				$iscaptcha = 0;
				$show_history = 0;
				$show_title = 1;
				$show_description = 1;
				$success_message = '';
				$prefix1 = '';
				$allowshowall = 1;
				$startnum = '1000';
				$endnum = '9999';
				$gentype = 1;
				$workday = array('1', '2', '3', '4', '5', '6', '7');
				$early_days = '7';
				$bookingcontent = cxpform_lang('bookingcontent');
				$send_bookingmsg = 0;
				$show_submit_count = 0;
				$allowoutsite = 0;
				$id = '';
			}
			showformheader(XF_FORM_URL . 'pmod=form&op=submit_setting&form_id=' . $form_id. '&id=' . $id, 'formsubmit');
			showtableheader();
			showtitle(lang('plugin/cxpform', 'submit_setting'));
			showsetting(lang('plugin/cxpform', 'submit_islogin'), 'islogin', $islogin ? 1 : 0, 'radio', '', 0, '', '', '', true);
			showsetting(lang('plugin/cxpform', 'submit_allowoutsite'), 'allowoutsite', $allowoutsite ? 1 : 0, 'radio', '', 0, '', '', '', true);
			showsetting(lang('plugin/cxpform', 'submit_allowshowall'), 'allowshowall', $allowshowall ? 1 : 0, 'radio', '', 0, '', '', '', true);
			
			showsetting(lang('plugin/cxpform', 'submit_count'), 'show_submit_count', $show_submit_count ? 1 : 0, 'radio', '', 0, '', '', '', true);
			
			showsetting(lang('plugin/cxpform', 'submit_user_perday'), 'user_perday', $user_perday, 'text', '', 0, '', '', '', true);
			showsetting(lang('plugin/cxpform', 'submit_ip_perday'), 'ip_perday', $ip_perday, 'text', '', 0, '', '', '', true);
			showsetting(lang('plugin/cxpform', 'submit_limit_ip'), 'limit_ip', $limit_ip, 'textarea', '', 0, '', '', '', true);
			showsetting(lang('plugin/cxpform', 'submit_iscaptcha'), 'iscaptcha', $iscaptcha ? 1 : 0, 'radio', '', 0, '', '', '', true);
			showsetting(lang('plugin/cxpform', 'submit_show_submit_history'), 'show_history', $show_history ? 1 : 0, 'radio', '', 0, '', '', '', true);
			showsetting(lang('plugin/cxpform', 'submit_show_title'), 'show_title', $show_title ? 1 : 0, 'radio', '', 0, '', '', '', true);
			showsetting(lang('plugin/cxpform', 'submit_show_description'), 'show_description', $show_description ? 1 : 0, 'radio', '', 0, '', '', '', true);
			
			showsetting(cxpform_lang('submit_success_message'), 'success_message', $success_message, 'textarea', '', 0, '', '', '', true);
			
			showtitle(cxpform_lang('reservation_setting'));
			
			showsetting(cxpform_lang('sendbookingnumber'), 'send_bookingmsg', $send_bookingmsg ? 1 : 0, 'radio', '', 0, '', '', '', true);
			
			
			showtablerow('', array('class="td27"'), array(cxpform_lang('sendbookingnumberrule') . ':', ''));
			
			// 星期
			$arr = array('1' => cxpform_lang('week1'), '2' => cxpform_lang('week2'), '3' => cxpform_lang('week3'), '4' => cxpform_lang('week4'), '5' => cxpform_lang('week5'), '6' => cxpform_lang('week6'), '7' => cxpform_lang('week7'));
			$weekstr = '';
			foreach($arr as $k1=>$v1){
				$weekstr .= '<input type="checkbox" name="workday[]" value="' . $k1 . '" ' . (in_array($k1, $workday) ? 'checked="checked"': '') . ' /> <span style="font-weight:normal;">' . $v1 . '</span>';
			}
			
			showtablerow('', array('class="td27"'), array(cxpform_lang('rule_prefix') . ':<input type="text" name="prefix1" value="' . $prefix1 . '" /><br />' 
			. cxpform_lang('rule_startnum') . ':<input type="text" name="startnum" value="' . $startnum . '" size="5" /> - ' 
			. cxpform_lang('rule_endnum') . ':<input type="text" name="endnum" value="' . $endnum . '" size="5" /><br />' 
			. cxpform_lang('gentype') . ':<input type="radio" name="gentype" value="1" ' . ($gentype == '1' ? 'checked="checked"' : '') . ' /> ' 
			. cxpform_lang('gentype1') . ' <input type="radio" name="gentype" value="2" ' . ($gentype == '2' ? 'checked="checked"' : '') . ' /> ' . cxpform_lang('gentype2'), ''));
			
			showtablerow('', array('class="td27"'), array(cxpform_lang('workday') . ':', ''));
			showtablerow('', array('class="td27"'), array($weekstr, ''));
			
			showsetting(cxpform_lang('earlydays'), 'early_days', $early_days, 'text', '', 0, '', '', '', true);
			
			showsetting(cxpform_lang('sendbookingnumbercontent'), 'bookingcontent', $bookingcontent, 'textarea', '', 0, sprintf(cxpform_lang('bookingcontenttip'), XF_PLUGIN_URL . 'pmod=form&op=more_content&form_id=' . $form_id), '', '', true);
			
			showsubmit('formsubmit');
			showtablefooter();
			showformfooter();
		}else{
			$id = intval($_GET['id']);
			$islogin = intval(trim($_GET['islogin']));
			$ip_perday = intval(trim($_GET['ip_perday']));
			$user_perday = intval(trim($_GET['user_perday']));
			$limit_ip = addslashes(trim($_GET['limit_ip']));
			$iscaptcha = intval(trim($_GET['iscaptcha']));
			$show_history = intval(trim($_GET['show_history']));
			$show_title = intval(trim($_GET['show_title']));
			$show_description = intval(trim($_GET['show_description']));
			$success_message = trim($_GET['success_message']);
			
			$prefix1 = trim($_GET['prefix1']);
			$allowoutsite = intval($_GET['allowoutsite']);
			$allowshowall = intval($_GET['allowshowall']);
			$startnum = intval($_GET['startnum']);
			$endnum = intval($_GET['endnum']);
			$gentype = intval($_GET['gentype']);
			$send_bookingmsg = intval($_GET['send_bookingmsg']);
			$show_submit_count = intval($_GET['show_submit_count']);
			
			$workday = $_GET['workday'];
			
			$early_days = intval($_GET['early_days']);
			$bookingcontent = trim($_GET['bookingcontent']);
			
			$data = array(
				'form_id' => $form_id,
				'islogin' => $islogin,
				'ip_perday' => $ip_perday,
				'user_perday' => $user_perday,
				'limit_ip' => $limit_ip,
				'iscaptcha' => $iscaptcha,
				'show_history' => $show_history,
				'show_title' => $show_title,
				'show_description' => $show_description,
				'success_message' => $success_message,
				'prefix1' => $prefix1,
				'allowoutsite' => $allowoutsite,
				'allowshowall' => $allowshowall,
				'startnum' => $startnum,
				'endnum' => $endnum,
				'gentype' => $gentype,
				'workday' => maybe_serialize($workday),
				'early_days' => $early_days,
				'bookingcontent' => $bookingcontent,
				'send_bookingmsg' => $send_bookingmsg,
				'show_submit_count' => $show_submit_count,
			);
			// var_dump($data);
			// exit;
			if($id == ''){
				DB::insert('cxpform_submit_setting', $data);
			}else{
				DB::update('cxpform_submit_setting', $data, DB::field('id', $id));
			}
			
			cpmsg(lang('plugin/cxpform', 'submit_success'), XF_PLUGIN_URL . "pmod=form&op=submit_setting&form_id=" . $form_id . '', 'succeed');			
		}
?>