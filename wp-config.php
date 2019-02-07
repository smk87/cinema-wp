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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '8~]F<Yu$|Sh~+)c}Kw,_5 x3J&^t|Qy80k?#hGe_~Ex)f}Rt M[Ec)j_:+16}D~h');
define('SECURE_AUTH_KEY',  'l!MBlJwy1=d+O0Sp(F9GtlS4!}PepZ|~ecCz_fHnlE$^_1?{pi&3EB@&uhwF)cWC');
define('LOGGED_IN_KEY',    'AtqA[vm&R7D/rJ0 &Lnb5@>T+zzhM`<BeoO5Ool;p%4*%^/p 6+jC_g@||g|zwR&');
define('NONCE_KEY',        '4t7Ifzswq,#U/aY:|5q]<;,%}2<!zl-]Y?:x?1d5sW7^ v1g^x%,1tokSpWP!8kP');
define('AUTH_SALT',        'h<kdo$c-7028K5+DNBU_h>balm^$58.Wcd:-dQh/KR90Ztblxbnnl1o1~BfZ]h^q');
define('SECURE_AUTH_SALT', '}UZ$$Q(<t#TQ,w=f>J>+$ml/FQIE<`Slil[K{7mO5m,m-SFZ3M60K^5!KJbw]c/p');
define('LOGGED_IN_SALT',   'ur!=~dmD:c;Z8bMTqUjX3,k H:&};wBl?^$y`X7peCFoap)XCb^4wXp?_GF)s-M2');
define('NONCE_SALT',       '&]:oz-mnb^1CU>~;r:J6fxA=nLj>H0+vpxx5b{6{y7X/9{ s}_zI9y_{EDC!e<wO');

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
