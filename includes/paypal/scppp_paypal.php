<?php
function scppp_paypal_form(){
	global $user_login, $current_user, $table_prefix, $wpdb;
	
	$wp_user_id=$current_user->ID;
	$wp_site_url=get_option('siteurl');
	
	$scppp_paypal='https://www.paypal.com/cgi-bin/webscr';
	$scppp_paypal_sandbox='https://www.sandbox.paypal.com/cgi-bin/webscr';
	
	$sql="SELECT * FROM ".$table_prefix."scppp_paypal LIMIT 1";
	$paypal_result=$wpdb->get_row($sql);
	
	$sql2="SELECT a.*, b.user_type_price FROM ".$table_prefix."scppp_users as a INNER JOIN ".$table_prefix."scppp_user_type as b ON a.user_level=b.id WHERE a.user_id=".$wp_user_id." AND a.user_status='1'";
	$uresult=$wpdb->get_row($sql2);
	
	if($paypal_result->paypal_sandbox==1){
		$paypal_action=$scppp_paypal_sandbox;
	}
	else{
		$paypal_action=$scppp_paypal;
	}
	
	$paypal_auth_code=scppp_gen_paypal_auth_code();
	$new_paypal_auth_code=$paypal_auth_code.''.$paypal_result->auth_code;
	
	$cssurl = SCPPP_BASE_URL.'/css/frontstyle.css';
  echo "<link rel='stylesheet' type='text/css' href='$cssurl' />\n";
	echo '<form name="paypalform" id="paypalform" action="'.$paypal_action.'" method="post">';
	echo '<input type="hidden" name="cmd" value="_xclick" />';
	echo '<input type="hidden" name="business" value="'.$paypal_result->paypal_email.'" />';
	echo '<input type="hidden" name="item_name" value="pay per post" />';
	echo '<input type="hidden" name="item_number" value="1" />';
	echo '<input type="hidden" name="amount" value="'.$uresult->user_type_price.'" />';
	echo '<input type="hidden" name="no_shipping" value="1" />';
	echo '<input type="hidden" name="no_note" value="1" />';
	
	if($paypal_result->paypal_ipn==1){
		echo '<input type="hidden" name="notify_url" value="'.$wp_site_url.'/post-page/" />';
	}
	
	if($paypal_result->paypal_sandbox==1){
		echo '<input type="hidden" name="test_ipn" value="1" />';
	}
	
	echo '<input type="hidden" name="cancel_return" value="'.get_option('home').'" />';
	echo '<input type="hidden" name="return" value="'.$wp_site_url.'/post-page/?authcode='.$new_paypal_auth_code.'" />';
	echo '<input type="hidden" name="currency_code" value="'.$paypal_result->paypal_currency.'" />';
	echo '<input type="submit" class="btn_paypal" value="" />';
	echo '</form>';
	
}

function scppp_gen_paypal_auth_code() {
    $length = 20;
    $characters ='1234567890abcdefghijklmnopqrstuvwxyz';
    $string = '';    

    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }

    return $string;
}
?>