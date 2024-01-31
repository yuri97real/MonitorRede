<?php

namespace App\Controllers;

use Core\iRequest;
use Core\iResponse;

use App\Models\HostCategoryModel;

class HostCategoryController
{
    public function getCategories(iRequest $request, iResponse $response)
    {
        $result = (new HostCategoryModel)->getCategories();
        $status = $result["error"] ? 500 : 200;

        return $response->status($status)->json($result);
    }

    public function createCategory(iRequest $request, iResponse $response)
    {
        $data = $request->only([
            "description",
            "side",
            "num_sequence",
        ]);

        $result = (new HostCategoryModel)->createCategory($data);
        $status = $result["error"] ? 500 : 200;

        return $response->status($status)->json($result);
    }

    public function updateCategory(iRequest $request, iResponse $response)
    {
        [ "category_id"=> $category_id ] = get_object_vars($request->params);

        $data = $request->only([
            "description",
            "side",
            "num_sequence",
        ]);

        $result = (new HostCategoryModel)->updateCategory($category_id, $data);
        $status = $result["error"] ? 500 : 200;

        return $response->status($status)->json($result);
    }

    public function deleteCategory(iRequest $request, iResponse $response)
    {
        [ "category_id"=> $category_id ] = get_object_vars($request->params);

        $result = (new HostCategoryModel)->deleteCategory($category_id);
        $status = $result["error"] ? 500 : 200;

        return $response->status($status)->json($result);
    }
}