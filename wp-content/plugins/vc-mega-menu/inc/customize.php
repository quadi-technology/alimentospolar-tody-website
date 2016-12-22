<?php

global $vc_mm_customize_data;
$vc_mm_customize_data = array();


class VC_Mega_Menu_Customize {

    function __construct() {
        /**
         * Fires after Customize settings have been saved.
         *
         * @since 3.6.0
         *
         * @param WP_Customize_Manager $this WP_Customize_Manager instance.
         */
        add_action( 'customize_save_after', array( $this, 'save' ) );

        if ( isset( $_REQUEST['wp_customize'] ) && $_REQUEST['wp_customize'] == 'on' ){
            $this->preview();
        }

    }

    function filter_nav_css( $css, $menu_id ){
        $key = 'vc_mm_get_nav_css_'.$menu_id;
        global $vc_mm_customize_data;
        if( isset( $vc_mm_customize_data[ $key ] ) ){
            return $vc_mm_customize_data[ $key ];
        }

        return $css;
    }

    function filter_nav_settings( $settings, $menu_id ){
        $key = 'vc_nav_settings_'.$menu_id;
        global $vc_mm_customize_data;
        if( isset( $vc_mm_customize_data[ $key ] ) ){
            return $vc_mm_customize_data[ $key ];
        }
        return $settings;
    }

    function filter_item_settings( $settings, $menu_id ){
        $key = 'vc_mm_item_settings_'.$menu_id;
        global $vc_mm_customize_data;
        if( isset( $vc_mm_customize_data[ $key ] ) ){
            return $vc_mm_customize_data[ $key ];
        }
        return $settings;
    }

    function filter_item_content( $settings, $menu_id ){
        $key = 'vc_mm_item_content_'.$menu_id;
        global $vc_mm_customize_data;
        if( isset( $vc_mm_customize_data[ $key ] ) ){
            return $vc_mm_customize_data[ $key ];
        }
        return $settings;
    }

    function filter_item_css( $settings, $menu_id ){
        $key = 'vc_mm_item_css_'.$menu_id;
        global $vc_mm_customize_data;
        if( isset( $vc_mm_customize_data[ $key ] ) ){
            return $vc_mm_customize_data[ $key ];
        }
        return $settings;
    }


    function preview(){
        $data =  $_REQUEST;
        $data = stripslashes_deep( $data );

        if ( ! isset( $data['customized'] ) ) {
            return ;
        }
        $data =  $data['customized'];

        $data = json_decode( $data , true );
        foreach( $data as $key => $_data ){
            if ( strpos( $key, 'nav_menu_item' ) !== false ) {
                $item_id =  preg_replace("/[^0-9]/","", $key );
                $this->save_menu_item( $_data, $item_id, true );
            } else if ( strpos( $key, 'nav_menu' ) !== false ) {

                $menu_id =  preg_replace("/[^0-9]/","", $key );
                $this->save_nav_menu( $_data, $menu_id , true );
            }
        }

    }

    function save( $customize ){
        $data =  $_REQUEST;
        $data = stripslashes_deep( $data );

        $data =  $data['customized'];
        $data = json_decode( $data , true );

        foreach( $data as $key => $_data ){
            if ( strpos( $key, 'nav_menu_item' ) !== false ) {
                $item_id =  preg_replace("/[^0-9]/","", $key );
                $this->save_menu_item( $_data, $item_id );
            } else if ( strpos( $key, 'nav_menu' ) !== false ) {
                $menu_id =  preg_replace("/[^0-9]/","", $key );
                $this->save_nav_menu( $_data, $menu_id, false );
            }
        }
    }

    function save_menu_item( $data , $item_id, $preview = false ){

        global $VC_Mega_Menu_Admin;
        $_d = $VC_Mega_Menu_Admin->save( $data['vc_mm_data'], $preview );
        if ( $preview ) {
            global $vc_mm_customize_data;
            $key = 'vc_mm_item_settings_'.$item_id;
            $vc_mm_customize_data[ $key ] = $_d[ 'settings' ];

            $key = 'vc_mm_item_content_'.$item_id;
            $vc_mm_customize_data[ $key ] = $_d[ 'content' ];

            $key = 'vc_mm_item_css_'.$item_id;
            $vc_mm_customize_data[ $key ] = $_d[ 'css' ];

            add_filter( 'vc_mm_get_item_settings', array( $this, 'filter_item_settings' ), 1000, 2 );
            add_filter( 'vc_mm_get_mega_content', array( $this, 'filter_item_content' ), 1000, 2  );
            add_filter( 'vc_mm_get_item_css', array( $this, 'filter_item_css' ) , 1000, 2 );
        }

    }

    function save_nav_menu( $data, $menu_id , $preview = false ){
        if ( ! is_array( $data ) ){
            return  false;
        }

        if ( ! isset( $data['vc_nav_settings'] ) ) {
            return false;
        }

        $settings =   $data['vc_nav_settings'];
        $settings = wp_parse_args( $settings, array() );

        global $VC_Mega_Menu_Admin;
        $_data =  $VC_Mega_Menu_Admin->update_nav_menu( $menu_id, $settings, $preview );
        if ( $preview && $_data ) {
            global $vc_mm_customize_data;
            $vc_mm_customize_data[ 'vc_mm_get_nav_css_'.$menu_id ] = $_data['css'];
            $vc_mm_customize_data[ 'vc_nav_settings_'.$menu_id ] = $_data['settings'];
            add_filter( 'vc_mm_nav_css', array( $this, 'filter_nav_css' ), 1000, 2 );
            add_filter( 'vc_mm_get_nav_settings', array( $this, 'filter_nav_settings' ), 1000, 2 );
        }

    }


}


new VC_Mega_Menu_Customize();
