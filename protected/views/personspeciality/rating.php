<?php

/* @var $model Personspeciality */
/* @var $data CActiveDataProvider */
Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl().'/jui/css/base/jquery-ui.css');

?>
<style>
@font-face {
    font-family: Oreos;
    src: url("../css/oreos.ttf") format('truetype');
    font-weight:100;
}
  
  .ui-autocomplete {
    max-height: 200px;
    width: 400px;
    overflow-y: auto;
    font-size: 8pt;
    font-family: Verdana;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
/* IE 6 doesn't support max-height
* we use height instead, but this forces the menu to always be this tall
*/
  * html .ui-autocomplete {
    height: 200px;
  }
  
/* Request status styling */
  .req-status-0 {
    color: red;
    background-color: #DDDDEE;
    font-size: 8pt;
    font-family: Tahoma;
    font-weight: normal;
  }
  .req-status-1 {
    color: black;
    background-color: #DDDDEE;
    font-size: 8pt;
    font-family: Tahoma;
    font-weight: normal;
  }
  .req-status-2 {
    color: #EE5f5B;
    background-color: #DDDDEE;
    font-size: 8pt;
    font-family: Tahoma;
    font-weight: normal;
  }
  .req-status-3 {
    color: #800000;
    background-color: #DDDDEE;
    font-size: 8pt;
    font-family: Tahoma;
    font-weight: normal;
  }
  .req-status-4 {
    color: black;
    background-color: #DDDDEE;
    font-size: 8pt;
    font-family: Tahoma;
    font-weight: normal;
  }
  .req-status-5 {
    color: #298dcd;
    background-color: #DDDDEE;
    font-size: 8pt;
    font-family: Tahoma;
    font-weight: normal;
  }
  .req-status-6 {
    color: #c09853;
    background-color: #DDDDEE;
    font-size: 8pt;
    font-family: Tahoma;
    font-weight: normal;
  }
  .req-status-7 {
    color: green;
    background-color: #DDDDEE;
    font-size: 8pt;
    font-family: Tahoma;
    font-weight: normal;
  }
  .req-status-8 {
    color: black;
    background-color: #DDDDEE;
    font-size: 8pt;
    font-family: Tahoma;
    font-weight: normal;
  }
  .req-status-9 {
    color: #EE5f5B;
    background-color: #DDDDEE;
    font-size: 8pt;
    font-family: Tahoma;
    font-weight: normal;
  }
  .req-status-10 {
    color: red;
    background-color: #DDDDEE;
    font-size: 8pt;
    font-family: Tahoma;
    font-weight: normal;
  }
</style>
<script type="text/javascript">
$(function (){
  $('#rating-params-form').submit(function(){
    var is_excel = ($(this).attr('action') === '<?php echo Yii::app()->CreateUrl('/personspeciality/excelrating'); ?>');
    var spec_id = $('#hidden_spec_id').val();
    if ($('#Personspeciality_rating_order_mode').is(':checked') && !spec_id){
      alert('Спеціальність не обрана');
      return false;
    }
    if ($('#Personspeciality_rating_order_mode').is(':checked') && spec_id && is_excel){
      return true;
    }
    if ($('#Personspeciality_rating_order_mode').is(':checked') && spec_id && !is_excel){
      $.fn.yiiGridView.update('rating-grid', {
        data: $('#rating-params-form').serialize()
      });
      return false;
    }
    if (!$('#Personspeciality_rating_order_mode').is(':checked')){
      $.fn.yiiGridView.update('rating-grid', {
        data: $(this).serialize()
      });
    }
    return false;
  });
});

$(function (){
  $("#Personspeciality_SPEC").keydown(function (){
    $('#hidden_spec_id').val('');
    if ($('#Personspeciality_rating_order_mode').is(':checked')){
        $('#RatingButton').slideUp();
    }
    $('#RatingExcel').slideUp();
  });
  $("#Personspeciality_SPEC").autocomplete(
    {delay:1000, minLength:3, "showAnim":"fold",
      "source":"<?php echo Yii::app()->CreateUrl("/specialities/autocomplete"); ?>",
      "select": function(event,ui){ 
        $('#hidden_spec_id').val(ui.item.spec_id);
        if ($('#Personspeciality_rating_order_mode').is(':checked')){
          $('#RatingExcel').slideDown();
        }
          $('#RatingButton').slideDown();
      } });
});

$(function (){
  $('#Personspeciality_rating_order_mode').change(function (){
    if ($('#Personspeciality_rating_order_mode').is(':checked')){
      $('#Personspeciality_page_size').val('автоматично');
      $('#Personspeciality_page_size').attr('readonly',true);
      
      $('#Personspeciality_searchID').attr('readonly',true);
      $('#Facultets_FacultetFullName').attr('readonly',true);
      $('#Personspeciality_NAME').attr('readonly',true);
      $('#Benefit_BenefitName').attr('readonly',true);
      
      $('#Personspeciality_mistakes_only').slideUp();
      $('#Personspeciality_mistakes_only').attr('checked',false);
      $('#for_mistakes_only').slideUp();
      $('#Personspeciality_edbo_mode').slideUp();
      $('#Personspeciality_edbo_mode').attr('checked',false);
      $('#for_edbo_mode').slideUp();
      
      $('#statuses').slideDown();
      if (!$('#hidden_spec_id').val()){
        $('#RatingExcel').slideUp();
        $('#RatingButton').slideUp();
      } else {
        $('#RatingButton').slideDown();
        $('#RatingExcel').slideDown();
      }
    } else {
      $('#Personspeciality_page_size').val('15');
      $('#Personspeciality_page_size').attr('readonly',false);
      
      $('#Personspeciality_searchID').attr('readonly',false);
      $('#Facultets_FacultetFullName').attr('readonly',false);
      $('#Personspeciality_NAME').attr('readonly',false);
      $('#Benefit_BenefitName').attr('readonly',false);
      
      $('#Personspeciality_mistakes_only').slideDown();
      $('#for_mistakes_only').slideDown();
      $('#for_edbo_mode').slideDown();
      $('#Personspeciality_edbo_mode').slideDown();
      
      $('#statuses').slideUp();
      $('#RatingButton').slideDown();
      $('#RatingExcel').slideUp();
    }
  });
});

</script>

    <?php
    /* @var $form CActiveForm */
    $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'id' => 'rating-params-form',
    ));
    ?>
  <?php echo CHtml::link('Завантажити дані із ЄДЕБО --->',
          Yii::app()->CreateUrl('/edbodata/datauploader'),array(
              'target' => 'blank', 'style' => 'float: right;'
          )); ?>

