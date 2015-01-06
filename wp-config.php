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
define('DB_NAME', 'db02904178ec5b421c9b5ba413016683fd');

/** MySQL database username */
define('DB_USER', 'zumrrboatotompbh');

/** MySQL database password */
define('DB_PASSWORD', 'rB2GEWYMksRVSwdQk43R62fuAgb5uwfwcqrnZGzh6YDvuUrwDdxGSuvywFfyV4rV');

/** MySQL hostname */
define('DB_HOST', '02904178-ec5b-421c-9b5b-a413016683fd.mysql.sequelizer.com');

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
define('AUTH_KEY',         'i;~3#o>xBtV5X#e{Q>&xOy>Lx~M4J|a+QXQw.bKq6HXw@Ugp}WJu+DMQN|/:@Elc');
define('SECURE_AUTH_KEY',  '[TKw.w?NBAb=pfJb${Q{tjHd$fS::L3tDA9ztj,Lu5c%E8,|a?73O]16BKF};bcK');
define('LOGGED_IN_KEY',    'FGcc);mJ&(k-gy9m?@6Dsn$Jxk=:.J0Fr!_bSy5?v640u!WGPK2l}~G~MQ3hBXqe');
define('NONCE_KEY',        'vF3pwzE,a<;EUh[~v:J+DR<-!quF%u>TB8ZzBid61Gue0SbrFpgdR|G7dml9!7Uo');
define('AUTH_SALT',        '{-MaDz:gg.2Z#eP0jvV1IK~gru1AnQo~I,WbxHMvkDXlPQUBJ|, 6W^tA2)Qy4&`');
define('SECURE_AUTH_SALT', '-TMeHa=QP0(8NVITxmiBg:%LL5r=Vr0c5Kj;iB#Movn;SQV}L$U{@VAD0[hO+]f~');
define('LOGGED_IN_SALT',   'n8B9wLURq^lDj,~o[ez(p[^J<|ok^H+41t-$J,` zRW`Ca fZ1/9(-:[++cR r7=');
define('NONCE_SALT',       '4f?QjXBq{Uw~OL=Fq&RUjHmtwIEWnUIr%})8*H,lV=zDr=ECrFbFfe0pS ^;W2bk');

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
