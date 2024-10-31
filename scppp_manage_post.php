<?php
function scppp_update_post_data(){
	global $user_login, $current_user, $table_prefix, $wpdb;
	$post_authr=$current_user->ID;
	$post_id=$_POST['scppp_post_id'];
	$post_ttl=$_POST['scppp_post_title'];
	$post_cont=$_POST['scppp_post_content'];
	
	$wpdb->update( ''.$table_prefix.'posts',
					array( 'post_title' => $post_ttl, 'post_content' => $post_cont ),
					array( 'ID' => $post_id ),
					array('%s','%s'),
					array('%d')
				);
}

function scppp_post_edit_page(){
	global $user_login, $current_user, $table_prefix, $wpdb;
	$post_authr=$current_user->ID;
	$post_id=$_POST['scppp_post_id'];
	$sql="SELECT * FROM ".$table_prefix."posts WHERE ID='".$post_id."' AND post_author='".$post_authr."'";
	$result=$wpdb->get_row($sql);
	$sqls="SELECT * FROM ".$table_prefix."term_relationships WHERE object_id='".$post_id."'";
	$scpprs=$wpdb->get_row($sqls);
	$catid=$scpprs->term_taxonomy_id;
	echo 'Edit post';
	echo '<form name="scppp_post" action="" method="post" enctype="multipart/form-data" />';
	echo '<input type="text" name="scppp_post_title" id="scppp_post_title" style="width:100%;" value="'.$result->post_title.'" /><br />';
	echo '<textarea name="scppp_post_content" rows="10" style="width:100%;">'.$result->post_content.'</textarea>';
	$category=get_categories();
	echo 'Category : <select name="scppp_post_cat">';
	foreach($category as $cats){
		if($cats->cat_ID==$catid){$sls='selected="selected"';}else{$sls='';}
		echo '<option value="'.$cats->cat_ID.'" '.$sls.'>'.$cats->cat_name.'</option>';
	}
	echo '</select><br />';
	echo '<input type="hidden" name="scppp_post_id" value="'.$post_id.'" />';
	echo '<input type="hidden" name="submit_post_data" value="true" />';
	echo '<input type="submit" name="edit_post" value="Edit Post" />';
	echo '</form>';
}
function scppp_view_manage_post(){
	global $user_login, $current_user, $table_prefix, $wpdb;
	$post_authr=$current_user->ID;
	$sql="SELECT * FROM ".$table_prefix."posts WHERE post_author='".$post_authr."' AND post_status='publish' ORDER BY ID desc";
	$result=$wpdb->get_results($sql);
	echo '<div style="width:100%;">';
	echo '<table width="100%">';
	echo '<tr style="background:#cccccc;"><th>Sn.</th><th>Post Title</th><th>Created Date</th><th></th><th></th></tr>';
	$i=1;
	foreach($result as $pr){
	echo '<tr>';
	echo '<td width="30">'.$i++.'</td>';
	echo '<td height="25">'.$pr->post_title.'</td>';
	echo '<td width="150">'.$pr->post_date.'</td>';
	echo '<td>';
	echo '<form name="scppp_post" action="" method="post" enctype="multipart/form-data" />';
	echo '<input type="hidden" name="scppp_post_id" value="'.$pr->ID.'" />';
	echo '<input type="hidden" name="scppp_post_edit" value="true" />';
	echo '<input type="Submit" Value="Edit" style="background:none; border:none; cursor:pointer;" />';
	echo '</form>';
	echo '</td>';
	echo '</tr>';
	}
	echo '</table>';
	echo '</div>';
}
function scppp_manage_post(){
	global $user_login, $current_user, $table_prefix, $wpdb;
	if(isset($_POST['submit_post_data'])){
		scppp_update_post_data();
	}
	if(isset($_POST['scppp_post_edit'])){
		scppp_post_edit_page();
	}
	else{
		scppp_view_manage_post();
	}
}

add_shortcode("scpppmanagepost","scppp_manage_post");
?>