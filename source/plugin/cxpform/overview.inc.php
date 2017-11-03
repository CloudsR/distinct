<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
include_once(dirname(__FILE__) . '/include/function.php');
	echo '<script type="text/javascript" src="source/plugin/cxpform/resource/js/jquery.js" language="javascript"></script><script type="text/javascript">var jq = jQuery.noConflict();</script>';
	echo '<script type="text/javascript" src="source/plugin/cxpform/resource/js/My97DatePicker/WdatePicker.js" language="javascript"></script>';
	echo '<link rel="stylesheet" type="text/css" href="source/plugin/cxpform/resource/css/style.css" />';
	
	echo '<script src="http://s1.bdstatic.com/r/www/cache/ecom/esl/1-6-10/esl.js" charset="utf-8"></script>';

	// 表单列表
	$forms = cxpform_form_list();
	
	// 总查看数
	$count1 = DB::result_first("select count(id) as ccount from " . DB::table('cxpform_hits'));
	// 总提交数
	$count2 =DB::result_first("select count(id) from " . DB::table('cxpform_contents'));
	
showtableheader();

echo '<tr><td class="td21">' . lang('plugin/cxpform', 'total_form_count') . '</td><td>' . count($forms) . ' ' . lang('plugin/cxpform', 'unit2') . '</td><td class="td21">' . lang('plugin/cxpform', 'total_hits_count') . '</td><td>' . $count1 . ' ' . lang('plugin/cxpform', 'unit1') . '</td><td class="td21">' . lang('plugin/cxpform', 'total_submit_count') . '</td><td>' . $count2 . ' ' . lang('plugin/cxpform', 'unit1') . '</td></tr>';

// 默认是今天

$st = isset($_GET['st']) ? addslashes(trim($_GET['st'])) : '';
$et = isset($_GET['et']) ? addslashes(trim($_GET['et'])) : '';
$t = isset($_GET['t']) ? addslashes(trim($_GET['t'])) : '';

if($st == ''){
	$st = date('Y-m-d 00:00:00');
}
if($et == ''){
	$et = date('Y-m-d 23:59:59');
}

if($t == ''){
	$t = 1;
}

