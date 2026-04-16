<?php

class ProductController extends Controller
{
    private $productModel;
    private $brandModel;
    private $categoryModel;
    private $supplierModel;
    private $inventoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->brandModel = new BrandModel();
        $this->categoryModel = new CategoryModel();
        $this->supplierModel = new SupplierModel();
        $this->inventoryModel = new InventoryModel();
    }

    public function index()
    {
        $title = "Quản lý sản phẩm";

        $filters = [
            'keyword' => trim($_GET['keyword'] ?? ''),
            'status' => trim($_GET['status'] ?? ''),
            'brand_id' => $_GET['brand_id'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'supplier_id' => $_GET['supplier_id'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
        ];

        $hasFilter = false;
        foreach ($filters as $value) {
            if ($value !== '' && $value !== null) {
                $hasFilter = true;
                break;
            }
        }

        $products = $hasFilter
            ? $this->productModel->search($filters)
            : $this->productModel->readAll();

        $data = [
            'WEBSITE_TITLE' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            'products' => $products,
            'brands' => $this->brandModel->readAll(),
            'categories' => $this->categoryModel->readAll(),
            'suppliers' => $this->supplierModel->readAll(),
            'filters' => $filters,
            'message' => $_GET['message'] ?? '',
            'error' => $_GET['error'] ?? '',
        ];

        $this->view('product/index', $data);
    }

    public function view($id = null)
    {
        $id = (int)$id;

        if ($id <= 0) {
            $this->redirect('product/index?error=ID sản phẩm không hợp lệ');
            return;
        }

        $product = $this->productModel->readById($id);

        if (!$product) {
            $this->redirect('product/index?error=Không tìm thấy sản phẩm');
            return;
        }

        $title = "Chi tiết sản phẩm";

        $data = [
            'WEBSITE_TITLE' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            'product' => $product,
            'brands' => $this->brandModel->readAll(),
            'categories' => $this->categoryModel->readAll(),
            'suppliers' => $this->supplierModel->readAll(),
            'message' => $_GET['message'] ?? '',
            'error' => $_GET['error'] ?? '',
        ];

        $this->view('product/view', $data);
    }

    public function create()
    {
        $title = "Thêm sản phẩm";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectProductData();
            $errors = $this->validateProductData($data);

            if (!empty($errors)) {
                $viewData = [
                    'WEBSITE_TITLE' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
                    'product' => $data,
                    'errors' => $errors,
                    'brands' => $this->brandModel->readAll(),
                    'categories' => $this->categoryModel->readAll(),
                    'suppliers' => $this->supplierModel->readAll(),
                ];

                $this->view('product/create', $viewData);
                return;
            }

            try {
                $this->productModel->create($data);

                $productId = null;
                if (method_exists($this->productModel, 'getLastInsertId')) {
                    $productId = $this->productModel->getLastInsertId();
                }

                if ($productId && !empty($data['quantity'])) {
                    $this->inventoryModel->create([
                        'product_id' => $productId,
                        'quantity' => (int)$data['quantity'],
                    ]);
                }

                $this->redirect('product/index?message=Thêm sản phẩm thành công');
                return;
            } catch (Throwable $e) {
                $viewData = [
                    'WEBSITE_TITLE' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
                    'product' => $data,
                    'errors' => ['Có lỗi khi thêm sản phẩm: ' . $e->getMessage()],
                    'brands' => $this->brandModel->readAll(),
                    'categories' => $this->categoryModel->readAll(),
                    'suppliers' => $this->supplierModel->readAll(),
                ];

                $this->view('product/create', $viewData);
                return;
            }
        }

        $data = [
            'WEBSITE_TITLE' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            'product' => [],
            'errors' => [],
            'brands' => $this->brandModel->readAll(),
            'categories' => $this->categoryModel->readAll(),
            'suppliers' => $this->supplierModel->readAll(),
        ];

        $this->view('product/create', $data);
    }

    public function update($id = null)
    {
        $id = (int)$id;

        if ($id <= 0) {
            $this->redirect('product/index?error=ID sản phẩm không hợp lệ');
            return;
        }

        $existing = $this->productModel->readById($id);

        if (!$existing) {
            $this->redirect('product/index?error=Không tìm thấy sản phẩm để cập nhật');
            return;
        }

        $title = "Cập nhật sản phẩm";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->collectProductData();
            $data['product_id'] = $id;

            $errors = $this->validateProductData($data, true);

            if (!empty($errors)) {
                $viewData = [
                    'WEBSITE_TITLE' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
                    'product' => array_merge($existing, $data),
                    'errors' => $errors,
                    'brands' => $this->brandModel->readAll(),
                    'categories' => $this->categoryModel->readAll(),
                    'suppliers' => $this->supplierModel->readAll(),
                ];

                $this->view('product/update', $viewData);
                return;
            }

            try {
                $this->productModel->update($data);

                if (isset($data['quantity']) && method_exists($this->inventoryModel, 'update')) {
                    if (!empty($existing['inventory_id'])) {
                        $this->inventoryModel->update([
                            'inventory_id' => $existing['inventory_id'],
                            'product_id' => $id,
                            'quantity' => (int)$data['quantity'],
                        ]);
                    }
                }

                $this->redirect('product/index?message=Cập nhật sản phẩm thành công');
                return;
            } catch (Throwable $e) {
                $viewData = [
                    'WEBSITE_TITLE' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
                    'product' => array_merge($existing, $data),
                    'errors' => ['Có lỗi khi cập nhật sản phẩm: ' . $e->getMessage()],
                    'brands' => $this->brandModel->readAll(),
                    'categories' => $this->categoryModel->readAll(),
                    'suppliers' => $this->supplierModel->readAll(),
                ];

                $this->view('product/update', $viewData);
                return;
            }
        }

        $data = [
            'WEBSITE_TITLE' => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            'product' => $existing,
            'errors' => [],
            'brands' => $this->brandModel->readAll(),
            'categories' => $this->categoryModel->readAll(),
            'suppliers' => $this->supplierModel->readAll(),
        ];

        $this->view('product/update', $data);
    }

    public function delete($id = null)
    {
        $id = (int)$id;

        if ($id <= 0) {
            $this->redirect('product/index?error=ID sản phẩm không hợp lệ');
            return;
        }

        $product = $this->productModel->readById($id);

        if (!$product) {
            $this->redirect('product/index?error=Không tìm thấy sản phẩm để xóa');
            return;
        }

        try {
            $this->productModel->delete($id);
            $this->redirect('product/index?message=Xóa sản phẩm thành công');
        } catch (Throwable $e) {
            $this->redirect('product/index?error=Không thể xóa sản phẩm');
        }
    }

    private function collectProductData()
    {
        return [
            'product_name' => trim($_POST['product_name'] ?? ''),
            'brand_id' => (int)($_POST['brand_id'] ?? 0),
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'supplier_id' => (int)($_POST['supplier_id'] ?? 0),
            'price' => (float)($_POST['price'] ?? 0),
            'cost_price' => (float)($_POST['cost_price'] ?? 0),
            'description' => trim($_POST['description'] ?? ''),
            'sku' => trim($_POST['sku'] ?? ''),
            'image' => trim($_POST['image'] ?? ''),
            'status' => trim($_POST['status'] ?? 'active'),
            'quantity' => (int)($_POST['quantity'] ?? 0),
        ];
    }

    private function validateProductData($data, $isUpdate = false)
    {
        $errors = [];

        if ($data['product_name'] === '') {
            $errors[] = 'Tên sản phẩm không được để trống';
        }

        if ($data['brand_id'] <= 0) {
            $errors[] = 'Vui lòng chọn thương hiệu';
        }

        if ($data['category_id'] <= 0) {
            $errors[] = 'Vui lòng chọn danh mục';
        }

        if ($data['supplier_id'] <= 0) {
            $errors[] = 'Vui lòng chọn nhà cung cấp';
        }

        if ($data['price'] < 0) {
            $errors[] = 'Giá bán không hợp lệ';
        }

        if ($data['cost_price'] < 0) {
            $errors[] = 'Giá nhập không hợp lệ';
        }

        if ($data['sku'] === '') {
            $errors[] = 'SKU không được để trống';
        }

        if (isset($data['quantity']) && $data['quantity'] < 0) {
            $errors[] = 'Số lượng tồn kho không hợp lệ';
        }

        return $errors;
    }
}