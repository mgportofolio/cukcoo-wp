<?php

/**
 * Plugin Name:       WhatsApp Chat
 * Plugin URI:        https://quadlayers.com/portfolio/wordpress-whatsapp-chat/
 * Description:       WhatsApp Chat allows your visitors to contact you or your team through WhatsApp chat with a single click.
 * Version:           4.5.2
 * Author:            QuadLayers
 * Author URI:        https://quadlayers.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-whatsapp-chat
 * Domain Path:       /languages
 */
if (!defined('ABSPATH')) {
  die('-1');
}
if (!defined('QLWAPP_PLUGIN_NAME')) {
  define('QLWAPP_PLUGIN_NAME', 'WhatsApp Chat');
}
if (!defined('QLWAPP_PLUGIN_VERSION')) {
  define('QLWAPP_PLUGIN_VERSION', '4.5.2');
}
if (!defined('QLWAPP_PLUGIN_FILE')) {
  define('QLWAPP_PLUGIN_FILE', __FILE__);
}
if (!defined('QLWAPP_PLUGIN_DIR')) {
  define('QLWAPP_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR);
}
if (!defined('QLWAPP_PREFIX')) {
  define('QLWAPP_PREFIX', 'qlwapp');
}
if (!defined('QLWAPP_DOMAIN')) {
  define('QLWAPP_DOMAIN', QLWAPP_PREFIX);
}
if (!defined('QLWAPP_WORDPRESS_URL')) {
  define('QLWAPP_WORDPRESS_URL', 'https://wordpress.org/plugins/wp-whatsapp-chat/');
}
if (!defined('QLWAPP_REVIEW_URL')) {
  define('QLWAPP_REVIEW_URL', 'https://wordpress.org/support/plugin/wp-whatsapp-chat/reviews/?filter=5#new-post');
}
if (!defined('QLWAPP_DEMO_URL')) {
  define('QLWAPP_DEMO_URL', 'https://quadlayers.com/portfolio/wordpress-whatsapp-chat/?utm_source=qlwapp_admin');
}
if (!defined('QLWAPP_PURCHASE_URL')) {
  define('QLWAPP_PURCHASE_URL', QLWAPP_DEMO_URL);
}
if (!defined('QLWAPP_SUPPORT_URL')) {
  define('QLWAPP_SUPPORT_URL', 'https://quadlayers.com/account/support/?utm_source=qlwapp_admin');
}
if (!defined('QLWAPP_DOCUMENTATION_URL')) {
  define('QLWAPP_DOCUMENTATION_URL', 'https://quadlayers.com/documentation/whatsapp-chat/?utm_source=qlwapp_admin');
}
if (!defined('QLWAPP_GROUP_URL')) {
  define('QLWAPP_GROUP_URL', 'https://www.facebook.com/groups/quadlayers');
}

if (!class_exists('QLWAPP')) {
  include_once( QLWAPP_PLUGIN_DIR . 'includes/qlwapp.php' );
}

register_activation_hook(QLWAPP_PLUGIN_FILE, array('QLWAPP', 'do_activation'));
