<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;

?>

<div class="form-add-inventory">
  <legend>
    <strong>Inventory Form</strong>
  </legend>
  <?php
  $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
      'type'                   => 'horizontal',
      'id'                     => 'form-add-inventory',
      'enableClientValidation' => true,
      'clientOptions'          => array(
          'validateOnSubmit' => true,
      ),
  ));
  echo $form->errorSummary($inventoryModel);
  echo $form->uneditableRow($inventoryModel, 'date');
  echo $form->textFieldRow($inventoryModel, 'code', array('autocomplete' => 'off'));
  echo $form->textFieldRow($inventoryModel, 'product_type', array('autocomplete' => 'off', 'class'        => 'input-product-type'));
  echo $form->dropdownListRow($inventoryModel, 'location', $stores, array('prompt' => 'Select a Location'));
  echo $form->textFieldRow($inventoryModel, 'cost_price', array('id'           => 'input-cost-price', 'autocomplete' => 'off'));
  echo $form->textFieldRow($inventoryModel, 'marked_price', array('autocomplete' => 'off'));
  echo $form->textFieldRow($inventoryModel, 'quantity', array('autocomplete' => 'off'));
  echo $form->textAreaRow($inventoryModel, 'description', array('cols'         => '5', 'rows'         => '5', 'autocomplete' => 'off'));

  ?>
  <div class="control-group">
    <div class="controls">
      <?php
      $this->widget('bootstrap.widgets.TbButton', array(
          'buttonType' => 'submit',
          'type'       => 'primary',
          'size'       => 'small',
          'label'      => 'Add to Inventory',
      ));
      $this->widget('bootstrap.widgets.TbButton', array(
          'buttonType'  => 'reset',
          'type'        => 'danger',
          'size'        => 'small',
          'label'       => 'Cancel',
          'htmlOptions' => array('style' => 'margin:0 5px;')
      ));
      $this->endWidget();

      ?>
    </div>

  </div>


</div>
<div class="clearfix"></div>
<hr>
<div class="product-list">
  <legend><strong>Products Added to Inventory</strong></legend>
  <?php
  $this->widget('bootstrap.widgets.TbGridView', array(
      'dataProvider' => $dataProvider,
      'id'           => 'inventory-list-gridview',
      'filter'       => $arrayFilter,
      'type'         => 'condensed striped ',
      'columns'      => array(
          array(
              'name'   => 'date',
              'header' => 'Date',
              'value'  => '$data["date"]'
          ),
          array(
              'name'   => 'code',
              'header' => 'Code',
              'value'  => '$data["code"]'
          ),
          array(
              'name'   => 'product_type',
              'header' => 'Product Type',
              'value'  => '$data["product_type"]'
          ),
          array(
              'name'   => 'location',
              'header' => 'Location',
              'value'  => '$data["location"]',
          ),
          array(
              'name'   => 'cost_price',
              'header' => 'Cost Price',
              'value'  => '$data["cost_price"]',
          ),
          array(
              'name'   => 'marked_price',
              'header' => 'Marked Price',
              'value'  => '$data["marked_price"]',
          ),
          array(
              'name'   => 'quantity',
              'header' => 'Quantity',
              'value'  => '$data["quantity"]',
          ),
          array(
              'name'   => 'description',
              'header' => 'Description',
              'value'  => '$data["description"]',
          ),
          array(
              'class'    => 'bootstrap.widgets.TbButtonColumn',
              'template' => '{delete}{update}',
              'buttons'  => array
                  (
                  'delete' => array
                      (
                      'label' => 'Delete this product',
                      'url'   => 'Yii::app()->createAbsoluteUrl("/site/deleteInventory",array("id"=>$data["id"]))',
                  ),
                  'update' => array(
                      'label' => 'Edit this product',
                      'url'   => 'Yii::app()->createAbsoluteUrl("/site/updateInventoryForm",array("id"=>$data["id"]))',
                  )
              ),
          )
      )
  ));

  ?>
</div>
<?php
$cs = Yii::app()->getClientScript();
if (isset($_GET['edit']) && $_GET['edit'] == 'yes'):
  $cs->registerScript(
          'after-edit', "  $('html,body').animate({ scrollTop: $('#inventory-list-gridview').offset().top }, 'slow');", CClientScript::POS_END
  );
endif;

?>

