/**
 * Created by truongsa on 12/3/15.
 */

jQuery ( document ).ready ( function( $ ){

    $( '.vc_navbar-nav li #vc_fullscreen-button').parent().hide();
    $( '#vc_post-settings-button').attr( 'title', vc_mm_post_settings.item_settings).parent().addClass( 'vc-show-whatever' );
    $( '.vc_navbar .vc_navbar-nav').append( '<li class="vc-show-whatever vc-on-off-mega">'+ $( '#vc_item_toggle_enable').html() +'</li>' );
    $( '#vc_ui-panel-post-settings .vc_edit_form_elements').hide();
    var setting_html = $( $( '#vc_item_settings').html() );
    $( '#vc_ui-panel-post-settings .vc_ui-panel-content-container').append( setting_html );

    var save_btn, save_li;
    //vc_pull-right
    if ( $( '#wpb-edit-inline').length <= 0 ){
        save_li =  $(  '<li class="vc-show-whatever vc_pull-right"><a id="wpb-edit-inline" class="vc_btn vc_btn-primary vc_btn-sm vc_navbar-btn" href="#">'+vc_mm_post_settings.save_label+'</a></li>' );
        $( '.vc_navbar .vc_navbar-nav').append( save_li );
        save_btn =  $( '#wpb-edit-inline', save_li );
    } else {
        save_btn =  $( '#wpb-edit-inline');
        save_btn.parent().addClass( 'vc-show-whatever' );
    }

    save_btn.html( vc_mm_post_settings.save_label );
    save_btn.attr( 'href', '#').css( 'display', 'inline-block' );

    // Change Settings label
    $( '#vc_ui-panel-post-settings  .vc_ui-panel-header-heading').html( vc_mm_post_settings.setting_label );

    // Add tabs
    var tabs = $( $( '#vc_item_settings_tabs').html( ) );
    tabs.insertAfter( "#vc_ui-panel-post-settings .vc_ui-panel-header-header" );
    tabs.on( 'click', '.vc_edit-form-tab-control', function( e ) {
        //alert( 'clicked' );
        e.preventDefault();
        var li = $( this );
       $( '.vc_edit-form-tab-control', tabs).removeClass( 'vc_active' );
        li.addClass( 'vc_active' );
        if ( li.hasClass( 'vc-mm-to-design' ) ) {
            $( '.vc-mm-tab-general', setting_html ).hide();
            $( '.vc-mm-tab-design', setting_html ).show();
        } else {
            $( '.vc-mm-tab-general', setting_html ).show();
            $( '.vc-mm-tab-design', setting_html ).hide();
        }
    } );

    // Remove setting close button
    $( '#vc_ui-panel-post-settings .vc_ui-panel-footer [data-vc-ui-element="button-close"]').remove();

    // When hit save change page settings btn
    $( '#vc_ui-panel-post-settings [data-vc-ui-element="button-save"]').on( 'click', function(){
        //console.log( 'save-settings-change' );
    } );

   vc_field_settings( setting_html );


    $('.vc-mm-loading').remove();

    if ( typeof vc!== "undefined" ) {

       // console.log( vc );

        Shortcodes = vc.shortcodes;

        var parentWindow = window.parent || window.top;
        if (self !== parentWindow) {

            if ( typeof parentWindow.vc_mm_editing !== "undefined" &&  parentWindow.vc_mm_editing === 'customize' ) {
                $( '.vc_btn-sm', save_li ).html( vc_mm_post_settings.done_label );
            }

            // Save change data
            function save_change() {
                var _data = $('#post').serializeObject();
                var data = {};

                data.content = _data.content;
                data.vc_menu_item_id = _data.vc_menu_item_id;
                data._vc_settings = $('#vc-menu-settings-form').serializeObject();
                data._vc_settings.enable = $('#vc-mm-item-enable').attr('checked') ? 1 : 0;

                if ( typeof parentWindow.vc_mm_editing !== "undefined" &&  parentWindow.vc_mm_editing === 'customize' ) {

                    var settingValue = parentWindow.vc_current_control.setting();
                    settingValue = _.clone( settingValue );
                    settingValue[ 'vc_mm_data' ] = data;
                    parentWindow.vc_current_control.setting.set( settingValue );
                    if ( data._vc_settings.enable === 1 ) {
                        parentWindow.vc_current_control.container.addClass( 'vc-mm-active' );
                    } else {
                        parentWindow.vc_current_control.container.removeClass( 'vc-mm-active' );
                    }
                    parentWindow.vc_current_item_panel.addClass( 'cz-vc-hide' );

                    parentWindow.wp.customize.previewer.refresh();

                } else {

                    data.action = 'save_mm_menu';

                    $.ajax({
                        url: ajaxurl,
                        data: data,
                        type: 'post',
                        dataType: 'html',
                        success: function (res) {
                            parentWindow.vc_mm_data_item_changes(data, JSON.parse(res));
                        }
                    });

                }

            }

            $('.vc_navbar .vc_navbar-nav').append('<li class="menu-label vc-show-whatever"><span>' + parentWindow.vc_mm_item_title + '</span></li>');

            save_btn.on('click', function () {
                save_change();
                return false;
            });
        }

        if (  typeof Shortcodes !== "undefined"  ) {

            Shortcodes.on('sync', function (collection) {

                _.each(Shortcodes.models, function (model) {
                    // vc.events.triggerShortcodeEvents( 'sync', model );
                    if (typeof model.view !== "undefined") {
                        //console.log(  model.view.$el  );
                        $('.content a', model.view.$el).attr('target', '_blank');
                    }

                });

            });
        }

    }


} );
