<?php

include_once(QLWAPP_PLUGIN_DIR . 'includes/helpers.php');

class QLWAPP_Contact extends QLWAPP_Model {

  protected $table = 'contacts';

  function get_args() {
    $args = array(
        'id' => null,
        'order' => 1,
        // Defaults
        // -----------------------------------------------------------------
        'chat' => true,
        'avatar' => 'https://www.gravatar.com/avatar/00000000000000000000000000000000',
        'phone' => '542215677768',
        'firstname' => 'John',
        'lastname' => 'Doe',
        'label' => esc_html__('Support', 'wp-whatsapp-chat'),
        'message' => esc_html__('Hello! I\'m John from the support team.', 'wp-whatsapp-chat'),
        'timefrom' => '00:00',
        'timeto' => '00:00',
        'timezone' => qlwapp_get_current_timezone(),
        'timeout' => 'readonly'
    );
    return $args;
  }

  function get_next_id() {
    $contactos = $this->get_contacts();
    if (count($contactos)) {
      return max(array_keys($contactos)) + 1;
    }
    return 0;
  }

  function add_contact($contact_data) {    
    $contact_data['id'] = $this->get_next_id();
    $contact_data['order'] = count($this->get_contacts()) + 1;
    return $this->save($contact_data);
  }

  function update_contact($contact_data) {    
    return $this->save($contact_data);
  }

  function update_contacts($contacts) {
    return $this->save_data($this->table, $this->sanitize_value_data($contacts));
  }

  function save($contact_data = NULL) {
    $contacts = $this->get_contacts();
    $contacts[$contact_data['id']] = wp_parse_args($contact_data, $this->get_args());
    return $this->save_data($this->table, $this->sanitize_value_data($contacts));
  }

  function delete($id = NULL) {
    $contacts = parent::get_all($this->table);
    if ($contacts) {
      if (count($contacts) > 0) {
        unset($contacts[$id]);
        return $this->save_data($this->table, $this->sanitize_value_data($contacts));
      }
    }
    return false;
  }

  function settings_sanitize($settings) {

    if (isset($settings['contacts'])) {
      if (count($settings['contacts'])) {
        foreach ($settings['contacts'] as $id => $c) {
          $settings['contacts'][$id]['id'] = $id;
          $settings['contacts'][$id]['chat'] = (bool) $settings['contacts'][$id]['chat'];
          $settings['contacts'][$id]['avatar'] = sanitize_text_field($settings['contacts'][$id]['avatar']);
          $settings['contacts'][$id]['phone'] = sanitize_text_field($settings['contacts'][$id]['phone']);
          $settings['contacts'][$id]['firstname'] = sanitize_text_field($settings['contacts'][$id]['firstname']);
          $settings['contacts'][$id]['lastname'] = sanitize_text_field($settings['contacts'][$id]['lastname']);
          $settings['contacts'][$id]['label'] = sanitize_text_field($settings['contacts'][$id]['label']);
          $settings['contacts'][$id]['message'] = wp_kses_post($settings['contacts'][$id]['message']);
          $settings['contacts'][$id]['timeto'] = wp_kses_post($settings['contacts'][$id]['timeto']);
          $settings['contacts'][$id]['phone'] = qlwapp_format_phone($settings['contacts'][$id]['phone']);
        }
      }
    }

    return $settings;
  }

  function sanitize_value_data($contacts, $args = null) {
    //$contact_sanitized = array();
    foreach ($contacts as $key => $contact) {
      $contacts[$key] = parent::sanitize_value_data($contact, $this->get_args());
    }
    return $contacts;
  }

  // asumir que el id es igual al orden dentro del array
  function get_contact($id) {
    $parent_id = @max(array_keys(array_column($contacts, 'id'), $id));
    $contacts = $this->get_contacts();
    $contacts[$id];
    return $contacts[$id];
  }

  function get_contacts() {
    // $this->contacts see how to avoid multiple db calls in the same execution
    $result = parent::get_all($this->table);
    if ($result === NULL || count($result) === 0) {
      $default = array();
      $default[0] = $this->get_args();
      $default[0]['id'] = 0;
      $result = $default;
    }
    return $result;
  }

  function order_contact($a, $b) {

    if (!isset($a['order']) || !isset($b['order']))
      return 0;

    if ($a['order'] == $b['order'])
      return 0;

    return ( $a['order'] < $b['order'] ) ? -1 : 1;
  }

  function get_contacts_reorder() {
    $contacts = $this->get_contacts();
    uasort($contacts, array($this, 'order_contact'));
    return $contacts;
  }

}
