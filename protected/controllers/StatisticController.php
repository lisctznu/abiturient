<?php

class StatisticController extends Controller {

  /**
   * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
   * using two-column layout. See 'protected/views/layouts/column2.php'.
   */
  //public $layout='//layouts/column2';
  public $defaultAction = 'index';

  /**
   * @return array action filters
   */
  public function filters() {
    return array(
        'accessControl', // perform access control for CRUD operations
        'postOnly + ', // we only allow deletion via POST request
    );
  }

  /**
   * Specifies the access control rules.
   * This method is used by the 'accessControl' filter.
   * @return array access control rules
   */
  public function accessRules() {
    return array(
        array('allow', // allow authenticated user to perform 'create' and 'update' actions
            'actions' => array('util', 'index', 'View', 
                'Print', "Sverka", "ViewEx", 
                "ViewY", "Originals", "ViewBC", 
                "Statisticallname", "Stateb", "Statebperson",
                "Fromvillage", "Residentlist", "Viewall", 
                "Viewallprint", "Verify", "Viewgraduated", 
                "Viewgraduatedbyf", "Foreigngrad", "Examwithoutzno",
                "Maglang", "Maglangfil", "Personspecmag", 
                "Personspecspecialists", "Acts", "CreateActs", 
                "GraduatedSchool"),
            'users' => array('@'),
        ),
        array('allow', // allow admin user to perform 'admin' and 'delete' actions
            'actions' => array('Public'),
            'users' => array('*'),
        ),
        array('allow', // allow admin user to perform 'admin' and 'delete' actions
            'actions' => array('Util'),
            'roles' => array('Root'),
        ),
//			array('deny',  // deny all users
//				'users'=>array('*'),
//			),
//                        */
//			array('allow', // allow admin user to perform 'admin' and 'delete' actions
//				'actions'=>array('index','view','admin'),
//				'roles'=>array("Root","Admins","Operators"),
//			),
        array('deny', // deny all users
            'users' => array('*'),
        ),
    );
  }
  
