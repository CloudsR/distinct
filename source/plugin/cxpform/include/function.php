<?php 
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
// 分表的数目:默认10个表
define('XF_EXTRA_TABLE_NUM', 10);
// cxpform语言包
function cxpform_lang($key){
	return lang('plugin/cxpform', $key);
}

// 分表表名
function cxpform_get_hash_table($table,$code,$s=100){
	$hash = sprintf("%u", crc32($code));
	$hash1 = intval(fmod($hash, $s));
	// return $table."_".$hash1;
	return $table;
}

function cxpform_fgetcsv(& $handle, $length = null, $d = ',', $e = '"') {
     $d = preg_quote($d);
     $e = preg_quote($e);
     $_line = "";
     $eof=false;
     while ($eof != true) {
         $_line .= (empty ($length) ? fgets($handle) : fgets($handle, $length));
         $itemcnt = preg_match_all('/' . $e . '/', $_line, $dummy);
         if ($itemcnt % 2 == 0)
             $eof = true;
     }
     $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
     $_csv_pattern = '/(' . $e . '[^' . $e . ']*(?:' . $e . $e . '[^' . $e . ']*)*' . $e . '|[^' . $d . ']*)' . $d . '/';
     preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
     $_csv_data = $_csv_matches[1];
     for ($_csv_i = 0; $_csv_i < count($_csv_data); $_csv_i++) {
         $_csv_data[$_csv_i] = preg_replace('/^' . $e . '(.*)' . $e . '$/s', '$1' , $_csv_data[$_csv_i]);
         $_csv_data[$_csv_i] = str_replace($e . $e, $e, $_csv_data[$_csv_i]);
     }
     return empty ($_line) ? false : $_csv_data;
}


function cxpform_input_csv($handle) { 
    $out = array (); 
    $n = 0; 
    while ($data = cxpform_fgetcsv($handle, 10000)) { 
        $num = count($data); 
        for ($i = 0; $i < $num; $i++) { 
            $out[$n][$i] = $data[$i]; 
        } 
        $n++; 
    } 
    return $out; 
} 

//导成csv编码
function cxpform_csv_charset($content){
	return iconv(CHARSET, 'utf-8', $content);
	// if(strtoupper(CHARSET) === 'GBK'){
		// return $content;
	// }elseif(strtolower(CHARSET) === 'big5'){	
		// return iconv('big5', 'gbk', $content);
	// }else{
		// return iconv('utf-8', 'gbk', $content);
	// }
}

// 自定义bootstrap分页
function cxpform_pagination($count, $perpage, $page, $url, $showFirstAndLast = 1){
	$queryUrl = '';
	$params = parse_url($url);
	$num_links = 2;
	$queryUrl = '&' . $params['query'];
	  // Get the number of pages
      $pages = ceil($count / $perpage);
	  $start = (($page - $num_links) > 0) ? $page - ($num_links - 1) : 1;
	  $end   = (($page + $num_links) < $pages) ? $page + $num_links : $pages;
      // var_dump($start);
      // If we have more then one pages
      if ($pages > 1) {
        // Assign the 'previous page' link into the array if we are not on the first page
        if ($page != 1) {
          if ($showFirstAndLast) {
            $plinks[] = '<li><a href="?page=1'.$queryUrl.'">&laquo;&laquo;</a></li>';
          }
          $plinks[] = '<li><a href="?page='.($page - 1).$queryUrl.'">&laquo;</a></li>';
        }
        
        // Assign all the page numbers & links to the array
        // for ($j = 1; $j < ($pages + 1); $j++) {
		// var_dump($start, $end);
		for ($j = $start; $j <= $end; $j++){
          if ($page == $j) {
            $links[] = '<li class="active"><a>'.$j.'</a></li>'; // If we are on the same page as the current item
          } else {
            $links[] = '<li><a href="?page='.$j.$queryUrl.'">'.$j.'</a></li>'; // add the link to the array
          }
        }

        // Assign the 'next page' if we are not on the last page
        if ($page < $pages) {
          $slinks[] = '<li><a href="?page='.($page + 1).$queryUrl.'">&raquo;</a></li>';
          if ($showFirstAndLast) {
            $slinks[] = '<li><a href="?page='.($pages).$queryUrl.'">&raquo;&raquo;</a></li>';
          }
        }
        
        // Push the array into a string using any some glue
        return implode(' ', $plinks).implode('', $links).implode(' ', $slinks);
      }	
}

// 数据入库编码

function cxpform_is_utf8_1($str)   
{   
    $c=0; $b=0;   
    $bits=0;   
    $len=strlen($str);   
    for($i=0; $i<$len; $i++){   
        $c=ord($str[$i]);   
        if($c > 128){   
            if(($c >= 254)) return false;   
            elseif($c >= 252) $bits=6;   
            elseif($c >= 248) $bits=5;   
            elseif($c >= 240) $bits=4;   
            elseif($c >= 224) $bits=3;   
            elseif($c >= 192) $bits=2;   
            else return false;   
            if(($i+$bits) > $len) return false;   
            while($bits > 1){   
                $i++;   
                $b=ord($str[$i]);   
                if($b < 128 || $b > 191) return false;   
                $bits--;   
            }   
        }   
    }   
    return true;   
}

function cxpform_in_charset($v){
	if(!cxpform_is_utf8_1($v)) $v = diconv($v, CHARSET, "utf-8");
		
	if(strtolower(CHARSET) == 'gbk'){
		$v = dhtmlspecialchars(diconv($v, "UTF-8","gbk"));
	}elseif(strtolower(CHARSET) == 'big5'){
		$v = dhtmlspecialchars(diconv($v, "UTF-8","big5"));
	}else{
		$v = dhtmlspecialchars($v);
	}	
	
	return $v;
}

define('XF_PLUGIN_URL', $_G['siteurl'] . ADMINSCRIPT . '?action=plugins&operation=config&do=' . $pluginid . '&identifier=cxpform&');
define('XF_FORM_URL', 'plugins&operation=config&do=' . $pluginid . '&identifier=cxpform&');

// 表单类型
$cxpform_type = array(
	array('1', lang('plugin/cxpform', 'form_type1')),
	array('2', lang('plugin/cxpform', 'form_type2')),
	array('3', lang('plugin/cxpform', 'form_type3')),
);
$cxpform_type_arr = array();
foreach($cxpform_type as $xtype){
	$cxpform_type_arr[$xtype[0]] = $xtype[1];
}

// 字段类型
$cxpform_field_type = array(
	array('1', lang('plugin/cxpform', 'field1')),
	array('2', lang('plugin/cxpform', 'field2')),
	array('3', lang('plugin/cxpform', 'field3')),
	array('4', lang('plugin/cxpform', 'field4')),
	// array('5', lang('plugin/cxpform', 'field5')),
	// array('6', lang('plugin/cxpform', 'field6')),
	array('7', lang('plugin/cxpform', 'field7')),
	array('8', lang('plugin/cxpform', 'field8')),
	array('9', lang('plugin/cxpform', 'field9')),
	array('10', lang('plugin/cxpform', 'field10')),
	array('11', lang('plugin/cxpform', 'field11')),
	array('12', lang('plugin/cxpform', 'field12')),
	array('16', lang('plugin/cxpform', 'field16')),
	array('13', lang('plugin/cxpform', 'field13')),
	array('14', lang('plugin/cxpform', 'field14')),
	// array('15', lang('plugin/cxpform', 'field15')),
	array('17', lang('plugin/cxpform', 'field17')),
	array('18', lang('plugin/cxpform', 'field18')),
);



// 变形字段类型
$cxpform_field_type_arr = array();
foreach($cxpform_field_type as $type){
	$cxpform_field_type_arr[$type[0]] = $type[1];
}

