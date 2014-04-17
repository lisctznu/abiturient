<?php

/* @var $model Personspeciality */
/* @var $data CActiveDataProvider */
?>
<script type="text/javascript">
$(function (){
  $('#rating-params-form').submit(function(){
    if (($('#Personspeciality_SPEC').val() === '') && 
            $('#Personspeciality_order_mode').is(':checked')){
      alert('Заповніть, будь-ласка, ключові фрази спеціальності');
      return false;
    }
    $.fn.yiiGridView.update('rating-grid', {
      data: $(this).serialize()
    });
    return false;
  });
});
</script>

<div class="rating-params">
  <div class="wide form">

    <?php
    /* @var $form CActiveForm */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'id' => 'rating-params-form',
    ));
    ?>

    <div class="row">
<?php echo $form->checkBox($model, 'order_mode', array(
    'style' => 'float:left;margin-right: 10px;'
)); ?>
<?php echo $form->label($model, 'order_mode', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; width: 300px; text-align: left;'
)); ?>
    </div>
    <div class="row">
<?php echo $form->label($model, 'page_size', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; width: 300px; text-align: left;'
)); ?>
<?php echo $form->textField($model, 'page_size', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px;'
)); ?>
    </div>
    <div class='row'>
<?php echo $form->label($model, 'SPEC', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; width: 300px; text-align: left;'
)); ?>
<?php echo $form->textField($model, 'SPEC', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px;'
)); ?>
    </div>
    <div class="row buttons">
    <?php
    $this->widget("bootstrap.widgets.TbButton", array(
              'buttonType'=>'submit',
              'type'=>'primary',
              "size"=>"large",
              'htmlOptions' => array(
                'id' => 'RatingButton',
               ),
              'label'=>'Створити вибірку',
    )
        ); 
    ?>
    </div>

<?php $this->endWidget(); ?>

  </div><!-- search-form -->
</div>

