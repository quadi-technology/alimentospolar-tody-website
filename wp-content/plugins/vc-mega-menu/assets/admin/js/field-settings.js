
var vc_field_settings = function( $context ){

    "use strict";
    var $ = jQuery;

    // Add Color Picker to all inputs that have 'color-field' class
    $('.color-field', $context).each( function(){
        var c = $( this );
        c.wpColorPicker( {
            change: function(event, ui){
                c.val( ui.color.toString() );
                c.trigger( 'change' );
            },
        });
    } );


    // Upload
    var frame = wp.media({
        title: wp.media.view.l10n.addMedia,
        multiple: false,
        library: {type: 'image'},
        //button : { text : 'Insert' }
    });

    $('.item-media', $context ).each( function(){

        var _item = $( this );
        // when remove item
        $( '.vc-remove-button', _item ).on( 'click', function( e ){
            e.preventDefault();

            $( '.image_id, .image_url', _item).val( '' );
            $( '.thumbnail-image', _item ).html( '' );

            $( '.current', _item ).removeClass( 'show' ).addClass( 'hide' );

            $('.upload-button', _item ).text( $('.upload-button', _item ).attr( 'data-add-txt' ) );
            $( '.image_id', _item).trigger( 'change' );
            _item.removeClass( 'has-img' );

        } );

        // when upload item
        $('.thumbnail-image, .vc-add-button', _item ).on('click', function () {
            var btn = $( this );

            frame.on('select', function () {
                // Grab our attachment selection and construct a JSON representation of the model.
                var media_attachment = frame.state().get('selection').first().toJSON();
                // media_attachment= JSON.stringify(media_attachment);

                $( '.image_id', _item ).val(media_attachment.id);
                var preview, img_url;
                img_url = media_attachment.url;

                $( '.current', _item ).removeClass( 'hide').addClass( 'show' );

                $( '.image_url', _item ).val(img_url);
                preview = '<img src="' + img_url + '" alt="">';
                //$(' img', _item).remove();
                $( '.thumbnail-image', _item ).html( preview );
                $( '.image_id', _item).trigger( 'change' );
                _item.addClass( 'has-img' );

                btn.text( btn.attr( 'data-change-txt' ) );

            });

            frame.open();

        });

    } );



    var setup_icon_picker =  function( $_context, $input ){
        // Live search Icon
        $( '.vc-mm-icon-popup', $_context ).each( function(){
            var pp = $( this );
            $('.icons-search-input', pp ).on( 'keyup', function(){
                var v =  $( this).val();

                if ( v != '' ){
                    v = v.toLocaleLowerCase();
                    $( '.fip-icons-container .fip-box', pp ).addClass( 'hide' );
                    $( '.fip-icons-container .fip-box[data-value*="'+v+'"]',  pp ).removeClass( 'hide' );

                } else {
                    $( '.fip-icons-container .fip-box', pp ).removeClass( 'hide' );
                }

            } );

            // current-icon
            $( '.fip-icons-container .fip-box', pp ).on( 'click', function(){
                $( '.fip-icons-container .fip-box', pp).removeClass( 'current-icon' );
                $( this).addClass( 'current-icon' );
                //$( '.vc-mm-icon-name', pp ).val( $( this).attr( 'data-value' ) );
                //$( '.vc-mm-icon-type', pp ).val( $( this).attr( 'data-icon-type' ) );
                $input.val(  $( this).attr( 'data-value' ) );
                $( '.selected-icon', pp ).html( $( this).html() );
                return false;
            } );

            // toggle-list
            $( '.toggle-list', pp ).on( 'click', function(){
                var btn = $( this );
                if ( btn.hasClass('closed') ){
                    btn.html( '<i class="fip-fa fa fa-arrow-up"></i>' );
                    $( '.selector-popup', pp).slideDown();
                    btn.removeClass( 'closed' );
                }else {
                    btn.addClass( 'closed');
                    btn.html( '<i class="fip-fa fa fa-arrow-down"></i>' );
                    $( '.selector-popup', pp).slideUp();
                }
            } );

            // remove

            $( '.remove', pp ).on( 'click', function(){
               // $( '.vc-mm-icon-name', pp ).val( '' );
               // $( '.vc-mm-icon-type', pp ).val( '' );
                $input.val('');
                $( '.selected-icon', pp).html( '' );
                return false;
            } );
        });
    };
    // Icon Picker
    var icon_picker_tpl = $( '#vc_icon_picker_tpl').html();
    $( '.vc_icon_picker', $context).each( function(){
        var picker = $( icon_picker_tpl );
        picker.insertAfter( $( this ) );
        setup_icon_picker( picker, $( this ) );
        var current_icon = $( this).val();

        if ( current_icon != '' ){
            $( '.selected-icon', picker).html( '<i class="'+current_icon+'"></i>' );
        }

    });



};





function vc_nav_menu_settings( nav_settings ){

    var $ = jQuery;

    $( '#update-nav-menu .menu-settings').append( nav_settings );

    $( 'input.vc_enable_mega', nav_settings ).on( 'change', function(){
        if ( $( this).attr("checked") === "checked" ) {
            $( '.vc_mega_settings', nav_settings).show();
            $( '.vc_show_on_mega_enable', nav_settings).show();
            $( 'body').addClass('vc_body_admin_nav_enabled');

        } else {
            $( '.vc_mega_settings', nav_settings).hide();
            $( 'body').removeClass('vc_body_admin_nav_enabled');
            $( '.vc_show_on_mega_enable', nav_settings).hide();
        }
    } );

    $( 'input.vc_enable_mega', nav_settings).trigger( 'change' );


    $( 'select.vc_display', nav_settings ).on( 'change', function(){
        var v = $( this).val();
        if ( v !== 'h' ){
            $( '.vc_tab_layout', nav_settings).show();
            $( '.dd_vc_enable_sticky', nav_settings  ).hide();
            $( '.dd_vc_add_home_mobile', nav_settings  ).hide();
        } else {
            $( '.vc_tab_layout', nav_settings).hide();
            $( '.dd_vc_enable_sticky', nav_settings ).show();
            $( '.dd_vc_add_home_mobile', nav_settings ).show();
        }
    } );

    $( 'select.vc_display', nav_settings).trigger( 'change' );

    $( '.vc-mm-toggle-more-settings', nav_settings ).on( 'click', function( e ) {
        e.preventDefault();
        $( '.vc-more-nav-settings', nav_settings ).toggleClass( 'vc-nav-s-show' );

    } );
}











