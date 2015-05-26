<?php
namespace yii\easyii\modules\carousel\api;

use yii\easyii\components\ApiObject;

class CarouselObject extends ApiObject
{
    public $image;
    public $link;
    public $title;
    public $text;
}