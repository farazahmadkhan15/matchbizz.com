<?php

namespace App\Libraries;
define('THUMBNAIL_IMAGE_MAX_WIDTH', 150);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 150);

class ImageUploader
{
    public function upload($path, $imageName, $image)
    {
        if(!is_dir($path)){
            mkdir($path);
        }
        $pathImage = $path.DIRECTORY_SEPARATOR.$imageName;
        try{
            if (!$image->moveTo($pathImage)){
                return false;
            }
        }catch(\Exception $e)
        {
            return false;
        }
        return $pathImage;
    }

    public function thumbnail($pathImage,$path,$imageName)
    {
        $pathThumbnail = $path.DIRECTORY_SEPARATOR.$imageName;
        if (!$this->generate_image_thumbnail($pathImage,$pathThumbnail)){
            return false;
        }
        return $pathThumbnail;
    }

    public function deleteImage($pathImage)
    {
        if(!file_exists($pathImage)){
            return false;
        }
        try{
            if (!unlink($pathImage)){
                return false;
            }
        }catch(\Exception $e){
            return false;
        }
        return true;
    }

    private function generate_image_thumbnail($source_image_path, $thumbnail_image_path)
    {
        list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
        switch ($source_image_type) {
            case IMAGETYPE_GIF:
                $source_gd_image = imagecreatefromgif($source_image_path);
                break;
            case IMAGETYPE_JPEG:
                $source_gd_image = imagecreatefromjpeg($source_image_path);
                break;
            case IMAGETYPE_PNG:
                $source_gd_image = imagecreatefrompng($source_image_path);
                break;
        }
        if ($source_gd_image === false) {
            return false;
        }

        $source_aspect_ratio = $source_image_width / $source_image_height;
        $thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH / THUMBNAIL_IMAGE_MAX_HEIGHT;
        if ($source_image_width <= THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
            $thumbnail_image_width = $source_image_width;
            $thumbnail_image_height = $source_image_height;
        } elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
            $thumbnail_image_width = (int) (THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
            $thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
        } else {
            $thumbnail_image_width = THUMBNAIL_IMAGE_MAX_WIDTH;
            $thumbnail_image_height = (int) (THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
        }

        $thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
        imagecopyresampled(
            $thumbnail_gd_image,
            $source_gd_image,
            0,
            0,
            0,
            0,
            $thumbnail_image_width,
            $thumbnail_image_height,
            $source_image_width,
            $source_image_height
        );

        $img_disp = imagecreatetruecolor(THUMBNAIL_IMAGE_MAX_WIDTH,THUMBNAIL_IMAGE_MAX_WIDTH);
        $backcolor = imagecolorallocate($img_disp,0,0,0);
        imagefill($img_disp,0,0,$backcolor);

        imagecopy(
            $img_disp, $thumbnail_gd_image, 
            (imagesx($img_disp)/2)-(imagesx($thumbnail_gd_image)/2), 
            (imagesy($img_disp)/2)-(imagesy($thumbnail_gd_image)/2), 
            0, 
            0, 
            imagesx($thumbnail_gd_image), imagesy($thumbnail_gd_image)
        );

        imagejpeg($img_disp, $thumbnail_image_path, 90);
        imagedestroy($source_gd_image);
        imagedestroy($thumbnail_gd_image);
        imagedestroy($img_disp);
        return true;
    }
}