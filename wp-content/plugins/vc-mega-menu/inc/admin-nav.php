<?php
/**
 * User: truongsa
 * Date: 12/4/15
 * Time: 00:05
 */


function vc_mm_change_content ( $_post ){
    global $post;

    if ( is_admin() ) {

        if ( isset(  $_REQUEST['vc_menu_item_id'] ) && $_post->post_type == 'vc_mega_menu') {
            $content = get_post_meta( $_REQUEST['vc_menu_item_id'], '_vc_mm_content', true );
          $post->post_content = $content; // $content
        }
    }

}
add_action( 'edit_form_top', 'vc_mm_change_content' ) ;

add_filter( 'wpb_vc_js_status_filter',  'vc_mm_wpb_vc_js_status_filter'  );

function vc_mm_wpb_vc_js_status_filter( $status ){
    global $post;
    if (  $post->post_type == 'vc_mega_menu') {
        return 'true';
    }
    return $status;
}


add_action( 'in_admin_header', 'vc_mm_loading' );
function vc_mm_loading(  ){
    global $post;
    if ( ! $post ){
        return false;
    }

    if ( $post->post_type != 'vc_mega_menu' ) {
        return false;
    }

    ?>
    <div class="vc-mm-loading">
        <div class="vc-mdl">
            <span class="spinner"></span>
        </div>
    </div>
    <?php
}



class VC_Mega_Menu_Admin {

    function __construct(){
        add_action( 'admin_print_scripts-nav-menus.php', array( $this, 'add_scripts' ) );
       // add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style' ) );
        add_action( 'admin_footer-nav-menus.php', array( $this, 'nav_settings_template' ) );

        // Ajax Action to save menu
        add_action( 'wp_ajax_save_mm_menu', array( $this, 'save'  ) );
        add_action( 'add_meta_boxes', array( $this, 'add_mega_menu_meta_box' ) );

        // Mega menu item post type
        add_action( 'admin_print_scripts-post-new.php', array( $this, 'add_post_type_scripts' ), 1000 );
        add_action( 'admin_print_scripts-post.php', array( $this, 'add_post_type_scripts' ), 1000 );

        add_action( 'admin_print_styles-post-new.php', array( $this, 'add_post_type_styles' ) );
        add_action( 'admin_print_styles-post.php', array( $this, 'add_post_type_styles' ) );

        // delete menu that not use
        if ( ! isset( $_REQUEST['wp_customize'] ) || $_REQUEST['wp_customize'] != 'on' ){
            add_action( 'wp_update_nav_menu', array( $this, 'update_nav_menu' ) );
        }

        // do_action( 'wp_delete_nav_menu', $menu->term_id );
        if( is_admin(  ) ) {
            add_action( 'admin_init',  array( $this, 'add_cap' ) , 99 );
        }

        // Customize Setup
        add_action( 'customize_controls_enqueue_scripts', array( $this, 'custom_customize_enqueue' ) );
        add_action( 'wp_ajax_vc-mm-load-mega-items-settings', array( $this, 'customize_load_menu_settings' ) );

    }

    function customize_load_menu_settings(){
        // Check user permisons
        check_ajax_referer( 'customize-menus', 'customize-menus-nonce' );
        if ( ! current_user_can( 'edit_theme_options' ) ) {
            wp_die( -1 );
        }

        $menu_id =  $_POST['menu_id'];

        $data = array();
        ob_start();
        $this->nav_settings_template( $menu_id, true );
        $settings =  ob_get_clean();

        $data['menu_settings'] = $settings;
        $data['items_setting'] = $this->vc_menu_items_settings( $menu_id );

        wp_send_json_success( $data );

    }



