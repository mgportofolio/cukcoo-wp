<div class="wrap about-wrap full-width-layout qlwrap">
  <form method="post" id="qlwapp_display_form"> 
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><?php esc_html_e('Devices', 'wp-whatsapp-chat'); ?></th>
          <td>
            <select name="devices" style="width:350px" data-placeholder="<?php echo esc_attr('Choose target&hellip;', 'wp-whatsapp-chat'); ?>" aria-label="<?php echo esc_attr('Posts', 'wp-whatsapp-chat'); ?>" class="qlwapp-select2">
              <option value="all" <?php selected('all', $display['devices']); ?>><?php esc_html_e('Show in all devices', 'wp-whatsapp-chat'); ?></option>
              <option value="mobile" <?php selected('mobile', $display['devices']); ?>><?php esc_html_e('Show in mobile devices', 'wp-whatsapp-chat'); ?></option>
              <option value="desktop" <?php selected('desktop', $display['devices']); ?>><?php esc_html_e('Show in desktop devices', 'wp-whatsapp-chat'); ?></option>
              <option value="hide" <?php selected('hide', $display['devices']); ?>><?php esc_html_e('Hide in all devices', 'wp-whatsapp-chat'); ?></option>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row"><?php esc_html_e('Target', 'wp-whatsapp-chat'); ?></th>
          <td>
            <select multiple="multiple" name="target[]" style="width:350px" data-placeholder="<?php echo esc_attr('Choose target&hellip;', 'wp-whatsapp-chat'); ?>" aria-label="<?php echo esc_attr('Posts', 'wp-whatsapp-chat'); ?>" class="qlwapp-select2">
              <option value="none" <?php echo selected(true, in_array('none', (array) $display['target'])); ?>><?php echo esc_html__('Exclude from all', 'wp-whatsapp-chat'); ?></option>
              <option value="home" <?php echo selected(true, in_array('home', (array) $display['target'])); ?>><?php echo esc_html__('Home', 'wp-whatsapp-chat'); ?></option>
              <option value="blog" <?php echo selected(true, in_array('blog', (array) $display['target'])); ?>><?php echo esc_html__('Blog', 'wp-whatsapp-chat'); ?></option>
              <option value="search" <?php echo selected(true, in_array('search', (array) $display['target'])); ?>><?php echo esc_html__('Search', 'wp-whatsapp-chat'); ?></option>
              <option value="error" <?php echo selected(true, in_array('error', (array) $display['target'])); ?>><?php echo esc_html('404'); ?></option>
            </select>
            <p class="description hidden"><?php esc_html_e('If you select an option all the other will be excluded', 'wp-whatsapp-chat'); ?></p>
          </td>
        </tr>  
        <?php
        foreach (get_post_types(array('public' => true, 'show_in_nav_menus' => true), 'objects') as $type) {
          if (!isset($display[$type->name])) {
            $display[$type->name] = array();
          }

          if ($count = wp_count_posts($type->name)) {
            ?>
            <tr class="qlwapp-premium-field">
              <th scope="row"><?php esc_html_e(ucwords($type->label)); ?></th>
              <td>
      <!--              <select style="width:80px" class="qlwapp-select2">
                  <option value="include">Include</option>
                  <option value="include">Exclude</option>
                </select>-->
                <select id="qlwapp_select2_<?php echo esc_attr($type->name); ?>" multiple="multiple" name="<?php echo esc_attr($type->name . '[]'); ?>" style="width:350px" data-placeholder="<?php printf(esc_html__('Select for %s&hellip;', 'wp-whatsapp-chat'), $type->label); ?>" aria-label="<?php echo esc_attr($type->label); ?>"  data-name="<?php echo esc_attr($type->name); ?>" class="qlwapp-select2-ajax">
                  <option value="none" <?php echo selected(true, in_array('none', (array) $display[$type->name])); ?>><?php echo esc_html__('Exclude from all', 'wp-whatsapp-chat'); ?></option>
                  <!--<option value="archive" <?php echo selected(true, in_array('archive', (array) $display[$type->name])); ?>><?php echo esc_html__('Archive', 'wp-whatsapp-chat'); ?></option>-->
                  <?php
                  // Print selected posts
                  // -------------------------------------------------------------
                  if (isset($display[$type->name]) && count($display[$type->name])) {
                    foreach ($display[$type->name] as $post_id) {

                      if (!$post = get_post($post_id)) {
                        //backward compatibility for $post->post_name
                        $post = get_page_by_path($post_id);
                      }

                      if (isset($post->ID)) {
                        ?>
                        <option value="<?php echo esc_attr($post->ID); ?>" selected="selected"><?php echo esc_html(mb_substr($post->post_title, 0, 49)); ?></option>
                        <?php
                      }
                    }
                  }
                  // Print firsts 10 posts
                  // -------------------------------------------------------------                
                  /* if ($count->publish < 11) {
                    $posts = get_posts(array(
                    'post_type' => $type->name,
                    'post_status' => 'publish',
                    'numberposts' => 10
                    ));
                    foreach ($posts as $post) {
                    ?>
                    <option value="<?php echo esc_attr($post->post_name); ?>" <?php echo selected(true, in_array($post->post_name, (array) $display[$type->name])); ?>><?php echo esc_html($post->post_title); ?></option>
                    <?php
                    }
                    } */
                  ?>
                </select>
                <p class="description hidden"><small><?php esc_html_e('This is a premium feature', 'wp-whatsapp-chat'); ?></small></p>    
              </td>
            </tr>       
            <?php
          }
        }
        ?>
        <?php
        foreach ($taxonomies = get_taxonomies(array('public' => true), 'objects') as $taxonomy) {

          if (!isset($display[$taxonomy->name])) {
            $display[$taxonomy->name] = array();
          }

          $terms = get_terms(array(
              'taxonomy' => $taxonomy->name,
              'hide_empty' => false,
          ));

          if (count($terms)) {
            ?>
            <tr>
              <th scope="row"><?php esc_html_e(ucwords($taxonomy->label)); ?></th>
              <td>
                <select multiple="multiple" name="<?php echo esc_attr($taxonomy->name . '[]'); ?>" style="width:350px" data-placeholder="<?php echo esc_attr('Choose target&hellip;', 'wp-whatsapp-chat'); ?>" aria-label="<?php echo esc_attr($taxonomy->label); ?>" class="qlwapp-select2">
                  <option value="none" <?php echo selected(true, in_array('none', (array) $display[$taxonomy->name])); ?>><?php echo esc_html__('Exclude from all', 'wp-whatsapp-chat'); ?></option>
                  <?php
                  foreach ($terms as $term) {
                    //backward compatibility for $term->name
                    ?>
                    <option value="<?php echo esc_attr($term->term_id); ?>" <?php echo selected(true, in_array($term->term_id, (array) $display[$taxonomy->name]) || in_array($term->name, (array) $display[$taxonomy->name])); ?>><?php echo esc_html($term->name); ?></option>
                    <?php
                  }
                  ?>
                </select>
              </td>
            </tr>       
            <?php
          }
        }
        ?>
      </tbody>
    </table>  
    <?php wp_nonce_field('qlwapp_save_display', 'qlwapp_display_form_nonce'); ?>  
    <p class="submit">
      <?php submit_button(esc_html__('Save', 'wp-whatsapp-chat'), 'primary', 'submit', false); ?>
      <span class="settings-save-status">  
        <span class="saved"><?php esc_html_e('Saved successfully!'); ?></span>
        <span class="spinner"></span>
      </span>
    </p>
  </form>
</div>