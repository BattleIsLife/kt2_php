<?php

class OrderDetailModel extends Model implements RepositoryInterface{
    /* 
     * Các thuộc tính trong OrderDetailModel:
     * - detail_id: mã chi tiết 
     * - order_id: mã đơn hàng
     * - product_id: mã sản phẩm
     * - quantity: số lượng
     * - unit_price: đơn giá
     * - discount: giảm giá
     * - subtotal: tổng tiền
    */

    public function create($data)
    {
        $sql = "INSERT INTO orderdetail(order_id, product_id, quantity, unit_price, discount, subtotal)
                            VALUES(?, ?, ?, ?, ?, ?)";

        
        $quantity = $data['quantity'];
        $unit_price = $data['unit_price'];
        $discount = $data['discount'];
        $subtotal = ($quantity * $unit_price) * (1-$discount);
        $stmt = $this->db->query($sql, [
            $data['order_id'],
            $data['product_id'],
            $quantity,
            $unit_price,
            $discount,
            $subtotal
        ]);

        return $stmt;
    }

    public function readAll()
    {
        $stmt = $this->db->query("SELECT * FROM orderdetail");
        return $stmt->fetchAll();
    }

    public function readById($id)
    {
        $stmt = $this->db->query("SELECT * FROM orderdetail WHERE detail_id = ?", [$id]);
        return $stmt->fetch();
    }

    public function readByOrderId($id)
    {
        $stmt = $this->db->query("SELECT * FROM orderdetail WHERE order_id = ?", [$id]);
        return $stmt->fetchAll();
    }

    public function update($data)
    {
        $sql = "UPDATE orderdetail SET order_id = ?, 
                                       product_id = ?, 
                                       quantity = ?, 
                                       unit_price = ?, 
                                       discount = ?, 
                                       subtotal = ? 
                                WHERE detail_id = ?";

        
        $quantity = $data['quantity'];
        $unit_price = $data['unit_price'];
        $discount = $data['discount'];
        $subtotal = ($quantity * $unit_price) * (1-$discount);
        $stmt = $this->db->query($sql, [
            $data['order_id'],
            $data['product_id'],
            $quantity,
            $unit_price,
            $discount,
            $subtotal,
            $data['detail_id']
        ]);

        return $stmt;
    }

    public function updateFromOrderId($data)
    {
        $sql = "UPDATE orderdetail SET product_id = ?, 
                                       quantity = ?, 
                                       unit_price = ?, 
                                       discount = ?, 
                                       subtotal = ? 
                                WHERE order_id = ?";

        
        $quantity = $data['quantity'];
        $unit_price = $data['unit_price'];
        $discount = $data['discount'];
        $subtotal = ($quantity * $unit_price) * (1-$discount);
        $stmt = $this->db->query($sql, [
            $data['product_id'],
            $quantity,
            $unit_price,
            $discount,
            $subtotal,
            $data['order_id'],
        ]);

        return $stmt;
    }

    public function delete($id)
    {
        $stmt = $this->db->query("DELETE FROM orderdetail WHERE detail_id = ?", [$id]);
        return $stmt;
    }

    public function deleteFromOrderId($id)
    {
        $stmt = $this->db->query("DELETE FROM orderdetail WHERE order_id = ?", [$id]);
        return $stmt;
    }
}