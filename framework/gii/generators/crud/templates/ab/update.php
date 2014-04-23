<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

$this->menu=array(
	array('label'=>'Додати новий запис', 'url'=>array('create')),
	array('label'=>'Перегляд та редагування', 'url'=>array('admin')),
);
?>

<h1>Оновлення запису № <?php echo "<?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>