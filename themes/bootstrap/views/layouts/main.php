<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <?php
    Yii::app()->bootstrap->register();

    $cs = Yii::app()->getClientScript();
    $cs->registerScriptFile(Yii::app()->assetManager->publish('js/global_variables.js'));
    $cs->registerScriptFile(Yii::app()->assetManager->publish('js/common.js'));

    ?>
    <script>
      $(document).ready(function() {
        $global_variable.set('baseUrl', '<?php echo Yii::app()->request->getBaseUrl(true); ?>');
      });
    </script>
  </head>
  <body>
    <div id="wrap">
      <!-- Fixed navbar -->
      <?php
      $this->widget('bootstrap.widgets.TbNavbar', array(
          'type'     => 'inverse', // null or 'inverse'
          'brand'    => Yii::app()->name,
          'brandUrl' => Yii::app()->createAbsoluteUrl(''),
          'fixed'    => 'top',
          'fluid'    => true,
          'collapse' => true, // requires bootstrap-responsive.css
          'items'    => array(
              array(
                  'class' => 'bootstrap.widgets.TbMenu',
                  'items' => array(
                      array('label' => 'Admin',
                          'items' => array(
                              array(
                                  'label'       => 'Add Store',
                                  'url'         => 'javascript:void(0)',
                                  'itemOptions' => array('id' => 'add-store-menu')
                              ),
                              array(
                                  'label' => 'List Stores',
                                  'url'   => Yii::app()->createAbsoluteUrl('/site/liststores')
                              ),
                              array(
                                  'label'       => 'Add Product Type',
                                  'url'         => 'javascript:void(0)',
                                  'itemOptions' => array('id' => 'add-product-type-menu')
                              ),
                              array(
                                  'label' => 'List Product Type',
                                  'url'   => Yii::app()->createAbsoluteUrl('/site/listproducttype')
                              )
                          )
                      ),
                      array('label' => 'Sales',
                          'items' => array(
                              array(
                                  'label' => 'Add Sales',
                                  'url'   => Yii::app()->createAbsoluteUrl(''),
                              ),
                              array(
                                  'label' => 'Sales List',
                                  'url'   => Yii::app()->createAbsoluteUrl('/site/listproducts'),
                              ),
                          )
                      ),
                      array('label' => 'Inventory',
                          'items' => array(
                              array(
                                  'label' => 'Inventory List',
                                  'url'   => Yii::app()->createAbsoluteUrl('/site/inventorylist')
                              ),
                              array(
                                  'label' => 'Add Product to Inventory',
                                  'url'   => Yii::app()->createAbsoluteUrl('/site/addinventory')
                              ),
                          ),
                      )
                  ),
              ),
          ),
      ));

      ?>
      <!-- Begin page content -->
      <div class="container-fluid" id="page">
        <?php if (isset($this->breadcrumbs)): ?>
          <?php
          $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
              'links' => $this->breadcrumbs,
          ));

          ?> <!-- breadcrumbs -->
        <?php endif ?>
        <div class="row-fluid">
          <div class="span12">
            <?php echo $content; ?>
          </div>
        </div>

      </div> <!--page -->
      <div id="push"></div>
    </div>
    <div id="footer">
      <?php echo $this->renderPartial('/layouts/includes/footer'); ?>
    </div>
    <?php
    $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'add-stores-modal'));
    $this->endWidget();

    $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'add-product-type-modal'));
    $this->endWidget();

    ?>
  </body>
</html>
