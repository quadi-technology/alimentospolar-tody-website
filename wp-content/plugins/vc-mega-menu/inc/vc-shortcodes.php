<?php

if ( ! class_exists( 'WPBakeryShortCode_Vc_Mm_List' ) ) {
    class WPBakeryShortCode_Vc_Mm_List extends WPBakeryShortCode
    {

        public function content($atts, $content = '')
        {

            $atts = vc_map_get_attributes($this->getShortcode(), $atts);

            if (empty($atts['list'])) {
                return false;
            }

            $list = json_decode(urldecode($atts['list']), true);
            if (!is_array($list)) {
                return false;
            }

            ob_start();
            $old_content = ob_get_clean();
            ob_start();
            //var_dump( $atts );
            ?>
            <ul class="vc-mm-list<?php echo ($atts['list_style'] != '') ? ' ' . esc_attr($atts['list_style']) : ''; ?>">
                <?php
                foreach ($list as $k => $l) {
                    $l = wp_parse_args($l, array(
                        'title' => '',
                        'subtitle' => '',
                        'link' => '',
                        'icon' => '',
                    ));
                    $link = vc_build_link($l['link']);

                    $class = array();
                    if ($l['subtitle'] != '') {
                        $class[] = 'has-subtitle';
                    }

                    if ($l['icon'] != '') {
                        $class[] = 'has-icon';
                    }

                    if ($l['title']) {
                        ?>
                        <li class="<?php echo esc_attr(join(" ", $class)); ?>">
                            <a href="<?php echo esc_attr($link['url']); ?>" <?php echo($link['target'] ? ' target="' . esc_attr($link['target']) . '"' : ''); ?>>
                                <?php if ($l['icon']) { ?>
                                    <span class="vc-list-icon"><i class="<?php echo esc_attr($l['icon']); ?>"></i></span>
                                <?php } ?>
                                <span class="vc-list-title"><?php echo esc_attr($l['title']); ?></span>
                                <?php if ($l['subtitle']) { ?>
                                    <span class="vc-list-subtitle"><?php echo esc_attr($l['subtitle']); ?></span>
                                <?php } ?>
                            </a>
                        </li>
                    <?php
                    }
                }
                //
                ?>
            </ul>
            <?php

            $content = ob_get_clean();
            echo $old_content;
            return wpb_js_remove_wpautop($content);
        }

    }
}


vc_map( array(
    'name'          => __( 'VC List', 'vc_mm' ),
    'base'          => 'vc_mm_list',
    "category"      => __('Content', 'vc_mm' ),
    'icon'          => '',
    'description'   => __( 'Display number with count effect', 'vc_mm' ),
    'params'        => array(

        array(
            'type'          => 'param_group',
            'heading'       => __( 'Items', 'vc_mm' ),
            'param_name'    => 'list',
            'description'   => __( 'Add link items', 'vc_mm' ),
            'params'        => array(
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Title', 'vc_mm' ),
                    'param_name'    => 'title',
                    'admin_label'   => true,
                ),
                array(
                    'type'          => 'textfield',
                    'heading'       => __( 'Subtitle', 'vc_mm' ),
                    'param_name'    => 'subtitle',
                    // 'std'           => $this->default_atts['number'],
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'URL (Link)', 'vc_mm' ),
                    'param_name' => 'link',
                    'description' => __( 'Add link to custom heading.', 'vc_mm' ),
                    // compatible with btn2 and converted from href{btn1}
                ),
                array(
                    'type' => 'iconpicker',
                    'heading' => __( 'Icon', 'vc_mm' ),
                    'param_name' => 'icon',
                    'admin_label'   => true,
                    'description' => __( 'Select custom icon.', 'vc_mm' ),
                ),

            ),
        ),

        array(
            'type' => 'dropdown',
            'heading' => __( 'List style', 'js_composer' ),
            'value' => array(
                __( 'Default', 'js_composer' ) => '',
                __( 'Hover style', 'js_composer' ) => 'hover-style',
            ),
            'param_name' => 'list_style',
        ),

    ),
) );


//-------------------


