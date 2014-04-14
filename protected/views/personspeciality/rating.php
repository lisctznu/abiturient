<?php

/* @var $model Personspeciality */
/* @var $data CActiveDataProvider */

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'rating-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $data,
    'filter' => $model,
    'columns' => array(
        array(
            'header' => 'ID заяви',
            'name' => 'idPersonSpeciality',
            'htmlOptions' => array(
                'style' => 'width: 50px;'
            )
        ),
        array(
            'header' => 'ID персони',
            'name' => 'person.idPerson',
            'filter' => CHtml::activeTextField($model->searchPerson, 'idPerson'),
            'htmlOptions' => array(
                'style' => 'width: 70px;'
            )
        ),
        array(
            'header' => 'ID документів',
            'name' => 'docs.idDocuments',
            'filter' => CHtml::activeTextField($model->searchDoc, 'idDocuments'),
            'htmlOptions' => array(
                'style' => 'width: 90px;'
            ),
            'value' => function ($data){
              foreach ($data->person->docs as $document){
                echo '<span class=\'label label-info\' style=\'margin-bottom: 3px;\'>'.$document->idDocuments."</span>";
                echo '<div class="clear"></div>';
              }
            }
        ),
        array(
            'name' => 'facultet.FacultetFullName',
            'filter' => CHtml::activeTextField($model->searchFaculty, 'FacultetFullName'),
            'header' => 'Факультет',
            'htmlOptions' => array(
                'style' => 'width: 120px;'
            ),
            'value' => '$data->sepciality->facultet->FacultetFullName'
        ),
        array(
            'name' => 'SPEC',
            'header' => 'Спеціальність',
            'filter' => CHtml::activeTextField($model, 'SPEC'),
            'htmlOptions' => array(
                'style' => 'width: 120px;'
            ),
        ),
        array(
            'name' => 'NAME',
            'header' => 'ПІБ',
            'filter' => CHtml::activeTextField($model, 'NAME'),
        ),
        array(
            'header' => 'Рейтингові відмітки',
            'htmlOptions' => array(
              'style' => 'width: 200px;'  
            ),
            'value' => function ($data){
              /* @var $data Personspeciality */
              $Total = 0.0;
              $doc_val = 0;
              $doc_val_zno = 0;
              $doc_name = (mb_substr($data->sepciality->SpecialityClasifierCode,0,1,'utf-8') == '6')?
                      'Атестат' : 'Диплом';
              foreach ($data->person->docs as $doc){
                /* @var $doc Documents */
                if ($doc->TypeID == 2 && $doc_name == 'Атестат') { //якщо це атестат про повну загальну освіту
                  $doc_val = round($doc->AtestatValue,1);
                  $atestat_model = Atestatvalue::model()->find('AtestatValue LIKE '.$doc_val);
                  if ($atestat_model){
                    $doc_val_zno = $atestat_model->ZnoValue;
                    $Total += $doc_val_zno;
                  }
                  break;
                }
                if (($doc->TypeID == 11 || $doc->TypeID == 12) && $doc_name == 'Диплом') { 
                  $doc_val = $doc->AtestatValue;
                  $doc_val_zno = $doc_val;
                  $Total += $doc_val_zno;
                  break;
                }
              }
              echo '<div style=\'width: 70px !important;float:left;\'>'.$doc_name.' : </div>' . (($doc_val_zno)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px;font-size: 8pt;\''
                      . ' title="Значення в документі : '.$doc_val.'">'.
                      $doc_val_zno . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px;font-size: 8pt;\'>'.
                      'немає' . '</span><div class="clear"></div>');
              
              $Total += (($data->documentSubject1)? $data->documentSubject1->SubjectValue : 0.0);
              $Total += (($data->documentSubject2)? $data->documentSubject2->SubjectValue : 0.0);
              $Total += (($data->documentSubject3)? $data->documentSubject3->SubjectValue : 0.0);
              
              echo '<div style=\'width: 70px !important;float:left;\'>ЗНО : </div>' . (($data->documentSubject1)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt;\'>'.
                      $data->documentSubject1->SubjectValue . '</span>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt;\'>'.
                      'немає' . '</span>');
              
              echo (($data->documentSubject2)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px;margin-right: 2px;margin-left:2px; font-size: 8pt;\'>'.
                      $data->documentSubject2->SubjectValue . '</span>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px;margin-right: 2px;margin-left:2px; font-size: 8pt;\'>'.
                      'немає' . '</span>');
              
              echo (($data->documentSubject3)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt;\'>'.
                      $data->documentSubject3->SubjectValue . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt;\'>'.
                      'немає' . '</span><div class="clear"></div>');
              
              $Total += (float)$data->AdditionalBall;
              echo '<div style=\'width: 70px !important;float:left;\'>Додатково : </div>' . (($data->AdditionalBall)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt;\'>'.
                      $data->AdditionalBall . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt;\'>'.
                      'немає' . '</span><div class="clear"></div>');
              $Total += (float)$data->CoursedpBall;
              echo '<div style=\'width: 70px !important;float:left;\'>Курси : </div>' . (($data->CoursedpBall)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt;\'>'.
                      $data->CoursedpBall . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt;\'>'.
                      'немає' . '</span><div class="clear"></div>');
              $Total += ($data->olymp? $data->olymp->OlympiadAwardBonus : 0.0);
              echo '<div style=\'width: 70px !important;float:left;\'>Олімпіади : </div>' . (($data->olymp)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px;\'>'.
                      $data->olymp->OlympiadAwardBonus . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px;\'>'.
                      'немає' . '</span><div class="clear"></div>');
              $Total += (float)$data->Exam1Ball;
              $Total += (float)$data->Exam2Ball;
              $Total += (float)$data->Exam3Ball;
              echo '<div style=\'width: 70px !important;float:left;\'>Вступні ісп. : </div>' . (($data->Exam1Ball)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt;\'>'.
                      $data->Exam1Ball . '</span>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt;\'>'.
                      'немає' . '</span>');
              echo (($data->Exam2Ball)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px;margin-right: 2px;margin-left:2px; font-size: 8pt;\'>'.
                      $data->Exam2Ball . '</span>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px;margin-right: 2px;margin-left:2px; font-size: 8pt;\'>'.
                      'немає' . '</span>');
              echo (($data->Exam3Ball)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt;\'>'.
                      $data->Exam3Ball . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt;\'>'.
                      'немає' . '</span><div class="clear"></div>');
              
              
              
              echo '<div style=\'width: 70px !important;float:left;\'>Разом : </div>' . 
                      '<span class=\'label label-inverse\' style=\'margin-bottom: 3px;font-size: 10pt;\''
                      . ' >'.
                      $Total .
                      '</span><div class="clear"></div>' ;
              //echo $data->Baldetail;
            }
        ),


    ),
    'htmlOptions' => array(
        'style' => 'font-size : 8pt;'
    )
));

?>