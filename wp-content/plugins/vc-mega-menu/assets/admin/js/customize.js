/**
 * Created by truongsa on 12/26/15.
 */

window.vc_mm_item_title = '';
window.vc_mm_editing = 'customize';
window.vc_current_control = {};
window.vc_current_item_panel = {};

( function( api ) {

    // console.log( api );
   // console.log( api.controlConstructor['nav_menu_item'].prototype.ready );
   // console.log( vc_menu_items_settings );

    function my_debug( test ){
        jQuery( 'body #my_test').remove();
        jQuery( 'body').append( '<div id="my_test">'+test+'</div>' );
    }


    var vc_mm_customize = function( $el, $ ){
        var self = this;
        self.panel = $( '#customize-controls');
        // collapsed , expanded
        self._resize = function( ){
            var ww =  $( window ).width();
            var pw =  self.panel.width();
            //cosole.log( ww - pw );
            var diff = ww - pw;
           // my_debug( diff );
            if ( diff < 850 ) {
                $( '.wp-full-overlay' ).removeClass( 'expanded').addClass( 'collapsed' );
                $el.width( ww - 20 );
            } else {
                $( '.wp-full-overlay' ).removeClass( 'collapsed').addClass( 'expanded' );
                $el.width( 830 );
            }
        };

        self._resize();
        $( window ).resize( function(){
            //console.log( 'resizing' );
            self._resize();
        } );

    };


    /**
     * Set up UI for adding a new menu item.
     * 2408
     */
    api.controlConstructor['nav_menu'].prototype._setupAddition =  function() {
        var control = self = this;
        var $ = jQuery;


        // check_ajax_referer( 'customize-menus', 'customize-menus-nonce' );
        params = {
            'customize-menus-nonce': api.Menus.data.nonce,
            'wp_customize': 'on',
            'menu_id': control.params.menu_id
        };
        request = wp.ajax.post( 'vc-mm-load-mega-items-settings', params );

        request.done( function( res ) {

            var _s = $( res.menu_settings );
            control.container.append( _s );
            vc_nav_menu_settings( _s );
            vc_field_settings( _s );

            _s.on( 'change keyup',  'input, select, textarea', function(){
                var _data =  $( 'input, select, textarea' , _s).serialize();

                var settingValue = control.setting();
                settingValue = _.clone( settingValue );
                settingValue[ 'vc_nav_settings' ] = _data;
               // console.log( settingValue );
                control.setting.set( settingValue );
                api.previewer.refresh();

            } );

            _.each( res.items_setting, function( settings, item_id ){
                _control = api.control( 'nav_menu_item[' + item_id + ']' );
              //  console.log( settings );
                if ( settings.options.enable === 1 || settings.options.enable === '1' ) {
                    _control.container.addClass( 'vc-mm-active' );
                }
            } );

        } );


        this.container.find( '.add-new-menu-item' ).on( 'click', function( event ) {

            if ( self.$sectionContent.hasClass( 'reordering' ) ) {
                return;
            }

            if ( ! $( 'body' ).hasClass( 'adding-menu-items' ) ) {
                $( this ).attr( 'aria-expanded', 'true' );
                api.Menus.availableMenuItemsPanel.open( self );
            } else {
                $( this ).attr( 'aria-expanded', 'false' );
                api.Menus.availableMenuItemsPanel.close();
                event.stopPropagation();
            }
        } );


    };


    api.controlConstructor['nav_menu_item'].prototype.ready = function () {
        var control = this;
        // console.log( control );

        if ( 'undefined' === typeof this.params.menu_item_id ) {
            throw new Error( 'params.menu_item_id was not defined' );
        }

        this._setupControlToggle();
        this._setupReorderUI();
        this._setupUpdateUI();
        this._setupRemoveUI();
        this._setupLinksUI();
        this._setupTitleUI();


        var btn =  jQuery( '<span class="vc-mm-item-settings">'+vc_mm_admin_object.mm+'</span>' );

        control.container.find( '.item-title').append( btn );

        btn.on( 'click', function( e ){
            e.preventDefault();

            var settingValue = control.setting();
            window.vc_mm_item_title = settingValue.title;
            window.vc_current_control = control;

            var id =  'vc-mm-frame-'+control.params.menu_item_id;

            if ( jQuery( '#'+id, jQuery( 'body' ) ).length > 0 ){
                jQuery( '.cz-vc-mm-frame', jQuery( 'body' )).removeClass( 'cz-vc-show').addClass( 'cz-vc-hide' );
                jQuery( '#'+id, jQuery( 'body' ) ).removeClass( 'cz-vc-hide').addClass( 'cz-vc-show' );
            } else {
                var url = vc_mm_admin_object.new_post + '&vc_menu_item_id=' + control.params.menu_item_id;
                var iframe = jQuery( '<div id="'+id+'" class="cz-vc-mm-frame"><iframe class="cz-vc-mm-frame-ifr" src="'+url+'"></iframe></div>' );
                jQuery(  'body .in-sub-panel' ).append( iframe );
                new vc_mm_customize( iframe, jQuery );
            }

            window.vc_current_item_panel = jQuery( '#'+id, jQuery( 'body' ) );

            return false;

        } );

    };










} )( wp.customize );
