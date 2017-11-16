<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('list_1');
block_get('22');?><?php include template('common/header'); $list = array();?><?php $wheresql = category_get_wheresql($cat);?><?php $list = category_get_list($cat, $wheresql, $page);?><link rel="stylesheet" type="text/css" href="template/elec_201701_hxzmt/style/css/pindao.css" />
<script src="template/elec_201701_hxzmt/style/js/jquery.superslide.js" type="text/javascript" type="text/javascript"></script><?php echo adshow("text/wp a_t");?><style id="diy_style" type="text/css"></style>
<div class="wp"> 
  <!--[diy=diy1]--><div id="diy1" class="area"></div><!--[/diy]--> 
</div>
<div id="ct" class="ct2 wp inside_box cl" style="margin: 30px 0 0 0;">
  <div style="float: left; width: 800px; box-shadow: none;">
  <div class="mn cl" style="float: none; padding: 0; margin: 0; background: none;">
  <!--[diy=diy_topic]--><div id="diy_topic" class="area"></div><!--[/diy]-->
  <div class="tit_top cl">
        <h3><?php echo $cat['catname'];?></h3><div class="cl" style="float: left; min-width: 200px; min-height: 30px;"><!--[diy=diy_info]--><div id="diy_info" class="area"></div><!--[/diy]--></div> 
        <?php if(($_G['group']['allowpostarticle'] || $_G['group']['allowmanagearticle'] || $categoryperm[$catid]['allowmanage'] || $categoryperm[$catid]['allowpublish']) && empty($cat['disallowpublish'])) { ?>
           <a href="portal.php?mod=portalcp&amp;ac=article&amp;catid=<?php echo $cat['catid'];?>" class="y post">发布+</a>
        <?php } ?>
</div>
    <?php echo adshow("articlelist/mbm hm/1");?><?php echo adshow("articlelist/mbm hm/2");?> 
    <!--[diy=listcontenttop]--><div id="listcontenttop" class="area"></div><!--[/diy]-->
    <div class="bm" style="margin: 0; background: none;">
      <!-- 文章列表 begin -->
      <div class="list_new Framebox cl" style="padding: 0;"> 
              <div class="box recommend_article">
<div class="removeline cl">
<ul id="itemContainer">
        <?php if(is_array($list['list'])) foreach($list['list'] as $value) { ?> 
        <?php $highlight = article_title_style($value);?> 
        <?php $article_url = fetch_article_url($value);?>        
        
  <div class="mbox_list mod_art_list cl">
    <a href="<?php echo $article_url;?>" target="_blank" class="mod_art_list_pic"><img src="<?php echo $value['pic'];?>" alt="<?php echo $value['title'];?>"/></a>
    <div class="mod_art_list_content">
      <h3 class="list_title"><a href="<?php echo $article_url;?>" target="_blank" <?php echo $highlight;?>><?php echo $value['title'];?></a></h3>
      <div class="mod_art_list_info" style="font-family: Arial, Helvetica, sans-serif;"><a href="home.php?mod=space&amp;uid=<?php echo $comment['authorid'];?>" class="author1"><?php echo avatar($value[uid],middle);?></a><a href="home.php?mod=space&amp;uid=<?php echo authorid;?>" target="_blank"><?php echo $value['username'];?></a><span style="padding: 0 10px 0 20px;"><?php echo $value['dateline'];?></span></div>
      <div class="mod_art_list_simple"><?php echo $value['summary'];?></div>    
      <div class="column-link-box">
         <a href="<?php echo $portalcategory[$value['catid']]['caturl'];?>" class="column-link" target="_blank"><?php echo $value['catname'];?></a>
      </div>  
    </div>
  </div>
        <?php } ?> 
        </ul>
</div>
</div>
      </div>
      <!-- 文章列表 end --> 
      <!--[diy=listloopbottom]--><div id="listloopbottom" class="area"></div><!--[/diy]--> 
    </div>
    <?php echo adshow("articlelist/mbm hm/3");?><?php echo adshow("articlelist/mbm hm/4");?>    
    <!--[diy=diycontentbottom]--><div id="diycontentbottom" class="area"></div><!--[/diy]--> 
  </div>
    <?php if($list['multi']) { ?>
    <div class="pgs cl" style="margin-top: 0;"><?php echo $list['multi'];?></div>
    <?php } ?>
  </div>
  <div class="sd pph" style="box-shadow: none;">
    <div class="drag"> 
      <!--[diy=diyrighttop]--><div id="diyrighttop" class="area"></div><!--[/diy]--> 
    </div>
    
    <!-- 分类 -->
    <div class="box-moder cl" style="margin: 0;">
    <?php if($cat['subs']) { ?>
      <h3><span class="span-mark span-mark2"></span><b>精彩频道</b></h3>
      <div class="portal_sort Framebox2 cl" style="width: 280px; margin: 0 0 0 30px;">
        <ul>
          <?php if(is_array($cat['subs'])) foreach($cat['subs'] as $value) { ?>          <li><a href="<?php echo $portalcategory[$value['catid']]['caturl'];?>"><?php echo $value['catname'];?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } elseif($cat['others']) { ?>
      <h3><span class="span-mark span-mark2"></span><b>相关分类</b></h3>
      <div class="portal_sort Framebox2 cl" style="width: 280px; margin: 0 0 0 30px;">
        <ul>
          <?php if(is_array($cat['others'])) foreach($cat['others'] as $value) { ?>          <li><a href="<?php echo $portalcategory[$value['catid']]['caturl'];?>"><?php echo $value['catname'];?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
    </div> 
    <!-- 推荐阅读 -->
    <div class="sbody cl" style="margin: 0;">
      <!--[diy=sbody]--><div id="sbody" class="area"></div><!--[/diy]--> 
    </div>

    <div class="drag"> 
      <!--[diy=diy2]--><div id="diy2" class="area"><div id="framePs4Opi" class="frame move-span cl frame-1"><div id="framePs4Opi_left" class="column frame-1-c"><div id="framePs4Opi_left_temp" class="move-span temp"></div><?php block_display('22');?></div></div></div><!--[/diy]--> 
    </div>
  </div>
</div>
<div class="wp mtn"> 
  <!--[diy=diy3]--><div id="diy3" class="area"></div><!--[/diy]--> 
</div>
<script type="text/javascript">
   jQuery(".focusBox").slide({ mainCell:".bd ul",effect:"fold",autoPlay:true,delayTime:300});
</script><?php include template('common/footer'); ?>