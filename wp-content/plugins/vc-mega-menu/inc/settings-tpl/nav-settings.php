<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$settings = get_term_meta( $current_menu_id, '_vc_mm_settings', true );
$settings = wp_parse_args( $settings, VC_MegaMenu::default_nav_settings() );

?>
<div class="vc-nav-setting">

    <dl class="vc_enable_mega">
        <dt class="howto"><?php _e( 'Mega', 'vc_mm' ) ?></dt>
        <dd class="checkbox-input">
            <input type="checkbox" value="1" <?php checked( $settings['enable'], 1 ) ?> id="vc_enable_mega_<?php echo esc_attr( $current_menu_id ); ?>" class="vc_enable_mega" name="vc_enable_mega">
            <label for="vc_enable_mega_<?php echo esc_attr( $current_menu_id ); ?>"><?php _e( 'Enable mega menu', 'vc_mm' ); ?></label>
        </dd>
    </dl>

    <?php
    // Filter You can disable option settings then use in theme
    if ( apply_filters( 'vc_mega_show_setting_settings', true ) ) { ?>
    <dl class="vc_mega_settings">
        <dt class="howto"><?php _e( 'Mega Settings', 'vc_mm' ); ?>

        </dt>

        <?php /*
        <dd class="checkbox-input">
            <input type="checkbox" value="1" <?php checked( $settings['show_desc'], 1 ) ?> id="vc_show_desc_<?php echo esc_attr( $current_menu_id ); ?>" class="vc_show_desc" name="vc_show_desc">
            <label for="vc_show_desc_<?php echo esc_attr( $current_menu_id ); ?>"><?php _e( 'Show menu items description', 'vc_mm' ); ?></label>
        </dd>
        */ ?>

        <dd class="checkbox-input">
            <label for="vc_display"><?php _e( 'Display' ) ?></label>
            <select name="vc_display" class="vc_display">
                <option <?php selected( $settings['display'], 'h' ) ?> value="h"><?php _e( 'Horizontal', 'vc_mm' ); ?></option>
                <option <?php selected( $settings['display'], 'v' ) ?> value="v"><?php _e( 'Vertical', 'vc_mm' ); ?></option>
                <option <?php selected( $settings['display'], 'd' ) ?> value="d"><?php _e( 'Dropdown', 'vc_mm' ); ?></option>
            </select>
        </dd>

        <dd class="checkbox-input">
            <a href="#" class="vc-mm-toggle-more-settings"><?php _e( 'How/Hide Advanced Settings', 'vc-mm' ); ?></a>
        </dd>

        <div class="vc-more-nav-settings">

        <dd class="checkbox-input">

            <div class="vc_tab_layout">
                <?php _e( 'Tab layout', 'vc_mm' ); ?><br/>
                <?php
                $layouts = array(
                    'full'  => array('label' => __( 'Full', 'vc_mm' ), 'url' => VC_MM_URL.'/assets/admin/images/submenu_vertical_full.png' ),
                    'align' => array('label' => __( 'align', 'vc_mm' ), 'url' => VC_MM_URL.'/assets/admin/images/submenu_vertical_align.png' ),
                );

                if ( $settings['tab_layout'] == '' ) {
                    $settings['tab_layout'] = 'full';
                }
                ?>
                <?php foreach( $layouts as $k => $layout ){ ?>
                    <div class="image-radio">
                        <input id="ir-<?php echo esc_attr( $k ); ?>_<?php echo esc_attr( $current_menu_id ); ?>" type="radio" <?php checked( $settings['tab_layout'], $k ); ?>  name="vc_tab_layout" class="vc_tab_layout" value="<?php echo esc_attr( $k ); ?>">
                        <label for="ir-<?php echo esc_attr( $k ); ?>_<?php echo esc_attr( $current_menu_id ); ?>"><img alt="" src="<?php echo esc_url( $layout['url'] ) ?>"></label>
                    </div>
                <?php } ?>
            </div>

        </dd>


        <dd class="dd_vc_enable_sticky dd_menu_h_layout checkbox-input">
            <label><?php _e( 'Horizontal layout', 'vc_mm' ); ?></label>
            <?php
            if ( $settings['h_layout'] == '' ) {
                $settings['h_layout'] = 5;
            }
            ?>
            <?php for( $i = 5 ; $i >= 1; $i-- ){

                $url =VC_MM_URL.'/assets/admin/images/layout-'.$i.'.png';
                ?>
                <div class="image-radio vc-mm-big ">
                    <input id="irmhl-<?php echo esc_attr( $i ); ?>_<?php echo esc_attr( $current_menu_id ); ?>" type="radio" <?php checked( $settings['h_layout'], $i ); ?>  name="vc_h_layout" class="vc_h_layout" value="<?php echo esc_attr( $i ); ?>">
                    <label for="irmhl-<?php echo esc_attr( $i ); ?>_<?php echo esc_attr( $current_menu_id ); ?>"><img alt="" src="<?php echo esc_url( $url ) ?>"></label>
                </div>
            <?php } ?>
            <div class="vc_clearfix"></div>
        </dd>

        <dd class="dd_vc_enable_sticky checkbox-input">
            <label><?php _e( 'Logo', 'vc_mm' ); ?></label>
            <?php
            $feat_image_url = '';
            if ( $settings['logo_id'] ) {
                $feat_image_url = wp_get_attachment_url( $settings['logo_id'] );
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
                <input type="hidden" value="<?php echo esc_attr( $settings['logo_id'] ) ;?>" class="logo_id image_id" name="vc_logo_id">
                <a href="#" class="vc-remove-button" title="<?php esc_attr_e( 'Remove', 'vc_mm' ); ?>"><span class="dashicons dashicons-no-alt"></span></a>
                <a href="#" class="vc-add-button" title="<?php esc_attr_e( 'Add image', 'vc_mm' ); ?>"><span class="dashicons dashicons-plus"></span></a>
            </div>
            <div class="vc_clearfix"></div>

        </dd>

        <dd class="dd_vc_enable_sticky checkbox-input">
            <input type="checkbox" value="1" <?php checked( $settings['is_sticky'], 1 ) ?> class="vc_enable_sticky" id="vc_enable_sticky_<?php echo esc_attr( $current_menu_id ); ?>" name="vc_enable_sticky">
            <label for="vc_enable_sticky_<?php echo esc_attr( $current_menu_id ); ?>"><?php _e( 'Enable sticky menu', 'vc_mm' ); ?></label>
        </dd>

        <dd class="dd_vc_add_home_mobile checkbox-input">
            <input type="checkbox" value="1" <?php checked( $settings['add_home_mobile'], 1 ) ?> id="vc_add_home_mobile_<?php echo esc_attr( $current_menu_id ); ?>" class="vc_add_home_mobile" name="vc_add_home_mobile">
            <label for="vc_add_home_mobile_<?php echo esc_attr( $current_menu_id ); ?>"><?php _e( 'Add home icon in mobile mod', 'vc_mm' ); ?></label>
        </dd>

        <dd class="dd_vc_enable_sticky checkbox-input">
            <input type="checkbox" value="1" <?php checked( $settings['transparent'], 1 ) ?> class="vc_transparent" id="vc_enable_transparent_<?php echo esc_attr( $current_menu_id ); ?>" name="vc_transparent">
            <label for="vc_enable_transparent_<?php echo esc_attr( $current_menu_id ); ?>"><?php _e( 'Background transparent top level', 'vc_mm' ); ?></label>
        </dd>

        <dd class="dd_vc_color checkbox-input">

            <div class="vc-skins">
                <?php
                $skins = VC_MegaMenu::get_skins();

                foreach( $skins as $k => $skin ){

                    $id = str_replace( array( '.', ' ' ), '-', $k ).'-'.$current_menu_id;
                    ?>
                    <div class="vc-skin">
                        <input id="vc-skin-<?php echo esc_attr( $id ); ?>" type="radio" <?php  checked( $settings['skin'], $k ); ?> name="vc_skin" value="<?php echo esc_attr( $k ); ?>">
                        <label for="vc-skin-<?php echo esc_attr( $id ); ?>" >
                            <span class="skin-color" style="background: <?php echo esc_attr( trim( $skin['primary_color']  )); ?>;"></span>
                            <span class="skin-color" style="background: <?php echo esc_attr( trim( $skin['secondary_color'] ) ); ?>;"></span>
                            <span class="skin-color" style="background: <?php echo esc_attr( trim( $skin['active_color'] ) ); ?> ;"></span>
                            <span class="skin-color" style="background: <?php echo esc_attr( trim( $skin['sub_bg'] ) ); ?> ;"></span>
                            <span class="skin-name"><?php echo esc_html( $skin['name'] ); ?></span>
                        </label>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div>
                <input type="text" class="color-field vc_bg" value="<?php echo esc_attr( $settings['bg'] ); ?>" id="vc_bg" name="vc_bg">
                <label for=""><?php _e( 'Background color', 'vc_mm' ); ?></label>
            </div>

            <div>
                <input type="text" class="color-field vc_color" value="<?php echo esc_attr( $settings['color'] ); ?>" id="vc_color" name="vc_color">
                <label for=""><?php _e( 'Text color', 'vc_mm' ); ?></label>
            </div>

            <div>
                <input type="text" class="color-field vc_hover_bg" value="<?php echo esc_attr( $settings['hover_bg'] ); ?>" id="vc_hover_bg" name="vc_hover_bg">
                <label for=""><?php _e( 'Hover item background', 'vc_mm' ); ?></label>

            </div>
            <div>
                <input type="text" class="color-field vc_hover_color" value="<?php echo esc_attr( $settings['hover_color'] ); ?>" id="vc_hover_color" name="vc_hover_color">
                <label for=""><?php _e( 'Hover item text', 'vc_mm' ); ?></label>

            </div>

            <div>
                <input type="text" class="color-field vc_active_bg" value="<?php echo esc_attr( $settings['active_bg'] ); ?>" id="vc_active_bg" name="vc_active_bg">
                <label for=""><?php _e( 'Active item background', 'vc_mm' ); ?></label>

            </div>

            <div>
                <input type="text" class="color-field vc_active_color" value="<?php echo esc_attr( $settings['active_color'] ); ?>"  id="vc_active_color" name="vc_active_color">
                <label for=""><?php _e( 'Active item text', 'vc_mm' ); ?></label>

            </div>

            <div>
                <input type="text" class="color-field vc_border_color" value="<?php echo esc_attr( $settings['border_color'] ); ?>" id="vc_active_color" name="vc_border_color">
                <label for=""><?php _e( 'Border color', 'vc_mm' ); ?></label>
            </div>

            <div>
                <label for=""><?php _e( 'Border', 'vc_mm' ); ?></label>
                <select class="vc_border" name="vc_border">
                    <option value=""><?php _e( 'Default', 'vc_mm' ); ?></option>
                    <?php for( $i = 0; $i<= 15; $i++ ){ ?>
                        <option <?php selected( $settings['border'], $i ); ?> value="<?php echo esc_attr( $i ) ?>"><?php printf( '%spx', $i ); ?></option>
                    <?php } ?>
                </select>
            </div>

        </dd>


        </div> <!-- /.vc-more-nav-settings -->





    </dl>
    <?php } ?>



</div>
