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

function wst_get_translate($char){
	$charurl=urlencode($char);
	$data = wst_get_html("http://api.search.live.com/xml.aspx?AppId=".APP_ID."&Sources=Translation&Translation.SourceLanguage=".SOURCE."&Translation.TargetLanguage=".TARGET."&Query=".$charurl);
	preg_match('/<tra:TranslatedTerm>(.*?)<\/tra:TranslatedTerm>/',$data,$translate);
	$txt = trim($translate[1]);
	return $txt;
}
function wp_slug_translate($postID){
	global $wpdb;
	$tableposts = $wpdb->posts ;
		$sql = "SELECT post_title,post_name FROM $tableposts WHERE ID=$postID";
		$res = $wpdb->get_results($sql);	
		$post_title = $res[0]->post_title;
		$post_name = $res[0]->post_name;
		$wst_title = wst_get_translate($post_title);
		
		if( sanitize_title($post_title) != $post_name ) {
			if( !substr_count($post_name,'%') )
				return true;
		}

		$wst_title = sanitize_title($wst_title);
		if( strlen($wst_title) < 2 ) {
			$wst_title = $postID;
		}
		
		$sql ="UPDATE ".$tableposts." SET `post_name` = '".$wst_title."' WHERE ID =$postID;";		
		$res = $wpdb->query($sql);
}
add_action('publish_post', 'wp_slug_translate');
add_action('edit_post', 'wp_slug_translate');

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