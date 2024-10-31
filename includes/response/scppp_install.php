<?php
function scppp_craete_plugin_page_table(){
	global $table_prefix, $wpdb;
	$table_name = $table_prefix."scppp_page";
	
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		    id int(11) NOT NULL auto_increment,
		   `page_id` int(11) NOT NULL default '0',
		   `cr_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		    PRIMARY KEY  (id)
		    );";
			
	$wpdb->query($sql);
}

function scppp_craete_plugin_response_table(){
	global $table_prefix, $wpdb;
	$table_name = $table_prefix."scppp_response";
	
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		    id int(11) NOT NULL auto_increment,
			`pdn` mediumtext NOT NULL default '',
		   `response_id` mediumtext NOT NULL default '',
		   `site_url` mediumtext NOT NULL default '',
		   `cr_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		    PRIMARY KEY  (id)
		    );";
			
	$wpdb->query($sql);
}

function scppp_users_table(){
	global $table_prefix, $wpdb;
	$table_name = $table_prefix."scppp_users";
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		    id int(11) NOT NULL auto_increment,
		   `user_id` int(11) NOT NULL default '0',
		   `user_name` mediumtext NOT NULL default '',
		   `user_email` mediumtext NOT NULL default '',
		   `user_level` int(11) NOT NULL default '0',
		   `user_status` varchar(20) NOT NULL default '',
		   `user_post_count` int(11) NOT NULL default '0',
		   `cr_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		    PRIMARY KEY  (id)
		    );";
	$wpdb->query($sql);
}

function scppp_user_type_table(){
	global $table_prefix, $wpdb;
	$table_name = $table_prefix."scppp_user_type";
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		    id int(11) NOT NULL auto_increment,
		   `user_type_name` mediumtext NOT NULL default '',
		   `user_type_price` int(11) NOT NULL default '0',
		   `user_type_status` int(1) NOT NULL default '1',
		   `cr_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		    PRIMARY KEY  (id)
		    );";
	$wpdb->query($sql);

}

function scppp_paypal_table(){
	global $table_prefix, $wpdb;
	$table_name = $table_prefix."scppp_paypal";
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		    id int(11) NOT NULL auto_increment,
		   `auth_code` mediumtext NOT NULL default '',
		   `paypal_email` mediumtext NOT NULL default '',
		   `paypal_ipn` int(1) NOT NULL default '0',
		   `paypal_currency` varchar(10) NOT NULL default 'USD',
		   `paypal_sandbox` int(1) NOT NULL default '0',
		   `user_message` mediumtext NOT NULL default '',
		   `admin_message` mediumtext NOT NULL default '',
		   `admin_email` mediumtext NOT NULL default '',
		   `cr_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		    PRIMARY KEY  (id)
		    );";
	$wpdb->query($sql);
}

function scppp_purchase_table(){
	global $table_prefix, $wpdb;
	$table_name = $table_prefix."scppp_purchase_log";
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		    id int(11) NOT NULL auto_increment,
			`wp_user_id` int(11) NOT NULL default '0',
			`wp_user_name` mediumtext NOT NULL default '',
		   `payer_first_name` mediumtext NOT NULL default '',
		   `payer_last_name` mediumtext NOT NULL default '',
		   `payer_id` mediumtext NOT NULL default '',
		   `payer_status` mediumtext NOT NULL default '',
		   `payer_email` mediumtext NOT NULL default '',
		   `verify_sign` mediumtext NOT NULL default '',
		   `transaction_id` mediumtext NOT NULL default '',
		   `transaction_type` mediumtext NOT NULL default '',
		   `session_id` mediumtext NOT NULL default '',
		   `auth_id` mediumtext NOT NULL default '',
		   `item_name` mediumtext NOT NULL default '',
		   `item_number` mediumtext NOT NULL default '',
		   `item_quantity` int(11) NOT NULL default '0',
		   `total_price` float NOT NULL default '0.00',
		   `mc_gross` float NOT NULL default '0.00',
		   `handling_amount` float NOT NULL default '0.00',
		   `shipping` float NOT NULL default '0.00',
		   `tax` float NOT NULL default '0.00',
		   `payment_status` mediumtext NOT NULL default '',
		   `payment_type` mediumtext NOT NULL default '',
		   `payment_date` mediumtext NOT NULL default '',
		   `scppp_status` mediumtext NOT NULL default '',
		   `cr_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		    PRIMARY KEY  (id)
		    );";
	$wpdb->query($sql);
}

