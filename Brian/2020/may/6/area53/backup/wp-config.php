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
define('DB_NAME', 'area53_wordpress');

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
define('AUTH_KEY', 'TPxpfIKBQPyKMXxkIO8FLEWxqS8Gqgg03SmD93ebyh5tD9gONZCxyoVE6BmEE8ec');
define('SECURE_AUTH_KEY', 'z2bAHuCVg1OzBlTgYr3WynsFmrksbUUfnuIuK53gBJtEJdRuC6BOxlcIObcQOvfP');
define('LOGGED_IN_KEY', 'zOWChSijyTr8kUQZweUR6GZ6lxaeRAOHHcIKMFuxLTGMFZrAcpRnkgCSDZy8wDhM');
define('NONCE_KEY', 'AjtiM6qeMMvPtU7aFmRSSKalKFPq6GdtYasF6eFmLlzKJ5wHx3PnUICiCHcvW5PC');
define('AUTH_SALT', 'sLkpdte5gRGObr9hzPLyzjc7CueliJULlzaHnV9Pm3PTiymFqE8ulIPI3wTqPnGZ');
define('SECURE_AUTH_SALT', 'URQnObBXSOZnVcyzZoxnM3nhG3QwauXYtYQuFvNS76clhEXDMlwMef7GaoVnS99N');
define('LOGGED_IN_SALT', 'Nfom7nzVrCvxIevb5EyPYeN4F3ZkHrzRo2628AIzyCXRNJeq500Zf41O6uYC8RCy');
define('NONCE_SALT', 'gkQuruXpyzDHf1LFDmxKKxjDRDdSemd5krf072FzzXGC6wtZdJgW0Cio0488Poe3');

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
