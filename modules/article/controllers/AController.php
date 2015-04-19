<?php
namespace yii\easyii\modules\article\controllers;

use yii\easyii\components\CategoryController;

class AController extends CategoryController
{
    public $categoryClass = 'yii\easyii\modules\article\models\Category';
    public $moduleName = 'article';
}