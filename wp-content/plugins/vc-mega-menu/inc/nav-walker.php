<?php
/**
 * Create HTML list of nav menu items.
 *
 * @since 3.0.0
 * @uses Walker
 */
class VC_MM_Walker_Nav_Menu extends Walker_Nav_Menu {

    public function get_item_settings( $item_id ){
        $settings = get_post_meta( $item_id, '_vc_mm_settings', true );
        $settings =  apply_filters( 'vc_mm_get_item_settings', $settings, $item_id );
        return wp_parse_args( $settings, VC_MegaMenu::default_item_settings() );
    }

    function is_enable_mega( $item_id ){
        $settings =  $this->get_item_settings( $item_id );
        return intval( $settings['enable'] ) == 1  ;
    }

    function get_mega_content( $item_id ){
        if ( ! $this->is_enable_mega( $item_id ) ){
            return false;
        }
        return  trim( apply_filters( 'vc_mm_get_mega_content', get_post_meta( $item_id, '_vc_mm_content', true ), $item_id ) );
    }

    public function get_mega_class( $settings ){

        $classes = array();
        if ( intval( $settings['enable'] ) == 1  ) {
            $classes[] = 'vc-mm-mega';
        }

        if ( $settings['icon'] != ''  ) {
            $classes[] = 'vc-mm-has-icon';
        }

        if ( $settings['hide_title'] == 1  ) {
            $classes[] = 'vc-hide-title';
        }

        if ( $settings['class'] != ''  ) {
            $classes[] = esc_attr( $settings['class'] );
        }

        if ( $settings['align'] != ''  ) {
            $classes[] =  'vc-mm-align-'.esc_attr( $settings['align'] );
        }

        if ( $settings['layout'] != ''  ) {
            if ( $settings['layout'] == 'default' ) {
                $settings['layout'] = 'full';
            }
            $classes[] = 'vc-mm-layout-'.$settings['layout'];
        }

        if ( $settings['item_type'] == 'wc_cart'  ) {
            $classes[] = 'vc-mm-wc-cart';
        }

        return $classes;
    }

    public function get_icon( $settings ){
        if (  is_array( $settings ) ){
            if ( ! isset( $settings['icon'] ) ){
                $icon = '';
            } else {
                $icon = $settings['icon'];
            }
        } else {
            $icon = ( string ) $settings;
        }

        if ( $icon == '' &&  $settings['item_type'] == 'wc_cart' ){
            $icon = 'fa fa-shopping-cart';
        }

        $_icon = '';

        if ( trim( $icon ) != '' ){
            $_icon = '<span class="vc-icon"><i class="'.esc_attr( $icon ).'"></i></span>';
        }

        if ( $icon == '' &&  $settings['item_type'] == 'wc_cart' ){
            $_icon = apply_filters( 'vc_mm_vc_cart_item_type', $_icon );
        }

        return $_icon;
    }

    public function can_view( $settings ) {

        if ( $settings['role'] != 'anyone' && $settings['role'] != '' ) {
            if ($settings['role'] == 'none_logged_in') { // user not logged in to see
                return is_user_logged_in() ? false : true;
            } elseif ($settings['role'] == 'logged_in') {
                return is_user_logged_in() ? true : false;
            } else {

                $user = wp_get_current_user();
                if (in_array($settings['role'], $user->roles)) {
                    return true;
                } else {
                    return false;
                }

            }
        }

        return true;
    }



    /**
     * Starts the list before the elements are added.
     *
     * @see Walker::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<div class='sub-menu'><ul class=\"sub-menu-inner\">\n";
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul></div>\n";
    }

    /**
     * Start the element output.
     *
     * @see Walker::start_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     * @param int    $id     Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $settings = $this->get_item_settings( $item->ID );

        // Check role
        if ( ! $this->can_view( $settings ) ){
            return ;
        }

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $classes[] = 'vc-menu-item';
        $classes[] = 'vc-d-'.$depth;

        $classes[] = join( ' ', $this->get_mega_class( $settings ) );

        /**
         * Filter the CSS class(es) applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        /**
         * Filter the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $json_settings = array();
        $json_settings['layout'] = $settings['layout'];
        $json_settings['width']  = $settings['width'];

        $li_attr = ( $this->is_enable_mega( $item->ID ) ) ? ' data-mm-settings="'.esc_attr( json_encode( $json_settings ) ).'" ' : '';

        $output .= $indent . '<li' . $id . $class_names .$li_attr .'>';

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';


        /**
         * Filter the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title  Title attribute.
         *     @type string $target Target attribute.
         *     @type string $rel    The rel attribute.
         *     @type string $href   The href attribute.
         * }
         * @param object $item  The current menu item.
         * @param array  $args  An array of {@see wp_nav_menu()} arguments.
         * @param int    $depth Depth of menu item. Used for padding.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        if ( ! isset( $atts['class'] ) ){
            $atts['class']  = '';
        }
        foreach ( $atts as $attr => $value ) {

            if ( $attr == 'class' ){
                if ( ! empty( $value ) ) {
                    $value .= ' nav-link';
                } else {
                    $value .= 'nav-link';
                }
            }

            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );

                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        if ( $settings['item_type'] == 'search' ) { // Search element

            $item_output = $args->before;
            $item_output .= VC_MegaMenu::get_search_form();
            $item_output .= $args->after;

        } else { // Default item
            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';

            // Get Custom Icon
            $item_output .= $this->get_icon( $settings );

            /** This filter is documented in wp-includes/post-template.php */
            if ( $settings['hide_title'] != 1 ){ // if hide title
                $item_output .='<span class="vc-label">';
                $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
                $item_output .='</span>';
            }

            $item_output .= '</a>';
            $item_output .= $args->after;

            // Mega content
            $content = $this->get_mega_content( $item->ID );
            if ( $content != '' ){
                $depth = 1;
                $item_output .= '<div class="vc-mm-mega-cont">';
                $item_output .= '<div class="vc-mm-mega-cont-inner">';
                $item_output .= do_shortcode( $content );
                $item_output .= '</div>';
                $item_output .= '</div>';
            }

        }


        /**
         * Filter a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item        Menu item data object.
         * @param int    $depth       Depth of menu item. Used for padding.
         * @param array  $args        An array of {@see wp_nav_menu()} arguments.
         */
        $output .= apply_filters( 'vc_walker_nav_menu_start_el', $item_output, $item, $depth, $args, $settings );
    }

    /**
     * Ends the element output, if needed.
     *
     * @see Walker::end_el()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Page data object. Not used.
     * @param int    $depth  Depth of page. Not Used.
     * @param array  $args   An array of arguments. @see wp_nav_menu()
     */
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }

} // Walker_Nav_Menu
