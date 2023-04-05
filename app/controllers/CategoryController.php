<?php

namespace App\Controllers;

use App\Models\Category;

class CategoryController extends BaseController
{
    public function indexAction()
    {
        return Category::find()->toArray();
    }

    public function retrieveAction(int $id)
    {
        $category = Category::findFirstById($id);

        if (! $category) {
            $this->setResponse([ "error" => "Category Not Found" ], 404);
        } else {
            $this->setResponse($category->toArray());
        }
    }

    public function createAction()
    {
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate((Array)$request, [
            'name' => 'required',
            'description' => 'required',
            'parentId' => 'numeric|exists:Category,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $category = new Category();
        $category->setName($request->name);
        $category->setDescription($request->description);
        $category->setParentCategoryId($request->parentId);

        if (! $category->save()) {
            $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);
        } else {
            $this->setResponse($category->toArray());
        }
    }

    public function updateAction(int $id)
    {
        $category = Category::findFirstById($id);

        if (! $category) {
            $this->setResponse([ "error" => "Category Not Found" ], 404);
            return;
        }

        $request = $this->request->getJsonRawBody();
        $category->setName($request->name);
        $category->setDescription($request->description);

        if (! $category->save()) {
            $this->setResponse([ "error" => $this->headerCode[$this->code] ], 400);
        } else {
            $this->setResponse($category->toArray());
        }
    }

    public function deleteAction(int $id)
    {
        $category = Category::findFirstById($id);
        if (! $category) {
            $this->setResponse([ "error" => "Category Not Found" ], 404);
        } else  {
            $this->deleteMasive([$id]);
            $category->delete();
            $this->setResponse([ "ok" => true ]);
        }
    }

    private function deleteMasive($categoryIds){
        if($categoryIds){
            $categories = Category::query()
                ->inWhere("parentCategoryId", $categoryIds)
                ->execute();

            $categoryIds = array_map(function ($category) {
                return  $category['id'];
            }, $categories->toArray());

            $this->deleteMasive($categoryIds);

            foreach($categories as $category){
                $category->delete();
            }
        }
    }

    public function childrenAction()
    {
        $request = $this->request->getJsonRawBody();
        $validation = $this->validator->validate((Array)$request, [
            'categoryId' => 'required|numeric|exists:Category,id',
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $categoryId = $request->categoryId;

        if ($categoryId != null) {
            return $this->modelsManager
                ->createBuilder()
                ->columns([
                    "Category.id",
                    "Category.name",
                    "Category.createdAt",
                    "Category.description",
                    "hasSubCategories" => "IF(COUNT(ServiceChild.parentCategoryId) > 0, true, false)"])
                ->from(["Category" => "App\Models\Category"])
                ->join("App\Models\Category", "ServiceChild.parentCategoryId = Category.id", "ServiceChild","LEFT")
                ->where("Category.parentCategoryId = :id: AND Category.deletedAt IS NULL", ["id"=>$categoryId])
                ->groupBy("Category.id")
                ->getQuery()
                ->execute();
        }

        $categoryIds = $request->categoryIds;

        if ($categoryIds == null) {
            $this->setResponse([ "error" => "400 Bad request" ], 400);
            return;
        }

        $children = $this->modelsManager
            ->createBuilder()
            ->columns([
                "Category.parentCategoryId",
                "Category.id",
                "Category.name",
                "Category.description",
                "hasSubCategories" => "IF(COUNT(ServiceChild.parentCategoryId) > 0, 1, 0)"
            ])
            ->from(["Category" => "App\Models\Category"])
            ->join("App\Models\Category", "ServiceChild.parentCategoryId = Category.id", "ServiceChild","LEFT")
            ->where("Category.deletedAt IS NULL")
            ->inWhere("Category.parentCategoryId", $categoryIds)
            ->groupBy(["Category.id", "Category.name", "Category.description"])
            ->getQuery()
            ->execute();

        $childrenByCategory = [];
        foreach ($children as $child) {
            $childrenByCategory[$child->parentCategoryId][] = $child->toArray();
            unset($childrenByCategory[$child->parentCategoryId]['parentCategoryId']);
        }

        return $childrenByCategory;
    }

    public function rootsAction()
    {
        $roots = $this->modelsManager
            ->createBuilder()
            ->columns([ 
                "Category.id", 
                "Category.name",
                "Category.description",
                "icon" => "Icons.code",
                "hasSubCategories" => "IF(COUNT(ServiceChild.parentCategoryId) > 0, true, false)"])
            ->from(["Category" => "App\Models\Category"])
            ->join("App\Models\Category", "ServiceChild.parentCategoryId = Category.id AND ServiceChild.deletedAt IS NULL", "ServiceChild","LEFT")
            ->join("App\Models\Icons", "Category.iconId = Icons.id", "Icons","LEFT")
            ->where("Category.parentCategoryId IS NULL AND Category.deletedAt IS NULL")
            ->groupBy("Category.id")
            ->getQuery()
            ->execute();

        return $roots;
    }

    public function autocompleteAction()
    {
        $category = trim($this->request->get("category"));

        if ($category == null || $category == "") { 
            $this->setResponse([ "error" => "400 Bad request" ], 400);
            return;
        }

        $matches = $this->modelsManager
            ->createBuilder()
            ->columns([
                "Category.id",
                "Category.name",
                "Category.parentCategoryId"
            ])
            ->from(["Category" => "App\Models\Category"])
            ->where("Category.name LIKE :name: AND deletedAt IS NULL", ["name" => "%" . $category . "%"])
            ->limit(10)
            ->getQuery()
            ->execute()
            ->toArray();

        return $this->di->get('CategoryForestFactory')->create($matches);
    }

    public function updateIconAction(int $categoryId){
        if (! $this->auth->isAdmin()) {
            throw new ForbiddenException(
                "forbidden/admin-only",
                "Only admin can update category"
            );
        }
        $request = $this->request->getJsonRawBody();

        $validation = $this->validator->validate((Array)$request, [
            'iconId' => 'required|numeric|exists:Icons,id'
        ]);

        if ($validation->fails()) {
            $this->setResponse([ "error" => $validation->errors()->toArray() ], 400);
            return;
        }
        $category = Category::findFirstById($categoryId);
        if (! $category) {
            $this->setResponse([ "error" => "Category Not Found" ], 404);
        }
        $category->setIconId($request->iconId);
        if(! $category->save()){
            $this->setResponse([ "error" => "Couldn't update category" ], 500);
        }
        return $category->toArray();
    }

}
