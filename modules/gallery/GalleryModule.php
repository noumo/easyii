<?php
namespace yii\easyii\modules\gallery;

class GalleryModule extends \yii\easyii\components\Module
{
    public $settings = [
        'thumbWidth' => 100,
        'thumbHeight' => 100,
        'thumbCrop' => true,

        'albumThumb' => true,
        'albumThumbWidth' => 100,
        'albumThumbHeight' => 100,
        'albumThumbCrop' => true,

        'autoSlug' => true
    ];
}