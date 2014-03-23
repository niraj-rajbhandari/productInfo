
<legend>
    <strong>Edit Inventory with code : <span class="font-green"><?php echo $inventoryModel->code; ?></span></strong>
</legend>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'type' => 'horizontal',
    'id' => 'form-edit-inventory',
    'action' => Yii::app()->createAbsoluteUrl('/site/updateInventory', array('id' => $id)),
    'enableClientValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
        ));
echo $form->errorSummary($inventoryModel);
echo $form->uneditableRow($inventoryModel, 'date');
echo $form->textFieldRow($inventoryModel, 'code',array('autocomplete'=>'off'));
echo $form->textFieldRow($inventoryModel, 'product_type', array('class' => 'input-product-type', 'autocomplete' => 'off'));
echo $form->dropdownListRow($inventoryModel, 'location', $location);
echo $form->textFieldRow($inventoryModel, 'cost_price', array('id' => 'input-cost-price','autocomplete'=>'off'));
echo $form->textFieldRow($inventoryModel, 'marked_price',array('autocomplete'=>'off'));
echo $form->textAreaRow($inventoryModel, 'description', array('cols' => '5', 'rows' => '5'));
?>
<div class="control-group">
    <div class="controls">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'submit',
            'type' => 'primary',
            'size'=>'small',
            'label' => 'Edit Product',
        ));
        ?>
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType' => 'reset',
            'type' => 'info',
            'size'=>'small',
            'label' => 'Reset',
        ));
        $this->endWidget();
        ?>
    </div>

</div>