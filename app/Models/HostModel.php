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
}