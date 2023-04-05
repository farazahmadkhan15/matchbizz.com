<?php

namespace App\Controllers;
use App\Models\Image;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Query;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use App\Libraries\imageUploader;
class ImageController extends BaseController
{
    public function indexAction()
    {

    }

    public function retrieveAction(int $id)
    {
        $image = Image::findFirstById($id);

        if (! $image) {
            $this->setResponse([ "error" => "Image Not Found" ], 404);
        } else {
            $this->setResponse($image->toArray());
        }
    }

    public function createAction()
    {
        if($this->request->isPost())
        {
            if($this->request->hasFiles(true))
            {
                $auth = $this->di->get('auth');
                $image = $this->request->getUploadedFiles()[0];
                $upload = new imageUploader($this->config,$auth);
                if($upload->upload($image)){
                    $this->setResponse([ "ok" => true ], 200);
                }else{
                    $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);                
                }
            }
            else
            {
                $this->setResponse([ "error" => "Missing image" ], 400);
            }
        }
    }

    public function deleteAction(int $id)
    {
        $image = Image::findFirstById($id);

        if (! $image) {
            $this->setResponse([ "error" => "Image Not Found" ], 404);
        } else {
            $image->delete();
            $this->setResponse([ "ok" => true ], 200);
        }
    }
}