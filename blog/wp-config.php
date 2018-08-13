<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'blog_awk');

/** MySQL database username */
define('DB_USER', 'blog_awk');

/** MySQL database password */
define('DB_PASSWORD', 'awkloca17');

/** MySQL hostname */
define('DB_HOST', 'blog_awk.mysql.dbaas.com.br');
#define('DB_HOST', 'blog.awktec.com');

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
define('AUTH_KEY',         'j2wd6iaoicscubxsp5xiqus6my9ehjjv4hfq3noqfht3hjrjrs3dnnwsnfpwqjab');
define('SECURE_AUTH_KEY',  '55mrc9wofija7evocntuakaatix2lcazrik2vrfe9vvyh27tlvoynrderem3gj7a');
define('LOGGED_IN_KEY',    'c2irwohqspzeuro9tllsqwsn7zeu5jteuxufijdarlwpafgg4onka3fdogjiivk9');
define('NONCE_KEY',        'ocvwm7vaqrtemvzpnolm4thwctj4ebpfsljz5v6tcl3qvudnvy8vdevlpyiqyrey');
define('AUTH_SALT',        '9f3nc58w5s3gwqyvpedrgi8qu7ikjik5eo66fqtm4sfkr2cq0zfilpunmirnwhhl');
define('SECURE_AUTH_SALT', '5cvlbfcgotlrdi4n4ax75xbqsh2kqzriguj9nahia9zobecxq738gftccmoqyyo0');
define('LOGGED_IN_SALT',   'grxnw0oqdqvicrlkmjrgliox0fjawhoqfgkrgjtwmxchkplmcuqdpq0mhoxet5rb');
define('NONCE_SALT',       '34xomz6ssjmuh3johbti2ba9bf6sjyxyr6pwzifbfpngjcredu7lcitnekyrffvv');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
