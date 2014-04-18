<?php

/* @var $model Personspeciality */
/* @var $data CActiveDataProvider */
?>
<script type="text/javascript">
//$(function (){
//  $('#rating-params-form').submit(function(){
//    if (($('#Personspeciality_SPEC').val() === '') && 
//            $('#Personspeciality_order_mode').is(':checked')){
//      alert('Заповніть, будь-ласка, ключові фрази спеціальності');
//      return false;
//    }
//    $.fn.yiiGridView.update('rating-grid', {
//      data: $(this).serialize()
//    });
//    return false;
//  });
//});
</script>

    <?php
    /* @var $form CActiveForm */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'id' => 'rating-params-form',
    ));
    ?>
<div class="well well-small row-fluid" style="width: 50%; margin: 0 auto;">
  <div class="span7">
    <span class="label label-info">нейтрально</span>
    <span class="label label-success">добре</span>
    <span class="label label-important">погано</span>
  </div>
  <div class="span5">
    <span style="font-size: 8pt; font-family: Tahoma; color: black; padding: 4px;">
      нейтрально
    </span>
    <span style="font-size: 8pt; font-family: Tahoma; color: green; padding: 4px;">
      добре
    </span>
    <span style="font-size: 8pt; font-family: Tahoma; color: red; padding: 4px;">
      погано
    </span>
  </div>
  <div class="clear"></div>
  
<?php echo $form->checkBox($model, 'order_mode', array(
    'style' => 'float:left;margin-right: 10px;'
)); ?>
<?php echo $form->label($model, 'order_mode', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;'
)); ?>
  
<?php echo $form->checkBox($model, 'edbo_mode', array(
    'style' => 'float:left;margin-right: 10px;'
)); ?>
<?php echo $form->label($model, 'edbo_mode', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;'
)); ?>
  <div class="span12">
    <div class="span6">
      <?php
      echo $form->label($model, 'page_size', array(
          'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;'
      ));
      ?>
      <?php
      echo $form->textField($model, 'page_size', array(
          'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px;'
      ));
      ?>
    </div>

    <div class="span6">
      <?php
      echo $form->label($model, 'SPEC', array(
          'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;'
      ));
      ?>
<?php
echo $form->textField($model, 'SPEC', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px;'
));
?>
    </div>
  </div>
    <?php
    $this->widget("bootstrap.widgets.TbButton", array(
              'buttonType'=>'submit',
              'type'=>'primary',
              "size"=>"mini",
              'htmlOptions' => array(
                'id' => 'RatingButton',
               ),
              'label'=>'Створити вибірку',
    )
        ); 
    ?>
</div>


<?php $this->endWidget(); ?>


<?php

