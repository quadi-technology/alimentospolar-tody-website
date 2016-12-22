<?php

/**
 * Ajax handle import actions
 *
 * @since: 1.3
 */
add_action( 'wp_ajax_vc_mm_import_menus', 'vc_mm_import_menus' );
function vc_mm_import_menus(){
    $nonce = $_REQUEST['_nonce'];
    if ( ! wp_verify_nonce( $nonce, 'vc_mega_menu' ) ) {
        die( 'Security check' );
    } else {
        // Do stuff here.
    }

    $import_file = VC_MM_PATH.'dummy-data/menus.xml';
    $import_file = apply_filters( 'vc_mm_import_menu_file', $import_file );

    if ( file_exists( $import_file ) ) {
        vc_mm_import_menu_file( $import_file );
        do_action( 'vc_mm_after_import_menu' );
    }

    die( 'done' );
}

/**
 * Enable import mega menus
 *
 * @since: 1.3
 */
function vc_mm_set_active_mega(){

    $menus =  array(
      'Navigation', 'Dropdown !', 'Vertical'
    );

    foreach( $menus as $menu ){
        $term = get_term_by( 'name', $menu, 'nav_menu');
        if( $term ) {
            $settings = VC_MegaMenu::default_nav_settings();
            $settings['enable'] = 1;
            update_term_meta( $term->term_id, '_vc_mm_settings',  $settings );
        }
    }
}

add_action( 'vc_mm_after_import_menu', 'vc_mm_set_active_mega');

/**
 * Import menu file
 *
 * @since: 1.3
 */
function vc_mm_import_menu_file( $file ) {

    if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);

    require_once ABSPATH . 'wp-admin/includes/import.php';

    $importer_error = false;

    if ( !class_exists( 'WP_Importer' ) ) {

        $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

        if ( file_exists( $class_wp_importer ) ){

            require_once($class_wp_importer);

        } else {

            $importer_error = true;

        }

    }

    if ( !class_exists( 'WP_Import' ) ) {

        $class_wp_import = dirname( __FILE__ ) .'/importer/wordpress-importer.php';

        if ( file_exists( $class_wp_import ) )
            require_once($class_wp_import);
        else
            $importer_error = true;

    }

    if($importer_error){

        die("Error on import");

    } else {

        if(!is_file( $file )){

            echo "The XML file containing the dummy content is not available or could not be read .. You might want to try to set the file permission to chmod 755.<br/>If this doesn't work please use the Wordpress importer and import the XML file (should be located in your download .zip: Sample Content folder) manually ";

        } else {

            $wp_import = new WP_Import();
            $wp_import->fetch_attachments = true;
            $wp_import->import( $file );
            //$this->flag_as_imported['content'] = true;

        }

    }

    do_action( 'radium_importer_after_theme_content_import');

}



/*
 * Plugin Name: Menu Exporter
 * Plugin URI: http://humanmade.co.uk
 * Description: Lets you only export your WordPress menus
 * Version: 1
 * Author: Joe Hoyle - Human Made Limited
 * Author URI: http://humanmade.co.uk/
 *
 */

/**
 * Temporarily hacks the nav_menu_item post type to set builtin to false to it shows up under eport options.
 *
 * @since 1.3
 *
 */
function me_show_menu_post_type_in_export_options() {

    global $wp_post_types;
    $wp_post_types['nav_menu_item']->_builtin = false;

}
add_action( 'load-export.php', 'me_show_menu_post_type_in_export_options' );

/**
 * The normal WordPress exporter API does not provide enough hooks etc, so we hijack the export page and run our own.
 *
 */
function me_catch_menu_export() {

    if( !isset( $_GET['download'] ) || empty( $_GET['content'] ) || $_GET['content'] !== 'nav_menu_item' )  {
        return;
    }

    ob_start();
    ob_get_clean();

    me_export_wp();

}
add_action( 'load-export.php', 'me_catch_menu_export' );

