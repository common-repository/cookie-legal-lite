<?php
	
	class SettingsCookieLegal{
		
		public $arrayOptions;
				
		public function inizializeOptions(){
			$default_options = array(
				'cookieLegal_actived'=>1
			);
			add_option('CookieLegal',$default_options);
		}
		
		public function controlOptions(){
			//echo '<p>Sono Presenti '.count($this->arrayOptions).'</p>';
		}
		
		public function getScript($type){
			include_once("cookie_list.php");
			$cl = new CookieList();
			$arraySrc;
			if($type == "frame"){
				$list_frame = apply_filters('cookieLegal_frame', $cookieLegal_iframe);
				$arraySrc = array_merge($cl->cookieLegal_iframe);
			}elseif($type == "script"){
				$list_script = apply_filters('cookieLegal_script', $cookieLegal_scripts);
				$arraySrc = array_merge($cl->cookieLegal_scripts);
			}else{
				$list_script = apply_filters('cookieLegal_all', $all_script);
				$arraySrc = array_merge($cl->all_script);
			}
			return $arraySrc;
		}
		
		public function getSrc($type){
			$arraySrc;
			if($type == "all"){
				$arraySrc = array_merge($this->getScript("all"));
			}
			return $arraySrc;
		}
	}

?>