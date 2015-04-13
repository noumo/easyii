<?php
namespace yii\easyii\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\web\Response;

use yii\easyii\helpers\Image;
use yii\easyii\models;

class RedactorController extends \yii\easyii\components\Controller
{
    public $controllerNamespace = 'yii\redactor\controllers';
    public $defaultRoute = 'upload';
    public $uploadDir = '@webroot/uploads';
    public $uploadUrl = '/uploads';

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON
                ],
            ]
        ];
    }

    public function actionUpload($dir = '')
    {
        $fileInstance = UploadedFile::getInstanceByName('file');
        if ($fileInstance) {
            $file = Image::upload($fileInstance, $dir);
            if($file) {
                return $this->getResponse($file);
            }
        }
        return ['error' => 'Unable to save image file'];
    }

//    public function actionClipboard()
//    {
//        if (!Yii::$app->request->isAjax) {
//            throw new HttpException(403, 'This action allow only ajaxRequest');
//        }
//        $contentType = Yii::$app->request->post('contentType');
//        $data = Yii::$app->request->post('data');
//
//        $mimeTypes = require(Yii::getAlias('@yii/helpers/mimeTypes.php'));
//        $filename = substr(uniqid(md5(rand()), true), 0, 10) . '.' . (array_search($contentType, $mimeTypes) !== false) ? array_search($contentType, $mimeTypes) : 'png';
//
//        if ($contentType && $data) {
//            if (file_put_contents($this->getFilePath($filename), base64_decode($data))) {
//                return [
//                    'filelink' => $this->getUrl($filename),
//                    'filename' => $filename
//                ];
//            } else {
//                return ['error' => 'Unable to save file'];
//            }
//        }
//    }
//
//    public function actionImageGetJson()
//    {
//        if (!Yii::$app->request->isAjax) {
//            throw new HttpException(403, 'This action allow only ajaxRequest');
//        }
//
//        $filesPath = FileHelper::findFiles($this->getSaveDir(), [
//            'recursive' => true,
//            'only' => ['*.jpg', '*.jpeg', '*.jpe', '*.png', '*.gif']
//        ]);
//        if (is_array($filesPath) && count($filesPath)) {
//            $result = [];
//            foreach ($filesPath as $filePath) {
//                $url = $this->getUrl(pathinfo($filePath, PATHINFO_BASENAME));
//                $result[] = ['thumb' => $url, 'image' => $url];
//            }
//            return $result;
//        }
//    }
//
//    public function getOwnerPath()
//    {
//        return Yii::$app->user->isGuest ? 'guest' : Yii::$app->user->id;
//    }
//
//    public function getSaveDir()
//    {
//        if (preg_match('/^\@/', $this->uploadDir)) {
//            $path = Yii::getAlias($this->uploadDir);
//        } else {
//            $path = $this->uploadDir;
//        }
//        if (FileHelper::createDirectory($path . DIRECTORY_SEPARATOR . $this->getOwnerPath())) {
//            return $path . DIRECTORY_SEPARATOR . $this->getOwnerPath();
//        }
//        return '';
//    }
//
//    public function getFilePath($fileName)
//    {
//        return $this->getSaveDir() . DIRECTORY_SEPARATOR . $fileName;
//    }
//
//    public function getUrl($fileName)
//    {
//        return $this->uploadUrl . '/' . $this->getOwnerPath() . '/' . $fileName;
//    }

    private function getResponse($fileName)
    {
        return [
            'filelink' => $fileName,
            'filename' => basename($fileName)
        ];
    }
}