<?php
/**
 * This is the template for generating a controller class file for CRUD feature.
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
  /**
   * @var string the default layout for the views. Defaults to '//layouts/main', meaning
   * using main layout. See 'protected/views/layouts/main.php'.
   */
  public $layout='//layouts/main';
  public $defaultAction='admin';

  /**
   * Filters.
   * @return array action filters
   */
  public function filters(){
    return array(
      'accessControl', // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules(){
    return array(
      array('allow', // allow authenticated users to perform 'update' and 'admin' actions
        'actions' => array('update', 'admin'),
        'users' => array('Users'),
      ),
      array('allow', // allow users with admin privileges to perform all CRUD actions
        'actions' => array('view', 'create', 'update', 'admin', 'delete'),
        'roles' => array('Admins', "Root"),
      ),
      array('deny', // deny all users
        'users' => array('*'),
      ),
    );
  }
  
  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate()
  {
    $req<?php echo $this->modelClass; ?> = Yii::app()->request->getPost('<?php echo $this->modelClass; ?>',null);
    $model = new <?php echo $this->modelClass; ?>;
    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(is_array($req<?php echo $this->modelClass; ?>)){
      $model->attributes = $req<?php echo $this->modelClass; ?>;
      if ($model->save()){
        $this->redirect(array('view','id' => $model-><?php echo $this->tableSchema->primaryKey; ?>));
      }
    }

    $this->render('create',array(
      'model' => $model,
    ));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id){
    $req<?php echo $this->modelClass; ?> = Yii::app()->request->getPost('<?php echo $this->modelClass; ?>',null);
    $model=$this->loadModel($id);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(is_array($req<?php echo $this->modelClass; ?>)){
      $model->attributes = $req<?php echo $this->modelClass; ?>;
      if($model->save()){
        $this->redirect(array('view','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>));
      }
    }

    $this->render('update',array(
      'model'=>$model,
    ));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id){
    $this->loadModel($id)->delete();

    // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
    if(!isset($_GET['ajax'])){
      $this->redirect( isset($_POST['returnUrl']) ? 
        $_POST['returnUrl'] : array('admin') );
    }
  }

  /**
   * Manages all models.
   */
  public function actionAdmin(){
    $req<?php echo $this->modelClass; ?> = Yii::app()->request->getParam('<?php echo $this->modelClass; ?>');
    $model = new <?php echo $this->modelClass; ?>('search');
    $model->unsetAttributes();  // clear any default values
    if(is_array($req<?php echo $this->modelClass; ?>)){
      $model->attributes = $req<?php echo $this->modelClass; ?>;
    }
    $this->render('admin',array(
      'model'=>$model,
    ));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer the ID of the model to be loaded
   */
  public function loadModel($id){
    $model = <?php echo $this->modelClass; ?>::model()->findByPk($id);
    if ($model === null){
      throw new CHttpException(404,'Помилка. Екземпляр класу <?php echo $this->modelClass; ?> з ID '.$id.' не інсує.');
    }
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param CModel the model to be validated
   */
  protected function performAjaxValidation($model){
    $reqAjax = Yii::app()->request->getPost('ajax',null);
    if($reqAjax === '<?php echo $this->class2id($this->modelClass); ?>-form'){
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

}