//Register "container" content element. It will hold all your inner (child) content elements
vc_map( array(
    "name" => __("VC Vertical Tabs", "vc_mm"),
    "base" => "vc_mm_tabs",
    "as_parent" => array('only' => 'vc_mm_tab'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
    "content_element" => true,
    "show_settings_on_create" => false,
    "is_container" => true,
    "params" => array(
        // add params same as with any other content element
        /*
        array(
            'type' => 'hidden',
            'heading' => __( 'Tab Type', 'js_composer' ),
            'value' => array(
                __( 'Vertical', 'vc_mm' ) => 'vertical',
               // __( 'horizontal', 'vc_mm' ) => 'horizontal',
            ),
            'admin_label'   => true,
            'param_name' => 'tab_type',
            'description' => __( 'Select tab type.', 'js_composer' ),
        ),
        */

        array(
            'type' => 'dropdown',
            'heading' => __( 'Tab Size', 'js_composer' ),
            'value' => array(
                __( '1 column - 1/12', 'js_composer' ) => '1/12',
                __( '2 columns - 1/6', 'js_composer' ) => '1/6',
                __( '3 columns - 1/4', 'js_composer' ) => '1/4',
                __( '4 columns - 1/3', 'js_composer' ) => '1/3',
                __( '5 columns - 5/12', 'js_composer' ) => '5/12',
                __( '6 columns - 1/2', 'js_composer' ) => '1/2',
                __( '7 columns - 7/12', 'js_composer' ) => '7/12',
                __( '8 columns - 2/3', 'js_composer' ) => '2/3',
                __( '9 columns - 3/4', 'js_composer' ) => '3/4',
                __( '10 columns - 5/6', 'js_composer' ) => '5/6',
                __( '11 columns - 11/12', 'js_composer' ) => '11/12',
                __( '12 columns - 1/1', 'js_composer' ) => '1/1',
            ),
            'param_name' => 'tab_size',
            /*
            'dependency' => array(
                'element' => 'tab_type',
                'value' => 'vertical',
            ),
            */
        ),
        array(
            'type' => 'dropdown',
            'heading' => __( 'Tab border', 'js_composer' ),
            'value' => array(
                __( 'Border', 'vc_mm' ) => 'border',
                __( 'No Border', 'vc_mm' ) => 'no-border',
            ),
            'param_name' => 'tab_border',
            'description' => __( 'Choose border or no border for your tab.', 'js_composer' ),
        ),
        array(
            'type'          => 'dropdown',
            'heading'       => __( 'Tab event', 'js_composer' ),
            'value'         => array(
                __( 'Hover', 'vc_mm' )     => 'hover',
                __( 'Click', 'vc_mm' )     => 'click',
            ),
            'std'           => 'hover',
            'param_name'    => 'tab_event',
            'description'   => __( 'Active tab via hover or click event.', 'js_composer' ),
        ),
        array(
            'type'          => 'textfield',
            'heading'       => __( 'Default active tab', 'js_composer' ),
            'std'           => '1',
            'param_name'    => 'default_active_tab',
            'description'   => __( 'By default, the first tab will be active.', 'js_composer' ),
            'dependency'    => array(
                'element'   => 'tab_event',
                'value'     => array( 'click' )
            )
        ),
        array(
            "type" => "textfield",
            "heading" => __("Extra class name", "js_composer"),
            "param_name" => "el_class",
            "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "my-text-domain")
        ),
        array(
            'type' => 'css_editor',
            'heading' => __( 'CSS box', 'js_composer' ),
            'param_name' => 'css',
            'group' => __( 'Design Options', 'js_composer' ),
        ),
    ),
    "js_view" => 'VcColumnView'
) );

vc_map( array(
    "name" => __("Content Tab", "vc_mm"),
    "base" => "vc_mm_tab",
    "content_element" => true,
    "show_settings_on_create" => true,
    "is_container" => true,
    "as_child" => array('only' => 'vc_mm_tabs'), // Use only|except attributes to limit parent (separate multiple values with comma)
    "as_parent" => true,
    //'icon' => 'fa fa-bars',
    "params" => array(

        array(
            "type" => "textfield",
            "heading" => __("Title", "vc_mm"),
            "param_name" => "tab_title",
            'admin_label'   => true,
        ),

        array(
            'type' => 'dropdown',
            'heading' => __( 'Is divider', 'js_composer' ),
            'value' => array(
                __( 'No', 'js_composer' ) => 'no',
                __( 'Yes', 'js_composer' ) => 'yes',
            ),
            //'admin_label' => true,
            'param_name' => 'is_divider',
            'admin_label'   => true,
            'description' => __( 'Tab Divider: apply for vertical mod only.', 'js_composer' ),
        ),

        array(
            'type' => 'dropdown',
            'heading' => __( 'Icon library', 'js_composer' ),
            'value' => array(
                __( 'Font Awesome', 'js_composer' ) => 'fontawesome',
                __( 'Open Iconic', 'js_composer' ) => 'openiconic',
                __( 'Typicons', 'js_composer' ) => 'typicons',
                __( 'Entypo', 'js_composer' ) => 'entypo',
                __( 'Linecons', 'js_composer' ) => 'linecons',
            ),
            //'admin_label' => true,
            'param_name' => 'type',
            'description' => __( 'Select icon library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_fontawesome',
            'value' => 'fa fa-adjust', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true,
                // default true, display an "EMPTY" icon?
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'fontawesome',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_openiconic',
            'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'openiconic',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'openiconic',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_typicons',
            'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'typicons',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'typicons',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_entypo',
            'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'entypo',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'entypo',
            ),
        ),
        array(
            'type' => 'iconpicker',
            'heading' => __( 'Icon', 'js_composer' ),
            'param_name' => 'icon_linecons',
            'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'linecons',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'type',
                'value' => 'linecons',
            ),
            'description' => __( 'Select icon from library.', 'js_composer' ),
        ),


    ),
    "js_view" => 'VcColumnView'
) );



