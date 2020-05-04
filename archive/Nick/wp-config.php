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
define('WP_HOME','http://www.spreadinghappiness.org/');
define('WP_SITEURL','http://www.spreadinghappiness.org/');
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'managin3_spreadinghappiness');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'Nick1234');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY',         'fD2liZz4mvO5RtkRBguV6QQTJjqP2qMr0vCcokXeC2Qx1Yai23MIWNF0QD4CD74B');
define('SECURE_AUTH_KEY',  'Ag8d9F1tOaBakjTLRM65b0UnZeeNUNnMJcb6DOHwdGICo1eAjBiMTZnUuC9lo7PS');
define('LOGGED_IN_KEY',    'NE4DsoXGuypwbLb1mAEhq41B7SQgRcFu9qfKPWP8EPAV5fuKmVR3T1WD4sDR0zzM');
define('NONCE_KEY',        'XLvGVmkfBHxFE55Kymxtcqjx2FaklHGvquxcgd9CGLIg1N3cvO65gZidUhHtg59P');
define('AUTH_SALT',        '5e6A1kJTNGH3BxH1KXSNYJpqDxi2hvwwHCeSh5zeDpvvyoPSpKwHMLnQnK7BZOhz');
define('SECURE_AUTH_SALT', 'Er8luygiZJC4pFk7tGFxBjNFsLstBJ0Zvknt1VSPzgBenRcSlafMcZaMk5vagBY6');
define('LOGGED_IN_SALT',   'A42XKrZ2Jm6TKgpp2UgP5N6sHmhm6kxy9HGjD82jPkkRMTpUpOnjCBtSAISiH9Cp');
define('NONCE_SALT',       '3j5xWeEuxJjLQ91Rnp3qile6CHeqkzKK66AtqMM6TTCcluEVZrAmXf9ejp2S9Bbk');

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