  /**
   * Метод формує дані для звіту і сам звіт про к-сть заявок абітурієнтів
   * для денної та заочної форми усіх спеціальностей
   * для конкретної дати і ОКР та за період від 01.07 поточного року.
   */
  public function actionView() {
    /* @var $reqQualifictionID integer */
    /* @var $reqEduFormID integer */
    $reqQualificationID = Yii::app()->request->getParam('QualificationID',1);
    $reqDate = Yii::app()->request->getParam('Date',date('d.m.Y'));
    
    $time = strtotime(str_replace('.','-',$reqDate));
    $date = date('Y-m-d',time());
    if ($time !== FALSE){
      $date = date('Y-m-d',$time);
    }
    $spec_ident = '6';
    switch ($reqQualificationID){
      case 1 : $spec_ident='6'; break;
      case 2 : $spec_ident='8'; break;
      case 3 : $spec_ident='7'; break;
    }
    
    $criteria = new CDbCriteria();
    $criteria->with = array(
        'facultet',
    );
    $criteria->addCondition('t.PersonEducationFormID IN(1,2)');
    $criteria->addCondition('SUBSTR(t.SpecialityClasifierCode,1,1) LIKE '
            . '"'.$spec_ident.'"');
    
    $criteria->select = array('*',
        new CDbExpression('((SELECT COUNT(ps.idPersonSpeciality) FROM personspeciality ps WHERE '
                . 'ps.SepcialityID=t.idSpeciality AND '
                . 'ps.QualificationID = ' . $reqQualificationID . ' AND '
                . 'ps.StatusID NOT IN (10) AND '
                . 'ps.CreateDate BETWEEN '
                . '"' . $date . ' 00:00:00' . '" '
                . 'AND "' . $date . ' 23:59:59")) AS cnt_requests_per_day'),
        new CDbExpression('((SELECT COUNT(ps.idPersonSpeciality) FROM personspeciality ps WHERE '
                . 'ps.SepcialityID=t.idSpeciality AND '
                . 'ps.QualificationID = ' . $reqQualificationID . ' AND '
                . 'ps.StatusID NOT IN (10) AND '
                . 'ps.CreateDate BETWEEN '
                //. '"'.date('Y').'-07-01 00:00:00' . '" '
                . '"2013-07-01 00:00:00' . '" '
                . 'AND "' . $date . ' 23:59:59")) AS cnt_requests'),
        new CDbExpression('((SELECT COUNT(DISTINCT ps.PersonID) FROM personspeciality ps WHERE '
                . 'ps.QualificationID IN ' . (($reqQualificationID == 1)? '(1)' : '(2,3)') . ' AND '
                . 'ps.CreateDate BETWEEN '
                . '"' . $date . ' 00:00:00' . '" '
                . 'AND "' . $date . ' 23:59:59")) AS cnt_persons_per_day'),
        new CDbExpression('((SELECT COUNT(DISTINCT ps.PersonID) FROM personspeciality ps WHERE '
                . 'ps.QualificationID IN ' . (($reqQualificationID == 1)? '(1)' : '(2,3)') . ' AND '
                . 'ps.CreateDate BETWEEN '
                //. '"'.date('Y').'-07-01 00:00:00' . '" '
                . '"2013-07-01 00:00:00' . '" '
                . 'AND "' . $date . ' 23:59:59")) AS cnt_persons'),
    );
    $criteria->group = 'idSpeciality';
    $criteria->order = 'facultet.FacultetFullName,SpecialityDirectionName,SpecialityName';
    $specs = Specialities::model()->findAll($criteria);
    
    $cnt_data = array();
    $counts_atall = array();
    
    $counts_atall[1]['per_day'] = 0;
    $counts_atall[1]['all'] = 0;
    
    $counts_atall[2]['per_day'] = 0;
    $counts_atall[2]['all'] = 0;
    
    $counts_atall['persons_per_day'] = 0;
    $counts_atall['persons_all'] = 0;
    $i=0;
    foreach ($specs as $spec){
      /* @var $spec Specialities */
      if (!isset($cnt_data[$spec->FacultetID])){
        $cnt_data[$spec->FacultetID] = array();
      }
      if (!isset($cnt_data[$spec->FacultetID]['name'])){
        $cnt_data[$spec->FacultetID]['name'] = $spec->facultet->FacultetFullName;
      }
      $lspec_ident = mb_substr($spec->SpecialityClasifierCode,0,1,'utf-8');
      $spec_name = $spec->SpecialityClasifierCode . ' '
            . (($lspec_ident == '6')?
                    $spec->SpecialityDirectionName : $spec->SpecialityName )
            . (($spec->SpecialitySpecializationName == '')? 
                    '' : ' ('.$spec->SpecialitySpecializationName. ')');
      $cnt_data[$spec->FacultetID][$spec_name][$spec->PersonEducationFormID] = array(
          'eduform' => ($spec->PersonEducationFormID == 1)? 'денна':"заочна",
          'cnt_requests_per_day' => $spec->cnt_requests_per_day,
          'cnt_requests' => $spec->cnt_requests,
      );
      $counts_atall[$spec->PersonEducationFormID]['per_day'] += $spec->cnt_requests_per_day;
      $counts_atall[$spec->PersonEducationFormID]['all'] += $spec->cnt_requests;
      if ($i == 0){
        $counts_atall['persons_per_day'] = $spec->cnt_persons_per_day;
        $counts_atall['persons_all'] = $spec->cnt_persons;
      }
      $i++;
    }
//    var_dump($cnt_data);
//    var_dump($counts_atall);
//    exit();
    
    $this->layout = '//layouts/clear';
    
    $this->render('statistic', array(
        'cnt_data' => $cnt_data,
        'summary' => $counts_atall,
        'spec_ident' => $spec_ident,
        'date' => $reqDate
    ));
  }

  public function actionIndex() {
    $this->render('index');
  }

  public function actionPrint() {
    $this->layout = '//layouts/clear';
    $this->render('print');
  }

