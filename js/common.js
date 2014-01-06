
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
    $('.input-product-type').typeahead({
        source: function(query, process) {
            $states = [];
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
                        noContractor.productType = 'No product Type';
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
            return item.replace(regex, "<strong>$1</strong>");
        },
        updater: function(item) {
            var itemSplit = item.split(',');
            var productType = itemSplit[0];
            if (productType != "No product Type") {
                return productType;
            }


        },
        matcher: function(item) {
            return true;
        }
    });

});
 