<div class="media-modal-backdrop">&nbsp;</div>
<div tabindex="0"  id="<?php echo esc_attr(QLWAPP_DOMAIN . '_modal'); ?>" class="media-modal wp-core-ui upload-php qlwapp-modal-contact" role="dialog" aria-modal="true" aria-labelledby="media-frame-title">
  <div class="media-modal-content" role="document">
    <form class="media-modal-form" method="POST" data-contact_id="{{data.id}}">
      <# if ( data.id != undefined ) { #>
      <input type="hidden" name="id" value="{{data.id}}" />
      <input type="hidden" name="order" value="{{data.order}}" />
      <# } #>
      <div class="edit-attachment-frame mode-select hide-menu hide-router">
        <div class="edit-media-header ">
          <# if (data.id != undefined ) {  #>  
          <button type="button" class="media-modal-prev left dashicons <# if ( data.order == 1 ) { #>disabled<# } #>"><span class="screen-reader-text"><?php esc_html_e('Edit previous media item'); ?></span></button>
          <button type="button" class="media-modal-next right dashicons <# if ( data.order == <?php echo esc_attr(count($contacts)); ?> ) { #>disabled<# } #>"><span class="screen-reader-text"><?php esc_html_e('Edit next media item'); ?></span></button>
          <#  } #>
          <button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text"><?php esc_html_e('Close dialog'); ?></span></span></button>       
        </div> 
        <div class="media-frame-title">
          <h1><?php esc_html_e('Edit contact', 'wp-whatsapp-chat'); ?> #<# if ( data.id != undefined ) { #>{{data.id}}<# } else { #><?php echo esc_html_e('new', 'wp-whatsapp-chat'); ?><# } #></h1>
        </div>
        <div class="media-frame-content" style="bottom:61px;">
          <div class="attachment-details">
            <div class="attachment-media-view landscape" style="overflow: hidden;">
              <table class="widefat">  
                <tr>  
                  <th><?php esc_html_e('Firstname', 'wp-whatsapp-chat'); ?></th>
                  <td><input type="text" id="cfirstname" name="firstname" placeholder="<?php echo esc_html($contact_args['firstname']); ?>" value="{{data.firstname}}" /></td>
                  <th><?php esc_html_e('Lastname', 'wp-whatsapp-chat'); ?></th>
                  <td><input type="text" id="clastname" name="lastname" placeholder="<?php echo esc_html($contact_args['lastname']); ?>" value="{{data.lastname}}" /></td>
                </tr>
                <tr>
                  <th><?php esc_html_e('Phone', 'wp-whatsapp-chat'); ?></td><td><input type="text" id="cphone" name="phone" placeholder="<?php echo esc_html($contact_args['phone']); ?>" value="{{data.phone}}" required="required"   pattern="\d[0-9]{6,15}$"/></th>
                  <td><?php esc_html_e('Label', 'wp-whatsapp-chat'); ?></td><td><input type="text" id="clabel" name="label" placeholder="<?php echo esc_html($contact_args['label']); ?>" value="{{data.label}}" /></td>
                </tr> 
              </table> 
              <table class="widefat"> 
                <tr>   
                  <th><?php esc_html_e('From', 'wp-whatsapp-chat'); ?></th>
                  <td class="qlwapp-premium-field">
                    <input type="time" name="timefrom" placeholder="<?php echo esc_html($contact_args['timefrom']); ?>" value="{{data.timefrom}}" />
                    <?php esc_html_e('To', 'wp-whatsapp-chat'); ?> 
                    <input type="time" name="timeto" placeholder="<?php echo esc_html($contact_args['timeto']); ?>" value="{{data.timeto}}" /> 
                  </td>
