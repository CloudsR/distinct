<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('list_2');
block_get('229,228,236,232');?><?php include template('common/header'); $list = array();?><?php $wheresql = category_get_wheresql($cat);?><?php $list = category_get_list($cat, $wheresql, $page);?><link rel="stylesheet" type="text/css" href="template/elec_201701_hx_dingzhi/style/css/event.css" />
<script src="template/elec_201701_hx_dingzhi/style/js/jquery.superslide.js" type="text/javascript" type="text/javascript"></script><?php echo adshow("text/wp a_t");?><style id="diy_style" type="text/css"></style>

</div>
<div class="cl" style="padding: 20px 0; margin: 20px 0 0 0; background: #FFFFFF;">
   <div class="wp cl">
      <div style="float: left; width: 630px;">
      <!--[diy=diy_1]--><div id="diy_1" class="area"><div id="frameP41BRQ" class="frame move-span cl frame-1"><div id="frameP41BRQ_left" class="column frame-1-c"><div id="frameP41BRQ_left_temp" class="move-span temp"></div><?php block_display('229');?></div></div></div><!--[/diy]-->
      </div>
      <div style="float: right; width: 530px;">
      <!--[diy=diy_2]--><div id="diy_2" class="area"></div><!--[/diy]-->
      </div>
   </div>
</div>
<div class="wp cl">
 <div style="float: left; width: 780px;">
      <!--[diy=diy_3]--><div id="diy_3" class="area"><div id="frameBN7LTF" class="frame move-span cl frame-1"><div id="frameBN7LTF_left" class="column frame-1-c"><div id="frameBN7LTF_left_temp" class="move-span temp"></div><?php block_display('228');?></div></div></div><!--[/diy]-->
      </div>
      <div style="float: right; width: 370px;">
         <!--[diy=diy_10]--><div id="diy_10" class="area"><div id="frameEwyNfc" class="xfs xfs_1 frame move-span cl frame-1"><div id="frameEwyNfc_left" class="column frame-1-c"><div id="frameEwyNfc_left_temp" class="move-span temp"></div><?php block_display('236');?><?php block_display('232');?></div></div></div><!--[/diy]-->
      </div>
</div>
<div class="wp cl">
<!--[diy=diy_11]--><div id="diy_11" class="area"></div><!--[/diy]-->
</div>
<div class="wp cl">
 <div style="float: left; width: 780px;">
      <!--[diy=diy_12]--><div id="diy_12" class="area"></div><!--[/diy]-->
      </div>
      <div style="float: right; width: 370px;">
         <!--[diy=diy_13]--><div id="diy_13" class="area"></div><!--[/diy]-->
      </div>
</div><?php include template('common/footer'); ?>