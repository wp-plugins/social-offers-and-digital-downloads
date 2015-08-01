<?php
/*
Plugin Name: Share to Download and Social Coupons
Plugin URI: http://www.socialintents.com
Description: Build your email list, likes and followers with social offers, coupons and digital downloads
Version: 1.0.1
Author: Social Intents
Author URI: http://www.socialintents.com/
*/
$siso_domain = plugins_url();
add_action('init', 'siso_init');
add_action('admin_notices', 'siso_notice');
add_filter('plugin_action_links', 'siso_plugin_actions', 10, 2);
add_action('wp_footer', 'siso_insert',4);
add_action('admin_footer', 'siRedirect');
define('SI_DASHBOARD_URL', "https://www.socialintents.com/dashboard.do");
define('SI_SMALL_LOGO',plugin_dir_url( __FILE__ ).'si-small.png');
function siso_init() {
    if(function_exists('current_user_can') && current_user_can('manage_options')) {
        add_action('admin_menu', 'siso_add_settings_page');
        add_action('admin_menu', 'siso_create_menu');
    }
}
function siso_insert() {
    global $current_user;
    if(strlen(get_option('siso_widgetID')) == 32 ) {
	echo("\n\n<!-- Social Offers at www.socialintents.com -->\n<script type=\"text/javascript\">\n");
    echo("(function() {function socialintents(){\n");
    echo("    var siJsHost = ((\"https:\" === document.location.protocol) ? \"https://\" : \"http://\");\n");
    echo("    var s = document.createElement('script');s.type = 'text/javascript';s.async = true;s.src = siJsHost+'www.socialintents.com/api/offers/socialintents.js#".get_option('siso_widgetID')."';\n");
        
    echo("    var x = document.getElementsByTagName('script')[0];x.parentNode.insertBefore(s, x);};\n");
    echo("if (window.attachEvent)window.attachEvent('onload', socialintents);else window.addEventListener('load', socialintents, false);})();\n");
    echo("</script>\n");
    }
}

function siso_notice() {
    if(!get_option('siso_widgetID')) echo('<div class="error"><p><strong>'.sprintf(__('Your Social Offers and Digital Downloads Plugin is disabled. Please go to the <a href="%s">plugin settings</a> to enter a valid widget key.  Find your widget key by logging in at www.socialintents.com and selecting your App General Settings.  New to socialintents.com?  <a href="http://www.socialintents.com">Sign up for a Free Trial!</a>' ), admin_url('options-general.php?page=social-offers-and-digital-downloads')).'</strong></p></div>');
}

function siso_plugin_actions($links, $file) {
    static $this_plugin;
    if(!$this_plugin) $this_plugin = plugin_basename(__FILE__);
    if($file == $this_plugin && function_exists('admin_url')) {
	$siso_domain = plugins_url();
        $settings_link = '<a href="'.admin_url('options-general.php?page=social-offers-and-digital-downloads').'">'.__('Settings', $siso_domain).'</a>';
        array_unshift($links, $settings_link);
    }
    return($links);
}