  public function actionPublic() {
    $model = new PersonSpecialityView('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['PersonSpecialityView'])) {
      $model->attributes = $_GET['PersonSpecialityView'];
    }
    $this->layout = '//layouts/main_1';
    $this->render('sverka', array("model" => $model));
  }

  public function actionSverka() {
    $model = new PersonSpecialityView('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['PersonSpecialityView'])) {
      $model->attributes = $_GET['PersonSpecialityView'];
      $this->layout = '//layouts/main_1';
      $this->render('sverka', array("model" => $model));
    } else if (isset($_POST['PersonSpecialityView'])) {
      $model->attributes = $_POST['PersonSpecialityView'];
      $this->layout = '//layouts/main_empty';
      $this->render('sverka_print', array("model" => $model));
    } else {
      $this->layout = '//layouts/main_1';
      $this->render('sverka', array("model" => $model));
    }
  }

  public function actionViewEx() {
    $this->layout = '//layouts/main_1';
    $this->render('statisticx');
  }

  public function actionViewY() {
    $this->layout = '//layouts/clear';
    $this->render('statisticy');
  }

  public function actionOriginals() {
    $this->layout = '//layouts/clear';
    $this->render('originals');
  }

  public function actionViewBC() {
    $this->layout = '//layouts/clear';
    $this->render('statistic_budget_contract');
  }

  public function actionStatisticallname() {
    $this->layout = '//layouts/clear';
    $this->render('Statisticallname');
  }

  public function actionStateb() {
    $this->layout = '//layouts/clear';
    $this->render('stateb');
  }

  public function actionStatebperson() {
    $this->layout = '//layouts/clear';
    $this->render('statebperson');
  }

