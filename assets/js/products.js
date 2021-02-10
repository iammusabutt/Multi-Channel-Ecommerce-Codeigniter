$(".select2").select2();
  jQuery(document).ready(function($){
    $("#product_type").change(function() {
      $('.simple').addClass('visible'); // hide all divs by removing class visible
      $('#' + $(this).val().toLowerCase()).removeClass('visible');  // find the matching div and add class visible to it
    });
  });
  function cleartags() {
    var $tagsMulti = $("#cleartags").select2()
    $tagsMulti.val(null).trigger("change");
  }
  $(document).on("click", "#select_attribute", function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    e.stopPropagation();
    var l = Ladda.create(this);
    l.start();
    var object_id = $(this).data('object-id');
    var attribute_value = $("#attribute_value").val();
    
    var form_data = new FormData();
    form_data.append("object_id", object_id)
    form_data.append("attribute_value", attribute_value)
    var url = base_url + account_type + "/products/ajax_assign_product_attribute";

    $.ajax({
      url: url,
      type: "POST",
      async:"false",
      dataType: "html",
      cache:false,
      contentType: false,
      processData: false,
      data: form_data, // serializes the form's elements.
      success: function(data)
      {				
        data = JSON.parse(data);
        if(data.response == "yes")
        {
          l.stop();
          $('.attributelist').html(data.content);
          $('.select2').select2();
          $.Notification.autoHideNotify('custom', 'top right', 'Success', data.message);
        }
        else
        {
          l.stop();
          $.Notification.autoHideNotify('error', 'top right', 'Attribute Already Exist!', data.message);
        }
      }
    });
  });

  /**
   * Delete Attribute
   *
   * @return {Array}
   */
  $(document).on("click", "#delete_attribute", function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    e.stopPropagation();
    var l = Ladda.create(this);
    l.start();
    var object_id = $(this).data('object-id');
    var attribute_value = $(this).data('attributevalue');
    
    var form_data = new FormData();
    form_data.append("object_id", object_id)
    form_data.append("attribute_value", attribute_value)
    var url = base_url + account_type + "/products/ajax_unassign_product_attribute";

    $.ajax({
      url: url,
      type: "POST",
      async:"false",
      dataType: "html",
      cache:false,
      contentType: false,
      processData: false,
      data: form_data, // serializes the form's elements.
      success: function(data)
      {				
        data = JSON.parse(data);
        if(data.response == "yes")
        {
          l.stop();
          $('.attributelist').html(data.content);
          $('.select2').select2();
          $.Notification.autoHideNotify('custom', 'top right', 'Attributed Deleted!', data.message);
        }
        else
        {
          l.stop();
          $.Notification.autoHideNotify('error', 'top right', 'Attribute Does Not Exist!', data.message);
        }
      }
    });
  });
  
  $(document).on("click", "#save_attribute", function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    e.stopPropagation();
    var l = Ladda.create(this);
    l.start();
    var object_id = $(this).data('object-id');
    var attribute_values = $( '.attribute-rows' ).find( 'select' ).serialize();
    var form_data = new FormData();
    form_data.append("object_id", object_id)
    form_data.append("attribute_values", attribute_values)
    var url = base_url + account_type + "/products/ajax_save_product_attribute";

    $.ajax({
      url: url,
      type: "POST",
      async:"false",
      dataType: "html",
      cache:false,
      contentType: false,
      processData: false,
      data: form_data, // serializes the form's elements.
      success: function(data)
      {				
        data = JSON.parse(data);
        if(data.response == "yes")
        {
          l.stop();
          $('.category').html(data.content);
          $.Notification.autoHideNotify('custom', 'top right', 'Success', data.message);
        }
      }
    });
  });
  
  $(document).on("click", "#add_variation", function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    e.stopPropagation();
    var l = Ladda.create(this);
    l.start();
    var object_id = $(this).data('object-id');
    var variation_action = $("#variation_action").val();
    console.log(variation_action);
    var form_data = new FormData();
    form_data.append("object_id", object_id)
    form_data.append("action", variation_action)
    var url = base_url + account_type + "/products/ajax_add_product_variation";

    $.ajax({
      url: url,
      type: "POST",
      async:"false",
      dataType: "html",
      cache:false,
      contentType: false,
      processData: false,
      data: form_data, // serializes the form's elements.
      success: function(data)
      {				
        data = JSON.parse(data);
        if(data.response == "yes")
        {
          l.stop();
          $('.variationlist').html(data.content);
          $('.selectpicker').selectpicker('refresh');
          $.Notification.autoHideNotify('custom', 'top right', 'Success', data.message);
        }
      }
    });
  });
  
  $(document).on("click", "#delete_variation", function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    e.stopPropagation();
    var l = Ladda.create(this);
    l.start();
    var object_id = $(this).data('object-id');
    var variation_id = $(this).data('variation-id');
    
    var form_data = new FormData();
    form_data.append("object_id", object_id)
    form_data.append("variation_id", variation_id)
    var url = base_url + account_type + "/products/ajax_delete_product_variation";

    $.ajax({
      url: url,
      type: "POST",
      async:"false",
      dataType: "html",
      cache:false,
      contentType: false,
      processData: false,
      data: form_data, // serializes the form's elements.
      success: function(data)
      {				
        data = JSON.parse(data);
        if(data.response == "yes")
        {
          l.stop();
          $('.variationlist').html(data.content);
          $('.selectpicker').selectpicker('refresh');
          $.Notification.autoHideNotify('custom', 'top right', 'Variation Deleted!', data.message);
        }
        else
        {
          l.stop();
          $.Notification.autoHideNotify('error', 'top right', 'Variation Does Not Exist!', data.message);
        }
      }
    });
  });
  
  $(document).on("click", "#save_variation", function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    e.stopPropagation();
    var l = Ladda.create(this);
    l.start();
    var object_id = $(this).data('object-id');
    var response = $( '.variation-rows' ).find( 'select, input' ).serializeJSON();
    var variations_values = JSON.stringify(response);
    console.log(variations_values);
    var form_data = new FormData();
    form_data.append("object_id", object_id)
    form_data.append("variations_values", variations_values)
    var url = base_url + account_type + "/products/ajax_save_product_variation";

    $.ajax({
      url: url,
      type: "POST",
      async:"false",
      dataType: "html",
      cache:false,
      contentType: false,
      processData: false,
      data: form_data, // serializes the form's elements.
      success: function(data)
      {				
        data = JSON.parse(data);
        if(data.response == "yes")
        {
          l.stop();
          $('.category').html(data.content);
          $.Notification.autoHideNotify('custom', 'top right', 'Success', data.message);
        }
      }
    });
  });