<?php

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
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'wordpress' );

/** Database password */
define( 'DB_PASSWORD', 'UGC3HsXY' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define( 'AUTH_KEY',          'o9b/AR2FNR*p5h<.+9p@!8?lPWP,1+M0Ib/dYah#=q=9-JOvfB[-Jvr%-LlA0X[g' );
define( 'SECURE_AUTH_KEY',   'IjV]DwfcX2J1?7HjKpR$dbRgb[jq<?]tI|`,WLqb lr1DYcy&eu5,D=e[UPiNu;C' );
define( 'LOGGED_IN_KEY',     'K8Mozf,&ASA{T5O`lyF3wHDsVQqtiyl8wEox2%n%p8?/mI?bl6hS4(H,Nc0feN L' );
define( 'NONCE_KEY',         'pD9gM{Jg!|&p~Rt~NW5#7=`i5BGIWD;K)U)Xd>To~~z.V<Q$8ePlZ7=e-816Tjhl' );
define( 'AUTH_SALT',         'fZup=s8DT|I+G9ZP;Q7=9!O-:clV(I9_qy#-bP.@RY;H:ay,-w|^+<_9_}w,oEK,' );
define( 'SECURE_AUTH_SALT',  'Er#oi7w)u@$Y1fC@YrY13q5@JR<<-~.WM=W+qOPsOp:wLT&l%=~aUCbqh{Eoz1Fc' );
define( 'LOGGED_IN_SALT',    'YYIDqsMGQ*e?;/;El qp-??rg(^BTq5Y%u9qlVu cz@^jy<.ksxn>Wdb|7sJ;VBu' );
define( 'NONCE_SALT',        'yo6/um3?k#0B]<_)~:_xix6 .fszP,skB&lb<ghU}D%uD)L4(u#x1ioeo&Y)*QfN' );
define( 'WP_CACHE_KEY_SALT', '^a0>{@V=$w<9t#,,.f,mQmY:>cP#|3%5!@H_RGe$sW!t23mx_klTvv,N)#cC3.=Q' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


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
	define( 'WP_DEBUG', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