$searchform = '
	<a href="' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(dgmdate(TIMESTAMP, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(TIMESTAMP, 'Y-m-d 23:59:59')) . '&t=1" ' . ($t=='1' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'today') . '</a> | 
	
	<a href="' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(dgmdate(TIMESTAMP - 86400 * 1, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(TIMESTAMP - 86400 * 1, 'Y-m-d 23:59:59')) . '&t=2" ' . ($t=='2' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'yesterday') . '</a> | 
	
	<a href="' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(dgmdate(TIMESTAMP - 86400 * 6, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(TIMESTAMP, 'Y-m-d 23:59:59')) . '&t=3" ' . ($t=='3' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'last7days') . '</a> | 
	
	<a href="' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(dgmdate(TIMESTAMP - 86400 * 29, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(TIMESTAMP, 'Y-m-d 23:59:59')) . '&t=4" ' . ($t=='4' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'last30days') . '</a> |
	
	<a href="' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(date('Y-m-01 00:00:00')) . '&et=' . urlencode(date('Y-m-' . cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y')) . ' 23:59:59')) . '&t=5" ' . ($t=='5' ? 'class="cur_date"' : '') . '>' . lang('plugin/cxpform', 'this_month') . '</a> ';
	
	switch($t){
		case '3':
			$searchform .= '<input type="button" value="' . lang('plugin/cxpform', '7daysbefore') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(dgmdate(dmktime($st) - 86400 * 7, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) - 86400 * 7, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> 
	
	<input type="button" value="' . lang('plugin/cxpform', '7daysafter') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(dgmdate(dmktime($st) + 86400 * 7, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) + 86400 * 7, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> ';
			break;
		case '4':
			// 前30天
			
			$searchform .= '<input type="button" value="' . lang('plugin/cxpform', '30daysbefore') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(dgmdate(dmktime($st) - 86400 * 30, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) - 86400 * 30, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> 
	
	<input type="button" value="' . lang('plugin/cxpform', '30daysafter') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(dgmdate(dmktime($st) + 86400 * 30, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) + 86400 * 30, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> ';
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
			
			$searchform .= '<input type="button" value="' . lang('plugin/cxpform', 'last_month') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(date($last_month_y . '-' . $last_month_m . '-01 00:00:00')) . '&et=' . urlencode(date($last_month_y . '-' . $last_month_m . '-' . cal_days_in_month(CAL_GREGORIAN, $last_month_m, $last_month_y) . ' 23:59:59')) . '&t=' . $t . '\';" /> 
	
	<input type="button" value="' . lang('plugin/cxpform', 'next_month') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(date($next_month_y . '-' . $next_month_m . '-01 00:00:00')) . '&et=' . urlencode(date($next_month_y . '-' . $next_month_m . '-' . cal_days_in_month(CAL_GREGORIAN, $next_month_m, $next_month_y) . ' 23:59:59')) . '&t=' . $t . '\';" /> ';
			break;
		case '6':
			break;
		case '1':
		case '2':
		default:
			$searchform .= '<input type="button" value="' . lang('plugin/cxpform', 'one_day') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(dgmdate(dmktime($st) - 86400 * 1, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) - 86400 * 1, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> 
	
	<input type="button" value="' . lang('plugin/cxpform', 'next_day') . '" class="btn" onclick="location.href=\'' . XF_PLUGIN_URL . 'pmod=overview&st=' . urlencode(dgmdate(dmktime($st) + 86400 * 1, 'Y-m-d 00:00:00')) . '&et=' . urlencode(dgmdate(dmktime($et) + 86400 * 1, 'Y-m-d 23:59:59')) . '&t=' . $t . '\';" /> ';
			break;
	}
	
	
	
	$searchform .= '<input type="checkbox" name="t" value="6" ' . ($t == '6' ? 'checked="checked"' : '') . ' />' . lang('plugin/cxpform', 'custime') . lang('plugin/cxpform', 'maohao') . '<input type="text" class="Wdate txt" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" name="st" value="' . $st . '" /> <input type="text" class="Wdate txt" onclick="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\'});" name="et" value="' . $et . '" />';
	showformheader(XF_FORM_URL . 'pmod=overview', 'formsubmit');
	showsubmit('formsubmit', lang('plugin/cxpform', 'search'), $searchform, '');
	showformfooter();

echo '<tr><td colspan="15">';
?>	
	<div id="main" style="min-height:900px;"></div>
	<?php 
	
	// 表单数组
	$condstr = '';
	
	$category_str = '';
	$hits_str = '';
	$submit_str = '';
	$st = strtotime($st);
	$et = strtotime($et);
	foreach($forms as $form){
		$category_str .= "'". $form['title'] . "',";
		
		$condstr = " WHERE form_id='" . $form['id'] . "' and addtime>=" . DB::quote($st) . " and addtime<=" . DB::quote($et);
		
		$results1 = DB::fetch_first("select count(id) as ccount from " . DB::table('cxpform_hits') . $condstr . " ");
		$results2 = DB::fetch_first("select count(id) as ccount from " . DB::table('cxpform_contents') . $condstr . " ");
		
		$hits_str .= $results1['ccount'] . ',';
		$submit_str .= $results2['ccount'] . ',';
	}
	$category_str = rtrim($category_str, ',');
	$hits_str = rtrim($hits_str, ',');
	$submit_str = rtrim($submit_str, ',');
	
	if($category_str != ''){
	?>
	<script type="text/javascript">
        // 路径配置
        require.config({
            paths:{ 
                'echarts' : 'http://echarts.baidu.com/build/echarts',
                'echarts/chart/bar' : 'http://echarts.baidu.com/build/echarts'
            }
        });
		        // 使用
        require(
            [
                'echarts',
                'echarts/chart/bar' // 使用柱状图就加载bar模块，按需加载
            ],
            function (ec) {
                // 基于准备好的dom，初始化echarts图表
                var myChart = ec.init(document.getElementById('main')); 
                
                option = {
					title : {
						text: '<?php echo lang('plugin/cxpform', 'overview_chart_title')?>',
						subtext: ''
					},
					tooltip : {
						trigger: 'axis'
					},
					legend: {
						data:['<?php echo lang('plugin/cxpform', 'hits_count')?>', '<?php echo lang('plugin/cxpform', 'submit_count')?>']
					},
					grid:{
						x:300
					},
					toolbox: {
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
					},
					calculable : true,
					xAxis : [
						{
							type : 'value',
							axisLabel:{
								formatter: '{value} <?php echo lang('plugin/cxpform', 'unit1');?>'
							},
							boundaryGap : [0, 0.01]
						}
					],
					yAxis : [
						{
							type : 'category',
							data : [<?php echo $category_str;?>],
							axisLabel : {
								// 'rotate': 45,
								'interval':0
							}
						}
					],
					series : [
						{
							name:'<?php echo lang('plugin/cxpform', 'hits_count')?>',
							type:'bar',
							itemStyle:{
								normal:{
									label:{
										show:true
									}
								}
							},
							data:[<?php echo $hits_str;?>]
						},
						{
							name:'<?php echo lang('plugin/cxpform', 'submit_count')?>',
							type:'bar',
							itemStyle:{
								normal:{
									label:{
										show:true,
										position: 'insideRight'
									}
								}
							},							
							data:[<?php echo $submit_str;?>]
						}
					]
				};
        
                // 为echarts对象加载数据 
                myChart.setOption(option); 
            }
        );
    </script>
<?php }
	echo '</td></tr>';




showtablefooter();
?>