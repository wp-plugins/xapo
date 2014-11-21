<?php
/**
	* Plugin Name: Xapo
	* Plugin URI: http://xapo.com
	* Description: A WordPress plugin that lets you accept bitcoin tips on your blog posts.
	* Version: 0.2.1
	* Author: Xapo
	* Author URI: https://xapo.com
	*/

define ( 'XAPO_TIP_URL', plugins_url () . '/' . basename(dirname(__FILE__)));


include("xapoMicroPaymentSDK.php");

function render_tip_btn($receiver_user_email, $post_title, $amount_BIT, $pay_type) {

	$xapoURL = "https://mpayment.xapo.com/pay_button/show";


	XapoMicroPaymentSDK::setEnvironmentUrl($xapoURL);

	if (empty($receiver_user_email)) {
		return false;
	}

	$pay_object_id = sanitize_title(get_bloginfo('name'));
	if (!empty($post_title)) {
		$pay_object_id .= " ".$post_title;
	}

	if (!empty($receiver_user_email)) {
		$html = XapoMicroPaymentSDK::iframeWidget(null, null, null, $receiver_user_email, $receiver_user_email, $pay_object_id, $amount_BIT, $pay_type);
	}

	return $html;
}

function print_tip_btn($content) {

	if (is_main_query()) {
		global $authordata;
		global $post;

		$echoWidget = false;
		if (is_null($content)) {
			$echoWidget = true;
		}


		if ( is_singular() ) {

			// Setup inital vars for xapo button
			$post_title = $post->ID; // Using Post ID right now just for reference

			$optionTipToAdmin = get_option('tip_to_admin');
			$optionTipToCustom = get_option('tip_to_custom');

			if ( $optionTipToAdmin == "true" && empty($optionTipToCustom) ) {
				$author_email = get_option('admin_email');
			} elseif (!empty($optionTipToCustom)) {
				$author_email = $optionTipToCustom;
			} else {
				$author_email = $authordata->user_email;
			}

			$optionAmountBit = get_option('amount_bit');
			if ( !empty($optionAmountBit) && $optionAmountBit > 0 ) {
				$amount_BIT = $optionAmountBit;
			} else {
				$amount_BIT = null;
			}

			$optionPayType = get_option('pay_type');
			if ( !empty($optionPayType) ) {
				$pay_type = $optionPayType;
			} else {
				$pay_type = "Tip"; // Default pay type value
			}
			
			$tip_btn = render_tip_btn($author_email, $post_title, $amount_BIT, $pay_type);

			if ($echoWidget) {
				echo stripslashes($tip_btn);
				return true;
			}

			// Add Tip Btn at end of content
			return $content . stripslashes($tip_btn);
		}

	}

	return $content;

}

// Don't add button to content for now
// Add iframe in theme using function print_tip_btc();
add_filter('the_content', 'print_tip_btn');

// Add Options Page to Settings Menu
add_action('admin_menu', 'add_options_menu');
function add_options_menu() {
	add_submenu_page('options-general.php', 'Xapo Button', 'Xapo Button', 'manage_options', 'xapo-button', 'xapo_options_page_callback' );
}

function xapo_options_page_callback() {
	include 'xapo-options.php';
}

function register_settings() {

	register_setting( 'xapo-tipping', 'xapoApiURL');
	register_setting( 'xapo-tipping', 'xapoAppID');
	register_setting( 'xapo-tipping', 'xapoAppSecret');

	register_setting( 'xapo-tipping', 'pay_type');
	register_setting( 'xapo-tipping', 'amount_bit');
	register_setting( 'xapo-tipping', 'tip_to_admin');
	register_setting( 'xapo-tipping', 'tip_to_custom');


} 

add_action( 'admin_init', 'register_settings' );

add_action( 'admin_notices', 'xapo_active_event' ) ;

function xapo_active_event() {
	?>
<!-- Facebook Conversion Code for Wordpress Plugin Active --> <script>(function() { var _fbq = window._fbq || (window._fbq = []); if (!_fbq.loaded) { var fbds = document.createElement('script'); fbds.async = true; fbds.src = '//connect.facebook.net/en_US/fbds.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(fbds, s); _fbq.loaded = true; } })(); window._fbq = window._fbq || []; window._fbq.push(['track', '6022567962340', {'value':'0.00','currency':'USD'}]); </script> <noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6022567962340&amp;cd[value]=0.00&amp…" /></noscript>
	<?php
}


// Add custom CSS and JS
function xapo_enqueue_style() {
	wp_enqueue_style('xapotipcss', XAPO_TIP_URL.'/assets/css/style.css', null, '0.0.1'); 
}

function xapo_enqueue_script() {
	wp_enqueue_script('xapotipjs', XAPO_TIP_URL.'/assets/js/bitbtn.js', array('jquery'), '0.0.1', true);
}

add_action( 'wp_enqueue_scripts', 'xapo_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'xapo_enqueue_script' );