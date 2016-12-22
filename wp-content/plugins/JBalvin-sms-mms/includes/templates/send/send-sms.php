<script type="text/javascript">	
	var boxId2 = 'wp_get_message';
	var counter = 'wp_counter';
	//var part = 'wp_part';
	var max = 'wp_max';
	function charLeft2() {
		checkSMSLength(boxId2, counter, max);
	}
	
	jQuery(document).ready(function(){
		jQuery(".wpsms-value").hide();
		jQuery(".wpsms-group").show();
		jQuery(".wp_get_attachment").fadeIn();
		jQuery('#SendMessage').click(function() {
			var validated = true;
			var message_type = jQuery('#select_sender').val();
			var message_text = jQuery('#wp_get_message').val();
			var file_selected = jQuery('#wp_get_attachment').val();
			if(message_text == ""){
				alert("Please provide text message");
				validated = false;
				return false;
			}
			if(message_type == "mms"){
				if(file_selected == ""){
					alert("Please select file");
					validated = false;
					return false;
				}
			}

			if(validated){
				if(<?php echo count($user_array);?> > 0){
					if(confirm("You are going to send MMS to <?php echo count($user_array);?> Users" )){
					   	jQuery('#send_message').submit();
					   	jQuery('.overlay').show();
					}
				}else{
					alert("Ooops!!! We do not have user records to send MMS");
				}
			}
		});
		
		jQuery("select#select_sender").change(function(){
			var get_method = jQuery(this).val();
			if(get_method == 'mms') {
				jQuery(".wp_get_attachment").fadeIn();
			} else {
				jQuery(".wp_get_attachment").fadeOut();
			}
		});
		
		charLeft2();
		jQuery("#" + boxId2).bind('keyup', function() {
			charLeft2();
		});
		jQuery("#" + boxId2).bind('keydown', function() {
			charLeft2();
		});
		jQuery("#" + boxId2).bind('paste', function(e) {
			charLeft2();
		});
		
	});
</script>
<style>
	.loader {
    border: 16px solid #f3f3f3; /* Light grey */
    border-top: 16px solid #3498db; /* Blue */
    border-radius: 50%;
    width: 120px;
    height: 120px;
    animation: spin 2s linear infinite;
    height:1px;
	background:#fff;
	position:absolute;
	width:0;
	top:50%;
	right:50%;
}
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
.overlay{
  position:fixed;
  z-index:99999;
  top:0;
  left:0;
  bottom:0;
  right:0;
  background:rgba(0,0,0,0.9);
  transition: 1s 0.4s;
}
.overlay_text {
	    color: white;
	    top: 65%;
	    left: 43%;
	    position: absolute;
}
</style>
<div class="overlay" style="display: none"><div class="loader"></div><div class="overlay_text">Please wait a while</div></div>
<div class="wrap">
	<h2><?php _e('Send Message', 'jbalvin-wp-sms'); ?></h2>
	<form method="post" action=""  enctype="multipart/form-data" id="send_message" name="send_message" autocomplete="off">
		<table class="form-table">
			<?php wp_nonce_field('update-options');?>
			<tr>
				<td><?php _e('Message Type', 'jbalvin-wp-sms'); ?>:</td>
				<td>
					<select name="message_type" id="select_sender" class="wpsms-value wpsms-group">
						<!-- <option value="sms" <?php if(isset($_POST['message_type']) && $_POST['message_type'] == 'sms' ){ ?> selected="selected"  <?php } ?>id="type_sms"><?php _e('SMS', 'jbalvin-wp-sms'); ?></option> -->
						<option value="mms" <?php if(isset($_POST['message_type']) && $_POST['message_type'] == 'mms' ){ ?> selected="selected" <?php } ?>id="type_mms"><?php _e('MMS', 'jbalvin-wp-sms'); ?></option>
					</select>
					<span><?php echo sprintf(__('<b>%s</b> Users have mobile number.', 'jbalvin-wp-sms'), count($user_array)); ?></span>
				</td>
			</tr>
			<tr>
				<td><?php _e('Message Text', 'wp-sms'); ?>:</td>
				<td>
					<textarea name="wp_get_message" id="wp_get_message" maxlength="140" style="width:350px; height: 200px; direction:ltr;"><?php if(isset($_POST['wp_get_message']) && !empty($_POST['wp_get_message'])){ echo $_POST['wp_get_message']; } else { } ?></textarea><br />
					<?php _e('The remaining words', 'wp-sms'); ?>: <span id="wp_counter" class="number"></span>/<span id="wp_max" class="number"></span><br />
				</td>
			</tr>
			<?php $display_file = 'display: none' ?>
			<?php if(isset($_POST['message_type']) && $_POST['message_type'] == 'mms' ){ $display_file = ''; } ?>
			<tr class="wp_get_attachment" style="<?php echo $display_file?>">
				<td><?php _e('Attachment', 'wp-sms'); ?>:</td>
				<td>
					<input type="file" style="direction:ltr;" id="wp_get_attachment" name="wp_get_attachment" value="">
				</td>
			</tr>
			<tr>
				<td>
					<p class="submit">
						<input type="button" class="button-primary" id="SendMessage" name="SendMessage" value="<?php _e('Send Message', 'jbalvin-wp-sms'); ?>" />
					</p>
				</td>
			</tr>
		</table>
	</form>
</div>
