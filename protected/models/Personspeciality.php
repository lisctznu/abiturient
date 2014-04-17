<?php

/**
 * This is the model class for table "personspeciality".
 *
 * The followings are the available columns in table 'personspeciality':
 * @property integer $idPersonSpeciality
 * @property integer $RequestNumber
 * @property integer $PersonID
 * @property integer $SepcialityID
 * @property integer $PaymentTypeID
 * @property integer $EducationFormID
 * @property integer $QualificationID
 * @property integer $EntranceTypeID
 * @property integer $CourseID
 * @property integer $CausalityID
 * @property integer $CoursedpID
 * @property integer $OlympiadID
 * @property integer $GraduatedUniversitieID
 * @property integer $GraduatedSpecialitieID
 * @property string  $GraduatedSpeciality
 * @property integer $PersonDocumentsAwardsTypesID
 * @property integer $isTarget
 * @property integer $isContract
 * @property integer $isBudget
 * @property integer $isNeedHostel
 * @property integer $isNotCheckAttestat
 * @property integer $isForeinghEntrantDocument
 * @property double  $AdditionalBall
 * @property double  $CoursedpBall
 * @property integer $isCopyEntrantDoc
 * @property integer $DocumentSubject1
 * @property integer $DocumentSubject2
 * @property integer $DocumentSubject3
 * @property integer $Exam1ID
 * @property integer $Exam1Ball
 * @property integer $Exam2ID
 * @property integer $Exam2Ball
 * @property integer $Exam3ID
 * @property integer $Exam3Ball
 *
 * The followings are the available model relations:
 * @property Person $person
 * @property Documentsubject $documentSubject1
 * @property Documentsubject $documentSubject2
 * @property Documentsubject $documentSubject3
 * @property Subjects $exam1
 * @property Subjects $exam2
 * @property Subjects $exam3
 * @property Olympiadsawards $olymp
 * @property Specialities $sepciality
 * @property Personeducationpaymenttypes $paymentType
 * @property Personeducationforms $educationForm
 * @property Qualifications $qualification
 * @property Personenterancetypes $entranceType
 * @property Courses $course
 * @property Causality $causality
 * @property integer $StatusID
 * @property Personrequeststatustypes $status
 * @property integer $RequestFromEB
 * @property integer $Quota1
 * @property integer $Quota2
 */
class Personspeciality extends ActiveRecord {

  public $StatusID = 1;
  public $currentMaxRequestNumber;
  public $currentMaxPersonRequestNumber;
  public $isHigherEducation = 0;
  public $isCopyEntrantDoc = 1;
  
  /* @var $searchPerson Person */
  /* @var $searchSpeciality Specialities */
  /* @var $searchFaculty Facultets */
  /* @var $searchDoc Documents */
  /* @var $searchBenefit Benefits */
  public $searchPerson;
  public $searchSpeciality;
  public $searchFaculty;
  public $searchDoc;
  public $searchBenefit;
  
  public $NAME;
  public $SPEC;
  public $Baldetail;
  public $order_mode;
  public $page_size;

  /**
   * Returns the static model of the specified AR class.
   * @param string $className active record class name.
   * @return Personspeciality the static model class
   */
  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  /**
   * @return string the associated database table name
   */
  public function getRequestPrefix() {
    $prefix = "";
    switch ($this->QualificationID) {
      case 1: $prefix = "Б";
        break;
      case 2: $prefix = "CМ";
        break;
      case 3: $prefix = "СМ";
        break;
      case 4: $prefix = "МС";
        break;
    }

    $prefix .= $this->CourseID . "-";

    return $prefix;
  }

  public function getRowClass() {
    // deleted
    if ($this->StatusID == 10)
      return "row-red";
    // cenceled
    if ($this->StatusID == 3)
      return "row-reset";
    //denied
    if ($this->StatusID == 2)
      return "row-goldenrod";
    if (!empty($this->edboID))
      return "row-green";
    return "";
  }

  public function tableName() {
    return 'personspeciality';
  }

  public function isShortForm() {
    if ($this->QualificationID == 2 || $this->QualificationID == 3)
      return true;
    return false;
  }

