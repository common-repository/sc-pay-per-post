<?php
function scppp_insert_ut_data(){
	global $table_prefix, $wpdb;
	$ut_name=$_POST['user_type'];
	$ut_price=$_POST['user_type_price'];
	$ut_status=$_POST['status'];
	
	$sql="INSERT INTO ".$table_prefix."scppp_user_type (user_type_name, user_type_price, user_type_status) VALUES ('".$ut_name."', '".$ut_price."', '".$ut_status."')";
	$wpdb->query($sql);
	scppp_view_user_type();
}

function scppp_update_edited_data(){
	global $table_prefix, $wpdb;
	$utid=$_POST['scppp_ut_id'];
	$ut_name=$_POST['user_type'];
	$ut_price=$_POST['user_type_price'];
	$ut_status=$_POST['status'];
	
	$sql=$wpdb->query("UPDATE ".$table_prefix."scppp_user_type SET user_type_name='".$ut_name."', user_type_price='".$ut_price."', user_type_status=".$ut_status." WHERE id='".$utid."' ");
	scppp_view_user_type();
}

function scppp_delete_user_type(){
	global $table_prefix, $wpdb;
	$utid=$_POST['scppp_ut_id'];
	$sql="DELETE FROM ".$table_prefix."scppp_user_type WHERE id=".$utid."";
	$wpdb->query($sql);
	scppp_view_user_type();
}

function scppp_user_type_option(){
	global $table_prefix, $wpdb;
	
	if(isset($_POST['add_user_type'])){
		scppp_add_user_type();
	}
	elseif(isset($_POST['scppp_add_ut_submit'])){
		scppp_insert_ut_data();
	}
	elseif(isset($_POST['scppp_ut_edit'])){
		scppp_edit_user_type();
	}
	elseif(isset($_POST['scppp_edit_ut_submit'])){
		scppp_update_edited_data();
	}
	elseif(isset($_POST['scppp_ut_delete'])){
		scppp_delete_user_type();
	}
	else{
		scppp_view_user_type();
	}
}

function scppp_view_user_type(){
	global $table_prefix, $wpdb;
	
	$sql="SELECT * FROM ".$table_prefix."scppp_user_type";
	$results=$wpdb->get_results($sql);
	
	echo '<div class="wrap">';
	echo '<div class="icon32" id="icon-users"><br></div><h2>SCPPP User Type</h2><br />';
	?><form method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);?>" ><?php
	echo '<input type="hidden" name="add_user_type" value="true" />';
	echo '<input type="submit" class="button add-new-h2" value="Add New" style="width:60px;" />';
	?></form><?php
	echo '<table cellspacing="0" class="widefat fixed">';
	echo '<thead><tr>';
	echo '<th class="manage-column" width="50">Sn</th>';
	echo '<th class="manage-column">User Type</th>';
	echo '<th class="manage-column">Price</th>';
	echo '<th class="manage-column">Status</th>';
	echo '<th class="manage-column">Created Date</th>';
	echo '<th class="manage-column" width="50"></th>';
	echo '<th class="manage-column" width="50"></th>';
	echo '</tr></thead>';
	echo '<tfoot><tr><th colspan="7"></th></tr></tfoot>';
	$i=1;
	foreach($results as $ut_result){
		echo '<tr>';
		echo '<td>'.$i++.'</td>';
		echo '<td>'.$ut_result->user_type_name.'</td>';
		echo '<td>'.$ut_result->user_type_price.'</td>';
		
		echo '<td>';
		if($ut_result->user_type_status==1){echo 'active';}else{echo 'Inactive';}
		echo '</td>';
		
		echo '<td>'.$ut_result->cr_date.'</td>';
		
		echo '<td>';
		?><form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" ><?php
		echo '<input type="hidden" name="scppp_ut_id" id="scppp_ut_id" value="'.$ut_result->id.'" />';
		echo '<input type="hidden" name="scppp_ut_edit" value="true" />';
		echo '<input type="submit" value="Edit" class="edit_btn" title="Edit" />';
		?></form><?php
		echo '</td>';
		echo '<td >';
		?><form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" ><?php
		echo '<input type="hidden" name="scppp_ut_id" id="scppp_ut_id" value="'.$ut_result->id.'" />';
		echo '<input type="hidden" name="scppp_ut_delete" value="true" />';
		echo '<input type="submit" value="Delete" class="delete_btn" title="delete" />';
		?></form><?php
		echo '</td>';
		echo '</tr>';
	}
	
	echo '</table>';
	echo '</div>';
}