<!--                                        <td><?php esc_html_e('Time is over', 'wp-whatsapp-chat'); ?></td> 
     <td class="qlwapp-premium-field">
         <select id="ctimeout" name="<?php echo esc_attr(QLWAPP_DOMAIN . '[contacts][' . $id . '][timeout]'); ?>">
             <option value="readonly"  ><?php esc_html_e('Show the field as read only', 'wp-whatsapp-chat'); ?></option>
             <option value="disabled" > <?php esc_html_e('Do not show the field', 'wp-whatsapp-chat'); ?></option>
         </select> 
         <p class="description hidden"><small><?php esc_html_e('This is a premium feature', 'wp-whatsapp-chat'); ?></small></p>    
     </td>   -->
                  <th><?php esc_html_e('Timezone', 'wp-whatsapp-chat'); ?></th> 
                  <td class="qlwapp-premium-field">
                    <select name="timezone" aria-describedby="timezone-description">
                      <?php echo preg_replace('/(.*)value="([^"]*)"(.*)/', '$1value="$2"<# if ( data.timezone == "$2" ) { #> selected="selected"<# } #> $3', wp_timezone_choice('__return_null')); ?>
                    </select> 
                  </td>  
                </tr>
              </table> 
              <table class="widefat"> 
                <tr>
                  <th><?php esc_html_e('Chat', 'wp-whatsapp-chat'); ?></th>
                  <td class="qlwapp-premium-field">
                    <p>
                      <label><input type="radio" class="media-modal-change" name="chat" value="1" <# if(data.chat) {#> checked <#}#> /><?php esc_html_e('Enabled', 'wp-whatsapp-chat'); ?></label>
                      <label><input type="radio" class="media-modal-change" name="chat" value="0" <# if(data.chat == false) {#> checked <#}#> /><?php esc_html_e('Disabled', 'wp-whatsapp-chat'); ?></label>
                    </p>
                    <p class="description hidden"><small><?php esc_html_e('This is a premium feature', 'wp-whatsapp-chat'); ?></small></p>    
                  </td>
                </tr>
                <tr class="qlwapp-premium-field"> 
                  <th><?php esc_html_e('Message', 'wp-whatsapp-chat'); ?></th>
                  <td colspan="2">
                    <textarea style="width:100%" name="message" <# if(data.chat == false) { #>readonly="readonly"<# } #>>{{ _.escapeHtml(data.message)}}</textarea> 
                  </td>
                <p class="description hidden"><small><?php esc_html_e('This is a premium feature', 'wp-whatsapp-chat'); ?></small></p>    
                </tr>
              </table> 
            </div>

            <div class="attachment-info">

              <span class="settings-save-status">
                <span class="spinner"></span>
                <span class="saved"><?php esc_html_e('Saved.'); ?></span>
              </span>

              <div class="details">
                <div class="filename"><strong><?php esc_html_e('Contact id', 'wp-whatsapp-chat'); ?>:</strong> {{data.id}}</div>
              </div>

              <div class="settings">
                <div class="upload">
                  <img id="cavatar-img" class="qlwapp-avatar" data-src="{{data.avatar}}" src="{{data.avatar}}"  width="150" height="150"/>
                  <div>
                    <input type="hidden" name="avatar" id="cavatar" value="{{data.avatar}}" />
                    <button type="button" class="upload_image_button button"><?php esc_html_e('Upload', 'wp-whatsapp-chat'); ?></button>
                    <button type="button" class="remove_image_button button">&times;</button>
                  </div>
                </div> 
              </div>

              <div class="actions">
                <a target="_blank" href="<?php echo QLWAPP_PURCHASE_URL; ?>"><?php esc_html_e('Premium', 'wp-whatsapp-chat'); ?></a> |
                <a target="_blank" href="<?php echo QLWAPP_DOCUMENTATION_URL; ?>"><?php esc_html_e('Documentation', 'wp-whatsapp-chat'); ?></a>
              </div>

            </div>
          </div>   
        </div>
        <div class="media-frame-toolbar" style="left:0;">
          <div class="media-toolbar">
            <div class="media-toolbar-secondary"></div>
            <div class="media-toolbar-primary search-form">
              <button type="submit" id="submit" class="media-modal-submit button button-primary media-button button-large" disabled="disabled"><?php esc_html_e('Save'); ?></button>
              <button type="button" class="media-modal-close button button-secondary media-button button-large" style="
                      height: auto;
                      float: none;
                      position: inherit;
                      padding: inherit;
                      "><?php esc_html_e('Close'); ?></button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>