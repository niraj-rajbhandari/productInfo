<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

        <?php Yii::app()->bootstrap->register(); ?>
        <script>
            baseUrl='<?php echo Yii::app()->request->baseUrl;?>';
        </script>
    </head>

    <body>

        <?php
        $this->widget('bootstrap.widgets.TbNavbar', array(
            'type' => 'inverse',
            'fixed' => 'top',
            'brand' => 'Products Info',
            'fluid'=>'true',
            'brandUrl' => Yii::app()->createAbsoluteurl(''),
        ));
        ?>

        <div class="container-fluid" id="page">

            <?php if (isset($this->breadcrumbs)): ?>
                <?php
                $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                ));
                ?><!-- breadcrumbs -->
            <?php endif ?>
                <div class="row-fluid">
                    <div class="span12">
                        <?php echo $content; ?>
                    </div>
                </div>
            <div class="clear"></div>



        </div><!-- page -->
        <div id="footer">
            <?php echo $this->renderPartial('/layouts/includes/footer'); ?>
        </div><!-- footer -->

    </body>
</html>
