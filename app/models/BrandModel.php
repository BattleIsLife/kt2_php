<?php

class BrandModel extends Model implements RepositoryInterface{
    /* Các thuộc tính trong BrandModel:
     * - brand_id: mã thương hiệu
     * - brand_name: tên thương hiệu
     * - created_at: ngày tạo
    */

    public function create($data)
    {
        $sql = "INSERT INTO brand(brand_name, created_at)
                            VALUES(?, ?)";

        $stmt = $this->db->query($sql, [
            $data['brand_id'],
            $data['brand_name'],
            date("Y-m-d H:i:s"),
        ]);

        return $stmt;
    }

    public function readAll()
    {
        $stmt = $this->db->query("SELECT * FROM brand");

        return $stmt->fetchAll();
    }

    public function readById($id)
    {
        $stmt = $this->db->query("SELECT * FROM brand WHERE brand_id = ?", [$id]);

        return $stmt->fetch();
    }

    public function update($data)
    {
        $sql = "UPDATE brand SET brand_name = ? 
                                WHERE brand_id = ?";

        $stmt = $this->db->query($sql, [
            $data['brand_name'],
            $data['brand_id'],
        ]);

        return $stmt;
    }

    public function delete($id)
    {
        $stmt = $this->db->query("DELETE FROM brand WHERE brand_id = ?", [$id]);

        return $stmt;
    }
}