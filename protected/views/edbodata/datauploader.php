<?php
/* @var $data_items array */
/* @var $model EdboData */
?>
<h3>Завантаження даних ЄДЕБО із CSV файла</h3>
<div class="span12">
  <div class="span6">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'type' => 'primary',
        'size' => 'small',
        'icon' => 'info-sign',
        'label' => 'Регулярна структура таблиці ЄДЕБО',
        'htmlOptions' => array(
            'onclick' => '$("#edbo_info_table").toggle();return false;'
        ),
        'url' => '#'
    ));
    ?>
    <TABLE 
      style="border-collapse: collapse;font-size: 8pt;font-family: Tahoma; display: none;" 
      border=4
      cellspacing="0"
      id="edbo_info_table">
      <TR><TD colspan=4 style="text-align: center; font-size: 10pt;">Зараз таблиця даних ЄДЕБО має таку 
          <span style="color: red;">регулярну</span> структуру</TD></TR>
      <TR><TH style="border: 3px solid black;">#</TH>
        <TH style="border: 3px solid black;">Стовпець</TH>
        <TH style="border: 3px solid black;">Назва в базі даних</TH>
        <TH style="border: 3px solid black;">Тип</TH>
      </TR>
      <?php
      $i = 0;
      foreach ($data_items as $data_item) {
        $field = $data_item['Comment'];
        $db_name = $data_item['Field'];
        $type = 'Текст';
        if (strstr($data_item['Type'], 'int') !== FALSE) {
          $type = 'Ціле число';
        }
        if (strstr($data_item['Type'], 'float') !== FALSE) {
          $type = 'Число з плаваючою комою';
        }
        if (strstr($data_item['Type'], 'varchar') !== FALSE) {
          $type = 'Рядок символів';
        }
        $bgcolor = 'white';
        if (!($i % 2)){
          $bgcolor = '#CCDDCC';
        }
        echo '<TR style=\'background-color: '.$bgcolor.'\'>'
                . '<TD style=\' border: 3px solid black;\'>' . ( ++$i) . '</TD>';
        echo '<TD style=\'padding: 0px; padding-left: 10px;'
        . 'font-family: Verdana; font-size: 11pt; border: 3px solid black;\'><I><B>' . $field . '</B></I></TD>'
        . '<TD style=\'padding: 0px; padding-left: 10px; border: 3px solid black;\'>' . $db_name . '</TD>'
        . '<TD style=\'padding: 0px; padding-left: 10px; border: 3px solid black;\'>' . $type . '</TD>';
        echo '</TR>';
      }
      ?>
    </TABLE>
  </div>
  <div class="span5">
    Завантаження CSV-файлу з даними ЄДЕБО.
    <?php
      $this->widget('bootstrap.widgets.TbFileUpload', array(
              'url' => $this->createUrl('/edbodata/upload'),
              'imageProcessing' => true,
              'name' => 'csv_file',
              'multiple' => false,
              'model' => $model,
              'attribute' => 'csv_file', // see the attribute?
              'multiple' => true,
              'options' => array(
                'maxFileSize' => 200000000,
                'acceptFileTypes' => 'js:/(\.|\/)(csv)$/i',
          )));
    ?>
  </div>
</div>