<?php

class SiteController extends Controller
{

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        try {
            $productModel = new Products();
            $productModel->date = date('m-d-Y');
            $location = array('Durbar Marg DS' => 'Durbar Marg DS', 'Durbar Marg NC' => 'Durbar Marg NC', 'Kumaripati NC' => 'Kumaripati NC', 'People\'s Plaza NC' => 'People\'s Plaza NC','CTC NC'=>'CTC NC','Pokhara NC'=>'Pokhara NC','Pokhara DS'=>'Pokhara DS');
            if (Yii::app()->request->isPostRequest) {
                if (isset($_POST['Products'])) {
                    $productModel->attributes = Yii::app()->request->getPost('Products');
                    $productModel->date = date('Y-m-d');
                    if ($productModel->validate()) {
                        if ($productModel->save()) {
                            $this->redirect(Yii::app()->createAbsoluteUrl(''));
                        } else {
                            throw new CHttpException('500', 'The product information could not be saved. Please try again.');
                        }
                    } else {
                        throw new CHttpException('500', ' Please fill the form properly.');
                    }
                }
            }

            if (Yii::app()->request->getQuery('Products')) {
                $productModel->attributes = Yii::app()->request->getQuery('Products');
            }
            $productsInfo = Products::model()->findAll();
            $products = array();
            if (!empty($productsInfo)) {
                foreach ($productsInfo as $product)
                {
                    $products[] = $product->attributes;
                }
            }
            array_walk($products, function(&$v, $k) {
                        $v['date'] = date('m-d-Y', strtotime($v['date']));
                    });
            $arrayFilter = new ArrayFilterClass;

            if (isset($_GET['ArrayFilterClass'])) {
                $arrayFilter->filters = $_GET['ArrayFilterClass'];
            }

            $arrayProvider = $arrayFilter->filter($products);
            //re-indexing array keys
//            $arrayProvider = array_values($arrayProvider);

            $dataProvider = new CArrayDataProvider($arrayProvider, array('id' => 'id', 'sort' => array('attributes' => array('code', 'product_type', 'location', 'cost_price', 'selling_price', 'marked_price', 'net_profit', 'date'),), 'pagination' => array('pageSize' => 10,)));
            $this->render('index', array('productModel' => $productModel, 'location' => $location, 'dataProvider' => $dataProvider, 'arrayFilter' => $arrayFilter));
        } catch (Exception $e) {
            throw new CHttpException('500', $e->getMessage());
        }
    }

    /**
     * deletes the product with the given id
     * @param int $id id of the product
     * @return boolean true/false on success/failure of delete operation
     */
    public function actionDelete($id = NULL)
    {
        if ($id != NULL) {
            $productModel = Products::model()->findByPk($id);
            if ($productModel->delete()) {
                return true;
            }
        }
    }

    public function actionUpdate($id = NULL)
    {
        try {
            if (Yii::app()->request->isAjaxRequest) {
                if (Yii::app()->request->isPostRequest) {
                    if ($id != NULL) {
                        $productModel = Products::model()->findByPk($id);
                        $location = array('Kathmandu' => 'Kathmandu', 'Bhaktapur' => 'Bhaktapur', 'Lalitpur' => 'Lalitpur', 'Pokhara' => 'Pokhara');
                        $productType = array('Sweater' => 'Sweater', 'Pants' => 'Pants', 'Shirt' => 'Shirt', 'Tshirt' => 'Tshirt');

                        $view = $this->renderPartial('edit_product_form', array('productModel' => $productModel, 'location' => $location, 'id' => $id), true);
                        echo CJSON::encode(array('view' => $view));
                        Yii::app()->end();
                    }
                }
            }
        } catch (Exception $e) {
            throw new CHttpException('500', $e->getMessage());
        }
    }

    public function actionUpdateProduct()
    {
        try {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];
                $productModel = Products::model()->findByPk($id);
                if (Yii::app()->request->isPostRequest) {
                    if (isset($_POST['Products'])) {
                        $productModel->attributes = Yii::app()->request->getPost('Products');
                        if ($productModel->validate()) {
                            if ($productModel->update()) {
                                $this->redirect(Yii::app()->createAbsoluteUrl(''));
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            throw new CHttpException('500',$e->getMessage());
        }
    }
    
    public function actionGetProductType(){
        if(Yii::app()->request->isAjaxRequest){
            if(Yii::app()->request->isPostRequest){
                $query=$_POST['query'];
                $result=  ProductType::model()->getProductType($query);
                echo CJSON::encode($result);
                Yii::app()->end();
            }
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
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
    public function actionLogin()
    {
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
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}