    /**
     * Enqueue script for custom customize control.
     */
    function custom_customize_enqueue() {

        wp_enqueue_media();
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_script( 'vc_mm_field_settings', VC_MM_URL.'assets/admin/js/field-settings.js', array( 'jquery' ), false, true );
        wp_enqueue_script( 'jquery-serialize-object', VC_MM_URL.'assets/admin/js/jquery-serialize-object.js', array( 'jquery' ), false, true );
        wp_enqueue_style( 'vc_mm_admin', VC_MM_URL.'assets/admin/css/vc-mm-admin.css' );

        wp_enqueue_style( 'font-awesome', home_url('/').'wp-content/plugins/js_composer/assets/lib/bower/font-awesome/css/font-awesome.min.css' );
        // wp_enqueue_style( 'vc_openiconic', home_url('/').'wp-content/plugins/js_composer/assets/css/lib/vc-open-iconic/vc_openiconic.min.css' );
        //wp_enqueue_style( 'vc_typicons', home_url('/').'wp-content/plugins/js_composer/assets/css/lib/vc-entypo/typicons.min.css' );
        //wp_enqueue_style( 'vc_entypo', home_url('/').'wp-content/plugins/js_composer/assets/css/lib/vc-entypo/vc_entypo.min.css' );
        //wp_enqueue_style( 'vc_linecons', home_url('/').'wp-content/plugins/js_composer/assets/css/lib/vc-lineicons/vc_linecons_icons.min.css' );


        wp_localize_script( 'customize-controls', 'vc_menu_items_settings', $this->vc_menu_items_settings( $this->get_selected_menu_id() ) );
        wp_localize_script( 'jquery', 'vc_mm_admin_object', array(
            'new_post'=> admin_url( 'post-new.php?post_type=vc_mega_menu' ),
            'mm'=> __( 'Mega', 'vc_mm' ),
            //'icon_list' => $this->get_icon_list(),
        ) );

        wp_register_script( 'vc-mm-customize-controls',  VC_MM_URL . 'assets/admin/js/customize.js' , array( 'jquery', 'customize-controls' ), false, true );
        wp_enqueue_script( 'vc-mm-customize-controls' );
    }


    /**
     * Enable page builder for vc_mega_menu post type
     */
    function add_cap(){
        global $current_user;

        //$current_user->allcaps["vc_access_rules_post_types/page"] = true;
        $current_user->allcaps["vc_access_rules_post_types/vc_mega_menu"] = true;
        $current_user->allcaps["vc_access_rules_post_types"] = "custom";

        //Always access to vcmm item page builder
        if ( in_array( 'administrator', $current_user->caps )   ) {
            $role = get_role( 'administrator' );
            //$role = get_role( 'author' );
            $role->add_cap( 'vc_access_rules_post_types', "custom" );
            $role->add_cap( 'vc_access_rules_post_types/vc_mega_menu', true );
        } else if (  in_array( 'author', $current_user->caps ) ) {
            $role = get_role( 'author' );
            $role->add_cap( 'vc_access_rules_post_types', "custom" );
            $role->add_cap( 'vc_access_rules_post_types/vc_mega_menu', true );
        }


    }



    /**
     * Adds a box to the main column on the Post and Page edit screens.
     */
    function add_mega_menu_meta_box() {

        add_meta_box(
            'vc_mega_menu_meta_box',
            __( 'Mega Settings', 'vc_mm' ),
            array( $this, 'render_meta_box_content' ),
            'vc_mega_menu'
        );

    }

    function render_meta_box_content(){
        global $post;

        $settings = get_post_meta( $_REQUEST['vc_menu_item_id'], '_vc_mm_settings', true );
        $settings =  wp_parse_args( $settings, VC_MegaMenu::default_item_settings() );

        ?>

        <input type="text" name="vc_menu_item_id" value="<?php echo absint( isset( $_REQUEST['vc_menu_item_id'] ) ? $_REQUEST['vc_menu_item_id'] : 0 ) ?>" >
        <input type="text" name="vc_nav_id" value="<?php echo absint( isset( $_REQUEST['vc_menu_id'] ) ? $_REQUEST['vc_menu_id'] : 0 ) ?>" >

        <script type="text/html" id="vc_item_toggle_enable">
            <div class="onoffswitch">
                <input type="checkbox" id="vc-mm-item-enable" name="enable" <?php checked( $settings['enable'], 1 ); ?> value="1" class="onoffswitch-checkbox">
                <label for="vc-mm-item-enable" class="onoffswitch-label"></label>
            </div>
        </script>

        <script type="text/html" id="vc_icon_picker_tpl">
            <div class="vc-mm-icon-popup-w">
                <div class="vc-icons-selector fip-vc-theme-grey vc-mm-icon-popup" style="position: relative;">
                    <div class="selector">
                        <span class="selected-icon">
                        </span>

                        <span class="selector-button toggle-list closed">
                            <i class="fip-fa fa fa-arrow-down"></i>
                        </span>
                        <span class="selector-button remove">
                            <i class="fip-fa fa fa-times"></i>
                        </span>
                    </div>
                    <div class="selector-popup" style="display: none">

                        <div class="selector-search">
                            <input type="text" class="icons-search-input" placeholder="<?php esc_attr_e( 'Search Icon', 'vc_mm' ); ?>" value="" name="">
                            <i class="fip-fa fa fa-search"></i>
                        </div>

                        <div class="fip-icons-container">
                            <?php
                            echo $this->get_icon_list();
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </script>

        <script type="text/html" id="vc_item_settings_tabs">
            <div class="vc_ui-panel-header-content">
                <ul data-vc-ui-element="panel-tabs-controls" class="vc_general vc_ui-tabs-line">
                    <li class="vc_edit-form-tab-control vc-mm-to-general vc_active">
                        <button  class="vc_ui-tabs-line-trigger"><?php _e( 'General', 'vc_mm' ); ?></button>
                    </li>
                    <li  class="vc_edit-form-tab-control vc-mm-to-design">
                        <button class="vc_ui-tabs-line-trigger"><?php _e( 'Design Options', 'vc_mm' ); ?></button>
                    </li>
                </ul>
            </div>
        </script>

        <script type="text/html" id="vc_item_settings">
            <form action="" id="vc-menu-settings-form" class="vc_ui-panel-content vc_properties-list vc_edit_form_elements">
                <?php
                do_action( 'vc_mm_before_item_settings', $settings );

                $file = dirname( __FILE__ ).'/settings-tpl/item-settings.php';
                $file =  apply_filters( 'vc_mm_item_setting_tpl_file', $file );
                if ( file_exists( $file ) ) {
                    include $file;
                }

                do_action('vc_mm_after_item_settings', $settings );
                ?>
            </form>
        </script>
        <?php
    }

