<?php          
/*
Plugin Name:SC Pay Per Post
Plugin URI: http://www.solvercircle.com
Description:Pay per post.
Version: 1.0
Author: SolverCircle
Author URI: http://www.solvercircle.com
*/


define("SCPPP_BASE_URL", WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__)));
include('includes/response/scppp_install.php');
include('scppp_user_act_page.php');
include('scppp_user_type.php');
include('scppp_manage_user.php');
include('scppp_user_post_page.php');
include('includes/paypal/scppp_paypal_config.php');
include('includes/paypal/scppp_paypal.php');
include('includes/paypal/scppp_ipn.php');
include('scppp_transaction.php');
include('includes/response/scppp_upgrade.php');
include('includes/widget/scppp_user_widget.php');
include('scppp_manage_post.php');

function scppp_admin_register_head(){
	$cssurl = SCPPP_BASE_URL.'/css/svstyle.css';
    echo "<link rel='stylesheet' type='text/css' href='$cssurl' />\n";
}
add_action('admin_head', 'scppp_admin_register_head');

?>