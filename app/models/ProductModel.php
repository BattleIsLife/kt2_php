<?php

class ProductModel extends Model implements RepositoryInterface
{
        /* 
     * Các thuộc tính trong ProductModel:
     * - product_id: mã sản phẩm
     * - product_name: tên sản phẩm
     * - brand_id: mã thương hiệu
     * - category_id: mã thể loại
     * - supplier_id: mã nhà cung cấp
     * - price: giá bán
     * - cost_price: giá nhập
     * - description: mô tả
     * - sku: mã vạch
     * - status: trạng thái (active, discontinued, out_of_stock)
     * - created_at: ngày tạo
    */
    public function create($data)
    {
        $sql = "INSERT INTO product (product_name, brand_id, category_id, supplier_id,
                                     price, cost_price, description, sku, image, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        return $this->db->query($sql, [
            $data['product_name'], $data['brand_id'],    $data['category_id'],
            $data['supplier_id'], $data['price'],        $data['cost_price'],
            $data['description'], $data['sku'],          $data['image'],
            date("Y-m-d H:i:s"),
        ]);
    }

    public function readAll()
    {
        $stmt = $this->db->query(
            "SELECT pr.*, br.brand_name, ct.category_name, sp.supplier_name
               FROM product pr
               JOIN brand    br ON pr.brand_id    = br.brand_id
               JOIN category ct ON pr.category_id = ct.category_id
               JOIN supplier sp ON pr.supplier_id = sp.supplier_id
              WHERE deleted_at IS NULL
              ORDER BY pr.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function countTotal()
    {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM product WHERE deleted_at IS NULL");
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function countFiltered($filters = [])
    {
        [$whereSql, $params] = $this->buildFilterWhere($filters);
        $stmt = $this->db->query(
            "SELECT COUNT(*) as total
               FROM product pr
               JOIN brand br ON pr.brand_id = br.brand_id
               JOIN category ct ON pr.category_id = ct.category_id
               JOIN supplier sp ON pr.supplier_id = sp.supplier_id
              WHERE {$whereSql}",
            $params
        );
        $result = $stmt->fetch();
        return (int) ($result['total'] ?? 0);
    }

    public function readPaginated($page = 1, $itemsPerPage = 5)
    {
        $page = max(1, (int) $page);
        $offset = ($page - 1) * $itemsPerPage;
        
        $stmt = $this->db->query(
            "SELECT pr.*, br.brand_name, ct.category_name, sp.supplier_name
               FROM product pr
               JOIN brand    br ON pr.brand_id    = br.brand_id
               JOIN category ct ON pr.category_id = ct.category_id
               JOIN supplier sp ON pr.supplier_id = sp.supplier_id
              WHERE deleted_at IS NULL
              ORDER BY pr.created_at DESC
              LIMIT ? OFFSET ?",
            [$itemsPerPage, $offset]
        );
        return $stmt->fetchAll();
    }

    public function readPaginatedFiltered($page = 1, $itemsPerPage = 5, $filters = [])
    {
        $page = max(1, (int) $page);
        $offset = ($page - 1) * $itemsPerPage;
        [$whereSql, $params] = $this->buildFilterWhere($filters);
        $params[] = (int) $itemsPerPage;
        $params[] = (int) $offset;

        $stmt = $this->db->query(
            "SELECT pr.*, br.brand_name, ct.category_name, sp.supplier_name
               FROM product pr
               JOIN brand br ON pr.brand_id = br.brand_id
               JOIN category ct ON pr.category_id = ct.category_id
               JOIN supplier sp ON pr.supplier_id = sp.supplier_id
              WHERE {$whereSql}
              ORDER BY pr.created_at DESC
              LIMIT ? OFFSET ?",
            $params
        );
        return $stmt->fetchAll();
    }

    public function readAllFiltered($filters = [])
    {
        [$whereSql, $params] = $this->buildFilterWhere($filters);
        $stmt = $this->db->query(
            "SELECT pr.*, br.brand_name, ct.category_name, sp.supplier_name
               FROM product pr
               JOIN brand br ON pr.brand_id = br.brand_id
               JOIN category ct ON pr.category_id = ct.category_id
               JOIN supplier sp ON pr.supplier_id = sp.supplier_id
              WHERE {$whereSql}
              ORDER BY pr.created_at DESC",
            $params
        );
        return $stmt->fetchAll();
    }

    private function buildFilterWhere($filters = [])
    {
        $conditions = ["pr.deleted_at IS NULL"];
        $params = [];

        $keyword = trim((string)($filters['keyword'] ?? ''));
        $status = trim((string)($filters['status'] ?? ''));
        $brand = trim((string)($filters['brand'] ?? ''));
        $supplier = trim((string)($filters['supplier'] ?? ''));

        if ($keyword !== '') {
            $conditions[] = "(pr.product_name LIKE ? OR pr.sku LIKE ?)";
            $kw = '%' . $keyword . '%';
            $params[] = $kw;
            $params[] = $kw;
        }
        if ($status !== '') {
            $conditions[] = "pr.status = ?";
            $params[] = $status;
        }
        if ($brand !== '') {
            $conditions[] = "br.brand_name = ?";
            $params[] = $brand;
        }
        if ($supplier !== '') {
            $conditions[] = "sp.supplier_name = ?";
            $params[] = $supplier;
        }

        return [implode(' AND ', $conditions), $params];
    }

    public function readById($id)
    {
        $stmt = $this->db->query(
            "SELECT pr.*, br.brand_name, ct.category_name, sp.supplier_name
               FROM product pr
               JOIN brand    br ON pr.brand_id    = br.brand_id
               JOIN category ct ON pr.category_id = ct.category_id
               JOIN supplier sp ON pr.supplier_id = sp.supplier_id 
              WHERE pr.product_id = ? AND deleted_at IS NULL",
            [$id]
        );
        return $stmt->fetch();
    }

    public function update($data)
    {
        // FIX BUG: bản gốc có $data['created_at'] thừa trong params
        $sql = "UPDATE product SET
                    product_name = ?, brand_id = ?, category_id = ?, supplier_id = ?,
                    price = ?, cost_price = ?, description = ?, sku = ?, image = ?, status = ?
                WHERE product_id = ?";
        return $this->db->query($sql, [
            $data['product_name'], $data['brand_id'],   $data['category_id'],
            $data['supplier_id'],  $data['price'],      $data['cost_price'],
            $data['description'],  $data['sku'],        $data['image'],
            $data['status'],       $data['product_id'],
        ]);
    }

    public function delete($id)
    {
        return $this->db->query("UPDATE product SET deleted_at = ? WHERE product_id = ?", [date("Y-m-d H:i:s"), 
                                                                                           $id]);
    }
}