/**
 * Generates the WXR export file for download - This is a rip of export_wp but supports only exporting menus and it's terms
 *
 *
 * @param array $args Filters defining what should be included in the export
 */
function me_export_wp( $args = array() ) {
    global $wpdb, $post;

    $sitename = sanitize_key( get_bloginfo( 'name' ) );
    if ( ! empty($sitename) ) $sitename .= '.';
    $filename = $sitename . 'wordpress.' . date( 'Y-m-d' ) . '.xml';

    header( 'Content-Description: File Transfer' );
    header( 'Content-Disposition: attachment; filename=' . $filename );
    header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

    $where = $wpdb->prepare( "{$wpdb->posts}.post_type = %s", 'nav_menu_item' );
    $join = '';
    // grab a snapshot of post IDs, just in case it changes during the export
    $post_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} $join WHERE $where" );

    /**
     * Wrap given string in XML CDATA tag.
     *
     * @since 2.1.0
     *
     * @param string $str String to wrap in XML CDATA tag.
     */
    function wxr_cdata( $str ) {
        if ( seems_utf8( $str ) == false )
            $str = utf8_encode( $str );

        // $str = ent2ncr(esc_html($str));
        $str = "<![CDATA[$str" . ( ( substr( $str, -1 ) == ']' ) ? ' ' : '') . "]]>";

        return $str;
    }

    /**
     * Return the URL of the site
     *
     * @since 2.5.0
     *
     * @return string Site URL.
     */
    function wxr_site_url() {
        // ms: the base url
        if ( is_multisite() )
            return network_home_url();
        // wp: the blog url
        else
            return get_bloginfo_rss( 'url' );
    }

    /**
     * Output a term_name XML tag from a given term object
     *
     * @since 2.9.0
     *
     * @param object $term Term Object
     */
    function wxr_term_name( $term ) {
        if ( empty( $term->name ) )
            return;

        echo '<wp:term_name>' . wxr_cdata( $term->name ) . '</wp:term_name>';
    }

    /**
     * Ouput all navigation menu terms
     *
     * @since 3.1.0
     */
    function wxr_nav_menu_terms() {
        $nav_menus = wp_get_nav_menus();
        if ( empty( $nav_menus ) || ! is_array( $nav_menus ) )
            return;

        foreach ( $nav_menus as $menu ) {
            echo "\t<wp:term><wp:term_id>{$menu->term_id}</wp:term_id><wp:term_taxonomy>nav_menu</wp:term_taxonomy><wp:term_slug>{$menu->slug}</wp:term_slug>";
            wxr_term_name( $menu );
            echo "</wp:term>\n";
        }
    }

    function me_wxr_nav_menu_item_terms_and_posts( &$post_ids ) {

        $posts_to_add = array();

        foreach( $post_ids as $post_id ) {

            if( ($type = get_post_meta( $post_id, '_menu_item_type', true ) ) == 'taxonomy' ) {
                $term = get_term( get_post_meta( $post_id, '_menu_item_object_id', true ), ($tax = get_post_meta( $post_id, '_menu_item_object', true )) );

                echo "\t<wp:term><wp:term_id>{$term->term_id}</wp:term_id><wp:term_taxonomy>{$tax}</wp:term_taxonomy><wp:term_slug>{$term->slug}</wp:term_slug>";
                wxr_term_name( $term );
                echo "</wp:term>\n";
            } elseif( $type == 'post_type' && in_array( get_post_meta( $post_id, '_menu_item_object', true ), array( 'post', 'page' ) ) ) {

                $posts_to_add[] = get_post_meta( $post_id, '_menu_item_object_id', true );
            }


        }
        $post_ids = array_merge( $posts_to_add, $post_ids );
    }

    /**
     * Output list of taxonomy terms, in XML tag format, associated with a post
     *
     * @since 2.3.0
     */
    function wxr_post_taxonomy() {
        global $post;

        $taxonomies = get_object_taxonomies( $post->post_type );
        if ( empty( $taxonomies ) )
            return;
        $terms = wp_get_object_terms( $post->ID, $taxonomies );

        foreach ( (array) $terms as $term ) {
            echo "\t\t<category domain=\"{$term->taxonomy}\" nicename=\"{$term->slug}\">" . wxr_cdata( $term->name ) . "</category>\n";
        }
    }

    echo '<?xml version="1.0" encoding="' . get_bloginfo('charset') . "\" ?>\n";

    ?>
    <!-- This is a WordPress eXtended RSS file generated by WordPress as an export of your site. -->
    <!-- It contains information about your site's posts, pages, comments, categories, and other content. -->
    <!-- You may use this file to transfer that content from one site to another. -->
    <!-- This file is not intended to serve as a complete backup of your site. -->

    <!-- To import this information into a WordPress site follow these steps: -->
    <!-- 1. Log in to that site as an administrator. -->
    <!-- 2. Go to Tools: Import in the WordPress admin panel. -->
    <!-- 3. Install the "WordPress" importer from the list. -->
    <!-- 4. Activate & Run Importer. -->
    <!-- 5. Upload this file using the form provided on that page. -->
    <!-- 6. You will first be asked to map the authors in this export file to users -->
    <!--    on the site. For each author, you may choose to map to an -->
    <!--    existing user on the site or to create a new user. -->
    <!-- 7. WordPress will then import each of the posts, pages, comments, categories, etc. -->
    <!--    contained in this file into your site. -->

    <?php the_generator( 'export' ); ?>
    <rss version="2.0"
         xmlns:excerpt="http://wordpress.org/export/<?php echo '1.1'; ?>/excerpt/"
         xmlns:content="http://purl.org/rss/1.0/modules/content/"
         xmlns:wfw="http://wellformedweb.org/CommentAPI/"
         xmlns:dc="http://purl.org/dc/elements/1.1/"
         xmlns:wp="http://wordpress.org/export/<?php echo '1.1'; ?>/"
        >

        <channel>
            <title><?php bloginfo_rss( 'name' ); ?></title>
            <link><?php bloginfo_rss( 'url' ); ?></link>
            <description><?php bloginfo_rss( 'description' ); ?></description>
            <pubDate><?php echo date( 'D, d M Y H:i:s +0000' ); ?></pubDate>
            <language><?php echo get_option( 'rss_language' ); ?></language>
            <wp:wxr_version><?php echo '1.1'; ?></wp:wxr_version>
            <wp:base_site_url><?php echo wxr_site_url(); ?></wp:base_site_url>
            <wp:base_blog_url><?php bloginfo_rss( 'url' ); ?></wp:base_blog_url>

            <?php wxr_nav_menu_terms(); ?>
            <?php me_wxr_nav_menu_item_terms_and_posts( $post_ids ) ?>

            <?php do_action( 'rss2_head' ); ?>

            <?php if ( $post_ids ) {
                global $wp_query;
                $wp_query->in_the_loop = true; // Fake being in the loop.

                // fetch 20 posts at a time rather than loading the entire table into memory
                while ( $next_posts = array_splice( $post_ids, 0, 20 ) ) {
                    $where = "WHERE ID IN (" . join( ',', $next_posts ) . ")";
                    $posts = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} $where" );

                    // Begin Loop
                    foreach ( $posts as $post ) {
                        setup_postdata( $post );
                        $is_sticky = is_sticky( $post->ID ) ? 1 : 0;
                        ?>
                        <item>
                            <title><?php echo apply_filters( 'the_title_rss', $post->post_title ); ?></title>
                            <link><?php the_permalink_rss() ?></link>
                            <pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
                            <dc:creator><?php echo get_the_author_meta( 'login' ); ?></dc:creator>
                            <guid isPermaLink="false"><?php esc_url( the_guid() ); ?></guid>
                            <description></description>
                            <content:encoded><?php echo wxr_cdata( apply_filters( 'the_content_export', $post->post_content ) ); ?></content:encoded>
                            <excerpt:encoded><?php echo wxr_cdata( apply_filters( 'the_excerpt_export', $post->post_excerpt ) ); ?></excerpt:encoded>
                            <wp:post_id><?php echo $post->ID; ?></wp:post_id>
                            <wp:post_date><?php echo $post->post_date; ?></wp:post_date>
                            <wp:post_date_gmt><?php echo $post->post_date_gmt; ?></wp:post_date_gmt>
                            <wp:comment_status><?php echo $post->comment_status; ?></wp:comment_status>
                            <wp:ping_status><?php echo $post->ping_status; ?></wp:ping_status>
                            <wp:post_name><?php echo $post->post_name; ?></wp:post_name>
                            <wp:status><?php echo $post->post_status; ?></wp:status>
                            <wp:post_parent><?php echo $post->post_parent; ?></wp:post_parent>
                            <wp:menu_order><?php echo $post->menu_order; ?></wp:menu_order>
                            <wp:post_type><?php echo $post->post_type; ?></wp:post_type>
                            <wp:post_password><?php echo $post->post_password; ?></wp:post_password>
                            <wp:is_sticky><?php echo $is_sticky; ?></wp:is_sticky>
                            <?php	if ( $post->post_type == 'attachment' ) : ?>
                                <wp:attachment_url><?php echo wp_get_attachment_url( $post->ID ); ?></wp:attachment_url>
                            <?php 	endif; ?>
                            <?php 	wxr_post_taxonomy(); ?>
                            <?php
                            $postmeta = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID ) );
                            if ( $postmeta ) : foreach( $postmeta as $meta ) : if ( $meta->meta_key != '_edit_lock' ) : ?>
                                <wp:postmeta>
                                    <wp:meta_key><?php echo wxr_cdata( $meta->meta_key ); ?></wp:meta_key>
                                    <wp:meta_value><?php echo wxr_cdata( $meta->meta_value ); ?></wp:meta_value>
                                </wp:postmeta>
                            <?php	endif; endforeach; endif; ?>
                            <?php	$comments = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_approved <> 'spam'", $post->ID ) );
                            if ( $comments ) : foreach ( $comments as $c ) : ?>
                                <wp:comment>
                                    <wp:comment_id><?php echo $c->comment_ID; ?></wp:comment_id>
                                    <wp:comment_author><?php echo wxr_cdata( $c->comment_author ); ?></wp:comment_author>
                                    <wp:comment_author_email><?php echo $c->comment_author_email; ?></wp:comment_author_email>
                                    <wp:comment_author_url><?php echo esc_url_raw( $c->comment_author_url ); ?></wp:comment_author_url>
                                    <wp:comment_author_IP><?php echo $c->comment_author_IP; ?></wp:comment_author_IP>
                                    <wp:comment_date><?php echo $c->comment_date; ?></wp:comment_date>
                                    <wp:comment_date_gmt><?php echo $c->comment_date_gmt; ?></wp:comment_date_gmt>
                                    <wp:comment_content><?php echo wxr_cdata( $c->comment_content ) ?></wp:comment_content>
                                    <wp:comment_approved><?php echo $c->comment_approved; ?></wp:comment_approved>
                                    <wp:comment_type><?php echo $c->comment_type; ?></wp:comment_type>
                                    <wp:comment_parent><?php echo $c->comment_parent; ?></wp:comment_parent>
                                    <wp:comment_user_id><?php echo $c->user_id; ?></wp:comment_user_id>
                                </wp:comment>
                            <?php	endforeach; endif; ?>
                        </item>
                    <?php
                    }
                }
            } ?>
        </channel>
    </rss>
<?php
}
