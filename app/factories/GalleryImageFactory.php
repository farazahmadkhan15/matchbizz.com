<?php

namespace App\Factories;

use App\Models\Image;
use App\Models\GalleryImage;
use Phalcon\Security\Random;
use App\Libraries\ImageUploader;
use Phalcon\Config;
use Phalcon\Mvc\Model\Transaction\Failed;
use Phalcon\Mvc\Model\Transaction\Manager;
use App\Security\Auth;

class GalleryImageFactory
{
    protected $config;

    protected $auth;

    public function __construct(Config $config, Auth $auth)
    {
        $this->config = $config;
        $this->auth = $auth;
    }

    public function create($request)
    {
        $image = $request->getUploadedFiles()[0];
        $description = $request->getPost('description');
        $businessProfileId = $request->getPost('businessProfileId');

        $imageModel = $this->createImage($image);

        $galleryImage = new GalleryImage();
        $galleryImage->setImageId($imageModel->getName());
        $galleryImage->setDescription($description);
        $galleryImage->setBusinessProfileId($businessProfileId);
        $galleryImage->save();

        return ["image" => $imageModel, "galleryImage" => $galleryImage];
    }

    public function createImage($image)
    {
        $imageName = (new Random())->uuid();
        $uploader = new ImageUploader();
        $appConfig = $this->config->application;
        $absoluteImageDir = $appConfig->publicDir.DIRECTORY_SEPARATOR.$appConfig->imgDir.DIRECTORY_SEPARATOR;

        $extension = $image->getExtension();

        $userDir = $absoluteImageDir.$this->auth->getUserId();
        $pathImage = $uploader->upload($userDir, "{$imageName}.{$extension}", $image);
        if (! $pathImage) {
            throw new \Exception('Unable to save image to disk');
        }

        $nameThumbnail = "{$imageName}_Thumbnail.{$extension}";
        $pathThumbnail = $uploader->thumbnail($pathImage, $userDir, $nameThumbnail, $image);
        if (! $pathThumbnail) {
            throw new \Exception('Unable to save image thumbnail to disk');
        }

        // are these diferent from the ones above?
        $pathImage = $appConfig->imgDir.DIRECTORY_SEPARATOR.$this->auth->getUserId().DIRECTORY_SEPARATOR.$imageName.".".$extension;
        $pathThumbnail = $appConfig->imgDir.DIRECTORY_SEPARATOR.$this->auth->getUserId().DIRECTORY_SEPARATOR.$nameThumbnail;


        $imageModel = new Image();
        $imageModel->setName($imageName);
        $imageModel->setAddress($pathImage);
        $imageModel->setAddressThumbnail($pathThumbnail);
        $imageModel->setUsed(1);
        $imageModel->setExtension($extension);
        $imageModel->save();

        return $imageModel;
    }
}