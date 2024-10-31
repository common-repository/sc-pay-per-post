<?php
function scppp_display_widget(){
	global $user_login, $current_user, $table_prefix, $wpdb;
	if(is_user_logged_in()){
		$site_url=get_option('siteurl');
		echo '<div>';
		echo '<a href="'.$site_url.'/post-dashboard/">User Posts</a>';
		echo '</div>';
	}
	else{
		echo 'Please Login';
	}
}

function scppp_sidebar_widget($args) {
  extract($args);
  echo $before_widget;
  echo $before_title;?>User Posts<?php echo $after_title;
  scppp_display_widget();
  echo $after_widget;
}


function scppp_widget_init(){
	register_sidebar_widget("Scppp_Widget", "scppp_sidebar_widget");
}

add_action("plugins_loaded", "scppp_widget_init");
?>