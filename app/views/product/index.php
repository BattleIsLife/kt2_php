
<?php include __DIR__ . '/../layouts/topbar.php'; ?>
<?php $filters = $filters ?? ['keyword' => '', 'status' => '', 'brand' => '', 'supplier' => '']; ?>

<main class="page-container">

    <section class="filter-panel">
        <form method="GET" action="<?= BASE_URL ?>/product">
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="input-icon-group">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" id="filterKeyword" name="keyword" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>" placeholder="Tìm theo tên hoặc SKU...">
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <select class="form-select" id="filterStatus" name="status">
                    <option value="" <?= (($filters['status'] ?? '') === '') ? 'selected' : '' ?>>Tất cả trạng thái</option>
                    <option value="active" <?= (($filters['status'] ?? '') === 'active') ? 'selected' : '' ?>>Active</option>
                    <option value="out_of_stock" <?= (($filters['status'] ?? '') === 'out_of_stock') ? 'selected' : '' ?>>Out of Stock</option>
                    <option value="discontinued" <?= (($filters['status'] ?? '') === 'discontinued') ? 'selected' : '' ?>>Discontinued</option>
                </select>
            </div>

            <div class="col-lg-3 col-md-6">
                <select class="form-select" id="filterBrand" name="brand">
                    <option value="" <?= (($filters['brand'] ?? '') === '') ? 'selected' : '' ?>>Tất cả thương hiệu</option>
                    <?php foreach (($brands ?? []) as $brandItem): ?>
                        <option value="<?= htmlspecialchars($brandItem['brand_name'] ?? '') ?>" <?= (($filters['brand'] ?? '') === ($brandItem['brand_name'] ?? '')) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($brandItem['brand_name'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-lg-3 col-md-6">
                <select class="form-select" id="filterSupplier" name="supplier">
                    <option value="" <?= (($filters['supplier'] ?? '') === '') ? 'selected' : '' ?>>Tất cả nhà cung cấp</option>
                    <?php foreach (($suppliers ?? []) as $supplierItem): ?>
                        <option value="<?= htmlspecialchars($supplierItem['supplier_name'] ?? '') ?>" <?= (($filters['supplier'] ?? '') === ($supplierItem['supplier_name'] ?? '')) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($supplierItem['supplier_name'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="filter-actions-bar">
            <div class="filter-left-actions">
                <button class="btn btn-primary btn-main-action" id="btnApplyFilter" type="submit">
                    <i class="fas fa-search me-2"></i>Tìm kiếm
                </button>

                <button
                    class="btn btn-light btn-secondary-action"
                    id="btnResetFilter"
                    type="button"
                    onclick="window.location.href='<?= BASE_URL ?>/product'"
                >
                    <i class="fas fa-rotate-left me-2"></i>Làm mới
                </button>
            </div>

            <div class="filter-right-actions">
                <button class="btn btn-success btn-main-action" type="button" data-bs-toggle="modal" data-bs-target="#createProductModal">
                    <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                </button>

                <button class="btn btn-light btn-secondary-action" type="button">
                    <i class="fas fa-file-import me-2"></i>Import Excel
                </button>

                <button
                    class="btn btn-light btn-secondary-action"
                    type="submit"
                    formaction="<?= BASE_URL ?>/product/export"
                    formmethod="get"
                >
                    <i class="fas fa-file-export me-2"></i>Export Excel
                </button>
            </div>
        </div>
        </form>
    </section>

    <section class="table-panel">
        <div class="table-responsive">
            <table class="table product-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Mã SP</th>
                        <th>Tên sản phẩm</th>
                        <th>Thương hiệu</th>
                        <th>Danh mục</th>
                        <th>Nhà cung cấp</th>
                        <th>Giá bán</th>
                        <th>Giá nhập</th>
                        <th>SKU</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): 
                            $profit = $product['price'] - $product['cost_price'];
                            $priceFormatted = number_format($product['price'], 0, ',', '.');
                            $costFormatted = number_format($product['cost_price'], 0, ',', '.');
                            $profitFormatted = number_format($profit, 0, ',', '.');
                            $rawImage = trim((string)($product['image'] ?? ''));
                            if ($rawImage === '') {
                                $imageUrl = BASE_URL . '/assets/images/no-image.png';
                            } elseif (preg_match('/^(?:https?:)?\/\//i', $rawImage) || strpos($rawImage, BASE_URL) === 0) {
                                $imageUrl = $rawImage;
                            } elseif (strpos($rawImage, 'images/') === 0) {
                                // Tương thích dữ liệu cũ lưu "images/xxx.png"
                                $imageUrl = BASE_URL . '/assets/' . ltrim($rawImage, '/');
                            } else {
                                $imageUrl = BASE_URL . '/' . ltrim($rawImage, '/');
                            }
                        ?>
                        <tr>
                            <td>
                                <div class="table-thumb">
                                    <img
                                        src="<?= htmlspecialchars($imageUrl) ?>"
                                        alt="<?= htmlspecialchars($product['product_name']) ?>"
                                        onerror="this.onerror=null;this.src='<?= BASE_URL ?>/assets/images/no-image.png';"
                                    >
                                </div>
                            </td>
                            <td><?= htmlspecialchars($product['product_id']) ?></td>
                            <td class="product-name-cell"><?= htmlspecialchars($product['product_name']) ?></td>
                            <td><?= htmlspecialchars($product['brand_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($product['category_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($product['supplier_name'] ?? '') ?></td>
                            <td class="price-text"><?= $priceFormatted ?> đ</td>
                            <td class="cost-text"><?= $costFormatted ?> đ</td>
                            <td><?= htmlspecialchars($product['sku']) ?></td>
                            <td>
                                <span class="status-badge status-<?= strtolower($product['status'] ?? 'active') ?>">
                                    <?= htmlspecialchars($product['status'] ?? 'Active') ?>
                                </span>
                            </td>
                            <td><?= date('Y-m-d', strtotime($product['created_at'])) ?></td>
                            <td class="text-center">
                                <div class="table-actions">
                                    <button
                                        type="button"
                                        class="action-btn action-view"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailProductModal"
                                        data-id="<?= htmlspecialchars($product['product_id']) ?>"
                                        data-name="<?= htmlspecialchars($product['product_name']) ?>"
                                        data-sku="<?= htmlspecialchars($product['sku']) ?>"
                                        data-brand="<?= htmlspecialchars($product['brand_name'] ?? '') ?>"
                                        data-category="<?= htmlspecialchars($product['category_name'] ?? '') ?>"
                                        data-supplier="<?= htmlspecialchars($product['supplier_name'] ?? '') ?>"
                                        data-price="<?= $priceFormatted ?> đ"
                                        data-cost="<?= $costFormatted ?> đ"
                                        data-profit="<?= $profitFormatted ?> đ"
                                        data-description="<?= htmlspecialchars($product['description'] ?? '') ?>"
                                        data-status="<?= htmlspecialchars($product['status'] ?? 'Active') ?>"
                                        data-created="<?= date('Y-m-d', strtotime($product['created_at'])) ?>"
                                        data-image="<?= htmlspecialchars($imageUrl) ?>"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="action-btn action-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editProductModal"
                                        data-id="<?= htmlspecialchars($product['product_id']) ?>"
                                        data-name="<?= htmlspecialchars($product['product_name']) ?>"
                                        data-sku="<?= htmlspecialchars($product['sku']) ?>"
                                        data-brand="<?= htmlspecialchars($product['brand_name'] ?? '') ?>"
                                        data-category="<?= htmlspecialchars($product['category_name'] ?? '') ?>"
                                        data-supplier="<?= htmlspecialchars($product['supplier_name'] ?? '') ?>"
                                        data-price="<?= $product['price'] ?>"
                                        data-cost="<?= $product['cost_price'] ?>"
                                        data-description="<?= htmlspecialchars($product['description'] ?? '') ?>"
                                        data-status="<?= htmlspecialchars($product['status'] ?? 'Active') ?>"
                                        data-created="<?= date('Y-m-d', strtotime($product['created_at'])) ?>"
                                        data-image="<?= htmlspecialchars($imageUrl) ?>"
                                    >
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="action-btn action-delete"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteProductModal"
                                        data-id="<?= htmlspecialchars($product['product_id']) ?>"
                                        data-name="<?= htmlspecialchars($product['product_name']) ?>"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                <p>Không có sản phẩm nào</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer-bar">
            <span id="paginationInfo">Trang <?= $currentPage ?> / <?= $totalPages ?> (Tổng: <?= $totalProducts ?> sản phẩm)</span>

            <div class="pagination-box">
                <?php
                    $queryParams = [
                        'keyword' => $filters['keyword'] ?? '',
                        'status' => $filters['status'] ?? '',
                        'brand' => $filters['brand'] ?? '',
                        'supplier' => $filters['supplier'] ?? '',
                    ];
                    $buildPageUrl = function ($page) use ($queryParams) {
                        $params = array_merge($queryParams, ['page' => $page]);
                        return '?' . http_build_query(array_filter($params, static function ($value) {
                            return $value !== null && $value !== '';
                        }));
                    };
                ?>
                <?php if ($currentPage > 1): ?>
                    <a href="<?= $buildPageUrl(1) ?>" class="page-btn" title="Trang đầu"><i class="fas fa-angle-double-left"></i></a>
                    <a href="<?= $buildPageUrl($currentPage - 1) ?>" class="page-btn" title="Trang trước"><i class="fas fa-angle-left"></i></a>
                <?php else: ?>
                    <button class="page-btn" disabled><i class="fas fa-angle-double-left"></i></button>
                    <button class="page-btn" disabled><i class="fas fa-angle-left"></i></button>
                <?php endif; ?>

                <!-- Render page numbers -->
                <?php
                    $start = max(1, $currentPage - 2);
                    $end = min($totalPages, $currentPage + 2);
                    
                    if ($start > 1):
                ?>
                    <a href="<?= $buildPageUrl(1) ?>" class="page-btn">1</a>
                    <?php if ($start > 2): ?>
                        <span class="page-btn" style="cursor: default;">...</span>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <a href="<?= $buildPageUrl($i) ?>" class="page-btn <?= $i === $currentPage ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php
                    if ($end < $totalPages):
                        if ($end < $totalPages - 1):
                ?>
                    <span class="page-btn" style="cursor: default;">...</span>
                        <?php endif; ?>
                    <a href="<?= $buildPageUrl($totalPages) ?>" class="page-btn"><?= $totalPages ?></a>
                    <?php endif; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= $buildPageUrl($currentPage + 1) ?>" class="page-btn" title="Trang sau"><i class="fas fa-angle-right"></i></a>
                    <a href="<?= $buildPageUrl($totalPages) ?>" class="page-btn" title="Trang cuối"><i class="fas fa-angle-double-right"></i></a>
                <?php else: ?>
                    <button class="page-btn" disabled><i class="fas fa-angle-right"></i></button>
                    <button class="page-btn" disabled><i class="fas fa-angle-double-right"></i></button>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<!-- CREATE MODAL -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl custom-modal-dialog">
        <div class="modal-content custom-modal-content">
            <div class="modal-header custom-modal-header">
                <h3 class="modal-title">Thêm sản phẩm mới</h3>
                <button type="button" class="btn-close custom-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body custom-modal-body">
                <form class="product-modal-form">

                    <div class="product-image-upload-row">
                        <div class="product-image-upload-box">
                            <img id="createPreviewImage" src="<?= BASE_URL ?>/assets/images/no-image.png" alt="Preview">
                        </div>

                        <div class="product-image-upload-field">
                            <label class="form-label">Chọn ảnh sản phẩm từ máy</label>

                            <div class="file-upload-custom">
                                <input type="file" id="createImageInput" accept="image/*" hidden>
                                <label for="createImageInput" class="file-upload-btn">Chọn ảnh</label>
                                <span class="file-upload-name" id="createFileName">Chưa chọn tệp nào</span>
                            </div>

                            <small class="input-help-text">Ảnh sẽ được preview ngay sau khi chọn.</small>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label">Mã sản phẩm</label>
                            <input type="text" class="form-control" value="<?= $nextProductId ?? 1 ?>" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control" placeholder="VD: APL-IP16-128">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" placeholder="Nhập tên sản phẩm">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Thương hiệu</label>
                            <select class="form-select">
                                <option selected>Chọn</option>
                                <option>Samsung</option>
                                <option>Apple</option>
                                <option>Sony</option>
                                <option>Xiaomi</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Danh mục</label>
                            <select class="form-select">
                                <option selected>Chọn</option>
                                <option>Dien Thoai</option>
                                <option>Laptop</option>
                                <option>May tinh bang</option>
                                <option>Tai nghe</option>
                                <option>Phụ kiện</option>
                            </select>
                        </div>

                        <div class="col-md-4" style="display: none;">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select">
                                <option selected>Active</option>
                                <option>Out of Stock</option>
                                <option>Discontinued</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Nhà cung cấp</label>
                            <input type="text" class="form-control" placeholder="Nhập nhà cung cấp">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Giá bán (VND)</label>
                            <input type="number" class="form-control" placeholder="0">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Giá nhập (VND)</label>
                            <input type="number" class="form-control" placeholder="0">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" rows="4" placeholder="Nhập mô tả sản phẩm"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer custom-modal-footer">
                <button type="button" class="btn btn-light btn-modal-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-xmark me-2"></i>Hủy
                </button>

                <button type="button" class="btn btn-primary btn-modal-submit">
                    <i class="far fa-floppy-disk me-2"></i>Thêm sản phẩm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- DETAIL MODAL -->
<div class="modal fade" id="detailProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl custom-modal-dialog">
        <div class="modal-content custom-modal-content detail-modal-content">
            <div class="modal-header custom-modal-header">
                <h3 class="modal-title">Chi tiết sản phẩm</h3>
                <button type="button" class="btn-close custom-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body custom-modal-body detail-modal-body">
                <div class="detail-image-center">
                    <div class="detail-product-image-box">
                        <img id="detailImage" src="<?= BASE_URL ?>/assets/images/no-image.png" alt="Ảnh sản phẩm">
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">THÔNG TIN CƠ BẢN</h4>
                    <div class="detail-card-box">
                        <div class="detail-row-item">
                            <span class="detail-label">Mã sản phẩm</span>
                            <strong id="detailId">SP001</strong>
                        </div>
                        <div class="detail-row-item">
                            <span class="detail-label">Tên sản phẩm</span>
                            <strong id="detailName">iPhone 15 Pro Max 256GB</strong>
                        </div>
                        <div class="detail-row-item">
                            <span class="detail-label">SKU</span>
                            <strong id="detailSku">APL-IP15PM-256</strong>
                        </div>
                        <div class="detail-row-item">
                            <span class="detail-label">Ngày tạo</span>
                            <strong id="detailCreated">2024-01-15</strong>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">PHÂN LOẠI SẢN PHẨM</h4>
                    <div class="detail-card-box">
                        <div class="detail-row-item">
                            <span class="detail-label">Thương hiệu</span>
                            <strong id="detailBrand">Apple</strong>
                        </div>
                        <div class="detail-row-item">
                            <span class="detail-label">Danh mục</span>
                            <strong id="detailCategory">Dien Thoai</strong>
                        </div>
                        <div class="detail-row-item">
                            <span class="detail-label">Nhà cung cấp</span>
                            <strong id="detailSupplier">Apple Vietnam</strong>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">GIÁ BÁN VÀ GIÁ NHẬP</h4>
                    <div class="detail-card-box">
                        <div class="detail-row-item">
                            <span class="detail-label">Giá bán</span>
                            <strong class="text-primary" id="detailPrice">34.990.000 đ</strong>
                        </div>
                        <div class="detail-row-item">
                            <span class="detail-label">Giá nhập</span>
                            <strong id="detailCost">30.500.000 đ</strong>
                        </div>
                        <div class="detail-row-item">
                            <span class="detail-label">Lợi nhuận</span>
                            <strong class="text-success" id="detailProfit">4.490.000 đ</strong>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">MÔ TẢ</h4>
                    <div class="detail-card-box">
                        <p class="detail-description-text" id="detailDescription">
                            iPhone 15 Pro Max với chip A17 Pro, camera 48MP, màn hình Super Retina XDR 6.7 inch, pin cả ngày.
                        </p>
                    </div>
                </div>

                <div class="detail-section">
                    <h4 class="detail-section-title">TRẠNG THÁI</h4>
                    <div class="detail-card-box">
                        <span id="detailStatusBadge" class="status-badge status-active">Active</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer custom-modal-footer detail-footer">
                <button type="button" class="btn btn-light btn-modal-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </button>
            </div>
        </div>
    </div>
</div>

<!-- EDIT MODAL -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl custom-modal-dialog">
        <div class="modal-content custom-modal-content">
            <div class="modal-header custom-modal-header">
                <h3 class="modal-title">Sửa sản phẩm</h3>
                <button type="button" class="btn-close custom-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body custom-modal-body">
                <form class="product-modal-form">
                    <div class="product-image-upload-row">
                        <div class="product-image-upload-box">
                            <img id="editPreviewImage" src="<?= BASE_URL ?>/assets/images/no-image.png" alt="Preview">
                        </div>

                        <div class="product-image-upload-field">
                            <label class="form-label">Chọn ảnh sản phẩm từ máy</label>

                            <div class="file-upload-custom">
                                <input type="file" id="editImageInput" accept="image/*" hidden>
                                <label for="editImageInput" class="file-upload-btn">Chọn ảnh</label>
                                <span class="file-upload-name" id="editFileName">Chưa chọn tệp nào</span>
                            </div>

                            <small class="input-help-text">Ảnh sẽ được preview ngay sau khi chọn.</small>
                        </div>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-6">
                            <label class="form-label">Mã sản phẩm</label>
                            <input type="text" class="form-control" id="editProductId" value="SP001" readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control" id="editSku" value="APL-IP15PM-256">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="editName" value="iPhone 15 Pro Max 256GB">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Thương hiệu</label>
                            <select class="form-select" id="editBrand">
                                <option>Samsung</option>
                                <option>Apple</option>
                                <option>Sony</option>
                                <option>Xiaomi</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Danh mục</label>
                            <select class="form-select" id="editCategory">
                                <option>Dien Thoai</option>
                                <option>Laptop</option>
                                <option>May tinh bang</option>
                                <option>Tai nghe</option>
                                <option>Phụ kiện</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" id="editStatus">
                                <option>Active</option>
                                <option>Out of Stock</option>
                                <option>Discontinued</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Nhà cung cấp</label>
                            <input type="text" class="form-control" id="editSupplier" value="Apple Vietnam">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Giá bán (VND)</label>
                            <input type="number" class="form-control" id="editPrice" value="34990000">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Giá nhập (VND)</label>
                            <input type="number" class="form-control" id="editCost" value="30500000">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" rows="4" id="editDescription">iPhone 15 Pro Max với chip A17 Pro, camera 48MP, màn hình Super Retina XDR 6.7 inch, pin cả ngày.</textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer custom-modal-footer">
                <button type="button" class="btn btn-light btn-modal-cancel" data-bs-dismiss="modal">
                    <i class="fas fa-xmark me-2"></i>Hủy
                </button>

                <button type="button" class="btn btn-primary btn-modal-submit">
                    <i class="far fa-floppy-disk me-2"></i>Lưu thay đổi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered custom-delete-dialog">
        <div class="modal-content custom-delete-content">
            <div class="modal-body delete-modal-body">
                <h3 class="delete-modal-title">Xác nhận xóa sản phẩm</h3>
                <p class="delete-modal-text">
                    Bạn có chắc chắn muốn xóa sản phẩm <strong id="deleteProductName">iPhone 15 Pro Max 256GB</strong>
                    (<span id="deleteProductId">SP001</span>)? Hành động này không thể hoàn tác.
                </p>

                <div class="delete-actions">
                    <button type="button" class="btn btn-light btn-modal-cancel" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger btn-delete-red">Xóa sản phẩm</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Inject dữ liệu từ PHP vào window.APP_DATA để product.js sử dụng
    window.APP_DATA = {
        baseUrl: '<?= BASE_URL ?>',
        products: <?= json_encode($products ?? [], JSON_UNESCAPED_UNICODE) ?>,
        brands: <?= json_encode($brands ?? [], JSON_UNESCAPED_UNICODE) ?>,
        categories: <?= json_encode($categories ?? [], JSON_UNESCAPED_UNICODE) ?>,
        suppliers: <?= json_encode($suppliers ?? [], JSON_UNESCAPED_UNICODE) ?>,
        currentPage: <?= $currentPage ?>,
        totalPages: <?= $totalPages ?>,
        totalProducts: <?= $totalProducts ?>,
        itemsPerPage: <?= $itemsPerPage ?>
    };
</script>

<script src="<?= BASE_URL ?>/js/product.js"></script>
