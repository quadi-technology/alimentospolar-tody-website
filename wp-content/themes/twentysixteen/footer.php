<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

		</div><!-- .site-content -->
</div>
</div>
</div>
		<footer id="colophon" class="site-footer" role="contentinfo">
			  <div class="footer_section clearfix">
			  	  
			  	   <div class="wrapper"> 

			  	        <div class="social_icon">
			  	        	<div class="social_text"><p class="social_title"><?php echo get_field('social_text','options'); ?></p></div>

			  	        	<?php $socials = get_field('icon_repeater','options'); 
			  	        			

			  	        			if($socials) {
                                    
         						?>
			  	        	

			  	        		 <?php foreach($socials as $social) {  ?>

			  	        			<img src="<?php echo $social['image_icon']; ?>" alt="">

			  	        		<?php } ?>
			  	        	

			  	        	<?php } ?>
			  	        </div>

			  	        <div class="terms_and_con_section">
			  	        	  <div class="terms_text"><p class="terms_texts"><?php echo get_field('legal_text','options'); ?></p></div>
			  	        	  <div class="terms_link">
			  	        	  <a href="<?php  echo get_field('terminos_link','options');?>" target="_blank"><?php  echo get_field('terms_and_condition','options');?></a>  <a href="<?php  echo get_field('politicas_de_privaciadad_link','options');?>" target="_blank"><?php  echo get_field('policy','options'); ?></a>
			  	        	  </div>
			  	        </div>
			  	        <div class="footer_logo">
			  	        		<div class="f_logo"><a href=""><img src="<?php echo get_field('footer_logo','options') ?>" alt="footer_logo"></a></div>
			  	        </div>

			  	   </div>

			  </div>
		</footer><!-- .site-footer -->
	</div><!-- .site-inner -->
</div><!-- .site -->

<?php wp_footer(); ?>

<script type='text/javascript' src='<?php echo get_template_directory_uri() ?>/js/enscroll-0.6.2.min.js'></script>
<script>
jQuery(document).ready(function() {
     jQuery('.scrollbox').enscroll();

     jQuery('#dhvc_form_message_60').bind('DOMNodeInserted', function(e) {
     	var message = jQuery('#dhvc_form_message_60').html();
     	var message_array = message.split('$$$');
     	/*alert(message_array[0]+"==="+message_array[1]);*/
     	jQuery('#overlay_form_2 #inner_message .wrapper_inner_1 h2').html(message_array[0]);
     	jQuery('#overlay_form_2 #inner_message .wrapper_inner_1 .msg1').html(message_array[1]);
     	jQuery('#overlay_form_2').show();

     	setTimeout(function(){
	     	jQuery('.dhvc-form-show').attr('style', 'visibility: hidden !important');
     	}, 1000);
     	
	});
      jQuery('#dhvc_form_message_660').bind('DOMNodeInserted', function(e) {
     	var message = jQuery('#dhvc_form_message_660').html();
     	var message_array = message.split('$$$');
     	/*alert(message_array[0]+"==="+message_array[1]);*/
     	jQuery('#overlay_form_3 #inner_message_1 .wrapper_inner_1 h2').html(message_array[0]);
     	jQuery('#overlay_form_3 #inner_message_1 .wrapper_inner_1 .msg2').html(message_array[1]);
     	jQuery('#overlay_form_3').show();
     	setTimeout(function(){
	     	jQuery('.dhvc-form-show').attr('style', 'visibility: hidden !important');
     	}, 1000);
     	
	});
     jQuery('#overlay_form_2').click(function(){
     	jQuery(this).fadeOut();	
     });
     jQuery('#overlay_form_3').click(function(){
     	jQuery(this).fadeOut();	
     });
     jQuery('#overlay_form_4').click(function(){
     	jQuery(this).fadeOut();	
     });
     jQuery('#overlay_form_5').click(function(){
     	jQuery(this).fadeOut();	
     });
});
</script>
  <div id="overlay_form_2">
  		<div id="inner_message">
  			<div class="wrapper_inner_1">
  			 	<h2></h2>
				<div class="msg1"></div>
				<div class="bot_msg">volver al inicio</div>
				<div class="close_btn_cus"></div>
			</div>
  		</div>
  </div>
  <div id="overlay_form_3">
  		<div id="inner_message_1">
  			<div class="wrapper_inner_1">
  			 	<h2></h2>
				<div class="msg2"></div>
				<div class="bot_msg_1">volver al inicio</div>
				<div class="close_btn_cus"></div>
			</div>
  		</div>
  </div>
</body>
</html>
