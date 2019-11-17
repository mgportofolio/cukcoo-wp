<?php

class QLWAPP_Display extends QLWAPP_Model {

    protected $table = 'display';

    function get_args() {

        $args = array(
            'devices' => 'all',
            'target' => array(),
            'page' => array(),
            'post' => array(),
            'category' => array(),
        );
        return $args;
    }

    function save($display_data = NULL) {
        return parent::save_data($this->table, $display_data);
    }
 

}
