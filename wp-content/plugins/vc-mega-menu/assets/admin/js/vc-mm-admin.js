/**
 * Created by truongsa on 12/3/15.
 */
// alert( 'ok' );


//------------------------------------------------
window.vc_mm_item_title = '';
window.currentEditItem = {};

//console.log( vc_menu_items_settings );

var vcMenuEdit = function( $item ){
    var that = this;
    var $ = jQuery;
    that.$item = $item;


    that.tpl = $(  $( '#vc-mm-popup-tpl').html() );

    that.navId      = $( '#update-nav-menu #menu' ).val();
    that.menuId     = $( 'input.menu-item-data-db-id', $item ).val();

    that.settings =  false;

    if ( typeof vc_menu_items_settings[ that.menuId ] !== "undefined" ){
        that.settings = vc_menu_items_settings[ that.menuId ];
    }

    // set icon
    if( that.settings ) {
        if (that.settings.options.icon !== '') {
            $(".item-title", $item).prepend('<span class="vc-mm-item-icon"><i class="' + that.settings.options.icon + '"></i></span>');
        }

        if ( that.settings.options.enable === 1 || that.settings.options.enable === '1'  ){
            $item.addClass( 'vc-mm-active' );
        } else {
            $item.removeClass( 'vc-mm-active' );
        }

    }

    that.autoFrameHeight = function(){

        if ( $( window).width( ) < 800 ) {
            that.tpl.css( {
                'left': ( 20 )+'px',
                'top': ( $( '#wpadminbar').height() + 20 )+'px',
            } );
        } else {
            that.tpl.css( {
                'left': ( $( '#adminmenuback').width() + 20 )+'px',
                'top': ( $( '#wpadminbar').height() + 20 )+'px',
            } );
        }


        $( '.vc-mm-popup').each( function(){
          //  var ah = that.actions.height();
            var h = that.tpl.height();
            $( 'iframe', that.tpl ).eq( 0 ).height ( h - 30 );
        } );

    };

    that.setPopupTitle = function(){
        window.vc_mm_item_title = $( '.edit-menu-item-title', $item).val() ;
    };

    that.openPopup = function( $item ){

        window.currentEditItem = that;

        if ( $( 'iframe', that.tpl ).length > 0 ){

        } else {
            var url ;

            url = vc_mm_admin_object.new_post+'&vc_menu_item_id='+that.menuId;

            that.tpl.append( '<iframe id="vc-mm-item-frame-'+that.menuId+'" src="'+url+'" class="vc-mm-iframe"></iframe>' );
        }

        that.frame       = $( 'iframe', that.tpl ).eq( 0 );
        that.tpl.attr( 'id', 'vc-mm-menu-id-'+ that.menuId );

        //console.log( 'clicked' );
        $( '#vc-mm-overlay' ).remove();
        $( 'body').append( '<div id="vc-mm-overlay"></div>' );

        $( '#vc-mm-overlay').on( 'click', function(){
            that.hidePopup();
            return false;
        } );

        // Hide all other popup
        $( 'body .vc-mm-popup').hide();

        that.setPopupTitle();

        if ( $( '#vc-mm-menu-id-'+ that.menuId).length > 0 ){
            that.autoFrameHeight();
        } else {

            that.autoFrameHeight();
            $( 'body' ).append( that.tpl );

            that.autoFrameHeight();

            $( window ).resize( function(){
                that.autoFrameHeight();
            } );

        }

        // set title
        that.tpl.show();

    };

    that.hidePopup = function(){
        that.tpl.hide();
        $( '#vc-mm-overlay').remove();
    };


    //Open settings
    $( '.vc-mm-item-settings', $item ).on( 'click', function(){

        that.openPopup( $item );
        return false;
    } );


};


var vcAddSettingsBtn = function( $item ){
    if ( jQuery( '.vc-mm-item-settings', $item ).length === 0 ) {
        jQuery( $item ).find('.item-title').append('<span class="vc-mm-item-settings"><span class="">'+vc_mm_admin_object.mm+'</span></span>');
        new vcMenuEdit( $item );
    }
};

var vcAddSettingsBtnItems = function(  ){
    // add setting for each menu item
    jQuery( 'li', wpNavMenu.menuList ).each( function(){
        vcAddSettingsBtn( jQuery( this ) );
    } );
};