<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'rating-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $data,
    'filter' => $model,
    'columns' => array(
        array(
            'header' => 'ID_PS',
            'name' => 'idPersonSpeciality',
            'filter' => CHtml::activeTextField($model, 'idPersonSpeciality', array(
                'style' => 'font-size: 7pt; font-family: Tahoma; height: 12px; width: 25px;'
            )),
            'htmlOptions' => array(
                'style' => 'width: 30px;'
            ),
            'headerHtmlOptions' => array(
                'style' => 'width: 30px;'
            )
        ),
        array(
            'header' => 'ID_P',
            'name' => 'person.idPerson',
            'filter' => CHtml::activeTextField($model->searchPerson, 'idPerson', array(
                'style' => 'font-size: 7pt; font-family: Tahoma; height: 12px; width: 25px;'
            )),
            'htmlOptions' => array(
                'style' => 'width: 30px;'
            ),
            'headerHtmlOptions' => array(
                'style' => 'width: 30px;'
            )
        ),
//        array(
//            'header' => 'Документи',
//            'name' => 'docs.idDocuments',
//            'filter' => CHtml::activeTextField($model->searchDoc, 'idDocuments', array(
//                'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px; width: 70px;'
//            )),
//            'htmlOptions' => array(
//                'style' => 'width: 80px;'
//            ),
//            'headerHtmlOptions' => array(
//                'style' => 'width: 80px;'
//            ),
//            'value' => function ($data){
//              foreach ($data->person->docs as $document){
//                echo '<span class=\'label label-info\' style=\'margin-bottom: 3px;\'>'.$document->idDocuments."</span>";
//                echo '<div class="clear"></div>';
//              }
//            }
//        ),
        array(
            'header' => 'Пільги',
            'name' => 'benefit.BenefitName',
            'filter' => CHtml::activeTextField($model->searchBenefit, 'BenefitName', array(
                'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px; width: 120px;'
            )),
            'htmlOptions' => array(
                'style' => 'width: 130px;'
            ),
            'headerHtmlOptions' => array(
                'style' => 'width: 130px;'
            ),
            'value' => function ($data){
              /* @var $data Personspeciality */
              $benefits =  Personbenefits::model()->findAll('PersonID='.$data->PersonID);
              foreach ($benefits as $benefit){
                $bgcolor = 'white';
                if ($benefit->benefit->isPV){
                  $bgcolor = '#FFFFCC';
                }
                if ($benefit->benefit->isPZK){
                  $bgcolor = '#CCDDCC';
                }
                echo '<div class=\'well well-small\' '
                . 'style=\'margin-bottom: 3px; width: 130px !important; '
                        . 'height: 140px !important;'
                        . 'font-size: 7pt;'
                        . 'overflow-wrap: break-word;'
                        . 'overflow-y: auto;'
                        . 'background-color: ' . $bgcolor . ';'
                        . '\' '
                . 'title=\''.$benefit->benefit->BenefitName.'\'>'
                .  $benefit->benefit->BenefitName
                . "</div>";
              }
            }
        ),
        array(
            'name' => 'facultet.FacultetFullName',
            'filter' => CHtml::activeTextField($model->searchFaculty, 'FacultetFullName', array(
                'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px; width: 75px;'
            )),
            'header' => 'Факультет',
            'htmlOptions' => array(
                'style' => 'width: 90px;'
            ),
            'headerHtmlOptions' => array(
                'style' => 'width: 90px;'
            ),
            'value' => '$data->sepciality->facultet->FacultetFullName'
        ),
        array(
            'name' => 'SPEC',
            'header' => 'Спеціальність',
            'filter' => CHtml::activeTextField($model, 'SPEC', array(
                'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px; width: 100px;'
            )),
            'htmlOptions' => array(
                'style' => 'width: 120px;'
            ),
            'headerHtmlOptions' => array(
                'style' => 'width: 120px;'
            )
        ),
        array(
            'name' => 'NAME',
            'header' => 'ПІБ',
            'filter' => CHtml::activeTextField($model, 'NAME', array(
                'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px;'
              )
            ),      
            'htmlOptions' => array(
                'style' => 'font-size: 12pt;'
            ),
                    
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
              
?> <div style="display:none;" id="id_<?php echo $data->idPersonSpeciality; ?>">  <?php
              echo '<div style=\'width: 70px !important;float:left;\'>'.$doc_name.' : </div>' . (($doc_val_zno)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px;font-size: 8pt;\''
                      . ' title="Значення в документі : '.$doc_val.'">'.
                      $doc_val_zno . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px;font-size: 8pt;\'>'.
                      'н/з' . '</span><div class="clear"></div>');
              
              $Total += (($data->documentSubject1)? $data->documentSubject1->SubjectValue : 0.0);
              $Total += (($data->documentSubject2)? $data->documentSubject2->SubjectValue : 0.0);
              $Total += (($data->documentSubject3)? $data->documentSubject3->SubjectValue : 0.0);
              
              echo '<div style=\'width: 70px !important;float:left;\'>ЗНО : </div>' . (($data->documentSubject1)? 
                      '<span class=\'label label-info\' '
                      . 'style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\' '
                      . 'title=\''.(($data->documentSubject1->subject) ? $data->documentSubject1->subject->SubjectName : '').'\'>'.
                      $data->documentSubject1->SubjectValue . '</span>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span>');
              
              echo (($data->documentSubject2)? 
                      '<span class=\'label label-info\' '
                      . 'style=\'margin-bottom: 3px;margin-right: 2px;margin-left:2px; font-size: 8pt; font-family: Tahoma;\' '
                      . 'title=\''.(($data->documentSubject2->subject) ? $data->documentSubject2->subject->SubjectName : '').'\'>'.
                      $data->documentSubject2->SubjectValue . '</span>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px;margin-right: 2px;margin-left:2px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span>');
              
              echo (($data->documentSubject3)? 
                      '<span class=\'label label-info\' '
                      . 'style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\' '
                      . 'title=\''.(($data->documentSubject3->subject) ? $data->documentSubject3->subject->SubjectName : '').'\'>'.
                      $data->documentSubject3->SubjectValue . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span><div class="clear"></div>');
              
              $Total += (float)$data->AdditionalBall;
              echo '<div style=\'width: 70px !important;float:left;\'>Додатково : </div>' . (($data->AdditionalBall)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      $data->AdditionalBall . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span><div class="clear"></div>');
              $Total += (float)$data->CoursedpBall;
              echo '<div style=\'width: 70px !important;float:left;\'>Курси : </div>' . (($data->CoursedpBall)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      $data->CoursedpBall . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span><div class="clear"></div>');
              $Total += ($data->olymp? $data->olymp->OlympiadAwardBonus : 0.0);
              echo '<div style=\'width: 70px !important;float:left;\'>Олімпіади : </div>' . (($data->olymp)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      $data->olymp->OlympiadAwardBonus . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span><div class="clear"></div>');
              $Total += (float)$data->Exam1Ball;
              $Total += (float)$data->Exam2Ball;
              $Total += (float)$data->Exam3Ball;
              echo '<div style=\'width: 70px !important;float:left;\'>Вступні ісп. : </div>' . (($data->Exam1Ball)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      $data->Exam1Ball . '</span>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span>');
              echo (($data->Exam2Ball)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px;margin-right: 2px;margin-left:2px; font-size: 8pt; font-family: Tahoma;\'>'.
                      $data->Exam2Ball . '</span>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px;margin-right: 2px;margin-left:2px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span>');
              echo (($data->Exam3Ball)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      $data->Exam3Ball . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span><div class="clear"></div>');
?> </div>  <?php
              
              
              echo '<div style=\'width: 70px !important;float:left;\'>Разом : </div>' . 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px;'
                      . ' font-size: 12pt; font-family: Tahoma; padding: 7px;\''
                      . ' >'.
                      $Total .
                      '</span>'
                      . '<a href=\'#\' '
                      . ' style=\'margin-left: 5px;\''
                      . ' onclick=\'$("#id_'.$data->idPersonSpeciality.'").slideToggle(); return false;\'>'
                      . 'деталі'
                      . '</a><div class="clear"></div>' ;
              //echo $data->Baldetail;
            }
        ),


    ),
    'htmlOptions' => array(
        'style' => 'font-size : 8pt;'
    )
));

?>