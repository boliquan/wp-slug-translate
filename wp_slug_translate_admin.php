<?php
function wp_slug_translate_admin(){
	add_options_page('WP Slug Translate Options', 'WP Slug Translate','manage_options', __FILE__, 'wp_slug_translate_page');
	add_action('admin_init','wp_slug_translate_register');
}
function wp_slug_translate_register(){
	register_setting('wst-settings','wp_slug_translate_clientid');
	register_setting('wst-settings','wp_slug_translate_clientsecret');
	register_setting('wst-settings','wp_slug_translate_language');
	register_setting('wst-settings','wp_slug_translate_secondmode');
	register_setting('wst-settings','wp_slug_translate_deactivate');
}
function wp_slug_translate_page(){
	function wp_slug_translate_reset(){
		update_option('wp_slug_translate_clientid','wp-slug-translate');
		update_option('wp_slug_translate_clientsecret','pK2JdEwF/Janzz2O36Lgkq0QcDkc4Fuw0HqJvWVIFLQ=');
		update_option('wp_slug_translate_language','zh-CHS');
		update_option('wp_slug_translate_secondmode','');
		update_option('wp_slug_translate_deactivate','');
	}
	if(isset($_POST['wp_slug_translate_reset'])){
		if($_POST['wp_slug_translate_reset']=='reset'){
			wp_slug_translate_reset();
			echo '<div id="message" class="updated fade"><p><strong>' . __("Default settings restored!","WP-Slug-Translate") . '</strong></p></div>';
		}
	}
?>
<div class="wrap">
	
<?php screen_icon(); ?>
<h2>WP Slug Translate</h2>

<form action="options.php" method="post" enctype="multipart/form-data" name="wp_slug_translate_form">
<?php settings_fields('wst-settings'); ?>

<table class="form-table">
	<tr valign="top">
		<th scope="row">
			Microsoft Azure Application<br />
			<span style="font-family:Tahoma,sans-serif;font-size:12px;"><a href="https://datamarket.azure.com/dataset/1899a118-d202-492c-aa16-ba21c33c06cb" title="Step One" target="_blank">Subscribe</a> | <a href="https://datamarket.azure.com/developer/applications/register" title="Step Two" target="_blank">Register</a> | <a href="http://boliquan.com/microsoft-azure-application/" title="<?php _e('Apply for your own ClientID and ClientSecret','WP-Slug-Translate'); ?>" target="_blank"><?php _e('Tutorial','WP-Slug-Translate'); ?></a></span>
		</th>
		<td>
			<label>
				<input type="text" name="wp_slug_translate_clientid" value="<?php echo get_option('wp_slug_translate_clientid'); ?>" style="width:300px;height:24px;" />
				<code>ClientID</code>
			</label><br />
			<label>
				<input type="text" name="wp_slug_translate_clientsecret" value="<?php echo get_option('wp_slug_translate_clientsecret'); ?>" style="width:300px;height:24px;" />
				<code>ClientSecret</code>
			</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e('Source Language','WP-Slug-Translate'); ?>
		</th>
		<td>
			From:
			<select name="wp_slug_translate_language">
				<option value="zh-CHS" <?php if (get_option('wp_slug_translate_language') == 'zh-CHS') { echo 'selected="selected"'; } ?>>zh-CHS - Chinese(Simplified)</option>
				<option value="zh-CHT" <?php if (get_option('wp_slug_translate_language') == 'zh-CHT') { echo 'selected="selected"'; } ?>>zh-CHT - Chinese(Traditional)</option>
				<option value="ar" <?php if (get_option('wp_slug_translate_language') == 'ar') { echo 'selected="selected"'; } ?>>ar - Arabic</option>
				<option value="bg" <?php if (get_option('wp_slug_translate_language') == 'bg') { echo 'selected="selected"'; } ?>>bg - Bulgarian</option>
				<option value="ca" <?php if (get_option('wp_slug_translate_language') == 'ca') { echo 'selected="selected"'; } ?>>ca - Catalan</option>
				<option value="cs" <?php if (get_option('wp_slug_translate_language') == 'cs') { echo 'selected="selected"'; } ?>>cs - Czech</option>
				<option value="da" <?php if (get_option('wp_slug_translate_language') == 'da') { echo 'selected="selected"'; } ?>>da - Danish</option>
				<option value="nl" <?php if (get_option('wp_slug_translate_language') == 'nl') { echo 'selected="selected"'; } ?>>nl - Dutch</option>
				<option value="en" <?php if (get_option('wp_slug_translate_language') == 'en') { echo 'selected="selected"'; } ?>>en - English</option>
				<option value="et" <?php if (get_option('wp_slug_translate_language') == 'et') { echo 'selected="selected"'; } ?>>et - Estonian</option>
				<option value="fi" <?php if (get_option('wp_slug_translate_language') == 'fi') { echo 'selected="selected"'; } ?>>fi - Finnish</option>
				<option value="fr" <?php if (get_option('wp_slug_translate_language') == 'fr') { echo 'selected="selected"'; } ?>>fr - French</option>
				<option value="de" <?php if (get_option('wp_slug_translate_language') == 'de') { echo 'selected="selected"'; } ?>>de - German</option>
				<option value="el" <?php if (get_option('wp_slug_translate_language') == 'el') { echo 'selected="selected"'; } ?>>el - Greek</option>
				<option value="ht" <?php if (get_option('wp_slug_translate_language') == 'ht') { echo 'selected="selected"'; } ?>>ht - Haitian Creole</option>
				<option value="he" <?php if (get_option('wp_slug_translate_language') == 'he') { echo 'selected="selected"'; } ?>>he - Hebrew</option>
				<option value="hi" <?php if (get_option('wp_slug_translate_language') == 'hi') { echo 'selected="selected"'; } ?>>hi - Hindi</option>
				<option value="mww" <?php if (get_option('wp_slug_translate_language') == 'mww') { echo 'selected="selected"'; } ?>>mww - Hmong Daw</option>
				<option value="hu" <?php if (get_option('wp_slug_translate_language') == 'hu') { echo 'selected="selected"'; } ?>>hu - Hungarian</option>
				<option value="id" <?php if (get_option('wp_slug_translate_language') == 'id') { echo 'selected="selected"'; } ?>>id - Indonesian</option>
				<option value="it" <?php if (get_option('wp_slug_translate_language') == 'it') { echo 'selected="selected"'; } ?>>it - Italian</option>
				<option value="ja" <?php if (get_option('wp_slug_translate_language') == 'ja') { echo 'selected="selected"'; } ?>>ja - Japanese</option>
				<option value="tlh" <?php if (get_option('wp_slug_translate_language') == 'tlh') { echo 'selected="selected"'; } ?>>tlh - Klingon</option>
				<option value="tlh-Qaak" <?php if (get_option('wp_slug_translate_language') == 'tlh-Qaak') { echo 'selected="selected"'; } ?>>tlh-Qaak - Klingon(pIqaD)</option>
				<option value="ko" <?php if (get_option('wp_slug_translate_language') == 'ko') { echo 'selected="selected"'; } ?>>ko - Korean</option>
				<option value="lv" <?php if (get_option('wp_slug_translate_language') == 'lv') { echo 'selected="selected"'; } ?>>lv - Latvian</option>
				<option value="lt" <?php if (get_option('wp_slug_translate_language') == 'lt') { echo 'selected="selected"'; } ?>>lt - Lithuanian</option>
				<option value="ms" <?php if (get_option('wp_slug_translate_language') == 'ms') { echo 'selected="selected"'; } ?>>ms - Malay</option>
				<option value="mt" <?php if (get_option('wp_slug_translate_language') == 'mt') { echo 'selected="selected"'; } ?>>mt - Maltese</option>
				<option value="no" <?php if (get_option('wp_slug_translate_language') == 'no') { echo 'selected="selected"'; } ?>>no - Norwegian</option>
				<option value="fa" <?php if (get_option('wp_slug_translate_language') == 'fa') { echo 'selected="selected"'; } ?>>fa - Persian</option>
				<option value="pl" <?php if (get_option('wp_slug_translate_language') == 'pl') { echo 'selected="selected"'; } ?>>pl - Polish</option>
				<option value="pt" <?php if (get_option('wp_slug_translate_language') == 'pt') { echo 'selected="selected"'; } ?>>pt - Portuguese</option>
				<option value="ro" <?php if (get_option('wp_slug_translate_language') == 'ro') { echo 'selected="selected"'; } ?>>ro - Romanian</option>
				<option value="ru" <?php if (get_option('wp_slug_translate_language') == 'ru') { echo 'selected="selected"'; } ?>>ru - Russian</option>
				<option value="sk" <?php if (get_option('wp_slug_translate_language') == 'sk') { echo 'selected="selected"'; } ?>>sk - Slovak</option>
				<option value="sl" <?php if (get_option('wp_slug_translate_language') == 'sl') { echo 'selected="selected"'; } ?>>sl - Slovenian</option>
				<option value="es" <?php if (get_option('wp_slug_translate_language') == 'es') { echo 'selected="selected"'; } ?>>es - Spanish</option>
				<option value="sv" <?php if (get_option('wp_slug_translate_language') == 'sv') { echo 'selected="selected"'; } ?>>sv - Swedish</option>
				<option value="th" <?php if (get_option('wp_slug_translate_language') == 'th') { echo 'selected="selected"'; } ?>>th - Thai</option>
				<option value="tr" <?php if (get_option('wp_slug_translate_language') == 'tr') { echo 'selected="selected"'; } ?>>tr - Turkish</option>
				<option value="uk" <?php if (get_option('wp_slug_translate_language') == 'uk') { echo 'selected="selected"'; } ?>>uk - Ukrainian</option>
				<option value="ur" <?php if (get_option('wp_slug_translate_language') == 'ur') { echo 'selected="selected"'; } ?>>ur - Urdu</option>
				<option value="vi" <?php if (get_option('wp_slug_translate_language') == 'vi') { echo 'selected="selected"'; } ?>>vi - Vietnamese</option>
				<option value="cy" <?php if (get_option('wp_slug_translate_language') == 'cy') { echo 'selected="selected"'; } ?>>cy - Welsh</option>
			</select>
			&nbsp;&nbsp;&nbsp;
			To:
			<code>en - English</code>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e('Second Mode','WP-Slug-Translate'); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wp_slug_translate_secondmode" value="yes" <?php if(get_option("wp_slug_translate_secondmode")=='yes') echo 'checked="checked"'; ?> />
				<?php _e('Running in the second mode.','WP-Slug-Translate'); ?>
			</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e('Delete Options','WP-Slug-Translate'); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wp_slug_translate_deactivate" value="yes" <?php if(get_option("wp_slug_translate_deactivate")=='yes') echo 'checked="checked"'; ?> />
				<?php _e('Delete options while deactivate this plugin.','WP-Slug-Translate'); ?>
			</label>
		</td>
	</tr>
</table>

<p class="submit">
<input type="submit" class="button-primary" name="Submit" value="<?php _e('Save Changes'); ?>" />
</p>

</form>

<form action="" method="post">
	<input type="hidden" name="wp_slug_translate_reset" value="reset" />
	<input type="submit" class="button" value="<?php _e('Reset'); ?>" />
</form>

<br />
<?php $fanyi_url = plugins_url('/img/fanyi.png', __FILE__);?>
<?php $donate_url = plugins_url('/img/paypal_32_32.jpg', __FILE__);?>
<?php $paypal_donate_url = plugins_url('/img/paypal_donate_email.jpg', __FILE__);?>
<?php $ali_donate_url = plugins_url('/img/alipay_donate_email.jpg', __FILE__);?>

<div class="icon32"><img src="<?php echo $fanyi_url; ?>" alt="fanyi" /></div>
<h2>Description</h2>
<p>
 1. WP Slug Translate can translate the post slug into english. It will take the post ID as slug when translation failure.<br />
 2. "Microsoft Azure Application": Input your own ClientID and ClientSecret. Up to 2 million characters a month every account.<br />
 3. "Source Language": Choose your language, 45 languages supported, powered by Microsoft Translator API.<br />
 4. "Second Mode": Running in the second mode, compatible with some synchronous plugins.<br />
 5. When you have written an article, click "Publish", then the post slug will be automatically translated into English.<br />
 6. For more information, please visit: <a href="http://boliquan.com/wp-slug-translate/" target="_blank">WP Slug Translate</a> | <a href="http://wordpress.org/plugins/wp-slug-translate/" target="_blank">Usage</a> | <a href="http://wordpress.org/plugins/wp-slug-translate/" target="_blank">Download</a>
</p>

<div class="icon32"><img src="<?php echo $donate_url; ?>" alt="Donate" /></div>
<h2>Donate</h2>
<p>
If you find my work useful and you want to encourage the development of more free resources, you can do it by donating.
</p>
<p>
<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SCWY6NPFRR8EY" target="_blank"><img src="<?php echo $paypal_donate_url; ?>" alt="Paypal Donate" title="Paypal" /></a>
&nbsp;
<a href="https://www.alipay.com/" target="_blank"><img src="<?php echo $ali_donate_url; ?>" alt="Alipay Donate" title="Alipay" /></a>
</p>
<br />

<?php $blq_logo_url = plugins_url('/img/blq_32_32.jpg', __FILE__);?>
<div class="icon32"><img src="<?php echo $blq_logo_url; ?>" alt="BoLiQuan" /></div>
<h2>Related Links</h2>
<ul style="margin:0 18px;">
<li><a href="http://boliquan.com/wp-slug-translate/" target="_blank">WP Slug Translate (FAQ)</a> | <a href="http://boliquan.com/wp-slug-translate/" target="_blank">Submit Translations</a> | <a href="http://wordpress.org/plugins/wp-slug-translate/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/wp-clean-up/" target="_blank">WP Clean Up</a> | <a href="http://wordpress.org/plugins/wp-clean-up/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/wp-smtp/" target="_blank">WP SMTP</a> | <a href="http://wordpress.org/plugins/wp-smtp/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/wp-anti-spam/" target="_blank">WP Anti Spam</a> | <a href="http://wordpress.org/plugins/wp-anti-spam/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/wp-code-highlight/" target="_blank">WP Code Highlight</a> | <a href="http://wordpress.org/plugins/wp-code-highlight/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/yg-share/" target="_blank">YG Share</a> | <a href="http://wordpress.org/plugins/yg-share/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/ylife/" target="_blank">YLife</a> | <a href="http://code.google.com/p/ylife/downloads/list" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/" target="_blank">BoLiQuan</a></li>
</ul>

<div style="text-align:center; margin:60px 0px 10px 0px;">&copy; <?php echo date("Y"); ?> BoLiQuan.COM</div>

</div>
<?php 
}
add_action('admin_menu', 'wp_slug_translate_admin');
?>