<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
/* @var $this CrudCode */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

$this->menu=array(
  array('label'=>'Додати новий запис', 'url'=>array('create')),
);

<?php echo "\n?>\n"; ?>

<h1><?php
$labname = $this->pluralize($this->class2name($this->modelClass));
echo $labname;
/*echo TranslateModelName::getTranstalion($labname)*/
?></h1>


<?php echo "<?php"; ?> 
$this->widget('bootstrap.widgets.TbGridView', array(
  'id' => '<?php echo $this->class2id($this->modelClass); ?>-grid',
  'dataProvider'=>$model->search(),
  'filter'=>$model,
  'columns'=>array(
<?php
$count = 0;
foreach ($this->tableSchema->columns as $column) {
    if (++$count == 7){
      echo "    /*\n";
    }
    if ($column->name == 'Visible') {
        echo "    " ."array('name'=>'Visible',\n
                    '      header'=>'Відображати при виборі',\n
                    '      filter'=>array('1'=>'так','0'=>'ні'),\n
                    '      value'=>'(\$data->Visible=='1')?('так'):('ні')')".",\n";
    } else {
        echo "    '" . $column->name . "',\n";
    }
}
if ($count >= 7){
    echo "    */\n";
}
?>
    array(
      'class'=>'bootstrap.widgets.TbButtonColumn',
    ),
  ),
)); ?>
