<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('list_3');
block_get('19');?><?php include template('common/header'); $list = array();?><?php $wheresql = category_get_wheresql($cat);?><?php $list = category_get_list($cat, $wheresql, $page);?><link rel="stylesheet" type="text/css" href="template/elec_201701_hxzmt/style/css/about.css" />
<script src="template/elec_201701_hxzmt/style/js/about.js" type="text/javascript"></script><?php echo adshow("text/wp a_t");?><style id="diy_style" type="text/css"></style>
</div>
<div class="wp" style="min-height: 500px;">
  <!--[diy=diy1]--><div id="diy1" class="area"><div id="frameYq2xXU" class="frame move-span cl frame-1"><div id="frameYq2xXU_left" class="column frame-1-c"><div id="frameYq2xXU_left_temp" class="move-span temp"></div><?php block_display('19');?></div></div></div><!--[/diy]-->
</div><?php include template('common/footer'); ?>