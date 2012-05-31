<?php
/*
Plugin Name: WP Slug Translate
Plugin URI: http://boliquan.com/wp-slug-translate/
Description: WP Slug Translate can translate the post slug into English. It will take the post ID as slug when translation failure.
Version: 1.8.1
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
		if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('WP-Slug-Translate', $moFile);
	}
}
add_filter('init','load_wp_slug_translate_lang');

abstract class Translate 
{
    function curlRequest($url, $header = array(), $postData = ''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if (!empty($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        if (!empty($postData)) {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($postData) ? http_build_query($postData) : $postData);
        }
        $curlResponse = curl_exec($ch);
        $curlErrno = curl_errno($ch);
        if ($curlErrno) {
            $curlError = curl_error($ch);
            throw new Exception($curlError);
        }
        curl_close($ch);
        return $curlResponse;
    }
    abstract function translate($text);
}

class BingTranslator extends Translate
{
    private $_clientID = CLIENTID;
    private $_clientSecret = CLIENTSECRET;
    private $_fromLanguage = SOURCE;
    private $_toLanguage = TARGET;

    private $_authUrl = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
    private $_scopeUrl = "http://api.microsofttranslator.com";
    private $_grantType = "client_credentials";
    private $_via = 0;

    function __construct($via = 0){
        $this->_via = empty($via) ? 0 : $via;
    }
    public function setLocal($loc){
        $this->_fromLanguage = $loc;
    }
    public function setToLanguange($to){
        $this->_toLanguage = $to;
    }
    public function translate($inputStr){
		return $this->_viaApi($inputStr);
    }

    private function _getTokens(){
        try{
            $postData = array(
                'grant_type' => $this->_grantType,
                'scope' => $this->_scopeUrl,
                'client_id' => $this->_clientID,
                'client_secret' => $this->_clientSecret
            );
            $header = array('User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:6.0.1) Gecko/20100101 Firefox/6.0.1');
            $response = $this->curlRequest($this->_authUrl, $header, $postData);
            $jsonObj = json_decode($response);
            return $jsonObj->access_token;
        }
		catch(Exception $e){
            echo "Exception-" . $e->getMessage();
        }
    }

    private function _viaApi($inputStr){
        $params = "appId=&text=" . urlencode($inputStr) . "&to=" . $this->_toLanguage . "&from=" . $this->_fromLanguage;
        $translateUrl = "http://api.microsofttranslator.com/v2/Http.svc/Translate?$params";
        $accessToken = $this->_getTokens();
        $authHeader = "Authorization: Bearer " . $accessToken;
        $header = array($authHeader, "Content-Type: text/xml");
        $curlResponse = $this->curlRequest($translateUrl, $header);
		
        $xmlObj = simplexml_load_string($curlResponse);
        $translatedStr = '';
        foreach ((array)$xmlObj[0] as $val) {
            $translatedStr = $val;
        }

        return $translatedStr;
    }

}

function wp_slug_translate($postID){
	global $wpdb;
	$tableposts = $wpdb->posts ;
		$sql = "SELECT post_title,post_name FROM $tableposts WHERE ID=$postID";
		$res = $wpdb->get_results($sql);	
		$post_title = $res[0]->post_title;
		$post_name = $res[0]->post_name;

		if( sanitize_title($post_title) != $post_name ) {
			if( !substr_count($post_name,'%') )
				return true;
		}
		
		$bing= new BingTranslator();
		$wst_title = sanitize_title( $bing->translate($post_title) );
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
	update_option('wp_slug_translate_clientid','wp-slug-translate');
	update_option('wp_slug_translate_clientsecret','pK2JdEwF/Janzz2O36Lgkq0QcDkc4Fuw0HqJvWVIFLQ=');
	update_option('wp_slug_translate_language','zh-CHS');
	update_option('wp_slug_translate_deactivate','');
}
register_activation_hook( __FILE__, 'wp_slug_translate_activate' );

if(get_option("wp_slug_translate_deactivate")=='yes'){
	function wp_slug_translate_deactivate(){
		delete_option('wp_slug_translate_clientid');
		delete_option('wp_slug_translate_clientsecret');
		delete_option('wp_slug_translate_language');
		delete_option('wp_slug_translate_deactivate');
	}
	register_deactivation_hook( __FILE__, 'wp_slug_translate_deactivate' );
}

?>