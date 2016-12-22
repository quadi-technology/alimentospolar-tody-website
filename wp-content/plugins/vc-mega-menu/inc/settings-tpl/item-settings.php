<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/*
<pre>
    <?php var_dump( $settings ); ?>
</pre>

*/
?>

<div class="vc-mm-tab-general">

    <div  class="vc_col-xs-12 vc_column wpb_el_type_checkbox">
        <div class="wpb_element_label"><?php _e( 'Item Type', 'vc_mm' ); ?></div>
        <div class="edit_form_line">
            <select name="item_type">
                <option <?php selected( $settings['item_type'], 'default' ); ?> value="default"><?php _e( 'Default', 'vc_mm' ); ?></option>
                <option <?php selected( $settings['item_type'], 'search' ); ?> value="search"><?php _e( 'Search box', 'vc_mm' ); ?></option>
                <option <?php selected( $settings['item_type'], 'wc_cart' ); ?> value="wc_cart"><?php _e( 'WooCommerce Cart', 'vc_mm' ); ?></option>
            </select>
        </div>
    </div>


    <div class="vc_col-xs-12 vc_column wpb_el_type_checkbox">
        <div class="wpb_element_label"><?php _e( 'Menu title', 'vc_mm' ); ?></div>
        <div class="edit_form_line">
            <label>
                <input type="checkbox"  value="1" <?php checked( $settings['hide_title'], 1 ); ?> class="wpb_vc_param_value wpb-textinput" name="hide_title">
                <?php _e( 'Hide menu title', 'vc_mm' ); ?>
            </label>
        </div>
    </div>

    <div  class="vc_col-xs-12 vc_column wpb_el_type_checkbox">
        <div class="wpb_element_label"><?php _e( 'Colors', 'vc_mm' ); ?></div>
        <div class="edit_form_line">
            <div class="vc-mm-mg" >
                <input type="text" value="<?php echo esc_attr( $settings['item_bg'] ) ;?>" class=" color-field" name="item_bg">
                <span><?php _e( 'Background', 'vc_mm' ); ?></span>
            </div>
            <div class="vc-mm-mg" >
                <input type="text" value="<?php echo esc_attr( $settings['item_color'] ) ;?>" class="color-field" name="item_color">
                <span><?php _e( 'Text color', 'vc_mm' ); ?></span>
            </div>
            <div class="vc-mm-mg" >
                <input type="text" value="<?php echo esc_attr( $settings['item_hover_bg'] ) ;?>" class="color-field" name="item_hover_bg">
                <span><?php _e( 'HOVER background color', 'vc_mm' ); ?></span>
            </div>
            <div class="vc-mm-mg" >
                <input type="text" value="<?php echo esc_attr( $settings['item_hover_bg'] ) ;?>" class="color-field" name="item_hover_color">
                <span><?php _e( 'HOVER text color', 'vc_mm' ); ?></span>
            </div>
            <div class="vc-mm-mg">
                <input type="text" value="<?php echo esc_attr( $settings['item_active_bg'] ) ;?>" class="color-field" name="item_active_bg">
                <span><?php _e( 'ACTIVE background color', 'vc_mm' ); ?></span>
            </div>
            <div class="vc-mm-mg" >
                <input type="text" value="<?php echo esc_attr( $settings['item_active_color'] ) ;?>" class="color-field" name="item_active_color">
                <span><?php _e( 'ACTIVE text color', 'vc_mm' ); ?></span>
            </div>

        </div>
    </div>

    <div  class="vc_col-xs-12 vc_column wpb_el_type_textfield">
        <div class="wpb_element_label"><?php _e( 'Layout (Horizontal only)', 'vc_mm' ); ?></div>
        <div class="edit_form_line">
            <?php
            $layouts = array(
                'center'            => array('label' => __( 'Center', 'vc_mm' ), 'url' => VC_MM_URL.'/assets/admin/images/submenu_center.png' ),
                'full'              => array('label' => __( 'Full', 'vc_mm' ), 'url' => VC_MM_URL.'/assets/admin/images/submenu_full.png' ),
                'right_edge_item'   => array('label' => __( 'Right edge item', 'vc_mm' ), 'url' => VC_MM_URL.'/assets/admin/images/submenu_right_edge_item.png' ),
                'left_edge_item'    => array('label' => __( 'Left edge item', 'vc_mm' ), 'url' => VC_MM_URL.'/assets/admin/images/submenu_left_edge_item.png' ),
            );
            ?>
            <?php foreach( $layouts as $k => $layout ){ ?>
                <div class="image-radio">
                    <input id="ir-<?php echo esc_attr( $k ); ?>" type="radio" <?php checked( $settings['layout'], $k ); ?> name="layout" value="<?php echo esc_attr( $k ); ?>">
                    <label for="ir-<?php echo esc_attr( $k ); ?>"><img alt="" src="<?php echo esc_url( $layout['url'] ) ?>"></label>
                </div>
            <?php } ?>
        </div>
    </div>


    <div  class="vc_col-xs-12 vc_column wpb_el_type_checkbox">
        <div class="wpb_element_label"><?php _e( 'Item Align', 'vc_mm' ); ?></div>
        <div class="edit_form_line">
            <select name="align">
                <option <?php selected( $settings['align'], 'default' ); ?> value="default"><?php _e( 'Default', 'vc_mm' ); ?></option>
                <option <?php selected( $settings['align'], 'left' ); ?> value="left"><?php _e( 'Left', 'vc_mm' ); ?></option>
                <option <?php selected( $settings['align'], 'right' ); ?> value="right"><?php _e( 'Right', 'vc_mm' ); ?></option>
            </select>
        </div>
    </div>


    <div  class="vc_col-xs-12 vc_column wpb_el_type_textfield">
        <div class="wpb_element_label"><?php _e( 'Who can see this item', 'vc_mm' ); ?></div>
        <div class="edit_form_line">
            <select name="role">
                <option <?php selected( $settings['role'], 'anyone' ); ?> value="anyone"><?php _e( 'Anyone', 'vc_mm' ); ?></option>
                <option <?php selected( $settings['role'], 'none_logged_in' ); ?> value="none_logged_in"><?php _e( 'None logged in users', 'vc_mm' ) ?></option>
                <option <?php selected( $settings['role'], 'logged_in' ); ?> value="logged_in"><?php _e( 'Logged in users', 'vc_mm' ) ?></option>
                <?php
                $editable_roles = array_reverse( get_editable_roles() );
                foreach ( $editable_roles as $role => $details ) {
                    $name = translate_user_role( $details['name'] );
                    echo  "<option ".selected( $settings['role'], $role, false ) ." value='" . esc_attr($role) . "'>$name</option>";
                }
                ?>
            </select>
        </div>
    </div>

    <div  class="vc_col-xs-12 vc_column wpb_el_type_dropdown vc_wrapper-param-type-dropdown vc_shortcode-param">
        <div class="wpb_element_label"><?php _e( 'Icon', 'vc-mm' ); ?></div>
        <input type="hidden" class="vc_icon_picker" value="<?php echo esc_attr( $settings['icon'] ); ?>" name="icon" >
    </div><!-- // End Select icon -->


    <div  class="vc_col-xs-12 vc_column wpb_el_type_textfield">
        <div class="wpb_element_label"><?php _e( 'Custom Menu Width', 'vc_mm' ); ?></div>
        <div class="edit_form_line">
            <input type="text" value="<?php echo esc_attr( $settings['width'] ); ?>" class="wpb_vc_param_value wpb-textinput el_class textfield" name="width">
        </div>
    </div>

