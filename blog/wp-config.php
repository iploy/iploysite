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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db380691317');

/** MySQL database username */
define('DB_USER', 'dbo380691317');

/** MySQL database password */
define('DB_PASSWORD', 'ilxlxWbukwxbukxbk0*72V');

/** MySQL hostname */
define('DB_HOST', 'localhost:/tmp/mysql5.sock');

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
define('AUTH_KEY',         'D[3)4;$j%l)_%<Qd4cRZF]5_CQHAg#2+NHX+KJ$kTQ&q2)4}+Z1WeJ:rUKS%vV-E');
define('SECURE_AUTH_KEY',  '|}ni?1De5NYZBR:`1FXku0LDWqn{l&>LB@;XCE_7Otvw=|cXE.1j7j.%/CFm)Tw9');
define('LOGGED_IN_KEY',    '3K3W*za$Oc+utg;b^[+fIWt]tXHW-JB:pv#-Ye0&])W.[S+EpZ|.?5iC%-6N*x=e');
define('NONCE_KEY',        '$#8@MVh`^Onz?A@0X ;YuQ2PdRCgPZva->}-s$T;F8Lqb6,E^2JO[kv&X+1~Qj3M');
define('AUTH_SALT',        'RdTUIEX e(uf*&n`=@9e}M6pYaZ7?|$#WfR)N|O,r-y+P:E(<;37U<Q~qNeCB3BC');
define('SECURE_AUTH_SALT', 'PN,FA,JI~?V*)5!MX][;*|@K0)=xQkp6TZ)`s7TS1sV=G~kC$~Dm9}eeactOm+xB');
define('LOGGED_IN_SALT',   '@jt>pm<oB2(7=G+E`v BOO82jIDi;Svp< RPfJ+Fh=+tT[W&-cagt)/N3oxIO~EC');
define('NONCE_SALT',       '0,xcPYiVY+U|d]{yGU8laoZ7MeQ6>SZ;|i.Qs)Z6GzXQv|ob+[tX$0[zmxuy-C~M');

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
