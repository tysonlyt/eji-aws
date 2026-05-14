<?php
define( 'WP_CACHE', false ); // By Speed Optimizer by SiteGround

define('FORCE_SSL_ADMIN', true);

/** WP 2FA plugin data encryption key. For more information please visit melapress.com */
define( 'WP2FA_ENCRYPT_KEY', 'g7OdmB6a5kH5spKZmNN0yg==' );

/** WP 2FA plugin data encryption key. For more information please visit melapress.com */

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'dblxnsylfsgmfe' );

/** Database username */
define( 'DB_USER', 'u7lgdk8icohc6' );

/** Database password */
define( 'DB_PASSWORD', 'oryxbgjomewa' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '?98{%C!$?H@rz,d-&yVl=z:>VKVG+A*jFQls:xYLo6a[zSsRR>DJIq8J.%^4>=vw' );
define( 'SECURE_AUTH_KEY',   'C& utm_^M7?~$VNb6K+Buq]W~TO+(.x5KaV/nZF_N-Dnp(&(uw4X>-*x4lQs23Pd' );
define( 'LOGGED_IN_KEY',     'J2M5D*w;Ox;%zjzE!*LPz3NCrFzoX5Lyq$_gqqDu?T4?+y0{?(idcyG]bo_R<50t' );
define( 'NONCE_KEY',         'a3|S {~9]$=r<~U@p,O56RQnth7nCwbpv-@30-p6p%AZLGjXT_^XQCyp5@z~7&i1' );
define( 'AUTH_SALT',         '~BR`;OoG?-tnDj+ +hiso/v YcWmC;$_:=Cfq?Hel[4Y%h8<<L*~5 ZkRQ|ew__z' );
define( 'SECURE_AUTH_SALT',  '6gnK+H3mD?N;:qablX<4kSzhqOv92#F&wQ/7_,KXV{.eljb{.O[Fm8,kT&x^TT~[' );
define( 'LOGGED_IN_SALT',    'qW^6d;Rhg?>+yRW+t=Sq7_p2W8qVco{;5GA4k/*Ht#m0) 2pJlLC.||E&_7I) +.' );
define( 'NONCE_SALT',        'RWm,kl ]&?MUleT4LViw_Ml=&wQ9np<#HEl@AAkBaxMkojdaR~L4`_x| v{7;/]U' );
define( 'WP_CACHE_KEY_SALT', 'Lt:00!;#rNO@/w_>;*Dp+Rck6z-m834[]+6XhJ[gL1VTr#~_h8EOpk Qe]2do8l5' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wyo_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	//define( 'WP_DEBUG', false );
}
define('WP_DEBUG', false); // Enable debugging
define('WP_DEBUG_LOG', false); // Save errors to wp-content/debug.log
define('WP_DEBUG_DISPLAY', false);


define( 'WP_MEMORY_LIMIT', '768M' );

define( 'DUPLICATOR_AUTH_KEY', '=S&`WXHJ::?e>$v8) *g>WE,!KfX65YtU0X1V`^b)9.>h^+7C:)>E+}%B<Ft@Ej_' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
@include_once('/var/lib/sec/wp-settings-pre.php'); // Added by SiteGround WordPress management system
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
