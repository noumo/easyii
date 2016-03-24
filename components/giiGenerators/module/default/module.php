<?php
/**
 * This is the template for generating a module class file.
 */

/* @var $this yii\web\View */
/* @var $generator \yii\easyii\components\giiGenerators\module\Generator */

$className = $generator->moduleClass;
$pos = strrpos($className, '\\');
$ns = ltrim(substr($className, 0, $pos), '\\');
$className = substr($className, $pos + 1);

$baseName = $generator->baseID;
$baseClass = ltrim($generator->baseClass, '\\');

echo "<?php\n";
?>

namespace <?= $ns ?>;

class <?= $className ?> extends \<?= $baseClass ?>
{
	public static $NAME = '<?= $baseName ?>';

	public $controllerNamespace = '<?= $generator->getControllerNamespace() ?>';

	public function init()
	{
		parent::init();

		$this->basePath = '@easyii/modules/<?= $baseName ?>';
	}
}
