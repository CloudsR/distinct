<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('list_12');?><?php include template('common/header'); $list = array();?><?php $wheresql = category_get_wheresql($cat);?><?php $list = category_get_list($cat, $wheresql, $page);?><link rel="stylesheet" type="text/css" href="template/elec_201701_hxzmt/style/css/event.css" />
<style type="text/css">
.clearfix:after {content: "\200B";display: block;height: 0;visibility: hidden;clear: both;}
.clearfix {*zoom: 1;}
.res-warp{ margin-top: 30px; font-size: 14px; }
.res-top{}
.res-t-item{ border: 1px solid #ccc; }
.res-t-item:last-child{border-top:none;}
.res-t-item label{ display: inline-block; vertical-align: top; padding: 17px; background: #66cc99; color: #fff;  }
.res-t-item ul{ display: inline-block;padding: 17px; vertical-align: top; }
.res-t-item ul li{ float: left;  padding: 0 10px; }
.res-list{ margin-top: 30px; margin-bottom: 30px; }
.res-list li{ width: 270px; float: left; margin: 15px; }
.res-list li:nth-child(4n+1){ margin-left: 0px; }
.res-list li:nth-child(4n){ margin-right: 0px; }
.res-list li .res-l-img{ display: block; width: 270px; height: 180px; }
.res-list li .res-l-img img{ width: 270px; height: 180px; }
.res-l-bottom{ padding-top: 10px; }
.res-l-bottom h3{  float: left; width: 150px; word-break: break-all; word-wrap: break-word; text-overflow: ellipsis;  white-space: nowrap; overflow: hidden;  }
.res-l-bottom .tit-rig{ float: right; position: relative; }
.res-l-bottom i{ display: block; position: absolute; width: 100px; height: 2px;}
.res-l-bottom i.r-line1{ background: #eaeaea; top:10px; width: 100%; left: 0; z-index: 1}
.res-l-bottom i.r-line2{ background: #66cc99; top:10px; width:50%; left: 0; z-index: 2}
.res-l-bottom span{ margin-top: 20px;  display: block; font-size: 13px; color:#66cc99 }
.res-bb p{ float: left; width: 150px; margin-top: 10px; }
.res-l-bottom .res-btn{float: right; display: block; padding:5px 18px;  font-size: 13px; border:1px solid #66cc99; border-radius:15px; margin-top: 15px; }
</style>
<script src="template/elec_201701_hxzmt/style/js/jquery.superslide.js" type="text/javascript" type="text/javascript"></script>
</div><?php echo adshow("text/wp a_t");?><style id="diy_style" type="text/css">
</style>
<div class="wp" style="min-height: 300px;"> 
  <!--[diy=diy_top]--><div id="diy_top" class="area">
  	
  	<div class="res-warp">
  		<!--top-->
  		<div class="res-top">
  			<div class="res-t-item">
  				<label>区域：</label>
  				<ul>
  					<li><a href="javascript:;">龙华区</a></li>
  					<li><a href="javascript:;">观澜区</a></li>
  				</ul>
  			</div>
  			<div class="res-t-item">
  				<label>场地：</label>
  				<ul>
  					<li><a href="javascript:;">综合球场</a></li>
  					<li><a href="javascript:;">篮球场</a></li>
  					<li><a href="javascript:;">足球场</a></li>
  					<li><a href="javascript:;">综合运动场</a></li>
  					<li><a href="javascript:;">员工活动中心</a></li>
  				</ul>
  			</div>
  		</div>
  		<!--top end-->
  		<!--list start-->
  		<ul class="res-list">
  			<li>
  				<a href="javascript:;">
  					<span class="res-l-img">
  						<img src="/res-img01.png" />
  					</span>
  					<div class="res-l-bottom">
  						<div class="res-title clearfix">
  							<h3>L区综合楼</h3>
  							<div class="tit-rig">
  								<i class="r-line1"></i>
  								<i class="r-line2"></i>
  								<span>未来7天已预订45%</span>
  							</div>
  						</div>
  						<div class="res-bb clearfix">
  							<p>龙华区-XXX<br/>篮球/排球/网球</p>
  							<span class="res-btn">立即预定</span>
  						</div>
  					</div>
  				</a>
  			</li>
  			<li>
  				<a href="javascript:;">
  					<span class="res-l-img">
  						<img src="/res-img01.png" />
  					</span>
  					<div class="res-l-bottom">
  						<div class="res-title clearfix">
  							<h3>L区综合楼</h3>
  							<div class="tit-rig">
  								<i class="r-line1"></i>
  								<i class="r-line2"></i>
  								<span>未来7天已预订45%</span>
  							</div>
  						</div>
  						<div class="res-bb clearfix">
  							<p>龙华区-XXX<br/>篮球/排球/网球</p>
  							<span class="res-btn">立即预定</span>
  						</div>
  					</div>
  				</a>
  			</li>
  			<li>
  				<a href="javascript:;">
  					<span class="res-l-img">
  						<img src="/res-img01.png" />
  					</span>
  					<div class="res-l-bottom">
  						<div class="res-title clearfix">
  							<h3>L区综合楼</h3>
  							<div class="tit-rig">
  								<i class="r-line1"></i>
  								<i class="r-line2"></i>
  								<span>未来7天已预订45%</span>
  							</div>
  						</div>
  						<div class="res-bb clearfix">
  							<p>龙华区-XXX<br/>篮球/排球/网球</p>
  							<span class="res-btn">立即预定</span>
  						</div>
  					</div>
  				</a>
  			</li>
  			<li>
  				<a href="javascript:;">
  					<span class="res-l-img">
  						<img src="/res-img01.png" />
  					</span>
  					<div class="res-l-bottom">
  						<div class="res-title clearfix">
  							<h3>L区综合楼</h3>
  							<div class="tit-rig">
  								<i class="r-line1"></i>
  								<i class="r-line2"></i>
  								<span>未来7天已预订45%</span>
  							</div>
  						</div>
  						<div class="res-bb clearfix">
  							<p>龙华区-XXX<br/>篮球/排球/网球</p>
  							<span class="res-btn">立即预定</span>
  						</div>
  					</div>
  				</a>
  			</li>
  			<li>
  				<a href="javascript:;">
  					<span class="res-l-img">
  						<img src="/res-img01.png" />
  					</span>
  					<div class="res-l-bottom">
  						<div class="res-title clearfix">
  							<h3>L区综合楼</h3>
  							<div class="tit-rig">
  								<i class="r-line1"></i>
  								<i class="r-line2"></i>
  								<span>未来7天已预订45%</span>
  							</div>
  						</div>
  						<div class="res-bb clearfix">
  							<p>龙华区-XXX<br/>篮球/排球/网球</p>
  							<span class="res-btn">立即预定</span>
  						</div>
  					</div>
  				</a>
  			</li>
  		</ul>
  		<!--list end-->
  	</div>




  </div><!--[/diy]--> 
  <!--[diy=diy1]--><div id="diy1" class="area"></div><!--[/diy]--> 
</div><?php include template('common/footer'); ?>