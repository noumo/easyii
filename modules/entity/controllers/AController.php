<?php
namespace yii\easyii\modules\entity\controllers;

use yii\easyii\components\CategoryController;

class AController extends CategoryController
{
    public $categoryClass = 'yii\easyii\modules\entity\models\Category';
    public $moduleName = 'entity';
}