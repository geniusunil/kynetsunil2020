<?php
define('WP_CACHE', false); // Added by WP Rocket
// BEGIN A2 CONFIG
// END A2 CONFIG
define( 'WP_MEMORY_LIMIT', '64M' );
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
 
//define('WP_HOME','http://softwarefindr.com');
//define('WP_SITEURL','http://softwarefindr.com');
//http://www.inmotionhosting.com/support/website/wordpress/wordpress-changing-the-site-url-and-home-settings 
 
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'area52_wordpress');
/** MySQL database username */
define('DB_USER', 'softwa44');
/** MySQL database password */
define('DB_PASSWORD', 'AQ3nZsmkTfuDAOq');
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
define( 'AUTH_KEY', ';(/xi~?R.tpKyovf/[{}^$ycQl9mjfjs5Lij^q|0qk k^~C$I8gDB&JA=jC-)InL' );
define( 'SECURE_AUTH_KEY', ',e?[~|1O[4~8?>.@+QmKtj%{XJ[guM)_<4b5S*LXAGqKby08EY?-n<3,)PNs8=i(' );
define( 'LOGGED_IN_KEY', ':T9F- s+]o l7Hl.>b!q3-+JIDu Q#!Q^YP{31@{wP/X3H/Ajk1[!Oafi$[2b=*U' );
define( 'NONCE_KEY', 'DodE&H=sKsL3WjGlox`CD Hhxr.;,UH33+5K,/{^sx@a.iOfzEp;O;LVjIAvS,(@' );
define( 'AUTH_SALT', 'x^H%8AOO1JvoXk*hw?xnwn~&@<no)wYCu(p$M=oBRa2nIV~F,cKV4T{aNKv^5[3R' );
define( 'SECURE_AUTH_SALT', 'XezFOGfKJSR^^V2x3ho>,?%ZEd<to I9Bv[Ghy-0R@,=x.}_,@xeHr8Tm.7J_JCn' );
define( 'LOGGED_IN_SALT', 'jh_aB]R=Var~1F$c mIW_}ag:F@-F~|-H8,^pTFo(E<!<7/$R` G/&Y*Nxu~P16D' );
define( 'NONCE_SALT', 'kZ#1b7^}]FBmID6tCz_C>ebXb!ea?EUA2ayz~U?*1<x)r@+-^)UF+6!`;gecbHG`' );
/**#@-*/
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpxx_';
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
define('WP_ALLOW_REPAIR', true);
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG', false );
/* That's all, stop editing! Happy blogging. */
/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