function scppp_add_user_type(){
	
	echo '<div class="wrap">';
	echo '<div class="icon32" id="icon-users"><br></div><h2>SCPPP User Type</h2><br />';
	echo '<div id="poststuff" class="metabox-holder has-right-sidebar">';
	echo '<div id="post-body"><div id="post-body-content"><div id="namediv" class="stuffbox">';
	echo '<h3>Add user type</h3>';
	echo '<div class="inside">';
	?><form method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);?>" ><?php
	echo '<table>';
	echo '<tr><td width="150"><label>User Type</label></td>';
	echo '<td><input type="text" name="user_type" id="user_type" /></td></tr>';
	echo '<tr><td valign="top"><label>Price</label></td>';
	echo '<td><input type="text" name="user_type_price" id="user_type_price" style="width:100px;" /><strong>$</strong></td></tr>';
	echo '<tr><td><label>Status</label></td>';
	echo '<td><select name="status"><option value="1">Active</option><option value="0">Not Active</option></select></td></tr>';	
	echo '<input type="hidden" name="scppp_add_ut_submit" value="true" />';
	echo '<tr><td colspan="2" align="right"><input type="submit" value="Add User Type" class="button-primary" style="width:100px; border:none;" /></td></tr>';
	echo '</table>';
	?></form><?php
	echo '</div>';
	echo '</div></div></div>';
	echo '</div>';
	echo '</div>';
}

function scppp_edit_user_type(){
	global $table_prefix, $wpdb;
	
	$utid=$_POST['scppp_ut_id'];
	$sql="SELECT * FROM ".$table_prefix."scppp_user_type WHERE id=".$utid."";
	$results=$wpdb->get_row($sql);
	
	echo '<div class="wrap">';
	echo '<div class="icon32" id="icon-users"><br></div><h2>SCPPP User Type</h2><br />';
	echo '<div id="poststuff" class="metabox-holder has-right-sidebar">';
	echo '<div id="post-body"><div id="post-body-content"><div id="namediv" class="stuffbox">';
	echo '<h3>Add user type</h3>';
	echo '<div class="inside">';
	?><form method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);?>" ><?php
	echo '<table>';
	echo '<tr><td width="150"><label>User Type</label></td>';
	echo '<td><input type="text" name="user_type" id="user_type" value="'.$results->user_type_name.'" /></td></tr>';
	echo '<tr><td valign="top"><label>Price</label></td>';
	echo '<td><input type="text" name="user_type_price" id="user_type_price" style="width:100px;" value="'.$results->user_type_price.'" /></td></tr>';
	echo '<tr><td><label>Status</label></td>';
	if($results->user_type_status==1){$active='selected="selected"';}else{$active='';}
	if($results->user_type_status==0){$inactive='selected="selected"';}else{$inactive='';}
	echo '<td><select name="status"><option value="1" '.$active.'>Active</option><option value="0" '.$inactive.'>Not Active</option></select></td></tr>';
	echo '<input type="hidden" name="scppp_ut_id" id="scppp_ut_id" value="'.$results->id.'" />';
	echo '<input type="hidden" name="scppp_edit_ut_submit" value="true" />';
	echo '<tr><td colspan="2" align="right"><input type="submit" value="Edit User Type" class="button-primary" style="width:100px; border:none;" /></td></tr>';
	echo '</table>';
	?></form><?php
	echo '</div>';
	echo '</div></div></div>';
	echo '</div>';
	echo '</div>';
}
?>