<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
loadcache('plugin');

include_once(DISCUZ_ROOT . 'source/function/function_misc.php');
include_once(dirname(__FILE__) . '/include/function.php');

$op = isset($_GET['op']) ? trim($_GET['op']) : '';
switch($op){
	case 'showcount':
	    // 显示提交数
		$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
		$submit_count = DB::result_first('select count(A.id) from ' . DB::table('cxpform_contents') . ' A where A.form_id=' . $form_id);
		
		ob_end_clean();
		echo 'document.write(' . $submit_count . ');';
		define(FOOTERDISABLED, false);
		exit();
		break;
	case 'ip':
		// ip地址接口
		$ip = $_G['clientip'];
		$url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip;
		
		$curl_handle = curl_init(); 
        curl_setopt($curl_handle, CURLOPT_URL, $url); 
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2); 
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($curl_handle, CURLOPT_FAILONERROR,1); 
		curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10); 
        $file_content = curl_exec($curl_handle); 
        curl_close($curl_handle);
		ob_end_clean();
		$json = json_decode(str_replace(array('var remote_ip_info = ', ';'), '', $file_content));
		echo json_encode(['code' => 0, 'data' => ['province' => $json->province, 'city' => $json->city, 'district' => $json->district]]);
		define(FOOTERDISABLED, false);
		exit();
		break;
	default:
	
		break;
}
?>