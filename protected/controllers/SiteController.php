<?php

class SiteController extends Controller {

  /**
   * Declares class-based actions.
   */
  public function actions() {
    return array(
        // captcha action renders the CAPTCHA image displayed on the contact page
        'captcha' => array(
            'class'     => 'CCaptchaAction',
            'backColor' => 0xFFFFFF,
        ),
        // page action renders "static" pages stored under 'protected/views/site/pages'
        // They can be accessed via: index.php?r=site/page&view=FileName
        'page'    => array(
            'class' => 'CViewAction',
        ),
    );
  }

  /**
   * This is the default 'index' action that is invoked
   * when an action is not explicitly requested by users.
   */
  public function actionIndex() {
    try {
      $productModel = new Products();
      $productModel->date = date('m-d-Y');
      $storesModel = Stores::model()->findAll();
      $stores = array();
      foreach ($storesModel as $store) {
        $stores[$store->store_name] = $store->store_name;
      }
      if (Yii::app()->request->isPostRequest) {
        if (isset($_POST['Products'])) {
          $productModel->attributes = Yii::app()->request->getPost('Products');
          $productModel->date = date('Y-m-d');
          if ($productModel->validate()) {
            if ($productModel->save()) {
              $this->redirect(Yii::app()->createAbsoluteUrl(''));
            }
            else {
              throw new CHttpException('500', 'The product information could not be saved. Please try again.');
            }
          }
          else {
            throw new CHttpException('500', ' Please fill the form properly.');
          }
        }
      }

      if (Yii::app()->request->getQuery('Products')) {
        $productModel->attributes = Yii::app()->request->getQuery('Products');
      }
      $productCriteria = new CDbCriteria();
      $condition = "date='" . date('Y-m-d') . "'";
      $productCriteria->addCondition($condition);
      $productsInfo = Products::model()->findAll($productCriteria);

      $dataProviderContainer = $this->_getProductsListArrayProvider($productsInfo);

      $this->render('index', array('productModel' => $productModel, 'stores'       => $stores, 'dataProvider' => $dataProviderContainer['dataProvider'], 'arrayFilter'  => $dataProviderContainer['arrayFilter']));
    }
    catch (Exception $e) {
      throw new CHttpException('500', $e->getMessage());
    }
  }

  public function actionlistProducts() {
    try {
      $productsModel = Products::model()->findAll();
      $dataProviderContainer = $this->_getProductsListArrayProvider($productsModel);
      $this->render('product_list', array('dataProvider' => $dataProviderContainer['dataProvider'], 'arrayFilter'  => $dataProviderContainer['arrayFilter']));
    }
    catch (Exception $e) {
      Throw new CHttpException('500', $e->getMessage());
    }
  }

  private function _getProductsListArrayProvider($productsInfo) {
    $products = array();
    if (!empty($productsInfo)) {
      foreach ($productsInfo as $product) {
        $products[] = $product->attributes;
      }
    }
    if (!empty($products)) {
      array_walk($products, function(&$v, $k) {
                $v['date'] = date('m-d-Y', strtotime($v['date']));
              });
    }
    $arrayFilter = new ArrayFilterClass;

    if (isset($_GET['ArrayFilterClass'])) {
      $arrayFilter->filters = $_GET['ArrayFilterClass'];
    }

    $arrayProvider = $arrayFilter->filter($products);
    //re-indexing array keys
//            $arrayProvider = array_values($arrayProvider);

    $dataProvider = new CArrayDataProvider($arrayProvider, array('id'         => 'id', 'sort'       => array('attributes' => array('code', 'product_type', 'location', 'cost_price', 'selling_price', 'marked_price', 'gross_profit', 'date'),), 'pagination' => array('pageSize' => 10,)));

    $returnData = array();
    $returnData['dataProvider'] = $dataProvider;
    $returnData['arrayFilter'] = $arrayFilter;
    return $returnData;
  }

  /**
   * deletes the product with the given id
   * @param int $id id of the product
   * @return boolean true/false on success/failure of delete operation
   */
  public function actionDelete($id = NULL) {
    try {
      if (Yii::app()->request->isAjaxRequest) {
        if ($id != NULL) {
          $productModel = Products::model()->findByPk($id);
          if ($productModel->delete()) {
            return true;
          }
        }
      }
    }
    catch (Exception $e) {
      throw new CHttpException('500', $e->getMessage());
    }
  }

  /**
   * Loads the update product form
   * @param int $id id of the product to be updated
   * @throws CHttpException
   */
  public function actionUpdate($id = NULL) {
    try {
      if (Yii::app()->request->isAjaxRequest) {
        if (Yii::app()->request->isPostRequest) {
          if ($id != NULL) {
            $productModel = Products::model()->findByPk($id);
            $storesModel = Stores::model()->findAll();
            $stores = array();
            foreach ($storesModel as $store) {
              $stores[$store->store_name] = $store->store_name;
            }
            if(isset($_POST['list']) && $_POST['list']=="yes"){
              $view = $this->renderPartial('partial_views/edit_product_list_form', array('productModel' => $productModel, 'location'     => $stores, 'id'           => $id), true);
            }else{
              $view = $this->renderPartial('edit_product_form', array('productModel' => $productModel, 'location'     => $stores, 'id'           => $id), true);
            }
            echo CJSON::encode(array('view' => $view));
            Yii::app()->end();
          }
        }
      }
    }
    catch (Exception $e) {
      throw new CHttpException('500', $e->getMessage());
    }
  }