//Your "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_VC_Mm_Tabs extends  WPBakeryShortCodesContainer{
        public function content( $atts, $content = '' ){

            $title = $interval = $el_class = '';
            $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
            extract( $atts );

            $tab_type = 'vertical';

            wp_enqueue_script( 'jquery-ui-tabs' );

            $el_class = $this->getExtraClass( $el_class );

            // Extract tab titles
            preg_match_all( '/vc_mm_tab([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
            $tabs  = array();
            if ( isset( $matches[1] ) ) {
                foreach( $matches[1] as $k => $v ){
                    $tabs[] = shortcode_parse_atts( $v[0] );
                }
            }

            $tabs_nav ='';

            foreach( $tabs as $tab ){
                $tab = vc_map_get_attributes( 'vc_mm_tab', $tab );
                $tab = wp_parse_args( $tab, array(
                    'tab_title' => '',
                    'type' => '', // icon type
                    'is_divider' => ''
                ) );
                $icon = '';
                if ( $tab['type'] != '' ){
                    if ( isset( $tab[ 'icon_'.$tab['type'] ] ) ) {
                        $icon = $tab[ 'icon_'.$tab['type'] ];
                    }
                }
                if ( $tab['is_divider'] == 'yes' ) {
                    if ( trim( $tab['tab_title'] ) != '' ){
                        $tabs_nav .= '<li class="vc-tab-divider has-title"><span>'.( trim( $icon ) !='' ? '<i class="vc-tab-icon '.esc_attr( $icon ).'"></i>' : '' ).esc_html( $tab['tab_title'] ).'</span></li>';
                    } else {
                        $tabs_nav .= '<li class="vc-tab-divider no-title"></li>';
                    }

                } else {
                    $tabs_nav .= '<li><a href="#"><span>'.( $icon!='' ? '<i class="vc-tab-icon  '.esc_attr( $icon ).'"></i>' : '' ).esc_html( $tab['tab_title'] ).'</span></a></li>';
                }
            }


            $tab_content_class = '';
            $c_w_a =  explode( '/', $tab_size );
            if (  count( $c_w_a ) == 2 ){
                $a = intval( $c_w_a[0] );
                $b = intval( $c_w_a[1] );
                $n = $b-$a;
                if ( $n <= 0 ) {
                    $tab_content_class = '12/12';
                } else {
                    $tab_content_class = "$n/$b";
                }
            }


            if ( $tabs_nav != '' ){
                if ( $tab_type != 'horizontal' ){
                    $tabs_nav = '<ul class="vc-mm-tabs-nav '. wpb_translateColumnWidthToSpan( $tab_size  ).'">'.$tabs_nav.'</ul>';
                } else {
                    $tabs_nav = '<ul class="vc-mm-tabs-nav">'.$tabs_nav.'</ul>';
                }

            }

            $el_class = $this->getExtraClass( $el_class );
            $el_class .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );

            $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, trim( $this->shortcode . ' wpb_content_element ' . $el_class ), $this->settings['base'], $atts );
            ////tab_event,default_active_tab
            
            if( isset( $tab_event ) ){
                if( $tab_event == 'hover' ){
                    $tab_data = 'data-tab_event="hover click"';
                }elseif( $tab_event == 'click' ){
                    $tab_data = 'data-tab_event="click"';
                    $data_default_tab = 1;
                    if( isset( $default_active_tab ) && $default_active_tab != '' ){
                        $tab_data .= ' data-default_active_tab="'. intval( $default_active_tab ).'"';
                    }
                }
            }
            
            if( isset( $tab_data ) ){
                $output = '<div class="vc_mm_tabs_wrapper '.$css_class.' vc-mm-tabs tabs-'.esc_attr( $tab_type ).' vc_clearfix" '.$tab_data.'>'. $tabs_nav;
            }else{
                $output = '<div class="vc_mm_tabs_wrapper '.$css_class.' vc-mm-tabs tabs-'.esc_attr( $tab_type ).' vc_clearfix">'. $tabs_nav;
            }
                if ( $tab_type != 'horizontal' ) {
                    $output .= ( $content != '' ? '<div class="vc-mm-tabs-c-wrap '.wpb_translateColumnWidthToSpan($tab_content_class).'">'.wpb_js_remove_wpautop( $content ).'</div>' : '' );
                } else {
                    $output .= ( $content != '' ? '<div class="vc-mm-tabs-c-wrap">'.wpb_js_remove_wpautop( $content ).'</div>' : '' );
                }

            $output .= '</div>';

            return $output;
        }

    }
}

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {

    //
   // include_once ABSPATH.'wp-content/plugins/js_composer/include/classes/shortcodes/vc-row.php';
    //class WPBakeryShortCode_VC_Mm_Tab extends WPBakeryShortCodesContainer {
    class WPBakeryShortCode_VC_Mm_Tab extends WPBakeryShortCodesContainer {

        public function content( $atts, $content = '' ){

            $atts = vc_map_get_attributes( $this->getShortcode(), $atts );
            if ( $atts['is_divider'] == 'yes' ){
                return '<div class="vc-mm-tab-cont is_divider"></div>';
            }

            $title = '';

            if ( $atts['tab_title'] != '' ){
                $icon = '';
                if ( $atts['type'] != '' ){
                    if ( isset( $atts[ 'icon_'.$atts['type'] ] ) ) {
                        $icon = $atts[ 'icon_'.$atts['type'] ];
                    }
                }

                if ( $icon != '' ){
                    $icon = '<span class="vc-icon"><i class="'.esc_attr( $icon ).'"></i></span>';
                }

                $title = '<h3 class="vc-mobile-tab-title">'.$icon.esc_html( $atts['tab_title'] ).'</h3>';
            }

           return '<div class="vc-mm-tab-cont">'.$title. wpb_js_remove_wpautop( $content ).'</div>';
        }
    }
}