  /**
   * @return array validation rules for model attributes.
   */
  public function rules() {
    // NOTE: you should only define rules for those attributes that
    // will receive user inputs.
    return array(
        array('PersonID, SepcialityID,  EducationFormID, 
                               QualificationID, EntranceTypeID, CourseID, CausalityID, 
                               isContract, isBudget, isCopyEntrantDoc, DocumentSubject1, 
                               DocumentSubject2, DocumentSubject3, 
                               Exam1ID, Exam1Ball, Exam2ID, Exam2Ball,
                               Exam3ID, Exam3Ball, isHigherEducation, SkipDocumentValue', 'numerical', 'integerOnly' => true),
        array("AdditionalBallComment,  CoursedpID, Quota1,Quota2, OlympiadID, isNotCheckAttestat, isForeinghEntrantDocument, PersonDocumentsAwardsTypesID, edboID, RequestFromEB, StatusID", 'safe'),
        array("Exam1ID", 'required', 'on' => "SHORTFORM"),
        array("EntranceTypeID", "required", "except" => "SHORTFORM"),
        //array("CausalityID",  "default", "value"=>100,"except"=>"SHORTFORM"),
        array("Exam1Ball, Exam2Ball, Exam3Ball", 'numerical',
            "max" => 200, "min" => 1, "allowEmpty" => true, 'except' => 'ZNOEXAM, EXAM'),
        array("AdditionalBall, CoursedpBall", 'numerical',
            "max" => 200, "min" => 1, "allowEmpty" => true),
        array('PersonID, SepcialityID,  EducationFormID, 
                               QualificationID,  CourseID, isContract, 
                               isCopyEntrantDoc, EntrantDocumentID, isNeedHostel', "required"),
        array("DocumentSubject1, DocumentSubject2, DocumentSubject3", "required", "on" => "ZNO"),
        array("Exam1ID, Exam2ID, Exam3ID, CausalityID", "required", "on" => "EXAM"),
        array("Exam1Ball, Exam2Ball, Exam3Ball", 'numerical', "max" => 200, "min" => 1, "allowEmpty" => true, "on" => "EXAM"),
        array("CausalityID", "required", "on" => "ZNOEXAM"),
        array("Exam1ID, Exam2ID, Exam3ID, DocumentSubject1, DocumentSubject2, DocumentSubject3", "valididateZnoExam", "on" => "ZNOEXAM"),
        array("Exam1Ball, Exam2Ball, Exam3Ball", 'numerical', "max" => 200, "min" => 1, "allowEmpty" => true, "on" => "ZNOEXAM"),
        // DocumentSubject1, DocumentSubject2, DocumentSubject3, 
        //  Exam1ID, Exam1Ball, Exam2ID, Exam2Ball, Exam3ID, Exam3Ball', 'numerical', 'integerOnly'=>true),
        //array('AdditionalBall', 'numerical'),
        // The following rule is used by search().
        // Please remove those attributes that should not be searched.
        array('idPersonSpeciality, PersonID, SepcialityID,  EducationFormID, QualificationID, EntranceTypeID, CourseID, CausalityID, isContract, AdditionalBall, isCopyEntrantDoc, DocumentSubject1, DocumentSubject2, DocumentSubject3, Exam1ID, Exam1Ball, Exam2ID, Exam2Ball, Exam3ID, Exam3Ball', 'safe', 'on' => 'search'),
        array('CustomerName,DocCustumer,AcademicSemesterID,CustomerAddress,CustomerPaymentDetails,DateОfСontract,PaymentDate', 'safe'),
    );
  }

  public function valididateZnoExam($attributes) {
    switch ($attributes) {
      case "DocumentSubject1":
      case "Exam1ID":
        if ((empty($this->DocumentSubject1) && empty($this->Exam1ID)) || (!empty($this->DocumentSubject1) && !empty($this->Exam1ID))) {
          $this->addError("$attributes", "Потрібно обрати сертифікат або предмт");

          return false;
        }
        break;
      case "DocumentSubject2":
      case "Exam2ID":
        if ((empty($this->DocumentSubject2) && empty($this->Exam2ID)) || (!empty($this->DocumentSubject2) && !empty($this->Exam2ID))) {
          $this->addError("$attributes", "Потрібно обрати сертифікат або предмт");

          return false;
        }
        break;
      case "DocumentSubject3":
      case "Exam3ID":

        if ((empty($this->DocumentSubject3) && empty($this->Exam3ID)) || (!empty($this->DocumentSubject3) && !empty($this->Exam3ID))) {
          $this->addError("$attributes", "Потрібно обрати сертифікат або предмет");

          return false;
        }
    }
    return true;
  }

  public function validate($attributes = null, $clearErrors = true) {
    if ($this->EntranceTypeID == 1)
      $this->scenario = "ZNO";
    if ($this->EntranceTypeID == 2)
      $this->scenario = "EXAM";
    if ($this->EntranceTypeID == 3)
      $this->scenario = "ZNOEXAM";

    return parent::validate($attributes, $clearErrors);
  }

  public function beforeSave() {
    if ($this->isNewRecord) {
      $c = new CDbCriteria();
      $c->compare("SepcialityID", $this->SepcialityID);
      $c->compare("QualificationID", $this->QualificationID);
      $c->compare("CourseID", $this->CourseID);
      $c->select = 'max(RequestNumber) as currentMaxRequestNumber';
      $res = self::model()->find($c);

      $this->RequestNumber = $res->currentMaxRequestNumber + 1;
    }

    if ($this->isNewRecord) {
      $c = new CDbCriteria();


      if ($this->QualificationID == 3 || $this->QualificationID == 2) {
        $c->addCondition("(QualificationID = 2 or QualificationID = 3) and PersonID = '{$this->PersonID}' and '{$this->CourseID}'");
      } else {
        $c->compare("PersonID", $this->PersonID);
        $c->compare("QualificationID", $this->QualificationID);
        $c->compare("CourseID", $this->CourseID);
      }

      $c->compare("CourseID", $this->CourseID);

      $res = self::model()->find($c);

      if (!empty($res) && !empty($res->PersonRequestNumber)) {
        $this->PersonRequestNumber = $res->PersonRequestNumber;
      } else {
        $c = new CDbCriteria();
        if ($this->QualificationID == 3 || $this->QualificationID == 2) {
          $c->addCondition("(QualificationID = 2 or QualificationID = 3) and '{$this->CourseID}'");
        } else {
          //$c->compare("PersonID", $this->PersonID);
          $c->compare("QualificationID", $this->QualificationID);
          $c->compare("CourseID", $this->CourseID);
        }
        $c->compare("CourseID", $this->CourseID);
        $c->select = 'max(PersonRequestNumber) as currentMaxPersonRequestNumber';
        $res = self::model()->find($c);
        $this->PersonRequestNumber = $res->currentMaxPersonRequestNumber + 1;
      }
    }

    return parent::beforeSave();
  }

  /**
   * @return array relational rules.
   */
  public function relations() {
    // NOTE: you may need to adjust the relation name and the related
    // class name for the relations automatically generated below.
    return array(
        'person' => array(self::BELONGS_TO, 'Person', 'PersonID'),
        'exam1' => array(self::BELONGS_TO, 'Subjects', 'Exam1ID'),
        'exam2' => array(self::BELONGS_TO, 'Subjects', 'Exam2ID'),
        'exam3' => array(self::BELONGS_TO, 'Subjects', 'Exam3ID'),
        'sepciality' => array(self::BELONGS_TO, 'Specialities', 'SepcialityID'),
        'paymentType' => array(self::BELONGS_TO, 'Personeducationpaymenttypes', 'PaymentTypeID'),
        'educationForm' => array(self::BELONGS_TO, 'Personeducationforms', 'EducationFormID'),
        'qualification' => array(self::BELONGS_TO, 'Qualifications', 'QualificationID'),
        'entranceType' => array(self::BELONGS_TO, 'Personenterancetypes', 'EntranceTypeID'),
        'course' => array(self::BELONGS_TO, 'Courses', 'CourseID'),
        'causality' => array(self::BELONGS_TO, 'Causality', 'CausalityID'),
        'documentSubject1' => array(self::BELONGS_TO, 'Documentsubject', 'DocumentSubject1'),
        'documentSubject2' => array(self::BELONGS_TO, 'Documentsubject', 'DocumentSubject2'),
        'documentSubject3' => array(self::BELONGS_TO, 'Documentsubject', 'DocumentSubject3'),
        'olymp' => array(self::BELONGS_TO, 'Olympiadsawards', 'OlympiadID'),
        'status' => array(self::BELONGS_TO, 'Personrequeststatustypes', 'StatusID'),
//                     
    );
  }

  /**
   * @return array customized attribute labels (name=>label)
   */
  public function attributeLabels() {
    return array(
        'idPersonSpeciality' => 'Id Person Speciality',
        'PersonID' => 'Person',
        'SepcialityID' => 'Спеціальність',
        'PaymentTypeID' => 'Форма оплати',
        'EducationFormID' => 'Форма навчання',
        'QualificationID' => 'Навчальний рівень',
        'EntranceTypeID' => 'Форма вступу',
        'CourseID' => 'Курс',
        'CausalityID' => 'Причина відсутності сертифікату',
        'isTarget' => 'Цільовий вступ',
        'isContract' => 'Контракт',
        'isBudget' => 'Бюджет',
        'isNeedHostel' => 'Потрібен гуртожиток',
        'AdditionalBall' => 'Додатковий бал',
        'EntrantDocumentID' => 'Документ-основа вступу',
        'isCopyEntrantDoc' => 'Копія',
        'DocumentSubject1' => 'Предмет сертифікату',
        'DocumentSubject2' => 'Предмет сертифікату',
        'DocumentSubject3' => 'Предмет сертифікату',
        'Exam1ID' => 'Екзамен 1',
        'Exam1Ball' => 'Бал 1',
        'Exam2ID' => 'Екзамен 2',
        'Exam2Ball' => 'Бал 2',
        'Exam3ID' => 'Екзамен 3',
        'Exam3Ball' => 'Бал 3',
        'isHigherEducation' => 'Освіта аналогічного кваліфікаційного рівня',
        'SkipDocumentValue' => 'Бал док-та не враховується',
        'AdditionalBallComment' => 'Коментар до додаткового балу',
        'CoursedpID' => 'Курси довузівської підготовки',
        'CoursedpBall' => 'Бал за курси',
        'OlympiadId' => 'Олімпіади',
        'Quota1' => 'Квота (с-ка міс-ть)',
        'Quota2' => 'Квота (держ. сл-ба)',
        'RequestNumber' => "Номер заявки",
        'PersonRequestNumber' => "Номер справи",
        "PersonDocumentsAwardsTypesID" => "Відзнака",
        'isForeinghEntrantDocument' => "Іноземн. док-т",
        'OlympiadID' => "Олимпиада",
        'isNotCheckAttestat' => "Не перевіряти",
        'GraduatedUniversitieID' => "ВНЗ, який закінчив",
        'GraduatedSpecialitieID' => "Напрямок (спеціальність), яку закінчив",
        "GraduatedSpeciality" => "Напрямок (спеціальність), яку закінчив",
        'RequestFromEB' => 'Эл-на за-ка',
        "edboID" => "ЄДБО Код",
        "StatusID" => "Статус заявки",
        "order_mode" => "Сортування у режимі 'РЕЙТИНГ' (за балами)",
        "page_size" => "Кількість рядків для однієї сторінки у списку",
        "SPEC" => "Ключові слова через пробіл для вибірки за спеціальністю",
    );
  }

  /**
   * Retrieves a list of models based on the current search/filter conditions.
   * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
   */
  public function search() {
    // Warning: Please modify the following code to remove attributes that
    // should not be searched.

    $criteria = new CDbCriteria;

    $criteria->compare('idPersonSpeciality', $this->idPersonSpeciality);
    $criteria->compare('PersonID', $this->PersonID);
    $criteria->compare('SepcialityID', $this->SepcialityID);
    $criteria->compare('PaymentTypeID', $this->PaymentTypeID);
    $criteria->compare('EducationFormID', $this->EducationFormID);
    $criteria->compare('QualificationID', $this->QualificationID);
    $criteria->compare('EntranceTypeID', $this->EntranceTypeID);
    $criteria->compare('CourseID', $this->CourseID);
    $criteria->compare('CausalityID', $this->CausalityID);
    $criteria->compare('isTarget', $this->isTarget);
    $criteria->compare('isContact', $this->isContact);
    $criteria->compare('AdditionalBall', $this->AdditionalBall);
    $criteria->compare('isCopyEntrantDoc', $this->isCopyEntrantDoc);
    $criteria->compare('DocumentSubject1', $this->DocumentSubject1);
    $criteria->compare('DocumentSubject2', $this->DocumentSubject2);
    $criteria->compare('DocumentSubject3', $this->DocumentSubject3);
    $criteria->compare('Exam1ID', $this->Exam1ID);
    $criteria->compare('Exam1Ball', $this->Exam1Ball);
    $criteria->compare('Exam2ID', $this->Exam2ID);
    $criteria->compare('Exam2Ball', $this->Exam2Ball);
    $criteria->compare('Exam3ID', $this->Exam3ID);
    $criteria->compare('Exam3Ball', $this->Exam3Ball);

    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
    ));
  }
  
