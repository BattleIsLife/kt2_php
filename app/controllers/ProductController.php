<?php

class ProductController extends Controller
{
    public function index()
    {
        $this->view('product/index');
    }

    public function create()
    {
        $this->view('product/create');
    }

    public function edit()
    {
        $this->view('product/edit');
    }

    public function detail()
    {
        $this->view('product/detail');
    }
}