<div class="well well-small row-fluid" style="width: 50%; margin: 0 auto;">
  <div class="span7">
    <span class="label label-info">нейтрально</span>
    <span class="label label-success">добре</span>
    <span class="label label-important">погано</span>
  </div>
  <div class="span5">
    <span style="font-size: 8pt; font-family: Tahoma; color: 0; padding: 4px;">
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
  
<?php echo $form->checkBox($model, 'rating_order_mode', array(
    'style' => 'float:left;margin-right: 10px;'
)); ?>
<?php echo $form->label($model, 'rating_order_mode', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;'
)); ?>
  
  <div id="statuses" style='display: none;'>
<?php echo $form->checkBox($model, 'status_confirmed', array(
    'style' => 'float:left;margin-right: 10px;', 'checked' => 'checked',
)); ?>
<?php echo $form->label($model, 'status_confirmed', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;'
)); ?>
<?php echo $form->checkBox($model, 'status_committed', array(
    'style' => 'float:left;margin-right: 10px;', 'checked' => 'checked',
)); ?>
<?php echo $form->label($model, 'status_committed', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;'
)); ?>
<?php echo $form->checkBox($model, 'status_submitted', array(
    'style' => 'float:left;margin-right: 10px;', 'checked' => 'checked',
)); ?>
<?php echo $form->label($model, 'status_submitted', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;'
)); ?>
    
    
  </div>

