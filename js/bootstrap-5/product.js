document.addEventListener("DOMContentLoaded", function () {
    const resetBtn = document.getElementById("btnResetFilter");

    if (resetBtn) {
        resetBtn.addEventListener("click", function () {
            document.querySelectorAll(".filter-panel input, .filter-panel select").forEach(el => {
                if (el.tagName === "SELECT") {
                    el.selectedIndex = 0;
                } else {
                    el.value = "";
                }
            });
        });
    }

    function previewLocalImage(input, previewImg) {
        if (!input || !previewImg) return;

        input.addEventListener("change", function (e) {
            const file = e.target.files[0];
            if (file) {
                previewImg.src = URL.createObjectURL(file);
            }
        });
    }

    previewLocalImage(
        document.getElementById("createImageInput"),
        document.getElementById("createPreviewImage")
    );

    previewLocalImage(
        document.getElementById("editImageInput"),
        document.getElementById("editPreviewImage")
    );

    function getStatusClass(status) {
        const normalized = (status || "").toLowerCase();

        if (normalized === "active") return "status-active";
        if (normalized === "out of stock") return "status-out";
        if (normalized === "discontinued") return "status-discontinued";
        return "status-active";
    }

    const detailModal = document.getElementById("detailProductModal");
    if (detailModal) {
        detailModal.addEventListener("show.bs.modal", function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const id = button.getAttribute("data-id") || "";
            const name = button.getAttribute("data-name") || "";
            const sku = button.getAttribute("data-sku") || "";
            const brand = button.getAttribute("data-brand") || "";
            const category = button.getAttribute("data-category") || "";
            const supplier = button.getAttribute("data-supplier") || "";
            const price = button.getAttribute("data-price") || "";
            const cost = button.getAttribute("data-cost") || "";
            const profit = button.getAttribute("data-profit") || "";
            const description = button.getAttribute("data-description") || "";
            const status = button.getAttribute("data-status") || "";
            const created = button.getAttribute("data-created") || "";
            const image = button.getAttribute("data-image") || "";

            document.getElementById("detailId").textContent = id;
            document.getElementById("detailName").textContent = name;
            document.getElementById("detailSku").textContent = sku;
            document.getElementById("detailBrand").textContent = brand;
            document.getElementById("detailCategory").textContent = category;
            document.getElementById("detailSupplier").textContent = supplier;
            document.getElementById("detailPrice").textContent = price;
            document.getElementById("detailCost").textContent = cost;
            document.getElementById("detailProfit").textContent = profit;
            document.getElementById("detailDescription").textContent = description;
            document.getElementById("detailCreated").textContent = created;
            document.getElementById("detailImage").src = image;

            const badge = document.getElementById("detailStatusBadge");
            badge.textContent = status;
            badge.className = "status-badge " + getStatusClass(status);

            const detailEditButton = document.getElementById("detailEditButton");
            const detailDeleteButton = document.getElementById("detailDeleteButton");

            detailEditButton.dataset.id = id;
            detailEditButton.dataset.name = name;
            detailEditButton.dataset.sku = sku;
            detailEditButton.dataset.brand = brand;
            detailEditButton.dataset.category = category;
            detailEditButton.dataset.supplier = supplier;
            detailEditButton.dataset.price = button.getAttribute("data-price").replace(/\./g, "").replace(" đ", "");
            detailEditButton.dataset.cost = button.getAttribute("data-cost").replace(/\./g, "").replace(" đ", "");
            detailEditButton.dataset.description = description;
            detailEditButton.dataset.status = status;
            detailEditButton.dataset.created = created;
            detailEditButton.dataset.image = image;

            detailDeleteButton.dataset.id = id;
            detailDeleteButton.dataset.name = name;
        });
    }

    const editModal = document.getElementById("editProductModal");
    if (editModal) {
        editModal.addEventListener("show.bs.modal", function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            document.getElementById("editProductId").value = button.getAttribute("data-id") || "";
            document.getElementById("editSku").value = button.getAttribute("data-sku") || "";
            document.getElementById("editName").value = button.getAttribute("data-name") || "";
            document.getElementById("editBrand").value = button.getAttribute("data-brand") || "";
            document.getElementById("editCategory").value = button.getAttribute("data-category") || "";
            document.getElementById("editSupplier").value = button.getAttribute("data-supplier") || "";
            document.getElementById("editPrice").value = button.getAttribute("data-price") || "";
            document.getElementById("editCost").value = button.getAttribute("data-cost") || "";
            document.getElementById("editDescription").value = button.getAttribute("data-description") || "";
            document.getElementById("editStatus").value = button.getAttribute("data-status") || "";
            document.getElementById("editPreviewImage").src = button.getAttribute("data-image") || "";
            document.getElementById("editImageInput").value = "";
        });
    }

    const deleteModal = document.getElementById("deleteProductModal");
    if (deleteModal) {
        deleteModal.addEventListener("show.bs.modal", function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            document.getElementById("deleteProductName").textContent = button.getAttribute("data-name") || "";
            document.getElementById("deleteProductId").textContent = button.getAttribute("data-id") || "";
        });
    }

    const detailEditButton = document.getElementById("detailEditButton");
    if (detailEditButton) {
        detailEditButton.addEventListener("click", function () {
            const detailModalInstance = bootstrap.Modal.getInstance(document.getElementById("detailProductModal"));
            if (detailModalInstance) detailModalInstance.hide();

            setTimeout(() => {
                document.getElementById("editProductId").value = this.dataset.id || "";
                document.getElementById("editSku").value = this.dataset.sku || "";
                document.getElementById("editName").value = this.dataset.name || "";
                document.getElementById("editBrand").value = this.dataset.brand || "";
                document.getElementById("editCategory").value = this.dataset.category || "";
                document.getElementById("editSupplier").value = this.dataset.supplier || "";
                document.getElementById("editPrice").value = this.dataset.price || "";
                document.getElementById("editCost").value = this.dataset.cost || "";
                document.getElementById("editDescription").value = this.dataset.description || "";
                document.getElementById("editStatus").value = this.dataset.status || "";
                document.getElementById("editPreviewImage").src = this.dataset.image || "";
                document.getElementById("editImageInput").value = "";

                const modal = new bootstrap.Modal(document.getElementById("editProductModal"));
                modal.show();
            }, 250);
        });
    }

    const detailDeleteButton = document.getElementById("detailDeleteButton");
    if (detailDeleteButton) {
        detailDeleteButton.addEventListener("click", function () {
            const detailModalInstance = bootstrap.Modal.getInstance(document.getElementById("detailProductModal"));
            if (detailModalInstance) detailModalInstance.hide();

            setTimeout(() => {
                document.getElementById("deleteProductName").textContent = this.dataset.name || "";
                document.getElementById("deleteProductId").textContent = this.dataset.id || "";

                const modal = new bootstrap.Modal(document.getElementById("deleteProductModal"));
                modal.show();
            }, 250);
        });
    }
});

function setupImageUpload(inputId, previewId, fileNameId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    const fileName = document.getElementById(fileNameId);

    if (!input || !preview || !fileName) return;

    input.addEventListener("change", function (e) {
        const file = e.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            fileName.textContent = file.name;
        } else {
            fileName.textContent = "Chưa chọn tệp nào";
        }
    });
}
    // 👇 GỌI Ở ĐÂY
    setupImageUpload("createImageInput", "createPreviewImage", "createFileName");
    setupImageUpload("editImageInput", "editPreviewImage", "editFileName");
