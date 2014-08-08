<?php
/*
Plugin Name: WP Slug Translate
Plugin URI: http://boliquan.com/wp-slug-translate/
Description: WP Slug Translate can translate the post slug into English. It will take the post ID as slug when translation failure.
Version: 1.8.8
Author: BoLiQuan
Author URI: http://boliquan.com/
Text Domain: WP-Slug-Translate
Domain Path: /lang
*/

define("CLIENTID",get_option('wp_slug_translate_clientid'));
define("CLIENTSECRET",get_option('wp_slug_translate_clientsecret'));
define("SOURCE",get_option('wp_slug_translate_language'));
define("TARGET","en");

function load_wp_slug_translate_lang(){
	$currentLocale = get_locale();
	if(!empty($currentLocale)){
		$moFile = dirname(__FILE__) . "/lang/wp-slug-translate-" . $currentLocale . ".mo";
		if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('WP-Slug-Translate',$moFile);
	}
}
add_filter('init','load_wp_slug_translate_lang');

class WstHttpRequest
{
	function curlRequest($url, $header = array(), $postData = ''){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		if(!empty($header)){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		if(!empty($postData)){
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($postData) ? http_build_query($postData) : $postData);
		}
		$curlResponse = curl_exec($ch);
		curl_close($ch);
		return $curlResponse;
	}
}

class WstMicrosoftTranslator extends WstHttpRequest
{
	private $_clientID = CLIENTID;
	private $_clientSecret = CLIENTSECRET;
	private $_fromLanguage = SOURCE;
	private $_toLanguage = TARGET;

	private $_grantType = "client_credentials";
	private $_scopeUrl = "http://api.microsofttranslator.com";
	private $_authUrl = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";

	private function _getTokens(){
		try{
			$header = array('User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:6.0.1) Gecko/20100101 Firefox/6.0.1');
			$postData = array(
				'grant_type' => $this->_grantType,
				'scope' => $this->_scopeUrl,
				'client_id' => $this->_clientID,
				'client_secret' => $this->_clientSecret
			);
			$response = $this->curlRequest($this->_authUrl, $header, $postData);
			$jsonObj = json_decode($response);
			if(!empty($jsonObj->access_token)){
				return $jsonObj->access_token;
			}
		}
		catch(Exception $e){
			//echo "Exception-" . $e->getMessage();
		}
	}

	function translate($inputStr){
		$params = "text=" . rawurlencode($inputStr) . "&from=" . $this->_fromLanguage . "&to=" . $this->_toLanguage;
		$translateUrl = "http://api.microsofttranslator.com/v2/Http.svc/Translate?$params";
		$accessToken = $this->_getTokens();
		$authHeader = "Authorization: Bearer " . $accessToken;
		$header = array($authHeader, "Content-Type: text/xml");
		$curlResponse = $this->curlRequest($translateUrl, $header);
		
		$xmlObj = simplexml_load_string($curlResponse);
		$translatedStr = '';
		foreach((array)$xmlObj[0] as $val){
			$translatedStr = $val;
		}

		return $translatedStr;
	}

}

if(get_option("wp_slug_translate_secondmode")!='yes'){

function wp_slug_translate($postid){
	global $wpdb;
	$sql = "SELECT post_title,post_name FROM $wpdb->posts WHERE ID = '$postid'";
	$results = $wpdb->get_results($sql);	
	$post_title = $results[0]->post_title;
	$post_name = $results[0]->post_name;

	if( !substr_count($post_name,'%') && !is_numeric($post_name) ){
		if(substr_count($post_name,'_')){
			$wst_post_name = str_replace('_','-',$post_name);
			$sql = "UPDATE $wpdb->posts SET post_name = '$wst_post_name' WHERE ID = '$postid'";
			$wpdb->query($sql);
		}
		return true;
	}

	$post_title = str_replace(array('_','/'),array(' ',' '),$post_title);
	$wst_microsoft= new WstMicrosoftTranslator();
	$wst_title = sanitize_title( $wst_microsoft->translate($post_title) );
	if( strlen($wst_title) < 2 ){
		$wst_title = $postid;
	}
		
	$sql = "UPDATE $wpdb->posts SET post_name = '$wst_title' WHERE ID = '$postid'";		
	$wpdb->query($sql);
}
//add_action('publish_post', 'wp_slug_translate', 1);
//add_action('edit_post', 'wp_slug_translate', 1);
add_action('save_post', 'wp_slug_translate', 1);

}else{

function wp_slug_translate($postname){
	$post_name = $postname;
	$post_title = $_POST['post_title'];
	
	if( !empty($post_name) && !is_numeric($post_name) ) return str_replace('_','-',$post_name);

	$post_title = str_replace(array('_','/'),array(' ',' '),$post_title);
	$wst_microsoft= new WstMicrosoftTranslator();
	$wst_title = sanitize_title( $wst_microsoft->translate($post_title) );
	
	return $wst_title;
}
add_filter('name_save_pre', 'wp_slug_translate', 1);

}

function wp_slug_translate_activate(){
	add_option('wp_slug_translate_clientid','wp-slug-translate');
	add_option('wp_slug_translate_clientsecret','pK2JdEwF/Janzz2O36Lgkq0QcDkc4Fuw0HqJvWVIFLQ=');
	add_option('wp_slug_translate_language','zh-CHS');
	add_option('wp_slug_translate_secondmode','');
	add_option('wp_slug_translate_deactivate','');
}
register_activation_hook( __FILE__, 'wp_slug_translate_activate' );

if(get_option("wp_slug_translate_deactivate")=='yes'){
	function wp_slug_translate_deactivate(){
		delete_option('wp_slug_translate_clientid');
		delete_option('wp_slug_translate_clientsecret');
		delete_option('wp_slug_translate_language');
		delete_option('wp_slug_translate_secondmode');
		delete_option('wp_slug_translate_deactivate');
	}
	register_deactivation_hook( __FILE__, 'wp_slug_translate_deactivate' );
}

function wp_slug_translate_settings_link($action_links,$plugin_file){
	if($plugin_file==plugin_basename(__FILE__)){
		$wst_settings_link = '<a href="options-general.php?page=' . dirname(plugin_basename(__FILE__)) . '/wp_slug_translate_admin.php">' . __("Settings") . '</a>';
		array_unshift($action_links,$wst_settings_link);
	}
	return $action_links;
}
add_filter('plugin_action_links','wp_slug_translate_settings_link',10,2); 

if(is_admin()){require_once('wp_slug_translate_admin.php');}

?>