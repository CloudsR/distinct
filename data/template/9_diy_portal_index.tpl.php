<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); 
block_get('123,124,125,126,127,128,90,91,92,130,94,129,93,131,132,133,134,135,136,142,95,96,97,98,99,100,101,139,138,104,103,140,105,137,102,141,106');?><?php include template('common/header2'); ?><link rel="stylesheet" type="text/css" id="time_diy" href="template/elec_201701_hx_dingzhi/style/css/menhu.css" />
<style id="diy_style" type="text/css"></style>
<script src="template/elec_201701_hx_dingzhi/style/js/jquery.superslide.js" type="text/javascript" type="text/javascript"></script>

</div>
<div class="cl" style="padding: 20px 0; background: #fff;">
   <div class="wp cl">
      <div style="float: left; width: 840px;">
      <!--[diy=diy_1]--><div id="diy_1" class="area"><div id="framegPldqL" class="frame move-span cl frame-1"><div id="framegPldqL_left" class="column frame-1-c"><div id="framegPldqL_left_temp" class="move-span temp"></div><?php block_display('123');?></div></div></div><!--[/diy]-->
      </div>
      <div style="float: right; width: 340px;">
      <!--[diy=diy_2]--><div id="diy_2" class="area"><div id="framefMzrPR" class="frame move-span cl frame-1"><div id="framefMzrPR_left" class="column frame-1-c"><div id="framefMzrPR_left_temp" class="move-span temp"></div><?php block_display('124');?></div></div><div id="frameybCYil" class="frame move-span cl frame-1"><div id="frameybCYil_left" class="column frame-1-c"><div id="frameybCYil_left_temp" class="move-span temp"></div><?php block_display('125');?></div></div><div id="frameTDG7O1" class="frame move-span cl frame-1"><div id="frameTDG7O1_left" class="column frame-1-c"><div id="frameTDG7O1_left_temp" class="move-span temp"></div></div></div></div><!--[/diy]-->
      </div>
   </div>
