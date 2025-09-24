<?php

namespace App\Model;

use App\Config\Database;

class BaseModel {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
}
