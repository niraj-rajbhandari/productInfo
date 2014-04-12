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
          $dbTransaction = Yii::app()->db->beginTransaction();
          $productModel->attributes = Yii::app()->request->getPost('Products');
          $productModel->date = date('Y-m-d');
          if ($productModel->validate()) {
            if ($productModel->save()) {
              $inventoryModel = Inventory::model()->findByAttributes(array('code' => $productModel->code));
              if (!empty($inventoryModel)) {
                $inventoryModel->quantity = $inventoryModel->quantity - 1;
                if ($inventoryModel->save()) {
                  $dbTransaction->commit();
                  Yii::app()->user->setFlash('success', 'The sales info has been recorded.');
                }
                else {
                  $dbTransaction->rollback();
                  Yii::app()->user->setFlash('error', 'The product is not available in the inventory');
                }
              }
              else {
                Yii::app()->user->setFlash('success', 'The sales info has been recorded! P.S : The code used is not in the inventory');
                $dbTransaction->commit();
              }
            }
            else {
              $dbTransaction->rollback();
              Yii::app()->user->setFlash('error', 'The product information could not be saved. Please try again.');
            }
          }
          else {
            Yii::app()->user->setFlash('error', 'Please fill the form properly');
          }
        }
        $this->redirect(Yii::app()->createAbsoluteUrl(''));
      }

      if (Yii::app()->request->getQuery('Products')) {
        $productModel->attributes = Yii::app()->request->getQuery('Products');
      }
      $productCriteria = new CDbCriteria();
      $condition = "date='" . date('Y-m-d') . "'";
      $productCriteria->addCondition($condition);
      $productsInfo = Products::model()->findAll($productCriteria);

      $dataProviderContainer = $this->_getListArrayProvider($productsInfo);

      $this->render('index', array('productModel' => $productModel, 'stores'       => $stores, 'dataProvider' => $dataProviderContainer['dataProvider'], 'arrayFilter'  => $dataProviderContainer['arrayFilter']));
    }
    catch (Exception $e) {
      throw new CHttpException('500', $e->getMessage());
    }
  }

  public function actionlistProducts() {
    try {
      $productsModel = Products::model()->findAll();
      $dataProviderContainer = $this->_getListArrayProvider($productsModel);
      $this->render('product_list', array('dataProvider' => $dataProviderContainer['dataProvider'], 'arrayFilter'  => $dataProviderContainer['arrayFilter']));
    }
    catch (Exception $e) {
      Throw new CHttpException('500', $e->getMessage());
    }
  }

  //gets array provider for grid view
  private function _getListArrayProvider($productsInfo) {
    $products = array();
    $isInventory = false;
    if (!empty($productsInfo)) {
      foreach ($productsInfo as $product) {
        $products[] = $product->attributes;
        if (isset($product->quantity)) {
          $isInventory = true;
        }
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

    $sortingArray = array();
    if ($isInventory) {
      $sortingArray = array('code', 'product_type', 'location', 'cost_price', 'selling_price', 'marked_price', 'gross_profit', 'date', 'quantity');
    }
    else {
      $sortingArray = array('code', 'product_type', 'location', 'cost_price', 'selling_price', 'marked_price', 'gross_profit', 'date');
    }
//    echo "<pre>".$isInventory;print_r($productsInfo);print_r($sortingArray);die('testing');
    $dataProvider = new CArrayDataProvider($arrayProvider, array('id'         => 'id', 'sort'       => array('attributes' => $sortingArray,), 'pagination' => array('pageSize' => 20,)));

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
  public function actionDeleteInventory($id = NULL) {
    try {
      if (Yii::app()->request->isAjaxRequest) {
        if ($id != NULL) {
          $inventoryModel = Inventory::model()->findByPk($id);
          if ($inventoryModel->delete()) {
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
            if (isset($_POST['list']) && $_POST['list'] == "yes") {
              $view = $this->renderPartial('partial_views/edit_product_list_form', array('productModel' => $productModel, 'location'     => $stores, 'id'           => $id), true);
            }
            else {
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
                if (isset($_GET['list']) && $_GET['list'] == "yes") {
                  $this->redirect(Yii::app()->createAbsoluteUrl('/site/listproducts'));
                }
                else {
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

  public function actionListProductType() {
    try {
      $productTypeModel = ProductType::model()->findAll();
      $productTypes = array();
      if (!empty($productTypeModel)) {
        foreach ($productTypeModel as $productType) {
          $productTypes[] = $productType->attributes;
        }
      }

      $arrayFilter = new ArrayFilterClass;

      if (isset($_GET['ArrayFilterClass'])) {
        $arrayFilter->filters = $_GET['ArrayFilterClass'];
      }

      $arrayProvider = $arrayFilter->filter($productTypes);
      //re-indexing array keys
//            $arrayProvider = array_values($arrayProvider);

      $dataProvider = new CArrayDataProvider($arrayProvider, array('id'         => 'id', 'sort'       => array('attributes' => array('id', 'product_type')), 'pagination' => array('pageSize' => 50,)));

      $returnData = array();
      $returnData['dataProvider'] = $dataProvider;
      $returnData['arrayFilter'] = $arrayFilter;
      $this->render('product_type_list', array('dataProvider' => $returnData['dataProvider'], 'arrayFilter'  => $returnData['arrayFilter']));
    }
    catch (Exception $e) {
      Throw new CHttpException('500', $e->getMessage());
    }
  }

  /**
   * deletes the store with the given id
   * @param int $id id of the store
   * @return boolean true/false on success/failure of delete operation
   */
  public function actionDeleteProductType($id = NULL) {
    try {
      if (Yii::app()->request->isAjaxRequest) {
        if ($id != NULL) {
          $productTypeModel = ProductType::model()->findByPk($id);
          if ($productTypeModel->delete()) {
            return true;
          }
        }
      }
    }
    catch (Exception $e) {
      throw new CHttpException('500', $e->getMessage());
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

  public function actionListStores() {
    try {
      $storesModel = Stores::model()->findAll();
      $stores = array();
      if (!empty($storesModel)) {
        $i = 0;
        foreach ($storesModel as $store) {
          $stores[$i] = $store->attributes;
          $stores[$i]['id'] = $store->store_id;
          $i++;
        }
      }

      $arrayFilter = new ArrayFilterClass;

      if (isset($_GET['ArrayFilterClass'])) {
        $arrayFilter->filters = $_GET['ArrayFilterClass'];
      }

      $arrayProvider = $arrayFilter->filter($stores);
      //re-indexing array keys
//            $arrayProvider = array_values($arrayProvider);

      $dataProvider = new CArrayDataProvider($arrayProvider, array('id'         => 'id', 'sort'       => array('attributes' => array('id', 'store_name')), 'pagination' => array('pageSize' => 10,)));

      $returnData = array();
      $returnData['dataProvider'] = $dataProvider;
      $returnData['arrayFilter'] = $arrayFilter;
      $this->render('stores_list', array('dataProvider' => $returnData['dataProvider'], 'arrayFilter'  => $returnData['arrayFilter']));
    }
    catch (Exception $e) {
      Throw new CHttpException('500', $e->getMessage());
    }
  }

  /**
   * deletes the store with the given id
   * @param int $id id of the store
   * @return boolean true/false on success/failure of delete operation
   */
  public function actionDeleteStore($id = NULL) {
    try {
      if (Yii::app()->request->isAjaxRequest) {
        if ($id != NULL) {
          $storesModel = Stores::model()->findByPk($id);
          if ($storesModel->delete()) {
            return true;
          }
        }
      }
    }
    catch (Exception $e) {
      throw new CHttpException('500', $e->getMessage());
    }
  }

  public function actionAddInventory() {
    try {
      $inventoryModel = new Inventory();
      $inventoryModel->date = date('m-d-Y');
      $storesModel = Stores::model()->findAll();
      $stores = array();
      foreach ($storesModel as $store) {
        $stores[$store->store_name] = $store->store_name;
      }
      if (Yii::app()->request->isPostRequest) {
        if (isset($_POST['Inventory'])) {
          $inventoryModel->attributes = Yii::app()->request->getPost('Inventory');
          $inventoryModel->date = date('Y-m-d');
          if ($inventoryModel->validate()) {
            if ($inventoryModel->save()) {
              $this->redirect(Yii::app()->createAbsoluteUrl('/site/addinventory'));
            }
            else {
              throw new CHttpException('500', 'The inventory information could not be saved. Please try again.');
            }
          }
          else {
            throw new CHttpException('500', ' Please fill the form properly.');
          }
        }
      }

      if (Yii::app()->request->getQuery('Inventory')) {
        $inventoryModel->attributes = Yii::app()->request->getQuery('Inventory');
      }
      $inventoryCriteria = new CDbCriteria();
      $condition = "date='" . date('Y-m-d') . "'";
      $inventoryCriteria->addCondition($condition);
      $inventoryInfo = Inventory::model()->findAll($inventoryCriteria);

      $dataProviderContainer = $this->_getListArrayProvider($inventoryInfo);

      $this->render('add_inventory', array('inventoryModel' => $inventoryModel, 'stores'         => $stores, 'dataProvider'   => $dataProviderContainer['dataProvider'], 'arrayFilter'    => $dataProviderContainer['arrayFilter']));
    }
    catch (Exception $e) {
      throw new CHttpException('500', $e->getMessage());
    }
  }

  public function actionInventoryList() {
    try {
      $inventoryModel = Inventory::model()->findAll();
      $dataProviderContainer = $this->_getListArrayProvider($inventoryModel);
      $this->render('inventory_list', array('dataProvider' => $dataProviderContainer['dataProvider'], 'arrayFilter'  => $dataProviderContainer['arrayFilter']));
    }
    catch (Exception $e) {
      Throw new CHttpException('500', $e->getMessage());
    }
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
  public function actionUpdateInventoryForm($id = NULL) {
    try {
      if (Yii::app()->request->isAjaxRequest) {
        if (Yii::app()->request->isPostRequest) {
          if ($id != NULL) {
            $inventoryModel = Inventory::model()->findByPk($id);
            $storesModel = Stores::model()->findAll();
            $stores = array();
            foreach ($storesModel as $store) {
              $stores[$store->store_name] = $store->store_name;
            }
            if (isset($_POST['list']) && $_POST['list'] == "yes") {
              $view = $this->renderPartial('partial_views/edit_inventory_list_form', array('inventoryModel' => $inventoryModel, 'location'       => $stores, 'id'             => $id), true);
            }
            else {
              $view = $this->renderPartial('edit_inventory_form', array('inventoryModel' => $inventoryModel, 'location'       => $stores, 'id'             => $id), true);
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

  public function actionUpdateInventory() {
    try {
      if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $inventoryModel = Inventory::model()->findByPk($id);
        if (Yii::app()->request->isPostRequest) {
          if (isset($_POST['Inventory'])) {
            $inventoryModel->attributes = Yii::app()->request->getPost('Inventory');
            if ($inventoryModel->validate()) {
              if ($inventoryModel->update()) {
                if (isset($_GET['list']) && $_GET['list'] == "yes") {
                  $this->redirect(Yii::app()->createAbsoluteUrl('/site/inventorylist'));
                }
                else {
                  $this->redirect(Yii::app()->createAbsoluteUrl('/site/addinventory', array('edit' => 'yes')));
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

  /**
   *  checks the availability of the code in the inventory
   * */
  public function actionCheckAvailability() {
    $response = array();
    if (Yii::app()->request->isAjaxRequest) {
      $code = Yii::app()->request->getParam('code');
      $inventoryModel = Inventory::model()->findByAttributes(array('code' => $code));
      if (!empty($inventoryModel)) {
//        if (intval($inventoryModel->quantity) == 0) {
//        $response['status'] = 'success';
//        $response['available'] = 'no';
//        $response['msg'] = 'This product is out of stock';
//      }
//      else {
//        $response['status'] = 'success';
//        $response['available'] = 'yes';
//        $response['msg'] = 'This product is available';
//      }
        $response['status'] = 'success';
        $response['available'] = 'yes';
      }
      else {
        $response['status'] = 'success';
        $response['available'] = 'not in inventory';
        $response['msg'] = 'This code is not available in the inventory';
      }
    }
    else {
      $response['status'] = 'error';
      $response['msg'] = 'Permission Denied!!!';
    }
    echo CJSON::encode($response);
    Yii::app()->end();
  }

  // ****************auto generated actions *******************************//
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