<?php

class PersonspecialityController extends Controller {

  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  public $layout = '//layouts/column2';
  public $defaultAction = 'admin';

  /**
   * @return array action filters
   */
  public function filters() {
//		return array(
//			//'accessControl', // perform access control for CRUD operations
//			//'postOnly', // we only allow deletion via POST request
//		);
    return array(
        'accessControl', // perform access control for CRUD operations
        'ajaxOnly + Refresh, Edboupdate, Studupdate',
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules() {
    return array(
        array('allow', // allow all users to perform 'index' and 'view' actions
            'actions' => array("rating", "excelrating"),
            'users' => array('*'),
        ),
        array('allow', // allow authenticated user to perform 'create' and 'update' actions
            'actions' => array('Znosubjects',
                'Speciality',
                'Specialitys',
                'View',
                'Create',
                'Update',
                "Delete",
                "Index",
                "Refresh",
                'admin', "Edboupdate",
                'Studupdate',
                "Create_electron",
                "edbodata"
            ),
            'users' => array('@'),
        ),
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('admin','delete'),
//				'users'=>array('admin'),
//			),
        array('deny', // deny all users
            'users' => array('*'),
        ),
    );
  }

  public function actionEdboupdate($id) {
    $model = Personspeciality::model()->findByPk($id);
    try {
      $link = Yii::app()->user->getEdboSearchUrl() . ":8080/PersonSearch/request.jsp";

      $client = new EHttpClient($link, array('maxredirects' => 30, 'timeout' => 30,));

      $client->setParameterPost(array("personIdMySql" => $model->PersonID, "personSpeciality" => $id));
      $response = $client->request(EHttpClient::POST);

      if ($response->isSuccessful()) {
        $obj = (object) CJSON::decode($response->getBody());
        if ($obj->error) {
          Yii::app()->user->setFlash("message", $obj->message);
        }
      } else {
        Yii::app()->user->setFlash("message", "Синхронізація не виконана! Спробуйте пізніше.");
      }
    } catch (Exception $e) {
      Yii::app()->user->setFlash("message", "Синхронізація не виконана! Спробуйте пізніше.");
    }

    echo CJSON::encode(array("result" => "success", "data" => ""));
  }

  public function actionZnosubjects($personid) {
    $model = new Personspeciality;
    if (isset($_POST['Personspeciality'])) {
      $model->attributes = $_POST['Personspeciality'];
      $model->PersonID = intval($personid);
    }
    $this->renderPartial("_subjects_holder", array('model' => $model, 'specialityid' => $model->SepcialityID));
  }

  public function actionSpeciality($idFacultet, $idEducationForm) {
//            $data = Specialities::model()->findAll('FacultetID=:FacultetID',
//                          array(':FacultetID'=>(int) $idFacultet));
//
//            $data=CHtml::listData($data,'idSpeciality','SpecialityName');
//            echo CHtml::tag('option', array('value'=>""), "", true);
    $data = Specialities::DropDownMask($idFacultet, $idEducationForm);
    echo CHtml::tag('option', array('value' => ""), "", true);
    foreach ($data as $value => $name) {
      echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
    }
  }

  /**
   * Displays a particular model.
   * @param integer $id the ID of the model to be displayed
   */
  public function actionView($id) {
    $this->render('view', array(
        'model' => $this->loadModel($id),
    ));
  }

  /*
   * @param $model Personspeciality 
   */

  protected function _setDefaults($model) {
    //$model = new Personspeciality();
    $user = User::model()->findByPk(Yii::app()->user->id);
    //debug(print_r($user->syspk, true));
    if (!empty($user->syspk)) {
      $pk = $user->syspk;
      //$pk=new SysPk();
      $model->CourseID = $pk->CourseID;
      $model->QualificationID = $pk->QualificationID;
      $model->isBudget = $pk->isBudget;
      $model->isContract = $pk->isContract;
      $model->EducationFormID = $pk->EducationFormID;
    }
  }

  /**
   * Creates a new model.
   * If creation is successful, the browser will be redirected to the 'view' page.
   */
  public function actionCreate($personid) {
    $model = new Personspeciality;
    $model->PersonID = (int) $personid;
    $this->_setDefaults($model);
    $valid = true;

    if (isset($_GET['Personspeciality'])) {
      $renderForm = "_form";
      //if (isset($_GET['Personspeciality']['GraduatedUniversitieID'])){
      if (!empty($_GET['Personspeciality']['QualificationID']) && $_GET['Personspeciality']['QualificationID'] > 1 && ($_GET['Personspeciality']['SepcialityID'] != 70686 && $_GET['Personspeciality']['SepcialityID'] != 90661)) {
        $model->scenario = "SHORTFORM";
        $renderForm = "_formShort";
        $model->CausalityID = 100;
      }
      $model->attributes = $_GET['Personspeciality'];

      if (intval($model->EntranceTypeID) == 1) {

        $model->Exam1ID = null;
        $model->Exam1Ball = null;
        $model->Exam2ID = null;
        $model->Exam2Ball = null;
        $model->Exam3ID = null;
        $model->Exam3Ball = null;
        $model->CausalityID = null;
      } elseif (intval($model->EntranceTypeID) == 2) {
        $model->DocumentSubject1 = null;
        $model->DocumentSubject2 = null;
        $model->DocumentSubject3 = null;
      }

      $valid = $model->validate() && $valid;
      if (!$valid) {
        //debug ($model->PersonID);
        echo CJSON::encode(array("result" => "error", "data" =>
            $this->renderPartial($renderForm, array('model' => $model), true)));
        Yii::app()->end();
      } else {
        if ($model->save())
        //debug ($model->PersonID);
          $person = Person::model()->findByPk($model->PersonID);
        echo CJSON::encode(array("result" => "success", "data" =>
            $this->renderPartial("//person/tabs/_spec", array('models' => $person->specs, 'personid' => $model->PersonID), true)
        ));
        Yii::app()->end();
      }
    }

    $this->renderPartial('_Modal', array('model' => $model, 'personid' => $model->PersonID));
  }

  public function actionCreate_electron($personid, $spec) {
    $model = new Personspeciality;
    $model->PersonID = (int) $personid;
    $this->_setDefaults($model);
    $valid = true;

    if (isset($_GET['Personspeciality'])) {
      $renderForm = "_form";
      //if (isset($_GET['Personspeciality']['GraduatedUniversitieID'])){

      $model->attributes = $_GET['Personspeciality'];

      if (intval($model->EntranceTypeID) == 1) {

        $model->Exam1ID = null;
        $model->Exam1Ball = null;
        $model->Exam2ID = null;
        $model->Exam2Ball = null;
        $model->Exam3ID = null;
        $model->Exam3Ball = null;
        $model->CausalityID = null;
      } elseif (intval($model->EntranceTypeID) == 2) {
        $model->DocumentSubject1 = null;
        $model->DocumentSubject2 = null;
        $model->DocumentSubject3 = null;
      }

      $valid = $model->validate() && $valid;
      if (!$valid) {
        //debug ($model->PersonID);
        echo CJSON::encode(array("result" => "error", "data" =>
            $this->renderPartial($renderForm, array('model' => $model), true)));
        Yii::app()->end();
      } else {
        if ($model->save())
        //debug ($model->PersonID);
          $person = Person::model()->findByPk($model->PersonID);
        echo CJSON::encode(array("result" => "success", "data" =>
            $this->renderPartial("//person/tabs/_spec", array('models' => $person->specs, 'personid' => $model->PersonID), true)
        ));
        Yii::app()->end();
      }
    }
    //$link = Yii::app()->user->getEdboSearchUrl().Yii::app()->params["documentSearchURL"];
    //debug($link);
    //print "<script type=\"text/javascript\">prompt('Введдіть ЄДБО Кодi!');</script>";
    //$client = new EHttpClient($link, array('maxredirects' => 30, 'timeout'=> 30,));
    //$client->setParameterPost($_GET);
    //$response = $client->request(EHttpClient::POST);
    $searchRes = array();
    $searchRes = $model->loadOnlineStatementFromJSON($spec);
    $user = Yii::app()->user->getUserModel();
    if ($user->syspk->SpecMask == "1") {
      $this->renderPartial('_Modal_electron', array('model' => $model, 'spec' => $spec));
    } else {
      $this->renderPartial('_Modal_electron_error', array('model' => $model));
    }
  }

  public function actionRefresh($id) {
    $this->renderPartial("//person/tabs/_spec", array('personid' => $id));
  }

  /**
   * Updates a particular model.
   * If update is successful, the browser will be redirected to the 'view' page.
   * @param integer $id the ID of the model to be updated
   */
  public function actionUpdate($id) {

    $model = $this->loadModel($id);

    $valid = true;
    // Uncomment the following line if AJAX validation is needed
    // $this->performAjaxValidation($model);

    if (isset($_GET['Personspeciality'])) {

      $renderForm = "_form";
      //if (isset($_GET['Personspeciality']['GraduatedUniversitieID'])){
      //debug($model->SepcialityID);
      if (!empty($_GET['Personspeciality']['QualificationID']) && $_GET['Personspeciality']['QualificationID'] > 1 && $model->SepcialityID != 70686 && $model->SepcialityID != 90661) {
        $model->scenario = "SHORTFORM";
        $renderForm = "_formShort";
        $model->CausalityID = 100;
      }
      $model->attributes = $_GET['Personspeciality'];

      if (intval($model->EntranceTypeID) == 1) {
        $model->Exam1ID = null;
        $model->Exam1Ball = null;
        $model->Exam2ID = null;
        $model->Exam2Ball = null;
        $model->Exam3ID = null;
        $model->Exam3Ball = null;
        $model->CausalityID = null;
      } elseif (intval($model->EntranceTypeID) == 2) {
        $model->DocumentSubject1 = null;
        $model->DocumentSubject2 = null;
        $model->DocumentSubject3 = null;
      }
      $valid = $model->validate() && $valid;
      try {
        if (!$valid) {
          echo CJSON::encode(array("result" => "error", "data" =>
              $this->renderPartial($renderForm, array('model' => $model), true)));
          Yii::app()->end();
        } else {
          if ($model->save())
            $person = Person::model()->findByPk($model->PersonID);
          echo CJSON::encode(array("result" => "success", "data" =>
              $this->renderPartial("//person/tabs/_spec", array('models' => $person->specs, 'personid' => $model->PersonID), true)
          ));
          Yii::app()->end();
        }
      } catch (Exception $e) {
        echo CJSON::encode(array("result" => "error", "data" => $e->getMessage()));
        Yii::app()->end();
      }
    }

    $this->renderPartial('_Modal', array('model' => $model, 'personid' => $model->PersonID));
  }

  /**
   * Deletes a particular model.
   * If deletion is successful, the browser will be redirected to the 'admin' page.
   * @param integer $id the ID of the model to be deleted
   */
  public function actionDelete($id) {
    try {
      $model = $this->loadModel($id);
      $personid = $model->PersonID;
      if (empty($model->edboID)) {
        if ($model->QualificationID > 1 && $model->SepcialityID != 70686 && $model->SepcialityID != 90661) {
          $model->scenario = "SHORTFORM";
          $model->CausalityID = 100;
        }
        $model->StatusID = 10;
        if (!$model->save()) {
          debug(print_r($model->getErrors(), true));
        }
      } else {
        Yii::app()->user->setFlash("message", "Заборонено видаляти заявку!");
      }
      $person = Person::model()->findByPk($personid);
      echo CJSON::encode(array("result" => "success", "data" => $this->renderPartial("//person/tabs/_spec", array('models' => $person->specs, 'personid' => $personid), true)));
    } catch (CHttpException $e) {
      echo CJSON::encode(array("result" => "error", "data" => $e->getMessage()));
    } catch (Exception $e) {
      echo CJSON::encode(array("result" => "error", "data" => "Дія заборонена!"));
    }
  }

  /**
   * Lists all models.
   */
  public function actionIndex() {
    $dataProvider = new CActiveDataProvider('Personspeciality');
    $this->render('index', array(
        'dataProvider' => $dataProvider,
    ));
  }

  /**
   * Manages all models.
   */
  public function actionAdmin() {
    $model = new Personspeciality('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['Personspeciality']))
      $model->attributes = $_GET['Personspeciality'];

    $this->render('admin', array(
        'model' => $model,
    ));
  }

  /**
   * Обновление цены за обучение
   * @param type $id
   */
  public function actionStudupdate($id) {
    $model = $this->loadModel($id);
    $valid = true;
    if (isset($_POST['Personspeciality'])) {
      $model->attributes = $_POST['Personspeciality'];
      $valid = $model->validate() && $valid;

      try {
        if ($model->save())
          $model = new PersonSpecialityView('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['PersonSpecialityView']))
          $model->attributes = $_GET['PersonSpecialityView'];
        //$person = PersonSpecialityView::model()->findByPk($model->idPersonSpeciality);
        echo CJSON::encode(array("result" => "success", "data" =>
            $this->renderPartial("//prices/tabs/_studprice", array('model' => $model), true)
        ));
        Yii::app()->end();
      } catch (Exception $e) {
        echo CJSON::encode(array("result" => "error", "data" => $e->getMessage()));
        Yii::app()->end();
      }
    }
    $this->renderPartial('_studpriceModal', array('model' => $model, 'personid' => $model->idPersonSpeciality));
  }

  /**
   * Returns the data model based on the primary key given in the GET variable.
   * If the data model is not found, an HTTP exception will be raised.
   * @param integer the ID of the model to be loaded
   */
  public function loadModel($id) {
    $model = Personspeciality::model()->findByPk($id);
    if ($model === null)
      throw new CHttpException(404, 'The requested page does not exist.');
    return $model;
  }

  public function actionSpecialitys($idFacultet, $idEducationForm, $QualificationID) {
//            $data = Specialities::model()->findAll('FacultetID=:FacultetID',
//                          array(':FacultetID'=>(int) $idFacultet));
//
//            $data=CHtml::listData($data,'idSpeciality','SpecialityName');
//            echo CHtml::tag('option', array('value'=>""), "", true);
    $data = Specialities::DropDownMask1($idFacultet, $idEducationForm, $QualificationID);
    echo CHtml::tag('option', array('value' => ""), "", true);
    foreach ($data as $value => $name) {
      echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
    }
  }
  
  /**
   * Формування рейтингу та звірення даних з ЄДЕБО.
   */
  public function actionRating(){
    $reqPersonspeciality = Yii::app()->request->getParam('Personspeciality',null);
    $reqFaculty = Yii::app()->request->getParam('Facultets',null);
    $reqBenefits = Yii::app()->request->getParam('Benefit',null);
    
    $model = new Personspeciality();
    $model->rating_order_mode = 0;
    
    if (isset($reqPersonspeciality['rating_order_mode'])){
      $model->rating_order_mode = $reqPersonspeciality['rating_order_mode'];
      if ($model->rating_order_mode){
        $model->SepcialityID = $reqPersonspeciality['SepcialityID'];
      }
    }
    
    $faculty = new Facultets('search');
    $benefit = new Benefit('search');
    if (!$model->rating_order_mode){
      $faculty->unsetAttributes();  // clear any default values
      if ($reqFaculty){
        $faculty->attributes = $reqFaculty;
      }
      $benefit->unsetAttributes();  // clear any default values
      if ($reqBenefits){
        $benefit->attributes = $reqBenefits;
      }
    }
    $model->searchFaculty = $faculty;
    $model->searchBenefit = $benefit;
    
    if (isset($reqPersonspeciality['searchID']) && !$model->rating_order_mode){
      $model->searchID = $reqPersonspeciality['searchID'];
    }
    if (isset($reqPersonspeciality['NAME']) && !$model->rating_order_mode){
      $model->NAME = $reqPersonspeciality['NAME'];
    }
    if (isset($reqPersonspeciality['SPEC']) && !$model->rating_order_mode){
      $model->SPEC = $reqPersonspeciality['SPEC'];
    }
    if (isset($reqPersonspeciality['status_confirmed'])){
      $model->status_confirmed = $reqPersonspeciality['status_confirmed'];
    }
    if (isset($reqPersonspeciality['status_committed'])){
      $model->status_committed = $reqPersonspeciality['status_committed'];
    }
    if (isset($reqPersonspeciality['status_submitted'])){
      $model->status_submitted = $reqPersonspeciality['status_submitted'];
    }
    if (isset($reqPersonspeciality['mistakes_only']) && !$model->rating_order_mode){
      $model->mistakes_only = $reqPersonspeciality['mistakes_only'];
    }
    if (isset($reqPersonspeciality['edbo_mode']) && !$model->rating_order_mode){
      $model->edbo_mode = $reqPersonspeciality['edbo_mode'];
    }
    if (isset($reqPersonspeciality['page_size']) && !$model->rating_order_mode){
      $model->page_size = $reqPersonspeciality['page_size'];
    }
    
    $data = $model->search_rel();
    $this->layout = '//layouts/main';
    $this->render('/personspeciality/rating',array(
       'model' => $model,
       'data' => $data,
    ));
  }
  
  /**
   * Формування XLS-файлу з рейтингом конкретної спеціальності
   */
  public function actionExcelrating(){
    $reqPersonspeciality = Yii::app()->request->getParam('Personspeciality',null);
    $reqFaculty = Yii::app()->request->getParam('Facultets',null);
    $reqBenefits = Yii::app()->request->getParam('Benefit',null);

    $model = new Personspeciality();
    if (isset($reqPersonspeciality['rating_order_mode'])){
      $model->rating_order_mode = $reqPersonspeciality['rating_order_mode'];
      if ($model->rating_order_mode){
        $model->SepcialityID = $reqPersonspeciality['SepcialityID'];
      }
    }
    $faculty = new Facultets('search');
    $benefit = new Benefit('search');
    if (!$model->rating_order_mode){
      $faculty->unsetAttributes();  // clear any default values
      if ($reqFaculty){
        $faculty->attributes = $reqFaculty;
      }
      $benefit->unsetAttributes();  // clear any default values
      if ($reqBenefits){
        $benefit->attributes = $reqBenefits;
      }
      if (isset($reqPersonspeciality['SPEC'])){
        $model->SPEC = $reqPersonspeciality['SPEC'];
      }
    }
    $model->searchFaculty = $faculty;
    $model->searchBenefit = $benefit;
    if (isset($reqPersonspeciality['status_confirmed'])){
      $model->status_confirmed = $reqPersonspeciality['status_confirmed'];
    }
    if (isset($reqPersonspeciality['status_committed'])){
      $model->status_committed = $reqPersonspeciality['status_committed'];
    }
    if (isset($reqPersonspeciality['status_submitted'])){
      $model->status_submitted = $reqPersonspeciality['status_submitted'];
    }
    //повертається масив моделей
    $models = $model->search_rel(true);
    if (count($models)){
        $_data = $this->CreateRatingData($models);
        $this->layout = '//layouts/clear';
        $this->render('/personspeciality/excelrating',$_data);
    } else {
        echo 'Помилка - немає даних!';
    }
  }
  
  /**
   * Метод формує рейтингові дані для конкретної спеціальності.
   * @param Personspeciality[] $models масив моделей, що повертає метод search_rel
   * @return array
   */
  protected function CreateRatingData($models){
        $Speciality = iconv("utf-8", "windows-1251",
                $models[0]->SPEC);
        $Faculty = iconv("utf-8", "windows-1251",
                $models[0]->sepciality->facultet->FacultetFullName);
        $_contract_counter = $models[0]->sepciality->SpecialityContractCount;
        $_budget_counter = $models[0]->sepciality->SpecialityBudgetCount;
        $_pzk_counter = $models[0]->sepciality->Quota1;
        $_quota_counter = $models[0]->sepciality->Quota2;
        Personspeciality::setCounters(
                $_contract_counter, 
                $_budget_counter, 
                $_pzk_counter, 
                $_quota_counter);

        $u_max_info_row = array();
        $info_row = array();

        $i = 0;
        $qpzk = 0;
        $u = 0;
        
        $data['pzk'] = array();
        $data['quota'] = array();
        $data['budget'] = array();
        $data['contract'] = array();
        $data['below'] = array();
        $below_counter = 0;
        
        foreach ($models as $model){
          $info_row['PIB'] = iconv("utf-8", "windows-1251",$model->NAME);
          $info_row['Points'] = $model->ComputedPoints;
          $info_row['isPZK'] = ($model->isOutOfComp || $model->Quota1)? '+': '';
          $info_row['isExtra'] = ($model->isExtraEntry)? '+': '';
          $info_row['isOriginal'] = (!$model->isCopyEntrantDoc)? '+': '';
          $was = 0;
          if ((Personspeciality::$is_rating_order) && $model->Quota1){
            //цільовики
            $was = Personspeciality::decrementCounter(Personspeciality::$C_QUOTA);    
            if ($was){
              Personspeciality::decrementCounter(Personspeciality::$C_BUDGET);
              $local_counter = 1 + $_quota_counter - $was;
              $data['quota'][$local_counter] = $info_row;
              $qpzk++;
            } else {
              $info_row['isPZK'] = 'Z';
              if ($u == 0){
                $u_max_info_row = $info_row;
              } else if ( (float)$u_max_info_row['Points'] < (float)$info_row['Points'] ){
                $u_max_info_row = $info_row;
              }
              $data['u'][$u++] = $info_row;
              $i++;
              continue;
            }
          }

          if ((Personspeciality::$is_rating_order) && $model->isOutOfComp && !$model->Quota1){
            //поза конкурсом
            $was = Personspeciality::decrementCounter(Personspeciality::$C_OUTOFCOMPETITION);
            if ($was){
              Personspeciality::decrementCounter(Personspeciality::$C_BUDGET);
              $local_counter = 1 + $_pzk_counter - $was;
              $data['pzk'][$local_counter] = $info_row;
              $qpzk++;
            } else {
              $info_row['isPZK'] = 'Z';
              if ($u == 0){
                $u_max_info_row = $info_row;
              } else if ( (float)$u_max_info_row['Points'] < (float)$info_row['Points'] ){
                $u_max_info_row = $info_row;
              }
              $data['u'][$u++] = $info_row;
              $i++;
              continue;
            }
          }

          if ( (Personspeciality::$is_rating_order) && (
                  ( $model->isBudget && !$model->isOutOfComp && !$model->Quota1 ) || 
                  (!empty($data['u']) && !$model->isOutOfComp && !$model->Quota1 )) ){
            //на бюджет
            while (!empty($data['u']) && ( (float)$u_max_info_row['Points'] > (float)$info_row['Points'])){
              $was = Personspeciality::decrementCounter(Personspeciality::$C_BUDGET);
              if ($was){
                $local_counter = 1 + $_budget_counter - $was - $qpzk;
                $data['budget'][$local_counter] = $u_max_info_row;
              }
              else {
                $was = Personspeciality::decrementCounter(Personspeciality::$C_CONTRACT);
                if ($was){
                  $local_counter = 1 + $_contract_counter - $was;
                  $data['contract'][$local_counter] = $u_max_info_row;
                }
                else {
                  break;
                }
              }
              $p_max = 0.0;
              foreach ($data['u'] as $u_id => $d_u){
                if ($d_u['PIB'] == $u_max_info_row['PIB'] && $d_u['Points'] == $u_max_info_row['Points']){
                  unset($data['u'][$u_id]);
                  continue;
                }
                if ((float)$d_u['Points'] > $p_max){
                  $p_max = (float)$d_u['Points'];
                  $u_max_info_row = $d_u;
                }
              }
            }
            $was = Personspeciality::decrementCounter(Personspeciality::$C_BUDGET);
            if ($was){
              $local_counter = 1 + $_budget_counter - $was - $qpzk;
              $data['budget'][$local_counter] = $info_row;
              $i++;
              continue;
            }
          }

          if ((Personspeciality::$is_rating_order) && 
                  ((!$model->isBudget && !$model->isOutOfComp && !$model->Quota1) || 
                  (!$was && $model->isBudget && !$model->isOutOfComp && !$model->Quota1) )){
            //на контракт
            while (!empty($data['u']) && ( (float)$u_max_info_row['Points'] > (float)$info_row['Points'])){
              $was = Personspeciality::decrementCounter(Personspeciality::$C_CONTRACT);
              if ($was){
                $local_counter = 1 + $_contract_counter - $was;
                $data['contract'][$local_counter] = $u_max_info_row;
              }
              if (!$was){
                break;
              }
              $p_max = 0.0;
              foreach ($data['u'] as $u_id => $d_u){
                if ($d_u['PIB'] == $u_max_info_row['PIB'] && $d_u['Points'] == $u_max_info_row['Points']){
                  unset($data['u'][$u_id]);
                  continue;
                }
                if ((float)$d_u['Points'] > $p_max){
                  $p_max = (float)$d_u['Points'];
                  $u_max_info_row = $d_u;
                }
              }
            }
            $was = Personspeciality::decrementCounter(Personspeciality::$C_CONTRACT);
            if ($was){
              $local_counter = 1 + $_contract_counter - $was;
              $data['contract'][$local_counter] = $info_row;
              $i++;
              continue;
            }
          }
          
          if (!$was){
            while (!empty($data['u']) && ( (float)$u_max_info_row['Points'] > (float)$info_row['Points'])){
              $data['below'][$below_counter++] = $u_max_info_row;
              $p_max = 0.0;
              foreach ($data['u'] as $u_id => $d_u){
                if ($d_u['PIB'] == $u_max_info_row['PIB'] && $d_u['Points'] == $u_max_info_row['Points']){
                  unset($data['u'][$u_id]);
                  continue;
                }
                if ((float)$d_u['Points'] > $p_max){
                  $p_max = (float)$d_u['Points'];
                  $u_max_info_row = $d_u;
                }
              }
            }
            $data['below'][$below_counter++] = $info_row;
          }
          $i++;
        }
        return array('data'=>$data,
            'Speciality'=>$Speciality,
            'Faculty'=>$Faculty,
            '_contract_counter'=>$_contract_counter,
            '_budget_counter'=>$_budget_counter,
            '_pzk_counter'=>$_pzk_counter,
            '_quota_counter'=>$_quota_counter,
            );
  }
  
  /**
   * Performs the AJAX validation.
   * @param CModel the model to be validated
   */
  protected function performAjaxValidation($model) {
    if (isset($_POST['ajax']) && $_POST['ajax'] === 'personspeciality-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }
  }

}