$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'rating-grid',
    'type' => 'striped bordered condensed',
    'dataProvider' => $data,
    'filter' => $model,
    'columns' => array(
        array(
            'header' => 'ID',
            //'name' => 'searchID',
            'filter' => CHtml::activeTextField($model, 'searchID', array(
                'style' => 'font-size: 7pt; font-family: Tahoma; height: 12px; width: 40px;'
            )),
            'htmlOptions' => array(
                'style' => 'width: 50px;'
            ),
            'headerHtmlOptions' => array(
                'style' => 'width: 50px;'
            ),
            'value' => function($data,$row){
              /* @var $data Personspeciality */
?> <a href='#' title='Показати додаткові параметри' 
     onclick="$('#row_<?php echo $row; ?>').slideToggle();return false;">
     <i class="icon-white icon-info-sign" style="background-color: #05B2D2; border-radius: 10px;"></i>
     <?php echo ($row+1); ?></a> <?php
?> <div id='row_<?php echo $row; ?>' style='display:none; font-size:8pt;'> <?php
              echo 'id_заявки: <span class=\'label label-info\'>'.$data->idPersonSpeciality.
                      '</span><hr style=\'margin: 5px !important;\'/>';
              echo 'id_персони: <span class=\'label label-info\'>'.$data->PersonID.
                      '</span><hr style=\'margin: 5px !important;\'/>';
              echo 'id_ЄДЕБО: <span class=\'label label-info\'>'.$data->edboID.
                      '</span><br/>';
?> </div> <?php
            }
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
                'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px; width: 140px;'
            )),
            'htmlOptions' => array(
                'style' => 'width: 150px;'
            ),
            'headerHtmlOptions' => array(
                'style' => 'width: 150px;'
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
                'style' => 'font-size: 10pt;'
            ),
            'value' => function ($data){
              /* @var $data Personspeciality */
              if (!$data->edbo && $data->edboID){
                $data->edbo = EdboData::model()->findByPk($data->edboID);
              }
              $color = 'black';
              if ($data->edbo){
                $color = ($data->NAME == $data->edbo->PIB) ? 'green' : 'red';
                if ($data->NAME != $data->edbo->PIB){
                  echo '<div style=\'color: #BBDDBB; font-size: 8pt;\'>'.$data->edbo->PIB.'</div>';
                }
              }
              echo '<div style=\'color: '.$color.';\'>'.$data->NAME.'</div>';
            }
                    
        ),
                
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
              $benefits =  explode(';;',$data->BenefitList);
              $cnt_benefits = count($benefits);
              if ($cnt_benefits == 1 && $benefits[0] == ''){
                return ;
              }
              $is_out_of_comp_list = explode(';;', $data->isOutOfCompList);
              $is_extra_entry_list = explode(';;', $data->isExtraEntryList);
              if ($cnt_benefits > 0){
                $active_text = "";
                switch ($cnt_benefits){
                  case 1:
                    $active_text = "Є одна пільга";
                    break;
                  case 2:
                    $active_text = "Є дві пільги";
                    break;
                  case 3:
                    $active_text = "Є три пільги";
                    break;
                  default :
                    $active_text = "Кількість пільг : " . $cnt_benefits;
                    break;
                }
?> 
<a href="#" onclick="$('#benefit_<?php echo $data->idPersonSpeciality; ?>').slideToggle(); return false;">
    <span class="label label-info">
      <i class="icon-white icon-info-sign"></i>
      <?php echo $active_text; ?>
    </span></a>
<div style="display:none;" id="benefit_<?php echo $data->idPersonSpeciality; ?>"> <?php
                foreach ($benefits as $id => $benefit){
                  /* @var $benefit Personbenefits */
                  $bgcolor = 'white';
                  $title = '';
                  if ($is_extra_entry_list[$id]){
                    $bgcolor = '#FFFFCC';
                    $title .= '(абітурієнт має право на ПЕРШОЧЕРГОВИЙ вступ)';
                  }
                  if ($is_out_of_comp_list[$id]){
                    $bgcolor = '#CCDDCC';
                    $title .= '(абітурієнт має право на вступ ПОЗА КОНКУРСОМ)';
                  }
                  echo '<div class=\'well well-small\' '
                  . 'style=\'margin-bottom: 3px; width: 125px !important; '
                          . 'height: 140px !important;'
                          . 'font-size: 7pt;'
                          . 'overflow-wrap: break-word;'
                          . 'overflow-y: auto;'
                          . 'background-color: ' . $bgcolor . ';'
                          . '\' '
                  . 'title=\''.$title.'\'>'
                  .  $benefit
                  . "</div>";
                }
?> </div> <?php
              }
            }
        ),

        array(
            'header' => 'Рейтингові відмітки',
            'htmlOptions' => array(
              'style' => 'width: 200px;'  
            ),
            'value' => function ($data,$row){
              /* @var $data Personspeciality */
              $Total = 0.0;
              $doc_val = 0;
              $doc_val_zno = 0;
              if (!$data->edbo && $data->edboID){
                $data->edbo = EdboData::model()->findByPk($data->edboID);
              }
              $doc_name = (mb_substr($data->sepciality->SpecialityClasifierCode,0,1,'utf-8') == '6')?
                      'Атестат' : 'Диплом';
              
              $docvalues= explode(';',$data->DocValues);
              $doctypes = explode(';',$data->DocTypes);
              $points = array();
              foreach ($doctypes as $id => $doctype){
                $points[$doctype] = $docvalues[$id];
              }
              
              $PointMap = Personspeciality::getPointMap();
              
              if ($doc_name == 'Атестат') { //якщо це атестат про повну загальну освіту
                if (!isset($points[2])){
                  foreach ($points as $pid => $point){
                    if ($pid == 11 || $pid == 12){
                      $doc_name = '<span style=\'color: red\'>Диплом?</span>';
                      $doc_val = round($point,1);
                      break;
                    }
                  }
                } else {
                  $doc_val = round($points[2],1);
                }
                $doc_val_zno = $PointMap[(string)$doc_val];
//                var_dump($data->PointMap);
//                var_dump($doc_val);
//                var_dump($doc_val_zno);
//                
//                if ($row == 1){
//                  exit();
//                }
                $Total += $doc_val_zno;
              }
              if ($doc_name == 'Диплом') {
                if (!isset($points[11])){
                  $points[11] = 0.0;
                }
                if (!isset($points[12])){
                  $points[12] = 0.0;
                }
                $doc_val = max(array(round($points[11],1),round($points[12],1)));
                if (isset($PointMap[(string)$doc_val])){
                  $doc_val_zno = $PointMap[(string)$doc_val];
                } else {
                  $doc_val_zno = $doc_val;
                }
                $Total += $doc_val_zno;
              }
              
              $Total += (($data->documentSubject1)? (float)$data->documentSubject1->SubjectValue : 0.0);
              $Total += (($data->documentSubject2)? (float)$data->documentSubject2->SubjectValue : 0.0);
              $Total += (($data->documentSubject3)? (float)$data->documentSubject3->SubjectValue : 0.0);
              $Total += (float)$data->AdditionalBall;
              $Total += (float)$data->CoursedpBall;
              $Total += ($data->olymp? (float)$data->olymp->OlympiadAwardBonus : 0.0);
              $Total += (float)$data->Exam1Ball;
              $Total += (float)$data->Exam2Ball;
              $Total += (float)$data->Exam3Ball;
              
              
              $span_class = 'label-info';
              if ($data->edbo){
                $span_class = ((float)$data->edbo->RatingPoints == (float)$Total)? 
                        'label-success' : 'label-important';
              }
              echo '<div style=\'width: 70px !important;float:left;\'>Разом : </div>' 
                      . '<a href=\'#\' '
                      . ' style=\'margin-left: 5px;\''
                      . ' onclick=\'$("#id_'.$data->idPersonSpeciality.'").slideToggle(); return false;\'>'
                      . '<span class=\'label '.$span_class.'\' style=\'margin-bottom: 3px;'
                      . ' font-size: 10pt; font-family: Tahoma; padding: 4px;\''
                      . ' >'
                      . '<i class=\'icon-white icon-info-sign\'></i> '
                      . $Total
                      .'</span>'
                      . '</a><div class="clear"></div>' ;
              
?> <div style="display:none;" id="id_<?php echo $data->idPersonSpeciality; ?>">  <?php
              $span_class = 'label-info';
              if ($data->edbo){
                $span_class = ((float)$data->edbo->DocPoint == (float)$doc_val)?
                        'label-success' : 'label-important';
              }
              
              echo '<div style=\'width: 70px !important;float:left;\'>'.$doc_name.' : </div>' . (($doc_val_zno)? 
                      '<span class=\'label '.$span_class.'\' style=\'margin-bottom: 3px;font-size: 8pt;\''
                      . ' title="Значення в документі : '.$doc_val.'">'.
                      $doc_val_zno . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px;font-size: 8pt;\'>'.
                      'н/з' . '</span><div class="clear"></div>');
              
              echo '<div style=\'width: 70px !important;float:left;\'>ЗНО : </div>' . (($data->documentSubject1)? 
                      '<span class=\'label label-info\' '
                      . 'style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\' '
                      . 'title=\''.(($data->documentSubject1->subject1) ? $data->documentSubject1->subject1->SubjectName : '').'\'>'.
                      $data->documentSubject1->SubjectValue . '</span>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span>');
              
              echo (($data->documentSubject2)? 
                      '<span class=\'label label-info\' '
                      . 'style=\'margin-bottom: 3px;margin-right: 2px;margin-left:2px; font-size: 8pt; font-family: Tahoma;\' '
                      . 'title=\''.(($data->documentSubject2->subject2) ? $data->documentSubject2->subject2->SubjectName : '').'\'>'.
                      $data->documentSubject2->SubjectValue . '</span>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px;margin-right: 2px;margin-left:2px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span>');
              
              echo (($data->documentSubject3)? 
                      '<span class=\'label label-info\' '
                      . 'style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\' '
                      . 'title=\''.(($data->documentSubject3->subject3) ? $data->documentSubject3->subject3->SubjectName : '').'\'>'.
                      $data->documentSubject3->SubjectValue . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span><div class="clear"></div>');
              
              echo '<div style=\'width: 70px !important;float:left;\'>Додатково : </div>' . (($data->AdditionalBall)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      $data->AdditionalBall . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span><div class="clear"></div>');
              
              echo '<div style=\'width: 70px !important;float:left;\'>Курси : </div>' . (($data->CoursedpBall)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      $data->CoursedpBall . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span><div class="clear"></div>');
              
              echo '<div style=\'width: 70px !important;float:left;\'>Олімпіади : </div>' . (($data->olymp)? 
                      '<span class=\'label label-info\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      $data->olymp->OlympiadAwardBonus . '</span><div class="clear"></div>' : 
                
                      '<span class=\'label label-red\' style=\'margin-bottom: 3px; font-size: 8pt; font-family: Tahoma;\'>'.
                      'н/з' . '</span><div class="clear"></div>');

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
              //echo $data->Baldetail;
            }
        ),


    ),
    'htmlOptions' => array(
        'style' => 'font-size : 8pt;'
    )
));

?>