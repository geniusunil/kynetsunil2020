<?php
define('WP_CACHE', true); // Added by WP Rocket
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
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
define('AUTH_KEY', 'vR8HHFZ2kT0ovwyW53bdCBsrdt7pyZF6ZsM1QqlPlP4wsKRVzVTNflDgGnTu6G5x');
define('SECURE_AUTH_KEY', 'VUHpIe9ofw7V4DqOBbwvVWrk3ZT86jy8NLGVbbGmuff2hgOdhdsH4kJGIohTBQpy');
define('LOGGED_IN_KEY', 'eCpvNJEy2Fdi9F2VODFjsrhqKtEC4BNKfGLlEtP9xMOTYAcbt4ox7Sk7LWpLBJKD');
define('NONCE_KEY', '2sYronB01F4cGlbvomUQBh7xMq9Gz6zOY54OTXJl3y1yUgJKLHHXaY90w3EspTKu');
define('AUTH_SALT', '4MYU2UYvowcUycsFP2Gpleh7N15TQFdmD0XGhK580AxBTCmEgxVIsNA4bLOUfaaO');
define('SECURE_AUTH_SALT', 'uIJNbNvlNvg7aPc3mkliA7dkP4j8qqc1igVEPfturTV4SHlGB9Y4gLLYEHKWxT1l');
define('LOGGED_IN_SALT', 'rrfldBSL92AYjwEM3sdFPyOjz9BAvCqI5ePzn7vos3ehYsAh8ltnwWUf7O3OqvWb');
define('NONCE_SALT', 'NCvuKG5LOM0nc2vwbVZK1ZMN79oJCLFBc3cr2Fw6qV1EDfbeqz5UWEWuMv6UzdOn');

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
