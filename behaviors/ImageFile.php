<?php
namespace yii\easyii\behaviors;

use Yii;
use yii\db\ActiveRecord;
use yii\easyii\components\Module;
use yii\easyii\helpers\Image;
use yii\easyii\helpers\Upload;
use yii\easyii\models\SeoText;
use yii\web\UploadedFile;

class ImageFile extends \yii\base\Behavior
{
    private $_model;

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    public function beforeValidate()
    {
        if(!empty($_FILES)){
            $this->owner->image_file = UploadedFile::getInstance($this->owner, 'image_file');
        }
    }

    public function beforeInsert()
    {
        $model = $this->owner;
        if ($model->image_file instanceof UploadedFile) {
            $model->image_file = Image::upload($model->image_file, Module::getModuleName(get_class($model)));
            if(!$model->image_file) {
                $model->image_file = !$model->isNewRecord ? '' : $model->oldAttributes['image_file'];
            }
        }
    }

    public function beforeUpdate()
    {
        if(!empty($_FILES)){
            $model = $this->owner;

            $model->image_file = UploadedFile::getInstance($model, 'image_file');
            if($model->image_file && $model->validate(['image_file'])){
                $model->image_file = Image::upload($model->image_file, Module::getModuleName(get_class($model)));
                if($model->oldAttributes['image_file']){
                    Upload::delete($model->oldAttributes['image_file']);
                }
            }
            else{
                $model->image_file = $model->oldAttributes['image_file'];
            }
        }
    }

    public function afterDelete()
    {
        if($this->owner->image_file){
            Upload::delete($this->owner->image_file);
        }
    }

    public function getImage()
    {
        return Upload::getFileUrl($this->owner->image_file);
    }

    public function getSeoText()
    {
        if(!$this->_model)
        {
            $this->_model = $this->owner->seo;
            if(!$this->_model){
                $this->_model = new SeoText([
                    'class' => get_class($this->owner),
                    'item_id' => $this->owner->primaryKey
                ]);
            }
        }

        return $this->_model;
    }
}