function scppp_page_create(){
	global $table_prefix, $wpdb;
	$sql="SELECT * FROM wp_posts WHERE post_title='SCPPP page'";
	$page_result=$wpdb->get_row($sql);
	$result=$page_result->post_title;
	$wppost_title="SCPPP page";
	$wppost_type="page";
	$wpstatus="publish";
	if(!isset($result)){
		$sqls_wp="INSERT INTO  wp_posts(post_title,post_status,guid,post_type)VALUES('$wppost_title','$wpstatus','','$wppost_type')"; 
		$wpdb->query($sqls_wp);
	}
}

function add_scppp_admin_page(){
	add_object_page('scppp', 'SCPPP', 8,__FILE__, 'manage_scppp' );
	add_submenu_page(__FILE__, 'scppp', 'Manage PPP', 8,__FILE__, 'manage_scppp');
}

function add_scppp_sub_menu(){
	$response=scppp_plugin_response();
	if($response){
	$page_ref = add_submenu_page( __FILE__, 'scppp-user-type', 'User Type', 8, 'scppp_user_type','scppp_user_type_option');
	$page_ref = add_submenu_page( __FILE__, 'scppp-users', 'Manage User', 8, 'scppp_users','scppp_manage_user');
    $page_ref = add_submenu_page( __FILE__, 'scppp-paypal', 'Paypal', 8, 'scppp_paypal','scppp_paypal_option');
	$page_ref = add_submenu_page( __FILE__, 'scppp-transaction', 'Transaction', 8, 'scppp_transaction','scppp_transaction_history');
	}
	$page_ref = add_submenu_page( __FILE__, 'scppp-upgrade', 'Upgrade', 8, 'scppp_upgrade','scppp_product_upgrade');   
}

function manage_scppp(){
	$scppp_siteurl=get_option('siteurl');
	scppp_craete_plugin_page_table();
	scppp_craete_plugin_response_table();
	scppp_users_table();
	scppp_user_type_table();
	scppp_paypal_table();
	scppp_purchase_table();
	$option=array(
		'scppp_url'	=> $scppp_siteurl,
		'scpppapi'	=> '',
		'scppp_status' => 'inactive'
	);
	add_option('scppp',$option,'','yes');
	scppp_admin_oppage();
}

function scppp_admin_oppage(){
	echo '<div class="wrap">';
	echo '<div class="icon32" id="icon-tools"><br></div><h2>SCPPP Admin</h2><br />';
	
	echo '<h2>Instructions</h2>';
	echo '<p>';
	echo 'To setup SC Pay Per Post Plugin, first create three page named Post Page, Post Dashboard, and a subscription page. Use the following short code in those three pages to work this plugin.';
	echo '<ul><li><strong>Post Page :</strong> [scppppostpage]</li><li><strong>Post Dashboard :</strong> [scpppmanagepost]</li><li><strong>Subscription :</strong> [scpppuserupgrade]</li></ul><br />';
	
	echo '</p>';
	echo '<h2>Widget</h2>';
	echo '<p>Activate the SCPPP_Widget from admin widget section to display Scppp widget in the sidebar. </p>';
	echo '<h2>Paypal Option</h2>';
	echo '<p>Please fill the paypal form from paypal option page. And make sure paypal ipn is configured correctly. Paypal IPN is mandatory for SCPPP. In paypal ipn configuration page, use post page url. Example: <strong>your_site_url/post-page/</strong></p>';
  echo '<h2>Permalinks</h2>';
  echo '<p>Please select Custom permalink structure and set it to <strong>"/%postname%/"</strong></p>';
  echo '<h2><strong>API KEY</strong></h2>';
  echo '<p>An API key is mandatory to install this plugin. Without API key it will not work. To get an API key go to www.solvercircle.com/contact-us/ page and send us a message with your domain/site name and email address or send us an email at info@solvercircle.com. We will generate the API key and send it to your email address. And the API key is FREE. </p>';
	echo '</div>';

}

function scppp_install(){
	$newoptions = get_option('scppp_option');
	add_option('scppp_option', $newoptions);
}

function scppp_uninstall(){
	delete_option('scppp_options');
}	 
	 	                                         											  
add_action('admin_menu', 'add_scppp_admin_page');
add_action('admin_menu', 'add_scppp_sub_menu');
register_activation_hook( __FILE__, 'scppp_install' );
register_deactivation_hook( __FILE__, 'scppp_uninstall' );


?>