<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

		$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
		$forminfo = cxpform_form_info($form_id);
		cxpform_form_subnav($form_id, 'get_code');
		showtableheader();
		
		showtitle(lang('plugin/cxpform', 'code_link_style3'));
		
		showsetting(lang('plugin/cxpform', 'code_link_style3_copy_tip'), '', '<script src="' . $_G['siteurl'] . 'plugin.php?id=cxpform:ajax&op=showcount&form_id=' . $form_id . '"></script>', 'textarea', '', 0, '', 'style="height:50px;width:500px;"', '', true);
		
		showtitle(lang('plugin/cxpform', 'code_link_style1'));
		
		$str1 = '<script type="text/javascript" src="' . $_G['siteurl'] . 'source/plugin/cxpform/resource/js/jquery.js" language="javascript"></script>
		<script type="text/javascript">var jq = jQuery.noConflict();</script>
		<script type="text/javascript" src="' . $_G['siteurl'] . 'source/plugin/cxpform/resource/js/jquery.form.js" language="javascript"></script>
		<script type="text/javascript" src="' . $_G['siteurl'] . 'source/plugin/cxpform/resource/js/jquery.validate.min.js" language="javascript"></script>
		<script type="text/javascript" src="' . $_G['siteurl'] . 'source/plugin/cxpform/resource/js/My97DatePicker/WdatePicker.js" language="javascript"></script>
		<link rel="stylesheet" type="text/css" href="' . $_G['siteurl'] . 'source/plugin/cxpform/resource/css/style.css" />
			<script type="text/javascript">
				jq(function(){
					jq(\'#fromurl\').val(window.location.href);
					jq(\'#cxpform' . $form_id . '\').validate({
						ignore: "",
						errorPlacement: function(error, element) {
							var id = element.attr(\'id\');
							if(element.attr("type") == "checkbox"){
								id = element.attr("name").substring(0, element.attr("name").length - 2);
								error.appendTo(\'#\' + id + \'error\');
							}
							if(element.attr("type") == "radio"){
								id = element.attr("name");
								error.appendTo(\'#\' + id + \'error\');
							}
							if(jq(document).find(\'#\' + id + \'error\')){
								error.appendTo(\'#\' + id + \'error\');
							}
						}
					});
					jq(\'#cxpform' . $form_id . '\').ajaxForm({
						target:\'#result\',
						success:function(responseText, statusText, xhr, form){
							if(responseText == \'' . lang('plugin/cxpform', 'success') . '\'){
								jq(\'#cxpform' . $form_id . 'btn\').attr(\'disabled\',\'disabled\');
								showDialog(responseText, \'notice\');
							}else{
								showDialog(responseText, \'notice\');
							}
						}
					});
				});
			</script>';
			
		$str2 = '<script type="text/javascript" src="' . $_G['siteurl'] . 'source/plugin/cxpform/resource/js/jquery.js" language="javascript"></script>
		<script type="text/javascript">var jq = jQuery.noConflict();</script>
		<script type="text/javascript" src="' . $_G['siteurl'] . 'source/plugin/cxpform/resource/js/jquery.form.js" language="javascript"></script>
		<script type="text/javascript" src="' . $_G['siteurl'] . 'source/plugin/cxpform/resource/js/jquery.validate.min.js" language="javascript"></script>
		<script type="text/javascript" src="' . $_G['siteurl'] . 'source/plugin/cxpform/resource/js/My97DatePicker/WdatePicker.js" language="javascript"></script>
		<link rel="stylesheet" type="text/css" href="' . $_G['siteurl'] . 'source/plugin/cxpform/resource/css/style.css" />
			<script type="text/javascript">
				jq(function(){
					jq(\'#fromurl\').val(window.location.href);
					jq(\'#cxpform' . $form_id . '\').validate({
						ignore: "",
						errorPlacement: function(error, element) {
							var id = element.attr(\'id\');
							if(element.attr("type") == "checkbox"){
								id = element.attr("name").substring(0, element.attr("name").length - 2);
								error.appendTo(\'#\' + id + \'error\');
							}
							if(element.attr("type") == "radio"){
								id = element.attr("name");
								error.appendTo(\'#\' + id + \'error\');
							}
							if(jq(document).find(\'#\' + id + \'error\')){
								error.appendTo(\'#\' + id + \'error\');
							}
						}
					});
					jq(\'#cxpform' . $form_id . '\').ajaxForm({
						target:\'#result\',
						success:function(responseText, statusText, xhr, form){
							if(responseText == \'' . lang('plugin/cxpform', 'success') . '\'){
								jq(\'#cxpform' . $form_id . 'btn\').attr(\'disabled\',\'disabled\');
								jq(\'#myModalContent\').html(responseText);
								jq(\'#myModal\').modal();
							}else{
								jq(\'#myModalContent\').html(responseText);
								jq(\'#myModal\').modal();
								jq(\'#result\').show();
							}
						}
					});
				});
			</script>';			
		
		$textcontent1 = $str1 . "<div class=\"error\" id=\"result\"></div><form id=\"cxpform" . $form_id . "\" action=\"" . $_G['siteurl'] . "plugin.php?id=cxpform:style1&form_id=" . $form_id . "\" method=\"post\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"formhash\" value=\"{FORMHASH}\" /><input type=\"hidden\" name=\"fromurl\" id=\"fromurl\" /><table cellspacing=\"0\" cellpadding=\"0\" class=\"tfm\">\n";
		$textcontent2 = '';
		
		$fields = cxpform_field_list($form_id);
		foreach($fields as $field){
			$textcontent2 .= '<tr><td>' . $field['field_label'] . '</td><td>' . cxpform_render_field($field) . "</td></tr>\n";
		}
		// 验证码
		$textcontent2 .= '<tr><td>' . cxpform_lang('captcha') . ':<span class="rq">*</span></td><td><input type="text" name="captcha_code" id="captcha_code" class="px" style="width:100px;" /><img id="captcha_img" src="' . $_G['siteurl'] .'source/plugin/cxpform/include/securimage/securimage_show.php" width="100" height="30" style="vertical-align:bottom;" /> <a href="#" onclick="document.getElementById(\'captcha_img\').src=\'' . $_G['siteurl'] . 'source/plugin/cxpform/include/securimage/securimage_show.php\';return false;">' . cxpform_lang('reload_captcha') . '</a><p id="captcha_codeerror" style="padding-left:10px;clear:both;"></p></td></tr><script type="text/javascript">jq(function(){jq(\'#captcha_code\').rules(\'add\', {required:true,messages:{required:\'' . cxpform_lang('captcha') . ' ' . cxpform_lang('empty') . '\'}});});</script>';
		// 提交按钮
		$textcontent2 .= '<tr><td><button type="submit" name="formsubmit" id="cxpform' . $form_id . 'btn" value="true" class="pn pnc"><strong>' . cxpform_lang('submit') . '</strong></button>';
		if($submit_setting['show_history'] > 0){
			$textcontent2 .= '<a href="plugin.php?id=cxpform:dashboard&op=my_contents" target="_blank">' . cxpform_lang('dashboard_nav2') . '</a>';
		}
		$textcontent2 .= '</td></tr>';					
		
		$textcontent1 .= $textcontent2 . '</table></form>';
		
		showsetting(lang('plugin/cxpform', 'copy_to_view'), '', $_G['siteurl'] . 'plugin.php?id=cxpform:style1&form_id=' . $form_id, 'textarea', '', 0, '', 'style="height:50px;width:500px;"', '', true);
		
		showtitle(lang('plugin/cxpform', 'code_link_style2'));
		
		showsetting(lang('plugin/cxpform', 'copy_to_view'), '', $_G['siteurl'] . 'plugin.php?id=cxpform:style2&form_id=' . $form_id, 'textarea', '', 0, '', 'style="height:50px;width:500px;"', '', true);
		
		if($forminfo['form_type'] != '2'){
		showtitle(lang('plugin/cxpform', 'code_common'));
		showsetting('', '', $textcontent1, 'textarea', '', 0, '', 'style="height:100px;width:500px;"', '', true);
		}
		
		if($forminfo['form_type'] != '2'){
		$textcontent3 = $str2. "<style type=\"text/css\">
	.form-group{border-bottom:1px solid #EFEFEF;clear:both;}
	.caption{text-align:center;}
	.caption label{font-weight:400;}
	.caption label input{margin-right:5px;}
	</style><div class=\"alert alert-danger\" role=\"alert\" id=\"result\" style=\"display:none;\"></div><form id=\"cxpform" . $form_id . "\" action=\"" . $_G['siteurl'] . "plugin.php?id=cxpform:style2&form_id=" . $form_id . "\" method=\"post\" enctype=\"multipart/form-data\"><input type=\"hidden\" name=\"formhash\" value=\"{FORMHASH}\" /><input type=\"hidden\" name=\"fromurl\" id=\"fromurl\" />\n";
		
		$textcontent4 = '';
		foreach($fields as $field){
			$textcontent4 .= cxpform_render_field_bootstrap($field);
		}
		
		// 验证码
		$textcontent4 .= '<div class="form-group">
			<label>' . lang('plugin/cxpform', 'captcha') . ':</label>
			<input type="text" name="captcha_code" class="form-control" id="captcha_code" /><br /><img id="captcha_img" src="' . $_G['siteurl'] . 'source/plugin/cxpform/include/securimage/securimage_show.php" width="100" height="30" /> <a href="#" onclick="document.getElementById(\'captcha_img\').src=\'' . $_G['siteurl'] . 'source/plugin/cxpform/include/securimage/securimage_show.php\';return false;">' . lang('plugin/cxpform', 'reload_captcha') . '</a><p class="help-block" id="captcha_codeerror"></p>
		  </div><script type="text/javascript">jq(function(){jq(\'#captcha_code\').rules(\'add\', {required:true,messages:{required:\'' . lang('plugin/cxpform', 'captcha') . ' ' . lang('plugin/cxpform', 'empty') . '\'}});});</script>';
		// 提交按钮
		$textcontent4 .= '<button type="submit" class="btn btn-default" name="formsubmit" id="cxpform' . $form_id . 'btn" value="' . lang('plugin/cxpform', 'submit') . '">' . lang('plugin/cxpform', 'submit') . '</button>';
		
		$textcontent3 .= $textcontent4 . "</form>" . '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">' . cxpform_lang('close') . '</span></button>
        <h4 class="modal-title" id="myModalLabel">' . cxpform_lang('modal_title') . '</h4>
      </div>
      <div class="modal-body" id="myModalContent">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">' . cxpform_lang('close') . '</button>
        
      </div>
    </div>
  </div>
</div><script src="source/plugin/cxpform/resource/bootstrap/js/bootstrap.min.js"></script>
    <script src="source/plugin/cxpform/resource/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="source/plugin/cxpform/resource/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js"></script>';
		
		showtitle(lang('plugin/cxpform', 'code_bootstrap'));
		showsetting('', '', $textcontent3, 'textarea', '', 0, '', 'style="height:100px;width:500px;"', '', true);
		}
		showtitle(lang('plugin/cxpform', 'code_iframe'));
		showsetting(lang('plugin/cxpform', 'code_iframe1'), '', '<iframe src="' . $_G['siteurl'] . 'plugin.php?id=cxpform:style2&form_id=' . $form_id . '&type=iframe" frameborder="0" width="100%" height="500"></iframe>', 'textarea', '', 0, '', 'style="height:50px;width:500px;"', '', true);
		
		showtitle(lang('plugin/cxpform', 'code_bbcode'));
		echo '<tr><td colspan="15">' . lang('plugin/cxpform', 'code_bbcode_content') . '</td></tr>';
		
		
		showtablefooter();
?>