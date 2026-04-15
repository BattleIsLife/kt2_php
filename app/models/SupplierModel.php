<?php

class SupplierModel extends Model implements RepositoryInterface{
    /* 
     * Các thuộc tính trong SupplierModel
     * - supplier_id: mã nhà cung cấp
     * - supplier_name: tên nhà cung cấp
     * - contact_name: tên người liên hệ
     * - phone: sdt
     * - address: địa chỉ
     * - created_at: ngày tạo
    */

    public function create($data)
    {
        $sql = "INSERT INTO supplier(supplier_name, contact_name, phone, address, created_at) 
                            VALUES(?, ?, ?, ?, ?)";
        
        $stmt = $this->db->query($sql, [
            $data['supplier_name'],
            $data['contact_name'],
            $data['phone'],
            $data['address'],
            $data['created_at']
        ]);

        return $stmt;
    }

    public function readAll()
    {
        $stmt = $this->db->query("SELECT * FROM supplier ORDER BY created_at");

        return $stmt->fetchAll();
    }

    public function readById($id)
    {
        $stmt = $this->db->query("SELECT * FROM supplier WHERE supplier_id = ?", [$id]);

        return $stmt->fetch();
    }

    public function update($data)
    {
        $sql = "UPDATE supplier SET supplier_name = ?,
                                    contact_name = ?,
                                    phone = ?,
                                    email = ?,
                                    address = ? 
                                WHERE supplier_id = ?";

        $stmt = $this->db->query($sql, [
            $data['supplier_name'],
            $data['contact_name'],
            $data['phone'],
            $data['address'],
            $data['supplier_id']
        ]);

        return $stmt;
    }

    public function delete($id)
    {
        $stmt = $this->db->query("DELETE FROM supplier WHERE supplier_id = ?", [$id]);

        return $stmt;
    }
}