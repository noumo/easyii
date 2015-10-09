<?php
namespace yii\easyii\modules\news\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\easyii\behaviors\SeoBehavior;
use yii\easyii\behaviors\Taggable;
use yii\easyii\helpers\Upload;
use yii\easyii\models\Photo;
use yii\easyii\modules\news\NewsModule;
use yii\helpers\StringHelper;

/**
 * @property integer $news_id
 * @property string $title
 * @property string $short
 * @property string $text
 * @property string $image_file
 * @property string $slug
 * @property integer $time
 * @property integer $views
 * @property integer $status
 *
 * @property string $image
 */

class News extends \yii\easyii\components\ActiveRecord
{
    const STATUS_OFF = 0;
    const STATUS_ON = 1;

    public static function tableName()
    {
        return 'easyii_news';
    }

    public function rules()
    {
        return [
            [['text', 'title'], 'required'],
            [['title', 'short', 'text'], 'trim'],
            ['title', 'string', 'max' => 128],
            ['image_file', 'image'],
            [['views', 'time', 'status'], 'integer'],
            ['time', 'default', 'value' => time()],
            ['slug', 'match', 'pattern' => self::$SLUG_PATTERN, 'message' => Yii::t('easyii', 'Slug can contain only 0-9, a-z and "-" characters (max: 128).')],
            ['slug', 'default', 'value' => null],
            ['status', 'default', 'value' => self::STATUS_ON],
            ['tagNames', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('easyii', 'Title'),
            'text' => Yii::t('easyii', 'Text'),
            'short' => Yii::t('easyii/news', 'Short'),
            'image' => Yii::t('easyii', 'Image'),
            'time' => Yii::t('easyii', 'Date'),
            'slug' => Yii::t('easyii', 'Slug'),
            'tagNames' => Yii::t('easyii', 'Tags'),
        ];
    }

    public function behaviors()
    {
        return [
            'seoBehavior' => SeoBehavior::className(),
            'taggabble' => Taggable::className(),
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => NewsModule::setting('slugImmutable')
            ],
        ];
    }

    public function getPhotos()
    {
        return $this->hasMany(Photo::className(), ['item_id' => 'news_id'])->where(['class' => self::className()])->sort();
    }

    public function getImage()
    {
        return Upload::getLink($this->image_file);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->short = StringHelper::truncate(NewsModule::setting('enableShort') ? $this->short : strip_tags($this->text), NewsModule::setting('shortMaxLength'));

            if(!$insert && $this->image_file != $this->oldAttributes['image_file'] && $this->oldAttributes['image_file']){
                Upload::delete($this->oldAttributes['image_file']);
            }
            return true;
        } else {
            return false;
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        if($this->image_file){
            Upload::delete($this->image_file);
        }

        foreach($this->getPhotos()->all() as $photo){
            $photo->delete();
        }
    }
}