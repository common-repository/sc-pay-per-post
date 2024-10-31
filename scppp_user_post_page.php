<?php

function scppp_post_submit(){
	global $user_login, $current_user, $table_prefix, $wpdb;
	
	$str_pattern=array(" ","  ","/","\\","'","\"",",",".","!","@","#","$","%","^","&","*","(",")","{","}",":",":","+","?","<",">","=");
	
	$post_title=$_POST['scppp_post_title'];
	$new_post_title=strtolower($post_title);
	$post_cont=$_POST['scppp_post_content'];
	$post_category=$_POST['scppp_post_cat'];
	
	$post_authr=$current_user->ID;
	$post_date=date('Y-m-d H:i:s');
	$post_date_gmt=$post_date;
	$init_post_name=str_replace($str_pattern," ",$new_post_title);
	$post_name=preg_replace('!\s+!',"-",$init_post_name);
	$post_modified=$post_date;
	$post_modified_gmt=$post_date;
	$post_type='post';
	
	
	$wpdb->insert( ''.$table_prefix.'posts',
					array(
						'post_author'	=> $post_authr,
						'post_date'		=> $post_date,
						'post_date_gmt'	=> $post_date_gmt,
						'post_content'	=> $post_cont,
						'post_title'	=> $post_title,
						'post_name'		=> $post_name,
						'post_modified'	=> $post_modified,
						'post_modified_gmt' => $post_modified_gmt,
						'post_type' 	=> $post_type
					),
					array('%d','%s','%s','%s','%s','%s','%s','%s','%s')
				);
	$inserted_id=$wpdb->insert_id;
	
	$post_url=get_option('siteurl').'/?p='.$inserted_id;
	$wpdb->update( ''.$table_prefix.'posts',
					array( 'guid' => $post_url ),
					array( 'ID' => $inserted_id ),
					array('%s'),
					array('%d')
				);
				
	$wpdb->insert(''.$table_prefix.'term_relationships', 
					array(
						'object_id' => $inserted_id,
						'term_taxonomy_id' => $post_category
					),
					array('%d','%d')
				);
}

function scppp_user_mail(){
	global $user_login, $current_user, $table_prefix, $wpdb;
	
	$sqls="SELECT user_message, admin_message, admin_email FROM ".$table_prefix."scppp_paypal";
	$result=$wpdb->get_row($sqls);
	$user_msg=$result->user_message;
	$user_email=$current_user->user_email;
	$admin_msg=$result->admin_message;
	$admin_email=$result->admin_email;
	
	$headers=get_option('siteurl');
	$subject='Pay per post';
	
	wp_mail( $user_email, $subject, $user_msg, $headers );
	wp_mail( $admin_email, $subject, $admin_msg, $headers );
}

function scppp_post_page_shortcode(){
	global $user_login, $current_user, $table_prefix, $wpdb;
	$scps=get_option('scppp');
	$scpsid=$scps['scpppapi'];
	$scpss=$scps['scppp_status'];
	$scpsdb=scppp_get_api_response();
  //print_r($_POST);
	//if(isset($_POST['merchant_return_link'])){
  if(isset($_POST['txn_id'])){
		scppp_insert_transaction_data();
	}
	
	if(!isset($_POST['post_submit']) && !isset($_POST['txn_id'])){
			scppp_paypal_ipn();
		}
	
	if(is_user_logged_in()){
		if(isset($_POST['post_submit'])){
			scppp_post_submit();
			echo "Post submitted successfully.";
		}
		
		$sql="SELECT * FROM ".$table_prefix."scppp_users WHERE user_id=".$current_user->ID." AND user_status='1'";
		$user_result=$wpdb->get_row($sql);
		if(isset($_POST['txn_id'])){
			scppp_user_mail();
			if(count($user_result)!=0){
				echo 'Add post';
				echo '<form name="scppp_post" action="" method="post" enctype="multipart/form-data" />';
				echo '<input type="text" name="scppp_post_title" id="scppp_post_title" style="width:100%;" /><br />';
				echo '<textarea name="scppp_post_content" rows="10" style="width:100%;"></textarea>';
				$category=get_categories();
				echo 'Category : <select name="scppp_post_cat">';
				foreach($category as $cats){
					echo '<option value="'.$cats->cat_ID.'">'.$cats->cat_name.'</option>';
				}
				echo '</select><br />';
				echo '<input type="submit" name="post_submit" value="Publish" />';
				echo '</form>';
			}
			else{
				echo 'To post in the blog please add a user subscription packege first.';
			}
		}
		else{
			if(($scpsid==$scpsdb) && ($scpss=='active')){
				scppp_paypal_form();
			}
		}
	}
	else{
		?><div>Please <a href="<? bloginfo('url');?>/wp-login.php">login</a> to publish post.</div><?php
	}
}


add_shortcode("scppppostpage","scppp_post_page_shortcode");
?>