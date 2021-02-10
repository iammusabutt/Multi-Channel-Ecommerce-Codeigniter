// autocomplete functionality
if (jQuery('input#product_search-autocomplete').length > 0) {
    jQuery('input#product_search-autocomplete').typeahead({
      displayText: function(item) {
           return item.object_title
      },
      afterSelect: function(item) {
        this.$element[0].value = item.object_title;
        var object_id = item.object_id;
        jQuery("input#product_search-field-autocomplete").val(item.object_id);
        jQuery.ajax({
          url: baseurl + "user/products/ajax_fetch_product_vendors",
          dataType: "json",
          type: "POST",
          data: {
            object_id: object_id // notice misspelled variable name
          },
          //data: form_data, // serializes the form's elements.
          success: function (data) {
            if(data.response == "yes") {
              $('.product-vendor-list').html(data.content);
              $('.selectpicker').selectpicker('refresh');
            } else {
              $.Notification.autoHideNotify('error', 'top right', 'Unauthorized Action', 'Following action is not allowed by system');
            }
          }
        });
      },
      source: function (query, process) {
        jQuery.ajax({
                url: baseurl + "user/products/ajax_fetch_products",
                data: {query:query},
                dataType: "json",
                type: "POST",
                success: function (data) {
                    process(data)
                }
            })
      }   
    });
}
