<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

		// 表单id
		$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
		cxpform_form_subnav($form_id, 'content_status');
		if($op1 === 'add'){ // 添加表单题目
			if(!submitcheck('formsubmit')) {
				showformheader(XF_FORM_URL . 'pmod=form&op=content_status&op1=add&form_id=' . $form_id, 'formsubmit');
				showtableheader();
				// echo '<tr><td></td></tr>';
				showtitle(lang('plugin/cxpform', 'add_content_status'));
				showsetting(lang('plugin/cxpform', 'status_name'), 'status_name', '', 'text', '', 0, lang('plugin/cxpform', 'empty'), '', '', true);
				showsetting(lang('plugin/cxpform', 'status_color'), 'status_color', '#000000', 'color', '', 0, '', '', '', true);
			
							 
				// showsetting(lang('plugin/cxpform', 'is_default'), 'is_default', 1, 'radio', '', 0, '', '', '', true);
				
				showsubmit('formsubmit');
				
				showtablefooter();
				showformfooter();	
					
			}else{
				$status_name = trim($_GET['status_name']);
				$status_color = trim($_GET['status_color']);
				$is_default = intval($_GET['is_default']);
				if(!$status_name){
					cpmsg(lang('plugin/cxpform', 'status_name') . lang('plugin/cxpform', 'empty'), '', 'error');
				}else{
					// if(!$field_name){
						// cpmsg('字段名称不能为空', '', 'error');
					// }
				}
				
				$data = array(
					'form_id' => $form_id,
					'status_name' => $status_name,
					'status_color' => $status_color,
					'is_default' => $is_default,
				);
				
				DB::insert('cxpform_content_status', $data, 1);
				cpmsg(lang('plugin/cxpform', 'add_content_status_success'), XF_PLUGIN_URL . "pmod=form&op=content_status&form_id=" . $form_id, 'succeed');
			}			
		}elseif($op1 === 'edit'){
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			if(!submitcheck('formsubmit')) {
				$info = cxpform_content_status_info($id);
				showformheader(XF_FORM_URL . 'pmod=form&op=content_status&op1=edit&id=' . $id . '&form_id=' . $form_id, 'formsubmit');
				showtableheader();
				showtitle(lang('plugin/cxpform', 'edit_content_status'));
				
				showsetting(lang('plugin/cxpform', 'status_name'), 'status_name', $info['status_name'], 'text', '', 0, lang('plugin/cxpform', 'empty'), '', '', true);
				showsetting(lang('plugin/cxpform', 'status_color'), 'status_color', $info['status_color'], 'color', '', 0, '', '', '', true);
				
				// showsetting(lang('plugin/cxpform', 'is_default'), 'is_default', $info['is_default'] ? 1 : 0, 'radio', '', 0, '', '', '', true);
				
				showsubmit('formsubmit');
				
				showtablefooter();
				showformfooter();
				
			}else{
				$status_name = trim($_GET['status_name']);
				$status_color = trim($_GET['status_color']);
				$is_default = intval($_GET['is_default']);
				if(!$status_name){
					cpmsg(lang('plugin/cxpform', 'status_name') . lang('plugin/cxpform', 'empty'), '', 'error');
				}else{
					// if(!$field_name){
						// cpmsg('字段名称不能为空', '', 'error');
					// }
				}
				
				$data = array(
					'form_id' => $form_id,
					'status_name' => $status_name,
					'status_color' => $status_color,
					'is_default' => $is_default,
				);
				
				DB::update('cxpform_content_status', $data, DB::field('id', $id));

				cpmsg(lang('plugin/cxpform', 'edit_content_status_success'), XF_PLUGIN_URL . "pmod=form&op=content_status&form_id=" . $form_id . '', 'succeed');				
			}
		}elseif($op1 === 'delete'){
			$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
			if($formhash == formhash()){
				//删除
				$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
				// 删除
				DB::delete('cxpform_content_status', DB::field('id', $id));
				
				cpmsg(lang('plugin/cxpform', 'delete_content_status_success'), XF_PLUGIN_URL . "pmod=form&op=content_status&form_id=" . $form_id, 'succeed');
			}
		}elseif($op1 == 'update_sort'){
			$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
			if($formhash == formhash()){		
				ob_end_clean();
				$status_id = isset($_GET['status_id']) ? intval($_GET['status_id']) : '';
				$sortid = isset($_GET['sortid']) ? intval($_GET['sortid']) : '';
				// 更新排序号
				DB::query("update " . DB::table('cxpform_content_status') . " set sortid='" . $sortid . "' where id='" . $status_id . "'");
				echo 'success';
				define(FOOTERDISABLED, false);
				exit();
			}
		}elseif($op1 == 'update_default'){
			$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
			if($formhash == formhash()){		
				ob_end_clean();
				$status_id = isset($_GET['status_id']) ? intval($_GET['status_id']) : '';
				// 更新是否必选
				$is_default = isset($_GET['is_default']) ? intval($_GET['is_default']) : '';
				DB::query("update " . DB::table('cxpform_content_status') . " set is_default='0'");
				DB::query("update " . DB::table('cxpform_content_status') . " set is_default='1' where id='" . $status_id . "'");
				echo 'success';
				define(FOOTERDISABLED, false);
				exit();		
			}
		}else{
			
			// 字段列表
			$ppp = 10;
			$resultempty = FALSE;
			$page = max(1, intval($_GET['page']));
			echo '<a href="' . XF_PLUGIN_URL . 'pmod=form&op=content_status&op1=add&form_id=' . $form_id . '" class="addtr">' . lang('plugin/cxpform', 'add_content_status') . '</a>';
			showtips(cxpform_lang('status_tip'));
			showtableheader();
			showtitle(lang('plugin/cxpform', 'content_status_list'));
			showformheader(XF_FORM_URL . 'pmod=form&op=content_status', 'formsubmit');
			
			showformfooter();

			echo '<tr class="header">
					<th>' . lang('plugin/cxpform', 'id') . '</th>
					<th>' . lang('plugin/cxpform', 'status_name') . '</th>
					<th>' . lang('plugin/cxpform', 'status_color') . '</th>
					<th>' . lang('plugin/cxpform', 'is_default') . '</th>
					<th>' . lang('plugin/cxpform', 'field_sortid') . '</th>
					<th>' . lang('plugin/cxpform', 'op') . '</th>
					</tr>';
			$count = DB::result_first('select count(*) from ' . DB::table('cxpform_content_status') . ' where form_id=' . $form_id);
			$results = DB::fetch_all('SELECT * FROM '.DB::table('cxpform_content_status') . ' where form_id=' . $form_id . ' order by sortid asc,id asc ', null, 'topicid');
			
			foreach($results as $row) {
				echo '<tr class="hover">
					<td class="td25">' . $row['id'] . '</td>
					<td>' . $row['status_name'] . '</td>
					<td>' . $row['status_color'] . '<div style="display:inline-block;width:20px;height:10px;background-color:' . $row['status_color'] . '"></div></td>
					<td><input id="chk' . $row['id'] . '" value="' . $row['is_default'] . '" type="checkbox" ' . ($row['is_default'] ? 'checked="checked" disabled="disabled"' : '') . ' /></td>
					<td class="td25"><input type="text" value="' . $row['sortid'] . '" class="txt" size="2" id="sortid' . $row['id'] . '" /></td>
					<td>
					<a href="' . XF_PLUGIN_URL . 'pmod=form&op=content_status&op1=edit&form_id=' . $form_id . '&id=' . $row['id'] . '">' . lang('plugin/cxpform', 'edit') . '</a> 
					<a href="' . XF_PLUGIN_URL . 'pmod=form&op=content_status&op1=delete&form_id=' . $form_id . '&id=' . $row['id'] . '&formhash=' . FORMHASH . '" onclick="if(confirm(\'' . lang('plugin/cxpform', 'delconfirm') . '\')){return true;}else{return false;}">' . lang('plugin/cxpform', 'delete') . '</a> 
					
				</td></tr><script type="text/javascript">
				jq(function(){
					jq(\'#sortid' . $row['id'] . '\').change(function(){
						var sortid = jq(this).val();
						jq.post(\'' . XF_PLUGIN_URL . 'pmod=form&op=content_status&op1=update_sort&formhash=' . FORMHASH . '\',{status_id:' . $row['id'] . ',sortid:sortid},function(data){
							if(data=="success"){
								self.location.href = location.href;
							}
						});
					});
					jq("#chk' . $row['id'] . '").click(function(){
						var val = jq(this).val();
						if(val == "0"){
							var newval = 1;
							jq(this).val("1");
						}else{
							var newval = 0;
							jq(this).val("0");
						}
						jq.post(\'' . XF_PLUGIN_URL . 'pmod=form&op=content_status&op1=update_default&formhash=' . FORMHASH . '\',{status_id:' . $row['id'] . ',is_default:newval},function(data){
							if(data=="success"){
								self.location.href = location.href;
							}
						});
					});
				});
				</script>';
			}
			

			showtablefooter();	
					
		}
?>