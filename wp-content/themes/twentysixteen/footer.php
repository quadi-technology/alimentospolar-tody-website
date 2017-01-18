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
                  <div class="footer_logo desk-m">
                      <div class="f_logo"><a href=""><img src="<?php echo get_field('footer_logo','options') ?>" alt="footer_logo"></a></div>
                  </div>
                  <div class="social_icon">
                    <div class="social_text"><p class="social_title"><?php echo get_field('social_text','options'); ?></p></div>

                    <?php $socials = get_field('icon_repeater','options'); 
                        

                        if($socials) {
                                    
                    ?>
                    

                       <?php foreach($socials as $social) {  ?>

                        <a href="<?php echo $social['image_icon_link']; ?>"><img src="<?php echo $social['image_icon']; ?>" alt=""></a>

                      <?php } ?>
                    

                    <?php } ?>
                  </div>

                  <div class="terms_and_con_section">
                      <div class="terms_text"><p class="terms_texts"><?php echo get_field('legal_text','options'); ?></p></div>
                      <div class="terms_link">
                      <a href="<?php  echo get_field('terminos_link','options');?>" target="_blank"><?php  echo get_field('terms_and_condition','options');?></a>  <a href="<?php  echo get_field('politicas_de_privaciadad_link','options');?>" target="_blank"><?php  echo get_field('policy','options'); ?></a>
                      </div>
                  </div>
                  <div class="footer_logo desk-f">
                      <div class="f_logo"><a href=""><img src="<?php echo get_field('footer_logo','options') ?>" alt="footer_logo"></a></div>
                  </div>

             </div>

        </div>
    </footer><!-- .site-footer -->
  </div><!-- .site-inner -->
</div><!-- .site -->

<?php wp_footer(); ?>

<script type='text/javascript' src='<?php echo get_template_directory_uri() ?>/js/enscroll-0.6.2.min.js'></script>
<script type='text/javascript' src='<?php echo get_template_directory_uri() ?>/js/select2.full.min.js'></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() ?>/css/select2.min.css">

<!-- <script type='text/javascript' src='/js/enscroll-0.6.2.min.js'></script>
<script type='text/javascript' src='/js/select2.full.min.js'></script>
<link rel="stylesheet" type="text/css" href="/css/select2.min.css"> -->
 
<script>

jQuery(document).ready(function() {
  //  jQuery('.f1 .dhvc-form-input input').focusin( function() {
  //     jQuery('.f1  span#dhvc_form_control_name-error').hide();
  // });
  // jQuery('.f1 .dhvc-form-input input').focusout( function() {
  //     jQuery('.f1 span#dhvc_form_control_name-error').hide();
  // });
      jQuery(".add-class-select .dhvc-form-select .dhvc-form-control-city").addClass("js-example-basic-multiple");
      jQuery(".add-class-select .dhvc-form-select .dhvc-form-control-city.js-example-basic-single").select2();
     
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
   
     jQuery('#overlay_form_4').click(function(){
      jQuery(this).fadeOut(); 
     });
     jQuery('#overlay_form_5').click(function(){
      jQuery(this).fadeOut(); 
     });

     jQuery(".dhvc-form-submit").bind( "click", function(){
      setTimeout(validate_form_msg, 500);
     });
});

function validate_form_msg(){
  jQuery('input.dhvc-form-error').each(function(index, value) {
    var message = jQuery("#"+value.id+"-error").html();
    /*jQuery("#"+value.id+"-error").hide();.css('visibility', 'hidden !important');*/
    jQuery("#"+value.id+"-error").html('');
    value.placeholder = message;
  });

  jQuery('input.dhvc-form-valid').each(function(index, value) {
    jQuery("#"+value.id+"-error").show();
    jQuery("#"+value.id+"-error").removeClass('dhvc-form-error').addClass('dhvc-form-valid');
  });
}
</script>
  <div id="overlay_form_2">
      <div id="inner_message">
        <div class="wrapper_inner_1">
          <h2></h2>
        <div class="msg1"></div>
        <div class="bot_msg"><a href="http://tody.stage.qdata.io">volver al inicio</a></div>
        <div class="close_btn_cus"></div>
      </div>
      </div>
  </div>
  <div id="overlay_form_3">
      <div id="inner_message_1">
        <div class="wrapper_inner_1">
          <h2></h2>
        <div class="msg2"></div>
        <div class="bot_msg_1"><a href="http://tody.stage.qdata.io">volver al inicio</a></div>
        <div class="close_btn_cus"></div>
      </div>
      </div>
  </div>
</body>
</html>
