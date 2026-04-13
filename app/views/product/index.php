<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/topbar.php'; ?>

<main class="page-container">

    <section class="filter-panel">
        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="input-icon-group">
                    <i class="fas fa-search"></i>
                    <input type="text" class="form-control" placeholder="Tìm theo tên hoặc SKU...">
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <select class="form-select">
                    <option selected>Tất cả trạng thái</option>
                    <option>Active</option>
                    <option>Out of Stock</option>
                    <option>Discontinued</option>
                </select>
            </div>

            <div class="col-lg-3 col-md-6">
                <select class="form-select">
                    <option selected>Tất cả thương hiệu</option>
                    <option>Samsung</option>
                    <option>Apple</option>
                    <option>Sony</option>
                    <option>Xiaomi</option>
                </select>
            </div>

            <div class="col-lg-3 col-md-6">
                <select class="form-select">
                    <option selected>Tất cả danh mục</option>
                    <option>Dien Thoai</option>
                    <option>Laptop</option>
                    <option>May tinh bang</option>
                    <option>Tai nghe</option>
                    <option>Phụ kiện</option>
                </select>
            </div>
        </div>

        <div class="filter-actions-bar">
            <div class="filter-left-actions">
                <button class="btn btn-primary btn-main-action">
                    <i class="fas fa-search me-2"></i>Tìm kiếm
                </button>

                <button class="btn btn-light btn-secondary-action" id="btnResetFilter" type="button">
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

                <button class="btn btn-light btn-secondary-action" type="button">
                    <i class="fas fa-file-export me-2"></i>Export Excel
                </button>
            </div>
        </div>
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
                    <tr>
                        <td>
                            <div class="table-thumb">
                                <img src="<?= BASE_URL ?>/assets/images/iphone15.jpg" alt="iPhone 15">
                            </div>
                        </td>
                        <td>SP001</td>
                        <td class="product-name-cell">iPhone 15 Pro Max 256GB</td>
                        <td>Apple</td>
                        <td>Dien Thoai</td>
                        <td>Apple Vietnam</td>
                        <td class="price-text">34.990.000 đ</td>
                        <td class="cost-text">30.500.000 đ</td>
                        <td>APL-IP15PM-256</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>2024-01-15</td>
                        <td class="text-center">
                            <div class="table-actions">
                                <button
                                    type="button"
                                    class="action-btn action-view"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detailProductModal"
                                    data-id="SP001"
                                    data-name="iPhone 15 Pro Max 256GB"
                                    data-sku="APL-IP15PM-256"
                                    data-brand="Apple"
                                    data-category="Dien Thoai"
                                    data-supplier="Apple Vietnam"
                                    data-price="34.990.000 đ"
                                    data-cost="30.500.000 đ"
                                    data-profit="4.490.000 đ"
                                    data-description="iPhone 15 Pro Max với chip A17 Pro, camera 48MP, màn hình Super Retina XDR 6.7 inch, pin cả ngày."
                                    data-status="Active"
                                    data-created="2024-01-15"
                                    data-image="<?= BASE_URL ?>/assets/images/iphone15.jpg"
                                >
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button
                                    type="button"
                                    class="action-btn action-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editProductModal"
                                    data-id="SP001"
                                    data-name="iPhone 15 Pro Max 256GB"
                                    data-sku="APL-IP15PM-256"
                                    data-brand="Apple"
                                    data-category="Dien Thoai"
                                    data-supplier="Apple Vietnam"
                                    data-price="34990000"
                                    data-cost="30500000"
                                    data-description="iPhone 15 Pro Max với chip A17 Pro, camera 48MP, màn hình Super Retina XDR 6.7 inch, pin cả ngày."
                                    data-status="Active"
                                    data-created="2024-01-15"
                                    data-image="<?= BASE_URL ?>/assets/images/iphone15.jpg"
                                >
                                    <i class="fas fa-pen"></i>
                                </button>

                                <button
                                    type="button"
                                    class="action-btn action-delete"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteProductModal"
                                    data-id="SP001"
                                    data-name="iPhone 15 Pro Max 256GB"
                                >
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="table-thumb">
                                <img src="<?= BASE_URL ?>/assets/images/macbook-air-m3.jpg" alt="MacBook Air M3">
                            </div>
                        </td>
                        <td>SP003</td>
                        <td class="product-name-cell">MacBook Air M3 13 inch</td>
                        <td>Apple</td>
                        <td>Laptop</td>
                        <td>Apple Vietnam</td>
                        <td class="price-text">27.990.000 đ</td>
                        <td class="cost-text">24.000.000 đ</td>
                        <td>APL-MBA-M3-13</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>2024-03-05</td>
                        <td class="text-center">
                            <div class="table-actions">
                                <button type="button" class="action-btn action-view" data-bs-toggle="modal" data-bs-target="#detailProductModal"
                                    data-id="SP003"
                                    data-name="MacBook Air M3 13 inch"
                                    data-sku="APL-MBA-M3-13"
                                    data-brand="Apple"
                                    data-category="Laptop"
                                    data-supplier="Apple Vietnam"
                                    data-price="27.990.000 đ"
                                    data-cost="24.000.000 đ"
                                    data-profit="3.990.000 đ"
                                    data-description="Laptop mỏng nhẹ, chip Apple M3, màn hình sắc nét, pin lâu và phù hợp cho công việc hằng ngày."
                                    data-status="Active"
                                    data-created="2024-03-05"
                                    data-image="<?= BASE_URL ?>/assets/images/macbook-air-m3.jpg">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button type="button" class="action-btn action-edit" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                    data-id="SP003"
                                    data-name="MacBook Air M3 13 inch"
                                    data-sku="APL-MBA-M3-13"
                                    data-brand="Apple"
                                    data-category="Laptop"
                                    data-supplier="Apple Vietnam"
                                    data-price="27990000"
                                    data-cost="24000000"
                                    data-description="Laptop mỏng nhẹ, chip Apple M3, màn hình sắc nét, pin lâu và phù hợp cho công việc hằng ngày."
                                    data-status="Active"
                                    data-created="2024-03-05"
                                    data-image="<?= BASE_URL ?>/assets/images/macbook-air-m3.jpg">
                                    <i class="fas fa-pen"></i>
                                </button>

                                <button type="button" class="action-btn action-delete" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-id="SP003" data-name="MacBook Air M3 13 inch">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="table-thumb">
                                <img src="<?= BASE_URL ?>/assets/images/sony-wh1000xm5.jpg" alt="Sony WH-1000XM5">
                            </div>
                        </td>
                        <td>SP004</td>
                        <td class="product-name-cell">Sony WH-1000XM5</td>
                        <td>Sony</td>
                        <td>Tai nghe</td>
                        <td>Sony Vietnam</td>
                        <td class="price-text">8.490.000 đ</td>
                        <td class="cost-text">6.800.000 đ</td>
                        <td>SONY-WH1000XM5</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>2024-01-20</td>
                        <td class="text-center">
                            <div class="table-actions">
                                <button type="button" class="action-btn action-view" data-bs-toggle="modal" data-bs-target="#detailProductModal"
                                    data-id="SP004"
                                    data-name="Sony WH-1000XM5"
                                    data-sku="SONY-WH1000XM5"
                                    data-brand="Sony"
                                    data-category="Tai nghe"
                                    data-supplier="Sony Vietnam"
                                    data-price="8.490.000 đ"
                                    data-cost="6.800.000 đ"
                                    data-profit="1.690.000 đ"
                                    data-description="Tai nghe chống ồn cao cấp, âm thanh chi tiết, pin tốt và phù hợp làm việc hoặc giải trí."
                                    data-status="Active"
                                    data-created="2024-01-20"
                                    data-image="<?= BASE_URL ?>/assets/images/sony-wh1000xm5.jpg">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button type="button" class="action-btn action-edit" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                    data-id="SP004"
                                    data-name="Sony WH-1000XM5"
                                    data-sku="SONY-WH1000XM5"
                                    data-brand="Sony"
                                    data-category="Tai nghe"
                                    data-supplier="Sony Vietnam"
                                    data-price="8490000"
                                    data-cost="6800000"
                                    data-description="Tai nghe chống ồn cao cấp, âm thanh chi tiết, pin tốt và phù hợp làm việc hoặc giải trí."
                                    data-status="Active"
                                    data-created="2024-01-20"
                                    data-image="<?= BASE_URL ?>/assets/images/sony-wh1000xm5.jpg">
                                    <i class="fas fa-pen"></i>
                                </button>

                                <button type="button" class="action-btn action-delete" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-id="SP004" data-name="Sony WH-1000XM5">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="table-thumb">
                                <img src="<?= BASE_URL ?>/assets/images/xiaomi-pad-6-pro.jpg" alt="Xiaomi Pad 6 Pro">
                            </div>
                        </td>
                        <td>SP005</td>
                        <td class="product-name-cell">Xiaomi Pad 6 Pro</td>
                        <td>Xiaomi</td>
                        <td>May tinh bang</td>
                        <td>Xiaomi Vietnam</td>
                        <td class="price-text">9.990.000 đ</td>
                        <td class="cost-text">7.500.000 đ</td>
                        <td>XM-PAD6P-128</td>
                        <td><span class="status-badge status-out">Out of Stock</span></td>
                        <td>2024-02-28</td>
                        <td class="text-center">
                            <div class="table-actions">
                                <button type="button" class="action-btn action-view" data-bs-toggle="modal" data-bs-target="#detailProductModal"
                                    data-id="SP005"
                                    data-name="Xiaomi Pad 6 Pro"
                                    data-sku="XM-PAD6P-128"
                                    data-brand="Xiaomi"
                                    data-category="May tinh bang"
                                    data-supplier="Xiaomi Vietnam"
                                    data-price="9.990.000 đ"
                                    data-cost="7.500.000 đ"
                                    data-profit="2.490.000 đ"
                                    data-description="Máy tính bảng hiệu năng tốt, màn hình đẹp, phù hợp học tập, làm việc và giải trí."
                                    data-status="Out of Stock"
                                    data-created="2024-02-28"
                                    data-image="<?= BASE_URL ?>/assets/images/xiaomi-pad-6-pro.jpg">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button type="button" class="action-btn action-edit" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                    data-id="SP005"
                                    data-name="Xiaomi Pad 6 Pro"
                                    data-sku="XM-PAD6P-128"
                                    data-brand="Xiaomi"
                                    data-category="May tinh bang"
                                    data-supplier="Xiaomi Vietnam"
                                    data-price="9990000"
                                    data-cost="7500000"
                                    data-description="Máy tính bảng hiệu năng tốt, màn hình đẹp, phù hợp học tập, làm việc và giải trí."
                                    data-status="Out of Stock"
                                    data-created="2024-02-28"
                                    data-image="<?= BASE_URL ?>/assets/images/xiaomi-pad-6-pro.jpg">
                                    <i class="fas fa-pen"></i>
                                </button>

                                <button type="button" class="action-btn action-delete" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-id="SP005" data-name="Xiaomi Pad 6 Pro">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="table-thumb">
                                <img src="<?= BASE_URL ?>/assets/images/galaxy-buds2-pro.jpg" alt="Samsung Galaxy Buds2 Pro">
                            </div>
                        </td>
                        <td>SP006</td>
                        <td class="product-name-cell">Samsung Galaxy Buds2 Pro</td>
                        <td>Samsung</td>
                        <td>Tai nghe</td>
                        <td>Samsung Electronics VN</td>
                        <td class="price-text">4.490.000 đ</td>
                        <td class="cost-text">3.200.000 đ</td>
                        <td>SS-BUDS2P</td>
                        <td><span class="status-badge status-discontinued">Discontinued</span></td>
                        <td>2023-06-15</td>
                        <td class="text-center">
                            <div class="table-actions">
                                <button type="button" class="action-btn action-view" data-bs-toggle="modal" data-bs-target="#detailProductModal"
                                    data-id="SP006"
                                    data-name="Samsung Galaxy Buds2 Pro"
                                    data-sku="SS-BUDS2P"
                                    data-brand="Samsung"
                                    data-category="Tai nghe"
                                    data-supplier="Samsung Electronics VN"
                                    data-price="4.490.000 đ"
                                    data-cost="3.200.000 đ"
                                    data-profit="1.290.000 đ"
                                    data-description="Tai nghe không dây nhỏ gọn, âm thanh tốt, chống ồn và phù hợp dùng hằng ngày."
                                    data-status="Discontinued"
                                    data-created="2023-06-15"
                                    data-image="<?= BASE_URL ?>/assets/images/galaxy-buds2-pro.jpg">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button type="button" class="action-btn action-edit" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                    data-id="SP006"
                                    data-name="Samsung Galaxy Buds2 Pro"
                                    data-sku="SS-BUDS2P"
                                    data-brand="Samsung"
                                    data-category="Tai nghe"
                                    data-supplier="Samsung Electronics VN"
                                    data-price="4490000"
                                    data-cost="3200000"
                                    data-description="Tai nghe không dây nhỏ gọn, âm thanh tốt, chống ồn và phù hợp dùng hằng ngày."
                                    data-status="Discontinued"
                                    data-created="2023-06-15"
                                    data-image="<?= BASE_URL ?>/assets/images/galaxy-buds2-pro.jpg">
                                    <i class="fas fa-pen"></i>
                                </button>

                                <button type="button" class="action-btn action-delete" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-id="SP006" data-name="Samsung Galaxy Buds2 Pro">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="table-thumb">
                                <img src="<?= BASE_URL ?>/assets/images/airpods-pro-2.jpg" alt="Apple AirPods Pro 2">
                            </div>
                        </td>
                        <td>SP007</td>
                        <td class="product-name-cell">Apple AirPods Pro 2</td>
                        <td>Apple</td>
                        <td>Tai nghe</td>
                        <td>Apple Vietnam</td>
                        <td class="price-text">6.790.000 đ</td>
                        <td class="cost-text">5.500.000 đ</td>
                        <td>APL-APP2-USBC</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>2024-01-10</td>
                        <td class="text-center">
                            <div class="table-actions">
                                <button type="button" class="action-btn action-view" data-bs-toggle="modal" data-bs-target="#detailProductModal"
                                    data-id="SP007"
                                    data-name="Apple AirPods Pro 2"
                                    data-sku="APL-APP2-USBC"
                                    data-brand="Apple"
                                    data-category="Tai nghe"
                                    data-supplier="Apple Vietnam"
                                    data-price="6.790.000 đ"
                                    data-cost="5.500.000 đ"
                                    data-profit="1.290.000 đ"
                                    data-description="Tai nghe không dây cao cấp, kết nối nhanh, âm thanh tốt, phù hợp hệ sinh thái Apple."
                                    data-status="Active"
                                    data-created="2024-01-10"
                                    data-image="<?= BASE_URL ?>/assets/images/airpods-pro-2.jpg">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button type="button" class="action-btn action-edit" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                    data-id="SP007"
                                    data-name="Apple AirPods Pro 2"
                                    data-sku="APL-APP2-USBC"
                                    data-brand="Apple"
                                    data-category="Tai nghe"
                                    data-supplier="Apple Vietnam"
                                    data-price="6790000"
                                    data-cost="5500000"
                                    data-description="Tai nghe không dây cao cấp, kết nối nhanh, âm thanh tốt, phù hợp hệ sinh thái Apple."
                                    data-status="Active"
                                    data-created="2024-01-10"
                                    data-image="<?= BASE_URL ?>/assets/images/airpods-pro-2.jpg">
                                    <i class="fas fa-pen"></i>
                                </button>

                                <button type="button" class="action-btn action-delete" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-id="SP007" data-name="Apple AirPods Pro 2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="table-thumb">
                                <img src="<?= BASE_URL ?>/assets/images/xiaomi14-ultra.jpg" alt="Xiaomi 14 Ultra">
                            </div>
                        </td>
                        <td>SP008</td>
                        <td class="product-name-cell">Xiaomi 14 Ultra</td>
                        <td>Xiaomi</td>
                        <td>Dien Thoai</td>
                        <td>Xiaomi Vietnam</td>
                        <td class="price-text">23.990.000 đ</td>
                        <td class="cost-text">19.500.000 đ</td>
                        <td>XM-14U-512</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>2024-03-20</td>
                        <td class="text-center">
                            <div class="table-actions">
                                <button type="button" class="action-btn action-view" data-bs-toggle="modal" data-bs-target="#detailProductModal"
                                    data-id="SP008"
                                    data-name="Xiaomi 14 Ultra"
                                    data-sku="XM-14U-512"
                                    data-brand="Xiaomi"
                                    data-category="Dien Thoai"
                                    data-supplier="Xiaomi Vietnam"
                                    data-price="23.990.000 đ"
                                    data-cost="19.500.000 đ"
                                    data-profit="4.490.000 đ"
                                    data-description="Điện thoại flagship hiệu năng mạnh, camera tốt và thiết kế cao cấp."
                                    data-status="Active"
                                    data-created="2024-03-20"
                                    data-image="<?= BASE_URL ?>/assets/images/xiaomi14-ultra.jpg">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button type="button" class="action-btn action-edit" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                    data-id="SP008"
                                    data-name="Xiaomi 14 Ultra"
                                    data-sku="XM-14U-512"
                                    data-brand="Xiaomi"
                                    data-category="Dien Thoai"
                                    data-supplier="Xiaomi Vietnam"
                                    data-price="23990000"
                                    data-cost="19500000"
                                    data-description="Điện thoại flagship hiệu năng mạnh, camera tốt và thiết kế cao cấp."
                                    data-status="Active"
                                    data-created="2024-03-20"
                                    data-image="<?= BASE_URL ?>/assets/images/xiaomi14-ultra.jpg">
                                    <i class="fas fa-pen"></i>
                                </button>

                                <button type="button" class="action-btn action-delete" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-id="SP008" data-name="Xiaomi 14 Ultra">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="table-thumb">
                                <img src="<?= BASE_URL ?>/assets/images/anker-cable.jpg" alt="Cáp USB-C Anker PowerLine III">
                            </div>
                        </td>
                        <td>SP009</td>
                        <td class="product-name-cell">Cáp USB-C Anker PowerLine III</td>
                        <td>Apple</td>
                        <td>Phụ kiện</td>
                        <td>Anker Vietnam</td>
                        <td class="price-text">350.000 đ</td>
                        <td class="cost-text">180.000 đ</td>
                        <td>ANK-PL3-USBC</td>
                        <td><span class="status-badge status-active">Active</span></td>
                        <td>2024-04-01</td>
                        <td class="text-center">
                            <div class="table-actions">
                                <button type="button" class="action-btn action-view" data-bs-toggle="modal" data-bs-target="#detailProductModal"
                                    data-id="SP009"
                                    data-name="Cáp USB-C Anker PowerLine III"
                                    data-sku="ANK-PL3-USBC"
                                    data-brand="Apple"
                                    data-category="Phụ kiện"
                                    data-supplier="Anker Vietnam"
                                    data-price="350.000 đ"
                                    data-cost="180.000 đ"
                                    data-profit="170.000 đ"
                                    data-description="Cáp sạc bền, truyền dữ liệu ổn định, phù hợp dùng với nhiều thiết bị."
                                    data-status="Active"
                                    data-created="2024-04-01"
                                    data-image="<?= BASE_URL ?>/assets/images/anker-cable.jpg">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button type="button" class="action-btn action-edit" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                    data-id="SP009"
                                    data-name="Cáp USB-C Anker PowerLine III"
                                    data-sku="ANK-PL3-USBC"
                                    data-brand="Apple"
                                    data-category="Phụ kiện"
                                    data-supplier="Anker Vietnam"
                                    data-price="350000"
                                    data-cost="180000"
                                    data-description="Cáp sạc bền, truyền dữ liệu ổn định, phù hợp dùng với nhiều thiết bị."
                                    data-status="Active"
                                    data-created="2024-04-01"
                                    data-image="<?= BASE_URL ?>/assets/images/anker-cable.jpg">
                                    <i class="fas fa-pen"></i>
                                </button>

                                <button type="button" class="action-btn action-delete" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-id="SP009" data-name="Cáp USB-C Anker PowerLine III">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-footer-bar">
            <span>Trang 1 / 2</span>

            <div class="pagination-box">
                <button class="page-btn">Trước</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">Sau</button>
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
                            <input type="text" class="form-control" placeholder="VD: SP013">
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

                        <div class="col-md-4">
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

                <div class="detail-footer-actions">
                    <button type="button" class="btn btn-warning btn-edit-orange text-white" id="detailEditButton">
                        <i class="fas fa-pen me-2"></i>Sửa sản phẩm
                    </button>

                    <button type="button" class="btn btn-danger btn-delete-red" id="detailDeleteButton">
                        <i class="fas fa-trash me-2"></i>Xóa sản phẩm
                    </button>
                </div>
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

<?php include __DIR__ . '/../layouts/footer.php'; ?>