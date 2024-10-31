<?php
function scppp_edit_user_sts(){
	global $table_prefix, $wpdb;
	$user_ids=$_POST['scppp_user_id'];
	$usersts=$_POST['user_sts'];
	$sql=$wpdb->query("UPDATE ".$table_prefix."scppp_users SET user_status=".$usersts." WHERE id=".$user_ids."");
}
function scppp_edit_user_data(){
	global $table_prefix, $wpdb;
	$user_ids=$_POST['scppp_user_id'];
	$sql="SELECT * FROM ".$table_prefix."scppp_users WHERE id=".$user_ids."";
	$result=$wpdb->get_row($sql);
	echo '<div class="wrap">';
	echo '<div class="icon32" id="icon-users"><br></div><h2>SCPPP Edit User</h2><br />';
	echo '<div id="poststuff" class="metabox-holder has-right-sidebar">';
	echo '<div id="post-body"><div id="post-body-content"><div id="namediv" class="stuffbox">';
	echo '<h3>Add user type</h3>';
	echo '<div class="inside">';
	?><form method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);?>" ><?php
	echo '<table>';
	echo '<tr><td width="120">User Name</td><td> '.$result->user_name.'</td></tr>';
	if($result->user_status=='1'){$active='selected="selected"';}
	if($result->user_status=='0'){$inactive='selected="selected"';}
	echo '<tr><td>User Status</td><td><select name="user_sts"><option value="1" '.$active.'>Active</option><option value="0" '.$inactive.'>Inactive</option></select></td></tr>';
	echo '<input type="hidden" name="scppp_user_id" value="'.$user_ids.'" />';
	echo '<input type="hidden" name="scppp_user_sts_update" value="ture" />';
	echo '<tr><td colspan="2" align="right"><input type="submit" class="button-primary" style="width:100px; border:none;" value="Edit user" /></td>';
	echo '</table>';	
	?></form><?php
	echo '</div>';
	echo '</div></div></div>';
	echo '</div>';
	echo '</div>';
}
function scppp_delete_users(){
	global $table_prefix, $wpdb;
	$user_ids=$_POST['scppp_user_id'];
	$sql=$wpdb->query("DELETE FROM ".$table_prefix."scppp_users WHERE id=".$user_ids."");
}

function scppp_manage_user(){
	global $table_prefix, $wpdb;
	if(isset($_POST['scppp_user_sts_update'])){
		scppp_edit_user_sts();
	}
	if(isset($_POST['scppp_user_edit'])){
		scppp_edit_user_data();
	}
	else{
		if(isset($_POST['scppp_user_delete'])){
			scppp_delete_users();
		}
		view_manage_user();
	}
}

function view_manage_user(){
	global $table_prefix, $wpdb;
	
	$sql="SELECT a.*, b.user_type_name FROM ".$table_prefix."scppp_users as a INNER JOIN ".$table_prefix."scppp_user_type as b ON a.user_level=b.id";
	$results=$wpdb->get_results($sql);
	
	echo '<div class="wrap">';
	//page title
	echo '<div class="icon32" id="icon-users"><br></div><h2>SCPPP Users</h2><br />';
	?><form method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);?>" ><?php
	echo '<input type="hidden" name="add_user_type" value="true" />';
	?></form><?php
	echo '<table cellspacing="0" class="widefat fixed">';
	echo '<thead><tr>';
	echo '<th class="manage-column" width="50">Sn</th>';
	echo '<th class="manage-column">User Name</th>';
	echo '<th class="manage-column">User Type</th>';
	echo '<th class="manage-column">Status</th>';
	echo '<th class="manage-column">Posts</th>';
	echo '<th class="manage-column" width="50"></th>';
	echo '<th class="manage-column" width="50"></th>';
	echo '</tr></thead>';
	echo '<tfoot><tr><th colspan="7"></th></tr></tfoot>';
	$i=1;
	foreach($results as $ut_result){
		echo '<tr>';
		echo '<td>'.$i++.'</td>';
		echo '<td>'.$ut_result->user_name.'</td>';
		echo '<td>'.$ut_result->user_type_name.'</td>';
		
		echo '<td>';
		if($ut_result->user_status==1){echo 'active';}else{echo 'Inactive';}
		echo '</td>';
		
		echo '<td>'.$ut_result->user_post_count.'</td>';
		
		echo '<td>';
		?><form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" ><?php
		echo '<input type="hidden" name="scppp_user_id" id="scppp_user_id" value="'.$ut_result->id.'" />';
		echo '<input type="hidden" name="scppp_user_edit" value="true" />';
		echo '<input type="submit" value="Edit" class="edit_btn" title="Edit" />';
		?></form><?php
		echo '</td>';
		echo '<td >';
		?><form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" ><?php
		echo '<input type="hidden" name="scppp_user_id" id="scppp_user_id" value="'.$ut_result->id.'" />';
		echo '<input type="hidden" name="scppp_user_delete" value="true" />';
		echo '<input type="submit" value="Delete" class="delete_btn" title="delete" />';
		?></form><?php
		echo '</td>';
		echo '</tr>';
	}
	
	echo '</table>';
	echo '</div>';
}

?>