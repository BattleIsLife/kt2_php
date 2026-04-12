<?php

class InventoryModel extends Model implements RepositoryInterface{

    /* Các thuộc tính trong InventoryModel:
     * - inventory_id: mã tồn kho
     * - product_id: mã sản phẩm
     * - quantity: số lượng
     * - last_updated: cập nhật lần cuối
    */

    public function create($data)
    {
        $sql = "INSERT INTO inventory(inventory_id, product_id
                                      quantity, last_updated)
                            VALUES(?, ?, ?, ?)";
        $last_updated = date("Y-m-d H:i:s");
        $stmt = $this->db->query($sql, [
            $data['inventory_id'],
            $data['product_id'],
            $data['quantity'],
            $last_updated,
        ]);

        return $stmt;
    }

    public function readAll()
    {
        $stmt = $this->db->query("SELECT * FROM inventory");
        return $stmt->fetchAll();
    }

    public function readById($id)
    {
        $stmt = $this->db->query("SELECT * FROM inventory WHERE inventory_id = ?", [$id]);
        return $stmt->fetch();
    }

    public function readByProductId($id)
    {
        $stmt = $this->db->query("SELECT * FROM inventory WHERE product_id = ?", [$id]);
        return $stmt->fetch();
    }

    public function update($data)
    {
        $sql = "UPDATE inventory SET product_id = ?, 
                                     quantity = ?, 
                                     last_updated = ?, 
                               WHERE inventory_id = ?
                            ";
        $last_updated = date("Y-m-d H:i:s");
        $stmt = $this->db->query($sql, [
            $data['product_id'],
            $data['quantity'],
            $last_updated,
        ]);

        return $stmt;
    }

    public function delete($id)
    {
        $stmt = $this->db->query("DELETE FROM inventory WHERE inventory_id = ?", [$id]);
        return $stmt;
    }
}