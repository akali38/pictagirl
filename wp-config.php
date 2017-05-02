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
define('DB_NAME', 'pictagirl');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'Itachilord38');

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
define('AUTH_KEY',         '@uSiqx&em]k}Z:<]Y6L-Yh;d$8loJRQ6!A?G/Bduc*i.U+Qj>PMH2;EyT^9@P&mT');
define('SECURE_AUTH_KEY',  'pKbf^znW,.a7PQA]sHiN9L;n}qESa4MQM&1 v=TG]MCE:Y[*ydyP3fw(hbrMQ C4');
define('LOGGED_IN_KEY',    'q}jIQI4;gryx>`-kJW_w9Dterlu~TQ=H!)bP{4CCZo2* jEU$4#Ty|Q1^AkZ#|$5');
define('NONCE_KEY',        'BoJ:Zl/;uX1z!0SJ2u6AY.{D -O/d<[rdw#%,CPZ5#]n/:B5u&h$.`k`_wDIvghv');
define('AUTH_SALT',        ';0>QjfFpz>;4.t-B hMiki^Tmmp]Lo8f63hMYs}3X78)`t7A98iV$V;86|amEl5`');
define('SECURE_AUTH_SALT', 'W-%Yc6|`}Nybw2pX$.+vc[q[Z=qg? ^cO8jaMTg?fP^dV!^=:;*Dp>h.@K^F;_D=');
define('LOGGED_IN_SALT',   'e|nMSGH)f)|p3  d{Oq/m*YsM1Z3}??_+elZJ[bCkCpV@;!;hd3C;ssJbE[;Tg +');
define('NONCE_SALT',       'whWV`yil^n/=K9SVQ+94(cr1c}mN}YT)ftHk.%b^(BC&VT]P}ebyi/xPCLK+v0-_');

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
