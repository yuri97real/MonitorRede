<?php

namespace App\Models;

use Core\Model;

class HostCategoryModel extends Model
{
    public function getCategories()
    {
        $result = $this->execute("SELECT * FROM NETWORK_MONITOR.HOST_CATEGORIES");
        return $result;
    }

    public function getCategoryById(int $category_id)
    {
        $result = $this->execute("SELECT * FROM NETWORK_MONITOR.HOST_CATEGORIES WHERE ID = ?", [ $category_id ], false);
        return $result;
    }

    public function createCategory(array $data)
    {
        $fields = array_keys($data);
        $fields = implode(",", $fields);

        $interrogations = array_fill(0, count($data), "?");
        $interrogations = implode(",", $interrogations);

        return $this->execute("INSERT INTO NETWORK_MONITOR.HOST_CATEGORIES ({$fields}) VALUES ($interrogations)", $data);
    }

    public function updateCategory(int $category_id, array $data)
    {
        $setters = [];

        foreach($data as $field => $value)
        {
            $setters[] = "{$field} = ?";
        }

        $setters = implode(",", $setters);

        $data["category_id"] = $category_id;

        return $this->execute("UPDATE NETWORK_MONITOR.HOST_CATEGORIES SET {$setters} WHERE ID = ?", $data);
    }

    public function deleteCategory(int $category_id)
    {
        return $this->execute("DELETE FROM NETWORK_MONITOR.HOST_CATEGORIES WHERE ID = ?", [ $category_id ]);
    }
}