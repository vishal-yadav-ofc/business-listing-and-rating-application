<?php
class BusinessModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAll() {
        $res = $this->conn->query("
            SELECT b.*, IFNULL(AVG(r.rating),0) as avg_rating
            FROM businesses b
            LEFT JOIN ratings r ON b.id = r.business_id
            GROUP BY b.id
        ");

        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getById($id) {
        $res = $this->conn->query("SELECT * FROM businesses WHERE id=$id");
        return $res->fetch_assoc();
    }

    public function add($name,$address,$phone,$email) {
        $stmt = $this->conn->prepare("INSERT INTO businesses(name,address,phone,email) VALUES(?,?,?,?)");
        $stmt->bind_param("ssss",$name,$address,$phone,$email);
        return $stmt->execute();
    }

    public function update($id,$name,$address,$phone,$email) {
        $stmt = $this->conn->prepare("UPDATE businesses SET name=?, address=?, phone=?, email=? WHERE id=?");
        $stmt->bind_param("ssssi",$name,$address,$phone,$email,$id);
        return $stmt->execute();
    }

    public function delete($id) {
        return $this->conn->query("DELETE FROM businesses WHERE id=$id");
    }
}