<?php
namespace yii\easyii\controllers;

use Yii;
use yii\easyii\actions\DeleteAction;
use yii\easyii\actions\SortByNumAction;
use yii\easyii\components\Module;
use yii\easyii\helpers\Upload;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\easyii\helpers\Image;
use yii\easyii\components\Controller;
use yii\easyii\models\Photo;

class PhotosController extends Controller
{
    public $modelClass = 'yii\easyii\models\Photo';

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'delete' => [
                'class' => DeleteAction::className(),
                'successMessage' => Yii::t('easyii', 'Photo deleted')
            ],
            'up' => [
                'class' => SortByNumAction::className(),
                'addititonalEquality' => ['class', 'item_id']
            ],
            'down' => [
                'class' => SortByNumAction::className(),
                'addititonalEquality' => ['class', 'item_id']
            ],
        ];
    }

    public function actionUpload($class, $item_id)
    {
        $success = null;

        $photo = new Photo;
        $photo->class = $class;
        $photo->item_id = $item_id;
        $photo->image_file = UploadedFile::getInstance($photo, 'image_file');

        if ($photo->image_file && $photo->validate(['image_file'])) {
            $photo->image_file = Image::upload($photo->image_file, Module::getModuleName($class));

            if ($photo->image_file) {
                if ($photo->save()) {
                    $success = [
                        'message' => Yii::t('easyii', 'Photo uploaded'),
                        'photo' => [
                            'id' => $photo->primaryKey,
                            'image' => $photo->image,
                            'thumb' => Image::thumb($photo->image_file, Photo::PHOTO_THUMB_WIDTH, Photo::PHOTO_THUMB_HEIGHT),
                            'description' => ''
                        ]
                    ];
                } else {
                    Upload::delete($photo->image_file);
                    $this->error = Yii::t('easyii', 'Create error. {0}', $photo->formatErrors());
                }
            } else {
                $this->error = Yii::t('easyii', 'File upload error. Check uploads folder for write permissions');
            }
        } else {
            $this->error = Yii::t('easyii', 'File is incorrect');
        }

        return $this->formatResponse($success);
    }

    public function actionDescription($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post('description')) {
            $model->description = Yii::$app->request->post('description');
            if (!$model->update()) {
                $this->error = Yii::t('easyii', 'Update error. {0}', $model->formatErrors());
            }
        } else {
            $this->error = Yii::t('easyii', 'Bad response');
        }

        return $this->formatResponse(Yii::t('easyii', 'Photo description saved'));
    }

    public function actionImage($id)
    {
        $success = null;
        $photo = $this->findModel($id);

        $oldImage = $photo->image_file;
        $photo->image_file = UploadedFile::getInstance($photo, 'image_file');

        if ($photo->image_file && $photo->validate(['image_file'])) {
            $photo->image_file = Image::upload($photo->image_file, 'photos');
            if ($photo->image_file) {
                if ($photo->save()) {
                    Upload::delete($oldImage);

                    $success = [
                        'message' => Yii::t('easyii', 'Photo uploaded'),
                        'photo' => [
                            'image' => $photo->image,
                            'thumb' => Image::thumb($photo->image_file, Photo::PHOTO_THUMB_WIDTH, Photo::PHOTO_THUMB_HEIGHT)
                        ]
                    ];
                } else {
                    Upload::delete($photo->image_file);

                    $this->error = Yii::t('easyii', 'Update error. {0}', $photo->formatErrors());
                }
            } else {
                $this->error = Yii::t('easyii', 'File upload error. Check uploads folder for write permissions');
            }
        } else {
            $this->error = Yii::t('easyii', 'File is incorrect');
        }

        return $this->formatResponse($success);
    }
}