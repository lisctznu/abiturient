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
	array('label'=>'Перегляд та редагування', 'url'=>array('admin')),
);
?>

<h1>Створення нового запису <!--<?php echo $this->modelClass; ?>--></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
