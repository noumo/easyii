<?php
namespace yii\easyii\modules\catalog\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\behaviors\SortableModel;
use yii\easyii\components\ActiveRecord;
use yii\easyii\models\Photo;

class ItemData extends ActiveRecord
{

    public static function tableName()
    {
        return 'easyii_catalog_item_data';
    }
}