<?php
namespace yii\easyii\modules\gallery\models;

use Yii;

use yii\easyii\behaviors\SortableModel;
use yii\easyii\models\Photo;

class Album extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public $photo_count;

    public static function tableName()
    {
        return 'easyii_gallery_albums';
    }

    public static function findWithPhotoCount()
    {
        return self::find()
            ->select([self::tableName().'.*', 'COUNT(p.photo_id) as photo_count'])
            ->join('LEFT JOIN', ['p' => Photo::find()->where(['module' => 'gallery'])], self::tableName().'.album_id = p.item_id')
            ->groupBy(self::tableName().'.album_id');
    }

    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'trim'],
            ['thumb', 'image'],
            ['slug',  'match', 'pattern' => '/^[a-zA-Z][\w_-]*$/'],
            ['slug', 'unique'],
            ['slug', 'default', 'value' => null]
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'thumb' => Yii::t('easyii', 'Image'),
            'slug' => Yii::t('easyii', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->status = self::STATUS_ON;
            }
            return true;
        } else {
            return false;
        }
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'album_id'])->where(['module' => 'gallery'])->sort();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getPhotos()->all() as $photo){
            $photo->delete();
        }

        if($this->thumb){
            @unlink(Yii::getAlias('@webroot').$this->thumb);
        }
    }
}