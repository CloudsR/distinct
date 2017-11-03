<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$form_id = isset($_GET['form_id']) ? intval($_GET['form_id']) : 0;
cxpform_form_subnav($form_id, 'charts');
$forminfo = cxpform_form_info($form_id);

echo '<script src="http://s1.bdstatic.com/r/www/cache/ecom/esl/1-6-10/esl.js" charset="utf-8"></script>';
showtableheader();
showtitle(lang('plugin/cxpform', 'viewchart'));

// 默认是今天

$st = isset($_GET['st']) ? addslashes(trim($_GET['st'])) : '';
$et = isset($_GET['et']) ? addslashes(trim($_GET['et'])) : '';
$t = isset($_GET['t']) ? addslashes(trim($_GET['t'])) : '';


if($st == ''){
	$st = dgmdate(TIMESTAMP, 'Y-m-d 00:00:00');
}
if($et == ''){
	$et = dgmdate(TIMESTAMP, 'Y-m-d 23:59:59');
}


if($t == ''){
	$t = 1;
}

$searchform = '
	<a href="' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(dgmdate(TIMESTAMP, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(TIMESTAMP, 'Y-m-d 23:59:59')) . '&t=1" ' . ($t=='1' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'today') . '</a> | 
	
	<a href="' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(dgmdate(TIMESTAMP - 86400 * 1, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(TIMESTAMP - 86400 * 1, 'Y-m-d 23:59:59')) . '&t=2" ' . ($t=='2' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'yesterday') . '</a> | 
	
	<a href="' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(dgmdate(TIMESTAMP - 86400 * 6, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(TIMESTAMP, 'Y-m-d 23:59:59')) . '&t=3" ' . ($t=='3' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'last7days') . '</a> | 
	
	<a href="' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(dgmdate(TIMESTAMP - 86400 * 29, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(TIMESTAMP, 'Y-m-d 23:59:59')) . '&t=4" ' . ($t=='4' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'last30days') . '</a> |
	
	<a href="' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(date('Y-m-01 00:00:00')) . '&et=' . urlencode(date('Y-m-' . cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) . ' 23:59:59')) . '&t=5" ' . ($t=='5' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'this_month') . '</a> ';
	
	switch($t){
		case '3':
			$searchform .= '<input type="button" value="' . lang('plugin/cxpform', '7daysbefore') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(dgmdate(dmktime($st) - 86400 * 7, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) - 86400 * 7, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> 
	
	<input type="button" value="' . lang('plugin/cxpform', '7daysafter') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(dgmdate(dmktime($st) + 86400 * 7, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) + 86400 * 7, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> ';
			break;
		case '4':
			// 前30天
			
			$searchform .= '<input type="button" value="' . lang('plugin/cxpform', '30daysbefore') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(dgmdate(dmktime($st) - 86400 * 30, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) - 86400 * 30, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> 
	
	<input type="button" value="' . lang('plugin/cxpform', '30daysafter') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(dgmdate(dmktime($st) + 86400 * 30, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) + 86400 * 30, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> ';
			break;
		case '5':
			// 上一个月
			$thismonth = date('m', dmktime($st));
			$thisyear = date('Y', dmktime($st));
			if($thismonth == 1) {
				$lastmonth = 12;
				$lastyear = $thisyear - 1;
			} else {
				$lastmonth = $thismonth - 1;
				$lastyear = $thisyear;
			}
			if($thismonth == 12){
				$nextmonth = 1;
				$nextyear = $thisyear + 1;
			}else{
				$nextmonth = $thismonth + 1;
				$nextyear = $thisyear;
			}
			$last_month_m = $lastmonth;
			$last_month_y = $lastyear;
			$next_month_m = $nextmonth;
			$next_month_y = $nextyear;
			
			$searchform .= '<input type="button" value="' . lang('plugin/cxpform', 'last_month') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(date($last_month_y . '-' . $last_month_m . '-01 00:00:00')) . '&et=' . urlencode(date($last_month_y . '-' . $last_month_m . '-' . cal_days_in_month(CAL_GREGORIAN, $last_month_m, $last_month_y) . ' 23:59:59')) . '&t=' . $t . '\';" /> 
	
	<input type="button" value="' . lang('plugin/cxpform', 'next_month') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(date($next_month_y . '-' . $next_month_m . '-01 00:00:00')) . '&et=' . urlencode(date($next_month_y . '-' . $next_month_m . '-' . cal_days_in_month(CAL_GREGORIAN, $next_month_m, $next_month_y) . ' 23:59:59')) . '&t=' . $t . '\';" /> ';
			break;
		case '6':
			break;
		case '1':
		case '2':
		default:
			$searchform .= '<input type="button" value="' . lang('plugin/cxpform', 'one_day') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(dgmdate(dmktime($st) - 86400 * 1, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) - 86400 * 1, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> 
	
	<input type="button" value="' . lang('plugin/cxpform', 'next_day') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=form&op=charts&form_id=' . $form_id . '&st=' . urlencode(dgmdate(dmktime($st) + 86400 * 1, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) + 86400 * 1, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> ';
			break;
	}
	
	
	
	$searchform .= '<input type="checkbox" name="t" value="6" ' . ($t == '6' ? 'checked="checked"' : '') . ' />' . lang('plugin/cxpform', 'custime') .lang('plugin/cxpform', 'maohao') .'<input type="text" class="Wdate txt" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" name="st" value="' . $st . '" /> <input type="text" class="Wdate txt" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" name="et" value="' . $et . '" />';
	showformheader(XF_FORM_URL . 'pmod=form&op=charts&form_id=' . $form_id, 'formsubmit');
	showsubmit('formsubmit', lang('plugin/cxpform', 'search'), $searchform, '');
	showformfooter();
	showtablefooter();
// echo '<tr><td>';
?>
	<div id="main" style="min-height:400px"></div>
	<?php 
	
	// 表单数组
	$condstr = " WHERE form_id='" . $form_id . "' and addtime>=UNIX_TIMESTAMP(" . DB::quote($st) . ") and addtime<=UNIX_TIMESTAMP(" . DB::quote($et) . ") ";
	
	$dates = cxpform_prDates($st, $et);
	
	// $results1 = DB::fetch_all("select FROM_UNIXTIME(addtime, '%Y-%m-%d') as d, count(id) as ccount from " . DB::table('cxpform_hits') . $condstr . " group by d");
	$results2 = DB::fetch_all("select FROM_UNIXTIME(addtime, '%Y-%m-%d') as d, count(id) as ccount from " . DB::table('cxpform_contents') . $condstr . " group by d");
	
	// foreach($results1 as $row1){
		// $results1[$row1['d']] = $row1['ccount'];
	// }
	
	foreach($results2 as $row2){
		$results2[$row2['d']] = $row2['ccount'];
	}	
	
	$category_str = '';
	$hits_str = '';
	$submit_str = '';
	
	showtableheader();
	?>
	
		<tr class="header">
			<th><?php echo cxpform_lang('field12');?></th>
			
			<th><?php echo cxpform_lang('submit_count');?></th>
		</tr>
		
		<?php 
		foreach($dates as $date){
		?>
		<tr>
			<td><?php echo $date;?></td>
			
			<td><?php echo (array_key_exists($date, $results2) ? $results2[$date] : 0)?></td>
		</tr>
		<?php 
		}
	
	showtablefooter();
	
	foreach($dates as $date){
		$category_str .= "'". $date . "',";
		// if(array_key_exists($date, $results1)){
			// $hits_str .= $results1[$date] . ',';
		// }else{
			// $hits_str .= '0,';
		// }
		if(array_key_exists($date, $results2)){
			$submit_str .= $results2[$date] . ',';
		}else{
			$submit_str .= '0,';
		}
	}
	$category_str = rtrim($category_str, ',');
	// $hits_str = rtrim($hits_str, ',');
	$submit_str = rtrim($submit_str, ',');
	if($category_str != ''){
	?>
	<script type="text/javascript">
		var toolbox = {
						show : true,
						feature : {
							mark : {
								show: true,
								title:{
									mark:'<?php echo lang('plugin/cxpform', 'mark1')?>',
									mark:'<?php echo lang('plugin/cxpform', 'mark2')?>',
									mark:'<?php echo lang('plugin/cxpform', 'mark3')?>'
								}
							},
							dataView : {
								show: true,
								title: '<?php echo lang('plugin/cxpform', 'dataView1')?>',
								readOnly: false,
								lang:['<?php echo lang('plugin/cxpform', 'dataView2')?>','<?php echo lang('plugin/cxpform', 'dataView3')?>','<?php echo lang('plugin/cxpform', 'dataView4')?>']
							},
							magicType : {
								show: true,
								title:{
									line:'<?php echo lang('plugin/cxpform', 'magicType1')?>',
									bar:'<?php echo lang('plugin/cxpform', 'magicType2')?>'
								},
								type: ['line', 'bar']
							},
							restore : {show: true, title:'<?php echo lang('plugin/cxpform', 'restore')?>'},
							saveAsImage : {show: true, title:'<?php echo lang('plugin/cxpform', 'saveAsImage')?>'}
						}
					};
		var toolbox1 = {
						show : true,
						feature : {
							mark : {
								show: true,
								title:{
									mark:'<?php echo lang('plugin/cxpform', 'mark1')?>',
									mark:'<?php echo lang('plugin/cxpform', 'mark2')?>',
									mark:'<?php echo lang('plugin/cxpform', 'mark3')?>'
								}
							},
							dataView : {
								show: true,
								title: '<?php echo lang('plugin/cxpform', 'dataView1')?>',
								readOnly: false,
								lang:['<?php echo lang('plugin/cxpform', 'dataView2')?>','<?php echo lang('plugin/cxpform', 'dataView3')?>','<?php echo lang('plugin/cxpform', 'dataView4')?>']
							},
							restore : {show: true, title:'<?php echo lang('plugin/cxpform', 'restore')?>'},
							saveAsImage : {show: true, title:'<?php echo lang('plugin/cxpform', 'saveAsImage')?>'}
						}
					};					

        require.config({
            paths:{ 
                'echarts' : 'http://echarts.baidu.com/build/echarts',
                'echarts/chart/bar' : 'http://echarts.baidu.com/build/echarts'
            }
        });
        require(
            [
                'echarts',
                'echarts/chart/bar' 
            ],
            function (ec) {
                var myChart = ec.init(document.getElementById('main')); 
                
                option = {
					title : {
						text: '<?php echo lang('plugin/cxpform', 'form') . ' ' . $forminfo['title'] . ' ';?>',
						subtext: '',
						x:'center'
					},
					tooltip : {
						trigger: 'axis'
					},
					legend: {
						padding:10,
						x:'left',
						data:['<?php echo lang('plugin/cxpform', 'submit_count')?>']
					},
					grid:{
						x:100
					},
					toolbox: toolbox,
					calculable : true,
					xAxis : [
						{
							type : 'category',
							// boundaryGap : false,
							data : [<?php echo $category_str;?>]
						}
					],
					yAxis : [
						{
							type : 'value'
						}
					],
					series : [
						{
							name:'<?php echo lang('plugin/cxpform', 'submit_count')?>',
							type:'line',
							data:[<?php echo $submit_str;?>]
						}
					]
				};
                    
        
               
                myChart.setOption(option); 
            }
        );
    </script>		
	<?php 
	}
	
	showtableheader();
	// 3 4  13的报表
	$fields = cxpform_field_list($form_id);
	foreach($fields as $field){
		if(!in_array($field['field_type'], array('3', '4', '13'))) continue;
		// 选项
		$field_options = cxpform_field_option_list($field['id']);
		// 字段选项
		$field_option_value = cxpform_field_option_value($field['id'], $form_id, $st, $et);
		$field_option_value1 = $field_option_value;
		//if(count($field_option_value1) > 1){
		//	unset($field_option_value1[0]);
		//}
		$total1 = cxpform_field_option_total($field['id'], $form_id, $st, $et);
		$category_str = '';
		$option_num = '';
		foreach($field_options as $option){
			$category_str .= "'" . $option['option_name'] . "',";
			// option_id = 10 的选项个数
			$option_num .= '{value:' . intval($field_option_value[$option['id']]) . ", name:'" . $option['option_name'] . "'},";
		}
		if($field['show_other']){
			$category_str .= "'" . cxpform_lang('other') . "',";
			$option_num .= "{value:" . intval($field_option_value[0]) . ", name:'" . cxpform_lang('other') . "'},";
		}
		$category_str = rtrim($category_str, ',');
		$option_num = rtrim($option_num, ',');
		if($category_str != ''){
		
		showtitle(cxpform_lang('field') . ':' . $field['field_label'] . ' ' . cxpform_lang('chart_title'));
	?>
	<tr><td valign="top">
	
		<table style="width:100%;">
			<tr>
				<th colspan="3"><?php echo cxpform_lang('total_submit_count');?>:<?php echo $total1;?></th>
			</tr>
			<tr class="header">
				<th><?php echo cxpform_lang('field_option_name');?></th>
				<th><?php echo cxpform_lang('submit_count');?></th>
				<th><?php echo cxpform_lang('percentage');?></th>
			</tr>
			
			<?php 
			foreach($field_options as $option){
				$field_option_value1 = intval($field_option_value[$option['id']]);
			?>
			<tr>
				<td><?php echo $option['option_name'];?></td>
				<td><?php echo $field_option_value1;?></td>
				<td><?php echo number_format($field_option_value1 / $total1 * 100, 2);?> %</td>
			</tr>
			<?php 
			}
			if($field['show_other']){
			?>
			<tr>
				<td><?php echo cxpform_lang('other');?></td>
				<td><?php echo intval($field_option_value[0]);?></td>
				<td><?php echo number_format(intval($field_option_value[0]) / $total1 *100, 2);?> %</td>
			</tr>
			<?php 
			}
			?>
			
		</table>	
	
	</td><td><div id="field<?php echo $field['id'];?>" style="height:350px;width:350px;"></div></td></tr>
	<script type="text/javascript">
        require(
            [
                'echarts',
                'echarts/chart/pie' 
            ],
            function (ec) {
                
                var myChart<?php echo $field['id'];?> = ec.init(document.getElementById('field<?php echo $field['id'];?>')); 
                
                option<?php echo $field['id'];?> = {
					title : {
						text: '',
						subtext: '',
						x:'center'
					},
					tooltip : {
						trigger: 'item',
						formatter: "{a} <br/>{b} : {c} ({d}%)"
					},
					legend: {
						data:[<?php echo $category_str;?>],
						orient : 'vertical',
						x:'left'
					},
					toolbox: toolbox1,
					calculable : true,
					series : [
						{
							name:'<?php echo lang('plugin/cxpform', 'choose_num');?>',
							type:'pie',
							radius : '55%',
							center:['50%', '60%'],
							data:[<?php echo $option_num;?>]
						}
					]
				};
        
                
                myChart<?php echo $field['id'];?>.setOption(option<?php echo $field['id'];?>); 
            }
        );
	</script>
	<?php 
	}
	}
	?>
	
<?php 		
// echo '</td></tr>';
		
// showtablefooter();
?>