    function get_icon_list(){
        ob_start();
        $oc = ob_get_clean();
        ob_start();

            $group_icons = array( );
            $group_icons['fontawesome'] = vc_iconpicker_type_fontawesome( array() );
            //$group_icons['openiconic'] = vc_iconpicker_type_openiconic( array() );
            //$group_icons['typicons'] = vc_iconpicker_type_typicons( array() );
            //$group_icons['entypo'] = vc_iconpicker_type_entypo( array() );
            //$group_icons['linecons'] = vc_iconpicker_type_linecons( array() );

            $i = 0;

            foreach( $group_icons as $gk => $all_icons ) {

                foreach ($all_icons as $k => $icons) {

                    if (is_array($icons)) {

                        foreach ($icons as $k2 => $icons2) {
                            if (is_string($icons2)) {
                                $i ++ ;
                                ?><span class="fip-box lv-2" data-icon-type="<?php echo esc_attr( $gk ); ?>" data-value="<?php echo esc_attr($k2); ?>" title="<?php echo esc_attr($icons2); ?>"><i class="<?php echo esc_attr($k2); ?>" ></i></span>
                            <?php
                            }else {
                                foreach ($icons2 as $k3 => $icons3 ) {
                                    if (is_string($icons3)) {
                                        $i ++ ;
                                        ?><span class="fip-box lv-3" data-icon-type="<?php echo esc_attr( $gk ); ?>" data-value="<?php echo esc_attr($k3); ?>" title="<?php echo esc_attr($icons3); ?>"><i class="<?php echo esc_attr($k3); ?>" ></i></span><?php
                                    }
                                }
                            }
                        }

                    } else {

                        $i ++ ;
                        ?>
                        <span class="fip-box" data-icon-type="<?php echo esc_attr( $gk ); ?>"  data-value="<?php echo esc_attr($k2); ?>" title="<?php echo esc_attr($icons); ?>"><i class="<?php echo esc_attr($k); ?>" ></i></span>
                    <?php
                    }
                    ?>


                <?php
                }
            }

        $c = ob_get_clean();
        echo $oc;

        return $c;
    }


    /**
     * Add scripts to menu pages
     */
    function add_scripts(){

        wp_enqueue_media();
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style( 'wp-color-picker' );

        wp_enqueue_script( 'vc_mm_field_settings', VC_MM_URL.'assets/admin/js/field-settings.js', array( 'jquery' ), false, true );
        wp_enqueue_script( 'vc_mm_admin', VC_MM_URL.'assets/admin/js/vc-mm-admin.js', array( 'jquery' ), false, true );
        wp_enqueue_style( 'vc_mm_admin', VC_MM_URL.'assets/admin/css/vc-mm-admin.css' );

        wp_enqueue_style( 'font-awesome', home_url('/').'wp-content/plugins/js_composer/assets/lib/bower/font-awesome/css/font-awesome.min.css' );

        wp_localize_script( 'vc_mm_admin', 'vc_menu_items_settings', $this->vc_menu_items_settings( $this->get_selected_menu_id() ) );
        wp_localize_script( 'jquery', 'vc_mm_admin_object', array(
            'new_post'=> admin_url( 'post-new.php?post_type=vc_mega_menu' ),
            'mm'=> __( 'Mega', 'vc_mm' ),
            '_nonce'=> wp_create_nonce( 'vc_mega_menu' ),
            'menu_url'=> admin_url( 'nav-menus.php' ),
            'confirm_import' => __( 'Are you sure want to IMPORT demo menus ?', 'vc-mm' ),
            'import_txt' => __( 'Import demo menus', 'vc-mm' ),
            //'icon_list' => $this->get_icon_list(),
        ) );


    }

