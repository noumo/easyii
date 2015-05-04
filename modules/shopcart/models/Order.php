<?php
namespace yii\easyii\modules\shopcart\models;

use Yii;
use yii\easyii\behaviors\CalculateNotice;
use yii\easyii\validators\EscapeValidator;

class Order extends \yii\easyii\components\ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_PENDING = 1;
    const STATUS_CONFIRMED = 2;
    const STATUS_DECLINED = 3;
    const STATUS_SENT = 4;
    const STATUS_RETURNED = 5;
    const STATUS_ERROR = 6;
    const STATUS_COMPLETED = 7;

    const SESSION_KEY = 'easyii_shopcart_at';

    public $good_count;

    public static function tableName()
    {
        return 'easyii_shopcart_orders';
    }

    public function rules()
    {
        return [
            [['name', 'address', 'phone', 'comment'], 'trim'],
            ['email', 'email'],
            ['name', 'string', 'max' => 32],
            ['address', 'string', 'max' => 1024],
            ['phone', 'string', 'max' => 32],
            ['phone', 'match', 'pattern' => '/^[\d\s-\+\(\)]+$/'],
            ['comment', 'string', 'max' => 1024],
            [['name', 'address', 'phone', 'comment'], EscapeValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('easyii', 'Name'),
            'email' => Yii::t('easyii', 'E-mail'),
            'address' => Yii::t('easyii/shopcart', 'Delivery address'),
            'phone' => Yii::t('easyii/shopcart', 'Phone'),
            'comment' => Yii::t('easyii/shopcart', 'Comment'),
        ];
    }

    public static function statusName($status)
    {
        $states = self::states();
        return !empty($states[$status]) ? $states[$status] : $status;
    }

    public static function states()
    {
        return [
            self::STATUS_NEW => Yii::t('easyii/shopcart', 'New'),
            self::STATUS_PENDING => Yii::t('easyii/shopcart', 'Pending'),
            self::STATUS_CONFIRMED => Yii::t('easyii/shopcart', 'Confirmed'),
            self::STATUS_DECLINED => Yii::t('easyii/shopcart', 'Declined'),
            self::STATUS_SENT => Yii::t('easyii/shopcart', 'Sent'),
            self::STATUS_RETURNED => Yii::t('easyii/shopcart', 'Returned'),
            self::STATUS_ERROR => Yii::t('easyii/shopcart', 'Error'),
            self::STATUS_COMPLETED => Yii::t('easyii/shopcart', 'Completed'),
        ];
    }

    public function getGoods()
    {
        return $this->hasMany(Good::className(), ['order_id' => 'order_id']);
    }

    public function getCost()
    {
        $total = 0;
        foreach($this->goods as $good){
            $total += $good->count * round($good->price * (1 - $good->discount / 100));
        }

        return $total;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert) {
                $this->access_token = Yii::$app->security->generateRandomString(32);
                $this->status = self::STATUS_NEW;
                $this->time = time();
            } else {
                if($this->oldAttributes['status'] == self::STATUS_NEW && $this->status == self::STATUS_PENDING){
                    $this->new = 1;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getGoods()->all() as $good){
            $good->delete();
        }
    }

    public function behaviors()
    {
        return [
            'cn' => [
                'class' => CalculateNotice::className(),
                'callback' => function(){
                    return self::find()->where(['new' => 1])->count();
                }
            ]
        ];
    }
}