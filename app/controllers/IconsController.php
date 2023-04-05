<?php

namespace App\Controllers;

use App\Models\Icons;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Phalcon\Mvc\Model\Query;
use App\Models\Category;

class IconsController extends BaseController
{
    public function indexAction()
    {
        $request = $this->request->get();
        $validation = $this->validator->validate((Array)$request, [
            'page' => 'required|numeric',
            'limit' => 'required|numeric',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }

        $currentPage = $this->request->get("page");
        $limit = $this->request->get("limit");
        $query = $this->request->get("query");

        $builder = $this->modelsManager
            ->createBuilder()
            ->columns([
                "Icons.id",
                "Icons.code",
            ])
            ->from(["Icons" => "App\Models\Icons"]);
            if ($query) {
                $builder->where("Icons.code LIKE :code:", ["code" => "%{$query}%"]);
            }
            $builder->andWhere("Icons.deletedAt IS NULL");

        $paginator = new PaginatorQueryBuilder(
            [
                "builder" => $builder,
                "limit"   => $limit,
                "page"    => $currentPage,
            ]
        );
        return $paginator->getPaginate();
    }
}
