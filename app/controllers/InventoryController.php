<?php

class InventoryController extends Controller
{
    private $inventoryModel;
    private $productModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $rows = $this->productModel->readAll();
        $this->view('inventory/index', [
            'WEBSITE_TITLE' => 'Quản lý tồn kho',
            'rows' => $rows,
            'message' => $_GET['message'] ?? '',
            'error' => $_GET['error'] ?? '',
        ]);
    }

    public function detail($id = null)
    {
        $item = $this->inventoryModel->readById((int)$id);
        if (!$item) {
            $this->redirect('inventory/index?error=Không tìm thấy bản ghi tồn kho');
        }
        $product = $this->productModel->readById((int)$item['product_id']);
        $this->view('inventory/detail', [
            'WEBSITE_TITLE' => 'Chi tiết tồn kho',
            'item' => $item,
            'product' => $product,
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'product_id' => (int)($_POST['product_id'] ?? 0),
                'quantity' => (int)($_POST['quantity'] ?? 0),
            ];
            $errors = [];
            if ($data['product_id'] <= 0) $errors[] = 'Vui lòng chọn sản phẩm.';
            if ($data['quantity'] < 0) $errors[] = 'Số lượng không được âm.';
            if ($this->inventoryModel->readByProductId($data['product_id'])) $errors[] = 'Sản phẩm này đã có bản ghi tồn kho.';
            if ($errors) {
                $this->view('inventory/create', ['WEBSITE_TITLE' => 'Thêm tồn kho', 'item' => $data, 'products' => $this->productModel->readAll(), 'errors' => $errors]);
                return;
            }
            try {
                $this->inventoryModel->create($data);
                $this->redirect('inventory/index?message=Thêm tồn kho thành công');
            } catch (Throwable $e) {
                $this->view('inventory/create', ['WEBSITE_TITLE' => 'Thêm tồn kho', 'item' => $data, 'products' => $this->productModel->readAll(), 'errors' => [$e->getMessage()]]);
            }
            return;
        }
        $this->view('inventory/create', ['WEBSITE_TITLE' => 'Thêm tồn kho', 'item' => [], 'products' => $this->productModel->readAll(), 'errors' => []]);
    }

    public function update($id = null)
    {
        $item = $this->inventoryModel->readById((int)$id);
        if (!$item) {
            $this->redirect('inventory/index?error=Không tìm thấy bản ghi tồn kho để cập nhật');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'inventory_id' => (int)$id,
                'product_id' => (int)($_POST['product_id'] ?? $item['product_id']),
                'quantity' => (int)($_POST['quantity'] ?? 0),
            ];
            $errors = [];
            if ($data['product_id'] <= 0) $errors[] = 'Vui lòng chọn sản phẩm.';
            if ($data['quantity'] < 0) $errors[] = 'Số lượng không được âm.';
            if ($errors) {
                $this->view('inventory/edit', ['WEBSITE_TITLE' => 'Cập nhật tồn kho', 'item' => array_merge($item, $data), 'products' => $this->productModel->readAll(), 'errors' => $errors]);
                return;
            }
            try {
                $this->inventoryModel->update($data);
                $this->redirect('inventory/view/' . $id . '?message=Cập nhật tồn kho thành công');
            } catch (Throwable $e) {
                $this->view('inventory/edit', ['WEBSITE_TITLE' => 'Cập nhật tồn kho', 'item' => array_merge($item, $data), 'products' => $this->productModel->readAll(), 'errors' => [$e->getMessage()]]);
            }
            return;
        }
        $this->view('inventory/edit', ['WEBSITE_TITLE' => 'Cập nhật tồn kho', 'item' => $item, 'products' => $this->productModel->readAll(), 'errors' => []]);
    }

    public function delete($id = null)
    {
        $item = $this->inventoryModel->readById((int)$id);
        if (!$item) {
            $this->redirect('inventory/index?error=Không tìm thấy bản ghi tồn kho để xóa');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->inventoryModel->delete((int)$id);
                $this->redirect('inventory/index?message=Xóa bản ghi tồn kho thành công');
            } catch (Throwable $e) {
                $this->redirect('inventory/view/' . $id . '?error=' . urlencode('Không thể xóa tồn kho: ' . $e->getMessage()));
            }
            return;
        }
        $this->view('inventory/delete', ['WEBSITE_TITLE' => 'Xóa tồn kho', 'item' => $item, 'product' => $this->productModel->readById((int)$item['product_id'])]);
    }
}
