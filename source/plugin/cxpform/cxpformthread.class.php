<?php 
/*
 *	
 *	Description:帖子回复内容扩展
 * */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
// loadcache('plugin');
include_once(dirname(__FILE__) . '/include/function.php');
class plugin_cxpform{

	//TODO - Insert your code here
	/**
	 * @Methods describe
	 * @return string type
	 */
	// public function global_header() {
		//TODO - Insert your code here
		
		//return 'TODO:global_header';	//TODO modify your return code here
	// }

	function deletethread($param) {
		if($param['step'] == 'delete') {
			$tid = $param['param'][0][0];
			if($tid) {
				$thread = get_thread_by_tid($result['tid'], 'tid');
				if(empty($thread)) {
					$form_id = DB::result_first("SELECT id FROM ".DB::table('cxpforms')." WHERE tid='$tid'");
					DB::delete('cxpform_notice_setting', DB::field('form_id', $form_id));
					DB::delete('cxpform_submit_setting', DB::field('form_id', $form_id));
					DB::delete('cxpform_hits', DB::field('form_id', $form_id));
					DB::delete('cxpform_field_value', DB::field('form_id', $form_id));
					// 删除表单内容
					DB::delete('cxpform_contents', DB::field('form_id', $form_id));
					// 删除表单内容扩展字段,删除分表
					// for($i=0;$i<XF_EXTRA_TABLE_NUM;$i++){
						// DB::delete('cxpform_content_extra_' . $i, DB::field('form_id', $form_id));
					// }
					DB::delete('cxpform_content_extra', DB::field('form_id', $form_id));
					// 删除表单字段
					DB::delete('cxpform_fields', DB::field('form_id', $form_id));
					// 删除字段选项
					DB::delete('cxpform_field_options', DB::field('form_id', $form_id));
					// 删除表单
					DB::delete('cxpforms', DB::field('id', $form_id));
					
					// 删除回复
					// C::t('forum_post')->delete_by_tid(0, $tid);
					// 删除帖子
					// C::t('forum_thread')->delete($tid);
					
				}
			}
		}		
	}

}	

class plugin_cxpform_forum extends plugin_cxpform {

}
?>