// 表单列表
if(!function_exists('cxpform_form_list')){
	function cxpform_form_list(){
		$forms = DB::fetch_all('select * from ' . DB::table('cxpforms'));
		$arr = array();
		foreach($forms as $form){
			$arr[$form['id']] = $form;
		}
		return $arr;
	}
}

// 更多设置判断及得到要发送的内容
function cxpform_get_sms($form_id, $addtime, $bookingtime, $ip='', $area='', $mobile=''){

	// 更多内容
	$aclsq = DB::fetch_all('select * from ' . DB::table('cxpform_more_content') . ' where form_id=' . $form_id);
	foreach($aclsq as $aclsrow){
		$contentid = $aclsrow['id'];
		// 得到短信的限制
		$aclsq1 = DB::fetch_all('select * from ' . DB::table('cxpform_more_content_acls') . ' where content_id='.$contentid.' order by executionorder asc');
		$str = '';
		foreach($aclsq1 as $aclsrow1){
			// logical , filtertype, comparison, data, executionorder
			$str .= $aclsrow1['logical'];
			$comparison_result = false;
			// 构造出sql，判定要发送那条短信
			switch($aclsrow1['filtertype']){
				case 'addtime': // 添加时间
					$comparison = $aclsrow1['comparison'];
					$data = strtotime($aclsrow1['data']);
					if(eval("return {$addtime} {$comparison} {$data};")){
						$param1 = true;
					}else{
						$param1 = false;
					}
					$comparison_result = $param1;
					break;
				case 'bookingtime': // 预约时间
					$comparison = $aclsrow1['comparison'];
					$data = $aclsrow1['data'];
					if(eval("return {$bookingtime} {$comparison} {$data};")){
						$param2 = true;
					}else{
						$param2 = false;
					}
					$comparison_result = $param2;
					break;
				case 'bookingperson': // 预约人数
					$sql = ' select count(*) as c from ' . DB::table('cxpform_contents') . ' where form_id='.$form_id;
					$row = DB::fetch_first($sql);
					$personcount = $row['c'];
					$comparison = $aclsrow1['comparison'];
					$data = $aclsrow1['data'];
					if(eval("return {$personcount} {$comparison} {$data};")){
						$param3 = true;
					}else{
						$param3 = false;
					}
					$comparison_result = $param3;
					break;
				case 'ip': // ip
					$comparison = $aclsrow1['comparison'];
					$data = $aclsrow1['data'];
					if(eval("return '{$ip}' {$comparison} '{$data}';")){
						$param4 = true;
					}else{
						$param4 = false;
					}
					$comparison_result = $param4;
					break;
				case 'area': // 地区
					$comparison = $aclsrow1['comparison'];
					$data = $aclsrow1['data'];
					switch($comparison){
						case '==':
						case '!=':
							if(eval("return {$area} {$comparison} {$data};")){
								$param5 = true;
							}else{
								$param5 = false;
							}						
							break;
						case '=~': // 包含
							if(strpos($data, $area)!==false){
								$param5 = true;
							}else{
								$param5 = false;
							}
							break;
						case '!~': // 不包含
							if(strpos($data, $area)===false){
								$param5 = true;
							}else{
								$param5 = false;
							}							
							break;
						case '=x': // 正则匹配
							if(preg_match($data, $area)!==false){
								$param5 = true;
							}else{
								$param5 = false;
							}						
							break;
						case '!x': // 正则不匹配
							if(preg_match($data, $area)===false){
								$param5 = true;
							}else{
								$param5 = false;
							}						
							break;
					}
					$comparison_result = $param5;
					break;
				case 'mobile': // 手机号码
					$comparison = $aclsrow1['comparison'];
					$data = $aclsrow1['data'];
					if(eval("return {$mobile} {$comparison} '{$data}';")){
						$param6 = true;
					}else{
						$param6 = false;
					}
					$comparison_result = $param6;
					break;
			}
			
			$str .= $comparison_result ? ' true ' : ' false ';
		}
		
		$str = ltrim(ltrim($str, 'and '), 'or ');
		
		if(eval("return $str;")){
			// 返回短信内容
			return $aclsrow['content'];
		}else{
			continue;
		}
	}
	return false;
}
// 随机生成预约号，判断预约号是否已经存在
function cxpform_genrandcode($bookingtime, $form_id, $prefix1, $startnum, $endnum){
	$bookingnumber = $prefix1 .''. rand($startnum, $endnum);
	$row = DB::result_first("select count(id) as c from " . DB::table('cxpform_contents') . " where form_id='" . $form_id . "' and bookingtime='" . $bookingtime . "' and bookingnumber='" . $bookingnumber . "'");
	if($row > 0){
		// 已经存在
		return cxpform_genrandcode($bookingtime, $form_id, $prefix1, $startnum, $endnum);
	}else{
		// 不存在
		return $bookingnumber;
	}
}

// 表单信息
if(!function_exists('cxpform_form_info')){
	function cxpform_form_info($form_id){
		$forminfo = DB::fetch_first('select * from ' . DB::table('cxpforms') . ' where id=' . $form_id);
		return $forminfo;
	}
}

// 表单提交设置
if(!function_exists('cxpform_submit_setting')){
	function cxpform_submit_setting($form_id){
		$submit_setting = DB::fetch_first('select * from ' . DB::table('cxpform_submit_setting') . ' where form_id=' . $form_id);
		return $submit_setting;
	}
}

// 表单提醒设置
if(!function_exists('cxpform_notice_setting')){
	function cxpform_notice_setting($form_id){
		$notice_setting = DB::fetch_first('select * from ' . DB::table('cxpform_notice_setting') . ' where form_id=' . $form_id);
		return $notice_setting;
	}
}

// 表单字段列表
if(!function_exists('cxpform_field_list')){
	function cxpform_field_list($form_id){
		$fields = DB::fetch_all('SELECT * FROM '.DB::table('cxpform_fields') . ' where form_id=' . $form_id . ' order by sortid asc, id asc');
		$arr1 = array();
		foreach($fields as $field){
			$arr1[$field['id']] = $field;
		}
		return $arr1;
	}
}

// 表单字段信息
if(!function_exists('cxpform_field_info')){
	function cxpform_field_info($field_id){
		$fieldinfo = DB::fetch_first('select * from ' . DB::table('cxpform_fields') . ' where id=' . $field_id);
		return $fieldinfo;
	}
}

