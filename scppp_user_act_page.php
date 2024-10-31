<?php

function scppp_upgrade_user(){
	global $user_login, $current_user, $table_prefix, $wpdb;
	$utid=$_POST['ut_id'];
	$wp_uid=$current_user->ID;
	$wp_uname=$current_user->user_login;
	$wp_uemail=$current_user->user_email;
	$ustatus='1';
	
	$sql="INSERT INTO ".$table_prefix."scppp_users (user_id, user_name, user_email, user_level, user_status) VALUES ('".$wp_uid."', '".$wp_uname."', '".$wp_uemail."', '".$utid."', '".$ustatus."')";
	$wpdb->query($sql);
}

function scppp_user_upgrede_shortcode(){
	global $user_login, $current_user, $table_prefix, $wpdb;
	if(is_user_logged_in()){
		$sql="SELECT * FROM ".$table_prefix."scppp_users WHERE user_id=".$current_user->ID." AND user_status='1'";
		$user_result=$wpdb->get_row($sql);

		if(count($user_result)==0){
			if(isset($_POST['ut_submit'])){
				scppp_upgrade_user();
			}
			$sql="SELECT * FROM ".$table_prefix."scppp_user_type WHERE user_type_price<>0";
			$results=$wpdb->get_results($sql);
			echo '<h3>Select Yor Subscription Plan :</h3>';
			echo '<table width="100%">';
			echo '<tr><td colspan="2" style="border-bottom:solid 1px #cccccc;" height="20"></td></tr>';
			foreach($results as $uts){
			?><form name="upgrade_user" action="" method="post" enctype="multipart/form-data"><?
			echo '<tr><td colspan="2" height="20"></td></tr>';
			echo '<tr><td width="150">Subscription type </td><td>: '.$uts->user_type_name.'</td></tr>';
			echo '<tr><td>Subscription Price </td><td>: '.$uts->user_type_price.'</td></tr>';
			echo '<input type="hidden" name="ut_id" id="ut_id" value="'.$uts->id.'" />';
			echo '<tr><td></td><td> <input type="submit" name="ut_submit" value="Subscribe" /></td>';
			echo '<tr><td colspan="2" style="border-bottom:solid 1px #cccccc;" height="20"></td></tr>';
			?></form><?
			}
			echo '</table>';
		}
		else{
			echo "You already subscribed this subscription plan";
		}
	}
	else{
		?><div>Please <a href="<? bloginfo('url');?>/wp-login.php">login</a> to upgrade your account.</div><?
	}
}

add_shortcode("scpppuserupgrade","scppp_user_upgrede_shortcode");


?>