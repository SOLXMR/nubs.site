<?php
/** Database settings */
define( 'DB_NAME', 'nubsmrbf_wp824' );
define( 'DB_USER', 'nubsmrbf_wp824' );
define( 'DB_PASSWORD', '9xPX7x3(2[S--!p2' );
define( 'DB_HOST', 'localhost' );
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

/** Authentication Unique Keys and Salts */
define( 'AUTH_KEY',         'xj41wyqjgniynm8rg2ru16vkmik8at2jllqyjhdpftxabagslfwoqfkpsnydjdhz' );
define( 'SECURE_AUTH_KEY',  '2ur7totknpmwrcr70g36mydu2ajp1oezfs3i9qztnuxzckrt58i3ahckwgbzgsyv' );
define( 'LOGGED_IN_KEY',    'jjleaimdd7zikz6p7qihadmfv0ublqbeprhv21xqs08dtntvrhjpidq6ofuot4fe' );
define( 'NONCE_KEY',        '1cdby0tsqs6cweisf3jcqkrrq7tkllmwlidi1poijbupb447vrbz2b8g9gbke2xa' );
define( 'AUTH_SALT',        'qhjybhfx2sidpfzbha35gsim5kddyv1kde6gqec4ylwtj3dk9g7fg6qwzybvdaol' );
define( 'SECURE_AUTH_SALT', '5ltxrur1yu4mjcxtywls306jmiprp0uoutpb3ubz8wnrhoklhnwaurmccnfwzm0p' );
define( 'LOGGED_IN_SALT',   'jbuxkhaaeubta82hltjcyunvb4bxhn6wmxpzyco5cga3lfhzgfilqgdacnrotqdb' );
define( 'NONCE_SALT',       '1gq4pdrby5hu2apuozqrxz7pxdsrhqatwnmkvgqmvifkmpxbsntqtwq3hjnjt0ap' );

/** WordPress Database Table prefix. */
$table_prefix = 'wp3a_';

/** WordPress Debugging settings */
define( 'WP_DEBUG', false );

/** Additional Security */
define('DISALLOW_FILE_EDIT', true);  // Disable file editing
define('WP_POST_REVISIONS', 5);      // Limit post revisions
ini_set('display_errors', 0);        // Disable error display
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/your/log/file.log');  // Define error log path

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
