<?php
class plugin_k_baidushare {

    function plugin_k_baidushare() {
        global $_G;
        $this->script = $_G['cache']['plugin']['k_baidushare']['script'];
		$this->position = $_G['cache']['plugin']['k_baidushare']['position'];
		$this->content = $_G['cache']['plugin']['k_baidushare']['content'];
    }
	
	function is_ie() {
		$str=preg_match('/MSIE ([0-9]\.[0-9])/',$_SERVER['HTTP_USER_AGENT'],$matches);
		if($str == 0){
			return 0;
		}else{
			return floatval($matches[1]);
		}
	}
	
	function is_firefox() {
		$str=preg_match('/Firefox/',$_SERVER['HTTP_USER_AGENT'],$matches);
		if($str == 0){
			return false;
		}else{
			return true;
		}
	}

	function viewthread_postheader() {
		if ($this->position == 1) {
			$v = $this->is_ie();
			if($v <= 6 && $v != 0){
				return array('<div class="y" style="position:relative;top:-16px;">'.$this->script.'</div>');
			}elseif ($v == 7){
				return array('<div class="y" style="position:relative;top:-20px;">'.$this->script.'</div>');
			}elseif ($this->is_firefox()){
				return array('<div class="y">'.$this->script.'</div>');
			}else{
				return array('<div class="y" style="position:relative;top:-6px;">'.$this->script.'</div>');
			}
		}else{
			return array();
		}
	}
	
	function viewthread_postfooter(){
		if($this->position == 2) {
			$v = $this->is_ie();
			if ($v <= 6 && $v != 0) {
				return array('<div class="z" style="line-height:14px;margin-top:6px;">'.$this->script.'</div>');
			} else if ($v == 7) {
				return array('<div class="z" style="line-height:14px;margin-top:6px;">'.$this->script.'</div>');
			} else if ($this->is_firefox()) {
				return array('<div class="y" style="line-height:14px;margin-top:6px;">'.$this->script.'</div>');
			} else {
				return array('<div class="y" style="line-height:14px;margin-top:6px;position:relative;">'.$this->script.'</div>');
			}
		}else {
			return array();
		}
	}
	
	function viewthread_posttop() {
		if($this->position == 3){
			return array('<div class="z" style="padding-top:5px;margin-bottom:25px;width:100%">'.$this->script.'</div>');
		}else{
			return array();
		}
	}
	
	function viewthread_postbottom() {
		if($this->position == 4){
			return array('<div style="margin-bottom:-10px; overflow:hidden;">'.$this->script.'</div>');
		}else{
			return array('');
		}
	}
	
	function viewthread_endline(){
		if($this->content == 2){
			$v = "<script type='text/javascript'>var arrAll=document.getElementsByTagName('*');for (var i=0;i<arrAll.length;i++){if(arrAll[i].id.substring(0,11)=='postmessage'){var data=arrAll[i].innerHTML.replace(/<[^>].*?>/g,\"\").replace(/\\n|\\r|\\t/g,\" \");var baidushare=document.getElementById('bdshare');baidushare.setAttribute('data','{\"text\":data}');break;}}</script>";
			return array('<div>' . $v  . '</div>');
		}
		else{
			return array();
		}
	}
	
	function viewthread_useraction(){
		return "";
	}
}

class plugin_k_baidushare_group extends plugin_k_baidushare {

}

class plugin_k_baidushare_forum extends plugin_k_baidushare {
}

?>