</div>
<div class="cl" style=" margin-top: 20px;">
   <div class="wp" >
    <div style="float: left; width: 840px;">
         <!--[diy=diy_3]--><div id="diy_3" class="area"><div id="frameI5Yeg3" class="frame move-span cl frame-1"><div id="frameI5Yeg3_left" class="column frame-1-c"><div id="frameI5Yeg3_left_temp" class="move-span temp"></div><?php block_display('126');?></div></div><div id="framecM30M7" class="frame move-span cl frame-1"><div id="framecM30M7_left" class="column frame-1-c"><div id="framecM30M7_left_temp" class="move-span temp"></div><?php block_display('127');?></div></div><div id="frameqSpd3Y" class="frame move-span cl frame-1"><div id="frameqSpd3Y_left" class="column frame-1-c"><div id="frameqSpd3Y_left_temp" class="move-span temp"></div><?php block_display('128');?></div></div><div id="framelIOgic" class="frame move-span cl frame-1"><div id="framelIOgic_left" class="column frame-1-c"><div id="framelIOgic_left_temp" class="move-span temp"></div><?php block_display('90');?></div></div><div id="frameSuWWIA" class="frame move-span cl frame-1"><div id="frameSuWWIA_left" class="column frame-1-c"><div id="frameSuWWIA_left_temp" class="move-span temp"></div><?php block_display('91');?></div></div><div id="framesHuZ62" class="frame move-span cl frame-1"><div id="framesHuZ62_left" class="column frame-1-c"><div id="framesHuZ62_left_temp" class="move-span temp"></div><?php block_display('92');?></div></div></div><!--[/diy]-->
         <div class="cl">
         <div class="cl" style="float: left; width: 380px;">
         <!--[diy=diy_4]--><div id="diy_4" class="area"><div id="frameO8WKsZ" class="frame move-span cl frame-1"><div id="frameO8WKsZ_left" class="column frame-1-c"><div id="frameO8WKsZ_left_temp" class="move-span temp"></div><?php block_display('130');?></div></div><div id="frameP7qffQ" class="frame move-span cl frame-1"><div id="frameP7qffQ_left" class="column frame-1-c"><div id="frameP7qffQ_left_temp" class="move-span temp"></div><?php block_display('94');?></div></div></div><!--[/diy]-->
         </div>
         <div class="cl" style="float: right; width: 380px;">
         <!--[diy=diy_5]--><div id="diy_5" class="area"><div id="frameS8QQjl" class="frame move-span cl frame-1"><div id="frameS8QQjl_left" class="column frame-1-c"><div id="frameS8QQjl_left_temp" class="move-span temp"></div><?php block_display('129');?></div></div><div id="frameGp83Oa" class="frame move-span cl frame-1"><div id="frameGp83Oa_left" class="column frame-1-c"><div id="frameGp83Oa_left_temp" class="move-span temp"></div><?php block_display('93');?></div></div></div><!--[/diy]-->
         </div>
         </div>
         </div>
         <div style="float: right; width: 340px;">
            <div class="login_box" style="margin-bottom: 20px;">
               <div class="a_img">
               <?php if($_G['uid']) { ?><a href="home.php?mod=space&amp;uid=<?php echo $_G['uid'];?>" target="_blank" title="访问我的空间" id="umnav" onMouseOver="showMenu({'ctrlid':this.id,'ctrlclass':'a'})"> 
                 <?php echo avatar($_G[uid],middle);?>                 </a><?php } else { ?><img src="template/elec_201701_hx_dingzhi/style/10.jpg" alt="" height="95" width="95"><?php } ?>
               </div>
               <div class="a_info">
               <?php if($_G['uid']) { ?><div class="cl"><?php echo $_G['member']['username'];?></div><div class="lg_button cl"><a href="home.php?mod=spacecp">设置</a><a id="nte_menu" href="home.php?mod=space&amp;do=notice" class="notification" style="margin: 0; background: #66CC99;">提醒<?php if($_G['member']['newprompt']) { ?><span style="padding-left: 5px; color: #F08989;"><?php echo $_G['member']['newprompt'];?></span><?php } ?></a></div><?php } else { ?><div class="cl" style="line-height: 24px;">亲爱的会员</br>欢迎回来</div><div class="lg_button cl"><a href="member.php?mod=<?php echo $_G['setting']['regname'];?>">注册</a><a href="member.php?mod=logging&amp;action=login" onClick="showWindow('login', this.href)" style="margin: 0; background: #66CC99;">登录</a></div><?php } ?>
               </div>
            </div>
            <!--[diy=diy_10]--><div id="diy_10" class="area"><div id="framer11U3E" class="frame move-span cl frame-1"><div id="framer11U3E_left" class="column frame-1-c"><div id="framer11U3E_left_temp" class="move-span temp"></div><?php block_display('131');?></div></div><div id="frameslabi9" class="frame move-span cl frame-1"><div id="frameslabi9_left" class="column frame-1-c"><div id="frameslabi9_left_temp" class="move-span temp"></div><?php block_display('132');?></div></div><div id="framehD4oO1" class="frame move-span cl frame-1"><div id="framehD4oO1_left" class="column frame-1-c"><div id="framehD4oO1_left_temp" class="move-span temp"></div><?php block_display('133');?></div></div><div id="framedlE8N7" class="frame move-span cl frame-1"><div id="framedlE8N7_left" class="column frame-1-c"><div id="framedlE8N7_left_temp" class="move-span temp"></div><?php block_display('134');?></div></div><div id="tabTaD1D0" class="tab1 frame-tab move-span cl"><div id="tabTaD1D0_title" class="tab-title title column cl" switchtype="click"><?php block_display('135');?><?php block_display('136');?></div><div id="tabTaD1D0_content" class="tb-c"></div><script type="text/javascript">initTab("tabTaD1D0","click");</script></div><div id="framee99486" class="frame move-span cl frame-1"><div id="framee99486_left" class="column frame-1-c"><div id="framee99486_left_temp" class="move-span temp"></div><?php block_display('142');?></div></div><div id="frameXHFZVA" class="frame move-span cl frame-1"><div id="frameXHFZVA_left" class="column frame-1-c"><div id="frameXHFZVA_left_temp" class="move-span temp"></div><?php block_display('95');?></div></div><div id="frameOE3UuS" class="frame move-span cl frame-1"><div id="frameOE3UuS_left" class="column frame-1-c"><div id="frameOE3UuS_left_temp" class="move-span temp"></div><?php block_display('96');?></div></div><div id="frames1e1AS" class="frame move-span cl frame-1"><div id="frames1e1AS_left" class="column frame-1-c"><div id="frames1e1AS_left_temp" class="move-span temp"></div><?php block_display('97');?></div></div><div id="framej7ju89" class="frame move-span cl frame-1"><div id="framej7ju89_left" class="column frame-1-c"><div id="framej7ju89_left_temp" class="move-span temp"></div><?php block_display('98');?></div></div><div id="tabcY2LyR" class="tab1 frame-tab move-span cl"><div id="tabcY2LyR_title" class="tab-title title column cl" switchtype="click"><?php block_display('99');?><?php block_display('100');?></div><div id="tabcY2LyR_content" class="tb-c"></div><script type="text/javascript">initTab("tabcY2LyR","click");</script></div><div id="framenb540J" class="frame move-span cl frame-1"><div id="framenb540J_left" class="column frame-1-c"><div id="framenb540J_left_temp" class="move-span temp"></div><?php block_display('101');?></div></div></div><!--[/diy]-->
         </div>
      </div>
   </div>
