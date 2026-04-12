<?php
// Load config
require_once 'config/config.php';

// Hàm autoload controller (gọi khi new ClassName)
spl_autoload_register(function($className) {
    $paths = [
        'app/controllers/',
        'app/models/',
        'bootstrap/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Lấy URL từ .htaccess (vd: product/edit/5)
$url = $_GET['url'] ?? '';
if(isset($url))
    filter_var($url, FILTER_SANITIZE_URL);
$url = rtrim($url, '/');
$urlParts = explode('/', $url);

// Xác định Controller (mặc định là home)
$controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : DEFAULT_CONTROLLER . 'Controller';
$controllerFile = 'app/controllers/' . $controllerName . '.php';

// Xác định Action (mặc định là index)
$action = $urlParts[1] ?? DEFAULT_ACTION;

// Lấy tham số (vd: id từ product/edit/5)
$params = array_slice($urlParts, 2);


// Kiểm tra controller tồn tại
if (!file_exists($controllerFile)) {
    die("Controller '$controllerName' not found!");
}

// Tạo instance và gọi action
$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    die("Action '$action' not found in controller '$controllerName'!");
}

// Gọi action với các tham số
call_user_func_array([$controller, $action], $params);