// 输出字段输入框
if(!function_exists('cxpform_render_field')){
	function cxpform_render_field($field = array()){
		
		$field_name = 'field_' . $field['id'];
		if($field['field_name'] != ''){
			$field_name = $field['field_name'];
		}
		
		switch($field['field_type']){
			default: // 默认也是文本框
			case '1': // 文本框
				return '<input type="text" name="' . $field_name . '" id="field_' . $field['id'] . '" class="px" /><p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;
			case '15': // 密码
				return '<input type="password" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '" class="px" /><p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;				
			case '2': // 文本域
				return '<textarea name="field_' . $field['id'] . '" id="field_' . $field['id'] . '" class="pt"></textarea><p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;
			case '3': // 单选框组
				$field_options = cxpform_field_option_list($field['id']);
				$option_str = '';
				foreach($field_options as $option){
					if($option['picurl'] != ''){
						$option_str .= '<div class="option-item">
								<div class="img-wrap"><a href="' . $option['picurl'] . '" target="_blank"><img src="' . $option['picurl'] . '" /></a></div>
								<div class="txt-wrap"><input type="radio" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '_' . $option['id'] . '" value="' . $option['id'] . '" /><label for="field_' . $field['id'] . '_' . $option['id'] . '">' . $option['option_name'] . '</label></div>
							</div>';
					}else{
						$option_str .= '<div class="option-item"><input type="radio" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '_' . $option['id'] . '" value="' . $option['id'] . '" /><label for="field_' . $field['id'] . '_' . $option['id'] . '">' . $option['option_name'] . '</label></div>';
					}
				}
				$other_str = '';
				if($field['show_other']){
					$other_str = '<div class="option-item"><input type="radio" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '_0" value="other_field" /><label for="field_' . $field['id'] . '_0">' . cxpform_lang('other') . '</label> <input type="text" name="field_' . $field['id'] . '_other" id="field_' . $field['id'] . '_other" class="px" style="width:100px;" /></div>';
				}				
				return $option_str . $other_str . '<p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><p id="field_' . $field['id'] . '_othererror" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'input[name="field_' . $field['id'] . '"]\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'limit1') . '\'
							}
						});
						jq(\'#field_' . $field['id'] . '_0\').click(function(){
							jq(\'input[name="field_' . $field['id'] . '_other"]\').rules(\'add\',{
								required:true,
								messages:{
									required:\'' . lang('plugin/cxpform', 'other_required') . '\'
								}
							});
						});
					});
				</script>';
				break;
			case '4': // 复选框组
				$field_options = cxpform_field_option_list($field['id']);
				$optioncount = count($field_options);
				$option_str = '';
				foreach($field_options as $option){
					if($option['picurl'] != ''){
						$option_str .= '<div class="option-item">
								<div class="img-wrap"><a href="' . $option['picurl'] . '" target="_blank"><img src="' . $option['picurl'] . '" /></a></div>
								<div class="txt-wrap"><input type="checkbox" name="field_' . $field['id'] . '[]" id="field_' . $field['id'] . '_' . $option['id'] . '" value="' . $option['id'] . '" /><label for="field_' . $field['id'] . '_' . $option['id'] . '">' . $option['option_name'] . '</label></div>
							</div>';
					}else{
						$option_str .= '<div class="option-item"><input type="checkbox" name="field_' . $field['id'] . '[]" id="field_' . $field['id'] . '_' . $option['id'] . '" value="' . $option['id'] . '" /><label for="field_' . $field['id'] . '_' . $option['id'] . '">' . $option['option_name'] . '</label></div>';
					}
				}
				$other_str = '';
				if($field['show_other']){
					$other_str = '<div class="option-item"><input type="checkbox" name="field_' . $field['id'] . '[]" id="field_' . $field['id'] . '_0" value="other_field" /><label for="field_' . $field['id'] . '_0">' . cxpform_lang('other') . '</label> <input type="text" name="field_' . $field['id'] . '_other" id="field_' . $field['id'] . '_other" class="px" style="width:100px;" /></div>';
				}
				
				$maxlength = $field['select_num'] <= 0 || $field['select_num'] > $optioncount ? ($field['show_other'] ? ($optioncount + 1) : $optioncount) : $field['select_num'];
				
				return $option_str . $other_str . '<p id="field_' . $field['id'] . 'error" style="padding-left:5px;clear:both;"></p><p id="field_' . $field['id'] . '_othererror" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'input[name*="field_' . $field['id'] . '"]\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							maxlength:' . $maxlength . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'limit1') . '\',
								maxlength:\'' . sprintf(lang('plugin/cxpform', 'limit2'), $maxlength) . '\'
							}
						});
						jq(\'#field_' . $field['id'] . '_0\').click(function(){
							jq(\'input[name="field_' . $field['id'] . '_other"]\').rules(\'add\',{
								required:true,
								messages:{
									required:\'' . lang('plugin/cxpform', 'other_required') . '\'
								}
							});
						});						
					});
				</script>';
				break;
			case '5': // 单选框
				return '<input type="radio" name="field_' . $field['id'] . '" />';
				break;
			case '6': // 复选框
				return '<input type="checkbox" name="field_' . $field['id'] . '" />';
				break;
			case '7': // 邮箱
				return '<input type="text" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '"  class="px" /><p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							email:true,
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\',
								email:\'' . lang('plugin/cxpform', 'valid_format') . '\'
							}
						});
					});</script>';
				break;
			case '8': // 手机号码
				return '<input type="text" name="' . $field_name . '" id="field_' . $field['id'] . '"  class="px" /><p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jQuery.validator.addMethod("mobile", function(value, element, param) { 
						return this.optional(element) ||  /^(1(([357][0-9])|([4][0123456789])|[8][0123456789]))\d{8}$/.test(value);
					}, \'' . lang('plugin/cxpform', 'valid_format') . '\');
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							mobile:true,
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;
			case '9': // 身份证
				return '<input type="text" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '"  class="px" /><p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							minlength:15,
							maxlength:18,
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\',
								minlength:\'' . lang('plugin/cxpform', 'min15') . '\',
								maxlength:\'' . lang('plugin/cxpform', 'max18') . '\'
							}
						});
					});</script>';
				break;
			case '10': // 网址
				return '<input type="text" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '"  class="px" /><p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							url:true,
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\',
								url:\'' . lang('plugin/cxpform', 'valid_format') . '\'
							}
						});
					});</script>';
				break;
			case '11': // 数字
				return '<input type="text" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '"  class="px" /><p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							number:true,
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\',
								number:\'' . lang('plugin/cxpform', 'valid_format') . '\'
							}
						});
					});</script>';
				break;
			case '12': // 日期
				return '<input type="text" name="' . $field_name . '" id="field_' . $field['id'] . '" class="Wdate" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd\'' . ($field['field_name'] != '' ? ',minDate:\'' . date('Y-m-d') . '\'' : '') . '});"  class="px" readonly="readonly" /><p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;	
			case '16': // 时间
				return '<input type="text" name="' . $field_name . '" id="field_' . $field['id'] . '" class="Wdate" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});"  class="px" readonly="readonly" /><p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;				
			case '13':
				$field_options = cxpform_field_option_list($field['id']);
				$option_str = '<select name="field_' . $field['id'] . '" id="field_' . $field['id'] . '"><option value="">' . lang('plugin/cxpform', 'choose') . '</option>';
				foreach($field_options as $option){
					$option_str .= '<option value="' . $option['id'] . '">' . $option['option_name'] . '</option>';
				}
				return $option_str . '</select><p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';				
				break;
			case '14': // 文件
				return '<input type="file" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '"  class="px" /><p id="field_' . $field['id'] . 'error" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;	
			case '18': // 地址
				return '<p style="padding:10px 20px 0 10px;" id="field_' . $field['id'] . '_citys"><select name="province"></select><input type="hidden" id="field_' . $field['id'] . '_province1"><select name="city"></select><input type="hidden" id="field_' . $field['id'] . '_city1"/><select name="area"></select><input type="hidden" id="field_' . $field['id'] . '_district1"/></p><p style="padding:10px 20px 0 10px;"><input type="text" class="px" name="field_' . $field['id'] . '_address" id="field_' . $field['id'] . '_address" /><input type="hidden" name="field_' . $field['id'] . '" id="real_field_' . $field['id'] . '_val" /></p><p id="field_' . $field['id'] . '_addresserror" style="padding-left:10px;clear:both;"></p><script type="text/javascript">
                  jq(function(){
					  var sProvince = \'\';
						var sCity = \'\';
						var sArea = \'\';
						jq.ajaxSetup({
							async : false
						});
						jq.get(\'plugin.php?id=cxpform:ajax&op=ip\', function(data) {
							var json = JSON.parse(data);
							if (json.code == 0) {
								sProvince = json.data.province;
								sCity = json.data.city;
								sArea = json.data.district;
							}
						});
						jq(\'#field_' . $field['id'] . '_province1\').val(sProvince);
						jq(\'#field_' . $field['id'] . '_city1\').val(sCity);
						jq(\'#field_' . $field['id'] . '_district1\').val(sArea);
						jq(\'#field_' . $field['id'] . '_address\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
						jq(\'#real_field_' . $field['id'] . '_val\').val(sProvince + sCity + sArea);
					  jq(\'#field_' . $field['id'] . '_citys\').citys({
							province:sProvince,
							city:sCity,
							area:sArea,
							onChange:function(data){
								jq(\'#field_' . $field['id'] . '_province1\').val(data.province);
								jq(\'#field_' . $field['id'] . '_city1\').val(data.city);
								jq(\'#field_' . $field['id'] . '_district1\').val(data.area);
								jq(\'#real_field_' . $field['id'] . '_val\').val(data.province + data.city + data.area + jq(\'#field_' . $field['id'] . '_address\').val());
							}
						},function(api){
							var info = api.getInfo();
							jq(\'#field_' . $field['id'] . '_province1\').val(info.province);
							jq(\'#field_' . $field['id'] . '_city1\').val(info.city);
							jq(\'#field_' . $field['id'] . '_district1\').val(info.area);
							jq(\'#real_field_' . $field['id'] . '_val\').val(info.province + info.city + info.area);
						});
						jq(\'#field_' . $field['id'] . '_address\').on(\'change\', function(){
							jq(\'#real_field_' . $field['id'] . '_val\').val(jq(\'#field_' . $field['id'] . '_province1\').val() + jq(\'#field_' . $field['id'] . '_city1\').val() + jq(\'#field_' . $field['id'] . '_district1\').val() + jq(\'#field_' . $field['id'] . '_address\').val());
						});
				  });
				  </script>';
				break;
		}
	}
}

// 
if(!function_exists('cxpform_render_field_value')){
	function cxpform_render_field_value($field = array(), $vals = ''){
		switch($field['field_type']){
			default: // 默认也是文本框
			case '1': // 文本框
				return '<input type="text" class="px" value="' . $vals . '" disabled="disabled" />';
				break;
			case '2': // 文本域
				return '<textarea rows="6" cols="80" disabled="disabled">' . $vals . '</textarea>';
				break;
			case '3': // 单选框组
				$field_options = cxpform_field_option_list($field['id']);
				$option_str = '';
				$arr = maybe_unserialize($vals);
				foreach($field_options as $option){
					$option_str .= '<input type="radio" value="' . $option['id'] . '" disabled="disabled" ' . ($vals == $option['id'] ? 'checked="checked"' : '') . ' />' . $option['option_name'];
				}
				$other_str = '';
				if($field['show_other']){
					$idx = array_search('other_field', $arr);
					$other_str .= '<input type="radio" disabled="disabled" ' . ($idx !== FALSE ? 'checked="checked"' : '') . ' /> ' . cxpform_lang('other') . ' <input type="text" disabled="disabled" value="' . ($idx !== FALSE ? $arr[$idx + 1] : '') . '" />';
				}				
				return $option_str . $other_str . '';
				break;
			case '4': // 复选框组
				$field_options = cxpform_field_option_list($field['id']);
				$optioncount = count($field_options);
				$option_str = '';
				$arr = maybe_unserialize($vals);
				foreach($field_options as $option){
					$option_str .= '<input type="checkbox" disabled="disabled" value="' . $option['id'] . '" ' . (in_array($option['id'], $arr) ? 'checked="checked"' : '') . ' />' . $option['option_name'];
				}
				$other_str = '';
				if($field['show_other']){
					$idx = array_search('other_field', $arr);
					$other_str .= '<input type="checkbox" disabled="disabled" ' . ($idx !== FALSE ? 'checked="checked"' : '') . ' /> ' . cxpform_lang('other') . ' <input type="text" disabled="disabled" value="' . ($idx !== FALSE ? $arr[$idx + 1] : '') . '" />';
				}
				return $option_str . $other_str . '';
				break;
			case '5': // 单选框
				return '<input type="radio" name="field_' . $field['id'] . '" />';
				break;
			case '6': // 复选框
				return '<input type="checkbox" name="field_' . $field['id'] . '" />';
				break;
			case '7': // 邮箱
				return '<input type="text" value="' . $vals . '" disabled="disabled"  class="px" />';
				break;
			case '8': // 手机号码
				return '<input type="text" value="' . $vals . '"  disabled="disabled" class="px" />';
				break;
			case '9': // 身份证
				return '<input type="text" value="' . $vals . '"  disabled="disabled" class="px" />';
				break;
			case '10': // 网址
				return '<input type="text" value="' . $vals . '"  disabled="disabled" class="px" />';
				break;
			case '11': // 数字
				return '<input type="text" value="' . $vals . '"  disabled="disabled" class="px" />';
				break;
			case '12': // 日期
			case '16': // 时间
				return '<input type="text" value="' . $vals . '"  disabled="disabled" class="px" />';
				break;	
			case '13':
				$field_options = cxpform_field_option_list($field['id']);
				$option_str = '<select name="field_' . $field['id'] . '" id="field_' . $field['id'] . '" disabled="disabled"><option value="">' . lang('plugin/cxpform', 'choose') . '</option>';
				foreach($field_options as $option){
					$option_str .= '<option value="' . $option['id'] . '" ' . ($vals == $option['id'] ? 'selected="selected"' : '') . '>' . $option['option_name'] . '</option>';
				}
				return $option_str . '</select>';				
				break;
			case '14': // 文件
				return '<a href="' . $vals . '" target="_blank">' . $vals . '</a>';
				break;				
		}
	}
}

// 输出bootstrap样式的表单元素
if(!function_exists('cxpform_render_field_bootstrap')){
	function cxpform_render_field_bootstrap($field = array()){
	
		$field_name = 'field_' . $field['id'];
		if($field['field_name'] != ''){
			$field_name = $field['field_name'];
		}
		
		switch($field['field_type']){
			default: // 默认也是文本框
			case '1': // 文本框
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p>
					<input type="text" name="' . $field_name . '" id="field_' . $field['id'] . '" class="form-control" /><p class="help-block" id="field_' . $field['id'] . 'error"></p></div><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;
			case '15': // 密码
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p>
					<input type="password" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '" class="form-control" /><p class="help-block" id="field_' . $field['id'] . 'error"></p></div><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;				
			case '2': // 文本域
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p><textarea name="field_' . $field['id'] . '" class="form-control" id="field_' . $field['id'] . '"></textarea><p class="help-block" id="field_' . $field['id'] . 'error"></p></div><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;
			case '3': // 单选框组
				$field_options = cxpform_field_option_list($field['id']);
				$option_str = '';
				$has_picurl = 0;
				foreach($field_options as $option){
					if($option['picurl'] != ''){
						$option_str .= '<div class="col-xs-6 col-md-6"><a href="' . $option['picurl'] . '" target="_blank" class="thumbnail"><img src="' . $option['picurl'] . '" /></a><div class="caption"><input type="radio" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '_' . $option['id'] . '" value="' . $option['id'] . '" /><label for="field_' . $field['id'] . '_' . $option['id'] . '">' . $option['option_name'] . '</label></div></div>';
						$has_picurl++;
					}else{
						$option_str .= '<div class="radio' . ($field['inline'] ? '-inline' : '') . '"><label for="field_' . $field['id'] . '_' . $option['id'] . '"><input type="radio" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '_' . $option['id'] . '" value="' . $option['id'] . '" />' . $option['option_name'] . '</label></div>';
					}
				}
				$other_str = '';
				if($field['show_other']){
					$other_str = '<div class="' . ($has_picurl > 0 ? 'col-xs-6 col-md-6' : 'radio' . ($field['inline'] ? '-inline' : '') . '') . '"><label for="field_' . $field['id'] . '_0"><input type="radio" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '_0" value="other_field" />' . cxpform_lang('other') . ' <input type="text" name="field_' . $field['id'] . '_other" id="field_' . $field['id'] . '_other" class="form-control" style="width:100px;" /></label></div>';
				}				
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label><p class="help-block">' . $field['field_desc'] . '</p>' . $option_str . $other_str . ($has_picurl > 0 ? '<div class="clearfix"></div>' : '') . '<p class="help-block" id="field_' . $field['id'] . 'error"></p><p class="help-block" id="field_' . $field['id'] . '_othererror"></p></div><script type="text/javascript">
					jq(function(){
						jq(\'input[name="field_' . $field['id'] . '"]\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'limit1') . '\'
							}
						});
						jq(\'#field_' . $field['id'] . '_0\').click(function(){
							jq(\'input[name="field_' . $field['id'] . '_other"]\').rules(\'add\',{
								required:true,
								messages:{
									required:\'' . lang('plugin/cxpform', 'other_required') . '\'
								}
							});
						});						
					});
				</script>';
				break;
			case '4': // 复选框组
				$field_options = cxpform_field_option_list($field['id']);
				$optioncount = count($field_options);
				$option_str = '';
				$has_picurl = 0;
				foreach($field_options as $option){
					if($option['picurl'] != ''){
						$option_str .= '<div class="col-xs-6 col-md-6"><a href="' . $option['picurl'] . '" target="_blank" class="thumbnail"><img src="' . $option['picurl'] . '" /></a><div class="caption"><input type="checkbox" name="field_' . $field['id'] . '[]" id="field_' . $field['id'] . '_' . $option['id'] . '" value="' . $option['id'] . '" /><label for="field_' . $field['id'] . '_' . $option['id'] . '">' . $option['option_name'] . '</label></div></div>';
						$has_picurl++;
					}else{
						$option_str .= '<div class="checkbox' . ($field['inline'] ? '-inline' : '') . '"><label for="field_' . $field['id'] . '_' . $option['id'] . '"><input type="checkbox" name="field_' . $field['id'] . '[]" id="field_' . $field['id'] . '_' . $option['id'] . '" value="' . $option['id'] . '" />' . $option['option_name'] . '</label></div>';
					}
				}
				$other_str = '';
				if($field['show_other']){
					$other_str = '<div class="' . ($has_picurl > 0 ? 'col-xs-6 col-md-6' : 'checkbox' . ($field['inline'] ? '-inline' : '') . '') . '"><label for="field_' . $field['id'] . '_0"><input type="checkbox" name="field_' . $field['id'] . '[]" id="field_' . $field['id'] . '_0" value="other_field" />' . cxpform_lang('other') . ' <input type="text" name="field_' . $field['id'] . '_other" id="field_' . $field['id'] . '_other" class="form-control" /></label></div>';
				}		
				
				$maxlength = $field['select_num'] <= 0 || $field['select_num'] > $optioncount ? ($field['show_other'] ? ($optioncount + 1) : $optioncount) : $field['select_num'];
				
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label><p class="help-block">' . $field['field_desc'] . '</p>' . $option_str . $other_str . ($has_picurl > 0 ? '<div class="clearfix"></div>' : '') . '<p class="help-block" id="field_' . $field['id'] . 'error"></p><p class="help-block" id="field_' . $field['id'] . '_othererror"></p></div><script type="text/javascript">
					jq(function(){
						jq(\'input[name*="field_' . $field['id'] . '"]\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							maxlength:' . $maxlength . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'limit1') . '\',
								maxlength:\'' . sprintf(lang('plugin/cxpform', 'limit2'), $maxlength) . '\'
							}
						});
						jq(\'#field_' . $field['id'] . '_0\').click(function(){
							jq(\'input[name="field_' . $field['id'] . '_other"]\').rules(\'add\',{
								required:true,
								messages:{
									required:\'' . lang('plugin/cxpform', 'other_required') . '\'
								}
							});
						});							
					});
				</script>';
				break;
			case '5': // 单选框
				return '<input type="radio" name="field_' . $field['id'] . '" />';
				break;
			case '6': // 复选框
				return '<input type="checkbox" name="field_' . $field['id'] . '" />';
				break;
			case '7': // 邮箱
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p>
					<input type="text" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '" class="form-control" /><p class="help-block" id="field_' . $field['id'] . 'error"></p></div><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							email:true,
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\',
								email:\'' . lang('plugin/cxpform', 'valid_format') . '\'
							}
						});
					});</script>';
				break;
			case '8': // 手机号码
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p>
					<input type="text" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '" class="form-control" /><p class="help-block" id="field_' . $field['id'] . 'error"></p></div><script type="text/javascript">
					jQuery.validator.addMethod("mobile", function(value, element, param) { 
						return this.optional(element) ||  /^(1(([375][0-9])|([4][0123456789])|[8][0123456789]))\d{8}$/.test(value);
					}, \'' . lang('plugin/cxpform', 'valid_format') . '\');
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							mobile:true,
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;
			case '9': // 身份证号
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p>
					<input type="text" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '" class="form-control" /><p class="help-block" id="field_' . $field['id'] . 'error"></p></div><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							minlength:15,
							maxlength:18,
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\',
								minlength:\'' . lang('plugin/cxpform', 'min15') . '\',
								maxlength:\'' . lang('plugin/cxpform', 'max18') . '\'
							}
						});
					});</script>';
				break;
			case '10': // 网址
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p>
					<input type="text" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '" class="form-control" /><p class="help-block" id="field_' . $field['id'] . 'error"></p></div><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							url:true,
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\',
								url:\'' . lang('plugin/cxpform', 'valid_format') . '\'
							}
						});
					});</script>';
				break;
			case '11': // 数字
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p>
					<input type="text" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '" class="form-control" /><p class="help-block" id="field_' . $field['id'] . 'error"></p></div><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							number:true,
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\',
								number:\'' . lang('plugin/cxpform', 'valid_format') . '\'
							}
						});
					});</script>';
				break;
			case '12': // 日期
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p>
					<input type="text" name="' . $field_name . '" id="field_' . $field['id'] . '" class="form-control" readonly="readonly" /><p class="help-block" id="field_' . $field['id'] . 'error"></p></div><script type="text/javascript">
					
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').datetimepicker({
							format: \'yyyy-mm-dd\',
							language:\'zh-CN\',
							minView:2,' . ($field['field_name'] != '' ? 'startDate:\'' . date('Y-m-d') . '\',' : '') . '
							todayBtn:true,
							autoclose:1
						});
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;
			case '16': // 时间
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p>
					<input type="text" name="' . $field_name . '" id="field_' . $field['id'] . '" class="form-control" readonly="readonly" /><p class="help-block" id="field_' . $field['id'] . 'error"></p></div><script type="text/javascript">
					
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').datetimepicker({
							format: \'yyyy-mm-dd hh:ii:ss\',
							language:\'zh-CN\',
							todayBtn:true,
							autoclose:1
						});
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;				
			case '13': // 下拉框
				$field_options = cxpform_field_option_list($field['id']);
				$option_str = '<option value="">' . lang('plugin/cxpform', 'choose') . '</option>';
				foreach($field_options as $option){
					$option_str .= '<option value="' . $option['id'] . '">' . $option['option_name'] . '</option>';
				}			
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p>
					<select name="field_' . $field['id'] . '" id="field_' . $field['id'] . '" class="form-control">' . $option_str . '</select><p class="help-block" id="field_' . $field['id'] . 'error"></p></div><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;
			case '14': // 文件
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p>
					<input type="file" name="field_' . $field['id'] . '" id="field_' . $field['id'] . '" class="form-control" /><p class="help-block" id="field_' . $field['id'] . 'error"></p></div><script type="text/javascript">
					jq(function(){
						jq(\'#field_' . $field['id'] . '\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
					});</script>';
				break;
			case '18': // 地址
				return '<div class="form-group">
				<label>' . $field['field_label'] . ($field['isrequired'] ? '<span style="color:red;font-weight:bold;">*</span>' : '') . '</label>
				<p class="help-block">' . $field['field_desc'] . '</p><div class="row" style="" id="field_' . $field['id'] . '_citys"><div class="col-sm-4"><select name="province" class="form-control"></select><input type="hidden" id="field_' . $field['id'] . '_province1"></div><div class="col-sm-4"><select name="city" class="form-control"></select><input type="hidden" id="field_' . $field['id'] . '_city1"/></div><div class="col-sm-4"><select name="area" class="form-control"></select><input type="hidden" id="field_' . $field['id'] . '_district1"/></div></div><div style=""><input type="text" class="px form-control" name="field_' . $field['id'] . '_address" id="field_' . $field['id'] . '_address" /><input type="hidden" name="field_' . $field['id'] . '" id="real_field_' . $field['id'] . '_val" /></div><p class="help-block" id="field_' . $field['id'] . '_addresserror" style="padding-left:10px;clear:both;"></p></div><script type="text/javascript">
                  jq(function(){
					  var sProvince = \'\';
						var sCity = \'\';
						var sArea = \'\';
						jq.ajaxSetup({
							async : false
						});
						jq.get(\'plugin.php?id=cxpform:ajax&op=ip\', function(data) {
							var json = JSON.parse(data);
							if (json.code == 0) {
								sProvince = json.data.province;
								sCity = json.data.city;
								sArea = json.data.district;
							}
						});
						jq(\'#field_' . $field['id'] . '_province1\').val(sProvince);
						jq(\'#field_' . $field['id'] . '_city1\').val(sCity);
						jq(\'#field_' . $field['id'] . '_district1\').val(sArea);
						jq(\'#field_' . $field['id'] . '_address\').rules(\'add\', {
							required:' . ($field['isrequired'] ? 'true' : 'false') . ',
							messages:{
								required:\'' . $field['field_label'] . '' . lang('plugin/cxpform', 'empty') . '\'
							}
						});
						jq(\'#real_field_' . $field['id'] . '_val\').val(sProvince + sCity + sArea);
					  jq(\'#field_' . $field['id'] . '_citys\').citys({
							province:sProvince,
							city:sCity,
							area:sArea,
							onChange:function(data){
								jq(\'#field_' . $field['id'] . '_province1\').val(data.province);
								jq(\'#field_' . $field['id'] . '_city1\').val(data.city);
								jq(\'#field_' . $field['id'] . '_district1\').val(data.area);
								jq(\'#real_field_' . $field['id'] . '_val\').val(data.province + data.city + data.area + jq(\'#field_' . $field['id'] . '_address\').val());
							}
						},function(api){
							var info = api.getInfo();
							jq(\'#field_' . $field['id'] . '_province1\').val(info.province);
							jq(\'#field_' . $field['id'] . '_city1\').val(info.city);
							jq(\'#field_' . $field['id'] . '_district1\').val(info.area);
							jq(\'#real_field_' . $field['id'] . '_val\').val(info.province + info.city + info.area);
						});
						jq(\'#field_' . $field['id'] . '_address\').on(\'change\', function(){
							jq(\'#real_field_' . $field['id'] . '_val\').val(jq(\'#field_' . $field['id'] . '_province1\').val() + jq(\'#field_' . $field['id'] . '_city1\').val() + jq(\'#field_' . $field['id'] . '_district1\').val() + jq(\'#field_' . $field['id'] . '_address\').val());
						});
				  });
				  </script>';
				break;	
		}
	}
}

// 后台的字段输入框
function cxpform_render_cp_field($fields, $field_value = ''){
	$name_prefix = '';

	
	switch($fields['field_type']){
		case '1': // 文本框
			return '<tr><td colspan="2" class="td27" s="1">' . $fields['field_label'] . '</td></tr><tr class="noborder"><td class="vtop rowform"><input name="' . $name_prefix . 'field_' . $fields['id'] . '" value="' . $field_value . '" type="text" class="txt" /></td><td class="vtop tips2" s="1"></td></tr>' . "\n";
			break;
		case '2': // 文本域
			return '<tr><td colspan="2" class="td27" s="1">' . $fields['field_label'] . '</td></tr><tr class="noborder"><td class="vtop rowform"><textarea name="' . $name_prefix . 'field_' . $fields['id'] . '">' . $field_value . '</textarea></td><td class="vtop tips2" s="1"></td></tr>' . "\n";
			break;	
		case '3': //单选框组 
		case '4': // 复选框组
		case '13': //下拉框
			$field_options = cxpform_field_option_list($fields['id']);
			$option_str = '<option value="">' . lang('plugin/cxpform', 'choose') . '</option>';
			foreach($field_options as $option){
				$option_str .= '<option value="' . $option['id'] . '" ' . ($field_value == $option['id'] ? 'selected="selected"' : '')  . '>' . $option['option_name'] . '</option>';
			}
			return '<tr><td colspan="2" class="td27" s="1">' . $fields['field_label'] . '</td></tr><tr class="noborder"><td class="vtop rowform"><select name="' . $name_prefix . 'field_' . $fields['id'] . '">' . $option_str . '</select></td><td class="vtop tips2" s="1"></td></tr>' . "\n";
			break;
		default: // 文本框
			return '<tr><td colspan="2" class="td27" s="1">' . $fields['field_label'] . '</td></tr><tr class="noborder"><td class="vtop rowform"><input name="' . $name_prefix . 'field_' . $fields['id'] . '" value="' . $field_value . '" type="text" class="txt" /></td><td class="vtop tips2" s="1"></td></tr>' . "\n";
			break;			
	}
}

// 前台的查询字段输入框
function cxpform_render_search_field_style1($fields, $field_value = ''){
	$name_prefix = '';

	
	switch($fields['field_type']){	
		case '3': //单选框组 
		case '4': // 复选框组
		case '13': //下拉框
			$field_options = cxpform_field_option_list($fields['id']);
			$option_str = '<option value="">' . lang('plugin/cxpform', 'choose') . '</option>';
			foreach($field_options as $option){
				$option_str .= '<option value="' . $option['id'] . '" ' . ($field_value == $option['id'] ? 'selected="selected"' : '')  . '>' . $option['option_name'] . '</option>';
			}
			return '<tr>
				<td width="150">' . $fields['field_label'] . ':</td><td><select name="' . $name_prefix . 'field_' . $fields['id'] . '" id="field_' . $fields['id'] . '" class="" style="width:150px;">' . $option_str . '</select></td>
			</tr>' . "\n";
			break;
		default: // 文本框
			return '<tr>
				<td width="150">' . $fields['field_label'] . ':</td><td><input type="text" name="' . $name_prefix . 'field_' . $fields['id'] . '" id="field_' . $fields['id'] . '" class="px" style="width:150px;" value="' . $field_value . '" /></td>
			</tr>' . "\n";
			break;			
	}
}
function cxpform_render_search_field_style2($fields, $field_value = ''){
	$name_prefix = '';

	switch($fields['field_type']){	
		case '3': //单选框组 
		case '4': // 复选框组
		case '13': //下拉框
			$field_options = cxpform_field_option_list($fields['id']);
			$option_str = '<option value="">' . lang('plugin/cxpform', 'choose') . '</option>';
			foreach($field_options as $option){
				$option_str .= '<option value="' . $option['id'] . '" ' . ($field_value == $option['id'] ? 'selected="selected"' : '')  . '>' . $option['option_name'] . '</option>';
			}
			return '<div class="form-group">
				<label for="search_item_' . $fields['id'] . '">' . $fields['field_label'] . '</label>
				<select name="field_' . $fields['id'] . '" class="form-control" id="search_item_' . $fields['id'] . '">' . $option_str . '</select>
			  </div>' . "\n";
			break;
		default: // 文本框
			return '<div class="form-group">
				<label for="search_item_' . $fields['id'] . '">' . $fields['field_label'] . '</label>
				<input type="text" name="field_' . $fields['id'] . '" class="form-control" id="search_item_' . $fields['id'] . '" placeholder="' . $field_value . '">
			  </div>' . "\n";
			break;			
	}
}

// 题目选项列表
if(!function_exists('cxpform_field_option_list')){
	function cxpform_field_option_list($field_id){
		$results = DB::fetch_all("select * from " . DB::table('cxpform_field_options') . " where field_id='" . $field_id . "' order by sortid asc");
		$field_option_arr = array();
		foreach($results as $row){
			$field_option_arr[$row['id']] = $row;
		}
		return $field_option_arr;
	}
}

// 题目选项值数组
if(!function_exists('cxpform_field_option_value')){
	function cxpform_field_option_value($field_id, $form_id, $st, $et){
		$results = DB::fetch_all("select A.option_id, count(A.id) as ccount from " . DB::table('cxpform_field_value') . " A left join " . DB::table('cxpform_contents') . " B on A.content_id=B.id and A.form_id=B.form_id  where A.field_id='" . $field_id . "' and B.addtime>=UNIX_TIMESTAMP(" . DB::quote($st) . ") and B.addtime<=UNIX_TIMESTAMP(" . DB::quote($et) . ") group by A.option_id");
		$arr = array();
		foreach($results as $row){
			$arr[$row['option_id']] = $row['ccount'];
		}
		return $arr;
	}
}

// 题目提交答案的数量
if(!function_exists('cxpform_field_option_total')){
	function cxpform_field_option_total($field_id, $form_id, $st, $et){
		$results = DB::fetch_all("select A.id from " . DB::table('cxpform_field_value') . " A left join " . DB::table('cxpform_contents') . " B on A.content_id=B.id and A.form_id=B.form_id where A.field_id='" . $field_id . "' and B.addtime>=UNIX_TIMESTAMP(" . DB::quote($st) . ") and B.addtime<=UNIX_TIMESTAMP(" . DB::quote($et) . ") group by A.content_id");
		return count($results);
	}
}

// 两个日期之间的日期
if(!function_exists('cxpform_prDates')){
	function cxpform_prDates($start,$end){
		$dt_start = strtotime($start);
		$dt_end = strtotime($end);
		$arr = array();
		// while ($dt_start<=$dt_end){
			// $arr[] = date('Y-m-d', $dt_start);
			// $dt_start = strtotime('+1 day',$dt_start);
		// }
		do { 
			$arr[] = date('Y-m-d', $dt_start);
		} while (($dt_start += 86400) <= $dt_end);
		return $arr;
	}
}

// 后台表单的二级菜单
if(!function_exists('cxpform_form_subnav')){
	function cxpform_form_subnav($form_id, $cur_op = ''){
		global $_G;
		$forminfo = cxpform_form_info($form_id);
		echo '<div><div class="itemtitle"><h3>' . lang('plugin/cxpform', 'form_name') . lang('plugin/cxpform', 'maohao') . $forminfo['title'] . '</h3><ul class="tab1">
				<li><a href="' . $_G['siteurl'] . 'plugin.php?id=cxpform:style1&form_id=' . $form_id . '" target="_blank"><span>' . lang('plugin/cxpform', 'preview') . '</span></a></li>
				<li ' . ($cur_op == 'edit' ? 'class="current"' : '') . '><a href="' . XF_PLUGIN_URL . 'pmod=form&op=edit&id=' . $form_id . '"><span>' . lang('plugin/cxpform', 'edit') . '</span></a></li> 
				<li ' . ($cur_op == 'submit_setting' ? 'class="current"' : '') . '><a href="' . XF_PLUGIN_URL . 'pmod=form&op=submit_setting&form_id=' . $form_id . '"><span>' . lang('plugin/cxpform', 'submit_setting') . '</span></a></li>
				<li ' . ($cur_op == 'notice_setting' ? 'class="current"' : '') . '><a href="' . XF_PLUGIN_URL . 'pmod=form&op=notice_setting&form_id=' . $form_id . '"><span>' . lang('plugin/cxpform', 'notice_setting') . '</span></a></li>
				<li ' . ($cur_op == 'form_fields' ? 'class="current"' : '') . '><a href="' . XF_PLUGIN_URL . 'pmod=form&op=form_fields&form_id=' . $form_id . '"><span>' . lang('plugin/cxpform', 'fieldmanage') . '</span></a></li>
				<li ' . ($cur_op == 'content_status' ? 'class="current"' : '') . '><a href="' . XF_PLUGIN_URL . 'pmod=form&op=content_status&form_id=' . $form_id . '"><span>' . lang('plugin/cxpform', 'content_status') . '</span></a></li>
				<!--<li ' . ($cur_op == 'hits' ? 'class="current"' : '') . '><a href="' . XF_PLUGIN_URL . 'pmod=form&op=hits&form_id=' . $form_id . '"><span>' . lang('plugin/cxpform', 'hitshistory') . '</span></a></li>-->
				<li ' . ($cur_op == 'form_content' ? 'class="current"' : '') . '><a href="' . XF_PLUGIN_URL .  'pmod=form&op=form_content&form_id=' . $form_id . '"><span>' . lang('plugin/cxpform', 'viewresult') . '</span></a></li>';
				// if($forminfo['form_type'] != '2'){
				echo '<li ' . ($cur_op == 'charts' ? 'class="current"' : '') . '><a href="' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '"><span>' . lang('plugin/cxpform', 'viewchart') . '</span></a></li>
				';
				// }
				echo '<li ' . ($cur_op == 'get_code' ? 'class="current"' : '') . '><a href="' . XF_PLUGIN_URL . 'pmod=form&op=get_code&form_id=' . $form_id . '"><span>' . lang('plugin/cxpform', 'get_code') . '</span></a></li>
				</ul></div></div>';
	}
}


// 提醒内容替换
if(!function_exists('cxpform_notice_content')){
	function cxpform_notice_content($fields, $contenttext, $contentval, $defaultparams = array()){
		$replace = array();
		
		if(count($defaultparams) > 0){
			$arr1 = array();
			$arr2 = array();
			
			foreach($defaultparams as $dk => $dv){
				$arr1[] = '{' . $dk . '}';
				$arr2[] = $dv;
			}
			
			// 替换内置的变量
			$contenttext = str_replace($arr1, $arr2, $contenttext);
		}
		foreach($fields as $field1){
			if($field1['field_type'] == '14'){
				$replace[] = '{field_' . $field1['id'].'}';
			}
		}
		foreach($fields as $field){
			if($field['field_type'] == '14') continue;
			$replace[] = '{field_' . $field['id'].'}';
		}
		$content = str_replace($replace, $contentval, $contenttext);
		return $content;
	}
}

function cxpform_content_info($content_id){
	$info = DB::fetch_first('select * from ' . DB::table('cxpform_contents') . ' where id=' . $content_id);
	return $info;
}

// 得到扩展字段
function cxpform_content_extra($content_id){
	$content_extra = DB::fetch_all('select A.* from ' . DB::table('cxpform_content_extra') . ' as A where A.content_id=' . $content_id);
	$content_extra_arr = array();
	foreach($content_extra as $cextra){
		$content_extra_arr[$cextra['field_id']] = $cextra['field_value'];
	}
	return $content_extra_arr;
}

function cxpform_content_extra_1($history_id){
	$content_extra = DB::fetch_all('select A.*, B.field_label, B.field_type, B.show_other from ' . DB::table('cxpform_content_extra') . ' as A left join ' . DB::table('cxpform_fields') . ' B on A.field_id=B.id where A.content_id=' . $history_id . ' order by B.sortid asc, B.id asc');
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
}

// 输出题目的图表
function cxpform_render_field_charts($form_id, $st, $et){
	
	// 3 4  13的报表
	$fields = cxpform_field_list($form_id);
	foreach($fields as $field){
		if(!in_array($field['field_type'], array('3', '4', '13'))) continue;
		// 选项
		$field_options = cxpform_field_option_list($field['id']);
		// 字段选项
		$field_option_value = cxpform_field_option_value($field['id'], $form_id, $st, $et);
		$field_option_value1 = $field_option_value;
		//if(count($field_option_value1) > 1){
		//	unset($field_option_value1[0]);
		//}
		$total1 = cxpform_field_option_total($field['id'], $form_id, $st, $et);
		$category_str = '';
		$option_num = '';
		foreach($field_options as $option){
			$category_str .= "'" . $option['option_name'] . "',";
			// option_id = 10 的选项个数
			$option_num .= '{value:' . intval($field_option_value[$option['id']]) . ", name:'" . $option['option_name'] . "'},";
		}
		if($field['show_other']){
			$category_str .= "'" . cxpform_lang('other') . "',";
			$option_num .= "{value:" . intval($field_option_value[0]) . ", name:'" . cxpform_lang('other') . "'},";
		}		
		$category_str = rtrim($category_str, ',');
		$option_num = rtrim($option_num, ',');
		if($category_str != ''){
		
		
	?>
	<table class="dt">
	<tr><td colspan="2"><strong><?php echo cxpform_lang('field');?>:<?php echo $field['field_label'];?> <?php echo cxpform_lang('chart_title');?></strong></td></tr>
	<tr><td valign="top" width="50%">
	
		<table class="dt" style="width:100%;">
			<tr>
				<th colspan="3"><?php echo cxpform_lang('total_submit_count');?>:<?php echo $total1;?></th>
			</tr>
			<tr class="header">
				<th><?php echo cxpform_lang('field_option_name');?></th>
				<th><?php echo cxpform_lang('submit_count');?></th>
				<th><?php echo cxpform_lang('percentage');?></th>
			</tr>
			
			<?php 
			
			foreach($field_options as $option){
			$field_option_value1 = intval($field_option_value[$option['id']]);
			?>
			
			<tr>
				<td><?php echo $option['option_name'];?></td>
				<td><?php echo $field_option_value1;?></td>
				<td><?php echo number_format($field_option_value1 / $total1 * 100, 2);?> %</td>
			</tr>
			<?php }?>
			<?php if($field['show_other']){?>
			<tr>
				<td><?php echo cxpform_lang('other');?></td>
				<td><?php echo intval($field_option_value[0]);?></td>
				<td><?php echo number_format(intval($field_option_value[0]) / $total1 *100, 2);?> %</td>
			</tr>
			<?php }?>
			
		</table>	
	
	</td><td width="50%"><div id="field<?php echo $field['id'];?>" style="height:350px;width:350px;"></div></td></tr></table>
	<script type="text/javascript">
        require(
            [
                'echarts',
                'echarts/chart/pie'
            ],
            function (ec) {
               
                var myChart<?php echo $field['id'];?> = ec.init(document.getElementById('field<?php echo $field['id'];?>')); 
                
                option<?php echo $field['id'];?> = {
					title : {
						text: '',
						subtext: '',
						x:'center'
					},
					tooltip : {
						trigger: 'item',
						formatter: "\{a\} <br/>\{b\} : \{c\} (\{d\}%)"
					},
					legend: {
						data:[<?php echo $category_str;?>],
						orient : 'vertical',
						x:'left'
					},
					toolbox: toolbox1,
					calculable : true,
					series : [
						{
							name:'<?php echo cxpform_lang('choose_num');?>',
							type:'pie',
							radius : '55%',
							center:['50%', '60%'],
							data:[<?php echo $option_num;?>]
						}
					]
				};
        
                 
                myChart<?php echo $field['id'];?>.setOption(option<?php echo $field['id'];?>); 
            }
        );
	</script>
	<?php 
	}
	}	
}

// 状态列表
function cxpform_content_status_list($form_id){
	$results = DB::fetch_all("select * from " . DB::table('cxpform_content_status') . " where form_id='" . $form_id . "' order by sortid asc");
	$arr = array();
	foreach($results as $row){
		$arr[$row['id']] = $row;
	}
	return $arr;	
}
// 状态信息
function cxpform_content_status_info($id){
	$info = DB::fetch_first('select * from ' . DB::table('cxpform_content_status') . ' where id=' . $id);
	return $info;
}

// 处理记录信息
function cxpform_content_logs_info(){
	
}

/**
 * Unserialize value only if it was serialized.
 *
 * @since 2.0.0
 *
 * @param string $original Maybe unserialized original, if is needed.
 * @return mixed Unserialized data can be any type.
 */
if(!function_exists('maybe_unserialize')){
	function maybe_unserialize( $original ) {
		if ( is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
			return @unserialize( $original );
		return $original;
	}
}

/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 * @since 2.0.5
 *
 * @param mixed $data Value to check to see if was serialized.
 * @param bool $strict Optional. Whether to be strict about the end of the string. Defaults true.
 * @return bool False if not serialized and true if it was.
 */
if(!function_exists('is_serialized')){ 
	function is_serialized( $data, $strict = true ) {
		// if it isn't a string, it isn't serialized
		if ( ! is_string( $data ) )
			return false;
		$data = trim( $data );
		if ( 'N;' == $data )
			return true;
		$length = strlen( $data );
		if ( $length < 4 )
			return false;
		if ( ':' !== $data[1] )
			return false;
		if ( $strict ) {
			$lastc = $data[ $length - 1 ];
			if ( ';' !== $lastc && '}' !== $lastc )
				return false;
		} else {
			$semicolon = strpos( $data, ';' );
			$brace     = strpos( $data, '}' );
			// Either ; or } must exist.
			if ( false === $semicolon && false === $brace )
				return false;
			// But neither must be in the first X characters.
			if ( false !== $semicolon && $semicolon < 3 )
				return false;
			if ( false !== $brace && $brace < 4 )
				return false;
		}
		$token = $data[0];
		switch ( $token ) {
			case 's' :
				if ( $strict ) {
					if ( '"' !== $data[ $length - 2 ] )
						return false;
				} elseif ( false === strpos( $data, '"' ) ) {
					return false;
				}
				// or else fall through
			case 'a' :
			case 'O' :
				return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
			case 'b' :
			case 'i' :
			case 'd' :
				$end = $strict ? '$' : '';
				return (bool) preg_match( "/^{$token}:[0-9.E-]+;$end/", $data );
		}
		return false;
	}
}

/**
 * Check whether serialized data is of string type.
 *
 * @since 2.0.5
 *
 * @param mixed $data Serialized data
 * @return bool False if not a serialized string, true if it is.
 */
if(!function_exists('is_serialized_string')){ 
	function is_serialized_string( $data ) {
		// if it isn't a string, it isn't a serialized string
		if ( !is_string( $data ) )
			return false;
		$data = trim( $data );
		$length = strlen( $data );
		if ( $length < 4 )
			return false;
		elseif ( ':' !== $data[1] )
			return false;
		elseif ( ';' !== $data[$length-1] )
			return false;
		elseif ( $data[0] !== 's' )
			return false;
		elseif ( '"' !== $data[$length-2] )
			return false;
		else
			return true;
	}
}

/**
 * Serialize data, if needed.
 *
 * @since 2.0.5
 *
 * @param mixed $data Data that might be serialized.
 * @return mixed A scalar data
 */
if(!function_exists('maybe_serialize')){ 
	function maybe_serialize( $data ) {
		if ( is_array( $data ) || is_object( $data ) )
			return serialize( $data );

		// Double serialization is required for backward compatibility.
		// See http://core.trac.wordpress.org/ticket/12930
		if ( is_serialized( $data, false ) )
			return serialize( $data );

		return $data;
	}
}
?>