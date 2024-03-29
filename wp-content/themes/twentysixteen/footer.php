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

<?php
wp_footer();

$check_entry_count = false;
$valid_form_ids = array();
$valid_form_ids[] = 629;    // registro2
$valid_form_ids[] = 655;    // registro3
$current_page_id = get_the_ID();
if(in_array($current_page_id, $valid_form_ids)){
  $check_entry_count = true;
}
?>

<script type='text/javascript' src='<?php echo get_template_directory_uri() ?>/js/enscroll-0.6.2.min.js'></script>
<script type='text/javascript' src='<?php echo get_template_directory_uri() ?>/js/select2.full.min.js'></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() ?>/css/select2.min.css">

<script>

jQuery(document).ready(function() {

     jQuery('.custom_css.close_video .ult-overlay-close').click(function(){
        jQuery('body').removeClass('ult_modal-body-open');
     });
     /* Loder js */
    jQuery('#loading_image_gif').hide();

    /* select2 js */
    jQuery(".add-class-select .dhvc-form-select select.dhvc-form-control-city").select2();

    /* enscroll js */
    jQuery('.scrollbox').enscroll();

    /* Create popup text on success and unsuccess js */
    jQuery('#dhvc_form_message_60').bind('DOMNodeInserted', function(e) {
        var message = jQuery('#dhvc_form_message_60').html();
        var message_array = message.split('$$$');
        /*alert(message_array[0]+"==="+message_array[1]);*/
        jQuery('#overlay_form_2 #inner_message .wrapper_inner_1 h2').html(message_array[0]);
        jQuery('#overlay_form_2 #inner_message .wrapper_inner_1 .msg1').html(message_array[1]);
        jQuery('#overlay_form_2').show();

        setTimeout(function() {
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
        setTimeout(function() {
            jQuery('.dhvc-form-show').attr('style', 'visibility: hidden !important');
        }, 1000);

    });

    /* close popup on click js - only for sent mail options popup's */
    jQuery('#overlay_form_4').click(function() {
        jQuery(this).fadeOut();
    });
    jQuery('#overlay_form_5').click(function() {
        jQuery(this).fadeOut();
    });
    /* validation msg timeout */
    jQuery(".dhvc-form-submit").bind("click", function() {
        setTimeout(validate_form_msg, 500);
    });

    /* prevent close popup on click at overlay - close only at close button and also close video */
    jQuery('.close_video').click(function(e) {
        e.stopPropagation();
        e.preventDefault();

    });

    jQuery('.close_video2').click(function(e) {
        e.stopPropagation();
        e.preventDefault();

    });
     var href = window.location.href;
    jQuery('.close_video .btn a.button_link').click(function(){
          window.location.href= href+'registro1/';
    });
    jQuery('.close_video2 .btn a.button_link').click(function(){
          window.location.href= href+'registro1/';
    });
    jQuery(".close_video .ult-overlay-close").click(function() {

        jQuery(".close_video").removeClass('ult-open');
        var youtubeSrc = jQuery(".close_video .ult_modal-body").find("iframe").attr("src");



        if (jQuery(".close_video .ult_modal-body").find("iframe").length > 0) { // checking if there is iframe only then it will go to next level
            jQuery(".close_video .ult_modal-body").find("iframe").attr("src", ""); // removing src on runtime to stop video
            jQuery(".close_video .ult_modal-body").find("iframe").attr("src", youtubeSrc); // again passing youtube src value to iframe
        }


    });
    jQuery(".close_video2 .ult-overlay-close").click(function() {
        jQuery(".close_video2").removeClass('ult-open');
        var youtubeSrc = jQuery(".close_video2 .ult_modal-body").find("iframe").attr("src");


        if (jQuery(".close_video2 .ult_modal-body").find("iframe").length > 0) { // checking if there is iframe only then it will go to next level
            jQuery(".close_video2 .ult_modal-body").find("iframe").attr("src", ""); // removing src on runtime to stop video
            jQuery(".close_video2 .ult_modal-body").find("iframe").attr("src", youtubeSrc); // again passing youtube src value to iframe
        }

    });

    <?php
    if($check_entry_count){
    ?>
      get_total_entries();
    <?php
    }
    ?>

});
function validate_form_msg(){
  jQuery('input.dhvc-form-error').each(function(index, value) {
    var message = jQuery("#"+value.id+"-error").html();
    /*jQuery("#"+value.id+"-error").hide();.css('visibility', 'hidden !important');*/
    jQuery("#"+value.id+"-error").html('');
    jQuery("span.dhvc-form-valid").hide();
    value.placeholder = message;
  });

  jQuery('input.dhvc-form-valid').each(function(index, value) {
    jQuery("#"+value.id+"-error").show();
    jQuery("#"+value.id+"-error").removeClass('dhvc-form-error').addClass('dhvc-form-valid');
  });
}

function get_total_entries(){
  jQuery.ajax({
    url: '<?php echo admin_url('admin-ajax.php'); ?>',
    type: "POST",
    async: false,
    data: {
      'action':'get_total_entries'
    },
    success: function(resp) {
      var response_obj = jQuery.parseJSON(resp);
      if(response_obj.status == "limit_reached"){
        jQuery('#overlay_form_7').show();
      }
    }
  });
}

</script>
  <div id="overlay_form_2">
      <div id="inner_message">
        <div class="wrapper_inner_1">
          <h2></h2>
        <div class="msg1"></div>
        <div class="bot_msg"><a href="http://tody.stage.qdata.io/">volver al inicio</a></div>
        <div class="close_btn_cus"></div>
      </div>
      </div>
  </div>
  <div id="overlay_form_3">
      <div id="inner_message_1">
        <div class="wrapper_inner_1">
          <h2></h2>
        <div class="msg2"></div>
        <div class="bot_msg_1"><a href="http://tody.stage.qdata.io/">volver al inicio</a></div>
        <div class="close_btn_cus"></div>
      </div>
      </div>
  </div>
  <div id="overlay_form_7" style="display: block">
      <div id="inner_message_12">
        <div class="wrapper_inner_1">
          <h2>-Lo sentimos hemos llegado al limite de registros-</h2>
        <div class="msg7">Pero no te desanimes, un delicioso y Cremosito sabor de Toddy te espera en la tienda mas cercana.</div>
        <div class="bot_msg_12"><a href="http://tody.stage.qdata.io/">volver al inicio</a></div>
        <div class="close_btn_cus"></div>
      </div>
      </div>
  </div>
</body>
</html>
