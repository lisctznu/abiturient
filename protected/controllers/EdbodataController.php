<?php

class EdbodataController extends Controller
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
      array('allow', // allow users with admin privileges to perform all CRUD actions
        'actions' => array('view', 'create', 'update', 'admin', 'delete', 'datauploader', 'upload', 'deletecsv'),
        'users' => array('@'),
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
    $reqEdboData = Yii::app()->request->getPost('EdboData',null);
    $model = new EdboData;
    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(is_array($reqEdboData)){
      $model->attributes = $reqEdboData;
      if ($model->save()){
        $this->redirect(array('view','id' => $model->ID));
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
    $reqEdboData = Yii::app()->request->getPost('EdboData',null);
    $model=$this->loadModel($id);

    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if(is_array($reqEdboData)){
      $model->attributes = $reqEdboData;
      if($model->save()){
        $this->redirect(array('view','id'=>$model->ID));
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
    $reqEdboData = Yii::app()->request->getParam('EdboData');
    $model = new EdboData('search');
    $model->unsetAttributes();  // clear any default values
    if(is_array($reqEdboData)){
      $model->attributes = $reqEdboData;
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
    $model = EdboData::model()->findByPk($id);
    if ($model === null){
      throw new CHttpException(404,'Помилка. Екземпляр класу EdboData з ID '.$id.' не інсує.');
    }
    return $model;
  }

  /**
   * Performs the AJAX validation.
   * @param CModel the model to be validated
   */
  protected function performAjaxValidation($model){
    $reqAjax = Yii::app()->request->getPost('ajax',null);
    if($reqAjax === 'edbo-data-form'){
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }
  
  /**
   * Метод завантаження даних ЄДЕБО із CSV-файлу
   */
  public function actionDatauploader(){
      $SQL="SHOW FULL COLUMNS FROM edbo_data";
      $connection = Yii::app()->db; 
      $command = $connection->createCommand($SQL);
      $rowCount = $command->execute(); // execute the non-query SQL
      $data_items = $command->queryAll(); // execute a query SQL
      $this->render('/edbodata/datauploader',array(
         'data_items' => $data_items,
         'model' => new Edbodata(),
         'rowCount' => $rowCount,
      ));
  }
  
  public function actionUpload() {
    header('Vary: Accept');
    if (isset($_SERVER['HTTP_ACCEPT']) &&
            (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
      header('Content-type: application/json');
    } else {
      header('Content-type: text/plain');
    }
    $this->layout = '//layouts/clear';
    $data = array();

    $model = new EdboData();
    $model->csv_file = CUploadedFile::getInstance($model, 'csv_file');
    if ($model->csv_file !== null && $model->validate(array('csv_file'))) {
      $md5_name = md5_file($model->csv_file->getTempName());
      $ext = $model->csv_file->extensionName;
      $new_filename = Yii::app()->getBasePath().'/data/'.$md5_name.'.'.$ext;
      if ($model->csv_file->saveAs($new_filename) !== true){
        $data[] = array('error', $new_filename. ' не зберігся...');
        echo json_encode($data);
        return ;
      }
      $file = $new_filename;
      
      $hfile = fopen($file,"r");
      if (!$hfile){
        $data[] = array('error', $file . ' не відкривається.');
        echo json_encode($data);
        return ;
      }
      
      $fsize = filesize($file);

      $csvcontent = fread($hfile,$fsize);
      if (!$csvcontent){
        $data[] = array('error', $file . ' не читається.');
        echo json_encode($data);
        exit() ;
      }
      
      fclose($hfile);
      
      $inserted = 0;
      $updated = 0;
      $SQL="SHOW FULL COLUMNS FROM edbo_data";
      $connection = Yii::app()->db; 
      $command = $connection->createCommand($SQL);
      $rowCount = $command->execute(); // execute the non-query SQL
      $row_header = $command->queryAll(); // execute a query SQL
      
      $field_count = $rowCount;
      $fieldseparator = ";";
      $lineseparator = "\n";
      
      $arr_lines = explode($lineseparator,$csvcontent);
      foreach($arr_lines as $line) {
        $edbo_model = new EdboData();
        $line_utf8 = iconv('windows-1251','utf-8',$line);
        $line_strs = explode('"',$line_utf8);
        for($k = 0; $k < count($line_strs); $k++) {
          if ($k % 2){
            $line_strs[$k] = str_replace($fieldseparator,"__SEPARATOR__",$line_strs[$k]);
          }
        }
        $new_line = str_replace("\r","",trim(implode('"',$line_strs)," "));
        //phones
        $float_replaced_line = preg_replace("/([1-9][0-9]*?),([0-9]+?)/","$1.$2",$new_line);
        $escaped_line = str_replace("'","\'",$float_replaced_line);
        $row_data = explode($fieldseparator,$escaped_line);
        $current_field_count = count($row_data);
        if ($current_field_count != $field_count) {
          $data[] = array('error', $current_field_count .'!=' . $field_count);
          echo json_encode($data);
          exit() ;
        }
        $edbo_attributes = array();
        for ($k = 0; $k < $current_field_count; $k++){
          $data_item = str_replace("__SEPARATOR__",$fieldseparator,$row_data[$k]);
          if (!isset($row_header[$k]['Field'])){
            $data[] = array('error', $k .' - не індекс назви поля таблиці БД');
            echo json_encode($data);
            exit() ;
          }
          $edbo_attributes[$row_header[$k]['Field']] = $data_item;
        }

        $edbo_old_model = EdboData::model()->findByPk($row_data[0]);
        $is_new = false;
        $is_update = false;
        if (!$edbo_old_model){
          $is_new = true;
        }
        for ($k = 0; ($k < $current_field_count && $edbo_old_model); $k++){
          $old_param = $edbo_old_model->getAttribute($row_header[$k]['Field']);
          $new_param = $edbo_attributes[$row_header[$k]['Field']];
          if ($old_param != $new_param){
            $is_update = true;
            break;
          }
        }
        
        if ($is_new){
          $inserted++;
          $edbo_model->attributes = $edbo_attributes;
          $edbo_model->save();
          continue;
        }
        if ($is_update){
          $updated++;
          $edbo_old_model->attributes = $edbo_attributes;
          $edbo_old_model->save();
        }
      }
      
      $uploaded = "Втавлено : ". $inserted . " , оновлено : " . $updated;

        $data[] = array(
            'name' => $model->csv_file->name,
            'type' => $model->csv_file->type,
            'size' => $model->csv_file->size,
            'uploaded' => $uploaded,
            // we need to return the place where our image has been saved
            //'url' => $model->getImageUrl(), // Should we add a helper method?
            // we need to provide a thumbnail url to display on the list
            // after upload. Again, the helper method now getting thumbnail.
            //'thumbnail_url' => $model->getImageUrl(MyModel::IMG_THUMBNAIL),
            // we need to include the action that is going to delete the avatar
            // if we want to after loading 
            'delete_url' => $this->createUrl('/edbodata/deletecsv',array('path' => $file)),
            'delete_type' => 'POST');
    } else {
      if ($model->hasErrors('csv_file')) {
        $data[] = array('error', $model->getErrors('csv_file'));
      } else {
        throw new CHttpException(500, "Could not upload file " . CHtml::errorSummary($model));
      }
    }
    // JQuery File Upload expects JSON data
    echo json_encode($data);
  }
  
  public function actionDeletecsv(){
    if (Yii::app()->request->isPostRequest){
      $path = Yii::app()->request->getParam('path',null);
      if ($path){
        unlink($path);
      } else {
        $data[] = array('error', 'No path');
        echo json_encode($data);
      }
    }
  }

}
