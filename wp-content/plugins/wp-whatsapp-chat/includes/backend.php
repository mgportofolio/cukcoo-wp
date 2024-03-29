<?php

class QLWAPP_Admin {

  protected static $instance;

  function init() {
    add_action('wp_ajax_qlwapp_get_posts', array($this, 'ajax_get_posts'));
    add_filter('default_option_qlwapp', array($this, 'generate_db'));
    add_filter('sanitize_option_qlwapp', 'wp_unslash');
    add_action('admin_enqueue_scripts', array($this, 'add_js'));
    add_action('admin_head', array($this, 'add_css'));
  }

  function generate_db() {
    $db = new QLWAPP_Model();
    return $db->options();
  }

  function includes() {
    include_once (QLWAPP_PLUGIN_DIR . 'includes/controllers/WelcomeController.php');
    include_once (QLWAPP_PLUGIN_DIR . 'includes/controllers/ButtonController.php');
    include_once (QLWAPP_PLUGIN_DIR . 'includes/controllers/BoxController.php');
    include_once (QLWAPP_PLUGIN_DIR . 'includes/controllers/ContactController.php');
    include_once (QLWAPP_PLUGIN_DIR . 'includes/controllers/DisplayController.php');
    include_once (QLWAPP_PLUGIN_DIR . 'includes/controllers/SchemeController.php');
    include_once (QLWAPP_PLUGIN_DIR . 'includes/controllers/PremiumController.php');
    include_once (QLWAPP_PLUGIN_DIR . 'includes/controllers/SuggestionsController.php');
  }

  function add_css() {
    ?>
    <style>
      @font-face {
        font-family: 'qlwf-whatsapp';
        src: url(data:application/x-font-woff;charset=utf-8;base64,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) format('woff');
        font-weight: normal;
        font-style: normal;
      }

      #toplevel_page_qlwapp .wp-menu-image img {
        height: 16px;
      }
    </style>
    <?php

  }

  function add_js() {
    if (isset($_GET['page']) && strpos($_GET['page'], QLWAPP_DOMAIN) !== false) {
      wp_register_style('qlwapp-select2', plugins_url('/assets/css/qlwapp-select2' . QLWAPP::is_min() . '.css', QLWAPP_PLUGIN_FILE), array('wp-color-picker'), QLWAPP_PLUGIN_VERSION, 'all');

      wp_enqueue_style('qlwapp-admin', plugins_url('/assets/css/qlwapp-admin' . QLWAPP::is_min() . '.css', QLWAPP_PLUGIN_FILE), array('wp-color-picker', 'qlwapp-select2'), QLWAPP_PLUGIN_VERSION, 'all');

      wp_register_script('qlwapp-select2', plugins_url('/assets/js/select2.min.js', QLWAPP_PLUGIN_FILE), array('jquery'), QLWAPP_PLUGIN_VERSION);
      wp_enqueue_script('qlwapp-admin', plugins_url('/assets/js/qlwapp-admin' . QLWAPP::is_min() . '.js', QLWAPP_PLUGIN_FILE), array('jquery', 'qlwapp-select2', 'wp-color-picker'), QLWAPP_PLUGIN_VERSION, true);

      wp_localize_script('qlwapp-admin', 'qlwapp', array(
          'nonce' => array(
              'qlwapp_get_posts' => wp_create_nonce('qlwapp_get_posts'),
      )));
    }
  }

  function ajax_get_posts() {

    if (current_user_can('manage_options')) {

      if (!empty($_REQUEST) && check_admin_referer('qlwapp_get_posts', 'nonce')) {

        $data = array(
            array('none', esc_html__('Exclude from all', 'wp-whatsapp-chat'))
        );

        $args = array(
            'post_type' => sanitize_key($_REQUEST['name']),
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page' => 10,
            'exclude' => array_map('intval', (array) $_REQUEST['selected'])
        );

        if ($_REQUEST['q']) {
          $args['s'] = sanitize_text_field($_REQUEST['q']);
        }

        $posts = get_posts($args);

        foreach ($posts as $post) {
          $data[] = array($post->ID, mb_substr($post->post_title, 0, 49));
        }

        wp_send_json($data);
      }
    }

    wp_die();
  }

  public static function instance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
      self::$instance->includes();
      self::$instance->init();
    }
    return self::$instance;
  }

}

QLWAPP_Admin::instance();

