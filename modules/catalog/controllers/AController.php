<?php
namespace yii\easyii\modules\catalog\controllers;

use yii\easyii\components\CategoryController;

class AController extends CategoryController
{
    public $categoryClass = 'yii\easyii\modules\catalog\models\Category';
    public $moduleName = 'catalog';
}