function siso_add_settings_page() {
    function siso_settings_page() { global $siso_domain ?>
<div class="wrap">
<?php screen_icon() ?><h2><?php _e('Social Offers by Social Intents', $siso_domain) ?></h2>
    <div class="metabox-holder meta-box-sortables ui-sortable pointer">
        <div class="postbox" style="float:left;width:30em;margin-right:10px">
            <h3 class="hndle"><span><?php _e('Social Offers Widget Key', $siso_domain) ?></span></h3> 
            <div class="inside" style="padding: 0 10px">
                <form id="saveSettings" method="post" action="options.php">
                    <p style="text-align:center"><?php wp_nonce_field('update-options') ?>
			<a href="http://www.socialintents.com/" title="Social Offers and Digital Downloads">
			<?php echo '<img src="'.plugins_url( 'socialintents.png' , __FILE__ ).'" height="150" "/> ';?></a></p>
                    <p><label for="siso_widgetID"><?php printf(__('Enter your Widget Key below to activate the plugin. <br><br> If you\'ve already signed up, <a href=\'http://www.socialintents.com\' target=\'_blank\'>login here</a> to grab your key under Apps --> Social Offer --> Your Code Snippet.<br>', $siso_domain), '<strong><a href="http://www.socialintents.com/" title="', '">', '</a></strong>') ?></label><br />
			<input type="text" name="siso_widgetID" id="siso_widgetID" placeholder="Your Widget Key" value="<?php echo(get_option('siso_widgetID')) ?>" style="width:100%" />
                    <p class="submit" style="padding:0"><input type="hidden" name="action" value="update" />
                        <input type="hidden" name="page_options" value="siso_widgetID" />
                        <input type="submit" name="siso_submit" id="siso_submit" value="<?php _e('Save Settings', $siso_domain) ?>" class="button-primary" /> 
			</p>
                 </form>
            </div>
        </div>
        <div class="postbox" style="float:left;width:38em">
            <h3 class="hndle"><span id="siso_noAccountSpan"><?php _e('No Account?  Sign up for a Free Trial!', $siso_domain) ?></span></h3>
            <div id="siso_register" class="inside" style="padding: -30px 10px">			
		<p><?php printf(__('Social Intents is a social offers and apps platform that helps you grow your business with simple yet powerful apps.
			Please visit %1$sSocial Intents%2$ssocialintents.com%3$s to 
				learn more.', $siso_domain), '<a href="http://www.socialintents.com/" title="', '">', '</a>') ?></p>
			<b>Register Now!</b> (or register directly on our site at <a href="http://www.socialintents.com" target="_blank">Social Intents</a>)<br>
			<input type="text" name="siso_email" id="siso_email" value="<?php echo(get_option('admin_email')) ?>" placeholder="Your Email" style="width:50%;margin:3px;" />
			<input type="text" name="siso_name" id="siso_name" value="<?php echo(get_option('user_nicename')) ?>" placeholder="Your Name" style="width:50%;margin:3px;" />
			<input type="password" name="siso_password" id="siso_password" value="" placeholder="Your Password" style="width:50%;margin:3px;" />
			<br><input type="button" name="siso_inputRegister" id="siso_inputRegister" value="Get Started!" class="button-primary" style="margin:3px;" /> 
			</div>

	    <div id="siso_registerComplete" class="inside" style="padding: -20px 10px;display:none;">

		<p>Manage your Social Offer using the links below.  View history, customize your settings, and configure social networks.</p>
		
		<p>
		<a href='#' id='customizeLink' class="" target="_ss">Customize my offer</a>
		<br><a href='#' id='socialLink' class="" target="_ss">Set up my social networks</a><br>
		</p>
<p><a href='#' id='dashboardLink' class="button button-primary" target="_ss">View Reports</a><br>
		

		
	    </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
var siso_wid= $('#siso_widgetID').val();
if (siso_wid=='') {}
else
{
	$( "#siso_register" ).hide();
	$( "#siso_registerComplete" ).show();
	$( "#siso_noAccountSpan" ).html("Social Offers Plugin Settings");

}


$(document).on("click", "#customizeLink", function(e){
e.preventDefault();
var u = "https://www.socialintents.com/wp.do?np=widget&id=<?php echo(get_option('siso_widgetID')) ?>";
u+="&pid="+sessionStorage.getItem("siso_pid")+"&sid="+sessionStorage.getItem("siso_sid");
window.open(u, '_ss1');
});

$(document).on("click", "#socialLink", function(e){
e.preventDefault();
var u = "https://www.socialintents.com/wp.do?np=socialsettings";
u+="&pid="+sessionStorage.getItem("siso_pid")+"&sid="+sessionStorage.getItem("siso_sid");
window.open(u, '_ss2');
});

$(document).on("click", "#dashboardLink", function(e){
e.preventDefault();
var u = "https://www.socialintents.com/wp.do?np=dashboard";
u+="&pid="+sessionStorage.getItem("siso_pid")+"&sid="+sessionStorage.getItem("siso_sid");
window.open(u, '_ss3');
});
                         


$(document).on("click", "#siso_inputSaveSettings", function () {

var siso_wid= $('#siso_widgetID').val();
var siso_tt= encodeURIComponent($('#siso_tab_text').val());
var siso_tc= encodeURIComponent($('#siso_tab_color').val());
var siso_top= $('#siso_time_on_page').val();
var url = 'https://www.socialintents.com/json/jsonSaveSOSettings.jsp?tc='+siso_tc+'&tt='+siso_tt+'&wid='+siso_wid+'&top='+siso_top+'&callback=?';sessionStorage.removeItem("settings");
$.ajax({
   type: 'GET',
    url: url,
    async: false,
    jsonpCallback: 'jsonCallBack',
    contentType: "application/json",
    dataType: 'jsonp',
    success: function(json) {
       $('#siso_widgetID').val(json.key);
	sessionStorage.removeItem("settings");
	sessionStorage.removeItem("socialintents_vs_offers");
	sessionStorage.setItem("hasSeenPopup","false");
	$( "#saveDetailSettings" ).submit();
	
    },
    error: function(e) {
    }
});
});

$(document).on("click", "#siso_inputRegister", function () {
var siso_email= $('#siso_email').val();
var siso_name= $('#siso_name').val();
var siso_password= $('#siso_password').val();
var url = 'https://www.socialintents.com/json/jsonSignup.jsp?type=offers&name='+siso_name+'&email='+siso_email+'&pw='+siso_password+'&callback=?';
$.ajax({
   type: 'GET',
    url: url,
    async: false,
    jsonpCallback: 'jsonCallBack',
    contentType: "application/json",
    dataType: 'jsonp',
    success: function(json) {
	if (json.msg=='') {
         	$('#siso_widgetID').val(json.key);

sessionStorage.setItem("siso_sid",json.sid);
sessionStorage.setItem("siso_pid",json.pid);

		alert("Thanks for registering!  Next up:  Use the links to the right to customize your offer.");
		$( "#saveSettings" ).submit();
	}
	else {
		alert(json.msg);
	}
    },
    error: function(e) {
       
    }
});
});
});
</script>
<?php }
$siso_domain = plugins_url();
add_submenu_page('options-general.php', __('Social Offers', $siso_domain),
                 __('Social Offers',$siso_domain),'manage_options','social-offers-and-digital-downloads','siso_settings_page');
}
function addSiSOLink() {
$dir = plugin_dir_path(__FILE__);
include $dir . 'options.php';
}
function siso_create_menu() {
  $optionPage = add_menu_page('Social Offers','Social Offers',
                              'administrator','siso_dashboard','addSiSOLink',plugins_url('social-offers-and-digital-downloads/si-small.png'));
}
function siRedirect() {
$redirectUrl = "https://www.socialintents.com/dashboard.do";
echo "<script> jQuery('a[href=\"admin.php?page=siso_dashboard\"]').attr('href', '".$redirectUrl."').attr('target', '_ss') </script>";
}?>