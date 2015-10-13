<?php
namespace yii\easyii\modules\shopcart\controllers;

use Yii;
use yii\easyii\actions\DeleteAction;
use yii\easyii\components\Controller;
use yii\easyii\modules\shopcart\models\Good;

class GoodsController extends Controller
{
    public function actions()
    {
        return [
            'delete' => [
                'class' => DeleteAction::className(),
                'model' => Good::className(),
                'successMessage' => Yii::t('easyii/shopcart', 'Item deleted')
            ]
        ];
    }
}