/* Woocommerce Widgets */

if (  class_exists( 'WooCommerce' ) ) {
    /**
     * @see WC_Widget_Product_Categories
     *
     */
    vc_map(array(
        'name' => __('VC Product Categories', 'vc_mm'),
        'base' => 'vc_mm_p_categories',
        "category" => __('WordPress Widgets', 'vc_mm'),
        'icon' => '',
        'description' => __('Display number with count effect', 'vc_mm'),
        'params' => array(

            array(
                'type' => 'dropdown',
                'std' => 'name',
                'param_name' => 'orderby',
                'label' => __('Order by', 'woocommerce'),
                'value' => array(
                    __('Category Order', 'woocommerce') => 'order',
                    __('Name', 'woocommerce') => 'name'
                )
            ),

            array(
                'type' => 'checkbox',
                'param_name' => 'count',
                'heading' => __('Show product counts', 'woocommerce')
            ),
            array(
                'type' => 'checkbox',
                'param_name' => 'hierarchical',
                'heading' => __('Show hierarchy', 'woocommerce')
            ),
            array(
                'type' => 'checkbox',
                'param_name' => 'show_children_only',
                'heading' => __('Only show children of the current category', 'woocommerce')
            )
        ),
    ));


    class WPBakeryShortCode_Vc_Mm_P_Categories extends WPBakeryShortCode
    {

        public function content( $atts, $content = '')
        {

            $atts = vc_map_get_attributes($this->getShortcode(), $atts);

            ob_start();
            $old_content = ob_get_clean();
            ob_start();
            // var_dump( $atts );
            the_widget( 'WC_Widget_Product_Categories', $atts, array() );

            $content = ob_get_clean();
            echo $old_content;
            return wpb_js_remove_wpautop( $content );
        }


    }

    /**
     * @see WC_Widget_Cart
     */
    vc_map(array(
        'name' => __('VC Cart', 'vc_mm'),
        'base' => 'vc_mm_wc_cart',
        "category" => __('WordPress Widgets', 'vc_mm'),
        'icon' => '',
        'description' => __('Display WooCommerce cart', 'vc_mm'),
        'params' => array(

        ),
    ));

    class WPBakeryShortCode_Vc_Mm_Wc_Cart extends WPBakeryShortCode
    {

        public function content( $atts, $content = '')
        {

            $atts = vc_map_get_attributes($this->getShortcode(), $atts);

            ob_start();
            $old_content = ob_get_clean();
            ob_start();
            // var_dump( $atts );
            the_widget( 'WC_Widget_Cart', $atts, array() );

            $content = ob_get_clean();
            echo $old_content;
            return wpb_js_remove_wpautop( $content );
        }
    }


    /**
     * @see WC_Widget_Products
     */
    vc_map(array(
        'name' => __('VC Products', 'vc_mm'),
        'base' => 'vc_mm_wc_products',
        "category" => __('WordPress Widgets', 'vc_mm'),
        'icon' => '',
        'description' => __('Display WooCommerce Products', 'vc_mm'),
        'params' => array(
            array(
                'type' => 'textfield',
                'param_name' => 'number',
                'heading' => __('Number of products to show', 'woocommerce'),

            ),
            array(
                'type' => 'dropdown',
                'param_name' => 'show',
                'label' => __('Show', 'woocommerce'),
                'value' => array(
                    __( 'All Products', 'woocommerce' ) => '',
                    __('Featured Products', 'woocommerce') => 'featured',
                    __('On-sale Products', 'woocommerce') => 'onsale',
                )
            ),
            array(
                'type' => 'dropdown',
                'param_name' => 'orderby',
                'label' => __('Order by', 'woocommerce'),
                'value' => array(
                    __( 'Date', 'woocommerce' ) => 'date',
                    __('Price', 'woocommerce') => 'price',
                    __('Sales', 'woocommerce') => 'sales',
                    __('Random', 'woocommerce') => 'rand',

                )
            ),

            array(
                'type' => 'dropdown',
                'param_name' => 'order',
                'label' => __('Order', 'woocommerce'),
                'value' => array(
                    __('DESC', 'woocommerce') => 'desc',
                    __('ASC', 'woocommerce' ) => 'asc',

                )
            ),

            array(
                'type' => 'checkbox',
                'param_name' => 'hide_free',
                'heading' => __('Hide free products', 'woocommerce')
            ),
            array(
                'type' => 'checkbox',
                'param_name' => 'show_hidden',
                'heading' => __('Show hidden products', 'woocommerce')
            )


        ),
    ));

    class WPBakeryShortCode_Vc_Mm_Wc_Products extends WPBakeryShortCode
    {

        public function content( $atts, $content = '')
        {

            $atts = vc_map_get_attributes($this->getShortcode(), $atts);

            ob_start();
            $old_content = ob_get_clean();
            ob_start();

            //$w = new WC_Widget_Products();
            the_widget( 'WC_Widget_Products', $atts, array( 'widget_id' => 'vc_mm_wc_products_'.rand() ) );

           // $w->widget( array(),  $atts );

            $content = ob_get_clean();
            echo $old_content;
            return wpb_js_remove_wpautop( $content );
        }
    }



    /**
     * @see WC_Widget_Products
     */
    vc_map(array(
        'name' => __('VC Top Rated Products', 'vc_mm'),
        'base' => 'vc_mm_wc_top_rated',
        "category" => __('WordPress Widgets', 'vc_mm'),
        'icon' => '',
        'description' => __('Display WooCommerce Top RatedProducts', 'vc_mm'),
        'params' => array(
            array(
                'type' => 'textfield',
                'param_name' => 'number',
                'heading' => __('Number of products to show', 'woocommerce'),

            ),

        ),
    ));

    class WPBakeryShortCode_Vc_Mm_Wc_Top_Rated extends WPBakeryShortCode
    {

        public function content( $atts, $content = '')
        {

            $atts = vc_map_get_attributes($this->getShortcode(), $atts);

            ob_start();
            $old_content = ob_get_clean();
            ob_start();

            the_widget( 'WC_Widget_Top_Rated_Products', $atts, array( 'widget_id' => 'vc_mm_wc_rate_products_'.rand() ) );

            $content = ob_get_clean();
            echo $old_content;
            return wpb_js_remove_wpautop( $content );
        }
    }
}

