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
define('DB_NAME', 'saasscout_wordpress');

/** MySQL database username */
define('DB_USER', 'saasscout');

/** MySQL database password */
define('DB_PASSWORD', 'uwdnpCQuAk0PJjS');

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
define('AUTH_KEY', 'Qw4WZEn9nQ79wtqdkOlcGMXNi58nUKjPDpnF740ce4xzu5TQk9IEnGpCOAaXIZAE');
define('SECURE_AUTH_KEY', 'ww2EruyZlN6xtzuPoao0MEqHfFZlCXLmV2UfBr60wVScopTSPxqCmJYJwUixYCmL');
define('LOGGED_IN_KEY', '3NZLSFdWmDxqezm2j45qX1sBnT5x8VygmvevRiL44q6VvT8XanQ7oKcg0t2ZcXLf');
define('NONCE_KEY', '2U9bQIJ1I3RAO61ByN6S839kzODxj5gcGEw3nvn6osaNteoSpfq6phcEP5d8UER1');
define('AUTH_SALT', '0dDlscrh4Im48LxJpoCAcbTtxWwcFKI2KpRpW0OFdNEDv5dczDgHBrDB9JlGlsga');
define('SECURE_AUTH_SALT', 'A2G3Dm6y4C6j9ELQYs2cfhfgdiCWj92Nl74MvAG7PFrodBfe9HwT1idUVJAz4oSS');
define('LOGGED_IN_SALT', '29icD7KufVcFoYgHr2SQPg3GxBo6iJRGl738j2JHszwGGkLe8sSHEruP07RzXaIK');
define('NONCE_SALT', 'Tp73QaKQ1Nk0z6wEBXTiCGNPgRCS1CSzXvclqos57paDLgAP48huxYMrRkncaQ4l');

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
