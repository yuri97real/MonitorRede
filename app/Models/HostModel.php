<?php

namespace App\Models;

use Core\Model;

class HostModel extends Model
{
    public function getHosts()
    {
        $result = $this->execute("SELECT * FROM NETWORK_MONITOR.HOSTS");
        return $result;
    }

    public function createHost(array $data)
    {
        $fields = array_keys($data);
        $fields = implode(",", $fields);

        $interrogations = array_fill(0, count($data), "?");
        $interrogations = implode(",", $interrogations);

        return $this->execute("INSERT INTO NETWORK_MONITOR.HOSTS ({$fields}) VALUES ($interrogations)", $data);
    }

    public function updateHost(int $host_id, array $data)
    {
        $setters = [];

        foreach($data as $field => $value)
        {
            $setters[] = "{$field} = ?";
        }

        $setters = implode(",", $setters);

        $data["host_id"] = $host_id;

        return $this->execute("UPDATE NETWORK_MONITOR.HOSTS SET {$setters} WHERE ID = ?", $data);
    }

    public function deleteHost(int $host_id)
    {
        return $this->execute("DELETE FROM NETWORK_MONITOR.HOSTS WHERE ID = ?", [ $host_id ]);
    }
}