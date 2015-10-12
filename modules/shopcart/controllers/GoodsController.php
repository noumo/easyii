<?php
namespace yii\easyii\modules\shopcart\controllers;

use Yii;

use yii\easyii\behaviors\CommonActions;
use yii\easyii\components\Controller;
use yii\easyii\modules\shopcart\models\Good;

class GoodsController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => CommonActions::className(),
                'model' => Good::className(),
            ],
        ];
    }
    public function actionDelete($id)
    {
        return $this->deleteModel($id, Yii::t('easyii/shopcart', 'Item deleted'));
    }
}