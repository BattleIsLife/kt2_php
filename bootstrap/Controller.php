<?php
class Controller
{
    // Hàm hỗ trợ render view
    protected function view($viewPath, $data = [], $layout = 'main') {
        // Extract dữ liệu để dùng trong view
        extract($data);
        
        // Bắt đầu buffer để lấy nội dung view chính
        ob_start();
        $viewFile = 'app/views/' . $viewPath . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "View '$viewPath' not found!";
        }
        $content = ob_get_clean();
        
        // Gán nội dung vào biến $content để layout sử dụng
        $data['WEBSITE_CONTENT'] = $content;
        
        // Extract các phần tử trong data thành biến. VD: $data['title'] -> $title
        extract($data);
        
        // Include layout
        $layoutFile = 'app/views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            // Nếu không có layout thì hiển thị trực tiếp
            echo $content;
        }
    }
    
    // Hàm redirect tiện ích
    protected function redirect($url) {
        header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
        exit;
    }
}