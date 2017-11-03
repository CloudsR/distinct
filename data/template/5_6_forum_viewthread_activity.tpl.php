<?php if(!defined('IN_DISCUZ')) exit('Access Denied'); ?>
<script type="text/javascript">
function checkform(theform) {
if (theform.message.value.length > 200) {
alert('您的留言超过 200 个字符的限制');
theform.message.focus();
return false;
}
return true;
}
</script>

<div class="act pbm cl">
<div class="spvimg">
<?php if($activity['thumb']) { ?><a href="javascript:;"><img src="<?php echo $activity['thumb'];?>" onclick="zoom(this, '<?php echo $activity['attachurl'];?>')"/></a><?php } else { ?><img src="<?php echo IMGDIR;?>/nophoto.gif" width="300" height="300" /><?php } ?>
</div>
<div class="spi" style="position: relative;">
<dl>
<dt>活动类型:</dt>
<dd style="color: #bd2f2f;"><?php echo $activity['class'];?></dd>
<dt>开始时间:</dt>
<dd>
<?php if($activity['starttimeto']) { ?>
<?php echo $activity['starttimefrom'];?> 至 <?php echo $activity['starttimeto'];?> 商定
<?php } else { ?>
<?php echo $activity['starttimefrom'];?>
<?php } ?>
</dd>
<dt>活动地点:</dt>
<dd><?php echo $activity['place'];?></dd>
<dt>性别:</dt>
<dd>
<?php if($activity['gender'] == 1) { ?>
男
<?php } elseif($activity['gender'] == 2) { ?>
女
<?php } else { ?>
 不限
<?php } ?>
</dd>
<?php if($activity['cost']) { ?>
<dt>每人花销:</dt>
<dd><?php echo $activity['cost'];?> 元</dd>
<?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['viewthread_activity_extra1'])) echo $_G['setting']['pluginhooks']['viewthread_activity_extra1'];?>
</dl>
<?php if(!$_G['forum_thread']['is_archived']) { ?>
<dl class="nums">
<dt style="padding-top: 2px;">已报名人数:</dt>
<dd>
<em style="font-size: 14px;"><?php echo $allapplynum;?></em> 人
<?php if($post['invisible'] == 0 && ($_G['forum_thread']['authorid'] == $_G['uid'] || (in_array($_G['group']['radminid'], array(1, 2)) && $_G['group']['alloweditactivity']) || ( $_G['group']['radminid'] == 3 && $_G['forum']['ismoderator'] && $_G['group']['alloweditactivity']))) { ?>
<span class="pipe">|</span>
<span class="xi2"><a href="misc.php?mod=invite&amp;action=thread&amp;id=<?php echo $_G['tid'];?>&amp;activity=1" onclick="showWindow('invite', this.href, 'get', 0);">邀请</a></span> &nbsp;
<span class="xi2"><a href="forum.php?mod=misc&amp;action=activityapplylist&amp;tid=<?php echo $_G['tid'];?>&amp;pid=<?php echo $post['pid'];?><?php if($_GET['from']) { ?>&amp;from=<?php echo $_GET['from'];?><?php } ?>" onclick="showWindow('activity', this.href, 'get', 0)" title="管理">管理</a> &nbsp; <a href="forum.php?mod=misc&amp;action=activityexport&amp;tid=<?php echo $_G['tid'];?>" title="导出">导出</a></span>
<?php } ?>
</dd>
</dl>
<dl>
<?php if($activity['number']) { ?>
<dt>剩余名额:</dt>
<dd>
<?php echo $aboutmembers;?> 人
</dd>
<?php } if($activity['expiration']) { ?>
<dt>报名截止:</dt>
<dd><?php echo $activity['expiration'];?></dd>
<?php } ?>
<?php if(!empty($_G['setting']['pluginhooks']['viewthread_activity_extra2'])) echo $_G['setting']['pluginhooks']['viewthread_activity_extra2'];?>
<dd style="margin-left: 0; position: absolute; left: 30px; bottom: 30px;">
<?php if($post['invisible'] == 0) { if($applied && $isverified < 2) { ?>
<p class="xg1 xs1"><?php if(!$isverified) { ?>您的加入申请已发出，请等待发起者审批<?php } else { ?>您已经参加了此活动<?php } ?></p>
<?php if(!$activityclose) { ?><p><button class="pn vm" type="submit" value="true" name="applylistsubmit" onclick="showDialog($('activityjoincancel').innerHTML, 'info', '取消报名')" style="background: #f63756 !important;"><span>取消报名</span></button></p><?php } } elseif(!$activityclose) { ?>
<p class="pns">
<?php if($isverified != 2) { if(!$activity['number'] || $aboutmembers > 0) { ?><button class="pn" value="true" name="ijoin" onclick="<?php if($_G['uid']) { ?>showDialog($('activityjoin').innerHTML, 'info', '我要参加')<?php } else { ?>showWindow('login', 'member.php?mod=logging&action=login&guestmessage=yes')<?php } ?>" style="background: #f63756 !important;"><span>我要参加</span></button><?php } } else { ?>
<button class="pn" value="true" name="ijoin" onclick="showDialog($('activityjoin').innerHTML, 'info', '完善资料')" style="background: #f63756 !important;"><span>完善资料</span></button>
<?php } ?>
</p>
<?php } } ?>
</dd>
</dl>
<?php } ?>
</div>
</div>

