<div class="list-of-product-type">
    <legend><strong>List of Product Type</strong></legend>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $dataProvider,
        'id' => 'product-type-list-gridview',
        'filter' => $arrayFilter,
        'type' => 'condensed striped ',
        'columns' => array(
            array(
                'name' => 'id',
                'header' => '#',
                'value' => '$data["id"]'
            ),
            array(
                'name' => 'product_type',
                'header' => 'Product Type',
                'value' => '$data["product_type"]'
            ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{delete}',
                'buttons' => array
                    (
                    'delete' => array
                        (
                        'label' => 'Delete this Product Type',
                        'url' => 'Yii::app()->createAbsoluteUrl("/site/deleteproducttype",array("id"=>$data["id"]))',
                    ),
                ),
            )
        )
    ));
    ?>
</div>
