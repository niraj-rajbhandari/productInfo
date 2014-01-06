<legend>
    <strong>Edit Product <span class="font-green"><?php echo $productModel->code;?></span></strong>
</legend>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'type' => 'horizontal',
    'id' => 'form-edit-products',
    'action' => Yii::app()->createAbsoluteUrl('/site/updateProduct', array('id' => $id)),
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
        ));
echo $form->errorSummary($productModel);
echo $form->uneditableRow($productModel, 'date');
echo $form->textFieldRow($productModel, 'code');
echo $form->textFieldRow($productModel, 'product_type', array('class' => 'input-product-type','autocomplete'=>'off'));
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
            'label' => 'Edit Product',
        ));
        ?>
          <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'reset',
            'type' => 'info',
            'label' => 'Reset',
        ));
        $this->endWidget();
        ?>
    </div>

</div>
<script type="text/javascript">
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
            return item.replace(regex, "<strong class='font-green'>$1</strong>");
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

</script>

