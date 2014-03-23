<div class="list-of-products">
    <legend><strong>Inventory List</strong></legend>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $dataProvider,
        'id' => 'complete-inventory-list-gridview',
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
                'name' => 'description',
                'header' => 'Description',
                'value' => '$data["description"]',
            ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
//                'template' => '{delete}{update}',
                'template' => '{delete}',
                'buttons' => array
                    (
                    'delete' => array
                        (
                        'label' => 'Delete this Inventory Item',
                        'url' => 'Yii::app()->createAbsoluteUrl("/site/deleteInventory",array("id"=>$data["id"]))',
                    ),
//                    'update' => array(
//                        'label' => 'Edit this Inventory Item',
//                        'url' => 'Yii::app()->createAbsoluteUrl("/site/updateInventoryForm",array("id"=>$data["id"]))',
//                    )
                ),
            )
        )
    ));
    ?>
</div>
<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'edit-Inventory-modal')); ?>

<?php $this->endWidget();?>