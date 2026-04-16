<?php

class CategoryController extends Controller
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $this->view('category/index', [
            'WEBSITE_TITLE' => 'Quản lý danh mục',
            'categories' => $this->categoryModel->readAll(),
            'message' => $_GET['message'] ?? '',
            'error' => $_GET['error'] ?? '',
        ]);
    }

    public function detail($id = null)
    {
        $category = $this->categoryModel->readById((int)$id);
        if (!$category) {
            $this->redirect('category/index?error=Không tìm thấy danh mục');
        }
        $this->view('category/detail', ['WEBSITE_TITLE' => 'Chi tiết danh mục', 'category' => $category]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'category_name' => trim($_POST['category_name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
            ];
            $errors = [];
            if ($data['category_name'] === '') $errors[] = 'Tên danh mục không được để trống.';
            if ($errors) {
                $this->view('category/create', ['WEBSITE_TITLE' => 'Thêm danh mục', 'category' => $data, 'errors' => $errors]);
                return;
            }
            try {
                $this->categoryModel->create($data);
                $this->redirect('category/index?message=Thêm danh mục thành công');
            } catch (Throwable $e) {
                $this->view('category/create', ['WEBSITE_TITLE' => 'Thêm danh mục', 'category' => $data, 'errors' => [$e->getMessage()]]);
            }
            return;
        }
        $this->view('category/create', ['WEBSITE_TITLE' => 'Thêm danh mục', 'category' => [], 'errors' => []]);
    }

    public function update($id = null)
    {
        $category = $this->categoryModel->readById((int)$id);
        if (!$category) {
            $this->redirect('category/index?error=Không tìm thấy danh mục để cập nhật');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'category_id' => (int)$id,
                'category_name' => trim($_POST['category_name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
            ];
            $errors = [];
            if ($data['category_name'] === '') $errors[] = 'Tên danh mục không được để trống.';
            if ($errors) {
                $this->view('category/edit', ['WEBSITE_TITLE' => 'Cập nhật danh mục', 'category' => array_merge($category, $data), 'errors' => $errors]);
                return;
            }
            try {
                $this->categoryModel->update($data);
                $this->redirect('/category/detail/ID' . $id . '?message=Cập nhật danh mục thành công');
            } catch (Throwable $e) {
                $this->view('category/edit', ['WEBSITE_TITLE' => 'Cập nhật danh mục', 'category' => array_merge($category, $data), 'errors' => [$e->getMessage()]]);
            }
            return;
        }
        $this->view('category/edit', ['WEBSITE_TITLE' => 'Cập nhật danh mục', 'category' => $category, 'errors' => []]);
    }

    public function delete($id = null)
    {
        $category = $this->categoryModel->readById((int)$id);
        if (!$category) {
            $this->redirect('category/index?error=Không tìm thấy danh mục để xóa');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->categoryModel->delete((int)$id);
                $this->redirect('category/index?message=Xóa danh mục thành công');
            } catch (Throwable $e) {
                $this->redirect('category/detail' . $id . '?error=' . urlencode('Không thể xóa danh mục: ' . $e->getMessage()));
            }
            return;
        }
        $this->view('category/delete', ['WEBSITE_TITLE' => 'Xóa danh mục', 'category' => $category]);
    }
}
