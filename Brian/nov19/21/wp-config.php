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
define('DB_NAME', 'area51_wordpress');

/** MySQL database username */
define('DB_USER', 'softwa44');

/** MySQL database password */
define('DB_PASSWORD', 'AQ3nZsmkTfuDAOq');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'TXre7Jiik5xwtjJlkyWeaDc9M71CmnfRMpUvl5n95rS6uJflDXJPe8DrfCSXC1XK');
define('SECURE_AUTH_KEY', 'YDdqGzY45DVXnEKhmhfDUy2ulGq1y4DOfxoWmRuocEUiTvIZfPYddzlfqFDSdTNx');
define('LOGGED_IN_KEY', 'PGuVZGdWZW6WEJY6Ws7xHLX4zkwO8ceDQo4lp619EPEcQP5icUHBhFCA0LOFOxMb');
define('NONCE_KEY', 'WiFvfOGYEbJXbI0sJsRs7BWCM0sOMJvMQivODjxO1RUtI6zYXCMTMiwtewhUGb2j');
define('AUTH_SALT', '517NukzfzyMqJ2iYw8BJkaX3xrjjPWvAMLnx2UTOqqLEAzXGL94LPsgx0KylNUmH');
define('SECURE_AUTH_SALT', 'zBjpUTPwJ1f8MyqKRzfbG1cOC2l4AAXitpl9q06tD1PIJyrCXuTHu12nfpT3K3Wo');
define('LOGGED_IN_SALT', '1RkmtkPcnnXYUYu007PaE9hf4TNBZsslg93fDqx5C5FWD5Fjyv4XpUQ7ISwSWMa8');
define('NONCE_SALT', '82wMML2x2vGTfyL8bPXKWeXvzeMFh50VysAc0Xjd4lB00F5N3vX2wmPlTnbX00PY');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpxx_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
