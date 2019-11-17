<?php

include_once(QLWAPP_PLUGIN_DIR . 'includes/helpers.php');

class QLWAPP_Settings {

  protected static $instance;

  // fix required header in license tab
  function settings_header() {
    global $submenu;
    include (QLWAPP_PLUGIN_DIR . '/includes/view/backend/pages/parts/header.php');
  }

  // fix settings override with defaults on license save
  function settings_sanitize($qlwapp) {

    $current = get_option(QLWAPP_DOMAIN, array());

    return wp_parse_args($qlwapp, $current);
  }

  // required to save license
  function settings_register() {
    register_setting(sanitize_key(QLWAPP_DOMAIN . '-group'), sanitize_key(QLWAPP_DOMAIN), array($this, 'settings_sanitize'));
  }

  function previous_author($qlwapp) {
    //button
    if ($phone = get_option('whatsapp_chat_page')) {
      $qlwapp['button']['phone'] = $phone;
    }
    if ($text = get_option('whatsapp_chat_button')) {
      $qlwapp['button']['text'] = $text;
    }
    if (get_option('whatsapp_chat_powered_by')) {
      $qlwapp['button']['developer'] = 'yes';
    }
    if (false !== get_option('whatsapp_chat_round')) {
      $qlwapp['button']['rounded'] = 'no';
    }
    if (false !== get_option('whatsapp_chat_down')) {
      $vposition = get_option('whatsapp_chat_down') ? 'bottom' : 'middle';
      $hposition = get_option('whatsapp_chat_left_side') ? 'left' : 'right';
      $qlwapp['button']['position'] = "{$vposition}-{$hposition}";
    }
    if ($message = get_option('whatsapp_chat_msg')) {
      $qlwapp['button']['message'] = $message;
    }
    // display 
    if ($mobile = get_option('whatsapp_chat_mobile')) {
      $qlwapp['display']['devices'] = 'mobile';
    }
    if (get_option('whatsapp_chat_hide_button')) {
      $qlwapp['display']['devices'] = 'hide';
    }
    if (get_option('whatsapp_chat_hide_post')) {
      $qlwapp['display']['post'] = array('none');
    }
    if (get_option('whatsapp_chat_hide_page')) {
      $qlwapp['display']['page'] = array('none');
    }
    // No se usa mas
    /* if (get_option('whatsapp_chat_hide_product')) {
      $qlwapp['display']['product'] = array('none');
      }
      // No se usa mas
      if (get_option('whatsapp_chat_hide_project')) {
      $qlwapp['display']['project'] = array('none');
      }
     */

    //scheme
    if (get_option('whatsapp_chat_dark')) {
      $qlwapp['scheme']['brand'] = '#075E54';
      $qlwapp['scheme']['text'] = '#ffffff';
    } elseif (get_option('whatsapp_chat_white')) {
      $qlwapp['scheme']['brand'] = '#ffffff';
      $qlwapp['scheme']['text'] = '#075E54';
    } elseif (false !== get_option('whatsapp_chat_white')) {
      $qlwapp['scheme']['brand'] = '#20B038';
      $qlwapp['scheme']['text'] = '#ffffff';
    }

    return $qlwapp;
  }

  function previous_versions($qlwapp) {
    if (isset($qlwapp['chat']['response']) && !isset($qlwapp['box']['response'])) {
      $qlwapp['box']['response'] = $qlwapp['chat']['response'];
    }
    if (isset($qlwapp['user']['message'])) {
      $qlwapp['button']['message'] = $qlwapp['user']['message'];
    }
    if (isset($qlwapp['button']['rounded']) && $qlwapp['button']['rounded'] == 1) {
      $qlwapp['button']['rounded'] = 'yes';
    }
    if (isset($qlwapp['button']['developer']) && $qlwapp['button']['developer'] == 1) {
      $qlwapp['button']['developer'] = 'yes';
    }
    return $qlwapp;
  }

  function premium_version() {
    global $qlwapp;
    //includes
    include_once(QLWAPP_PLUGIN_DIR . 'includes/models/QLWAPP_Model.php');
    include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Button.php');
    include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Box.php');
    include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Contact.php');
    include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Chat.php');
    include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Display.php');
    include_once(QLWAPP_PLUGIN_DIR . 'includes/models/License.php');
    include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Scheme.php');
    // models
    $model = new QLWAPP_Model();
    $license_model = new QLWAPP_License();
    $button_model = new QLWAPP_Button();
    $box_model = new QLWAPP_Box();
    $contact_model = new QLWAPP_Contact();
    $chat_model = new QLWAPP_Chat();
    $display_model = new QLWAPP_Display();
    $scheme_model = new QLWAPP_Scheme();

    // objects
    $qlwapp = $model->options();
    $qlwapp['button'] = $button_model->get();
    $qlwapp['box'] = $box_model->get();
    $qlwapp['contacts'] = $contact_model->get_contacts_reorder();
    $qlwapp['chat'] = $chat_model->get();
    $qlwapp['display'] = $display_model->get();
    $qlwapp['license'] = $license_model->get();
    $qlwapp['scheme'] = $scheme_model->get();

    if (!is_admin()) {
      if (isset($qlwapp['button']['phone'])) {
        $qlwapp['button']['phone'] = qlwapp_format_phone($qlwapp['button']['phone']);
      }
      if (isset($qlwapp['button']['timezone'])) {
        $qlwapp['button']['timezone'] = qlwapp_get_timezone_offset($qlwapp['button']['timezone']);
      }
    }

    if (isset($qlwapp['contacts'])) {
      if (count($qlwapp['contacts'])) {
        foreach ($qlwapp['contacts'] as $id => $c) {
          $qlwapp['contacts'][$id] = wp_parse_args($c, $contact_model->get_args());

          if (!is_admin()) {
            if (!empty($qlwapp['contacts'][$id]['phone'])) {
              $qlwapp['contacts'][$id]['phone'] = qlwapp_format_phone($qlwapp['contacts'][$id]['phone']);
            }
            if (!empty($qlwapp['contacts'][$id]['timezone'])) {
              $qlwapp['contacts'][$id]['timezone'] = qlwapp_get_timezone_offset($qlwapp['contacts'][$id]['timezone']);
            }
          }
        }
      }
    }
  }

  function init() {
    add_filter('init', array($this, 'premium_version'));
    add_action('admin_init', array($this, 'settings_register'));
    add_filter('option_qlwapp', array($this, 'previous_versions'));
    add_filter('default_option_qlwapp', array($this, 'previous_author'), 20);
  }

  public static function instance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
      self::$instance->init();
    }
    return self::$instance;
  }

}

QLWAPP_Settings::instance();