//-----------------------------------
// wpNavMenu.menusChanged

wpNavMenu.addItemToMenu = function(menuItem, processMethod, callback) {
    var $ = jQuery;
    var menu = $('#menu').val(),
        nonce = $('#menu-settings-column-nonce').val(),
        params;

    processMethod = processMethod || function(){};
    callback = callback || function(){};

    params = {
        'action': 'add-menu-item',
        'menu': menu,
        'menu-settings-column-nonce': nonce,
        'menu-item': menuItem
    };

    $.post( ajaxurl, params, function(menuMarkup) {
        var ins = $('#menu-instructions');

        menuMarkup = $.trim( menuMarkup ); // Trim leading whitespaces
        processMethod(menuMarkup, params);

        // Make it stand out a bit more visually, by adding a fadeIn
        $( 'li.pending' ).hide().fadeIn('slow');
        $( '.drag-instructions' ).show();
        if( ! ins.hasClass( 'menu-instructions-inactive' ) && ins.siblings().length ) {
            ins.addClass( 'menu-instructions-inactive' );
        }

        // Jus add a settings icon to mega menu
        vcAddSettingsBtnItems( $ );

        callback();
    });
};


jQuery( document ).ready( function( $ ){
    vcAddSettingsBtnItems();

    $( 'form#update-nav-menu').submit( function(){
        $( 'body .vc-mm-popup').remove();
    } );
    // Add settings

    var nav_settings = $( $( '#vc-nav-settings').html() );
    if ( nav_settings ) {

        vc_field_settings( nav_settings );
        vc_nav_menu_settings( nav_settings );

        if ( typeof wpNavMenu !== "undefined" ){
            wpNavMenu.menusChanged = false;
        }

    }

    // add Import button

    $( '.nav-menus-php .nav-tab-wrapper').append( '<a class="nav-tab vc-mm-import-btn" href="#"><span class="dashicons dashicons-migrate"></span> <span class="spinner is-active"></span> '+vc_mm_admin_object.import_txt+'</a>' );
    $( '.nav-menus-php .nav-tab-wrapper').on( 'click', '.vc-mm-import-btn', function( e ) {
        e.preventDefault();

        var c = confirm( vc_mm_admin_object.confirm_import );
        if ( c ) {
            var btn = $(this);
            $('.dashicons', btn).css('display', 'none');
            $('.spinner', btn).css('display', 'inline-block');

            var params = {
                'action': 'vc_mm_import_menus',
                '_nonce': vc_mm_admin_object._nonce
            };

            $.post(ajaxurl, params, function ($data) {

                $('.dashicons', btn).css('display', 'inline-block');
                $('.spinner', btn).css('display', 'none');

                window.location = vc_mm_admin_object.menu_url;

                if (typeof wpNavMenu !== "undefined") {
                    wpNavMenu.menusChanged = false;
                }

            });
        }

    } );



} );


window['vc_mm_data_item_changes'] = function( data, respond ){

    //console.log( window.currentEditItem );

    window.currentEditItem.tpl.hide();

    if( ! respond.is_update ) {
        jQuery( 'iframe', window.currentEditItem.tpl ).remove();
    }
    jQuery( '#vc-mm-overlay').remove();

    vc_menu_items_settings[ window.currentEditItem.menuId ] = {
        url:  respond.url,
        options : respond.settings
    };

    window.currentEditItem.settings = {
        url:  respond.url,
        options : respond.settings
    };

    jQuery( '.vc-mm-item-icon', window.currentEditItem.$item ).remove();

    if ( respond.settings.icon !== '' ) {
        jQuery( ".item-title" , window.currentEditItem.$item ).prepend( '<span class="vc-mm-item-icon"><i class="'+respond.settings.icon+'"></i></span>' );
    }

    if ( respond.settings.enable === 1 || respond.settings.enable === "1" ){
        window.currentEditItem.$item.addClass( 'vc-mm-active' );
    } else {
        window.currentEditItem.$item.removeClass( 'vc-mm-active' );
    }

    if ( typeof wpNavMenu !== "undefined" ){
        wpNavMenu.menusChanged = false;
    }




};

