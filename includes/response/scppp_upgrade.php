<?php
function scppp_product_upgrade(){
	global $wpdb, $table_prefix;
	$oldapi=scppp_get_api_response_id();
	if(isset($_POST['scppp_api_submit'])){
		if($oldapi!=''){
			$sql=$wpdb->query("DELETE FROM ".$table_prefix."scppp_response");
		}
		$api=$_POST['scppp_api'];
		$pdn='sc pay per post';
		$siteurl=get_option('siteurl');
		$new_api=$api.'--'.$pdn.'--'.$siteurl;
		$url='http://solvercircle.com/sc_upgrade_api.php';
		$data=array('apikey' => urlencode($api),'pdn' => urlencode($pdn), 'siteurl' => urlencode($siteurl));
		$api_response=scppp_curl_api_submit($url,$data);
		if($api_response!=''){
			$response_data=explode("-",$api_response);
			$res_product_name=$response_data[0];
			$res_api_key=$response_data[1];
			$res_site_url=$response_data[2];
			$wpdb->insert( ''.$table_prefix.'scppp_response',
					array(
						'pdn' 			=> $pdn,
						'response_id'	=> $res_api_key,
						'site_url'		=> $res_site_url
					),
					array('%s','%s','%s')
				);
			$option=array('scppp_url'=>$res_site_url,'scpppapi'=>$res_api_key,'scppp_status'=>'active');
			update_option('scppp',$option);
			$api_response_msg='API successfull';
		}
		else{
			$option=array('scppp_url'=>$res_site_url,'scpppapi'=>'','scppp_status'=>'inactive');
			update_option('scppp',$option);
			$api_response_msg='Your Api Key Is Invalid.';
		}
	}
	
	
	scppp_upgrade_admin($api_response_msg);
}

function scppp_upgrade_admin($msg=''){
	global $wpdb, $table_prefix;
	$sql="SELECT * FROM ".$table_prefix."scppp_response LIMIT 1";
	$result=$wpdb->get_row($sql);
	if(count($result)>0){
		$apikey=$result->response_id;
	}
	else{
		$apikey='';
	}
	echo '<div class="wrap">';
	echo '<div class="icon32" id="icon-themes"><br></div><h2>SCPPP Upgrade</h2><br />';
	echo '<font color="#ff0000">'.$msg.'</font>';
	echo '<div id="poststuff" class="metabox-holder has-right-sidebar">';
	echo '<div id="post-body"><div id="post-body-content"><div id="namediv" class="stuffbox">';
	echo '<h3>Upgrade Plugin</h3>';
	echo '<div class="inside">';
	?><form method="post" enctype="multipart/form-data" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" ><?php
	echo '<table><tr>';
	echo '<td width="100"><label>API Key</label></td>';
	echo '<td><input type="text" name="scppp_api" id="scppp_api" value="'.$apikey.'" /></td></tr>';
	echo '<input type="hidden" name="scppp_api_submit" value="true" />';
	echo '<tr><td colspan="2" height="50" align="right"><input type="submit" value="Submit" class="button-primary" style="width:100px; border:none;" /></td></tr>';
	echo '</table>';
	?></form><?php
	echo '</div>';
	echo '</div></div></div>';
	echo '</div>';
	echo '</div>';
}

function scppp_api_submit($url, $data, $optional_headers = null){
	global $wpdb;
	$params = array('http' => array(
              'method' => 'POST',
              'content' => $data
            ));
	if ($optional_headers !== null) {
		$params['http']['header'] = $optional_headers;
	}
	$ctx = stream_context_create($params);
	$fp = @fopen($url, 'rb', false, $ctx);
	if (!$fp) {
		throw new Exception("Problem with $url, $php_errormsg");
	}
	$response = @stream_get_contents($fp);
	if ($response === false) {
		throw new Exception("Problem reading data from $url, $php_errormsg");
	}
	return $response;
}

function scppp_curl_api_submit($url,$fields){
	foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
	rtrim($fields_string,'&');
	
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POST,count($fields));
	curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true );
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function scppp_plugin_response(){
	global $wpdb, $table_prefix;
	
	$pdn='sc pay per post';
	$url='http://solvercircle.com/sc_upgrade_api.php';
	
	$sql="SELECT * FROM ".$table_prefix."scppp_response LIMIT 1";
	$result=$wpdb->get_row($sql);
	if(count($result)>0){
		$response=$result->response_id;
		$response_site=$result->site_url;
		$data=array('apikey' => urlencode($response),'pdn' => urlencode($pdn), 'siteurl' => urlencode($response_site));
		$scppp_plugin_response=scppp_curl_api_submit($url,$data);
		if($scppp_plugin_response!=''){
			$scppp_reponse=1;
		}
		else{
			$scppp_reponse=0;
		}
	}
	else{
		$scppp_reponse=0;
	}
	return $scppp_reponse;
}

function scppp_get_api_response_id(){
	global $wpdb, $table_prefix;
	$sql="SELECT * FROM ".$table_prefix."scppp_response LIMIT 1";
	$result=$wpdb->get_row($sql);
	if(count($result)>0){
		return $result->id;
	}
	else{
		return '';
	}
}

function scppp_get_api_response(){
	global $wpdb, $table_prefix;
	$sql="SELECT * FROM ".$table_prefix."scppp_response LIMIT 1";
	$result=$wpdb->get_row($sql);
	if(count($result)>0){
		return $result->response_id;
	}
	else{
		return '';
	}
}

?>