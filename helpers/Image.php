<?php
namespace yii\easyii\helpers;

use Yii;
use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\easyii\helpers\GD;

class Image
{
    public static function upload(UploadedFile $fileInstance, $dir = '', $resizeWidth = null, $resizeHeight = null, $resizeCrop = false)
    {
        $fileName = Upload::getUploadPath($dir) . DIRECTORY_SEPARATOR . Upload::getFileName($fileInstance);

        $uploaded = $resizeWidth
            ? self::copyResizedImage($fileInstance->tempName, $fileName, $resizeWidth, $resizeHeight, $resizeCrop)
            : $fileInstance->saveAs($fileName);

        if(!$uploaded){
            throw new HttpException(500, 'Cannot upload file "'.$fileName.'". Please check write permissions.');
        }

        return Upload::getLink($fileName);
    }

    static function createThumbnail($fileName, $width, $height = null, $crop = true)
    {
        $fileName = str_replace(Url::base(true), '', $fileName);
        
        $webRoot = Yii::getAlias('@webroot');
        if(!strstr($fileName, $webRoot)){
            $fileName = $webRoot . $fileName;
        }
        $thumbFolder = dirname($fileName) . DIRECTORY_SEPARATOR . ($width.($height ? 'x'.$height : ''));
        $thumbFile = $thumbFolder . DIRECTORY_SEPARATOR . basename($fileName);

        if(!FileHelper::createDirectory($thumbFolder)){
            throw new HttpException(500, 'Cannot create "'.$thumbFolder.'". Please check write permissions.');
        }
        return self::copyResizedImage($fileName, $thumbFile, $width, $height, $crop) ? Upload::getLink($thumbFile) : false;
    }

    static function copyResizedImage($inputFile, $outputFile, $width, $height = null, $crop = true)
    {
        if (extension_loaded('gd'))
        {
            $image = new GD($inputFile);

            if($height) {
                if($crop){
                    $image->cropThumbnail($width, $height);
                } else {
                    $image->resize($width, $height);
                }
            } else {
                $image->resize($width);
            }

            return $image->save($outputFile);
        }
        elseif(extension_loaded('imagick'))
        {
            $image = new \Imagick($inputFile);

            if($height && !$crop) {
                $image->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1, true);
            }
            else{
                $image->resizeImage($width, null, \Imagick::FILTER_LANCZOS, 1);
            }

            if($height && $crop){
                $image->cropThumbnailImage($width, $height);
            }

            return $image->writeImage($outputFile);
        }
        else {
            throw new HttpException(500, 'Please install GD or Imagick extension');
        }
        return false;
    }
}