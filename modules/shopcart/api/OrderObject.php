<?php
namespace yii\easyii\modules\shopcart\api;

use yii\easyii\components\API;
use yii\helpers\Url;

class OrderObject extends \yii\easyii\components\ApiObject
{
    public $name;
    public $address;
    public $phone;
    public $email;
}