<div class="wp cl">
   <div class="box cl" style="padding-bottom: 10px;">
      <div class="cl">
         <h3 style="margin-left: 20px;">焦点图</h3>
         <em class="y" style="margin: 16px 20px 0 0;"><a href="#" style="color: #cb2f2f; font-size: 18px;">我要上传</a></em>
      </div>
      <div class="cl" style="margin: 0 20px;">
         <div style="float: left; width: 370px; margin-right: 10px;">
            <!--[diy=diy_11]--><div id="diy_11" class="area"><div id="framewmIihU" class="frame move-span cl frame-1"><div id="framewmIihU_left" class="column frame-1-c"><div id="framewmIihU_left_temp" class="move-span temp"></div><?php block_display('139');?></div></div><div id="frameIZRL2R" class="frame move-span cl frame-1"><div id="frameIZRL2R_left" class="column frame-1-c"><div id="frameIZRL2R_left_temp" class="move-span temp"></div><?php block_display('138');?></div></div><div id="frameNA8BpH" class="frame move-span cl frame-1"><div id="frameNA8BpH_left" class="column frame-1-c"><div id="frameNA8BpH_left_temp" class="move-span temp"></div><?php block_display('104');?></div></div><div id="frameJBA0x4" class="frame move-span cl frame-1"><div id="frameJBA0x4_left" class="column frame-1-c"><div id="frameJBA0x4_left_temp" class="move-span temp"></div><?php block_display('103');?></div></div></div><!--[/diy]-->
         </div>
         <div style="float: left; width: 180px; margin-right: 10px;">
            <!--[diy=diy_12]--><div id="diy_12" class="area"><div id="framezcRfLD" class="frame move-span cl frame-1"><div id="framezcRfLD_left" class="column frame-1-c"><div id="framezcRfLD_left_temp" class="move-span temp"></div><?php block_display('140');?></div></div><div id="frameNEMkIM" class="frame move-span cl frame-1"><div id="frameNEMkIM_left" class="column frame-1-c"><div id="frameNEMkIM_left_temp" class="move-span temp"></div><?php block_display('105');?></div></div></div><!--[/diy]-->
         </div>
         <div style="float: left; width: 250px; margin-right: 10px;">
            <!--[diy=diy_13]--><div id="diy_13" class="area"><div id="frameUiXx48" class="frame move-span cl frame-1"><div id="frameUiXx48_left" class="column frame-1-c"><div id="frameUiXx48_left_temp" class="move-span temp"></div><?php block_display('137');?></div></div><div id="frameQR3ctr" class="frame move-span cl frame-1"><div id="frameQR3ctr_left" class="column frame-1-c"><div id="frameQR3ctr_left_temp" class="move-span temp"></div><?php block_display('102');?></div></div></div><!--[/diy]-->
         </div>
         <div style="float: left; width: 300px; margin-right: 0;">
            <!--[diy=diy_15]--><div id="diy_15" class="area"><div id="framezOZzrF" class="frame move-span cl frame-1"><div id="framezOZzrF_left" class="column frame-1-c"><div id="framezOZzrF_left_temp" class="move-span temp"></div><?php block_display('141');?></div></div><div id="frameE2mDdp" class="frame move-span cl frame-1"><div id="frameE2mDdp_left" class="column frame-1-c"><div id="frameE2mDdp_left_temp" class="move-span temp"></div><?php block_display('106');?></div></div></div><!--[/diy]-->
         </div>
      </div>
   </div>
</div>

 <?php include template('common/footer'); ?>