<?php
/*
Plugin Name: Mega Menu for Visual Composer
Plugin URI: http://shrimp2t.com/vcmm
Description: A Mega menu builder for Visual Composer
Version: 1.3.3
Author: Shrimp2t
Author URI: http://shrimp2t.com
*/

define( 'VC_MM_URL', trailingslashit( plugins_url('', __FILE__) ) );
define( 'VC_MM_PATH', trailingslashit( plugin_dir_path( __FILE__) ) );



class VC_MegaMenu {

    function __construct(){
        add_action( 'init', array( $this, 'init' ) );
        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
    }

    /**
     * Load plugin textdomain.
     *
     * @since 1.0.0
     */
    function load_textdomain() {
        load_plugin_textdomain( 'vc_mm', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
    }

    function admin_notice() {
        if ( ! class_exists( 'Vc_Manager' ) ){
            ?>
            <div class="warning notice notice-warning is-dismissible" id="vc-mm-notice">
                <p>
                    <?php printf( __( 'Mega Menu requirements <a target="_blank" href="%1$s">Visual Composer</a>: Page Builder for WordPress Plugin installed', 'vc_mm' ), 'http://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=shrimp2t' ); ?>
                </p>
            </div>
             <?php
        } else if ( version_compare( WPB_VC_VERSION, '4.9' ) < 0 ) {
            ?>
            <div class="warning notice notice-warning is-dismissible" id="vc-mm-notice">
                <p>
                    <?php printf( __( 'Mega Menu requirements at least <a target="_blank" href="%1$s">Visual Composer</a> version 4.9', 'vc_mm' ), 'http://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=shrimp2t' ); ?>
                </p>
            </div>
        <?php
        }

    }


    function init(){

        add_action( 'admin_notices', array( $this, 'admin_notice' ) );

        if (  class_exists( 'Vc_Manager' ) ){
            $this->post_type_init();
            $this->inc();
        } else {

        }
    }

    public static function default_item_settings(){
        return array(
            'item_type'   => '',
            'icon'   => '',
            'enable' => '',
            'layout' => 'full',
            'role'   => '',
            'width'  => '',
            'class'  => '',
            'hide_title' => '',
            'align' => '',
            // colors
            'item_bg' =>'',
            'item_color' =>'',
            'item_hover_bg' =>'',
            'item_hover_color' =>'',
            'item_active_bg' =>'',
            'item_active_color' =>'',

            // Border
            'border_color' => '',
            'border_size'  => '',
            'border_style' => '',

            'border_top'   => '',
            'border_right' => '',
            'border_bottom' => '',
            'border_left'  => '',

            // Content padding
            'content_padding_top'  => '',
            'content_padding_right'  => '',
            'content_padding_bottom'  => '',
            'content_padding_left'  => '',
            // BG
            'bg_image_id'           => '',
            'bg_image_url'          => '',
            'bg_color'              => '',
            'bg_style'              => '',
            'bg_position_top'       => '',
            'bg_position_right'     => '',
            'bg_position_bottom'    => '',
            'bg_position_left'      => '',
            'bg_link'      => '',

        );
    }

    public static function default_nav_settings(){
        return array(
            "enable" => '',
            "skin" => 'black.css',
            "transparent" => '',
            "show_desc" =>'',
            "is_sticky" => '',
            "add_home_mobile" => '',
            "display" => '',
            'tab_layout' => '',
            "bg" => '',
            "color" => '',
            "hover_bg" => '',
            "hover_color" => '',
            "active_bg" => '',
            "active_color" => '',
            "border" => '',
            "border_color" => '',
            "logo_id" => '',
            "h_layout" => '',
        );
    }

    public static function get_skins(){
        if ( ! function_exists( 'list_files' ) ){
           require_once ABSPATH.'/wp-admin/includes/file.php';
        }
        $files = list_files( VC_MM_PATH.'assets/frontend/skins/', 1 );

        $data = array();
        foreach( $files as $file ){
            $ext = explode( '.', $file );
            $ext =  (string) end( $ext );
            if ( strtolower( $ext ) == 'css' ) {
                $data[ basename( $file ) ] =  get_file_data(
                    $file,
                    array(
                        'name' => 'Name',
                        'primary_color' => '$primary_color',
                        'secondary_color' => '$secondary_color',
                        'active_color' => '$active_color',
                        'sub_bg' => '$sub_bg',
                        'sub_menu_color' => '$sub_menu_color',
                        'sub_menu_link_color' => '$sub_menu_link_color'
                    )
                );
            }
        }

        return apply_filters( 'vc_mm_get_skins', $data );
    }

    /**
     * Register a book post type.
     *
     * @link http://codex.wordpress.org/Function_Reference/register_post_type
     */
    function post_type_init() {

        $labels = array(
            'name'               => _x( 'Mega Menu Items', 'post type general name', 'vc_mm' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => false,
            'query_var'          => false,
            'rewrite'            => array( 'slug' => 'vc-mm' ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'can_export'         => false,
            'supports'           => array( 'title', 'editor' )
        );

        register_post_type( 'vc_mega_menu', $args );
    }

    /**
     * Include function lib
     */
    function inc(){
        require_once VC_MM_PATH.'inc/admin-nav.php';
        if ( is_admin() ){
            require_once VC_MM_PATH.'inc/import-export.php';
        } else {
            require_once VC_MM_PATH.'inc/nav-walker.php';
            add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_front_end' ), 99  );
        }

        require_once VC_MM_PATH.'inc/vc-shortcodes.php';
    }


    /**
     * Filter if Nav menu is activated then change the menu Walker Class
     *
     * @param $args
     * @return array
     */
    function nav_menu_front_end( $args ){

        if ( is_admin() ){
            return $args;
        }

        if ( (isset( $args['theme_location'] ) && $args['theme_location'] != '') || isset( $args['menu'] ) ) {

            // Get the nav menu based on the requested menu
            $menu = wp_get_nav_menu_object( $args['menu'] );

            // Get the nav menu based on the theme_location
            if ( ! $menu && $args['theme_location'] && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $args['theme_location'] ] ) ) {
                $menu = wp_get_nav_menu_object( $locations[ $args['theme_location'] ] );
            }
            if ( ! is_object( $menu ) ) {
                return $args;
            }

            $nav_settings = apply_filters( 'vc_mm_get_nav_settings', get_term_meta( $menu->term_id, '_vc_mm_settings', true ), $menu->term_id ) ;

            $nav_settings = wp_parse_args( $nav_settings,  self::default_nav_settings() );

            if ( $nav_settings['enable']  == 1 ) {

                $mobile_btn = '<li class="vc-menu-item vc-mm-mobile-toggle">
                                <a href="#" class="nav-link vc-mm-mobile-toggle-btn"><i class="fa fa-bars"></i></a>
                                <h3 class="vc-mm-child-title lv-0">
                                    <span>'.esc_html( $menu->name ).'</span><a class="vc-close" href="#"></a>
                                </h3>
                            </li>';

                $home_icon = '';
                if ( $nav_settings['add_home_mobile'] == 1 ){
                    $home_icon = '<li class="vc-menu-item  mobile-btn"><a class="nav-link" href="'.esc_url( home_url('/') ).'"><i class="fa fa-home"></i></a> </li>';
                }

                $menu_id = 'vc-nav-id-'. $menu->term_id;


                switch( $nav_settings['display'] ) {
                    case 'v':

                        if ( $nav_settings['tab_layout'] == '' ){
                            $nav_settings['tab_layout'] = 'align';
                        }

                        $items_wrap = '<div id="'.esc_attr( $menu_id ).'-wrapper" class="vc-mm-menu-v vc-mm-container">';
                            $items_wrap .= '<ul id="%1$s-mobile" class="vc-nav-on-mobile %2$s"><li class="vc-mobile-title"><span>'.esc_html( $menu->name ).'</span></li>' . $mobile_btn . '</ul>';
                            $items_wrap .= '<ul id="%1$s" data-menu-type="v" data-tab-layout="'.esc_attr( $nav_settings['tab_layout'] ).'" class="vc-nav-on-desktop %2$s">' . $mobile_btn . '%3$s</ul>';
                        $items_wrap .= '</div>';

                        break;
                    case 'd':


                        if ( $nav_settings['tab_layout'] == '' ){
                            $nav_settings['tab_layout'] = 'full';
                        }

                        $items_wrap = '<div id="'.esc_attr( $menu_id ).'-wrapper" class="vc-mm-menu-v vc-mm-drop-down is vc-mm-container">
                                    <a href="#" class="vc-mm-mobile-toggle vc-btn"><i class="fa fa-bars"></i> '.esc_html( $menu->name ).'</a>
                                    <a class="vc-drop-down-btn" href="#">'.esc_html( $menu->name ).'</a>
                                    <div class="vc-drop-down-wrapper">
                                        <ul id="%1$s" data-menu-type="v" data-tab-layout="'.esc_attr( $nav_settings['tab_layout'] ).'" class="vc-nav-on-desktop %2$s">' . $mobile_btn . '%3$s</ul>
                                    </div>
                                    </div>';
                        break;
                    default:

                        if ( $home_icon =='' ){
                            $home_icon = '<li class="vc-mobile-title"><span>'.esc_html( $menu->name ).'</span>';
                        }

                        $logo_url = '';
                        $logo = '';

                        if ( $nav_settings['h_layout'] ==1 || $nav_settings['h_layout'] == 2 ) {
                            if ( $nav_settings['logo_id'] ) {
                                $logo_url = wp_get_attachment_url( $nav_settings['logo_id'], '' );
                            }

                            if ( $logo_url != '' ) {
                                $logo = '<li class="vc-mm-li-logo"><a href="'.home_url('/').'" class="vc-mm-logo"><img alt="" src="'.esc_url( $logo_url ).'"></a></li>';
                                $home_icon = $logo;
                            }
                        }


                        $classes = array(
                            'vc-mm-menu-h ', //
                            'vc-mm-h-layout-'. $nav_settings['h_layout'],
                            'vc-mm-container'
                        );
                        if ( $nav_settings['is_sticky'] == 1 ){
                            $classes[] = 'vc-sticky';
                        }
                        if ( $nav_settings['transparent'] == 1 ){
                            $classes[] = ' vc-transparent';
                        }

                        $items_wrap = '<div id="'.esc_attr( $menu_id ).'-wrapper" class=" '.esc_attr( join( ' ', $classes ) ).' ">
                                    <ul id="%1$s-mobile" class="vc-nav-on-mobile %2$s">' .$home_icon . $mobile_btn . '</ul>
                                    <ul id="%1$s" data-menu-type="h" class="vc-nav-on-desktop %2$s">' .$logo . $mobile_btn . '%3$s</ul>
                                    </div>';
                        break;
                }



                $args = array(
                    'theme_location' => $args['theme_location'],
                    'menu' => $args['menu'],
                    'container' => '',
                    // 'container' => 'div',
                    'container_class' => '',
                    //'container_class' => 'vc-mm-menu-h vc-mm-container',
                    'container_id' => '',
                    'menu_class' => 'vc-mm-menu',
                    //'menu_class' => 'vc-mm-menu',
                    'menu_id' => $menu_id,
                    'echo' => true,
                    'fallback_cb' => 'wp_page_menu',
                    'before' => '',
                    'after' => '',
                    'link_before' => '',
                    'link_after' => '',
                    'items_wrap' => apply_filters( 'vc_nav_items_wrap', $items_wrap, $nav_settings, $menu ),
                    'depth' => 0,
                    'walker' => new VC_MM_Walker_Nav_Menu()
                );

                $css = '';

                if (is_object($menu)) {
                    $css .= apply_filters( 'vc_mm_get_nav_css', get_term_meta( $menu->term_id, '_vc_mm_css', true ), $menu->term_id );
                    // If the menu exists, get its items.
                    if ($menu && !is_wp_error($menu) && !isset($menu_items)) {
                        $menu_items = wp_get_nav_menu_items( $menu->term_id, array('update_post_term_cache' => false));
                        if ($menu_items) {
                            foreach ($menu_items as $p) {
                                $item_css = apply_filters( 'vc_mm_get_item_css',get_post_meta( $p->ID, '_vc_custom_css', true), $p->ID );
                                $css .= $item_css;
                            }
                        }
                    }
                }

                $css = apply_filters( 'vc_mm_nav_css', $css, $menu->term_id );

                if ($css != '') {
                    ?>
                    <style type="text/css" class="vc-nav-custom-css" id="vc-custom-css-<?php echo esc_attr( $menu->term_id ); ?>" media="all">
                        <?php
                          echo trim( preg_replace( '#<style[^>]*>(.*)</style>#is', '$1', $css ) );
                         ?>
                    </style>
                <?php
                }

                /**
                 * @TODO Enqueue scripts, styles if VC Mega Menu called
                 */
                if ( apply_filters( 'vc_mm_use_js', true ) ) {
                    wp_enqueue_script('jquery');
                    if ( $nav_settings['is_sticky'] == 1 && $nav_settings['display'] == 'h' ){
                        wp_enqueue_script( 'jquery.sticky', VC_MM_URL . 'assets/frontend/sticky.js', array('jquery'));
                    }

                    wp_enqueue_script( 'vc-mm', VC_MM_URL . 'assets/frontend/vc-mm.js', array('jquery'));
                }

                if ( apply_filters( 'vc_mm_use_css', true ) ) {
                    wp_enqueue_style( 'font-awesome', home_url('/').'wp-content/plugins/js_composer/assets/lib/bower/font-awesome/css/font-awesome.min.css' );

                   if ( $nav_settings['display'] == 'v' || $nav_settings['display'] == 'd' ) {
                       wp_enqueue_style( 'vc-mm', VC_MM_URL.'assets/frontend/vc-mm-v.css');
                   } else {
                       wp_enqueue_style( 'vc-mm', VC_MM_URL.'assets/frontend/vc-mm.css');
                   }

                    if ( $nav_settings['skin'] == '' ){
                        $nav_settings['skin'] = 'black.css';
                    }
                    wp_enqueue_style( 'vc-mm-skins', apply_filters( 'vc_mm_skin', VC_MM_URL.'assets/frontend/skins/'.$nav_settings['skin'], $nav_settings, $menu_id ) );

                }

            } // end if mega enable


        }
        return $args;
    }

    public static function get_search_form(){
        $form = '<form method="get" class="vc-mm-search-form" action="'.esc_url( home_url( '/' ) ).'">';
            $form .= '<input type="search" class="search-field" placeholder="'.esc_attr__( 'Search...', 'vc-mm' ).'" value="'.esc_attr( get_search_query() ).'" name="s"/>';
        $form .= '</form>';
        return $form;
    }


}

new VC_MegaMenu();