/**
 * Nav Shortcode 
 */
$list_exists_menu = array();
if ( 'vc_edit_form' === vc_post_param( 'action' ) && vc_verify_admin_nonce() ) {
	$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
	if ( is_array( $menus ) && ! empty( $menus ) ) {
		foreach ( $menus as $single_menu ) {
			if ( is_object( $single_menu ) && isset( $single_menu->name, $single_menu->term_id ) ) {
				$list_exists_menu[ $single_menu->name ] = $single_menu->term_id;
			}
		}
	}
}

vc_map( array(
	'name'         => __( "VC Mega Menu", "vc_mm" ),
	'base'         => 'vc_mm_menu',
	'description'  => __( 'Displaying your mega menu', 'vc_mm' ),
	'params'       => array(
		array(
			'type'           => 'dropdown',
			'heading'        => __( 'Menu', 'vc_mm' ),
			'param_name'     => 'nav_menu',
			'value'          => $list_exists_menu,
			'description'    => empty( $list_exists_menu ) ? __( 'No menu found. Please visit <b>Appearance > Menus</b> page to create new menu.', 'vc_mm' ) : __( 'Select menu to display.', 'vc_mm' ),
			'admin_label'    => true,
			'save_always'    => true,
		),
		array(
			'type'           => 'textfield',
			'heading'        => __( 'Extra class name', 'vc_mm' ),
			'param_name'     => 'el_class',
			'description'    => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'vc_mm' ),
		),
        array(
            'type'          => 'css_editor',
            'heading'       => __( 'CSS box', 'vc_mm' ),
            'param_name'    => 'css',
            'group'         => __( 'Design Options', 'vc_mm' ),
        ),
	),
));

if( !class_exists( 'WPBakeryShortCode_Vc_Mm_Menu' ) ){
    class WPBakeryShortCode_Vc_Mm_Menu extends WPBakeryShortCode{
        public function content( $atts, $content = ''){
            $atts = vc_map_get_attributes($this->getShortcode(), $atts);
            extract( $atts );
            
            $el_class = $this->getExtraClass( $el_class );
            $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ) . $el_class, $this->settings['base'], $atts );
            
            if ( empty( $atts[ 'nav_menu' ] ) ) {
                return false;
            }
                           
            $args = array(
                'menu'          => $nav_menu,
                'fallback_cb'   => 'wp_page_menu'
            );
                            
            
            ob_start();
            ?>
            <div class="vc_mm_shortcode_menu_wrapper<?php echo esc_attr( $css_class ); ?>">
                <?php wp_nav_menu( $args ); ?>
            </div>
            <?php
            return ob_get_clean();
        }
    }
}
