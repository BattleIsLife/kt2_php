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