</div><!-- /.vc-mm-tab-general -->


<div class="vc-mm-tab-design" style="display: none;">

    <div  class="vc_col-xs-12 vc_column wpb_el_type_textfield">
        <div class="wpb_element_label"><?php _e( 'Background', 'vc_mm' ); ?></div>
        <div class="vc-mm-edit-bg">

            <div class="edit_form_line">
                <div class="vc_css-editor vc_row vc_ui-flex-row">
                    <div class="vc_layout-onion vc_col-xs-7">
                        <div class="vc_margin"><label><?php _e( 'BG Image Position', 'vc_mm' ); ?></label>

                            <input type="text"  value="<?php echo esc_attr( $settings['bg_position_top'] ); ?>" placeholder="<?php esc_attr_e( '-', 'vc_mm' ); ?>" class="vc_top bg_position_top" name="bg_position_top">
                            <input type="text" value="<?php echo esc_attr( $settings['bg_position_right'] ); ?>" placeholder="<?php esc_attr_e( '-', 'vc_mm' ); ?>" class="vc_right bg_position_right" name="bg_position_right">
                            <input type="text" value="<?php echo esc_attr( $settings['bg_position_bottom'] ); ?>" placeholder="<?php esc_attr_e( '-', 'vc_mm' ); ?>" class="vc_bottom bg_position_bottom" name="bg_position_bottom">
                            <input type="text" value="<?php echo esc_attr( $settings['bg_position_left'] ); ?>" placeholder="<?php esc_attr_e( '-', 'vc_mm' ); ?>" class="vc_left bg_position_left" name="bg_position_left">


                            <div class="vc_border">
                                <label><?php _e( 'border', 'vc_mm' ); ?></label>

                                <input type="text" value="<?php echo esc_attr( $settings['border_top'] ); ?>" placeholder="<?php esc_attr_e( '-', 'vc_mm' ); ?>" class="vc_top" name="border_top">
                                <input type="text" value="<?php echo esc_attr( $settings['border_right'] ); ?>" placeholder="<?php esc_attr_e( '-', 'vc_mm' ); ?>" class="vc_right" name="border_right">
                                <input type="text" value="<?php echo esc_attr( $settings['border_bottom'] ); ?>" placeholder="<?php esc_attr_e( '-', 'vc_mm' ); ?>" class="vc_bottom" name="border_bottom">
                                <input type="text" value="<?php echo esc_attr( $settings['border_left'] ); ?>" placeholder="<?php esc_attr_e( '-', 'vc_mm' ); ?>" class="vc_left" name="border_left">

                                <div class="vc_padding">
                                    <label><?php _e( 'padding', 'vc_mm' ); ?></label>

                                    <input type="text" value="<?php echo esc_attr( $settings['content_padding_top'] ); ?>" placeholder="<?php esc_attr_e( '-', 'vc_mm' ); ?>" class="vc_top" name="content_padding_top">
                                    <input type="text" value="<?php echo esc_attr( $settings['content_padding_right'] ); ?>" placeholder="<?php esc_attr_e( '-', 'vc_mm' ); ?>" class="vc_right" name="content_padding_right">
                                    <input type="text" value="<?php echo esc_attr( $settings['content_padding_bottom'] ); ?>" placeholder="<?php esc_attr_e( '-', 'vc_mm' ); ?>" class="vc_bottom" name="content_padding_bottom">
                                    <input type="text" value="<?php echo esc_attr( $settings['content_padding_left'] ); ?>" placeholder="<?php esc_attr_e( '-', 'vc_mm' ); ?>" class="vc_left" name="content_padding_left">

                                    <div class="vc_content">
                                        <i></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="vc_col-xs-5 vc_settings">
                        <label><?php _e( 'Border color', 'vc_mm' ); ?></label>
                        <div class="color-group">
                            <input type="text" value="<?php echo esc_attr( $settings['border_color'] ) ;?>" class=" color-field" name="border_color">
                        </div>
                        <label><?php _e( 'Border style', 'vc_mm' ); ?></label>
                        <div class="vc_border-style">
                            <?php

                            $border_styles =  array(
                                'default'=> __( 'Default', 'vc_mm' ),
                                'solid'=> __( 'Solid', 'vc_mm' ),
                                'dotted'=> __( 'Dotted', 'vc_mm' ),
                                'dashed'=> __( 'Dashed', 'vc_mm' ),
                                'none'=> __( 'None', 'vc_mm' ),
                            );

                            ?>
                            <select name="border_style" class="vc_border-style"  >
                                <?php foreach( $border_styles as $k => $bs ){ ?>
                                    <option <?php selected( $settings['border_style'], $k ); ?> value="<?php echo esc_attr( $k ); ?>"><?php echo esc_html( $bs ); ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <label><?php _e( 'Background', 'vc_mm' ); ?></label>
                        <div class="color-group">
                            <input type="text" value="<?php echo esc_attr( $settings['bg_color'] ) ;?>" class="bg_url color-field" name="bg_color">
                        </div>
                        <div class="vc_background-image">
                            <?php
                            $feat_image_url = '';
                            if ( $settings['bg_image_id'] ) {
                                $feat_image_url = wp_get_attachment_url( $settings['bg_image_id'] );
                            }
                            ?>
                            <div class="item-media <?php echo ( $feat_image_url !='' ) ? 'has-img': 'no-img'; ?>">
                                <div class="thumbnail-image">
                                    <?php if ( $feat_image_url != '' ){
                                        ?>
                                        <img src="<?php echo esc_url( $feat_image_url ); ?>" alt="">
                                    <?php
                                    } ?>
                                </div>
                                <input type="hidden" value="<?php echo esc_attr( $settings['bg_image_url'] ) ;?>" class="bg_url image_url" name="bg_image_url">
                                <input type="hidden" value="<?php echo esc_attr( $settings['bg_image_id'] ) ;?>" class="bg_id image_id" name="bg_image_id">
                                <a href="#" class="vc-remove-button" title="<?php esc_attr_e( 'Remove', 'vc_mm' ); ?>"><span class="dashicons dashicons-no-alt"></span></a>
                                <a href="#" class="vc-add-button" title="<?php esc_attr_e( 'Add image', 'vc_mm' ); ?>"><span class="dashicons dashicons-plus"></span></a>
                            </div>
                            <div class="vc_clearfix"></div>
                        </div>
                        <div class="vc_background-style">
                            <select class="vc_background-style" name="bg_style">
                                <option <?php selected( $settings['bg_style'], 'cover' ); ?> value="cover"><?php _e( 'Cover', 'vc_mm' ); ?></option>
                                <option <?php selected( $settings['bg_style'], 'top-left' ); ?> value="top-left"><?php _e( 'Top left', 'vc_mm' ); ?></option>
                                <option <?php selected( $settings['bg_style'], 'top-right' ); ?> value="top-right"><?php _e( 'Top right', 'vc_mm' ); ?></option>
                                <option <?php selected( $settings['bg_style'], 'bottom-right' ); ?> value="bottom-right"><?php _e( 'Bottom right', 'vc_mm' ); ?></option>
                                <option <?php selected( $settings['bg_style'], 'bottom-left' ); ?> value="bottom-left"><?php _e( 'Bottom left', 'vc_mm' ); ?></option>
                                <option <?php selected( $settings['bg_style'], 'no-repeat' ); ?> value="no-repeat"><?php _e( 'No repeat', 'vc_mm' ); ?></option>
                                <option <?php selected( $settings['bg_style'], 'repeat' ); ?> value="repeat"><?php _e( 'Repeat', 'vc_mm' ); ?></option>
                            </select>

                        </div>

                    </div>
                </div>

                <div class="vc_clearfix"></div>
            </div>


        </div>
    </div>

    <div  class="vc_col-xs-12 vc_column wpb_el_type_textfield">
        <div class="wpb_element_label"><?php _e( 'Extra class name', 'vc_mm' ); ?></div>
        <div class="edit_form_line">
            <input type="text"  value="<?php echo esc_attr( $settings['class'] ); ?>"  class="wpb_vc_param_value wpb-textinput el_class textfield" name="class">
        </div>
    </div>

</div><!-- /.vc-mm-tab-design -->
