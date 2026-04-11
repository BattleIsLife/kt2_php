<?php

class TestModel implements RepositoryInterface
{
    protected $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($object)
    {
        // throw new \Exception('Not implemented');
    }

    public function readAll()
    {
        // throw new \Exception('Not implemented');
    }

    public function readById($id)
    {
        // throw new \Exception('Not implemented');
    }

    public function update($object)
    {
        // throw new \Exception('Not implemented');
    }

    public function delete($id)
    {
        // throw new \Exception('Not implemented');
    }    
}