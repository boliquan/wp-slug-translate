<?php
/*
Plugin Name: WP Slug Translate
Plugin URI: http://boliquan.com/wp-slug-translate/
Description: WP Slug Translate can translate the post slug into English,it will take the post ID as slug when translation failure.
Version: 1.7.0
Author: BoLiQuan
Author URI: http://boliquan.com/
Text Domain: WP-Slug-Translate
Domain Path: /lang
*/

//http://www.bing.com/developers/appids.aspx
define("APP_ID",get_option('wp_slug_translate_appid'));
define("SOURCE",get_option('wp_slug_translate_language'));
define("TARGET","en");

function load_wp_slug_translate_lang(){
	$currentLocale = get_locale();
	if(!empty($currentLocale)) {
		$moFile = dirname(__FILE__) . "/lang/wp-slug-translate-" . $currentLocale . ".mo";
		if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('WP-Slug-Translate', $moFile);
	}
}
add_filter('init','load_wp_slug_translate_lang');

function wst_get_html($url,$cookie=''){
	$curl = curl_init($url);
	$useragent="Mozilla/5.0 (Windows NT 5.1; rv:6.0.1) Gecko/20100101 Firefox/6.0.1";
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
	if ($cookie<>'') {
		curl_setopt ($curl, CURLOPT_COOKIE, $cookie);
	}
	$data = curl_exec($curl);
	curl_close($curl);
	return $data;
}

require_once(dirname( __FILE__ ).'/include/include.js.php');

if(is_admin()){require_once('wp_slug_translate_admin.php');}

function wp_slug_translate_activate(){
	add_option('wp_slug_translate_language','zh-CHS');
	add_option('wp_slug_translate_appid','4C8F1B4E6F087A90BDDE6DC1322F0F973EFCC32F');
	add_option('wp_slug_translate_deactivate','');
}
register_activation_hook( __FILE__, 'wp_slug_translate_activate' );

if(get_option("wp_slug_translate_deactivate")=='yes'){
	function wp_slug_translate_deactivate(){
		delete_option('wp_slug_translate_language');
		delete_option('wp_slug_translate_appid');
		delete_option('wp_slug_translate_deactivate');
	}
	register_deactivation_hook( __FILE__, 'wp_slug_translate_deactivate' );
}

?>