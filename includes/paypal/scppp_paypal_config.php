<?php
function scppp_update_paypal_option(){
	global $table_prefix, $wpdb;
	
	$scppp_auth_code=$_POST['scppp_auth_code'];
	$scpp_ppemail=$_POST['scppp_paypal_email'];
	$scppp_ppipn=$_POST['scpp_paypal_ipn'];
	$scppp_pp_currency='USD';
	if($_POST['scppp_paypal_sandbox']=='on'){
		$scppp_sandbox=1;
	}
	else{$scppp_sandbox=0;}
	$scppp_user_msg=$_POST['scppp_user_payment_message'];
	$scppp_admin_msg=$_POST['scppp_admin_payment_message'];
	$scppp_admin_email=$_POST['admin_email_address'];
	$scppp_pp_id=$_POST['scppp_paypal_id'];
	
	$wpdb->update( ''.$table_prefix.'scppp_paypal',
					array(
						'auth_code' 		=> $scppp_auth_code,
						'paypal_email' 		=> $scpp_ppemail,
						'paypal_ipn' 		=> $scppp_ppipn,
						'paypal_currency' 	=> $scppp_pp_currency,
						'paypal_sandbox'	=> $scppp_sandbox,
						'user_message' 		=> $scppp_user_msg,
						'admin_message' 	=> $scppp_admin_msg,
						'admin_email'		=> $scppp_admin_email
					),
					array('id'=>$scppp_pp_id),
					array('%s','%s','%d','%s','%d','%s','%s','%s'),
					array('%d')
				);
	
}

function scppp_insert_default_paypal_option(){
	global $table_prefix, $wpdb;
	
	$wpdb->insert( ''.$table_prefix.'scppp_paypal',
					array(
						'paypal_currency' => 'USD'
					),
					array('%s')
				);
}

function scppp_paypal_option(){
	global $table_prefix, $wpdb;
	
	$sql="SELECT * FROM ".$table_prefix."scppp_paypal LIMIT 1";
	$result=$wpdb->get_results($sql);
	if(count($result)<=0){
		scppp_insert_default_paypal_option();
	}
	
	if(isset($_POST['scppp_edit_paypal_config'])){
		scppp_update_paypal_option();
	}
	
	scppp_paypal_config_page();
}

function scppp_paypal_config_page(){
	global $table_prefix, $wpdb;
	
	$sql="SELECT * FROM ".$table_prefix."scppp_paypal LIMIT 1";
	$result=$wpdb->get_row($sql);
	
	echo '<div class="wrap">';
	echo '<div class="icon32" id="icon-tools"><br></div><h2>SCPPP Paypal Configuration</h2><br />';
	
	echo '<div id="poststuff" class="metabox-holder has-right-sidebar">';
	echo '<div id="post-body"><div id="post-body-content"><div id="namediv" class="stuffbox">';
	echo '<h3>Paypal Options</h3>';
	echo '<div class="inside">';
	?><form method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);?>" ><?php
	echo '<table>';
	echo '<tr><td colspan="2"><div class="pplogo"></div></td></tr>';
	echo '<tr><td height="30" width="150"><label>Authorization Code</label></td>';
	echo '<td><input type="text" name="scppp_auth_code" id="scppp_auth_code" value="'.$result->auth_code.'" /></td></tr>';
	echo '<tr><td height="30"><label>Paypal Email</label></td>';
	echo '<td><input type="text" name="scppp_paypal_email" id="scppp_paypal_email" value="'.$result->paypal_email.'" /></td></tr>';
	echo '<tr><td height="30"><label>Paypal IPN</label></td>';
	if($result->paypal_ipn==0){$active='selected="selected"';}else{$active='';}
	if($result->paypal_ipn==1){$inactive='selected="selected"';}else{$inactive='';}
	echo '<td><select name="scpp_paypal_ipn"><option value="0" '.$inactive.' >No</option><option value="1" '.$inactive.' >Yes</option></select></td></tr>';
	echo '<tr><td height="30">Currency Type</td>';
	echo '<td><input type="text" name="scppp_currency_type" id="scppp_currency_type" value="'.$result->paypal_currency.'" disabled="disabled" style="width:100px;" /></td>';
	echo '<tr><td height="30">Sandbox Mode</td>';
	if($result->paypal_sandbox==1){ $checked='checked="checked"';}else{$checked='';}
	echo '<td><input type="checkbox" name="scppp_paypal_sandbox" id="scppp_paypal_sandbox" style="width:20px;" '.$checked.' /> </td></tr>';
	
	echo '<tr><td colspan="2" align="right"><input type="submit" value="Save" class="button-primary" style="width:100px; border:none;" /></td></tr>';
	echo '</table>';
	
	echo '</div>';
	echo '</div></div></div>';
	echo '</div>';
	
	echo '<div id="poststuff" class="metabox-holder has-right-sidebar">';
	echo '<div id="post-body"><div id="post-body-content"><div id="namediv" class="stuffbox">';
	echo '<h3>Email Options</h3>';
	echo '<div class="inside">';
	
	echo '<table>';
	echo '<tr><td width="150" valign="top"><label>User message</label></td>';
	echo '<td><textarea name="scppp_user_payment_message" rows="4" style="width:100%;">'.$result->user_message.'</textarea></td></tr>';
	echo '<tr><td valign="top"><label>Admin message</label></td>';
	echo '<td><textarea name="scppp_admin_payment_message" rows="4" style="width:100%;">'.$result->admin_message.'</textarea></td></tr>';
	echo '<tr><td><label>Admin email address</label></td>';
	echo '<td><input type="text" name="admin_email_address" value="'.$result->admin_email.'" /></td></tr>';
	
	echo '<input type="hidden" name="scppp_paypal_id" id="scppp_paypal_id" value="'.$result->id.'" />';
	echo '<input type="hidden" name="scppp_edit_paypal_config" value="true" />';
	echo '<tr><td colspan="2" align="right" height="30"><input type="submit" value="Save" class="button-primary" style="width:100px; border:none;" /></td></tr>';
	echo '</table>';
	echo '</div>';
	echo '</div></div></div>';
	echo '</div>';
	
	
	echo '<div id="poststuff" class="metabox-holder has-right-sidebar">';
	echo '<div id="post-body"><div id="post-body-content"><div id="namediv" class="stuffbox">';
	echo '<h3>Notes</h3>';
	echo '<div class="inside">';
	echo '<table>';
	echo '<tr><td>**Paypal IPN must be enabled<br /><br />Use Following url for paypal ipn<br /><br /><strong>'.get_option('siteurl').'/post-page/</td></tr>';
	echo '</table>';
	?></form><?php
	echo '</div>';
	echo '</div></div></div>';
	echo '</div>';
	
	
	echo '</div>';
}
?>