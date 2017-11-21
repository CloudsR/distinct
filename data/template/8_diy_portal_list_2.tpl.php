<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('list_2');
block_get('21,20');?><?php include template('common/header'); $list = array();?><?php $wheresql = category_get_wheresql($cat);?><?php $list = category_get_list($cat, $wheresql, $page);?><link rel="stylesheet" type="text/css" href="template/elec_201701_hxzmt/style/css/event.css" />
<script src="template/elec_201701_hxzmt/style/js/jquery.superslide.js" type="text/javascript" type="text/javascript"></script>
</div><?php echo adshow("text/wp a_t");?><style id="diy_style" type="text/css"></style>
<div class="wp" style="min-height: 300px;"> 
  <!--[diy=diy_top]--><div id="diy_top" class="area"><div id="frameQtNY36" class="frame move-span cl frame-1"><div id="frameQtNY36_left" class="column frame-1-c"><div id="frameQtNY36_left_temp" class="move-span temp"></div><?php block_display('21');?></div></div></div><!--[/diy]--> 
  <!--[diy=diy1]--><div id="diy1" class="area"><div id="framesgOP0t" class="frame move-span cl frame-1"><div id="framesgOP0t_left" class="column frame-1-c"><div id="framesgOP0t_left_temp" class="move-span temp"></div><?php block_display('20');?></div></div></div><!--[/diy]--> 
</div><?php include template('common/footer'); ?>