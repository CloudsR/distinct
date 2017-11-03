<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

		// 表单id
		$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
		cxpform_form_subnav($form_id, 'form_fields');
		if($op1 === 'add'){ // 添加表单题目
			if(!submitcheck('formsubmit')) {
				showformheader(XF_FORM_URL . 'pmod=form&op=form_fields&op1=add&form_id=' . $form_id, 'formsubmit');
				showtableheader();
				// echo '<tr><td></td></tr>';
				showtitle(lang('plugin/cxpform', 'add_field'));
				showsetting(lang('plugin/cxpform', 'field_label'), 'field_label', '', 'text', '', 0, lang('plugin/cxpform', 'empty'), '', '', true);
				showsetting(lang('plugin/cxpform', 'field_desc'), 'field_desc', '', 'textarea', '', 0, '', '', '', true);
				showsetting(lang('plugin/cxpform', 'field_type'), array('field_type', $cxpform_field_type), '', 'select', '', 0, '', 'id="field_type"', '', true);
				echo '<tr class="option_setting" style="display:none;"><td colspan="15" class="td27" s="1">' . lang('plugin/cxpform', 'field_option') . ':</td></tr>';
				
				echo '<tr class="option_setting" style="display:none;"><td colspan="15">
					<table id="optiontable">
						<thead><tr><td></td><td>' . lang('plugin/cxpform', 'field_option_name') . '</td><td>' . lang('plugin/cxpform', 'field_option_image') . '</td><td>' . lang('plugin/cxpform', 'field_sortid') . '</td><td></td></tr></thead>
						<tbody></tbody>
					</table> <br /><input type="hidden" name="todelid" id="todelid" /><a class="addoption">' . lang('plugin/cxpform', 'add_field_option') . '</a>
				</td></tr>';
				
				echo '<tr class="option_setting" style="display:none;"><td colspan="15" class="td27" s="1">' . lang('plugin/cxpform', 'option_inline_type') . ':</td></tr>';
				echo '<tr class="option_setting" style="display:none;"><td colspan="15" class="rowform"><select name="inline"><option value="0">' . cxpform_lang('inline_type1') . '</option><option value="1">' . cxpform_lang('inline_type2') . '</option></select></td></tr>';
				
				echo '<tr class="option_setting" style="display:none;"><td colspan="15" class="td27" s="1">' . cxpform_lang('show_other') . ':</td></tr>';
				echo '<tr class="option_setting" style="display:none;"><td colspan="15"><input type="radio" name="show_other" value="1" /> ' . cplang('yes') . ' <input type="radio" name="show_other" value="0" /> ' . cplang('no') . '</td></tr>';
				
				// echo '<tr><td colspan="2" class="td27" s="1">' . cxpform_lang('show_other') . ':</td></tr><tr><td class="vtop rowform"><input type="checkbox" /></td></tr>';
				// showsetting(cxpform_lang('show_other'), 'show_other', 0, 'radio', '', 0, cxpform_lang('show_other_tip'), '', '', true);
				
				echo '<tr class="option_setting1" style="display:none;"><td colspan="2" class="td27" s="1">' . lang('plugin/cxpform', 'field_option_num') . ':</td></tr>';
				echo '<tr class="noborder option_setting1" onmouseover="setfaq(this, \'faqccc1\')" style="display:none;"><td class="vtop rowform">
<input name="select_num" value="" type="text" class="txt"></td><td class="vtop tips2" s="1"></td></tr>';

				echo '<tr class=""><td colspan="2" class="td27" s="1">' . lang('plugin/cxpform', 'default_value') . ':</td></tr>';
				echo '<tr class="noborder"><td class="vtop rowform">
<input name="select_num" value="" type="text" class="txt"></td><td class="vtop tips2" s="1">' . lang('plugin/cxpform', 'default_value_tip') . '</td></tr>';
							 
				showsetting(lang('plugin/cxpform', 'field_is_required'), 'isrequired', 1, 'radio', '', 0, '', '', '', true);
				
				showsubmit('formsubmit');
				
				showtablefooter();
				showformfooter();	
				echo '<script type="text/javascript">
					jq(function(){
						jq(\'.addoption\').click(function(){
							jq(\'#optiontable\').children(\'tbody\').append(\'<tr><td><input type="hidden" name="option_id[]" value="" /></td><td><input type="text" name="option_name[]" /></td><td><input type="text" name="picurl[]" /></td><td><input type="text" name="sortid[]" size="2" value="0" /></td><td><a class="deloption" data-pk="">' . lang('plugin/cxpform', 'delete') . '</a></td></tr>\');
						});
						jq(\'body\').on(\'click\', \'.deloption\', function(){
							if(confirm(\'' . lang('plugin/cxpform', 'delconfirm') . '\')){
								jq(this).parent(\'td\').parent(\'tr\').remove();
							}
						});
						jq(\'#field_type\').on(\'change\',function(){
							var v = jq(this).val();
							switch(v){
								case "4":
									jq(\'.option_setting\').show();
									jq(\'.option_setting1\').show();
									break;
								case "3":
								case "13":
									jq(\'.option_setting\').show();
									jq(\'.option_setting1\').hide();
									break;
								default:
									jq(\'.option_setting\').hide();
									jq(\'.option_setting1\').hide();
									break;
							}
						});
					});
				</script>';				
			}else{
				$field_label = trim($_GET['field_label']);
				$field_desc = trim($_GET['field_desc']);// 这边是后台，用户有需求可以放html，所以不做安全过滤了。
				$field_type = intval($_GET['field_type']);
				$select_num = intval($_GET['select_num']);
				$isrequired = intval($_GET['isrequired']);
				$show_other = intval($_GET['show_other']);
				$inline = intval($_GET['inline']);
				if(!$field_label){
					cpmsg(lang('plugin/cxpform', 'field_label') . lang('plugin/cxpform', 'empty'), '', 'error');
				}else{
					// if(!$field_name){
						// cpmsg('字段名称不能为空', '', 'error');
					// }
				}
				
				$data = array(
					'form_id' => $form_id,
					'field_label' => $field_label,
					'field_desc' => $field_desc,
					'field_type' => $field_type,
					'select_num' => $select_num,
					'isrequired' => $isrequired,
					'show_other' => $show_other,
					'inline' => $inline,
					'addtime' => $_SERVER['REQUEST_TIME'],
				);
				
				$new_field_id = DB::insert('cxpform_fields', $data, 1);
				// 这三个是数组请不要判定为没有安全过滤
				$option_id = isset($_GET['option_id']) ? $_GET['option_id'] : '';
				$option_name = isset($_GET['option_name']) ? $_GET['option_name'] : '';
				$picurl = isset($_GET['picurl']) ? $_GET['picurl'] : '';
				$sortid = isset($_GET['sortid']) ? $_GET['sortid'] : '';
				if($option_id !== ''){
					foreach($option_id as $opk=>$opid){
						$data1 = array(
							'form_id' => $form_id,
							'field_id' => $new_field_id,
							'option_name' => trim($option_name[$opk]),
							'picurl' => trim($picurl[$opk]),
							'sortid' => intval($sortid[$opk]),
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
				
				
				cpmsg(lang('plugin/cxpform', 'add_field_success'), XF_PLUGIN_URL . "pmod=form&op=form_fields&form_id=" . $form_id, 'succeed');
			}			
		}elseif($op1 === 'edit'){
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			if(!submitcheck('formsubmit')) {
				
				$fieldinfo = cxpform_field_info($id);
				
				showformheader(XF_FORM_URL . 'pmod=form&op=form_fields&op1=edit&id=' . $id . '&form_id=' . $form_id, 'formsubmit');
				showtableheader();
				showtitle(lang('plugin/cxpform', 'edit_field'));
				
				showsetting(lang('plugin/cxpform', 'field_label'), 'field_label', $fieldinfo['field_label'], 'text', '', 0, lang('plugin/cxpform', 'empty'), '', '', true);
				showsetting(lang('plugin/cxpform', 'field_desc'), 'field_desc', $fieldinfo['field_desc'], 'textarea', '', 0, '', '', '', true);
				showsetting(lang('plugin/cxpform', 'field_type'), array('field_type', $cxpform_field_type), $fieldinfo['field_type'], 'select', '', 0, '', 'id="field_type"', '', true);
				echo '<tr class="option_setting" ' . (in_array($fieldinfo['field_type'], array('3', '4', '13')) ? '' : 'style="display:none;"') . '><td colspan="15" class="td27" s="1">' . lang('plugin/cxpform', 'field_option') . ':</td></tr>';
				
				$field_options = cxpform_field_option_list($fieldinfo['id']);
				$option_str = '';
				foreach($field_options as $option){
					$option_str .= '<tr><td><input type="hidden" name="option_id[]" value="' . $option['id'] . '" /></td><td><input type="text" name="option_name[]" value="' . dhtmlspecialchars($option['option_name']) . '" /></td><td><input type="text" name="picurl[]" value="' . dhtmlspecialchars($option['picurl']) . '" /></td><td><input type="text" name="sortid[]" size="2" value="' . $option['sortid'] . '" /></td><td><a class="deloption" data-pk="' . $option['id'] . '">' . lang('plugin/cxpform', 'delete') . '</a></td></tr>';
				}
				
				echo '<tr class="option_setting" ' . (in_array($fieldinfo['field_type'], array('3', '4', '13')) ? '' : 'style="display:none;"') . '><td colspan="15">
					<table id="optiontable">
						<thead><tr><td></td><td>' . lang('plugin/cxpform', 'field_option_name') . '</td><td>' . lang('plugin/cxpform', 'field_option_image') . '</td><td>' . lang('plugin/cxpform', 'field_sortid') . '</td><td></td></tr></thead>
						<tbody>
						' . $option_str . '
						</tbody>
					</table> <br /><input type="hidden" name="todelid" id="todelid" /><a class="addoption">' . lang('plugin/cxpform', 'add_field_option') . '</a>
				</td></tr>';
				
				echo '<tr class="option_setting" ' . (in_array($fieldinfo['field_type'], array('3', '4', '13')) ? '' : 'style="display:none;"') . '><td colspan="15" class="td27" s="1">' . lang('plugin/cxpform', 'option_inline_type') . ':</td></tr>';
				echo '<tr class="option_setting" ' . (in_array($fieldinfo['field_type'], array('3', '4', '13')) ? '' : 'style="display:none;"') . '><td colspan="15" class="rowform"><select name="inline">
				<option value="0" ' . ($fieldinfo['inline'] == '0' ? 'selected="selected"' : '') . '>' . cxpform_lang('inline_type1') . '</option>
				<option value="1" ' . ($fieldinfo['inline'] == '1' ? 'selected="selected"' : '') . '>' . cxpform_lang('inline_type2') . '</option></select></td></tr>';
				
				echo '<tr class="option_setting" ' . (in_array($fieldinfo['field_type'], array('3', '4', '13')) ? '' : 'style="display:none;"') . '><td colspan="15" class="td27" s="1">' . cxpform_lang('show_other') . ':</td></tr>';
				echo '<tr class="option_setting" ' . (in_array($fieldinfo['field_type'], array('3', '4', '13')) ? '' : 'style="display:none;"') . '><td colspan="15">
				<input type="radio" name="show_other" value="1" ' . ($fieldinfo['show_other'] == '1' ? 'checked="checked"' : '') . ' /> ' . cplang('yes') . ' 
				<input type="radio" name="show_other" value="0" ' . ($fieldinfo['show_other'] == '0' ? 'checked="checked"' : '') . ' /> ' . cplang('no') . '</td></tr>';
				
				echo '<tr class="option_setting1" ' . (in_array($fieldinfo['field_type'], array('4')) ? '' : 'style="display:none;"') . '><td colspan="2" class="td27" s="1">' . lang('plugin/cxpform', 'field_option_num') . ':</td></tr>';
				echo '<tr class="noborder option_setting1" onmouseover="setfaq(this, \'faqccc1\')" ' . (in_array($fieldinfo['field_type'], array('4')) ? '' : 'style="display:none;"') . '><td class="vtop rowform">
<input name="select_num" value="' . $fieldinfo['select_num'] . '" type="text" class="txt"></td><td class="vtop tips2" s="1"></td></tr>';
				
				// showsetting(cxpform_lang('show_other'), 'show_other', $fieldinfo['show_other'] ? 1 : 0, 'radio', '', 0, cxpform_lang('show_other_tip'), '', '', true);
				
				showsetting(lang('plugin/cxpform', 'field_is_required'), 'isrequired', $fieldinfo['isrequired'] ? 1 : 0, 'radio', '', 0, '', '', '', true);
				
				showsubmit('formsubmit');
				
				showtablefooter();
				showformfooter();
				echo '<script type="text/javascript">
					jq(function(){
						jq(\'.addoption\').click(function(){
							jq(\'#optiontable\').children(\'tbody\').append(\'<tr><td><input type="hidden" name="option_id[]" value="" /></td><td><input type="text" name="option_name[]" /></td><td><input type="text" name="picurl[]" /></td><td><input type="text" name="sortid[]" size="2" value="0" /></td><td><a class="deloption" data-pk="">' . lang('plugin/cxpform', 'delete') . '</a></td></tr>\');
						});
						jq(\'body\').on(\'click\', \'.deloption\', function(){
							if(confirm(\'' . lang('plugin/cxpform', 'delconfirm') . '\')){
								jq(this).parent(\'td\').parent(\'tr\').remove();
								var option_id = jq(this).attr(\'data-pk\');
								var old_val = jq(\'#todelid\').val();
								if(option_id != \'\'){
									if(old_val == \'\'){
										var new_val = \',\'  + option_id + \',\';
									}else{
										var new_val = old_val + option_id + \',\';
									}
									jq(\'#todelid\').val(new_val);
								}
							}
						});
						jq(\'#field_type\').on(\'change\',function(){
							var v = jq(this).val();
							switch(v){
								case "4":
									jq(\'.option_setting\').show();
									jq(\'.option_setting1\').show();
									break;
								case "3":
								case "13":
									jq(\'.option_setting\').show();
									jq(\'.option_setting1\').hide();
									break;
								default:
									jq(\'.option_setting\').hide();
									jq(\'.option_setting1\').hide();
									break;
							}
						});
					});
				</script>';
			}else{
				$field_label = trim($_GET['field_label']);
				$field_desc = trim($_GET['field_desc']); // 这边是后台，用户有需求可以放html，所以不做安全过滤了。
				$field_type = intval($_GET['field_type']);
				$select_num = intval($_GET['select_num']);
				$isrequired = intval($_GET['isrequired']);
				$show_other = intval($_GET['show_other']);
				$inline = intval($_GET['inline']);
				if(!$field_label){
					cpmsg(lang('plugin/cxpform', 'field_label') . lang('plugin/cxpform', 'empty'), '', 'error');
				}else{
					// if(!$field_name){
						// cpmsg('字段名称不能为空', '', 'error');
					// }
				}
				
				$data = array(
					'form_id' => $form_id,
					'field_label' => $field_label,
					'field_desc' => $field_desc,
					'field_type' => $field_type,
					'select_num' => $select_num,
					'isrequired' => $isrequired,
					'show_other' => $show_other,
					'inline' => $inline,
				);
				
				DB::update('cxpform_fields', $data, DB::field('id', $id));
				// 这三个是数组请不要判定为没有安全过滤
				$option_id = isset($_GET['option_id']) ? $_GET['option_id'] : '';
				$option_name = isset($_GET['option_name']) ? $_GET['option_name'] : '';
				$picurl = isset($_GET['picurl']) ? $_GET['picurl'] : '';
				$sortid = isset($_GET['sortid']) ? $_GET['sortid'] : '';
				// 删除要删除的
				$todelid = isset($_GET['todelid']) ? addslashes(trim($_GET['todelid'])) : '';
				if($todelid !== ''){
					$delarr = explode(',', trim($todelid, ','));
					DB::delete('cxpform_field_options', DB::field('id', $delarr));
					// var_dump($delarr);
				}
				if($option_id !== ''){
					foreach($option_id as $opk=>$opid){
						$data1 = array(
							'form_id' => $form_id,
							'field_id' => $id,
							'option_name' => trim($option_name[$opk]),
							'picurl' => trim($picurl[$opk]),
							'sortid' => intval($sortid[$opk]),
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
				
				cpmsg(lang('plugin/cxpform', 'edit_field_success'), XF_PLUGIN_URL . "pmod=form&op=form_fields&form_id=" . $form_id . '', 'succeed');				
			}
		}elseif($op1 === 'delete'){
			$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
			if($formhash == formhash()){
				//删除表单字段
				$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
				DB::delete('cxpform_field_value', DB::field('field_id', $id));
				// 删除表单内容扩展字段
				// for($i=0; $i<XF_EXTRA_TABLE_NUM;$i++){
					// DB::delete('cxpform_content_extra_' . $i, DB::field('field_id', $id));
				// }
				DB::delete('cxpform_content_extra', DB::field('field_id', $id));
				// 删除字段选项
				DB::delete('cxpform_field_options', DB::field('field_id', $id));
				// 删除表单字段
				DB::delete('cxpform_fields', DB::field('id', $id));
				
				cpmsg(lang('plugin/cxpform', 'delete_field_success'), XF_PLUGIN_URL . "pmod=form&op=form_fields&form_id=" . $form_id, 'succeed');
			}
		}elseif($op1 == 'update_field_sort'){
			$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
			if($formhash == formhash()){		
				ob_end_clean();
				$field_id = isset($_GET['field_id']) ? intval($_GET['field_id']) : '';
				$sortid = isset($_GET['sortid']) ? intval($_GET['sortid']) : '';
				// 更新排序号
				DB::query("update " . DB::table('cxpform_fields') . " set sortid='" . $sortid . "' where id='" . $field_id . "'");
				echo 'success';
				define(FOOTERDISABLED, false);
				exit();
			}
		}elseif($op1 == 'update_field_required'){
			$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
			if($formhash == formhash()){		
				ob_end_clean();
				$field_id = isset($_GET['field_id']) ? intval($_GET['field_id']) : '';
				// 更新是否必选
				$isrequired = isset($_GET['isrequired']) ? intval($_GET['isrequired']) : '';
				DB::query("update " . DB::table('cxpform_fields') . " set isrequired='" . $isrequired . "' where id='" . $field_id . "'");
				echo 'success';
				define(FOOTERDISABLED, false);
				exit();
			}
		}elseif($op1 == 'update_field_value'){
			$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
			if($formhash == formhash()){		
				ob_end_clean();
				$field_name = isset($_GET['field_name']) ? trim($_GET['field_name']) : '';
				$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
				$field_value = isset($_GET['field_value']) ? addslashes(trim($_GET['field_value'])) : '';
				$data1 = array(
					$field_name => $field_value
				);
				DB::update('cxpform_fields', $data1, DB::field('id', $id));
				echo 'success';
				define(FOOTERDISABLED, false);
				exit();		
			}
		}else{
			$forminfo = cxpform_form_info($form_id);
			
			echo '<script type="text/javascript">
	function change_field_value(obj, field_name, id){
		jq.post(\'' . ADMINSCRIPT . '?action=plugins&operation=config&do=' . $pluginid . '&identifier=cxpform&pmod=form&op=form_fields&op1=update_field_value&formhash=' . FORMHASH . '\', {field_name:field_name, field_value:jq(obj).val(),id:id}, function(data){
			if(data==\'success\'){
				self.location.href = location.href;
			}
		});
	}
	</script>';
			// 字段列表
			$ppp = 10;
			$resultempty = FALSE;
			$page = max(1, intval($_GET['page']));
			echo '<a href="' . XF_PLUGIN_URL . 'pmod=form&op=form_fields&op1=add&form_id=' . $form_id . '" class="addtr">' . lang('plugin/cxpform', 'add_field') . '</a>';
			showtips(cxpform_lang('field_tip'));
			showtableheader();
			showtitle(lang('plugin/cxpform', 'field_list'));
			showformheader(XF_FORM_URL . 'pmod=form&op=form_fields', 'formsubmit');
			
			showformfooter();

			echo '<tr class="header">
					<th>' . lang('plugin/cxpform', 'id') . '</th>
					<th>' . lang('plugin/cxpform', 'field_sortid') . '</th>
					<th>' . lang('plugin/cxpform', 'field_label') . '</th>
					<th>' . lang('plugin/cxpform', 'field_type') . '</th>
					<th>' . lang('plugin/cxpform', 'field_is_required') . '</th>';
					
					// if($forminfo['form_type'] == '2'){
						echo '<th>' . cxpform_lang('is_search') . '</th>';
						echo '<th>' . cxpform_lang('is_header') . '</th>';
						echo '<th>' . lang('plugin/cxpform', 'field_regex') . '</th>';
					// }
					echo '<th>' . lang('plugin/cxpform', 'op') . '</th>
					</tr>';
			$count = DB::result_first('select count(*) from ' . DB::table('cxpform_fields') . ' where form_id=' . $form_id);
			$fields = DB::fetch_all('SELECT * FROM '.DB::table('cxpform_fields') . ' where form_id=' . $form_id . ' order by sortid asc,id asc ', null, 'topicid');
			
			foreach($fields as $field) {
				echo '<tr class="hover">
					<td class="td25">' . $field['id'] . '</td>
					<td class="td25"><input type="text" value="' . $field['sortid'] . '" class="txt" size="2" id="sortid' . $field['id'] . '" onchange="change_field_value(this, \'sortid\', ' . $field['id'] . ')" /></td>
					<td>' . $field['field_label'] . '</td>
					<td>' . $cxpform_field_type_arr[$field['field_type']] . '</td>
					<td><input value="' . ($field['isrequired'] ? 0 : 1) . '" type="checkbox" onclick="change_field_value(this, \'isrequired\', ' . $field['id'] . ')" ' . ($field['isrequired'] ? 'checked="checked"' : '') . ' /></td>';
					// if($forminfo['form_type'] == '2'){
						echo '<td><input type="checkbox" value="' . ($field['is_search'] ? 0 : 1) . '" ' . ($field['is_search'] ? 'checked="checked"' : '') . ' onclick="change_field_value(this, \'is_search\', ' . $field['id'] . ')" /></td>
						<td><input type="checkbox" value="' . ($field['is_header'] ? 0 : 1) . '" ' . ($field['is_header'] ? 'checked="checked"' : '') . ' onclick="change_field_value(this, \'is_header\', ' . $field['id'] . ')" /></td>
						
						<td><select id="regex_model" onchange="change_field_value(this, \'regex_model\', ' . $field['id'] . ')">
							<option value="0" ' . ($field['regex_model'] == 0 ? 'selected="selected"' : '') . '>' . cxpform_lang('field_regex_0') . '</option>
							<option value="1" ' . ($field['regex_model'] == 1 ? 'selected="selected"' : '') . '>' . cxpform_lang('field_regex_1') . '</option>
							<option value="2" ' . ($field['regex_model'] == 2 ? 'selected="selected"' : '') . '>' . cxpform_lang('field_regex_2') . '</option>
							<option value="3" ' . ($field['regex_model'] == 3 ? 'selected="selected"' : '') . '>' . cxpform_lang('field_regex_3') . '</option>
						</select></td>';
					// }
					echo '<td>';
					
					if($field['is_editable']){
						echo '<a href="' . XF_PLUGIN_URL . 'pmod=form&op=form_fields&op1=edit&form_id=' . $form_id . '&id=' . $field['id'] . '">' . lang('plugin/cxpform', 'edit') . '</a> 
					<a href="' . XF_PLUGIN_URL . 'pmod=form&op=form_fields&op1=delete&form_id=' . $form_id . '&id=' . $field['id'] . '&formhash=' . FORMHASH . '" onclick="if(confirm(\'' . lang('plugin/cxpform', 'delconfirm') . '\')){return true;}else{return false;}">' . lang('plugin/cxpform', 'delete') . '</a> ';
					}else{
						echo ' --- ';
					}
					
				echo '</td></tr>';
			}
			

			showtablefooter();	
					
		}
?>