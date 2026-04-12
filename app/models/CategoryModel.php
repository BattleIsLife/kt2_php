<?php

class CategoryModel extends Model implements RepositoryInterface{
    /* Các thuộc tính trong CategoryModel:
     * - category_id: mã loại
     * - category_name: tên loại
     * - description: mô tả
     * - created_at: ngày tạo
    */

    public function create($data)
    {
        $sql = "INSERT INTO category(category_name, 
                                     description, created_at)
                            VALUES(?, ?, ?, ?)";

        $stmt = $this->db->query($sql, [
            $data['category_id'],
            $data['category_name'],
            $data['description'],
            date("Y-m-d H:i:s"),
        ]);

        return $stmt;
    }

    public function readAll()
    {
        $stmt = $this->db->query("SELECT * FROM category");

        return $stmt->fetchAll();
    }

    public function readById($id)
    {
        $stmt = $this->db->query("SELECT * FROM category WHERE category_id = ?", [$id]);

        return $stmt->fetch();
    }

    public function update($data)
    {
        $sql = "UPDATE category SET category_name = ?,
                                    description = ? 
                                WHERE category_id = ?";

        $stmt = $this->db->query($sql, [
            $data['category_name'],
            $data['description'],
            $data['category_id'],
        ]);

        return $stmt;
    }

    public function delete($id)
    {
        $stmt = $this->db->query("DELETE FROM category WHERE category_id = ?", [$id]);

        return $stmt;
    }
}