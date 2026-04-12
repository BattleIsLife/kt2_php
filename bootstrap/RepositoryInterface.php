<?php

interface RepositoryInterface{
    public function create($data);
    public function readAll();
    public function readById($id);
    public function update($data);
    public function delete($id);
}