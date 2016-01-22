<?php
/**
 * Created by PhpStorm.
 * User: bk
 * Date: 17.10.15
 * Time: 10:35
 */

namespace yii\easyii\modules\content\controllers;

use yii\easyii\modules\content\models\Item;
use Yii;
use yii\easyii\components\Controller;

/**
 * Nav behavior. Adds nav to models
 * @package yii\easyii\behaviors
 */
class NavController extends Controller
{
    public function actionOn($id)
    {
        return $this->changeNav($id, Item::STATUS_ON);
    }

    public function actionOff($id)
    {
        return $this->changeNav($id, Item::STATUS_OFF);
    }

    public function changeNav($id, $status)
    {
        if(($model = Item::findOne($id))){
            $model->nav = $status;
            $model->update();
        }
        else{
            $this->error = Yii::t('easyii', 'Not found');
        }

        return $this->formatResponse(Yii::t('easyii', 'Status successfully changed'));
    }
}