  public function actionFromvillage() {
    $this->layout = '//layouts/main_1';
    $model = new VillageList('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['VillageList']))
      $model->attributes = $_GET['VillageList'];
    $this->layout = '//layouts/main_1';
    $this->render('from_village', array(
        'model' => $model,
    ));
  }

  public function actionResidentlist() {
    $this->layout = '//layouts/main_1';
    $this->render('resident_list');
  }

  /**
   * Метод виводить кількісну статистику.
   * @todo Do it later. Str=>299
   */
  public function actionViewall() {
    /* @var $reqQualifictionID integer */
    /* @var $reqEduFormID integer */
    $reqQualificationID = Yii::app()->request->getParam('QualificationID',1);
    $reqDateFrom = Yii::app()->request->getParam('DateFrom',date('d.m.Y'));
    $reqDateTo = Yii::app()->request->getParam('DateTo',date('d.m.Y'));
    $reqMode = Yii::app()->request->getParam('mode',0);
    $modes = explode(';',$reqMode);
    
    $timeFrom = strtotime(str_replace('.','-',$reqDateFrom));
    $dateFrom = date('Y-m-d',time());
    if ($timeFrom !== FALSE){
      $dateFrom = date('Y-m-d',$timeFrom);
    }
    $timeTo = strtotime(str_replace('.','-',$reqDateTo));
    $dateTo = date('Y-m-d',time());
    if ($timeTo !== FALSE){
      $dateTo = date('Y-m-d',$timeTo);
    }
    $spec_ident = '6';
    switch ($reqQualificationID){
      case 1 : $spec_ident='6'; break;
      case 2 : $spec_ident='8'; break;
      case 3 : $spec_ident='7'; break;
    }
    
    $sql_condition = ''; 
    for ($i = 0; $i < count($modes); $i++){
      switch ($modes[$i]){
        case 0: 
          $sql_condition .= 'ps.isBudget = 1 AND '; 
          break;
        case 1: 
          $sql_condition .= 'ps.isContract = 1 AND '; 
          break;
        case 2: 
          $sql_condition .= 'ps.isCopyEntrantDoc <> 1 AND '; 
          break;
        case 3: 
          $sql_condition .= 'ps.RequestFromEB = 1 AND '; 
          break;
      }
    }
    
    $criteria = new CDbCriteria();
    $criteria->with = array(
        'facultet',
    );
    $criteria->addCondition('t.PersonEducationFormID IN(1,2)');
    $criteria->addCondition('SUBSTR(t.SpecialityClasifierCode,1,1) LIKE '
            . '"'.$spec_ident.'"');
    
    $criteria->select = array('*',
        new CDbExpression('((SELECT COUNT(ps.idPersonSpeciality) FROM personspeciality ps WHERE '
                . 'ps.SepcialityID=t.idSpeciality AND '
                . 'ps.QualificationID = ' . $reqQualificationID . ' AND '
                . 'ps.StatusID NOT IN (10) AND '
                . $sql_condition
                . 'ps.CreateDate BETWEEN '
                . '"' . $dateFrom . ' 00:00:00' . '" '
                . 'AND "' . $dateTo . ' 23:59:59")) AS cnt_req_budget'),
        new CDbExpression('((SELECT COUNT(ps.idPersonSpeciality) FROM personspeciality ps WHERE '
                . 'ps.SepcialityID=t.idSpeciality AND '
                . 'ps.QualificationID = ' . $reqQualificationID . ' AND '
                . 'ps.StatusID NOT IN (10) AND '
                . 'ps.CreateDate BETWEEN '
                //. '"'.date('Y').'-07-01 00:00:00' . '" '
                . '"2013-07-01 00:00:00' . '" '
                . 'AND "' . $date . ' 23:59:59")) AS cnt_requests'),
        new CDbExpression('((SELECT COUNT(DISTINCT ps.PersonID) FROM personspeciality ps WHERE '
                . 'ps.QualificationID IN ' . (($reqQualificationID == 1)? '(1)' : '(2,3)') . ' AND '
                . 'ps.CreateDate BETWEEN '
                . '"' . $date . ' 00:00:00' . '" '
                . 'AND "' . $date . ' 23:59:59")) AS cnt_persons_per_day'),
        new CDbExpression('((SELECT COUNT(DISTINCT ps.PersonID) FROM personspeciality ps WHERE '
                . 'ps.QualificationID IN ' . (($reqQualificationID == 1)? '(1)' : '(2,3)') . ' AND '
                . 'ps.CreateDate BETWEEN '
                //. '"'.date('Y').'-07-01 00:00:00' . '" '
                . '"2013-07-01 00:00:00' . '" '
                . 'AND "' . $date . ' 23:59:59")) AS cnt_persons'),
    );
    $criteria->group = 'idSpeciality';
    $criteria->order = 'facultet.FacultetFullName,SpecialityDirectionName,SpecialityName';
    $specs = Specialities::model()->findAll($criteria);
    
    $cnt_data = array();
    $counts_atall = array();
    
    $counts_atall[1]['per_day'] = 0;
    $counts_atall[1]['all'] = 0;
    
    $counts_atall[2]['per_day'] = 0;
    $counts_atall[2]['all'] = 0;
    
    $counts_atall['persons_per_day'] = 0;
    $counts_atall['persons_all'] = 0;
    $i=0;
    foreach ($specs as $spec){
      /* @var $spec Specialities */
      if (!isset($cnt_data[$spec->FacultetID])){
        $cnt_data[$spec->FacultetID] = array();
      }
      if (!isset($cnt_data[$spec->FacultetID]['name'])){
        $cnt_data[$spec->FacultetID]['name'] = $spec->facultet->FacultetFullName;
      }
      $lspec_ident = mb_substr($spec->SpecialityClasifierCode,0,1,'utf-8');
      $spec_name = $spec->SpecialityClasifierCode . ' '
            . (($lspec_ident == '6')?
                    $spec->SpecialityDirectionName : $spec->SpecialityName )
            . (($spec->SpecialitySpecializationName == '')? 
                    '' : ' ('.$spec->SpecialitySpecializationName. ')');
      $cnt_data[$spec->FacultetID][$spec_name][$spec->PersonEducationFormID] = array(
          'eduform' => ($spec->PersonEducationFormID == 1)? 'денна':"заочна",
          'cnt_requests_per_day' => $spec->cnt_requests_per_day,
          'cnt_requests' => $spec->cnt_requests,
      );
      $counts_atall[$spec->PersonEducationFormID]['per_day'] += $spec->cnt_requests_per_day;
      $counts_atall[$spec->PersonEducationFormID]['all'] += $spec->cnt_requests;
      if ($i == 0){
        $counts_atall['persons_per_day'] = $spec->cnt_persons_per_day;
        $counts_atall['persons_all'] = $spec->cnt_persons;
      }
      $i++;
    }
//    var_dump($cnt_data);
//    var_dump($counts_atall);
//    exit();
    
    $this->layout = '//layouts/clear';
    
    $this->render('statistic', array(
        'cnt_data' => $cnt_data,
        'summary' => $counts_atall,
        'spec_ident' => $spec_ident,
        'date' => $reqDate
    ));
  }

  public function actionViewallprint() {
    $this->layout = '//layouts/clear';
    $this->render('viewall_print', array("model" => AllCounts::model()));
  }

  public function actionViewgraduated() {
    $this->layout = '//layouts/main_1';
    $this->render('graduated', array("model" => StatGraduated::model()));
  }

  public function actionViewgraduatedbyf() {
    $this->layout = '//layouts/clear';
    $this->render('graduatedbyf', array("model" => StatGraduatedByF::model()));
  }

  public function actionPersonspecmag() {
    $this->layout = '//layouts/clear';
    $this->render('getcsv', array("model" => PersonspecMag::model()));
  }

  public function actionPersonspecspecialists() {
    $this->layout = '//layouts/clear';
    $this->render('getcsv', array("model" => PersonspecSpecialists::model()));
  }

  public function actionVerify() {
    $this->layout = '//layouts/main_1';
    $model = new PersonspecAll('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['PersonspecAll']))
      $model->attributes = $_GET['PersonspecAll'];
    $this->layout = '//layouts/main_1';
    $this->render('verify', array(
        'model' => $model,
    ));
  }

  public function actionForeigngrad() {
    $this->layout = '//layouts/main_1';
    $this->render('foreigngraduated', array("model" => GraduatedAbiStat::model()));
  }

  public function actionExamwithoutzno() {
    $this->layout = '//layouts/clear';
    $this->render('examwithoutzno');
  }

  public function actionMaglang() {
    $this->layout = '//layouts/clear';
    $this->render('maglang');
  }

  public function actionMaglangfil() {
    $this->layout = '//layouts/clear';
    $this->render('maglangfil');
  }

  public function actionActs() {
    $this->layout = '//layouts/clear';
    $this->render('acts/index');
  }

  public function actionCreateActs() {
    $this->layout = '//layouts/clear';
    $this->render('acts/select');
  }

  public function actionGraduatedSchool() {
    $this->layout = '//layouts/main_1';
    $model = new GraduatedSchool('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['GraduatedSchool']))
      $model->attributes = $_GET['GraduatedSchool'];
    $this->layout = '//layouts/main_1';
    $this->render('graduatedschool', array(
        'model' => $model,
    ));
  }

  public function actionUtil() {
    $model = new PersonSpecialityView('search');
    $model->unsetAttributes();  // clear any default values
    if (isset($_GET['PersonSpecialityView'])) {
      $model->attributes = $_GET['PersonSpecialityView'];
      $out = "";
      if (isset($_GET['renum'])) {
        $c = new CDbCriteria();
        $c->compare("SepcialityID", $model->SepcialityID);
        $c->order = "CreateDate";
        $pspes = Personspeciality::model()->findAll($c);

        foreach ($pspes as $i => $obj) {
          $out.="RequestNumber: " . $obj->RequestNumber . " chaget to: " . ($i + 1) . "<br>";
          $obj->RequestNumber = $i + 1;
          if ($obj->QualificationID > 1 && $obj->SepcialityID != 70686 && $obj->SepcialityID != 90661) {
            $obj->scenario = "SHORTFORM";
            //$obj->CausalityID = 100;
          }
          if (!$obj->save()) {
            debug(print_r($obj->getErrors(), true));
          }
        }
      }
      $this->render('sverka', array("model" => $model));
      if (!empty($out))
        echo $out;
    } else {
      $this->render('util');
    }
  }

}
