<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

		// 表单id
		$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
		cxpform_form_subnav($form_id, 'submit_setting');
		if($op1 === 'add'){ // 添加表单题目
			if(!submitcheck('formsubmit')) {
				include template('cxpform:more_content');
			}else{
				$content = trim($_GET['content']);
				
				if(!$content){
					cpmsg(lang('plugin/cxpform', 'more_content') . lang('plugin/cxpform', 'empty'), '', 'error');
				}
				
				$data1 = array(
					'form_id' => $form_id,
					'content' => $content,
				);
				
				$newcontentid = DB::insert('cxpform_more_content', $data1, 1);
				
				// 条件
				$logical = isset($_GET['logical']) ? $_GET['logical'] : '';
				$filtertype = isset($_GET['filtertype']) ? $_GET['filtertype'] : '';
				$executionorder = isset($_GET['executionorder']) ? $_GET['executionorder'] : '';
				$comparison = isset($_GET['comparison']) ? $_GET['comparison'] : '';
				$data = isset($_GET['data']) ? $_GET['data'] : '';
				if($logical != ''){
					foreach($logical as $k1=>$v1){
						$data01 = array(
							'form_id' => $form_id,
							'content_id' => $newcontentid,
							'logical' => $v1,
							'filtertype' => $filtertype[$k1],
							'executionorder' => $executionorder[$k1],
							'comparison' => $comparison[$k1],
							'data' => $data[$k1],
						);
						DB::insert('cxpform_more_content_acls', $data01);
					}
				}
				
				cpmsg(lang('plugin/cxpform', 'add_more_content_success'), XF_PLUGIN_URL . "pmod=form&op=more_content&form_id=" . $form_id, 'succeed');
			}			
		}elseif($op1 === 'edit'){
			$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			if(!submitcheck('formsubmit')) {
				$info = DB::fetch_first('select * from ' . DB::table('cxpform_more_content') . ' where id=' . $id);
				
				$acls = DB::fetch_all('select * from ' . DB::table('cxpform_more_content_acls') . ' where content_id=' . $id);
				
				include template('cxpform:more_content');
				
			}else{
				$content = trim($_GET['content']);
				if(!$content){
					cpmsg(lang('plugin/cxpform', 'content') . lang('plugin/cxpform', 'empty'), '', 'error');
				}
				
				$data1 = array(
					'content' => $content,
				);
				
				DB::update('cxpform_more_content', $data1, DB::field('id', $id));
				
				// 条件
				$aclid = isset($_GET['aclid']) ? $_GET['aclid'] : '';
				$logical = isset($_GET['logical']) ? $_GET['logical'] : '';
				$filtertype = isset($_GET['filtertype']) ? $_GET['filtertype'] : '';
				$executionorder = isset($_GET['executionorder']) ? $_GET['executionorder'] : '';
				$comparison = isset($_GET['comparison']) ? $_GET['comparison'] : '';
				$data = isset($_GET['data']) ? $_GET['data'] : '';	
				
				if($logical != ''){
					foreach($logical as $k1=>$v1){
						if($aclid[$k1] == ''){
							$data01 = array(
								'form_id' => $form_id,
								'content_id' => $id,
								'logical' => $v1,
								'filtertype' => $filtertype[$k1],
								'executionorder' => $executionorder[$k1],
								'comparison' => $comparison[$k1],
								'data' => $data[$k1],
							);
							DB::insert('cxpform_more_content_acls', $data01);						
						}else{
							$data01 = array(
								'logical' => $v1,
								'filtertype' => $filtertype[$k1],
								'executionorder' => $executionorder[$k1],
								'comparison' => $comparison[$k1],
								'data' => $data[$k1],
							);
							DB::update('cxpform_more_content_acls', $data01, array('id' => $aclid[$k1]));							
						}
					}
				}
				
				// 删除的条件
				$todelaclid = $_GET['todelaclid'];
				if($todelaclid != ''){
					$arr1 = explode(',', $todelaclid);
					DB::delete('cxpform_more_content_acls', DB::field('id', $arr1));
				}
				

				cpmsg(lang('plugin/cxpform', 'edit_more_content_success'), XF_PLUGIN_URL . "pmod=form&op=more_content&form_id=" . $form_id . '', 'succeed');				
			}
		}elseif($op1 === 'delete'){
			$formhash = isset($_GET['formhash']) ? trim($_GET['formhash']) : '';
			if($formhash == formhash()){
				//删除
				$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
				// 删除
				DB::delete('cxpform_more_content_acls', DB::field('content_id', $id));
				DB::delete('cxpform_more_content', DB::field('id', $id));
				
				cpmsg(lang('plugin/cxpform', 'delete_more_content_success'), XF_PLUGIN_URL . "pmod=form&op=more_content&form_id=" . $form_id, 'succeed');
			}
		}else{
			
			// 字段列表
			$ppp = 10;
			$resultempty = FALSE;
			$page = max(1, intval($_GET['page']));
			echo '<a href="' . XF_PLUGIN_URL . 'pmod=form&op=more_content&op1=add&form_id=' . $form_id . '" class="addtr">' . lang('plugin/cxpform', 'add_more_content') . '</a>';
		
			showtableheader();
			showtitle(cxpform_lang('more_content_list'));


			echo '<tr class="header">
					<th>' . lang('plugin/cxpform', 'id') . '</th>
					<th>' . lang('plugin/cxpform', 'more_content') . '</th>
					<th>' . lang('plugin/cxpform', 'op') . '</th>
					</tr>';
			$count = DB::result_first('select count(*) from ' . DB::table('cxpform_more_content') . ' where form_id=' . $form_id);
			$results = DB::fetch_all('SELECT * FROM '.DB::table('cxpform_more_content') . ' where form_id=' . $form_id . ' order by sortid asc,id asc limit ' . intval(($page-1)*$ppp) . ',' . $ppp);
			if($count > 0){
			foreach($results as $row) {
				echo '<tr class="hover">
					<td class="td25">' . $row['id'] . '</td>
					<td>' . $row['content'] . '</td>
					
					<td>
					<a href="' . XF_PLUGIN_URL . 'pmod=form&op=more_content&op1=edit&form_id=' . $form_id . '&id=' . $row['id'] . '">' . lang('plugin/cxpform', 'edit') . '</a> 
					<a href="' . XF_PLUGIN_URL . 'pmod=form&op=more_content&op1=delete&form_id=' . $form_id . '&id=' . $row['id'] . '&formhash=' . FORMHASH . '" onclick="if(confirm(\'' . lang('plugin/cxpform', 'delconfirm') . '\')){return true;}else{return false;}">' . lang('plugin/cxpform', 'delete') . '</a> 
					
				</td></tr>';
			}
			}else{
				echo '<tr><td colspan="3">' . cxpform_lang('nodata') . '</td></tr>';
			}
			

			showtablefooter();	
			echo multi($count, $ppp, $page, XF_PLUGIN_URL . "pmod=form&op=more_content&form_id=" . $form_id);		
		}
?>