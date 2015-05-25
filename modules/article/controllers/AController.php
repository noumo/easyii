<?php
namespace yii\easyii\modules\article\controllers;

use yii\easyii\components\CategoryController;

class AController extends CategoryController
{
    /** @var string  */
    public $categoryClass = 'yii\easyii\modules\article\models\Category';

    /** @var string  */
    public $moduleName = 'article';
}