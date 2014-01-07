<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;
?>

<div class="form-add-product">
    <legend>
        <strong>Product Entry Form</strong>
        <a href="#product-list" class="btn btn-small btn-inverse pull-right product-list">Show Product List</a>
    </legend>
    <?php
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'type' => 'horizontal',
        'id' => 'form-add-products',
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    ));
    echo $form->errorSummary($productModel);
    echo $form->uneditableRow($productModel, 'date');
    echo $form->textFieldRow($productModel, 'code');
    echo $form->textFieldRow($productModel, 'product_type', array('autocomplete' => 'off', 'class' => 'input-product-type'));
    echo $form->dropdownListRow($productModel, 'location', $location, array('prompt' => 'Select a Location'));
    echo $form->textFieldRow($productModel, 'cost_price', array('id' => 'input-cost-price'));
    echo $form->textFieldRow($productModel, 'marked_price');
    echo $form->textFieldRow($productModel, 'selling_price', array('id' => 'input-selling-price'));
    echo $form->textFieldRow($productModel, 'net_profit', array('id' => 'input-gross-profit', 'disabled' => 'disabled'));
    echo $form->textAreaRow($productModel, 'description', array('cols' => '5', 'rows' => '5'));
    ?>
    <div class="control-group">
        <div class="controls">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'size'=>'small',
                'label' => 'Add Product',
            ));
            $this->endWidget();
            ?>
        </div>

    </div>


</div>
<div class="clearfix"></div>
<hr>
<div class="product-list">
    <legend><strong>Products List</strong></legend>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $dataProvider,
        'id' => 'product-list-gridview',
        'filter' => $arrayFilter,
        'type' => 'condensed striped ',
        'columns' => array(
            array(
                'name' => 'date',
                'header' => 'Date',
                'value' => '$data["date"]'
            ),
            array(
                'name' => 'code',
                'header' => 'Code',
                'value' => '$data["code"]'
            ),
            array(
                'name' => 'product_type',
                'header' => 'Product Type',
                'value' => '$data["product_type"]'
            ),
            array(
                'name' => 'location',
                'header' => 'Location',
                'value' => '$data["location"]',
            ),
            array(
                'name' => 'cost_price',
                'header' => 'Cost Price',
                'value' => '$data["cost_price"]',
            ),
            array(
                'name' => 'marked_price',
                'header' => 'Marked Price',
                'value' => '$data["marked_price"]',
            ),
            array(
                'name' => 'selling_price',
                'header' => 'Selling Price',
                'value' => '$data["selling_price"]',
            ),
            array(
                'name' => 'net_profit',
                'header' => 'Net Profit',
                'value' => '$data["net_profit"]',
            ),
            array(
                'name' => 'description',
                'header' => 'Description',
                'value' => '$data["description"]',
            ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{delete}{update}',
                'buttons' => array
                    (
                    'delete' => array
                        (
                        'label' => 'Delete this product',
                        'url' => 'Yii::app()->createAbsoluteUrl("/site/delete",array("id"=>$data["id"]))',
                    ),
                    'update' => array(
                        'label' => 'Edit this product',
                        'url' => 'Yii::app()->createAbsoluteUrl("/site/update",array("id"=>$data["id"]))',
                    )
                ),
            )
        )
    ));
    ?>
</div>
<script type="text/javascript">
    $('.product-list').click(function() {
        
        $('html,body').animate({ scrollTop: $('#product-list-gridview').offset().top }, 'slow');
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
    autoUpdateGridView('neustar-gridview');

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

</script>

