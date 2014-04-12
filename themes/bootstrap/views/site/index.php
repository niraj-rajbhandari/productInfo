<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name;

?>
<?php if (Yii::app()->user->hasFlash('success')): ?>
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Success!</strong> <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
<?php elseif (Yii::app()->user->hasFlash('error')): ?>
        <div class="alert alert-error">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Error!</strong> <?php echo Yii::app()->user->getFlash('error'); ?>
        </div>
<?php elseif (Yii::app()->user->getFlash('warning')) : ?>
        <div class="alert">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Warning!</strong>  <?php echo Yii::app()->user->getFlash('warning'); ?>
        </div>
<?php endif; ?>
<div class="form-add-product">
  <legend>
    <strong>Sales Entry Form</strong>
  </legend>
  <?php
  $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
      'type'                   => 'horizontal',
      'id'                     => 'form-add-products',
      'enableClientValidation' => true,
      'clientOptions'          => array(
          'validateOnSubmit' => true,
      ),
  ));
  echo $form->errorSummary($productModel);
  echo $form->uneditableRow($productModel, 'date');
  echo $form->textFieldRow($productModel, 'code', array('autocomplete' => 'off'));
  ?>

  <?php
  echo $form->textFieldRow($productModel, 'product_type', array('autocomplete' => 'off', 'class'        => 'input-product-type'));
  echo $form->dropdownListRow($productModel, 'location', $stores, array('prompt' => 'Select a Location'));
  echo $form->textFieldRow($productModel, 'cost_price', array('id'           => 'input-cost-price', 'autocomplete' => 'off'));
  echo $form->textFieldRow($productModel, 'marked_price', array('autocomplete' => 'off'));
  echo $form->textFieldRow($productModel, 'selling_price', array('id'           => 'input-selling-price', 'autocomplete' => 'off'));
  echo $form->textFieldRow($productModel, 'gross_profit', array('id'           => 'input-gross-profit', 'disabled'     => 'disabled', 'autocomplete' => 'off'));
  echo $form->textAreaRow($productModel, 'description', array('cols'         => '5', 'rows'         => '5', 'autocomplete' => 'off'));

  ?>
  <div class="control-group">
    <div class="controls">
      <?php
      $this->widget('bootstrap.widgets.TbButton', array(
          'buttonType' => 'submit',
          'type'       => 'primary',
          'size'       => 'small',
          'label'      => 'Enter Sales',
      ));
      $this->widget('bootstrap.widgets.TbButton', array(
          'buttonType' => 'reset',
          'type'       => 'danger',
          'size'       => 'small',
          'label'      => 'Cancel',
          'htmlOptions'=>array('style'=>'margin:0 5px;')
      ));
      $this->endWidget();

      ?>
    </div>

  </div>


</div>
<div class="clearfix"></div>
<hr>
<div class="product-list">
  <legend><strong>Today's Sales</strong></legend>
  <?php
  $this->widget('bootstrap.widgets.TbGridView', array(
      'dataProvider' => $dataProvider,
      'id'           => 'product-list-gridview',
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
              'name'   => 'selling_price',
              'header' => 'Selling Price',
              'value'  => '$data["selling_price"]',
          ),
          array(
              'name'   => 'gross_profit',
              'header' => 'Gross Profit',
              'value'  => '$data["gross_profit"]',
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
                      'url'   => 'Yii::app()->createAbsoluteUrl("/site/delete",array("id"=>$data["id"]))',
                  ),
                  'update' => array(
                      'label' => 'Edit this product',
                      'url'   => 'Yii::app()->createAbsoluteUrl("/site/update",array("id"=>$data["id"]))',
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
          'after-edit', "  $('html,body').animate({ scrollTop: $('#product-list-gridview').offset().top }, 'slow');", CClientScript::POS_END
  );
endif;

?>

