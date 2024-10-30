<?php
	class AdminSettingsCookieLegal{
		
		public function AdminSettingsCookieLegal(){
			//add_action('admin_init',array($this,'page_init'));
    		add_action('admin_menu',array($this,'admin_menu'));	
			add_action('admin_enqueue_scripts', array($this,'enqueue_color_picker'));
		}
		
		public function enqueue_color_picker( $hook_suffix ) {
			wp_enqueue_script('CookieLegal-color-js',
				plugins_url('js/jscolor/jscolor.js', __FILE__ ),
				array( 'jquery' ), false, true 
			);
			wp_enqueue_script('CookieLegal-core-js',
				plugins_url('js/cookieLegalBar.js', __FILE__ ),
				array( 'jquery' ), false, true 
			);
			wp_enqueue_style('CookieLegal-handle-style',
				plugins_url('css/CookieLegalStyle.css', __FILE__ ), false, true 
			);
		}
		
		public function admin_menu() {
			add_menu_page(
				'Cookie Legal',
				'Cookie Legal',
				'activate_plugins',
				'CookieLegal',
				array( $this, 'render_page' ) 
			);
			
			add_submenu_page(
				'CookieLegal', 
				__( "Genera la tua Informativa Breve", 'CookieLegal' ), 
				__( "Impostazioni Banner", 'CookieLegal' ),
				"activate_plugins", 
				'CookieLegal' . '-settingsBanner',
				array($this,'render_page') 
			);
			
			add_submenu_page(
				'CookieLegal', 
				__( "Scegli la tua Informativa Estesa", 'CookieLegal' ), 
				__( "Informativa Estesa", 'CookieLegal' ),
				"activate_plugins",
				'CookieLegal' . '-settingsPolicy',
				array($this,'render_page')
			);
			
			add_submenu_page(
				'CookieLegal', 
				__("Altre Funzionalità", 'CookieLegal' ), 
				__("Acquisti", 'CookieLegal' ), 
				"activate_plugins", 
				'CookieLegal' . '-addon', 
				array($this,'render_page')
			);
	
		}
		
		public function render_page(){
			$page = $_GET['page'];
			if( ($page == "CookieLegal") || ($page == "CookieLegal-info") ){
				include_once("form/info.php");
			}
			if($page == "CookieLegal-settingsBanner"){
				include_once("form/settingsBanner.php");
			}
			if($page == "CookieLegal-settingsPolicy"){
				include_once("form/settingsPolicy.php");
			}
			if($page == "CookieLegal-addon"){
				include_once("form/addon.php");
			}
		}
		
	}
?>