  public function actionUpdateProduct() {
    try {
      if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $productModel = Products::model()->findByPk($id);
        if (Yii::app()->request->isPostRequest) {
          if (isset($_POST['Products'])) {
            $productModel->attributes = Yii::app()->request->getPost('Products');
            if ($productModel->validate()) {
              if ($productModel->update()) {
                if(isset($_GET['list']) && $_GET['list']=="yes"){
                  $this->redirect(Yii::app()->createAbsoluteUrl('/site/listproducts'));
                }else{
                  $this->redirect(Yii::app()->createAbsoluteUrl('/site/index', array('edit' => 'yes')));
                }

              }
            }
          }
        }
      }
    }
    catch (Exception $e) {
      throw new CHttpException('500', $e->getMessage());
    }
  }

  public function actionGetProductType() {
    if (Yii::app()->request->isAjaxRequest) {
      if (Yii::app()->request->isPostRequest) {
        $query = $_POST['query'];
        $result = ProductType::model()->getProductType($query);
        echo CJSON::encode($result);
        Yii::app()->end();
      }
    }
  }

  public function actionAddProductTypeForm() {
    try {
      if (Yii::app()->request->isAjaxRequest) {
        $productTypeModel = new ProductType();
        $view = $this->renderPartial('partial_views/add_product_type_form', array('productTypeModel' => $productTypeModel), true);
        $response['statuscode'] = 200;
        $response['message'] = 'success';
        $response['view'] = $view;
      }
      else {
        $response['statuscode'] = 404;
        $response['message'] = 'Permission Denied';
      }
      echo CJSON::encode($response);
      Yii::app()->end();
    }
    catch (Exception $e) {
      $response['statuscode'] = 500;
      $response['message'] = $e->getMessage();
      echo CJSON::encode($response);
      Yii::app()->end();
    }
  }
  public function actionAddProductType() {
    try {
      if (Yii::app()->request->isAjaxRequest) {
        if (Yii::app()->request->isPostRequest) {
          $productType = new ProductType();
          $productType->Attributes = Yii::app()->request->getPost('ProductType');
          if ($productType->save()) {
            $response['statusCode'] = 200;
            $response['message'] = 'Success';
          }
          else {
            $response['statusCode'] = 500;
            $response['message'] = $productType->errors;
          }
        }
        else {
          $response['statusCode'] = 404;
          $response['message'] = 'Permission Denied';
        }
      }
      else {
        $response['statuscode'] = 404;
        $response['message'] = 'Permission Denied';
      }
      echo CJSON::encode($response);
      Yii::app()->end();
    }
    catch (Exception $e) {
      $response['statuscode'] = 500;
      $response['message'] = $e->getMessage();
      echo CJSON::encode($response);
      Yii::app()->end();
    }
  }

    public function actionAddStoreForm() {
    try {
      if (Yii::app()->request->isAjaxRequest) {
        $storeModel = new Stores();
        $view = $this->renderPartial('partial_views/add_stores_form', array('storeModel' => $storeModel), true);
        $response['statuscode'] = 200;
        $response['message'] = 'success';
        $response['view'] = $view;
      }
      else {
        $response['statuscode'] = 404;
        $response['message'] = 'Permission Denied';
      }
      echo CJSON::encode($response);
      Yii::app()->end();
    }
    catch (Exception $e) {
      $response['statuscode'] = 500;
      $response['message'] = $e->getMessage();
      echo CJSON::encode($response);
      Yii::app()->end();
    }
  }


  public function actionAddStore() {
    try {
      if (Yii::app()->request->isAjaxRequest) {
        if (Yii::app()->request->isPostRequest) {
          $storesModel = new Stores();
          $storesModel->Attributes = Yii::app()->request->getPost('Stores');
          if ($storesModel->save()) {
            $response['statusCode'] = 200;
            $response['message'] = 'Success';
          }
          else {
            $response['statusCode'] = 500;
            $response['message'] = $storesModel->errors;
          }
        }
        else {
          $response['statusCode'] = 404;
          $response['message'] = 'Permission Denied';
        }
      }
      else {
        $response['statuscode'] = 404;
        $response['message'] = 'Permission Denied';
      }
      echo CJSON::encode($response);
      Yii::app()->end();
    }
    catch (Exception $e) {
      $response['statuscode'] = 500;
      $response['message'] = $e->getMessage();
      echo CJSON::encode($response);
      Yii::app()->end();
    }
  }

  /**
   * This is the action to handle external exceptions.
   */
  public function actionError() {
    if ($error = Yii::app()->errorHandler->error) {
      if (Yii::app()->request->isAjaxRequest) echo $error['message'];
      else $this->render('error', $error);
    }
  }

  /**
   * Displays the contact page
   */
  public function actionContact() {
    $model = new ContactForm;
    if (isset($_POST['ContactForm'])) {
      $model->attributes = $_POST['ContactForm'];
      if ($model->validate()) {
        $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
        $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
        $headers = "From: $name <{$model->email}>\r\n" .
                "Reply-To: {$model->email}\r\n" .
                "MIME-Version: 1.0\r\n" .
                "Content-type: text/plain; charset=UTF-8";

        mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
        Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
        $this->refresh();
      }
    }
    $this->render('contact', array('model' => $model));
  }

  /**
   * Displays the login page
   */
  public function actionLogin() {
    $model = new LoginForm;

    // if it is ajax validation request
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }

    // collect user input data
    if (isset($_POST['LoginForm'])) {
      $model->attributes = $_POST['LoginForm'];
      // validate user input and redirect to the previous page if valid
      if ($model->validate() && $model->login()) $this->redirect(Yii::app()->user->returnUrl);
    }
    // display the login form
    $this->render('login', array('model' => $model));
  }

  /**
   * Logs out the current user and redirect to homepage.
   */
  public function actionLogout() {
    Yii::app()->user->logout();
    $this->redirect(Yii::app()->homeUrl);
  }

}