<?php
class HomeController extends Controller{
    
    public function index() {
        // Load model nếu cần
        // $productModel = new ProductModel();
        // $products = $productModel->getAll();
        
        // Dữ liệu truyền xuống view
        $data = [
            'title' => 'Trang chủ',
            'message' => 'Chào mừng đến với MVC'
        ];
        
        // Render view
        $this->view('home', $data);
    }
}