    function add_post_type_styles() {
        global $post;
        if ( $post->post_type == 'vc_mega_menu' ){
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_style( 'vc_mm_admin_ifram_css', VC_MM_URL . 'assets/admin/css/vc-mm-menu-item.css' );
        }
    }

    function add_post_type_scripts() {
        global $post;
        if ( $post->post_type == 'vc_mega_menu' ){

            wp_enqueue_media();
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script( 'jquery' );

            wp_enqueue_script( 'vc_mm_field_settings', VC_MM_URL.'assets/admin/js/field-settings.js', array( 'jquery' ), false, true );
            wp_enqueue_script( 'jquery-serialize-object', VC_MM_URL.'assets/admin/js/jquery-serialize-object.js', array( 'jquery' ), false, true );
            wp_enqueue_script( 'vc_mm_admin_iframe_js', VC_MM_URL.'assets/admin/js/vc-mm-menu-item.js', array( 'jquery' ), false, true );
            wp_localize_script( 'jquery', 'vc_mm_post_settings', array(
                'setting_label' => __( 'Menu Settings', 'vc_mm' ),
                'save_label' => __( 'Save', 'vc_mm' ),
                'done_label' => __( 'Done', 'vc_mm' ),
                'item_settings' => __( 'Item Settings', 'vc_mm' ),
            ) );
        }

    }

    function vc_menu_items_settings( $menu_id ){
        $items = wp_get_nav_menu_items( $menu_id );

        $default =  array(
            'icon'   => '',
            'layout' => 'default',
            'role'   => '',
            'width'  => '',
            'enable'  => '',
            'class'  => '',
        ) ;

        $menus_items = array();

        if ( $items ){
            foreach ( $items as $p ) {
                $settings = get_post_meta( $p->ID, '_vc_mm_settings', true );
                $settings = wp_parse_args( $settings, $default );
                $menus_items[ $p->ID ] = array(
                    'url'       =>  admin_url( 'post-new.php?post_type=vc_mega_menu&vc_menu_item_id='.$p->ID ),
                    'options'   => $settings,
                );

            }
        }

        return $menus_items;
    }

