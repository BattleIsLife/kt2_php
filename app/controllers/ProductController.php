<?php

class ProductController extends Controller
{
    private $productModel;
    private $categoryModel;
    private $brandModel;
    private $supplierModel;

    public function __construct()
    {
        $this->productModel  = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->brandModel    = new BrandModel();
        $this->supplierModel = new SupplierModel();
    }

    // -------------------------------------------------------
    // GET /product — Trả về view, danh sách sản phẩm, thương hiệu, thể loại và nhà cung cấp
    // -------------------------------------------------------
    public function index()
    {
        $products = $this->productModel->readAll();
        $brand = $this->brandModel->readAll();
        $category = $this->categoryModel->readAll();
        $supplier = $this->supplierModel->readAll();
    
        // Dữ liệu truyền xuống view
        $data = [
            'WEBSITE_TITLE' => 'ElectroShop - Quản lý sản phẩm',
            'products' => $products,
            'brands'   => $brand,
            'categories'  => $category,
            'suppliers'  => $supplier,
        ];
        
        // Render view
        $this->view('product/index', $data);
    }

    // -------------------------------------------------------
    // POST /product/create — Nhận JSON, trả JSON
    // -------------------------------------------------------
    public function create()
    {
        header('Content-Type: application/json');

        $jsonData = file_get_contents('php://input');
        $data     = json_decode($jsonData, true);

        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $product_name = trim($data['product_name'] ?? '');
        $brand_id     = $data['brand_id']     ?? '';
        $category_id  = $data['category_id']  ?? '';
        $supplier_id  = $data['supplier_id']  ?? '';
        $price        = $data['price']        ?? '';
        $cost_price   = $data['cost_price']   ?? '';
        $sku          = trim($data['sku']      ?? '');
        $description  = trim($data['description'] ?? '');
        $imageBase64  = $data['image']        ?? '';

        if (!$product_name) { echo json_encode(['success' => false, 'message' => 'Tên sản phẩm không được trống']);  exit; }
        if (!$brand_id)     { echo json_encode(['success' => false, 'message' => 'Vui lòng chọn thương hiệu']);       exit; }
        if (!$category_id)  { echo json_encode(['success' => false, 'message' => 'Vui lòng chọn danh mục']);          exit; }
        if (!$supplier_id)  { echo json_encode(['success' => false, 'message' => 'Vui lòng chọn nhà cung cấp']);      exit; }
        if ($price === '')  { echo json_encode(['success' => false, 'message' => 'Vui lòng nhập giá bán']);           exit; }
        if ($cost_price === '') { echo json_encode(['success' => false, 'message' => 'Vui lòng nhập giá nhập']);      exit; }
        if (!$sku)          { echo json_encode(['success' => false, 'message' => 'SKU không được trống']);            exit; }

        $imagePath = $this->saveBase64Image($imageBase64);

        $insertData = [
            'product_name' => htmlspecialchars($product_name),
            'brand_id'     => (int) $brand_id,
            'category_id'  => (int) $category_id,
            'supplier_id'  => (int) $supplier_id,
            'price'        => (float) $price,
            'cost_price'   => (float) $cost_price,
            'description'  => htmlspecialchars($description),
            'sku'          => htmlspecialchars($sku),
            'image'        => $imagePath,
        ];

        try {
            $this->productModel->create($insertData);
            echo json_encode(['success' => true, 'message' => 'Thêm sản phẩm thành công']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }

    // -------------------------------------------------------
    // POST /product/edit — Nhận JSON, trả JSON
    // -------------------------------------------------------
    public function edit()
    {
        header('Content-Type: application/json');

        $jsonData = file_get_contents('php://input');
        $data     = json_decode($jsonData, true);

        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $product_id   = (int) ($data['product_id']   ?? 0);
        $product_name = trim($data['product_name']    ?? '');
        $brand_id     = $data['brand_id']     ?? '';
        $category_id  = $data['category_id']  ?? '';
        $supplier_id  = $data['supplier_id']  ?? '';
        $price        = $data['price']        ?? '';
        $cost_price   = $data['cost_price']   ?? '';
        $sku          = trim($data['sku']      ?? '');
        $description  = trim($data['description'] ?? '');
        $status       = $data['status']       ?? 'active';
        $imageBase64  = $data['image']        ?? '';

        if (!$product_id)   { echo json_encode(['success' => false, 'message' => 'Thiếu product_id']);                 exit; }
        if (!$product_name) { echo json_encode(['success' => false, 'message' => 'Tên sản phẩm không được trống']);   exit; }
        if (!$brand_id)     { echo json_encode(['success' => false, 'message' => 'Vui lòng chọn thương hiệu']);        exit; }
        if (!$category_id)  { echo json_encode(['success' => false, 'message' => 'Vui lòng chọn danh mục']);           exit; }
        if (!$supplier_id)  { echo json_encode(['success' => false, 'message' => 'Vui lòng chọn nhà cung cấp']);       exit; }
        if (!$sku)          { echo json_encode(['success' => false, 'message' => 'SKU không được trống']);             exit; }

        $existing = $this->productModel->readById($product_id);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            exit;
        }

        // Có ảnh mới (base64) thì lưu và xóa ảnh cũ, không thì giữ ảnh cũ
        if ($imageBase64 && str_starts_with($imageBase64, 'data:image')) {
            $imagePath = $this->saveBase64Image($imageBase64);
            if ($imagePath && !empty($existing['image'])) {
                $oldFile = ROOT . '/' . $existing['image'];
                if (file_exists($oldFile)) unlink($oldFile);
            }
        } else {
            $imagePath = $existing['image'];
        }

        $updateData = [
            'product_id'   => $product_id,
            'product_name' => htmlspecialchars($product_name),
            'brand_id'     => (int) $brand_id,
            'category_id'  => (int) $category_id,
            'supplier_id'  => (int) $supplier_id,
            'price'        => (float) $price,
            'cost_price'   => (float) $cost_price,
            'description'  => htmlspecialchars($description),
            'sku'          => htmlspecialchars($sku),
            'image'        => $imagePath,
            'status'       => htmlspecialchars($status),
        ];

        try {
            $this->productModel->update($updateData);
            echo json_encode(['success' => true, 'message' => 'Cập nhật sản phẩm thành công']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }

    // -------------------------------------------------------
    // POST /product/delete — Nhận JSON, trả JSON
    // -------------------------------------------------------
    public function delete()
    {
        header('Content-Type: application/json');

        $jsonData = file_get_contents('php://input');
        $data     = json_decode($jsonData, true);

        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }

        $product_id = (int) ($data['product_id'] ?? 0);

        if (!$product_id) {
            echo json_encode(['success' => false, 'message' => 'Thiếu product_id']);
            exit;
        }

        $existing = $this->productModel->readById($product_id);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại']);
            exit;
        }

        if (!empty($existing['image'])) {
            $filePath = ROOT . '/' . $existing['image'];
            if (file_exists($filePath)) unlink($filePath);
        }

        try {
            $this->productModel->delete($product_id);
            echo json_encode(['success' => true, 'message' => 'Xóa sản phẩm thành công']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
        }
        exit;
    }

    // -------------------------------------------------------
    // Lưu ảnh từ chuỗi base64
    // -------------------------------------------------------
    private function saveBase64Image($base64String)
    {
        if (!$base64String || !str_starts_with($base64String, 'data:image')) {
            return '';
        }

        $parts = explode(',', $base64String, 2);
        if (count($parts) < 2) return '';

        preg_match('/data:image\/(\w+);base64/', $parts[0], $matches);
        $ext     = strtolower($matches[1] ?? 'jpg');
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowed)) return '';

        $imageData = base64_decode($parts[1]);
        if (!$imageData) return '';

        $uploadDir = ROOT . '/assets/images/products/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $filename = 'product_' . time() . '_' . uniqid() . '.' . $ext;
        file_put_contents($uploadDir . $filename, $imageData);

        return 'assets/images/products/' . $filename;
    }
}