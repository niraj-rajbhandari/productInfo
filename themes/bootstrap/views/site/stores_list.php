<div class="list-of-stores">
    <legend><strong>Stores List</strong></legend>
    <?php
    $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $dataProvider,
        'id' => 'stores-list-gridview',
        'filter' => $arrayFilter,
        'type' => 'condensed striped ',
        'columns' => array(
            array(
                'name' => 'id',
                'header' => '#',
                'value' => '$data["store_id"]'
            ),
            array(
                'name' => 'store_name',
                'header' => 'Store Name',
                'value' => '$data["store_name"]'
            ),
            array(
                'class' => 'bootstrap.widgets.TbButtonColumn',
                'template' => '{delete}',
                'buttons' => array
                    (
                    'delete' => array
                        (
                        'label' => 'Delete this Store',
                        'url' => 'Yii::app()->createAbsoluteUrl("/site/deletestore",array("id"=>$data["store_id"]))',
                    ),
                ),
            )
        )
    ));
    ?>
</div>