  /**
   * Пошук із врахування зовнішніх реляційних відношень.
   * Enjoy this code with smiles.
   */
  public function search_rel(){
    $order_mode = 0;
    $page_size = 5;
    if (is_numeric($this->order_mode)){
      $order_mode = $this->order_mode;
    }
    if (is_numeric($this->page_size) && $this->page_size > 0){
      $page_size = $this->page_size;
    }
    $criteria = new CDbCriteria();
    //З якими відношеннями маємо справу
    $with_rel = array();
    array_push($with_rel, 'sepciality');
    array_push($with_rel, 'person');
    array_push($with_rel, 'documentSubject1');
    array_push($with_rel, 'documentSubject2');
    array_push($with_rel, 'documentSubject3');
    array_push($with_rel, 'olymp');
    array_push($with_rel, 'educationForm');
    $with_rel['sepciality.facultet'] = array('select' => false);
    $with_rel['person.docs'] = array('select' => false);
    //array_push($with_rel, 'person.benefits.benefit');
    $with_rel['person.benefits.benefit'] = array('select' => false);
    $criteria->with = $with_rel;
    
    //також йде вибірка ПІБ персон і спеціальності 
    //формат поля спеціальності: [код_спеціальності назва_спеціальності[ (назва_напряму)] , форма: назва_форми]
    //можна ще додати поле для деталізації балів (рейтингу), але це для зневадження 
    $criteria->select = array('*',
        new CDbExpression('COUNT(DISTINCT benefit.idBenefit) AS cntBenefit'),
        new CDbExpression("concat_ws(' ',person.LastName,person.FirstName,person.MiddleName) AS NAME"),
        new CDbExpression("concat_ws(' ',"
                . "sepciality.SpecialityClasifierCode,"
                . "(case substr(sepciality.SpecialityClasifierCode,1,1) when '6' then "
                . "sepciality.SpecialityDirectionName else sepciality.SpecialityName end),"
                . "(case sepciality.SpecialitySpecializationName when '' then '' "
                . " else concat('(',sepciality.SpecialitySpecializationName,')') end)"
                . ",',',concat('форма: ',educationForm.PersonEducationFormName)) AS SPEC"),
//        new CDbExpression('concat(if (isnull(documentSubject1.SubjectValue),0.0,documentSubject1.SubjectValue),"+", 
//  if (isnull(documentSubject2.SubjectValue),0.0,documentSubject2.SubjectValue),"+", 
//  if (isnull(documentSubject3.SubjectValue),0.0,documentSubject3.SubjectValue),"+", 
//  if (isnull(t.AdditionalBall),0.0,t.AdditionalBall),"+", 
//  if (isnull(t.CoursedpBall),0.0,t.CoursedpBall),"+", 
//  if (isnull(olymp.OlympiadAwardBonus),0.0,olymp.OlympiadAwardBonus),"+", 
//  if (isnull(
//     (SELECT documents.AtestatValue 
//      FROM documents 
//      WHERE documents.TypeID = 2 AND documents.PersonID=t.PersonID AND substr(sepciality.SpecialityClasifierCode,1,1) like "6" LIMIT 1
//     )),
//    0.0,
//    round(((SELECT atestatvalue.ZnoValue 
//      FROM atestatvalue 
//      WHERE atestatvalue.AtestatValue like round(((
//             SELECT documents.AtestatValue 
//             FROM documents 
//             WHERE documents.TypeID = 2 AND documents.PersonID=t.PersonID LIMIT 1)
//      ),1) OR
//        atestatvalue.AtestatValue = round(((
//             SELECT documents.AtestatValue 
//             FROM documents 
//             WHERE documents.TypeID = 2 AND documents.PersonID=t.PersonID LIMIT 1)
//        ),1)
//     )
//    ),2)
//  ),"+", 
//
//  if (isnull(
//     (SELECT max(documents.AtestatValue)
//      FROM documents 
//      WHERE documents.PersonID=t.PersonID AND substr(sepciality.SpecialityClasifierCode,1,1) not like "6" LIMIT 1
//     )),
//    0.0,
//    round(((SELECT max(documents.AtestatValue)
//      FROM documents 
//      WHERE documents.PersonID=t.PersonID AND documents.TypeID IN (11,12) LIMIT 1
//     )),2)
//  ),"+", 
//
//  if (isnull(t.Exam1Ball),0.0,t.Exam1Ball),"+", 
//  if (isnull(t.Exam2Ball),0.0,t.Exam2Ball),"+", 
//  if (isnull(t.Exam3Ball),0.0,t.Exam3Ball)) as Baldetail')
        );
    $criteria->group = "t.idPersonSpeciality";
    //оформлення єдиного запиту на вибірку
    $criteria->together = true;
    
    $criteria->compare('facultet.FacultetFullName', $this->searchFaculty->FacultetFullName,true);
    $criteria->compare('t.idPersonSpeciality', $this->idPersonSpeciality);
    $criteria->compare('person.idPerson', $this->searchPerson->idPerson);
    $criteria->compare('docs.idDocuments', $this->searchDoc->idDocuments);
    if (is_numeric($this->searchBenefit->BenefitName)){
      $criteria->having = 'cntBenefit='.$this->searchBenefit->BenefitName;
      if ($this->searchBenefit->BenefitName > 1){
        $page_size = 1000;
      }
    }
    if (!is_numeric($this->searchBenefit->BenefitName)){
      $criteria->compare('benefit.BenefitName', $this->searchBenefit->BenefitName,true);
    }
    if ($this->NAME){
      $criteria->addCondition('concat_ws(" ",person.LastName,person.FirstName,person.MiddleName) '
            . 'LIKE "%'.$this->NAME.'%"');
    }
    if ($this->SPEC){
      $keys = explode(' ',$this->SPEC);
      foreach ($keys as $key){
      $criteria->addCondition("concat_ws(' ',"
                . "sepciality.SpecialityClasifierCode,"
                . "(case substr(sepciality.SpecialityClasifierCode,1,1) when '6' then "
                . "sepciality.SpecialityDirectionName else sepciality.SpecialityName end),"
                . "(case sepciality.SpecialitySpecializationName when '' then '' "
                . " else concat('(',sepciality.SpecialitySpecializationName,')') end)"
                . ",',',concat('форма: ',educationForm.PersonEducationFormName)) LIKE '%".$key."%'");
      }
    }
    
    $rating_order = 'if(sum(benefit.isPZK)>0,1,0) DESC, (if (isnull(documentSubject1.SubjectValue),0.0,documentSubject1.SubjectValue) + 
  if (isnull(documentSubject2.SubjectValue),0.0,documentSubject2.SubjectValue) + 
  if (isnull(documentSubject3.SubjectValue),0.0,documentSubject3.SubjectValue) + 
  if (isnull(t.AdditionalBall),0.0,t.AdditionalBall) + 
  if (isnull(t.CoursedpBall),0.0,t.CoursedpBall) + 
  if (isnull(olymp.OlympiadAwardBonus),0.0,olymp.OlympiadAwardBonus) + 
  if (isnull(
     (SELECT documents.AtestatValue 
      FROM documents 
      WHERE documents.TypeID = 2 AND documents.PersonID=t.PersonID AND substr(sepciality.SpecialityClasifierCode,1,1) like "6" LIMIT 1
     )),
    0.0,
    round(((SELECT atestatvalue.ZnoValue 
      FROM atestatvalue 
      WHERE atestatvalue.AtestatValue like round(((
             SELECT documents.AtestatValue 
             FROM documents 
             WHERE documents.TypeID = 2 AND documents.PersonID=t.PersonID LIMIT 1)
      ),1) OR
        atestatvalue.AtestatValue = round(((
             SELECT documents.AtestatValue 
             FROM documents 
             WHERE documents.TypeID = 2 AND documents.PersonID=t.PersonID LIMIT 1)
        ),1)
     )
    ),2)
  ) +  
  if (isnull(
     (SELECT documents.AtestatValue 
      FROM documents 
      WHERE documents.PersonID=t.PersonID AND documents.TypeID IN (11,12) LIMIT 1
     )),
    0.0,
    round(((SELECT max(documents.AtestatValue)
      FROM documents 
      WHERE documents.PersonID=t.PersonID AND documents.TypeID IN (11,12) LIMIT 1
     )),2)
  ) + 
  if (isnull(t.Exam1Ball),0.0,t.Exam1Ball) + 
  if (isnull(t.Exam2Ball),0.0,t.Exam2Ball) + 
  if (isnull(t.Exam3Ball),0.0,t.Exam3Ball)) DESC,if(sum(benefit.isPV)>0,1,0) DESC';
    if ($order_mode > 0){
      $criteria->order = $rating_order;
    }
    return new CActiveDataProvider($this, array(
        'criteria' => $criteria,
        'sort' => array(
            'defaultOrder' => array(
                'idPersonSpeciality' => CSort::SORT_DESC,
            ),
            'attributes' => array(
                'NAME' => array(
                    'asc' => 'NAME',
                    'desc' => 'NAME DESC',
                ),
                'SPEC' => array(
                    'asc' => 'SPEC',
                    'desc' => 'SPEC DESC',
                ),
                'person.idPerson' => array(
                    'asc' => 'person.idPerson',
                    'desc' => 'person.idPerson DESC',
                ),
//                'docs.idDocuments' => array(
//                    'asc' => 'docs.idDocuments',
//                    'desc' => 'docs.idDocuments DESC',
//                ),
                'benefit.BenefitName' => array(
                    'asc' => 'benefit.BenefitName',
                    'desc' => 'benefit.BenefitName DESC',
                ),
                'facultet.FacultetFullName' => array(
                    'asc' => 'facultet.FacultetFullName',
                    'desc' => 'facultet.FacultetFullName DESC',
                ),
                '*',
            ),
        ),
        'pagination' => array(
            'pageSize' => $page_size
        ),
    ));
  }

  public function loadOnlineStatementFromJSON($lol) {
    //$json_string = preg_replace("/[+-]?\d+\.\d+/", '"\0"', $json_string ); 

    /* $objarr = CJSON::decode($json_string);

      if (!empty($this->codeU)){
      Yii::app()->session[$this->codeU."-documents"] = serialize($objarr);
      }

      if ( trim($json_string) == "0" && empty($json_string) && count($objarr) == 0) return;

      foreach($objarr as $item){
      $val = (object)$item; */
    $model = $this;
//1	Код ЄДБО

    $model->edboID = $lol;
    /* if ($val->id_Type == 7)   {
      $model->entrantdoc = new Documents();
      $model->entrantdoc->TypeID = $val->id_Type;
      $model->entrantdoc->edboID = $val->id_Document;
      $model->entrantdoc->AtestatValue=$val->attestatValue;
      $model->entrantdoc->Numbers=$val->number;
      $model->entrantdoc->Series=$val->series;
      $model->entrantdoc->DateGet=date("d.m.Y",mktime(0, 0, 0, $val->dateGet['month']+1,  $val->dateGet['dayOfMonth'],  $val->dateGet['year']));
      $model->entrantdoc->ZNOPin = $val->znoPin;
      $model->entrantdoc->Issued = $val->issued;
      }
      } */
  }

}
