<?php
namespace yii\easyii\helpers;

class GD
{
    private $_image;
    private $_mime;
    private $_width;
    private $_height;

    public function __construct($file)
    {
        if (file_exists($file)) {
            $imageData = getimagesize($file);
            $this->_mime = image_type_to_mime_type($imageData[2]);
            $this->_width = $imageData[0];
            $this->_height = $imageData[1];

            switch ($this->_mime) {
                case 'image/jpeg':
                    $this->_image = imagecreatefromjpeg($file);
                    break;
                case 'image/png':
                    $this->_image = imagecreatefrompng($file);
                    break;
                case 'image/gif':
                    $this->_image = imagecreatefromgif($file);
                    break;
            }
        }
    }

    public function resize($width = null, $height = null)
    {
        if(!$this->_image || (!$width && !$height)){
            return false;
        }

        if(!$width)
        {
            if ($this->_height > $height) {
                $ratio = $this->_height / $height;
                $newWidth = round($this->_width / $ratio);
                $newHeight = $height;
            } else {
                $newWidth = $this->_width;
                $newHeight = $this->_height;
            }
        }
        elseif(!$height)
        {
            if ($this->_width > $width) {
                $ratio = $this->_width / $width;
                $newWidth = $width;
                $newHeight = round($this->_height / $ratio);
            } else {
                $newWidth = $this->_width;
                $newHeight = $this->_height;
            }
        }
        else
        {
            $newWidth = $width;
            $newHeight = $height;
        }

        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resizedImage, false);

        imagecopyresampled(
            $resizedImage,
            $this->_image,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $this->_width,
            $this->_height
        );

        $this->_image = $resizedImage;
    }

    public function cropThumbnail($width, $height)
    {
        if(!$this->_image || !$width || !$height){
            return false;
        }

        $sourceRatio = $this->_width / $this->_height;
        $thumbRatio = $width / $height;

        $newWidth = $this->_width;
        $newHeight = $this->_height;

        if($sourceRatio !== $thumbRatio)
        {
            if($this->_width >= $this->_height){
                if($thumbRatio > 1){
                    $newHeight = $this->_width / $thumbRatio;
                    if($newHeight > $this->_height){
                        $newWidth = $this->_height * $thumbRatio;
                        $newHeight = $this->_height;
                    }
                } elseif($thumbRatio == 1) {
                    $newWidth = $this->_height;
                    $newHeight = $this->_height;
                } else {
                    $newWidth = $this->_height * $thumbRatio;
                }
            } else {
                if($thumbRatio > 1){
                    $newHeight = $this->_width / $thumbRatio;
                } elseif($thumbRatio == 1) {
                    $newWidth = $this->_width;
                    $newHeight = $this->_width;
                } else {
                    $newHeight = $this->_width / $thumbRatio;
                    if($newHeight > $this->_height){
                        $newHeight = $this->_height;
                        $newWidth = $this->_height * $thumbRatio;
                    }
                }
            }
        }

        $resizedImage = imagecreatetruecolor($width, $height);
        imagealphablending($resizedImage, false);

        imagecopyresampled(
            $resizedImage,
            $this->_image,
            0,
            0,
            round(($this->_width - $newWidth) / 2),
            round(($this->_height - $newHeight) / 2),
            $width,
            $height,
            $newWidth,
            $newHeight
        );

        $this->_image = $resizedImage;
    }

    public function save($file, $quality = 90)
    {
        switch($this->_mime) {
            case 'image/jpeg':
                return imagejpeg($this->_image, $file, $quality);
                break;
            case 'image/png':
                imagesavealpha($this->_image, true);
                return imagepng($this->_image, $file);
                break;
            case 'image/gif':
                return imagegif($this->_image, $file);
                break;
        }
        return false;
    }
}