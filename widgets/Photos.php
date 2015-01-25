<?php
namespace yii\easyii\widgets;

use Yii;
use yii\base\Widget;
use yii\base\InvalidConfigException;

use yii\easyii\assets\ColorboxAsset;
use yii\easyii\assets\PhotosAsset;
use yii\easyii\models\Photo;

class Photos extends Widget
{
    public $module;
    public $item_id;

    public function init()
    {
        parent::init();

        if (empty($this->module)) {
            throw new InvalidConfigException('Required `module` param isn\'t set.');
        }
        if (empty($this->item_id)) {
            throw new InvalidConfigException('Required `item_id` param isn\'t set.');
        }
    }

    public function run()
    {
        $view = Yii::$app->getView();
        $view->registerJs('$(".colorbox").colorbox();');

        ColorboxAsset::register($view);
        PhotosAsset::register($view);

        $photos = Photo::find()->where(['module' => $this->module, 'item_id' => $this->item_id])->sort()->all();
        echo $this->render('photos', [
            'photos' => $photos
        ]);
    }

}