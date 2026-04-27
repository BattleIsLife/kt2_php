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
        $itemsPerPage = 5;
        $currentPage = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
        $filters = [
            'keyword' => trim((string)($_GET['keyword'] ?? '')),
            'status' => trim((string)($_GET['status'] ?? '')),
            'brand' => trim((string)($_GET['brand'] ?? '')),
            'supplier' => trim((string)($_GET['supplier'] ?? '')),
        ];

        $products = $this->productModel->readPaginatedFiltered($currentPage, $itemsPerPage, $filters);
        $totalProducts = $this->productModel->countFiltered($filters);
        $totalPages = max(1, (int) ceil($totalProducts / $itemsPerPage));
        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
            $products = $this->productModel->readPaginatedFiltered($currentPage, $itemsPerPage, $filters);
        }
        
        $brand = $this->brandModel->readAll();
        $category = $this->categoryModel->readAll();
        $supplier = $this->supplierModel->readAll();

        // Tính mã sản phẩm tiếp theo
        $maxProductId = $this->productModel->getMaxProductId();
        $nextProductId = $maxProductId + 1;

        // Dữ liệu truyền xuống view
        $data = [
            'WEBSITE_TITLE' => 'ElectroShop - Quản lý sản phẩm',
            'products'      => $products,
            'brands'        => $brand,
            'categories'    => $category,
            'suppliers'     => $supplier,
            'currentPage'   => $currentPage,
            'totalPages'    => $totalPages,
            'totalProducts' => $totalProducts,
            'itemsPerPage'  => $itemsPerPage,
            'filters'       => $filters,
            'nextProductId' => $nextProductId,
        ];

        $this->productModel->close();
        $this->brandModel->close();
        $this->categoryModel->close();
        $this->supplierModel->close();
        
        // Render view
        $this->view('product/index', $data);
    }

    // -------------------------------------------------------
    // POST /product/create — Nhận JSON, trả JSON
    // -------------------------------------------------------
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Request không hợp lệ']);
            exit;
        }
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
        $this->productModel->close();
        exit;
    }

    // -----------------------
    // GET /product/detail - Nhận JSON, trả JSON chi tiết sản phẩm
    // -----------------------
    public function detail()
    {
        header('Content-Type: application/json');

        $jsonData = file_get_contents('php://input');
        $data     = json_decode($jsonData, true);
        $product_id   = (int) ($data['product_id']   ?? 0);

        $product = $this->productModel->readById($product_id);
        if (empty($product))
        {
            echo json_encode(['error' => 'Sản phẩm không tồn tại', 'success' => false]);
            exit;
        }

        echo json_encode($product);
        exit;
    }

    // -------------------------------------------------------
    // POST /product/edit — Nhận JSON, trả JSON
    // -------------------------------------------------------
    public function edit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Request không hợp lệ']);
            exit;
        }

        header('Content-Type: application/json');

        $jsonData = file_get_contents('php://input');
        $data     = json_decode($jsonData, true);

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
        $this->productModel->close();
        exit;
    }

    // -------------------------------------------------------
    // POST /product/delete — Nhận JSON, trả JSON
    // -------------------------------------------------------
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Request không hợp lệ']);
            exit;
        }
        
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
        $this->productModel->close();
        exit;
    }

    public function export()
    {
        $filters = [
            'keyword' => trim((string)($_GET['keyword'] ?? '')),
            'status' => trim((string)($_GET['status'] ?? '')),
            'brand' => trim((string)($_GET['brand'] ?? '')),
            'supplier' => trim((string)($_GET['supplier'] ?? '')),
        ];

        $products = $this->productModel->readAllFiltered($filters);
        $this->productModel->close();

        $filename = 'products_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        if ($output === false) {
            exit;
        }

        // UTF-8 BOM để Excel mở tiếng Việt đúng.
        fwrite($output, "\xEF\xBB\xBF");

        fputcsv($output, [
            'Ma SP',
            'Ten san pham',
            'SKU',
            'Thuong hieu',
            'Danh muc',
            'Nha cung cap',
            'Gia ban',
            'Gia nhap',
            'Trang thai',
            'Ngay tao',
        ]);

        foreach ($products as $product) {
            fputcsv($output, [
                $product['product_id'] ?? '',
                $product['product_name'] ?? '',
                $product['sku'] ?? '',
                $product['brand_name'] ?? '',
                $product['category_name'] ?? '',
                $product['supplier_name'] ?? '',
                $product['price'] ?? '',
                $product['cost_price'] ?? '',
                $product['status'] ?? '',
                $product['created_at'] ?? '',
            ]);
        }

        fclose($output);
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