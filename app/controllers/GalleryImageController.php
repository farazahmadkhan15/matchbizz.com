<?php

namespace App\Controllers;

use App\Models\Image;
use App\Models\GalleryImage;
use App\Libraries\ImageUploader;
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use App\Exceptions\ConflictException;

const MAXIMUN_NUMBER_IMAGES = 50;

class GalleryImageController extends BaseController
{
    public function indexAction()
    {

    }

    public function retrieveAction(int $id)
    {
        $image = $this->modelsManager
            ->createBuilder()
            ->columns([
                "GalleryImage.description",
                "Image.name",
                "Image.extension",
                "address" => "Image.address",
                "addressThumbnail" =>"Image.addressThumbnail"
            ])
            ->from(["GalleryImage" => "App\Models\GalleryImage"])
            ->join("App\Models\Image", "Image.name = GalleryImage.ImageId", "Image")
            ->where("GalleryImage.id = :id:", ["id" => $id])
            ->getQuery()
            ->execute()
            ->getFirst();

        if (! $image) {
            $this->setResponse([ "error" => "Image Not Found" ], 404);
        } else {
            $image = $image->toArray();
            $this->setResponse($image);
        }
    }

    public function createAction()
    {
        $validator = $this->di->get('RequestValidator');
        $validation = $validator->validate($_POST + $_FILES, [
            'image' => 'required|uploaded_file:0,2M,png,jpeg',
            'businessProfileId' => 'required|numeric',
            'description' => 'required',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $businessProfileId = $this->request->get('businessProfileId');
        $count = GalleryImage::count("businessProfileId = {$businessProfileId}");

        if ($count >= MAXIMUN_NUMBER_IMAGES) {
            throw new ConflictException("gallery-image/maximun-number-reached", "The business has exceeded the maximum number of images");
        }

        $this->featureChecker->check($businessProfileId, 'add-gallery-photos');
        $this->featureChecker->checkCanEditBusinessProfile($businessProfileId);

        try {
            $galleryImage = $this->di->get('GalleryImageFactory')->create($this->request);
            if ($galleryImage) {
                $this->setResponse([ "ok" => true ], 200);
            } else {
                $this->setResponse([ "error" => $this->headerCode[500] ], 500);
            }
        } catch (\Exception $e) {
            $this->setResponse([ "error" => $this->headerCode[500] ], 500);
        }                      
        return;
    }

    public function deleteAction(int $id)
    {
        $galleryImage = GalleryImage::findFirstById($id);
        if (! $galleryImage) {
            $this->setResponse([ "error" => "Gallery Image Not Found" ], 404);
            return;
        }

        $businessProfileId = $galleryImage->getBusinessProfileId();
        $this->featureChecker->check($businessProfileId, 'add-gallery-photos');
        $this->featureChecker->checkCanEditBusinessProfile($businessProfileId);

        $image = Image::findFirst([
            "name = :name:",
            "bind"=>[ "name" => $galleryImage->imageId ]
        ]);
        if (! $image) {
            $this->setResponse([ "error" => "Image Not Found" ], 404);
            return;
        } 
        $imageUploader = new ImageUploader;
        $imageUploader->deleteImage($this->config->application->publicDir.$image->getAddress());
        $imageUploader->deleteImage($this->config->application->publicDir.$image->getAddressThumbnail());
        $image->delete();
        $galleryImage->delete();
        $this->setResponse([ "ok" => true ], 200);
        return;
    }

    public function getGalleryByBusinessAction()
    {
        $validator = $this->di->get('RequestValidator');
        $validation = $validator->validate((array)$this->request->get(), [
            'page' => 'required|numeric',
            'limit' => 'required|numeric',
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $currentPage = $this->request->get("page");
        $limit = $this->request->get("limit");
        $businessProfileId = $this->request->get("businessProfileId");

        $checkFeature = $this->di->get('FeatureChecker');
        $checkFeature->check($businessProfileId,'add-gallery-photos');

        $builder = $this->modelsManager
            ->createBuilder()
            ->columns([
                "GalleryImage.id",
                "GalleryImage.description",
                "Image.name",
                "Image.extension",
                "Image.address",
                "Image.addressThumbnail"
            ])
            ->from(["GalleryImage" => "App\Models\GalleryImage"])
            ->join("App\Models\Image", "Image.name = GalleryImage.ImageId", "Image")
            ->where("GalleryImage.businessProfileId = :businessProfileId:", ["businessProfileId" => $businessProfileId])
            ->andWhere("GalleryImage.deletedAt IS NULL"); 

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $builder,
                "limit"   => $limit,
                "page" => $currentPage,
            ]
        );
        return $paginator->getPaginate();
    }

    public function getHeaderImageAction(int $businessProfileId)
    {
        $validator = $this->di->get('RequestValidator');
        $validation = $validator->validate([ "businessProfileId" => $businessProfileId ], [
            'businessProfileId' => 'required|numeric|exists:BusinessProfile,id'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $checkFeature = $this->di->get('FeatureChecker');
        $checkFeature->check($businessProfileId,'add-gallery-photos');

        $images = $this->modelsManager
            ->createBuilder()
            ->columns([
                "GalleryImage.id",
                "GalleryImage.description",
                "Image.name",
                "Image.extension",
                "image" => "Image.address",
                "imageThumbnail" => "Image.addressThumbnail"
            ])
            ->from(["GalleryImage" => "App\Models\GalleryImage"])
            ->join("App\Models\Image", "Image.name = GalleryImage.ImageId", "Image")
            ->where("GalleryImage.businessProfileId = :businessProfileId:", ["businessProfileId" => $businessProfileId])
            ->andWhere("GalleryImage.deletedAt IS NULL")
            ->limit(2)
            ->getQuery()
            ->execute();

        if(!$images)
        {
            $this->setResponse([ "error" => "Image Not Found" ], 404);
            return;
        }

        return $images;
    }
}