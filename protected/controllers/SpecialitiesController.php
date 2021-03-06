<?php

class SpecialitiesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        public $defaultAction='admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
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
	public function accessRules()
	{
		return array(
			/*array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
                        array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','view','admin','create'),
				'roles'=>array('Admins'),
			),*/
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','view','admin','delete','create','update', "ajaxcreate","ajaxupdate"),
				'roles'=>array("Root","Admin"),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('autocomplete'),
				'users'=>array("*"),
			),

			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Specialities;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Specialities']))
		{
			$model->attributes=$_POST['Specialities'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->idSpeciality));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Specialities']))
		{
			$model->attributes=$_POST['Specialities'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->idSpeciality));
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
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Specialities');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Specialities('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Specialities']))
			$model->attributes=$_GET['Specialities'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Specialities::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='specialities-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
  
  public function actionAutocomplete(){
    if ( Yii::app()->request->isAjaxRequest ) {
      $reqTerm = Yii::app()->request->getParam('term',null);
      if (!$reqTerm){
        return;
      }
      $criteria = new CDbCriteria();
      $criteria->with = array('eduform');
      $criteria->together = true;
      $criteria->select = array(
         'idSpeciality',
          new CDbExpression("concat_ws(' ',"
                  . "SpecialityClasifierCode,"
                  . "(case substr(SpecialityClasifierCode,1,1) when '6' then "
                  . "SpecialityDirectionName else SpecialityName end),"
                  . "(case SpecialitySpecializationName when '' then '' "
                  . " else concat('(',SpecialitySpecializationName,')') end)"
                  . ",',',concat('форма: ',eduform.PersonEducationFormName)) AS tSPEC"
          ),
      );
      $terms = explode(' ',$reqTerm);
      foreach ($terms as $term){
        $criteria->compare("concat_ws(' ',"
                    . "SpecialityClasifierCode,"
                    . "(case substr(SpecialityClasifierCode,1,1) when '6' then "
                    . "SpecialityDirectionName else SpecialityName end),"
                    . "(case SpecialitySpecializationName when '' then '' "
                    . " else concat('(',SpecialitySpecializationName,')') end)"
                    . ",',',concat('форма: ',eduform.PersonEducationFormName))",$term,true);
      }
      $criteria->order = 'tSPEC ASC';
      $_data = CHtml::ListData(Specialities::model()->findAll($criteria),'idSpeciality','tSPEC');
      $data = array();
      $c = 0;
      foreach ($_data as $id => $val){
        $data[$c]['label'] = $val;
        $data[$c]['value'] = $val;
        $data[$c]['spec_id'] = $id;
        
        $c++;
      }
      $data['count'] = count($data);
      //var_dump($data);
      echo CJSON::encode( $data );
        
    }
  }
}
