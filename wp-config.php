<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('WP_CACHE', true); //Added by WP-Cache Manager
define( 'WPCACHEHOME', '/Volumes/Files/yecidfgomezc/Documents/repositorios/define.qdata.io/wp-content/plugins/wp-super-cache/' ); //Added by WP-Cache Manager
define('DB_NAME', '687622_todydef');

/** MySQL database username */
define('DB_USER', '687622_todydef');

/** MySQL database password */
define('DB_PASSWORD', 'N58],h#wK>>T');

/** MySQL hostname */
define('DB_HOST', 'mariadb-135.wc1.ord1.stabletransit.com');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '27]F_B"$:yE%=eLv#AW?5Oa?9JV]xqT{xSCh/x:=6xZPM8-mJEbb/l|06V]0=S{h');
define('SECURE_AUTH_KEY',  '8yz|7s_z1J>aS9w|bIa25a2x71xQTt92351wxK7{/T6KpDJA_UDN5F-zHf>]JWZa');
define('LOGGED_IN_KEY',    '2oG}0b:2509;DStna7!^9SdQ90PfWdpmB7k9*e!Ke)DSz7^KAOoK4B|$CO.}3gj2');
define('NONCE_KEY',        '+Eb4S_b73Pn0j0LUO02T92vLRlIhC3jn4u?We0r&PO*0Pqr2}uW6x,S<b]3N2n@7');
define('AUTH_SALT',        'SZr7ZTLd6q=VQbYOGjU$8aZj948@?GFG48b4oW&u=2?A#M<h6H;%SR{3j5B88*Od');
define('SECURE_AUTH_SALT', '8yz|7s_z1J>aS9w|bIa25a2x71xQTt92351wxK7{/T6KpDJA_UDN5F-zHf>]JWZa');
define('LOGGED_IN_SALT',   '82n%AL41dru727@5836eK+7TPV!S&IWLL)o_tsKGT%Oj=npR?v7yh%,7xZnc6)XS');
define('NONCE_SALT',       '1=B;c=y7Z=?J8d|{J6u1Hkv^-S,,ANH|x)/J4pera2!ig0eZCJo-4TXM9NBJ](Iv');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);

define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST']);
define('WP_CONTENT_URL', '/wp-content');
define('DOMAIN_CURRENT_SITE', $_SERVER['HTTP_HOST']);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
