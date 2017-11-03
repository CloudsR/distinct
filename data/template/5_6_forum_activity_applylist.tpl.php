<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); hookscriptoutput('activity_applylist');?><?php include template('common/header'); if(empty($_GET['infloat'])) { ?>
<div id="pt" class="bm cl">
<div class="z"><a href="./" class="nvhm" title="首页"><?php echo $_G['setting']['bbname'];?></a> <em>&rsaquo;</em> <?php echo $navigation;?></div>
</div>
<div id="ct" class="wp cl">
<div class="mn">
<div class="bm bw0">
<?php } ?>

<form id="applylistform" method="post" autocomplete="off" action="forum.php?mod=misc&amp;action=activityapplylist&amp;tid=<?php echo $_G['tid'];?>&amp;applylistsubmit=yes&amp;infloat=yes<?php if(!empty($_GET['from'])) { ?>&amp;from=<?php echo $_GET['from'];?><?php } ?>"<?php if(!empty($_GET['infloat']) && empty($_GET['from'])) { ?> onsubmit="ajaxpost('applylistform', 'return_<?php echo $_GET['handlekey'];?>', 'return_<?php echo $_GET['handlekey'];?>', 'onerror');return false;"<?php } ?> style="width: 590px;">
<div class="f_c">
<h3 class="flb">
<em id="return_<?php echo $_GET['handlekey'];?>"><?php if($isactivitymaster) { ?>活动报名者管理<?php } else { ?>活动报名者<?php } ?></em>
<span>
<?php if(!empty($_GET['infloat'])) { ?><a href="javascript:;" class="flbc" onclick="hideWindow('<?php echo $_GET['handlekey'];?>')" title="关闭">关闭</a><?php } ?>
</span>
</h3>
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="operation" value="" />
<?php if(!empty($_GET['infloat'])) { ?><input type="hidden" name="handlekey" value="<?php echo $_GET['handlekey'];?>" /><?php } ?>
<div class="c floatwrap">
<table class="list" cellspacing="0" cellpadding="0" style="table-layout: fixed;">
<thead>
<tr>
<?php if($isactivitymaster) { ?><th width="25">&nbsp;</th><?php } ?>
<th width="105">申请者</th>
<th>留言</th>
<th width="70">扩展项目</th>
<?php if($activity['cost']) { ?>
<th width="70">每人花销</th>
<?php } ?>
<th width="70">申请时间</th>
<?php if($isactivitymaster) { ?><th width="70">状态</th><?php } ?>
</tr>
</thead><?php if(is_array($applylist)) foreach($applylist as $apply) { ?><tr>
<?php if($isactivitymaster) { ?>
<td>
<?php if($apply['uid'] != $_G['uid']) { ?>
<input type="checkbox" name="applyidarray[]" class="pc" value="<?php echo $apply['applyid'];?>" />
<?php } else { ?>
<input type="checkbox" class="pc" disabled="disabled" />
<?php } ?>
</td>
<?php } ?>
<td>
<a target="_blank" href="home.php?mod=space&amp;uid=<?php echo $apply['uid'];?>"><?php echo $apply['username'];?></a>
<?php if($apply['uid'] != $_G['uid']) { ?>
<a href="home.php?mod=spacecp&amp;ac=pm&amp;op=showmsg&amp;handlekey=showmsg_<?php echo $apply['uid'];?>&amp;touid=<?php echo $apply['uid'];?>&amp;pmid=0&amp;daterange=2" onclick="hideMenu('aplayuid<?php echo $apply['uid'];?>_menu');showWindow('sendpm', this.href)" title="发短消息"><img src="<?php echo IMGDIR;?>/pmto.gif" alt="发短消息" class="vm" /></a>
<?php } ?>
</td>
<td><?php if($apply['message']) { ?><?php echo $apply['message'];?><?php } ?></td>
<td>
<?php if($apply['ufielddata']) { ?>
<div><a href="javascript:;" id="actl_<?php echo $apply['uid'];?>" class="showmenu" onmouseover="showMenu({'ctrlid':this.id, 'pos':'34!'});">查看</a></div>
<div id="actl_<?php echo $apply['uid'];?>_menu" class="p_pop p_opt actl_pop" style="display:none;"><ul><?php echo $apply['ufielddata'];?></ul></div>
<?php } else { ?>
无任何信息
<?php } ?>
</td>
<?php if($activity['cost']) { ?>
<td><?php if($apply['payment'] >= 0) { ?><?php echo $apply['payment'];?> 元<?php } else { ?>自付<?php } ?></td>
<?php } ?>
<td><?php echo $apply['dateline'];?></td>
<?php if($isactivitymaster) { ?>
<td><?php if($apply['verified'] == 1) { ?>
<img src="<?php echo IMGDIR;?>/data_valid.gif" class="vm" alt="允许参加" /> 允许参加
<?php } elseif($apply['verified'] == 2) { ?>
等待完善
<?php } else { ?>
尚未审核
<?php } ?>
</td>
<?php } ?>
</tr>
<?php } ?>
</table>
</div>
</div>
<?php if($isactivitymaster) { ?>
<div class="o pns">
<label<?php if(!empty($_GET['infloat'])) { ?> class="z"<?php } ?>><input class="pc" type="checkbox" name="chkall" onclick="checkall(this.form, 'applyid')" />全选 </label>
<label>附言: <input name="reason" class="px vm" size="25" /> </label>
<button class="pn pnc vm" type="submit" value="true" name="applylistsubmit"><span>批准</span></button>
<button class="pn vm" type="submit" value="true" name="applylistsubmit" onclick="$('applylistform').operation.value='replenish';"><span>需完善</span></button>
<button class="pn vm" type="submit" value="true" name="applylistsubmit" onclick="$('applylistform').operation.value='notification';"><span>发通知</span></button>
<button class="pn vm" type="submit" value="true" name="applylistsubmit" onclick="$('applylistform').operation.value='delete';"><span>拒绝</span></button>
</div>
<?php } ?>
</form>

<?php if(!empty($_GET['infloat'])) { ?>
<script type="text/javascript" reload="1">
function succeedhandle_<?php echo $_GET['handlekey'];?>(locationhref) {
ajaxget('forum.php?mod=viewthread&tid=<?php echo $_G['tid'];?>&viewpid=<?php echo $_GET['pid'];?>', 'post_<?php echo $_GET['pid'];?>');
hideWindow('<?php echo $_GET['handlekey'];?>');
}
</script>
<?php } if(empty($_GET['infloat'])) { ?>
</div>
</div>
</div>
<?php } include template('common/footer'); ?>