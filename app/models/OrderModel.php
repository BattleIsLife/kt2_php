<?php

class OrderModel extends Model implements RepositoryInterface{
    /* 
     * Các thuộc tính trong OrderModel:
     * - order_id: mã đơn hàng
     * - order_code: ???
     * - customer_name: tên khách hàng
     * - order_date: ngày đặt hàng
     * - status: trạng thái đặt hàng
     * - payment_method: phương thức thanh toán
     * - payment_status: trạng thái thanh toán
     * - total_amount: tổng tiền (trước giảm giá)
     * - discount: % giảm giá
     * - final_amount: tổng tiền sau giảm giá
     * - notes: ghi chú
    */

    public function create($data)
    {
        $sql = "INSERT INTO `order`(order_code, customer_name, total_amount, discount, final_amount, notes)
                            VALUES(?, ?, ?, ?, ?)";

        
        $total_amount = $data['total_amount'];
        $discount = $data['discount'];
        $stmt = $this->db->query($sql, [
            $data['order_id'],
            $data['order_code'],
            $data['customer_name'],
            $total_amount,
            $discount,
            $total_amount*(1-$discount),
            $data['notes']
            
        ]);

        return $stmt;
    }

    public function readAll()
    {
        $stmt = $this->db->query("SELECT * FROM `order`");
        return $stmt->fetchAll();
    }

    public function readById($id)
    {
        $stmt = $this->db->query("SELECT * FROM `order` WHERE order_id = ?", [$id]);
        return $stmt->fetch();
    }

    public function update($data)
    {
        $sql = "UPDATE `order` SET order_code = ?,
                                   customer_name = ?,
                                   status = ?,
                                   payment_method = ?,
                                   payment_status = ?,
                                   total_amount = ?,
                                   discount = ?
                                   final_amount = ?,
                                   notes = ? 
                               WHERE order_id = ?";

        
        $total_amount = $data['total_amount'];
        $discount = $data['discount'];
        $stmt = $this->db->query($sql, [
            $data['order_code'],
            $data['customer_name'],
            $data['status'],
            $data['payment_method'],
            $data['payment_status'],
            $total_amount,
            $discount,
            $total_amount*(1-$discount),
            $data['notes'],
            $data['order_id'],
            
        ]);

        return $stmt;
    }

    public function delete($id)
    {
        $stmt = $this->db->query("DELETE FROM `order` WHERE order_id = ?", [$id]);
        return $stmt;
    }
}