<?php echo $form->checkBox($model, 'mistakes_only', array(
    'style' => 'float:left;margin-right: 10px;'
)); ?>
<?php echo $form->label($model, 'mistakes_only', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;',
    'id' => 'for_mistakes_only',
)); ?>

  
<?php echo $form->checkBox($model, 'edbo_mode', array(
    'style' => 'float:left;margin-right: 10px;'
)); ?>
<?php echo $form->label($model, 'edbo_mode', array(
    'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;',
    'id' => 'for_edbo_mode',
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
    'style' => 'font-size: 7pt; font-family: Tahoma; height: 12px;',
));
echo $form->hiddenField($model, 'SepcialityID', array(
    'id' => 'hidden_spec_id',
));
?>
    </div>
  </div>
  
  <div class="span12">
    <center>
      <?php echo CHtml::link('інші параметри','#',
              array('onclick'=>'$("#another_parameters").slideToggle();return false;')); ?>
    </center>
    
  </div>
  <div class='row-fluid' style='display: none;' id='another_parameters'>
  <div class='span12'></div>
  <div class="span12">
    <div class="span6">
      <?php
      echo $form->label($model, 'searchID', array(
          'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;'
      ));
      ?>
      <?php
      echo $form->textField($model, 'searchID', array(
          'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px;'
      ));
      ?>
    </div>

    <div class="span6">
      <?php
      echo CHtml::label('Пошук по частині назви факультету', 
              CHtml::activeId($model->searchFaculty, 'FacultetFullName'), array(
          'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;'
      ));
      ?>
      <?php
      echo $form->textField($model->searchFaculty, 'FacultetFullName', array(
          'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px;',
      ));
      ?>
    </div>
  </div>
    <!-- ----- -->
  <div class='span12'>  
    <div class="span6">
      <?php
      echo $form->label($model, 'NAME', array(
          'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;'
      ));
      ?>
      <?php
      echo $form->textField($model, 'NAME', array(
          'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px;'
      ));
      ?>
    </div>

    <div class="span6">
      <?php
      echo CHtml::label('Пошук по частині назви пільги або їх число', 
              CHtml::activeId($model->searchBenefit, 'BenefitName'), array(
          'style' => 'font-size: 8pt; font-family: Tahoma; text-align: left;'
      ));
      ?>
      <?php
      echo $form->textField($model->searchBenefit, 'BenefitName', array(
          'style' => 'font-size: 8pt; font-family: Tahoma; height: 12px;',
      ));
      ?>
    </div>
  </div>
  </div>
  
  <div class="span12">
    <div class="span6">
    <?php
    $this->widget("bootstrap.widgets.TbButton", array(
              'buttonType'=>'submit',
              'type'=>'primary',
              "size"=>"small",
              "icon" => "eye-open white",
              'htmlOptions' => array(
                'id' => 'RatingButton',
                'class' => 'span9',
                'onclick' => '$(\'#rating-params-form\').attr(\'action\',\''.Yii::app()->createUrl($this->route).'\');'
                  . '$(\'#rating-params-form\').submit();return false;',
               ),
              'label'=>'Створити вибірку',
        )
    ); 
    ?>
    </div>
    <div class="span6">
    <?php
    $this->widget("bootstrap.widgets.TbButton", array(
              'buttonType'=>'link',
              'type'=>'primary',
              "size"=>"small",
              "icon"=>"file white",
              'htmlOptions' => array(
                'id' => 'RatingExcel',
                'class' => 'span9',
                'onclick' => '$(\'#rating-params-form\').attr(\'action\',\''.Yii::app()->CreateUrl('/personspeciality/excelrating').'\');'
                  . '$(\'#rating-params-form\').submit();return false;',
                'style' => 'display: none;',  
               ),
              'label'=>'Сформувати Excel-файл',
              'url' => '#'
        )
    ); 
    ?>
    </div>
  </div>
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
            'filter' => false,
            'htmlOptions' => array(
                'style' => 'width: 80px;'
            ),
            'headerHtmlOptions' => array(
                'style' => 'width: 80px;'
            ),
            'value' => function($data,$row){
              /* @var $data Personspeciality */
              if (!$row && Personspeciality::$is_rating_order){
                $_contract_counter = $data->sepciality->SpecialityContractCount;
                $_budget_counter = $data->sepciality->SpecialityBudgetCount;
                $_pzk_counter = $data->sepciality->Quota1;
                $_quota_counter = $data->sepciality->Quota2;
                Personspeciality::setCounters($_contract_counter, $_budget_counter, $_pzk_counter, $_quota_counter);
              }
?> <a href='#'  
     onclick="$('#row_<?php echo $row; ?>').slideToggle();return false;">
     <i class="icon-white icon-info-sign" style="background-color: #05B2D2; border-radius: 10px;"></i>
     <?php 
     
     $local_counter = 0;
     if ((Personspeciality::$is_rating_order) && $data->Quota1){
       //цільовики
       $was = Personspeciality::decrementCounter(Personspeciality::$C_QUOTA);
       if ($was){
         Personspeciality::decrementCounter(Personspeciality::$C_BUDGET);
         $local_counter = 1 + $data->sepciality->Quota2 - $was;
         echo '<span '
         . ' title="Місце у рейтингу цільового прийому за попередньою інформацією." '
         . ' style="color: #F89406; font-size: 12pt; font-family: Oreos;"> '
         . $local_counter
         . '</span>';
       } else {
         echo '<span '
         . ' title="Має право приймати участь у конкурсі, але не за цільовим прийомом." '
         . ' style="color: #CA0EE3;">'
         . 'Не проходить'
         . '</span>';
       }
     }
     
     if ((Personspeciality::$is_rating_order) && $data->isOutOfComp && !$data->Quota1){
       //поза конкурсом
       $was = Personspeciality::decrementCounter(Personspeciality::$C_OUTOFCOMPETITION);
       if ($was){
         Personspeciality::decrementCounter(Personspeciality::$C_BUDGET);
         $local_counter = 1 + $data->sepciality->Quota1 - $was;
         echo '<span '
         . ' title="Місце у рейтингу прийому поза конкурсом за попередньою інформацією." '
         . ' style="color: #05B2D2; font-size: 12pt; font-family: Oreos;"> '
         . $local_counter
         . '</span>';
       } else {
         echo '<span '
         . ' title="Має право приймати участь у конкурсі, але без права на позаконкурсний прийом." '
         . ' style="color: #CA0EE3;">'
         . 'Не проходить'
         . '</span>';
       }
     }
     
     if ((Personspeciality::$is_rating_order) && $data->isBudget && !$data->isOutOfComp && !$data->Quota1){
       //на бюджет
       $was = Personspeciality::decrementCounter(Personspeciality::$C_BUDGET);
       if ($was){
         $local_counter = 1 + $data->sepciality->SpecialityBudgetCount - $was;
         echo '<span '
         . ' title="Місце у рейтингу прийому за кошти держ. бюджету за попередньою інформацією." '
         . ' style="color: lightgreen; font-size: 12pt; font-family: Oreos;"> '
         . $local_counter
         . '</span>';
       }
     }
     
     if ((Personspeciality::$is_rating_order) && 
             ((!$data->isBudget && !$data->isOutOfComp && !$data->Quota1) || 
             (!$was && $data->isBudget && !$data->isOutOfComp && !$data->Quota1) )){
       //на контракт
       $was = Personspeciality::decrementCounter(Personspeciality::$C_CONTRACT);
       if ($was){
         $local_counter = 1 + $data->sepciality->SpecialityContractCount - $was;
         echo '<span '
         . ' title="Місце у рейтингу на контракт за попередньою інформацією." '
         . ' style="color: #ad6704; font-size: 12pt; font-family: Oreos;"> '
         . $local_counter
         . '</span>';
       } else {
         echo '<span '
         . ' title="...за попередньою інформацією." '
         . ' style="color: red;">'
         . 'Не проходить'
         . '</span>';
       }
     }
     //var_dump($data->rating_order_mode);
     if (!Personspeciality::$is_rating_order){
       echo $data->edboID;
     }
     ?></a> <?php
     
      $doc_orig = 'Копія';
      $is_orig = 0;
      if (!$data->isCopyEntrantDoc){
        $doc_orig = 'Оригінал';
        $is_orig = 1;
      }
      $span_class = 'label-info';
      if (($data->edbo)? ($data->edbo->OD == $is_orig): false){
        $span_class = 'label-success';
      }
      if (($data->edbo)? ($data->edbo->OD != $is_orig): false){
        $span_class = 'label-important';
      }
      echo '<span class=\'label '.$span_class.'\'>'.$doc_orig.
              '</span><br/>';
      echo 'статус заявки: <span class=\'label badge req-status-'.$data->StatusID.'\'>'
            . $data->status->PersonRequestStatusTypeName
            . '</span><hr style=\'margin: 5px !important;\'/>';
?> <div id='row_<?php echo $row; ?>' style='display:none; font-size:8pt;'> <?php
              echo 'id_заявки: <span class=\'label label-info\'>'.$data->idPersonSpeciality.
                      '</span><hr style=\'margin: 5px !important;\'/>';
              echo 'id_персони: <span class=\'label label-info\'>'.$data->PersonID.
                      '</span><hr style=\'margin: 5px !important;\'/>';
              echo 'id_ЄДЕБО: <span class=\'label label-info\'>'.$data->edboID.
                      '</span><hr style=\'margin: 5px !important;\'/>';
?> </div> <?php
            }
        ),

        array(
            'name' => 'facultet.FacultetFullName',
            'filter' => false,
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
            'filter' => false,
            'htmlOptions' => array(
                'style' => 'width: 150px;'
            ),
            'headerHtmlOptions' => array(
                'style' => 'width: 150px;'
            ),
            'value' => function ($data){
              /* @var $data Personspeciality */
              if (!$data->edbo && $data->edboID){
                $data->edbo = EdboData::model()->findByPk($data->edboID);
              }
              if ($data->edbo){
                $spec_code_ok = (strstr($data->SPEC,$data->edbo->SpecCode) !== FALSE);
                $speciality_ok = ($data->edbo->Speciality)? 
                        (strstr($data->SPEC,$data->edbo->Speciality) !== FALSE): true;
                $specialization_ok = ($data->edbo->Specialization)? 
                        (strstr($data->SPEC,$data->edbo->Specialization) !== FALSE): true;
                $edu_form_ok = (strstr($data->SPEC,$data->edbo->EduForm) !== FALSE);
                if ($spec_code_ok && $speciality_ok && $specialization_ok && $edu_form_ok){
                  echo "<span title='співпадає' style='color: green;'>"
                  . $data->SPEC
                  . "</span>";
                } else if (!$spec_code_ok){
                  echo "<span title='В ЄДЕБО код напряму: ".$data->edbo->SpecCode."' style='color: red;'>"
                  . $data->SPEC
                  . "</span>";
                } else if (!$speciality_ok){
                  echo "<span title='В ЄДЕБО спеціальність: ".$data->edbo->Speciality."' style='color: red;'>"
                  . $data->SPEC
                  . "</span>";
                } else if (!$specialization_ok){
                  echo "<span title='В ЄДЕБО спеціалізація: ".$data->edbo->Specialization."' style='color: red;'>"
                  . $data->SPEC
                  . "</span>";
                } else if (!$edu_form_ok){
                  echo "<span title='В ЄДЕБО форма навчання: ".$data->edbo->EduForm."' style='color: red;'>"
                  . $data->SPEC
                  . "</span>";
                }
              } else {
                echo $data->SPEC;
              }
            }
        ),
        
        array(
            'name' => 'NAME',
            'header' => 'ПІБ',
            'filter' => false,      
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
                  echo '<div style=\'color: #BBDDBB; font-size: 8pt;\''
                  . ' title=\'Такі дані в ЄДЕБО.\'>'.$data->edbo->PIB.'</div>';
                }
              }
              echo '<A HREF=\''.Yii::app()->createUrl('/person/'.$data->PersonID).'\' '
                      . ' TARGET="blank">'
                      . '<div style=\'color: '.$color.';\'>'.$data->NAME.'</div>'
                      . '</A>';
            }
                    
        ),
                
        array(
            'header' => 'Пільги',
            'name' => 'benefit.BenefitName',
            'filter' => false,
            'htmlOptions' => array(
                'style' => 'width: 130px;'
            ),
            'headerHtmlOptions' => array(
                'style' => 'width: 130px;'
            ),
            'value' => function ($data){
              /* @var $data Personspeciality */
              $_benefits =  explode(';;',$data->BenefitList);
              $id_benefits =  explode(';;',$data->idBenefitList);
              $is_out_of_comp_list = explode(';;', $data->isOutOfCompList);
              $is_extra_entry_list = explode(';;', $data->isExtraEntryList);
              $benefits = array();
              foreach ($_benefits as $id => $benefit){
                if (!$benefit){
                  continue;
                }
                $benefits[$id_benefits[$id]]['name'] = $benefit;
                $benefits[$id_benefits[$id]]['isPV'] = $is_extra_entry_list[$id];
                $benefits[$id_benefits[$id]]['isPZK'] = $is_out_of_comp_list[$id];
              }
              $cnt_benefits = count($benefits);
              if ($cnt_benefits == 1 && $benefits[$id_benefits[0]] == ''){
                return ;
              }

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
                  if ($benefit['isPV']){
                    $bgcolor = '#FFFFCC';
                    $title .= '(абітурієнт має право на ПЕРШОЧЕРГОВИЙ вступ)';
                  }
                  if ($benefit['isPZK']){
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
                  .  $benefit['name']
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
              
              $docvalues= explode(';',$data->DocValues);
              $doctypes = explode(';',$data->DocTypes);
              $points = array();
              
              $PointMap = Personspeciality::getPointMap();
              $DocTypes = Personspeciality::getPersonDocTypes();
              
              foreach ($doctypes as $id => $doctype){
                $points[$doctype]['val'] = $docvalues[$id];
                $points[$doctype]['type'] = $DocTypes[$doctype];
              }
              
              $is_elder = (mb_substr($data->sepciality->SpecialityClasifierCode,0,1,'utf-8') != '6');
              $doc_val = 0.0;
              $doc_val_zno = 0.0;
              $doc_name = 'Документ';
              $doc_desc = 'Документ';
              foreach ($points as $type => $point){
                if ($is_elder && !in_array($type,array(11,12))){
                  continue;
                }
                if ($point['val'] > 0.0){
                  $doc_val = round($point['val'],1);
                  if (isset($PointMap[(string)$doc_val])){
                    $doc_val_zno = $PointMap[(string)$doc_val];
                  }
                  else {
                    $doc_val_zno = $doc_val;
                  } 
                  $doc_desc = $point['type'];
                  break;
                }
              }

              $Total += $doc_val_zno;
              $Total += (($data->documentSubject1)? (float)$data->documentSubject1->SubjectValue : 0.0);
              $Total += (($data->documentSubject2)? (float)$data->documentSubject2->SubjectValue : 0.0);
              $Total += (($data->documentSubject3)? (float)$data->documentSubject3->SubjectValue : 0.0);
              $Total += (float)$data->AdditionalBall;
              $Total += (float)$data->CoursedpBall;
              $Total += ($data->olymp? (float)$data->olymp->OlympiadAwardBonus : 0.0);
              $Total += (float)$data->Exam1Ball;
              $Total += (float)$data->Exam2Ball;
              $Total += (float)$data->Exam3Ball;
              
              if ($data->Quota1){
                $span_class = 'label-info';
                $info_title = '';
                if ($data->edbo){
                  $span_class = ($data->Quota1 != '0' && $data->edbo->Quota == '1')? 
                          'label-success' : 'label-important';
                  $info_title = ($data->Quota1 != '0' && $data->edbo->Quota == '1')?
                          "" : 'В даних ЄДЕБО цей параметр ВІДСУТНІЙ';
                }
                echo ' ' 
                      . '<span class=\'label '.$span_class.'\' style=\'margin-bottom: 3px;'
                      . ' font-size: 8pt; font-family: Tahoma; padding: 4px;\''
                      . ' title=\''.$info_title.'\'>'
                      . 'Цільовий вступ'
                      .'</span>'
                      .'<div class="clear"></div>' ;            
              } else if ((isset($data->edbo->Quota)? ($data->edbo->Quota == '1') : false)){
                echo "<div style=\"color:red;\" title='У Абітурієнті відсутня'>"
                . "В ЄДЕБО є відмітка цільового вступу. </div>";

              }
              
              if ($data->isOutOfComp){
                $span_class = 'label-info';
                $info_title = '';
                if ($data->edbo){
                  $span_class = ($data->isOutOfComp != '0' && $data->edbo->Benefit == '1')? 
                          'label-success' : 'label-important';
                  $info_title = ($data->isOutOfComp != '0' && $data->edbo->Benefit == '1')?
                          "" : 'В даних ЄДЕБО цей параметр ВІДСУТНІЙ';
                }
                echo ' ' 
                      . '<span class=\'label '.$span_class.'\' style=\'margin-bottom: 3px;'
                      . ' font-size: 8pt; font-family: Tahoma; padding: 4px;\''
                      . ' title=\''.$info_title.'\'>'
                      . 'Поза конкурсом'
                      .'</span>'
                      .'<div class="clear"></div>' ;            
              } else if ((isset($data->edbo->Benefit)? ($data->edbo->Benefit == '1') : false)){
                echo "<div style=\"color:red;\" title='У Абітурієнті відсутня'>"
                . "В ЄДЕБО є відмітка вступу поза конкурсом. </div>";

              }
              
              if ($data->isExtraEntry){
                $span_class = 'label-info';
                $info_title = '';
                if ($data->edbo){
                  $span_class = ($data->isExtraEntry != '0' && $data->edbo->PriorityEntry == '1')? 
                          'label-success' : 'label-important';
                  $info_title = ($data->isExtraEntry != '0' && $data->edbo->PriorityEntry == '1')?
                          '' : 'В даних ЄДЕБО цей параметр ВІДСУТНІЙ';
                }
                echo ' ' 
                      . '<span class=\'label '.$span_class.'\' style=\'margin-bottom: 3px;'
                      . ' font-size: 8pt; font-family: Tahoma; padding: 4px;\''
                      . ' title=\''.$info_title.'\'>'
                      . 'Першочерговий вступ'
                      .'</span>'
                      .'<div class="clear"></div>' ;            
              } else if ((isset($data->edbo->PriorityEntry)? ($data->edbo->PriorityEntry == '1') : false)){
                echo "<div style=\"color:red;\" title='У Абітурієнті відсутня'>"
                . "В ЄДЕБО є відмітка першочергового вступу. </div>";
              }


              
              $span_class = 'label-info';
              $add_string = '';
              if ($data->edbo){
                $span_class = ((float)$data->edbo->RatingPoints == (float)$Total)? 
                        'label-success' : 'label-important';
                $add_string = ' (в даних ЄДЕБО: '. $data->edbo->RatingPoints .')';
              }
              $add_string .= ' | computed: '.$data->ComputedPoints;
              echo '<div style=\'width: 70px !important;float:left;\'>Разом : </div>' 
                      . '<a href=\'#\' '
                      . ' style=\'margin-left: 5px;\''
                      . ' onclick=\'$("#id_'.$data->idPersonSpeciality.'").slideToggle(); return false;\''
                      . ' title=\''.$add_string.'\'>'
                      . '<span class=\'label '.$span_class.'\' style=\'margin-bottom: 3px;'
                      . ' font-size: 10pt; font-family: Tahoma; padding: 4px;\''
                      . ' >'
                      . '<i class=\'icon-white icon-info-sign\'></i> '
                      . $Total
                      .'</span>'
                      . '</a><div class="clear"></div>' ;
              
?> <div style="display:none;" id="id_<?php echo $data->idPersonSpeciality; ?>">  <?php
              $span_class = 'label-info';
              $add_string = '';
              if ($data->edbo){
                $span_class = ((float)$data->edbo->DocPoint == (float)$doc_val)?
                        'label-success' : 'label-important';
                $add_string = ' (в даних ЄДЕБО: '. $data->edbo->DocPoint . ')';
              }
              
              echo '<div style=\'width: 70px !important;float:left;\' title=\''.$doc_desc.'\'>'.$doc_name.' : </div>' . (($doc_val_zno)? 
                      '<span class=\'label '.$span_class.'\' style=\'margin-bottom: 3px;font-size: 8pt;\''
                      . ' title="Значення в документі : '.$doc_val . $add_string . '">'.
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
?> </div><div clas='clear'></div>  <?php
            }
        ),


    ),
    'htmlOptions' => array(
        'style' => 'font-size : 8pt;'
    )
));

?>