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
define( 'DB_NAME', 'newdb' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         '7P8((k1bzo`f-SoYH::l&]_sr128=~dBNpBZfB#z1WM$5uMfQz)OxUmd8hw0z=,x' );
define( 'SECURE_AUTH_KEY',  '|:!9WDo+ Df9NZ;2^!fyf&Vvz/m078kN:DcCFX>HKc?:NS%nc9dKD2E)NV}XKQF ' );
define( 'LOGGED_IN_KEY',    'i`f!|W?Ysz(6*aR*!KN;i&#Mr]Mgt 4_*xy_b$_.hgn3?r4zk*g?:n!svz?zR~):' );
define( 'NONCE_KEY',        'Y7wtm&c%{DM<O(AiQ; L|zjw*#j`y34P<>6~8.FPff1Pc+c5*q$x8%Zw(kN}TT`m' );
define( 'AUTH_SALT',        'z@p^T=czB~OHT-XLnm+h-S9IwTIs~0U|h|0(pRICt]4?S-p 5Jx BLwaZ9+1W/? ' );
define( 'SECURE_AUTH_SALT', 'c 6m-3z]+~.WAzO^b:3l&x&3hIHNyH94<pWq94f xu|8.delqNaXvNp;5S^2W.OY' );
define( 'LOGGED_IN_SALT',   'pt;?;L.yzAdfK`5PW4qJU{)gQ7o9o g^&*lDM_e]dsQJ~4p9VrEBAI$}Vdeq-_z/' );
define( 'NONCE_SALT',       ';zT+hRan4f._9fST;akcqVBW)x_&/:b;4Z~y`2@H%ehGLdK+}sEFVmgfJv${Df3[' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_newdb';

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
