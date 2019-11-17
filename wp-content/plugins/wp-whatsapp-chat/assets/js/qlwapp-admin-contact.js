(function ($) {

  var count = 0,
          timer;

  _.mixin({
    escapeHtml: function (attribute) {
      return attribute.replace('&amp;', /&/g)
              .replace(/&gt;/g, ">")
              .replace(/&lt;/g, "<")
              .replace(/&quot;/g, '"')
              .replace(/&#039;/g, "'");
    }
  });

  var Contact = Backbone.Model.extend({
    defaults: qlwapp_contact.args
  });

  var ContactView = Backbone.View.extend({
    events: {
      'change input': 'enable',
      'change textarea': 'enable',
      'change select': 'enable',
      'click .media-modal-backdrop': 'close',
      'click .media-modal-close': 'close',
      'click .media-modal-prev': 'edit',
      'click .media-modal-next': 'edit',
      'change .media-modal-change': 'change',
      'submit .media-modal-form': 'submit'
    },
    templates: {},
    initialize: function () {
      _.bindAll(this, 'open', 'edit', 'change', 'load', 'render', 'close', 'submit');
      this.init();
      this.open();

    },
    init: function () {
      this.templates.window = wp.template('qlwapp-modal-window');
    },
    render: function () {

      var modal = this;

      // get active tab from the previous modal
      var tab = this.$el.find('ul.wc-tabs li.active a').attr('href');

      modal.$el.html(modal.templates.window(modal.model.attributes));

      _.delay(function () {
//        modal.$el.trigger('wooccm-enhanced-options');
//        modal.$el.trigger('wooccm-enhanced-select');
//        modal.$el.trigger('wooccm-tab-panels', tab);
//        modal.$el.trigger('init_tooltips');
      }, 100);

    },
    load: function () {
      var modal = this;
      if (modal.model.attributes.id == undefined) {
        modal.render();
        return;
      }
      $.ajax({
        url: ajaxurl,
        data: {
          action: 'qlwapp_edit_contact',
          nonce: qlwapp_contact.nonce.qlwapp_edit_contact,
          contact_id: this.model.attributes.id
        },
        dataType: 'json',
        type: 'POST',
        beforeSend: function () {

        },
        complete: function () {
          //unblock($tr);
        },
        error: function () {
          alert('Error!');
        },
        success: function (response) {

          if (response.success) {
            modal.model.set(response.data);
            modal.render();
          } else {
            alert(response.data);
          }
        }
      });
    },
    edit: function (e) {
      e.preventDefault();
      var modal = this,
              $button = $(e.target),
              contact_count = parseInt($('#qlwapp_contacts_table tr[data-contact_id]').length),
              order = parseInt(modal.model.get('order'));
      count++;
      if (timer) {
        clearTimeout(timer);
      }

      timer = setTimeout(function () {

        if ($button.hasClass('media-modal-next')) {
          order = Math.min(order + count, contact_count);
        } else {
          order = Math.max(order - count, 1);
        }

        modal.model.set({
          id: parseInt($('#qlwapp_contacts_table tr[data-contact_order=' + order + ']').data('contact_id'))
        });
        count = 0;
        modal.load();
      }, 300);
    },
    open: function (e) {
      this.load();
      $('body').addClass('modal-open').append(this.$el);
    },
    update: function (e) {

      e.preventDefault();

      var $field = $(e.target),
              name = $field.attr('name'),
              value = $field.val();

      if (e.target.type === 'checkbox') {
        value = $field.prop('checked') === true ? 1 : 0;
      }

      this.model.attributes[name] = value;
      this.model.changed[name] = value;

    },
    change: function (e) {
      e.preventDefault();
      this.update(e);
      this.render();

    },
    reload: function (e) {
      if (this.$el.find('#qlwapp_modal').hasClass('reload')) {
        location.reload();
        return;
      }
      this.remove();
      return;
    },
    close: function (e) {
      e.preventDefault();
      this.undelegateEvents();
      $(document).off('focusin');
      $('body').removeClass('modal-open');
      this.reload(e);
      return;
    },
    enable: function (e) {
      $('.media-modal-submit').removeProp('disabled');
    },
    submit: function (e) {
      e.preventDefault();
      var modal = this,
              $modal = modal.$el.find('#qlwapp_modal'),
              $details = $modal.find('.attachment-details');
      $.ajax({
        url: ajaxurl,
        data: {
          action: 'qlwapp_save_contact',
          nonce: qlwapp_contact.nonce.qlwapp_save_contact,
          contact_id: modal.model.attributes.id,
          contact_data: $('form', this.$el).serialize()
        },
        dataType: 'json',
        type: 'POST',
        beforeSend: function () {
          $('.media-modal-submit').prop('disabled', true);
          $details.addClass('save-waiting');
        },
        complete: function () {
          $details.addClass('save-complete');
          $details.removeClass('save-waiting');
        },
        error: function () {
          alert('Error!');
        },
        success: function (response) {
          if (response.success) {

            if (modal.model.attributes.id == undefined) {
              modal.close(e);
            }

            $modal.addClass('reload');

          } else {
            alert(response.data);
          }
        }
      });
      return false;
    }
  });
  var ContactModal = Backbone.View.extend({
    initialize: function (e) {
      var $button = $(e.target),
              contact_id = $button.closest('[data-contact_id]').data('contact_id');
      var model = new Contact();
      model.set({
        id: contact_id
      });
      new ContactView({
        model: model
      });
    },
  });
  $('.qlwapp_settings_edit').on('click', function (e) {
    e.preventDefault();
    new ContactModal(e);
  });
  $('#qlwapp_contact_add').on('click', function (e) {
    e.preventDefault();
    new ContactModal(e);
  });
  $('.qlwapp_settings_delete').on('click', function (e) {
    e.preventDefault();
    var nonce = $('#qlwapp_delete_contact_nonce').val();
    var $button = $(e.target),
            contact_id = $button.closest('[data-contact_id]').data('contact_id');
    if (!confirm(qlwapp_contact.message.contact_confirm_delete)) {
      return false;
    } else {
      $.ajax({
        url: ajaxurl,
        data: {
          action: 'qlwapp_delete_contact',
          nonce: nonce,
          contact_id: contact_id
        },
        dataType: 'json',
        type: 'POST',
        beforeSend: function () {
//        $spinner.addClass('is-active');
        },
        complete: function () {
//        $spinner.removeClass('is-active');
        },
        error: function (response) {
          console.log('response from error ');
          console.log(response);
          /// alert(response);
        },
        success: function (response) {

          if (response.data) {
            console.log(response.data);
            location.reload();
          } else {
            alert(response.data);
          }
        }
      });
    }
  });

  // Sorting
  // ---------------------------------------------------------------------------
  $('table#qlwapp_contacts_table tbody').sortable({
    items: 'tr',
    cursor: 'move',
    axis: 'y',
    handle: 'td.sort',
    scrollSensitivity: 40,
    helper: function (event, ui) {
      ui.children().each(function () {
        $(this).width($(this).width());
      });
      ui.css('left', '0');
      return ui;
    },
    start: function (event, ui) {
      ui.item.css('background-color', '#f6f6f6');
    },
    stop: function (event, ui) {
      ui.item.removeAttr('style');
      ui.item.trigger('updateMoveButtons');
      ui.item.trigger('updateSaveButton');
    }
  });
  $(document).on('updateSaveButton', function () {
    $('#qlwapp_contact_order').removeProp('disabled');
  });
  // Re-order buttons
  // ---------------------------------------------------------------------------
  $('.wc-item-reorder-nav').find('.wc-move-up, .wc-move-down').on('click', function () {

    var moveBtn = $(this),
            $row = moveBtn.closest('tr');
    moveBtn.focus();
    var isMoveUp = moveBtn.is('.wc-move-up'),
            isMoveDown = moveBtn.is('.wc-move-down');
    if (isMoveUp) {
      var $previewRow = $row.prev('tr');
      if ($previewRow && $previewRow.length) {
        $previewRow.before($row);
//					wp.a11y.speak( params.i18n_moved_up );
      }
    } else if (isMoveDown) {
      var $nextRow = $row.next('tr');
      if ($nextRow && $nextRow.length) {
        $nextRow.after($row);
//					wp.a11y.speak( params.i18n_moved_down );
      }
    }

    moveBtn.focus(); // Re-focus after the container was moved.
    moveBtn.closest('table').trigger('updateMoveButtons');
    moveBtn.closest('table').trigger('updateSaveButton');
  });
  $('.wc-item-reorder-nav').closest('table').on('updateMoveButtons', function () {
    var table = $(this),
            lastRow = $(this).find('tbody tr:last'),
            firstRow = $(this).find('tbody tr:first');
    table.find('.wc-item-reorder-nav .wc-move-disabled').removeClass('wc-move-disabled')
            .attr({'tabindex': '0', 'aria-hidden': 'false'});
    firstRow.find('.wc-item-reorder-nav .wc-move-up').addClass('wc-move-disabled')
            .attr({'tabindex': '-1', 'aria-hidden': 'true'});
    lastRow.find('.wc-item-reorder-nav .wc-move-down').addClass('wc-move-disabled')
            .attr({'tabindex': '-1', 'aria-hidden': 'true'});
  });
  $('table#qlwapp_contacts_table tbody').trigger('updateMoveButtons');
//save order of contact 
// Ajax order Submit
  $(document).on('submit', '#qlwapp_contacts_form', function (e) {
    e.preventDefault();
    var $form = $(this),
            $spinner = $form.find('.settings-save-status .spinner'),
            $saved = $form.find('.settings-save-status .saved');
    $.ajax({
      url: ajaxurl,
      data: {
        action: 'qlwapp_save_contact_order',
        nonce: qlwapp_contact.nonce.qlwapp_save_contact_order,
        contact_data: $form.serialize()
      },
      dataType: 'json',
      type: 'POST',
      beforeSend: function () {
        $spinner.addClass('is-active');
      },
      complete: function () {
        $spinner.removeClass('is-active');
      },
      error: function (response) {
        console.log('response from error ');
        console.log(response);
        /// alert(response);
      },
      success: function (response) {
        $saved.addClass('is-active');
        $('#qlwapp_contact_order').prop('disabled', true);
        if (response.success) {
          setTimeout(function () {
            $saved.removeClass('is-active');
          }, 1500);
//          console.log(response.data);
        } else {
          console.log(response.data);
          alert(response.data);
        }
      }
    });
    return false;
  });
})(jQuery);