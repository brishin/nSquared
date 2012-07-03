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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         '?v_9(L,aFp!a[~%>dIp[~Z1KexU[-*w -%(iT#nDRV|[bV-6NR889+-:re$kA/*X');
define('SECURE_AUTH_KEY',  '7xe!lwAgsB.H,.L9pIU+9|:I~z8JA I<{m&1kcP:S*y}If>#CTJ2OqABXfu^V3b ');
define('LOGGED_IN_KEY',    'vU6O][0E+;n$U!oFQ[oZ<Hg_-@aU;TAqgR%7} =.aNoy!NG |3R?U0[L/F|(>ReT');
define('NONCE_KEY',        '<Fa-^yVV|4Pui[]#7pmBr.R@E x$nWq:KsJWP]pm-yI+vN^ty,O;Bo|{7ju1C$Td');
define('AUTH_SALT',        'au[IXAHcp.7c1 BsJS?Ob+ZC/[:`76HU$?bO!|N>qyoo&it|tl9pU{J; |VR-w-v');
define('SECURE_AUTH_SALT', '$Vfcjn<-eCCVe3d^-zATh|=[M@<@YY+ODIc-.NyjQF5-|jI1a.,6bmeAJ[+?6hGz');
define('LOGGED_IN_SALT',   '[DsnR%%GWBJ{W+-6BDnJQ_s$ ZOjCy_G]olq-<$#Jv%<-+zXI;*/=>RQ=2*u:,-/');
define('NONCE_SALT',       'tHYdH:rC,_=USF$&NP#<@lD*M }_mex~i{6sO_Z+ V#h&__]-`VO{H)OubseU2|F');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
