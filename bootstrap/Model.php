<?php

class Model{
    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function close()
    {
        $this->db->close();
    }
}