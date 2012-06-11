<?php
/*
Plugin Name: JigoShop Statistics
Plugin URI: http://wordpress.org/extend/plugins/admin-quick-jump/
Description: Essential sales statistics for JigoShop
Author: James Kemp
Version: 1.0
Author URI: http://www.jckemp.com/
*/

class jck_jigoshop_stats {

	################################################
	###                                          ###
	###            General Functions             ###
	###                                          ###
	################################################
	
	function array_average_nonzero($arr) { 
	   return array_sum($arr) / count(array_filter($arr)); 
	}

	################################################
	###                                          ###
	###               Admin Menu                 ###
	###                                          ###
	################################################

	function jigoshop_admin_menu_additons() {

		add_submenu_page('jigoshop', __('Statistics', 'jigoshop'), __('Statistics', 'jigoshop'), 'manage_options', 'jigoshop_stats', array(&$this, 'stats'));
		
	}
	
	function stats() {
	
		include plugin_dir_path(__FILE__).'/jigoshop-stats-admin.php';
	
	}

	################################################
	###                                          ###
	###            Construct Class               ###
	###                                          ###
	################################################
	
	// PHP 4 Compatible Constructor
	function jck_jigoshop_stats() {
		$this->__construct();
	}
	
	// PHP 5 Constructor
	function __construct() { 
		add_action('admin_menu', array(&$this, 'jigoshop_admin_menu_additons'), 25);
	} 

}

$jck_jigoshop_stats = new jck_jigoshop_stats; // Start an instance of the plugin class