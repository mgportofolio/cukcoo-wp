<?php
if (!class_exists('QLWAPP_Frontend')) {

  class QLWAPP_Frontend {

    protected static $instance;

    function add_js() {
      wp_enqueue_style(QLWAPP_DOMAIN, plugins_url('/assets/css/qlwapp' . QLWAPP::is_min() . '.css', QLWAPP_PLUGIN_FILE), null, QLWAPP_PLUGIN_VERSION, 'all');
      wp_enqueue_script(QLWAPP_DOMAIN, plugins_url('/assets/js/qlwapp' . QLWAPP::is_min() . '.js', QLWAPP_PLUGIN_FILE), array('jquery'), QLWAPP_PLUGIN_VERSION, true);
    }

    function add_box() {

      global $qlwapp;

      include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Box.php');
      include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Contact.php');
      include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Display.php');
      include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Button.php');

      $box_model = new QLWAPP_Box();
      $contact_model = new QLWAPP_Contact();
      $button_model = new QLWAPP_Button();
      $display_model = new QLWAPP_Display();

      $contacts = $contact_model->get_contacts_reorder();
      $display = $display_model->get();
      $button = $button_model->get();
      $box = $box_model->get();

      if ($show = apply_filters('qlwapp_box_display', '__return_true')) {

        if (is_file($file = apply_filters('qlwapp_box_template', QLWAPP_PLUGIN_DIR . 'template/box.php'))) {
          include_once $file;
        }
      }
    }

    function add_frontend_css() {
      $scheme_model = new QLWAPP_Scheme();
      $scheme = $scheme_model->get();
      ?>
      <style>
        :root { 
          <?php
          foreach ($scheme as $key => $value) {
            if ($value != '') {
              printf('--%s-scheme-%s:%s;', QLWAPP_DOMAIN, $key, $value);
            }
          }
          ?>
        }
        <?php if ($scheme['brand']): ?>
          #qlwapp .qlwapp-toggle,
          #qlwapp .qlwapp-box .qlwapp-header,
          #qlwapp .qlwapp-box .qlwapp-user,
          #qlwapp .qlwapp-box .qlwapp-user:before {
            background-color: var(--qlwapp-scheme-brand);  
          }
        <?php endif; ?>
        <?php if ($scheme['text']): ?>
          #qlwapp .qlwapp-toggle,
          #qlwapp .qlwapp-toggle .qlwapp-icon,
          #qlwapp .qlwapp-toggle .qlwapp-text,
          #qlwapp .qlwapp-box .qlwapp-header,
          #qlwapp .qlwapp-box .qlwapp-user {
            color: var(--qlwapp-scheme-text);
          }
        <?php endif; ?>
      </style>
      <?php
    }

    function box_display($show) {

      global $wp_query;
      $display_model = new QLWAPP_Display();
      $display = $display_model->get();
      if (is_customize_preview()) {
        return true;
      }

      if (count($display['target'])) {

        if (is_front_page() || is_home() || is_search() || is_404()) {
          $show = false;
        }

        if (is_front_page() && in_array('home', $display['target'])) {
          return true;
        }

        if (is_home() && in_array('blog', $display['target'])) {
          return true;
        }

        if (is_search() && in_array('search', $display['target'])) {
          return true;
        }

        if (is_404() && in_array('error', $display['target'])) {
          return true;
        }
      }

      if (is_archive() && isset($wp_query->queried_object->taxonomy)) {

        if (isset($display[$wp_query->queried_object->taxonomy]) && count($display[$wp_query->queried_object->taxonomy])) {

          $show = false;

          if (in_array($wp_query->queried_object->term_id, $display[$wp_query->queried_object->taxonomy])) {
            return true;
          }

          //backward compatibility for $term->name
          if (in_array($wp_query->queried_object->slug, $display[$wp_query->queried_object->taxonomy])) {
            return true;
          }
        }
      }

      return $show;
    }

    function do_shortcode($atts, $content = null) {

      $button_model = new QLWAPP_Button();
      $button = $button_model->get();

      $atts = wp_parse_args($atts, $button);

      ob_start();
      ?>
      <div style="width: auto;" id="qlwapp" class="qlwapp-js-ready <?php printf("qlwapp-%s qlwapp-%s", esc_attr($atts['layout']), esc_attr($atts['rounded'] === 'yes' ? 'rounded' : 'square')); ?>">
        <a class="qlwapp-toggle" data-action="open" data-phone="<?php echo esc_attr($atts['phone']); ?>" data-message="<?php echo esc_html($atts['message']); ?>" href="#" target="_blank">
          <?php if ($atts['icon']): ?>
            <i class="qlwapp-icon <?php echo esc_attr($atts['icon']); ?>"></i>
          <?php endif; ?>
          <i class="qlwapp-close" data-action="close">&times;</i>
          <?php if ($atts['text']): ?>
            <span class="qlwapp-text"><?php echo esc_html($content); ?></span>
          <?php endif; ?>
        </a>
      </div>
      <?php
      return ob_get_clean();
    }

    function init() {
      add_action('wp_enqueue_scripts', array($this, 'add_js'));
      add_action('wp_head', array($this, 'add_frontend_css'), 200);
      add_action('wp_footer', array($this, 'add_box'));
      add_filter('qlwapp_box_display', array($this, 'box_display'));
      add_shortcode('whatsapp', array($this, 'do_shortcode'));
    }

    public static function instance() {
      if (!isset(self::$instance)) {
        self::$instance = new self();
        self::$instance->init();
      }
      return self::$instance;
    }

  }

  QLWAPP_Frontend::instance();
}
