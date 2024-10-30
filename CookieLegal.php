<?php

	/*
	Plugin Name: Cookie Legal Lite
	Plugin URI: http://www.cookielegal.it/product/lite
	Description: Cookie Legal Lite vi permette di legalizzare il vostro sito con le ultime leggi emanate dal Garante della Privacy. 
	Version: 1.1
	Author: Vhander.it FreeLancer - Editrade S.A.S - Studio Legale Avv. Andrea Nappi
	Author URI: http://www.coockielegal.it/site/contact
	 */
	require_once(plugin_dir_path( __FILE__ )."/bin/settings.class.php");
	
	function CL_require_page(){
		$final_page = '';
		$livel = count(ob_get_level());
		for($i = 0; $i < $livel; $i++){
			$final_page = $final_page.ob_get_clean();
		}
		echo apply_filters('final_output', $final_page);
	}
	
	function CL_cercaSrc($valPage, $valCookie) {
		$trovato = false;
		if(!is_array($valCookie)){
			$valCookie = array($valCookie);
		}
		foreach($valCookie as $i=>$src) {
			if( ($pos = strpos($valPage, $src)) !== FALSE){
				$trovato = $src;
			}
		}
		return $trovato;
	}
	
	function CL_remasterized_page($output){
		libxml_use_internal_errors(true);
		$async_scripts = array();
		$delete = array();
		$getSettings = new SettingsCookieLegal();
		$src = $getSettings->getSrc("all");
		$page = new DOMDocument();
		$page->encoding = 'utf-8';
		$page->loadHTML(mb_convert_encoding($output, 'HTML-ENTITIES', 'UTF-8'));
		$array_tags = $page->getElementsByTagName('script');
		foreach($array_tags as $script){
			$srcAttribute =  $script->getAttribute('src');
			if($srcAttribute){
				if(CL_cercaSrc($srcAttribute, $src) !== false ){
					if($script->getAttribute('async')){
						$async_scripts[] = $srcAttribute;
						$delete[] = $script;
						continue;
					}
					$script->setAttribute("type", "text/plain");
					continue;
				}
			}
			if($script->nodeValue){
				if(($key = CL_cercaSrc($script->nodeValue, $src)) !== false ){
					if($key == 'google-analytics.com' ){
                    	if(strpos($script->nodeValue, "'_gat._anonymizeIp'") == false ){
                        	$script->setAttribute("type", "text/plain");
						}
					}else{
						$script->setAttribute("type", "text/plain");
					}
				}
			}
		}
		
		foreach($delete as $el){
			$el->parentNode->removeChild($el);
		}
		
		$array_tags = $page->getElementsByTagName('iframe');
		foreach($array_tags as $frame){
			$srcAttribute =  $frame->getAttribute('src');
			if($srcAttribute){
				if(CL_cercaSrc($srcAttribute, $src) !== false ){
					$frame->removeAttribute('src');
					$frame->setAttribute("data-cookieLegal", $src_iframe);
				}
			}
		}
		if(count($async_scripts) != 0){
			$stringa = json_encode($async_scripts);
			$stringa = 'var Async = ' . $stringa . ';';
			$head = $page->getElementsByTagName('head')->item(0);
			$element = $page->createElement('script', $stringa);
			$head->appendChild($element);
		}
	
		$output = $page->saveHTML();
		libxml_use_internal_errors(false);
		return $output;
	}
	
	function CL_disableCookie(){
		ob_start();
		add_action('shutdown', 'require_page', 0);
        add_filter('final_output', 'CL_remasterized_page');
	}
	
	function CL_inizialize(){
		if(is_feed())return;
		$setSettings = new SettingsCookieLegal();
		$options = get_option("CookieLegal");
		if($options == FALSE){
			$setSettings->inizializeOptions();
		}else{
			$setSettings->arrayOptions = $options;
			$setSettings->controlOptions();
		}
		$options = get_option("CookieLegal");
		if($options['cookieLegal_actived'] == 1){
			if( (!isset($_COOKIE['CookieLegal'])) || ($_COOKIE['CookieLegal'] == "no") ){
				CL_disableCookie();
			}
		}
	}
	
	function CL_addFileCss(){
		$args = array(
			"title"=>"Titolo del banner",
			"text"=>"Questo è il testo del banner.",
			"click"=>"Accetto",
			"trans"=>0.85,
			"color"=>"0,0,0",
			"width"=>"total",
			"rounds"=>"10",
			"color_t"=>"FFFFFF",
			"pos_o"=>"center",
			"pos_h"=>"top",
			"color_click"=>"5BC0DE",
			"border_color"=>"46B8DA",
			"color_info"=>"2980B9",
		);
		$default = get_option('cl_banner',$args);
		if($default['width'] != "total"){
			if($default['pos_o'] == "left"){
				$horiz = "left:30px;";
			}elseif($default['pos_o'] == "center"){
				$horiz = "";
			}else{
				$horiz = "right:30px;";
			}
		}
		if($default['pos_h'] == "top"){
			$vert = "top:50px;";
		}elseif($default['pos_h'] == "center"){
			$vert = "top:50%;";
		}else{
			$vert = "bottom:50px;";
		}
		
		wp_enqueue_style('CookieLegal-handle-style',
			plugins_url('css/CookieLegalStyle.css', __FILE__ ), false, true 
		);
		echo '
			<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
			<META HTTP-EQUIV="expires" CONTENT="Wednesday,16 Jul 99 10:20:00 GMT">
			<style type="text/css">
				#containerCookieLegal{
					position:fixed;
					'.$vert.'
					z-index:99999;}
				
				#CookieLegal{
					'.$horiz.'
					background-color:rgba('.$default['color'].','.$default['trans'].');
					border:1px solid #999;
					border-radius:'.$default['rounds'].'px;}
				
				#CookieLegal p{
					margin:0px;
					margin-top:10px;
					padding:0px;
					margin-left:10px;
					font-size:0.9em;
					text-align:justify;
					color:#'.$default['color_t'].';}
				
				#CookieLegal a.button, a.buttonLegalPolicy {
					float: right;
					margin-top: 10px;
					margin-bottom: 10px;
					margin-right: 20px;
					color: #'.$default['color_t_click'].';
					background-color: #'.$default['color_click'].';
					border-color: #'.$default['border_color'].';
					border: 1px solid #BBB;
					border-radius: 5px;
					box-shadow: 0px 0px 1px 1px #'.$default['color_click'].' inset;
					font: bold 12px/1 "helvetica neue",helvetica,arial,sans-serif;
					padding: 5px 0px;
					text-align: center;
					text-shadow: 0px 1px 0px #FFF;
					margin-left: 20px;}
				
				#CookieLegal a.button:hover, a.buttonLegal:hover{
					background-color:#31b0d5;
					border-color:#269abc}
				
				#CookieLegal a.info{
					margin:0px;
					padding:0px;
					color:#'.$default['color_info'].';}
				
			</style>
		';
		echo '<script type="text/javascript" src="http://www.cookielegal.it/js/cookieLegalBar.js"></script>';
		wp_enqueue_script('CookieLegal-core-js',
			plugins_url('js/cookieLegalBar.js', __FILE__ ),
			array( 'jquery' ), false, true 
		);
		echo '<script type="text/javascript">
			jQuery(document).ready(function(){
				loadBar();
			});
		</script>';
		
		
	}
	
	function CL_creaBanner(){
		if( (!isset($_COOKIE['CookieLegal']))){
			$args = array(
				"title"=>"Titolo del banner",
				"text"=>"Questo è il testo del banner.",
				"click"=>"Accetto",
				"trans"=>0.85,
				"color"=>"0,0,0",
				"width"=>"total",
				"rounds"=>"10",
				"color_t"=>"FFFFFF",
				"pos_o"=>"center",
				"pos_h"=>"top",
			);
			$option = get_option('cl_banner',$args);
			$args = array(
				"id"=>"-1",
				"title"=>"",
				"type"=>"",
				"link"=>"#",
			);
			$post = get_option('cl_policy',$args);
			
			if($option['width'] == "total"){
				if($option['pos_o'] == "center"){
					$width = "col-lg-offset-1 col-md-offset-1 col-lg-10 col-md-10 col-sx-12 col-sm-12"; 
				}else{
					if($option['pos_o'] == "left"){
						$width = "col-lg-10 col-md-10 col-sx-12 col-sm-12"; 
					}else{
						$width = "col-lg-offset-2 col-md-offset-2 col-lg-10 col-md-10 col-sx-12 col-sm-12"; 
					}
				}
			}elseif($option['width'] == "medium"){
				if($option['pos_o'] == "center"){
					$width = "col-lg-offset-2 col-md-offset-2 col-lg-8 col-md-8 col-sx-12 col-sm-12"; 
				}else{
					if($option['pos_o'] == "left"){
						$width = "col-lg-8 col-md-8 col-sx-12 col-sm-12"; 
					}else{
						$width = "col-lg-offset-4 col-md-offset-4 col-lg-8 col-md-8 col-sx-12 col-sm-12"; 
					}
				} 
			}else{
				if($option['pos_o'] == "center"){
					$width = "col-lg-offset-4 col-md-offset-4 col-lg-4 col-md-4 col-sx-12 col-sm-12"; 
				}else{
					if($option['pos_o'] == "left"){
						$width = "col-lg-4 col-md-4 col-sx-12 col-sm-12"; 
					}else{
						$width = "col-lg-offset-8 col-md-offset-8 col-lg-4 col-md-4 col-sx-12 col-sm-12"; 
					}
				}
			}
			
			echo '<div id="containerCookieLegal" class="container-fluid">';
				echo '<div id="CookieLegal" class="'.$width.'">';
					echo '<div class="left col-lg-8 col-md-9 col-sm-8 col-sx-8">';
						echo '<p>'.$option['text'].' </br><a class="info" href="'.$post['link'].'">Maggiori informazioni</a></p>';
					echo '</div>';
					echo '<div class="right col-lg-4 col-md-3 col-sm-4 col-sx-4">';
						echo '<a href="#" onclick="accept()" class="button">'.$option['click'].'</a>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	}
	
	if(!is_admin()){
		add_action('wp','CL_inizialize');
		add_action("wp_head","CL_addFileCss");
	
		add_action("wp_footer","CL_creaBanner");
	}else{
		require_once(dirname( __FILE__ ) . '/CoockieLegalAdmin.php');
		$admin = new AdminSettingsCookieLegal();
	}
	
?>