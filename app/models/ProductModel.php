<?php

class ProductModel extends Model implements RepositoryInterface{
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
        $sql = "INSERT INTO product(product_name, brand_id, category_id, supplier_id, 
                                    price, cost_price, description, sku, image, created_at)
                            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $created_at = date("Y-m-d H:i:s");
        $stmt = $this->db->query($sql, [
            $data['product_name'],
            $data['brand_id'],
            $data['category_id'],
            $data['supplier_id'],
            $data['price'],
            $data['cost_price'],
            $data['description'],
            $data['sku'],
            $data['image'],
            date("Y-m-d H:i:s"),
        ]);

        return $stmt;
    }

    public function readAll()
    {
        $stmt = $this->db->query("SELECT * FROM product ORDER BY created_at");
        return $stmt->fetchAll();
    }

    public function readById($id)
    {
        $stmt = $this->db->query("SELECT * FROM product WHERE product_id = ?", [$id]);
        return $stmt->fetch();
    }

    public function update($data)
    {
        $sql = "UPDATE product SET product_name = ?, 
                                   brand_id = ?, 
                                   category_id = ?, 
                                   supplier_id = ?, 
                                   price = ?, 
                                   cost_price = ?, 
                                   description = ?, 
                                   sku = ?, 
                                   image = ?,
                                   status = ? 
                               WHERE product_id = ?
                            ";
        $stmt = $this->db->query($sql, [
            $data['product_name'],
            $data['brand_id'],
            $data['category_id'],
            $data['supplier_id'],
            $data['price'],
            $data['cost_price'],
            $data['description'],
            $data['sku'],
            $data['image'],
            $data['status'],
            $data['created_at'],
            $data['product_id'],
        ]);

        return $stmt;
    }

    public function delete($id)
    {
        $stmt = $this->db->query("DELETE FROM product WHERE product_id = ?", [$id]);
        return $stmt;
    }
}