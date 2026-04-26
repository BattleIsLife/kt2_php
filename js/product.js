document.addEventListener("DOMContentLoaded", function () {

    // =============================================================
    // 0. DATA TỪ CONTROLLER (window.APP_DATA inject bởi PHP)
    // =============================================================
    const BASE_URL   = window.APP_DATA?.baseUrl    || '';
    const brands     = window.APP_DATA?.brands     || [];
    const categories = window.APP_DATA?.categories || [];
    const suppliers  = window.APP_DATA?.suppliers  || [];

    // =============================================================
    // 1. SETUP FORM CREATE — gán id/name, populate select từ DB
    // =============================================================
    function setupCreateForm() {
        const modal = document.getElementById('createProductModal');
        if (!modal) return;

        // Lấy inputs theo thứ tự trong HTML view gốc
        // [0]=Mã SP (bỏ), [1]=SKU, [2]=Tên SP, [3]=NCC(text->replace), [4]=Giá bán, [5]=Giá nhập
        const inputs  = modal.querySelectorAll('input.form-control');
        const selects = modal.querySelectorAll('select.form-select');
        const textarea = modal.querySelector('textarea.form-control');

        if (inputs[1]) { inputs[1].id = 'createSku';   inputs[1].name = 'sku'; }
        if (inputs[2]) { inputs[2].id = 'createName';  inputs[2].name = 'product_name'; }
        if (inputs[4]) { inputs[4].id = 'createPrice'; inputs[4].name = 'price'; }
        if (inputs[5]) { inputs[5].id = 'createCost';  inputs[5].name = 'cost_price'; }
        if (textarea)  { textarea.id  = 'createDesc';  textarea.name  = 'description'; }

        // selects[0]=Brand, [1]=Category, [2]=Status
        if (selects[0]) populateSelect(selects[0], brands,     'brand_id',    'brand_name',    'createBrand',    'Chọn thương hiệu');
        if (selects[1]) populateSelect(selects[1], categories, 'category_id', 'category_name', 'createCategory', 'Chọn danh mục');
        if (selects[2]) {
            selects[2].id = 'createStatus';
            selects[2].querySelectorAll('option').forEach(opt => {
                opt.value = statusToValue(opt.textContent.trim());
            });
        }

        // Thay input Nhà cung cấp (inputs[3]) thành <select>
        if (inputs[3]) replaceWithSelect(inputs[3], suppliers, 'supplier_id', 'supplier_name', 'createSupplier', 'Chọn nhà cung cấp');
    }

    // =============================================================
    // 2. SETUP FORM EDIT — gán name, populate select từ DB
    // =============================================================
    function setupEditForm() {
        const modal = document.getElementById('editProductModal');
        if (!modal) return;

        // View đã có id, chỉ cần gán name
        const nameMap = {
            editProductId:   'product_id',
            editSku:         'sku',
            editName:        'product_name',
            editPrice:       'price',
            editCost:        'cost_price',
            editDescription: 'description',
        };
        Object.entries(nameMap).forEach(([id, name]) => {
            const el = document.getElementById(id);
            if (el) el.name = name;
        });

        // Populate Brand, Category
        const editBrand = document.getElementById('editBrand');
        if (editBrand) populateSelect(editBrand, brands, 'brand_id', 'brand_name', 'editBrand', 'Chọn thương hiệu');

        const editCat = document.getElementById('editCategory');
        if (editCat) populateSelect(editCat, categories, 'category_id', 'category_name', 'editCategory', 'Chọn danh mục');

        // Status: gán value đúng
        const editStatus = document.getElementById('editStatus');
        if (editStatus) {
            editStatus.querySelectorAll('option').forEach(opt => {
                opt.value = statusToValue(opt.textContent.trim());
            });
        }

        // Thay input Nhà cung cấp thành <select>
        const editSupplierInput = document.getElementById('editSupplier');
        if (editSupplierInput && editSupplierInput.tagName === 'INPUT') {
            replaceWithSelect(editSupplierInput, suppliers, 'supplier_id', 'supplier_name', 'editSupplier', 'Chọn nhà cung cấp');
        }
    }

    function populateSelect(el, data, valueKey, labelKey, newId, placeholder) {
        el.innerHTML = '';
        if (placeholder) {
            const opt = document.createElement('option');
            opt.value = ''; opt.textContent = placeholder;
            el.appendChild(opt);
        }
        data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item[valueKey];
            opt.textContent = item[labelKey];
            el.appendChild(opt);
        });
        el.id   = newId;
        el.name = valueKey;
    }

    function replaceWithSelect(inputEl, data, valueKey, labelKey, newId, placeholder) {
        const sel = document.createElement('select');
        sel.className = inputEl.className;
        sel.id        = newId;
        sel.name      = valueKey;
        if (placeholder) {
            const opt = document.createElement('option');
            opt.value = ''; opt.textContent = placeholder;
            sel.appendChild(opt);
        }
        data.forEach(item => {
            const opt = document.createElement('option');
            opt.value = item[valueKey];
            opt.textContent = item[labelKey];
            sel.appendChild(opt);
        });
        inputEl.parentNode.replaceChild(sel, inputEl);
    }

    function statusToValue(text) {
        const map = { 'Active': 'active', 'Out of Stock': 'out_of_stock', 'Discontinued': 'discontinued' };
        return map[text] || text.toLowerCase().replace(/ /g, '_');
    }

    setupCreateForm();
    setupEditForm();

    // =============================================================
    // 3. RENDER BẢNG từ mảng products
    // =============================================================
    function formatVND(num) {
        return Number(num).toLocaleString('vi-VN') + ' đ';
    }

    function getStatusClass(status) {
        const s = (status || '').toLowerCase();
        if (s === 'active')       return 'status-active';
        if (s === 'out_of_stock') return 'status-out';
        if (s === 'discontinued') return 'status-discontinued';
        return 'status-active';
    }

    function esc(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    }

    function resolveImageUrl(imagePath) {
        const fallback = BASE_URL + '/assets/images/no-image.png';
        const raw = String(imagePath || '').trim();
        if (!raw) return fallback;
        if (/^(https?:)?\/\//i.test(raw)) return raw;
        if (BASE_URL && raw.startsWith(BASE_URL)) return raw;
        if (/^images\//i.test(raw)) return BASE_URL + '/assets/' + raw.replace(/^\/+/, '');
        if (raw.startsWith('/')) return raw;
        return BASE_URL + '/' + raw.replace(/^\/+/, '');
    }

    function renderTable(products) {
        const tbody = document.querySelector('.product-table tbody');
        if (!tbody) return;

        if (!products || products.length === 0) {
            tbody.innerHTML = `<tr><td colspan="12" class="text-center text-muted py-4">Chưa có sản phẩm nào.</td></tr>`;
            return;
        }

        tbody.innerHTML = products.map(p => {
            const imgSrc  = resolveImageUrl(p.image);
            const profit  = Number(p.price) - Number(p.cost_price);
            const created = (p.created_at || '').substring(0, 10);

            return `
            <tr data-brand="${esc(p.brand_name)}" data-supplier="${esc(p.supplier_name)}" data-status="${esc(p.status)}" data-name="${esc(p.product_name)}" data-sku="${esc(p.sku)}">
                <td><div class="table-thumb"><img src="${imgSrc}" alt="${esc(p.product_name)}" onerror="this.onerror=null;this.src='${BASE_URL}/assets/images/no-image.png';"></div></td>
                <td>${esc(String(p.product_id))}</td>
                <td class="product-name-cell">${esc(p.product_name)}</td>
                <td>${esc(p.brand_name)}</td>
                <td>${esc(p.category_name)}</td>
                <td>${esc(p.supplier_name)}</td>
                <td class="price-text">${formatVND(p.price)}</td>
                <td class="cost-text">${formatVND(p.cost_price)}</td>
                <td>${esc(p.sku)}</td>
                <td><span class="status-badge ${getStatusClass(p.status)}">${esc(p.status)}</span></td>
                <td>${created}</td>
                <td class="text-center">
                    <div class="table-actions">
                        <button type="button" class="action-btn action-view"
                            data-bs-toggle="modal" data-bs-target="#detailProductModal"
                            data-id="${p.product_id}"
                            data-name="${esc(p.product_name)}"
                            data-sku="${esc(p.sku)}"
                            data-brand="${esc(p.brand_name)}"
                            data-category="${esc(p.category_name)}"
                            data-supplier="${esc(p.supplier_name)}"
                            data-price="${formatVND(p.price)}"
                            data-cost="${formatVND(p.cost_price)}"
                            data-profit="${formatVND(profit)}"
                            data-description="${esc(p.description || '')}"
                            data-status="${esc(p.status)}"
                            data-created="${created}"
                            data-image="${imgSrc}"
                            data-brand-id="${p.brand_id}"
                            data-category-id="${p.category_id}"
                            data-supplier-id="${p.supplier_id}"
                        ><i class="fas fa-eye"></i></button>

                        <button type="button" class="action-btn action-edit"
                            data-bs-toggle="modal" data-bs-target="#editProductModal"
                            data-id="${p.product_id}"
                            data-name="${esc(p.product_name)}"
                            data-sku="${esc(p.sku)}"
                            data-brand-id="${p.brand_id}"
                            data-category-id="${p.category_id}"
                            data-supplier-id="${p.supplier_id}"
                            data-price="${p.price}"
                            data-cost="${p.cost_price}"
                            data-description="${esc(p.description || '')}"
                            data-status="${esc(p.status)}"
                            data-image="${imgSrc}"
                        ><i class="fas fa-pen"></i></button>

                        <button type="button" class="action-btn action-delete"
                            data-bs-toggle="modal" data-bs-target="#deleteProductModal"
                            data-id="${p.product_id}"
                            data-name="${esc(p.product_name)}"
                        ><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>`;
        }).join('');
    }

    // Render lần đầu từ data PHP inject
    if (window.APP_DATA?.products) renderTable(window.APP_DATA.products);

    // =============================================================
    // 4. MODAL DETAIL
    // =============================================================
    document.getElementById('detailProductModal')?.addEventListener('show.bs.modal', function (e) {
        const d = e.relatedTarget?.dataset;
        if (!d) return;

        setText('detailId',          d.id);
        setText('detailName',        d.name);
        setText('detailSku',         d.sku);
        setText('detailBrand',       d.brand);
        setText('detailCategory',    d.category);
        setText('detailSupplier',    d.supplier);
        setText('detailPrice',       d.price);
        setText('detailCost',        d.cost);
        setText('detailProfit',      d.profit);
        setText('detailDescription', d.description);
        setText('detailCreated',     d.created);

        const img = document.getElementById('detailImage');
        if (img) img.src = resolveImageUrl(d.image);

        const badge = document.getElementById('detailStatusBadge');
        if (badge) {
            badge.textContent = d.status;
            badge.className   = 'status-badge ' + getStatusClass(d.status);
        }

        // Truyền data cho nút Sửa/Xóa ở footer detail modal
        const editBtn   = document.getElementById('detailEditButton');
        const deleteBtn = document.getElementById('detailDeleteButton');
        if (editBtn) {
            editBtn.dataset.id          = d.id;
            editBtn.dataset.name        = d.name;
            editBtn.dataset.sku         = d.sku;
            editBtn.dataset.brandId     = d.brandId;
            editBtn.dataset.categoryId  = d.categoryId;
            editBtn.dataset.supplierId  = d.supplierId;
            editBtn.dataset.price       = d.price;
            editBtn.dataset.cost        = d.cost;
            editBtn.dataset.description = d.description;
            editBtn.dataset.status      = d.status;
            editBtn.dataset.image       = d.image;
        }
        if (deleteBtn) {
            deleteBtn.dataset.id   = d.id;
            deleteBtn.dataset.name = d.name;
        }
    });

    // =============================================================
    // 5. MODAL EDIT — điền form
    // =============================================================
    document.getElementById('editProductModal')?.addEventListener('show.bs.modal', function (e) {
        const d = e.relatedTarget?.dataset;
        if (!d) return;
        fillEditForm(d);
    });

    function fillEditForm(d) {
        setVal('editProductId',   d.id);
        setVal('editSku',         d.sku);
        setVal('editName',        d.name);
        setVal('editPrice',       d.price);
        setVal('editCost',        d.cost);
        setVal('editDescription', d.description);

        // Status
        const statusSel = document.getElementById('editStatus');
        if (statusSel) statusSel.value = statusToValue(d.status || 'Active');

        // Brand / Category / Supplier bằng ID
        const brandSel    = document.getElementById('editBrand');
        const catSel      = document.getElementById('editCategory');
        const supplierSel = document.getElementById('editSupplier');
        if (brandSel)    brandSel.value    = d.brandId    || '';
        if (catSel)      catSel.value      = d.categoryId || '';
        if (supplierSel) supplierSel.value = d.supplierId || '';

        // Preview ảnh hiện tại
        const preview = document.getElementById('editPreviewImage');
        if (preview) preview.src = d.image || BASE_URL + '/assets/images/no-image.png';

        // Reset file input và base64
        const fileInput = document.getElementById('editImageInput');
        if (fileInput) fileInput.value = '';
        editImageBase64 = '';
        const fileName = document.getElementById('editFileName');
        if (fileName) fileName.textContent = 'Chưa chọn tệp nào';
    }

    // =============================================================
    // 6. MODAL DELETE
    // =============================================================
    document.getElementById('deleteProductModal')?.addEventListener('show.bs.modal', function (e) {
        const d = e.relatedTarget?.dataset;
        if (!d) return;
        setText('deleteProductName', d.name);
        setText('deleteProductId',  d.id);
    });

    // =============================================================
    // 7. NÚT SỬA / XÓA TRONG FOOTER DETAIL MODAL
    // =============================================================
    document.getElementById('detailEditButton')?.addEventListener('click', function () {
        bootstrap.Modal.getInstance(document.getElementById('detailProductModal'))?.hide();
        setTimeout(() => {
            fillEditForm(this.dataset);
            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        }, 250);
    });

    document.getElementById('detailDeleteButton')?.addEventListener('click', function () {
        bootstrap.Modal.getInstance(document.getElementById('detailProductModal'))?.hide();
        setTimeout(() => {
            setText('deleteProductName', this.dataset.name);
            setText('deleteProductId',  this.dataset.id);
            new bootstrap.Modal(document.getElementById('deleteProductModal')).show();
        }, 250);
    });

    // =============================================================
    // 8. PREVIEW ẢNH + LƯU BASE64
    // =============================================================
    let createImageBase64 = '';
    let editImageBase64   = '';

    function setupImagePreview(inputId, previewId, fileNameId, onBase64) {
        const input = document.getElementById(inputId);
        if (!input) return;
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                const b64 = e.target.result;
                const preview = document.getElementById(previewId);
                const label   = document.getElementById(fileNameId);
                if (preview) preview.src = b64;
                if (label)   label.textContent = file.name;
                onBase64(b64);
            };
            reader.readAsDataURL(file);
        });
    }

    setupImagePreview('createImageInput', 'createPreviewImage', 'createFileName', b64 => { createImageBase64 = b64; });
    setupImagePreview('editImageInput',   'editPreviewImage',   'editFileName',   b64 => { editImageBase64   = b64; });

    // =============================================================
    // 9. AJAX — gửi JSON, nhận JSON
    // =============================================================
    async function ajaxJSON(url, payload) {
        const res = await fetch(url, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(payload),
        });
        return res.json();
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) bootstrap.Modal.getInstance(el)?.hide();
    }

    function showToast(msg, ok = true) {
        let t = document.getElementById('_appToast');
        if (!t) {
            t = document.createElement('div');
            t.id = '_appToast';
            t.style.cssText = `
                position:fixed; bottom:24px; right:24px; z-index:99999;
                padding:12px 20px; border-radius:8px; color:#fff;
                font-size:14px; font-weight:500;
                box-shadow:0 4px 12px rgba(0,0,0,.2);
                transition:opacity .4s;
            `;
            document.body.appendChild(t);
        }
        t.textContent      = msg;
        t.style.background = ok ? '#198754' : '#dc3545';
        t.style.opacity    = '1';
        clearTimeout(t._timer);
        t._timer = setTimeout(() => { t.style.opacity = '0'; }, 3000);
    }

    function getVal(id)  { return document.getElementById(id)?.value       || ''; }
    function getText(id) { return document.getElementById(id)?.textContent || ''; }
    function setVal(id, val)  { const el = document.getElementById(id); if (el) el.value       = val || ''; }
    function setText(id, val) { const el = document.getElementById(id); if (el) el.textContent = val || ''; }
    // =============================================================
    // 10. CREATE
    // =============================================================
    document.querySelector('#createProductModal .btn-modal-submit')?.addEventListener('click', async function () {
        const payload = {
            product_name: getVal('createName'),
            sku:          getVal('createSku'),
            brand_id:     getVal('createBrand'),
            category_id:  getVal('createCategory'),
            supplier_id:  getVal('createSupplier'),
            price:        getVal('createPrice'),
            cost_price:   getVal('createCost'),
            description:  getVal('createDesc'),
            status:       getVal('createStatus') || 'active',
            image:        createImageBase64,
        };

        this.disabled = true; this.textContent = 'Đang lưu...';
        try {
            const json = await ajaxJSON(BASE_URL + '/product/create', payload);
            if (json.success) {
                renderTable(json.products);
                // Reset form
                ['createName','createSku','createPrice','createCost','createDesc'].forEach(id => setVal(id, ''));
                ['createBrand','createCategory','createSupplier'].forEach(id => setVal(id, ''));
                createImageBase64 = '';
                const preview = document.getElementById('createPreviewImage');
                if (preview) preview.src = BASE_URL + '/assets/images/no-image.png';
                const label = document.getElementById('createFileName');
                if (label) label.textContent = 'Chưa chọn tệp nào';
                const fileInput = document.getElementById('createImageInput');
                if (fileInput) fileInput.value = '';
                closeModal('createProductModal');
                showToast(json.message);
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                showToast(json.message, false);
            }
        } catch (e) {
            showToast('Lỗi kết nối server', false);
            console.error(e);
        } finally {
            this.disabled = false;
            this.innerHTML = '<i class="far fa-floppy-disk me-2"></i>Thêm sản phẩm';
        }
    });

    // =============================================================
    // 11. EDIT
    // =============================================================
    document.querySelector('#editProductModal .btn-modal-submit')?.addEventListener('click', async function () {
        const payload = {
            product_id:   getVal('editProductId'),
            product_name: getVal('editName'),
            sku:          getVal('editSku'),
            brand_id:     getVal('editBrand'),
            category_id:  getVal('editCategory'),
            supplier_id:  getVal('editSupplier'),
            price:        getVal('editPrice'),
            cost_price:   getVal('editCost'),
            description:  getVal('editDescription'),
            status:       getVal('editStatus') || 'active',
            image:        editImageBase64, // '' nếu không đổi ảnh → giữ ảnh cũ
        };

        this.disabled = true; this.textContent = 'Đang lưu...';
        try {
            const json = await ajaxJSON(BASE_URL + '/product/edit', payload);
            if (json.success) {
                renderTable(json.products);
                closeModal('editProductModal');
                showToast(json.message);
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                showToast(json.message, false);
            }
        } catch (e) {
            showToast('Lỗi kết nối server', false);
            console.error(e);
        } finally {
            this.disabled = false;
            this.innerHTML = '<i class="far fa-floppy-disk me-2"></i>Lưu thay đổi';
        }
    });

    // =============================================================
    // 12. DELETE
    // =============================================================
    document.querySelector('#deleteProductModal .btn-delete-red')?.addEventListener('click', async function () {
        const product_id = getText('deleteProductId');
        if (!product_id) return;

        this.disabled = true; this.textContent = 'Đang xóa...';
        try {
            const json = await ajaxJSON(BASE_URL + '/product/delete', { product_id: parseInt(product_id) });
            if (json.success) {
                renderTable(json.products);
                closeModal('deleteProductModal');
                showToast(json.message);
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                showToast(json.message, false);
            }
        } catch (e) {
            showToast('Lỗi kết nối server', false);
            console.error(e);
        } finally {
            this.disabled = false;
            this.textContent = 'Xóa sản phẩm';
        }
    });

    // Bộ lọc dùng server-side qua form GET trong view.

});