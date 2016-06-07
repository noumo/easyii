<?php
namespace yii\easyii\helpers;

use Yii;
use yii\easyii\models\Setting;
use yii\web\UploadedFile;
use yii\web\HttpException;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\easyii\helpers\GD;

class Image
{
    public static function upload(UploadedFile $fileInstance, $dir = '')
    {
        $fileName = Upload::getUploadPath($dir) . DIRECTORY_SEPARATOR . Upload::getFileName($fileInstance);

        $imageInfo = getimagesize($fileInstance->tempName);

        $maxWidth = (int)Setting::get('image_max_width');
        if($maxWidth > 0 && $imageInfo[0] > $maxWidth){
            $uploaded = self::resize($fileInstance->tempName, $fileName, Setting::get('image_max_width'));
        } else {
            $uploaded = $fileInstance->saveAs($fileName);
        }

        if(!$uploaded){
            throw new HttpException(500, 'Cannot upload file "'.$fileName.'". Please check write permissions. Also check GD and Imagick extensions.');
        }

        return $dir ? $dir . '/' . basename($fileName) : basename($fileName);
    }

    static function thumb($filename, $width = null, $height = null)
    {
        $filename = Upload::getAbsolutePath($filename);
        if(!is_file($filename)) {
            return '';
        }

        $info = pathinfo($filename);
        $thumbName = $info['filename'] . '-' . md5( filemtime($filename) . (int)$width . (int)$height) . '.' . $info['extension'];
        $thumbFile = Upload::getUploadPath('thumbs') . DIRECTORY_SEPARATOR . $thumbName;
        $thumbWebFile = Upload::getFileUrl('thumbs/' . $thumbName);
        if(file_exists($thumbFile)){
            return $thumbWebFile;
        }
        if($width && $height){
            $success = self::crop($filename, $thumbFile, $width, $height);
        } else {
            $success = self::resize($filename, $thumbFile, $width, $height);
        }
        return  $success ? $thumbWebFile : '';
    }

    static function crop($inputFile, $outputFile, $width, $height)
    {
        if(extension_loaded('imagick'))
        {
            $center = new \stojg\crop\CropBalanced($inputFile);
            $croppedImage = $center->resizeAndCrop($width, $height);

            return $croppedImage->writeimage($outputFile);
        }
        elseif (extension_loaded('gd'))
        {
            $image = new GD($inputFile);
            $image->cropThumbnail($width, $height);
            return $image->save($outputFile);
        }
        else {
            return false;
        }
    }

    static function resize($inputFile, $outputFile, $width = null, $height = null)
    {
        if(!$width && !$height){
            throw new HttpException(500, 'Width or Height must be set on resizing.');
        }

        if(extension_loaded('imagick'))
        {
            $image = new \Imagick($inputFile);
            $image->resizeImage($width, $height, \Imagick::FILTER_CUBIC, 0.5, ($width && $height));
            return $image->writeImage($outputFile);
        }
        elseif (extension_loaded('gd'))
        {
            $image = new GD($inputFile);
            $image->resize($width, $height);

            return $image->save($outputFile);
        }
        else {
            return false;
        }
    }
}