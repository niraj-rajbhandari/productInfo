
$(function() {
    $('#input-selling-price').focusout(function() {
        var costPrice = $('#input-cost-price').val();
        var sellingPrice = $('#input-selling-price').val();
        if (costPrice.length != 0 || sellingPrice.length != 0) {
            var grossProfit = sellingPrice - costPrice;
            $('#input-gross-profit').val(grossProfit);
            $('#input-gross-profit').removeAttr('disabled');
        }

    });
    $('.update').click(function() {
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

    // Autosuggest
    $(document).on('focus', '.input-product-type', function() {
        $('.input-product-type').typeahead({
            source: function(query, process) {
                states = [];
                noContractor = {};
                $.ajax({
                    url: baseUrl + '/site/getProductType',
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