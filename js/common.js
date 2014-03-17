
$(function() {
  //drop down menu on hover
  $('.nav li.dropdown').hover(function() {
    $('.nav li.dropdown').removeClass('open');
    $(this).addClass('open');
  });

  $('#wrap').hover(function() {
    $('.nav li.dropdown').removeClass('open');
  });
  //hide drop-down menu
  $('.nav li.no-drop').hover(function() {
    $('.nav li.dropdown').removeClass('open');
  });
  $('.nav li.no-drop, .nav-li.dropdown').click(function() {
    $('profile-setting-btn').popover('hide');
  });
  $(window).click(function() {
    $('.nav li.dropdown').removeClass('open');
  });
  $(document).resize(function() {
    $('.nav li.dropdown').removeClass('open');
  });
  //------------------------------
  $('#input-selling-price').focusout(function() {
    var costPrice = $('#input-cost-price').val();
    var sellingPrice = $('#input-selling-price').val();
    if (costPrice.length != 0 || sellingPrice.length != 0) {
      var grossProfit = sellingPrice - costPrice;
      $('#input-gross-profit').val(grossProfit);
      $('#input-gross-profit').removeAttr('disabled');
    }

  });
  $(document).on('click', '#product-list-gridview .update', function() {
    url = $(this).attr('href');
    $.ajax({
      type: 'POST',
      url: url,
      dataType: 'JSON',
      success: function(data) {
        $('.form-add-product').html('');
        $('.form-add-product').html(data.view);
        $('html, body').animate({scrollTop: 0}, 'slow');
      }
    });
    return false;
  });

  $(document).on('click', '#complete-product-list-gridview .update', function() {
    url = $(this).attr('href');
    $.ajax({
      type: 'POST',
      url: url,
      data: {list: 'yes'},
      dataType: 'JSON',
      success: function(data) {
        $('#edit-products-modal').html('');
        $('#edit-products-modal').html(data.view);
        $('#edit-products-modal').modal();
      }
    });
    return false;
  });

  // Autosuggest
  $(document).on('focus', '.input-product-type', function() {
    $('.input-product-type').typeahead({
      source: function(query, process) {
        states = [];
        noContractor = {};
        $.ajax({
          url: $global_variable.get('baseUrl') + '/site/getProductType',
          type: "POST",
          data: {query: $('.input-product-type').val()},
          dataType: "JSON",
          async: false,
          success: function(data) {
            states = [];
            map = {};
            noContractor = {};
            if (data.length == 0) {
              noContractor.product_type = 'Not a valid product type';
              states.push(noContractor.product_type);
            } else {
              $.each(data, function(i, state) {
                states.push(state.product_type);
              });
            }
            process(states);
          }
        });

      },
      items: 37,
      sorter: function(items) {
        return items.sort();
      },
      highlighter: function(item) {
        var regex = new RegExp('(' + this.query + ')', 'gi');
        return item.replace(regex, "<strong class='font-green'>$1</strong>");
      },
      updater: function(item) {
        var itemSplit = item.split(',');
        var productType = itemSplit[0];
        if (productType != "Not a valid product type") {
          return productType;
        }


      },
      matcher: function(item) {
        return true;
      }
    });
  });

  $('.product-list').click(function() {

    $('html,body').animate({scrollTop: $('#product-list-gridview').offset().top}, 'slow');
  });

  $(document).on('click', '#add-store-menu', function() {
    var url = $global_variable.get('baseUrl') + '/site/addStoreForm';
    $.ajax({
      type: 'GET',
      url: url,
      dataType: 'JSON'
    }).done(function(response) {
      $('#add-stores-modal').html('');
      $('#add-stores-modal').html(response.view);
      $('#add-stores-modal').modal();
    }).fail(function(xhr) {
      console.log(xhr);
    });
  });
  $(document).on('submit', '#form-add-stores', function() {
    var serializedData = $(this).serialize();
    var url = $global_variable.get('baseUrl') + '/site/addStore';
    $.ajax({
      type: 'POST',
      data: serializedData,
      url: url,
      dataType: 'JSON'
    }).done(function(response) {
      if (response.statusCode == 200) {
        $('#add-stores-modal').modal('hide');
        $('.add-store-form-error').css('display', 'none');
        window.location.reload();
      } else {
        var errorList = '<ul>';
        $.each(response.message, function(k, v) {
          errorList += '<li>' + v + '</li>';
        });
        errorList += '</ul>';
        $('.add-store-form-error').html('');
        $('.add-store-form-error').html(errorList);
        $('.add-store-form-error').css('display', '');
      }
    }).fail(function(xhr) {
      console.log(xhr);
    });
    return false;
  });

  $(document).on('click', '#add-product-type-menu', function() {
    var url = $global_variable.get('baseUrl') + '/site/addProductTypeForm';
    $.ajax({
      type: 'GET',
      url: url,
      dataType: 'JSON'
    }).done(function(response) {
      $('#add-product-type-modal').html('');
      $('#add-product-type-modal').html(response.view);
      $('#add-product-type-modal').modal();
    }).fail(function(xhr) {
      console.log(xhr);
    });
  });
  $(document).on('submit', '#form-add-product-type', function() {
    var serializedData = $(this).serialize();
    var url = $global_variable.get('baseUrl') + '/site/addProductType';
    $.ajax({
      type: 'POST',
      data: serializedData,
      url: url,
      dataType: 'JSON'
    }).done(function(response) {
      if (response.statusCode == 200) {
        $('#add-stores-modal').modal('hide');
        $('.add-product-type-form-error').css('display', 'none');
        window.location.reload();
      } else {
        var errorList = '<ul>';
        $.each(response.message, function(k, v) {
          errorList += '<li>' + v + '</li>';
        });
        errorList += '</ul>';
        $('.add-product-type-error').html('');
        $('.add-product-type-error').html(errorList);
        $('.add-product-type-error').css('display', '');
      }
    }).fail(function(xhr) {
      console.log(xhr);
    });
    return false;
  });
});

function autoUpdateGridView(id) {

  var timer;
  var inputId = '#' + id + ' .filters input[type=text] ';
  $(inputId).live('keyup', function(e) {
    var focusedId = $(document.activeElement).attr('name');
    clearTimeout(timer);
    timer = setTimeout(function() {
      $.fn.yiiGridView.update(id, {
        data: $(inputId).serialize(),
        complete: function(jqXHR, status) {
          if (status == 'success') {
            //refocus last filter input box.
            $('input[name="' + focusedId + '"]').focus();
            tmpStr = $('input[name="' + focusedId + '"]').val();
            $('input[name="' + focusedId + '"]').val('');
            $('input[name="' + focusedId + '"]').val(tmpStr);
          }
        }

      });
    }, 1000);
  });

}
autoUpdateGridView('product-list-gridview');