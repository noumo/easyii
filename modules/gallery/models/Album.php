<?php
namespace yii\easyii\modules\gallery\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\SeoBehavior;
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
            ->join('LEFT JOIN', ['p' => Photo::find()->where(['model' => Album::className()])], self::tableName().'.album_id = p.item_id')
            ->groupBy(self::tableName().'.album_id');
    }

    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'trim'],
            ['title', 'string', 'max' => 128],
            ['image', 'image'],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['slug', 'unique', 'when' => function($model){
                return $model->slug && !self::autoSlug();
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'image' => Yii::t('easyii', 'Image'),
            'slug' => Yii::t('easyii', 'Slug'),
        ];
    }

    public function behaviors()
    {
        return [
            SortableModel::className(),
            'seo' => SeoBehavior::className(),
        ];
    }

    public function beforeValidate()
    {
        if(self::autoSlug() && (!$this->isNewRecord || ($this->isNewRecord && $this->slug == ''))){
            $this->attachBehavior('sluggable', [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true
            ]);
        }
        return parent::beforeValidate();
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'album_id'])->where(['model' => Album::className()])->sort();
    }

    public function afterDelete()
    {
        parent::afterDelete();

        foreach($this->getPhotos()->all() as $photo){
            $photo->delete();
        }

        if($this->image){
            @unlink(Yii::getAlias('@webroot').$this->image);
        }
    }

    public static function autoSlug()
    {
        return Yii::$app->getModule('admin')->activeModules['gallery']->settings['autoSlug'];
    }
}