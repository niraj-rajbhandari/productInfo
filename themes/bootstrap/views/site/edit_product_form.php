
<legend>
    <strong>Edit Product <span class="font-green"><?php echo $productModel->code; ?></span></strong>
    <a href="<?php echo Yii::app()->createAbsoluteUrl(''); ?>" class="btn btn-small btn-inverse pull-right">Add Product</a>
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
echo $form->textFieldRow($productModel, 'code',array('autocomplete'=>'off'));
echo $form->textFieldRow($productModel, 'product_type', array('class' => 'input-product-type', 'autocomplete' => 'off'));
echo $form->dropdownListRow($productModel, 'location', $location);
echo $form->textFieldRow($productModel, 'cost_price', array('id' => 'input-cost-price','autocomplete'=>'off'));
echo $form->textFieldRow($productModel, 'marked_price',array('autocomplete'=>'off'));
echo $form->textFieldRow($productModel, 'selling_price', array('id' => 'input-selling-price','autocomplete'=>'off'));
echo $form->textFieldRow($productModel, 'gross_profit', array('id' => 'input-gross-profit', 'disabled' => 'disabled','autocomplete'=>'off'));
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
    $('#input-selling-price,#input-cost-price').focusout(function() {
        var costPrice = $('#input-cost-price').val();
        var sellingPrice = $('#input-selling-price').val();
        if (costPrice.length != 0 || sellingPrice.length != 0) {
            var grossProfit = sellingPrice - costPrice;
            $('#input-gross-profit').val(grossProfit);
            $('#input-gross-profit').removeAttr('disabled');
        }

    });
</script>