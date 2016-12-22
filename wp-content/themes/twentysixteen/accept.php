<?php 
/**
 * Template Name: Accept
 */
?>
<?php get_header();
$message = "";
if(isset($_GET['entrie_id']) && !empty($_GET['entrie_id'])){
	global $wpdb;
	$entry_id =  $_GET['entrie_id'];
	$get_sql = "";
	$get_sql .= "SELECT * FROM wp_dhvc_form_entry_data";
	$get_sql .= " WHERE md5(id) = '$entry_id' ";
	$get_result = $wpdb->query($wpdb->prepare($get_sql, $entry_id));	
	if($get_result){
		if(isset($_GET['accepted']) && $_GET['accepted'] == 'Accept'){
			$status = "active";
			$updatesql = "";
			$sql = "";
			$sql .= "SELECT * FROM wp_dhvc_form_entry_data ";	
			$sql .= "WHERE md5(id) ='".$entry_id."'";
			$result = $wpdb->get_results($sql);
			if(count($result) > 0){
				$entry_data = (array) maybe_unserialize($result[0]->entry_data);
				foreach ($entry_data as $key => $value) {
					if($key == 'status'){
							$entry_data[$key] = $status;
						}
				}
				$updatesql = "";
				$updatesql .= "UPDATE wp_dhvc_form_entry_data SET `entry_data` ='".maybe_serialize($entry_data)."'";
				$updatesql .= " WHERE md5(id) ='".$entry_id."'";
				$updateresult = $wpdb->query($updatesql);	
			}
		}	
		elseif(isset($_GET['accepted']) && $_GET['accepted'] == 'Decline'){
			$deletet_sql = "DELETE FROM wp_dhvc_form_entry_data WHERE md5(id) = %d";
			$result = $wpdb->query($wpdb->prepare($deletet_sql, $entry_id));	
		}
		$message = "success";	
	}else{
		$message = "error";	
	}		
}else{
	$message = "error";
}
echo "<script>window.location.href = '".get_site_url()."/?res=".$message."';</script>";
?>
<?php get_footer(); ?>