    function sanitize_hex_color( $color ) {
        if ( '' === $color )
            return '';

        // 3 or 6 hex digits, or the empty string.
        if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
            return $color;
        }
        return '';
    }

    function update_nav_menu( $nav_menu_selected_id, $data = false, $update_meta = true ){
        //$items = wp_get_nav_menu_items( $nav_menu_selected_id );
        if ( ! $data ) {
            $data = $_REQUEST;
        }

        $ids =  isset( $data['menu-item-db-id'] ) ? array_map( 'intval', $data['menu-item-db-id'] ) : false;
        if ( $ids ) {

            $args = array(
                'posts_per_page' => '-1',
                'post_type' => 'vc_mega_menu',
                'post_status' => 'any',
                'meta_key' => '_vc_mm_nav_id',
                'meta_value' => $nav_menu_selected_id,
                'meta_compare' => '=',
                'post_parent__not_in' => $ids
            );

            $query = new WP_Query($args);
            $posts = $query->get_posts();
            //$post_ids = wp_list_pluck($posts, 'ID');

            if ($posts) {
                foreach ($posts as $p) {
                     wp_delete_post( $p->ID, true );
                }
            }
        }

        // Update settings

        $enable = isset( $data['vc_enable_mega'] ) && intval( $data['vc_enable_mega'] ) == 1 ? 1 : 0;
        $show_desc = isset( $data['vc_show_desc'] ) && intval( $data['vc_show_desc'] ) == 1 ? 1 : 0;
        $is_sticky = isset( $data['vc_enable_sticky'] ) && intval( $data['vc_enable_sticky'] ) == 1 ? 1 : 0;
        $add_home_mobile = isset( $data['vc_add_home_mobile'] ) && intval( $data['vc_add_home_mobile'] ) == 1 ? 1 : 0;

        $display= isset( $data['vc_display'] ) && in_array( $data['vc_display'], array( 'h','v', 'd' ) ) ? sanitize_text_field( $data['vc_display'] ) : 'h';
        $tab_layout = isset( $data['vc_tab_layout'] ) && in_array( $data['vc_tab_layout'], array( 'align','full' ) ) ? sanitize_text_field( $data['vc_tab_layout'] ) : 'full';

        $bg = isset( $data['vc_bg'] ) ? $this->sanitize_hex_color( $data['vc_bg'] ) : '';
        $color = isset( $data['vc_color'] ) ? $this->sanitize_hex_color( $data['vc_color'] ) : '';
        $hover_bg = isset( $data['vc_hover_bg'] ) ? $this->sanitize_hex_color( $data['vc_hover_bg'] ) : '';
        $hover_color = isset( $data['vc_hover_color'] ) ? $this->sanitize_hex_color( $data['vc_hover_color'] ) : '';
        $active_bg = isset( $data['vc_active_bg'] ) ? $this->sanitize_hex_color( $data['vc_active_bg'] ) : '';
        $active_color = isset( $data['vc_active_color'] ) ? $this->sanitize_hex_color( $data['vc_active_color'] ) : '';

        $border = isset( $data['vc_border'] ) ? intval( $data['vc_border'] ) : '';
        $border_color = isset( $data['vc_border_color'] ) ? $this->sanitize_hex_color( $data['vc_border_color'] ) : '';

        $logo_id = isset( $data['vc_logo_id'] ) ? intval( $data['vc_logo_id'] ) : '';
        $h_layout = isset( $data['vc_h_layout'] ) ? intval( $data['vc_h_layout'] ) : '';
        $skin = isset( $data['vc_skin'] ) ? sanitize_text_field( $data['vc_skin'] ) : '';
        $transparent = isset( $data['vc_transparent'] ) ? intval( $data['vc_transparent'] ) : '';


        $default = VC_MegaMenu::default_nav_settings();
        $keys =  array_keys( $default );
        $settings = compact( $keys );

        $css = '';
        $wrap_s = '#vc-nav-id-'.$nav_menu_selected_id.'-wrapper';
        $menu_selector = $wrap_s.' #vc-nav-id-'.$nav_menu_selected_id;

        if ( $settings['bg'] != '' ) {
            $css .= $wrap_s.'{ background: '.$settings['bg'].'; }';
            //$css .= $menu_selector.'-mobile { background: '.$settings['bg'].'; }';
        }

        if ( $settings['color'] != '' ) {
            $css .= $menu_selector.' > li.vc-d-0 > .nav-link  { color: '.$settings['color'].'; }';
           // $css .= $menu_selector.'-mobile > li > a  { color: '.$settings['color'].'; }';
        }

        if ( $settings['hover_bg'] != '' ) {
            $css .= $menu_selector.' > li.vc-d-0:hover > .nav-link  { background: '.$settings['hover_bg'].'; }';
            //$css .= $menu_selector.'-mobile > li > a  { background: '.$settings['hover_bg'].'; }';
        }

        if ( $settings['hover_color'] != '' ) {
            $css .= $menu_selector.' > li.vc-d-0:hover > .nav-link  { color: '.$settings['hover_color'].'; }';
        }

        if ( $settings['active_bg'] != '' ) {
            $css .= $menu_selector.' > li.current-menu-item.vc-d-0 > .nav-link  { background: '.$settings['active_bg'].'; }';
        }

        if ( $settings['active_color'] != '' ) {
            $css .= $menu_selector.' > li.current-menu-item.vc-d-0 > .nav-link  { color: '.$settings['active_color'].'; }';
        }

        if ( isset( $data['vc_border'] ) ) {
            if( $settings['border'] > 0 || $data['vc_border'] != '' ) {
                $css .= $menu_selector.' { border-width: '.$settings['border'].'px; }';
            }
        }

        if( $settings['border_color'] != '' ) {
            $css .= $menu_selector.' { border-color: '.$settings['border_color'].'; }';
        }

        if ( $update_meta ) {
            update_term_meta( $nav_menu_selected_id, '_vc_mm_settings', $settings );
            update_term_meta( $nav_menu_selected_id, '_vc_mm_css', $css );
        } else {
            return  array( 'settings' => $settings, 'css' => $css );
        }


    }

    function nav_settings_template( $menu_id = false, $html_only = false ){
        if ( ! $menu_id ) {
            $current_menu_id = $this->get_selected_menu_id();
        } else {
            $current_menu_id =  $menu_id;
        }

        ?>
        <?php if ( ! $html_only ) { ?>
        <script type="text/html" id="vc-mm-popup-tpl">
            <div class="vc-mm-popup"></div>
        </script>
        <script type="text/html" id="vc-nav-settings">
        <?php } ?>
        <?php

        $file = dirname( __FILE__ ).'/settings-tpl/nav-settings.php';
        $file =  apply_filters( 'vc_mm_nav_setting_tpl_file', $file );
        if ( file_exists( $file ) ) {
            include_once( $file );
        }

        ?>
        <?php if ( ! $html_only ) { ?>
        </script>
        <?php } ?>
        <?php
    }

    public static  function get_selected_menu_id()
    {

        $nav_menus = wp_get_nav_menus(array('orderby' => 'name'));

        $menu_count = count($nav_menus);

        $nav_menu_selected_id = isset($_REQUEST['menu']) ? (int)$_REQUEST['menu'] : 0;

        $add_new_screen = (isset($_GET['menu']) && 0 == $_GET['menu']) ? true : false;

        // If we have one theme location, and zero menus, we take them right into editing their first menu
        $page_count = wp_count_posts('page');
        $one_theme_location_no_menus = (1 == count(get_registered_nav_menus()) && !$add_new_screen && empty($nav_menus) && !empty($page_count->publish)) ? true : false;

        // Get recently edited nav menu
        $recently_edited = absint(get_user_option('nav_menu_recently_edited'));
        if (empty($recently_edited) && is_nav_menu($nav_menu_selected_id))
            $recently_edited = $nav_menu_selected_id;

        // Use $recently_edited if none are selected
        if (empty($nav_menu_selected_id) && !isset($_GET['menu']) && is_nav_menu($recently_edited))
            $nav_menu_selected_id = $recently_edited;

        // On deletion of menu, if another menu exists, show it
        if (!$add_new_screen && 0 < $menu_count && isset($_GET['action']) && 'delete' == $_GET['action'])
            $nav_menu_selected_id = $nav_menus[0]->term_id;

        // Set $nav_menu_selected_id to 0 if no menus
        if ($one_theme_location_no_menus) {
            $nav_menu_selected_id = 0;
        } elseif (empty($nav_menu_selected_id) && !empty($nav_menus) && !$add_new_screen) {
            // if we have no selection yet, and we have menus, set to the first one in the list
            $nav_menu_selected_id = $nav_menus[0]->term_id;
        }

        return $nav_menu_selected_id;

    }

    function build_item_css( $settings, $item_id ){
        $css  = '';

        // Mega content css
        $content_css = array();
        if ( is_numeric( $settings['content_padding_top'] ) ){
            $content_css[] = 'padding-top: '.intval( $settings['content_padding_top'] ).'px;';
        }

        if ( is_numeric( $settings['content_padding_right'] ) ){
            $content_css[] = 'padding-right: '.intval( $settings['content_padding_right'] ).'px;';
        }

        if ( is_numeric( $settings['content_padding_bottom'] ) ){
            $content_css[] = 'padding-bottom: '.intval( $settings['content_padding_bottom'] ).'px;';
        }

        if ( is_numeric( $settings['content_padding_left'] ) ){
            $content_css[] = 'padding-left: '.intval( $settings['content_padding_left'] ).'px;';
        }

        if ( ! empty( $content_css ) ){
            $css .="#menu-item-{$item_id} .vc-mm-mega-cont-inner { ".join( " ", $content_css )." }";
        }

        // Mega Bg
        $bg_css = array();

        if (  $settings['bg_color'] != '' ){
            $bg_css[] = 'background-color: '.esc_attr( $settings['bg_color'] ).';';
        }

        if ( $settings['bg_image_id'] != '' ){
            $image_url  = wp_get_attachment_url( $settings['bg_image_id'] );

            if ( $image_url ) {

                $bg_css[] = 'background-image: url("'.esc_url( $image_url ).'");';

                if ( $settings['bg_style'] != '' ){
                    switch( $settings['bg_style'] ) {
                        case 'cover':
                            $bg_css[] = 'background-size: cover;';
                            $bg_css[] = 'background-repeat: no-repeat;';
                            $bg_css[] = 'background-position: center bottom;';
                            break;
                        case 'top-left':
                            $bg_css[] = 'background-position: top left;';
                            $bg_css[] = 'background-repeat: no-repeat;';
                            break;
                        case 'top-right':
                            $bg_css[] = 'background-position: top right;';
                            $bg_css[] = 'background-repeat: no-repeat;';
                            break;
                        case 'bottom-right':
                            $bg_css[] = 'background-position: bottom right;';
                            $bg_css[] = 'background-repeat: no-repeat;';
                            break;
                        case 'bottom-left':
                            $bg_css[] = 'background-position: bottom left;';
                            $bg_css[] = 'background-repeat: no-repeat;';
                            break;
                        case 'no-repeat':
                            $bg_css[] = 'background-position: center center;';
                            $bg_css[] = 'background-repeat: no-repeat;';
                            break;
                        case 'repeat':
                            $bg_css[] = 'background-repeat: repeat;';
                            break;
                    }
                } // end bg style

                if ( $settings['bg_position_top'] != '' ){
                    $bg_css['top'] = 'top: '.intval( $settings['bg_position_top'] ).'px;';
                } else {
                    if (  $settings['bg_position_bottom'] != '' ) {
                        $bg_css['top'] = 'top: auto;';
                    }

                }

                if ( $settings['bg_position_right'] != '' ){
                    $bg_css['right'] = 'right: '.intval( $settings['bg_position_right'] ).'px;';
                } else {
                    if (  $settings['bg_position_left'] != '' ) {
                        $bg_css['bg_position_left'] = 'top: auto;';
                    }
                }

                if ( $settings['bg_position_bottom'] != '' ){
                    $bg_css['bottom'] = 'bottom: '.intval( $settings['bg_position_bottom'] ).'px;';
                }else {
                    if ( $settings['bg_position_top'] != '' ){
                        $bg_css['bottom'] = 'bottom: auto;';
                    }

                }

                if ( $settings['bg_position_left'] != '' ){
                    $bg_css['left'] = 'left: '.intval( $settings['bg_position_left'] ).'px;';
                } else {
                    if (  $settings['bg_position_right'] != '' ) {
                        $bg_css['left'] = 'left: auto;';
                    }
                }

            }

        }

        if ( ! empty( $bg_css ) ) {
            $css .= '#menu-item-'.$item_id. ' .vc-mm-mega-cont-inner::before{ '.join( " ", $bg_css ).' }';
        }

        // Border
        $border = array();
        if ( $settings['border_color'] != '' ){
            $border[] = 'border-color: '.esc_attr( $settings['border_color'] ).';';
        }
        if ( $settings['border_style'] != '' && 'default' !=  $settings['border_style'] ){
            $border[] = 'border-style: '.esc_attr( $settings['border_style'] ).';';
        }
        if ( $settings['border_top'] != '' ){
            $border[] = 'border-top-width: '.intval( $settings['border_top'] ).'px;';
        }
        if ( $settings['border_right'] != '' ){
            $border[] = 'border-right-width: '.intval( $settings['border_right'] ).'px;';
        }
        if ( $settings['border_bottom'] != '' ){
            $border[] = 'border-bottom-width: '.intval( $settings['border_bottom'] ).'px;';
        }
        if ( $settings['border_left'] != '' ){
            $border[] = 'border-left-width: '.intval( $settings['border_left'] ).'px;';
        }



        if ( ! empty( $border ) ) {
            $css .= '#menu-item-'.$item_id. ' .vc-mm-mega-cont-inner{ '.join( " ", $border ).' }';
        }

        // item bg
        if ( $settings['item_bg'] != '' ){
            $css .= '.vc-mm-menu #menu-item-'.$item_id. ' > a.nav-link{ background-color: '.esc_attr( $settings['item_bg'] ).' ; }';
        }
        // Item text
        if ( $settings['item_bg'] != '' ){
            $css .= '.vc-mm-menu #menu-item-'.$item_id. ' > a.nav-link{ color: '.esc_attr( $settings['item_color'] ).'; }';
        }

        // Hover bg item
        if ( $settings['item_hover_bg'] != '' ){
            $css .= '.vc-mm-menu #menu-item-'.$item_id. ':hover > a.nav-link{ background-color: '.esc_attr( $settings['item_hover_bg'] ).' !important; }';
        }

        // Hover text item
        if ( $settings['item_hover_color'] != '' ){
            $css .= '.vc-mm-menu #menu-item-'.$item_id. ':hover > a.nav-link{ color: '.esc_attr( $settings['item_hover_color'] ).' !important; }';
        }


        // active BG
        if ( $settings['item_active_bg'] != '' ){
            $css .= '.vc-mm-menu #menu-item-'.$item_id.'.current-menu-item > a.nav-link{ background-color: '.esc_attr( $settings['item_active_bg'] ).'; }';
        }

        // active color
        if ( $settings['item_active_color'] != '' ){
            $css .= '.vc-mm-menu #menu-item-'.$item_id.'.current-menu-item > a.nav-link{ color: '.esc_attr( $settings['item_active_color'] ).'; }';
        }

        // menu-item-6
        return $css;

    }

    /**
     * Parse shortcodes custom css string.
     *
     * This function is used by self::buildShortcodesCustomCss and creates css string from shortcodes attributes
     * like 'css_editor'.
     *
     * A COPPY of method Vc_Base::parseShortcodesCustomCss
     *
     * @see    WPBakeryVisualComposerCssEditor
     * @see    Vc_Base::parseShortcodesCustomCss
     *
     * @since  4.2
     * @access public
     *
     * @param $content
     *
     * @return string
     */
    public function parseShortcodesCustomCss( $content ) {
        $css = '';
        if ( ! preg_match( '/\s*(\.[^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $content ) ) {
            return $css;
        }
        WPBMap::addAllMappedShortcodes();
        preg_match_all( '/' . get_shortcode_regex() . '/', $content, $shortcodes );
        foreach ( $shortcodes[2] as $index => $tag ) {
            $shortcode = WPBMap::getShortCode( $tag );
            $attr_array = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );
            if ( isset( $shortcode['params'] ) && ! empty( $shortcode['params'] ) ) {
                foreach ( $shortcode['params'] as $param ) {
                    if ( 'css_editor' === $param['type'] && isset( $attr_array[ $param['param_name'] ] ) ) {
                        $css .= $attr_array[ $param['param_name'] ];
                    }
                }
            }
        }
        foreach ( $shortcodes[5] as $shortcode_content ) {
            $css .= $this->parseShortcodesCustomCss( $shortcode_content );
        }

        return $css;
    }


    function save( $data = false,  $preview = false ){

        $return = array(
            'status'    => false,
            'url'       => '',
            'is_update' => true,
            'errors'    => array()
        );

        if ( ! $data ) {
            $data = $_POST;
        }

        $content = isset( $data['content'] ) ? wp_kses_post( $data['content']  ) : '';
        $menu_item_id = isset( $data['vc_menu_item_id'] ) ?  intval( $data['vc_menu_item_id'] ) : 0;

        $post = get_post( $menu_item_id );

        if ( get_post_type( $post ) !== 'nav_menu_item' ){
            if ( $preview ) {
                return false;
            }
            $return['errors'][] = __( 'Menu item not exists', 'vc_menu' );
            die( json_encode( $return ) );
        }

        $settings = isset( $data['_vc_settings'] ) ? $data['_vc_settings'] : array();
        $settings = wp_parse_args( $settings, VC_MegaMenu::default_item_settings() );

        if ( $menu_item_id > 0 ) {
           // $custom_css =  get_post_meta( $post_id, '_wpb_shortcodes_custom_css', true );
           // $custom_css =  $this->parseShortcodesCustomCss( $content );
            $content = stripslashes_deep( $content );
            $custom_css = apply_filters( 'vc_base_build_shortcodes_custom_css', $this->parseShortcodesCustomCss( $content ) );
            $custom_css = $this->build_item_css( $settings, $menu_item_id ).$custom_css;
            //update_post_meta( $menu_item_id, '_wpb_shortcodes_custom_css', $custom_css );
            if (  $preview ) {
                return array(
                    'css'=> $custom_css,
                    'settings' => $settings,
                    'content' => $content,
                );
            } else {

                update_post_meta( $menu_item_id, '_vc_custom_css', $custom_css );
                update_post_meta( $menu_item_id, '_vc_mm_settings', $settings );
                update_post_meta( $menu_item_id, '_vc_mm_content',  $content );
            }

        }

        if ( $preview ) {
            return false;
        }

        $return[ 'url' ]      = admin_url( 'post-new.php?post_type=vc_mega_menu&vc_menu_item_id='.$menu_item_id );
        $return[ 'settings' ] = $settings;

        wp_die( json_encode( $return ) );
    }


}

global $VC_Mega_Menu_Admin;

$VC_Mega_Menu_Admin = new VC_Mega_Menu_Admin();


include_once dirname( __FILE__ ).'/customize.php';

