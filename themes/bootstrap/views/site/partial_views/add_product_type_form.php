<div class="modal-header">
  <a href="javascript:void(0)" class="close" data-dismiss="modal" aria-hidden="true">X</a>
  <h3>Add Stores</h3>
</div>
<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'type'                   => 'horizontal',
    'id'                     => 'form-add-product-type',
    'enableClientValidation' => true,
    'clientOptions'          => array(
        'validateOnSubmit' => true,
    ),
        ));

?>
<div class="modal-body">
  <div class='alert alert-error add-product-form-error' style='display:none;'>

  </div>
  <?php
  echo $form->errorSummary($productTypeModel);
  echo $form->textFieldRow($productTypeModel, 'product_type', array('auto_complete' => 'off'));

  ?>
</div>

<div class="control-group">
  <div class="controls">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type'       => 'primary',
        'size'       => 'small',
        'label'      => 'Add Product Type',
        'htmlOptions'=>array('class'=>'pull-right','style'=>'margin:10px 10px 0;')
    ));

    ?>
<?php $this->endWidget(); ?>
  </div>

</div>