<table cellspacing="0" cellpadding="0"><tr><td class="t_f" id="postmessage_<?php echo $post['pid'];?>"><?php echo $post['message'];?></td></tr></table>


<?php if($_G['uid'] && !$activityclose && (!$applied || $isverified == 2)) { ?>
<div id="activityjoin" style="display:none">
<?php if($_G['forum']['status'] == 3 && helper_access::check_module('group') && $isgroupuser != 'isgroupuser') { ?>
<div class="c">
<p>您还不是本 <?php echo $_G['setting']['navs']['3']['navname'];?> 的成员不能参与此活动</p>
<p><a href="forum.php?mod=group&amp;action=join&amp;fid=<?php echo $_G['fid'];?>" class="xi2">点此处马上加入 <?php echo $_G['setting']['navs']['3']['navname'];?></a></p>
</div>
<?php } else { ?>
<form name="activity" id="activity" method="post" autocomplete="off" action="forum.php?mod=misc&amp;action=activityapplies&amp;fid=<?php echo $_G['fid'];?>&amp;tid=<?php echo $_G['tid'];?>&amp;pid=<?php echo $post['pid'];?><?php if($_GET['from']) { ?>&amp;from=<?php echo $_GET['from'];?><?php } ?>" onsubmit="ajaxpost('activity', 'return_activityapplies', 'return_activityapplies', 'onerror');return false;">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<input type="hidden" name="handlekey" value="activityapplies" />
<div class="c">
<?php if($_G['setting']['activitycredit'] && $activity['credit'] && !$applied) { ?><p class="xi1">注意：参加此活动将扣除您 <?php echo $activity['credit'];?> <?php echo $_G['setting']['extcredits'][$_G['setting']['activitycredit']]['title'];?></p><?php } ?>
<div class="actfm">
<table summary="我要参加" cellpadding="0" cellspacing="0" class="actl">
<?php if($activity['cost']) { ?>
<tr>
<th>支付方式</th>
<td>
<p class="mbn"><label for="payment_0"><input class="pr" type="radio" value="0" name="payment" id="payment_0" checked="checked" />承担自己应付的花销</label></p>
<p><label for="payment_1"><input type="radio" name="payment" id="payment_1" class="pr" value="1" />支付</label> <input name="payvalue"class="px pxs vm" size="3" onfocus="$('payment_1').checked = true;" /> 元</p>
</td>
</tr>
<?php } if(!empty($activity['ufield']['userfield'])) { if(is_array($activity['ufield']['userfield'])) foreach($activity['ufield']['userfield'] as $fieldid) { if($settings[$fieldid]['available']) { ?>
<tr>
<th id="th_<?php echo $fieldid;?>"><strong class="rq y">*</strong><?php echo $settings[$fieldid]['title'];?></th>
<td id="td_<?php echo $fieldid;?>">
<?php if($settings[$fieldid]['formtype'] != 'file') { ?>
<?php echo $htmls[$fieldid];?>
<?php } else { ?>
 <input id="activitypic_<?php echo $fieldid;?>" type="text" tabindex="1" value="" class="px" name="<?php echo $fieldid;?>" onblur="if(!this.value.match(/^https?:\/\/.+\/.+\.(jpg|png|gif|jpeg|bmp)$/i)){$('showerror_<?php echo $fieldid;?>').innerHTML='图片地址错误';}else{$('showerror_<?php echo $fieldid;?>').innerHTML='&nbsp;';}"><div id="showerror_<?php echo $fieldid;?>" class="rq mtn">请输入图片地址</div>
<?php } ?>
</td>
</tr>
<?php } } } if(!empty($activity['ufield']['extfield'])) { if(is_array($activity['ufield']['extfield'])) foreach($activity['ufield']['extfield'] as $extname) { ?><tr>
<th><?php echo $extname;?></th>
<td><input type="text" name="<?php echo $extname;?>" maxlength="200" class="px" value="<?php if(!empty($ufielddata)) { ?><?php echo $ufielddata['extfield'][$extname];?><?php } ?>" /></td>
</tr>
<?php } } ?>
<tr>
<th>留言</th>
<td><textarea name="message" maxlength="200" cols="38" rows="3" class="pt"><?php echo $applyinfo['message'];?></textarea></td>
</tr>
</table>
</div>
</div>
<div class="o pns">
<?php if($_G['setting']['activitycredit'] && $activity['credit'] && checklowerlimit(array('extcredits'.$_G['setting']['activitycredit'] => '-'.$activity['credit']), $_G['uid'], 1, 0, 1) !== true) { ?>
<p class="xi1"><?php echo $_G['setting']['extcredits'][$_G['setting']['activitycredit']]['title'];?> 不足<?php echo $activity['credit'];?></p>
<?php } else { ?>
<input type="hidden" name="activitysubmit" value="true">
<em class="xi1" id="return_activityapplies"></em>
<button type="submit" class="pn pnc"><span>提交</span></button>
<?php } ?>
</div>
</form>

<script type="text/javascript">
function succeedhandle_activityapplies(locationhref, message) {
showDialog(message, 'right', '', 'location.href="' + locationhref + '"');
}
</script>
<?php } ?>
</div>
<?php } elseif($_G['uid'] && !$activityclose && $applied) { ?>
<div id="activityjoincancel" style="display:none">
<form name="activity" method="post" autocomplete="off" action="forum.php?mod=misc&amp;action=activityapplies&amp;fid=<?php echo $_G['fid'];?>&amp;tid=<?php echo $_G['tid'];?>&amp;pid=<?php echo $post['pid'];?><?php if($_GET['from']) { ?>&amp;from=<?php echo $_GET['from'];?><?php } ?>">
<input type="hidden" name="formhash" value="<?php echo FORMHASH;?>" />
<div class="c">
<table summary="我要参加" cellpadding="0" cellspacing="0" class="actl">
<tr>
<th>留言</th>
<td><input type="text" name="message" maxlength="200" class="px" value="" /></td>
</tr>
</table>
</div>
<div class="o pns"><button type="submit" name="activitycancel" class="pn pnc" value="true"><span>提交</span></button></div>
</form>
</div>
<?php } if($applylist) { ?>
<div class="ptm pbm xs1" id="applylist_<?php echo $_G['tid'];?>">
<h2>已通过 (<?php echo $applynumbers;?> 人)</h2>
<table class="dt">
<tr>
<th width="140">&nbsp;</th>
<th>留言</th>
<?php if($activity['cost']) { ?>
<th width="100">每人花销</th>
<?php } ?>
<th width="110">申请时间</th>
</tr><?php if(is_array($applylist)) foreach($applylist as $apply) { ?><tr>
<td>
<a target="_blank" href="home.php?mod=space&amp;uid=<?php echo $apply['uid'];?>" class="ratl vm"><?php echo avatar($apply['uid'], 'small'); ?></a>
<a target="_blank" href="home.php?mod=space&amp;uid=<?php echo $apply['uid'];?>"><?php echo $apply['username'];?></a>
</td>
<td><?php if($apply['message']) { ?><p><?php echo $apply['message'];?></p><?php } ?></td>
<?php if($activity['cost']) { ?>
<td><?php if($apply['payment'] >= 0) { ?><?php echo $apply['payment'];?> 元<?php } else { ?>自付<?php } ?></td>
<?php } ?>
<td><?php echo $apply['dateline'];?></td>
</tr>
<?php } ?>
</table>
<?php if($applynumbers > $_G['setting']['activitypp']) { ?>
<br \>
<div class="pgs mbm cl">
<div class="pg">
<a onclick="ajaxget('forum.php?mod=misc&amp;action=getactivityapplylist&amp;tid=<?php echo $_G['tid'];?>&amp;page=2', 'applylist_<?php echo $_G['tid'];?>')" class="nxt" href="javascript:;">下一页</a>
</div>
</div>
<?php } ?>
</div>
<?php } if($applylistverified) { ?>
<div class="ptm pbm xs1">
<h2>暂未通过 (<?php echo $noverifiednum;?> 人)</h2>
<table class="dt">
<tr>
<th width="140">&nbsp;</th>
<th>留言</th>
<?php if($activity['cost']) { ?>
<th width="100">每人花销</th>
<?php } ?>
<th width="110">申请时间</th>
</tr><?php if(is_array($applylistverified)) foreach($applylistverified as $apply) { ?><tr>
<td>
<a target="_blank" href="home.php?mod=space&amp;uid=<?php echo $apply['uid'];?>" class="ratl vm"><?php echo avatar($apply['uid'], 'small'); ?></a>
<a target="_blank" href="home.php?mod=space&amp;uid=<?php echo $apply['uid'];?>"><?php echo $apply['username'];?></a>
</td>
<td><?php if($_G['forum_thread']['authorid'] == $_G['uid'] && $apply['message']) { ?><?php echo $apply['message'];?><?php } ?></td>
<?php if($activity['cost']) { ?>
<td><?php if($apply['payment'] >= 0) { ?><?php echo $apply['payment'];?> 元<?php } else { ?>自付<?php } ?></td>
<?php } ?>
<td><?php echo $apply['dateline'];?></td>
</tr>
<?php } ?>
</table>
</div>
<?php } ?>
