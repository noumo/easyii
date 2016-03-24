<?php
namespace yii\easyii\modules\content;

use yii\easyii\modules\content\modules\contentElements\ContentElementModule;

class ContentModule extends \yii\easyii\components\Module
{
    public $defaultRoute = 'item/all';

    public $settings = [
        'layoutThumb' => true,
        'itemsInFolder' => false,

        'itemThumb' => true,
        'itemPhotos' => true,
        'itemDescription' => true,
        'itemSale' => true,
    ];

    public static $installConfig = [
        'title' => [
            'en' => 'Content',
            'ru' => 'содержание',
        ],
        'icon' => 'list-alt',
        'order_num' => 110,
    ];

    public $controllerMap = [
        'content-element' => 'yii\easyii\modules\content\modules\contentElements\controllers\ContentElementController',
    ];

    public function init()
    {
        parent::init();

        $id = 'contentElements';
        $module = new ContentElementModule($id, $this);
        ContentElementModule::setInstance($module);

        $this->setModule($id, $module);
    }
}