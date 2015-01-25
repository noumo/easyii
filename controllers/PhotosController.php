<?php
namespace yii\easyii\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\web\Response;

use yii\easyii\helpers\Image;
use yii\easyii\components\Controller;
use yii\easyii\models\Photo;
use yii\easyii\behaviors\SortableController;

class PhotosController extends Controller
{
    public $defaultSettings = [
        'photoThumbWidth' => 100,
        'photoThumbHeight' => 100,
        'photoThumbCrop' => true,
    ];

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ],
            ],
            [
                'class' => SortableController::className(),
                'model' => Photo::className(),
            ]
        ];
    }

    public function actionUpload($module, $item_id)
    {
        $success = null;
        $model = new Photo;
        $model->module = $module;
        $model->item_id = $item_id;
        $model->image = UploadedFile::getInstance($model, 'image');

        $settings = array_merge($this->defaultSettings, Yii::$app->getModule('admin')->activeModules[$module]->settings);

        if($model->image && $model->validate(['image'])){
            $model->image = Image::upload($model->image, $module);
            if($model->image){
                $model->thumb = Image::createThumbnail($model->image, $settings['photoThumbWidth'], $settings['photoThumbHeight'], $settings['photoThumbCrop']);
                if($model->save()){
                    $success = [
                        'message' => Yii::t('easyii', 'Photo uploaded'),
                        'photo' => [
                            'id' => $model->primaryKey,
                            'thumb' => $model->thumb,
                            'image' => $model->image,
                            'description' => ''
                        ]
                    ];
                }
                else{
                    @unlink(Yii::getAlias('@webroot').$model->image);
                    @unlink(Yii::getAlias('@webroot').$model->thumb);
                    $this->error = Yii::t('easyii', 'Create error. {0}', $model->formatErrors());
                }
            }
            else{
                $this->error = Yii::t('easyii', 'File upload error. Check uploads folder for write permissions');
            }
        }
        else{
            $this->error = Yii::t('easyii', 'File is incorrect');
        }

        return $this->formatResponse($success);
    }

    public function actionDescription($id)
    {
        if(($model = Photo::findOne($id)))
        {
            if(Yii::$app->request->post('description'))
            {
                $model->description = Yii::$app->request->post('description');
                if(!$model->update()) {
                    $this->error = Yii::t('easyii', 'Update error. {0}', $model->formatErrors());
                }
            }
            else{
                $this->error = Yii::t('easyii', 'Bad response');
            }
        }
        else{
            $this->error = Yii::t('easyii', 'Not found');
        }

        return $this->formatResponse(Yii::t('easyii', 'Photo description saved'));
    }

    public function actionImage($id)
    {
        $success = null;

        if(($model = Photo::findOne($id)))
        {
            $oldImage = $model->image;
            $oldThumb = $model->thumb;

            $model->image = UploadedFile::getInstance($model, 'image');

            $settings = array_merge($this->defaultSettings, Yii::$app->getModule('admin')->activeModules[$model->module]->settings);

            if($model->image && $model->validate(['image'])){
                $model->image = Image::upload($model->image, $model->module);
                if($model->image){
                    $model->thumb = Image::createThumbnail($model->image, $settings['photoThumbWidth'], $settings['photoThumbHeight'], $settings['photoThumbCrop']);
                    if($model->save()){
                        @unlink(Yii::getAlias('@webroot').$oldImage);
                        @unlink(Yii::getAlias('@webroot').$oldThumb);

                        $success = [
                            'message' => Yii::t('easyii', 'Photo uploaded'),
                            'photo' => [
                                'thumb' => $model->thumb,
                                'image' => $model->image
                            ]
                        ];
                    }
                    else{
                        @unlink(Yii::getAlias('@webroot').$model->image);
                        @unlink(Yii::getAlias('@webroot').$model->thumb);

                        $this->error = Yii::t('easyii', 'Update error. {0}', $model->formatErrors());
                    }
                }
                else{
                    $this->error = Yii::t('easyii', 'File upload error. Check uploads folder for write permissions');
                }
            }
            else{
                $this->error = Yii::t('easyii', 'File is incorrect');
            }

        }
        else{
            $this->error =  Yii::t('easyii', 'Not found');
        }

        return $this->formatResponse($success);
    }

    public function actionDelete($id)
    {
        if(($model = Photo::findOne($id))){
            $model->delete();
        } else{
            $this->error = Yii::t('easyii', 'Not found');
        }
        return $this->formatResponse(Yii::t('easyii', 'Photo deleted'));
    }

    public function actionUp($id, $module, $item_id)
    {
        return $this->move($id, 'up', ['module' => $module, 'item_id' => $item_id]);
    }

    public function actionDown($id, $module, $item_id)
    {
        return $this->move($id, 'down', ['module' => $module, 'item_id' => $item_id]);
    }
}