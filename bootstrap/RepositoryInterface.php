<?php

interface RepositoryInterface{
    public function create($object);
    public function readAll();
    public function readById($id);
    public function update($object);
    public function delete($id);
}