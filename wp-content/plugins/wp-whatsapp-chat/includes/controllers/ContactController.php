<?php

include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Contact.php');

include_once(QLWAPP_PLUGIN_DIR . 'includes/controllers/QLWAPP_Controller.php');

class QLWAPP_Contact_Controller extends QLWAPP_Controller {

  protected static $instance;

  function add_menu() {
    add_submenu_page(QLWAPP_DOMAIN, esc_html__('Contacts', 'wp-whatsapp-chat'), esc_html__('Contacts', 'wp-whatsapp-chat'), 'manage_options', QLWAPP_DOMAIN . '_contacts', array($this, 'add_panel'));
  }

  function add_panel() {
    global $submenu;
    $contact_model = new QLWAPP_Contact();
    $contacts = $contact_model->get_contacts_reorder();
    $contact_args = $contact_model->get_args();
    include (QLWAPP_PLUGIN_DIR . '/includes/view/backend/pages/parts/header.php');
    include (QLWAPP_PLUGIN_DIR . '/includes/view/backend/pages/contacts.php');
  }

  function ajax_edit_contact() {
    if (check_ajax_referer('qlwapp_edit_contact', 'nonce', false)) {
      $contact_id = (isset($_REQUEST['contact_id'])) ? absint($_REQUEST['contact_id']) : -1;
      if ($contact_id != -1) {
        $contact_model = new QLWAPP_Contact();
        $contact = $contact_model->get_contact($contact_id);
        if ($contact) {
          return parent::success_ajax($contact);
        }
      }
      parent::error_reload_page();
    }
    parent::error_access_denied();
  }

  function ajax_save_contact() {

    if (current_user_can('manage_options')) {
      if (check_ajax_referer('qlwapp_save_contact', 'nonce', false) && isset($_REQUEST['contact_data'])) {
        $contact_data = array();

        parse_str($_REQUEST['contact_data'], $contact_data);

        $contact_model = new QLWAPP_Contact();
        
        if (is_array($contact_data)) {
          if (isset($contact_data['id'])) {
            return parent::success_save($contact_model->update_contact($contact_data));
          } else {
            return parent::success_save($contact_model->add_contact($contact_data));
          }
          return parent::error_reload_page();
        }
      }
      return parent::error_access_denied();
    }
  }

  function ajax_save_contact_order() {
    if (current_user_can('manage_options')) {
      if (check_ajax_referer('qlwapp_save_contact_order', 'nonce', false) && isset($_REQUEST['contact_data'])) {
        if (array_key_exists('contact_data', $_REQUEST)) {
          $contact_model = new QLWAPP_Contact();
          $contacts = $contact_model->get_contacts();
          $contact_order = array();
          parse_str($_REQUEST['contact_data'], $contact_order);
          $contact_order = $contact_order['contact_order'];
          $result = -1;
          if (is_array($contact_order) && count($contact_order) > 0) {
            $loop = 1;
            foreach ($contact_order as $contact_id) {
              if (isset($contacts[$contact_id])) {
                $contacts[$contact_id]['order'] = $loop;
                $loop++;
              }
            }
            $result = $contact_model->update_contacts($contacts);
          }
        }
      }
      wp_send_json_success($result);
    }
  }

  function ajax_delete_contact() {

    if (check_ajax_referer('qlwapp_delete_contact', 'nonce', false)) {

      $contact_id = (isset($_REQUEST['contact_id'])) ? absint($_REQUEST['contact_id']) : -1;

      $contact_model = new QLWAPP_Contact();
      // delete 
      $contact = $contact_model->delete($contact_id);
      if ($contact_id) {

        return parent::success_ajax($contact);
      }
      parent::error_reload_page();
    }
    parent::error_access_denied();
  }

  function init() {

    add_action('wp_ajax_qlwapp_add_contact', array($this, 'ajax_add_contact'));
    add_action('wp_ajax_qlwapp_edit_contact', array($this, 'ajax_edit_contact'));
    add_action('wp_ajax_qlwapp_save_contact', array($this, 'ajax_save_contact'));
    add_action('wp_ajax_qlwapp_delete_contact', array($this, 'ajax_delete_contact'));
    add_action('wp_ajax_qlwapp_save_contact_order', array($this, 'ajax_save_contact_order'));
    add_action('admin_enqueue_scripts', array($this, 'add_js'));
    add_action('admin_menu', array($this, 'add_menu'));
  }

  function add_js() {
    if (isset($_GET['page']) && ($_GET['page'] === 'qlwapp_contacts')) {
      $contact_model = new QLWAPP_Contact();
      wp_enqueue_media();
      wp_enqueue_script('qlwapp-admin-contact', plugins_url('/assets/js/qlwapp-admin-contact' . QLWAPP::is_min() . '.js', QLWAPP_PLUGIN_FILE), array('jquery', 'backbone'), QLWAPP_PLUGIN_VERSION, true);

      wp_localize_script('qlwapp-admin-contact', 'qlwapp_contact', array(
          'nonce' => array(
              'qlwapp_get_contact' => wp_create_nonce('qlwapp_get_contact'),
              'qlwapp_edit_contact' => wp_create_nonce('qlwapp_edit_contact'),
              'qlwapp_add_contact' => wp_create_nonce('qlwapp_add_contact'),
              'qlwapp_save_contact' => wp_create_nonce('qlwapp_save_contact'),
              'qlwapp_save_contact_order' => wp_create_nonce('qlwapp_save_contact_order')
          ),
          'message' => array(
              'contact_confirm_delete' => 'you want to delete the contact',
              'contact_confirm_delete_title' => 'Contact: confirmation'
          ),
          'args' => $contact_model->get_args()));
    }
  }

  public static function instance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
      self::$instance->init();
    }
    return self::$instance;
  }

}

QLWAPP_Contact_Controller::instance();
