<div class="list-of-products">
    <legend><strong>Products List</strong></legend>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $dataProvider,
        'id' => 'complete-product-list-gridview',
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
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'edit-products-modal')); ?>

<?php $this->endWidget();?>