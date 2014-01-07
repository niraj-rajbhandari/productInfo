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
    echo $form->textFieldRow($productModel, 'code',array('autocomplete'=>'off'));
    echo $form->textFieldRow($productModel, 'product_type', array('autocomplete' => 'off', 'class' => 'input-product-type'));
    echo $form->dropdownListRow($productModel, 'location', $location, array('prompt' => 'Select a Location'));
    echo $form->textFieldRow($productModel, 'cost_price', array('id' => 'input-cost-price','autocomplete'=>'off'));
    echo $form->textFieldRow($productModel, 'marked_price',array('autocomplete'=>'off'));
    echo $form->textFieldRow($productModel, 'selling_price', array('id' => 'input-selling-price','autocomplete'=>'off'));
    echo $form->textFieldRow($productModel, 'gross_profit', array('id' => 'input-gross-profit', 'disabled' => 'disabled','autocomplete'=>'off'));
    echo $form->textAreaRow($productModel, 'description', array('cols' => '5', 'rows' => '5','autocomplete'=>'off'));
    ?>
    <div class="control-group">
        <div class="controls">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'size' => 'small',
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
                'name' => 'gross_profit',
                'header' => 'Gross Profit',
                'value' => '$data["gross_profit"]',
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
<?php
$cs = Yii::app()->getClientScript();
if (isset($_GET['edit']) && $_GET['edit'] == 'yes'):
    $cs->registerScript(
            'after-edit', "  $('html,body').animate({ scrollTop: $('#product-list-gridview').offset().top }, 'slow');", CClientScript::POS_END
    );
endif;
?>

