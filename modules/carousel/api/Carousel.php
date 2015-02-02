<?php
namespace yii\easyii\modules\carousel\api;

use Yii;
use yii\easyii\helpers\Data;
use yii\easyii\modules\carousel\models\Carousel as CarouselModel;

class Carousel extends \yii\easyii\components\API
{
    public $clientOptions = ['interval' => 5000];

    private $_items = [];

    public function init()
    {
        parent::init();

        $data = Data::cache(CarouselModel::CACHE_KEY, 3600, function(){
            return CarouselModel::find()->status(CarouselModel::STATUS_ON)->sort()->asArray()->all();
        });

        foreach($data as $item){
            $temp = [
                'content' => '<img src="'.$item['image'].'"/>',
                'caption' => ''
            ];
            if($item['title']){
                $temp['caption'] .= '<h3>'.$item['title'].'</h3>';
            }
            if($item['text']){
                $temp['caption'] .= '<p>'.$item['text'].'</p>';
            }
            $this->_items[] = $temp;
        }
    }

    public function api_widget()
    {
        if(!count($this->_items)){
            return '<a href="/admin/carousel/a/create" target="_blank">'.Yii::t('easyii/carousel/api', 'Create carousel').'</a>';
        }

        $widget = \yii\bootstrap\Carousel::widget([
            'options' => ['class' => 'slide', 'style' => 'width: '.Yii::$app->getModule('admin')->activeModules['carousel']->settings['imageWidth'].'px'],
            'clientOptions' => $this->clientOptions,
            'items' => $this->_items
        ]);

        return LIVE_EDIT ? $this->wrapLiveEdit($widget, null, ['tag' => 'div']) : $widget;
    }
}