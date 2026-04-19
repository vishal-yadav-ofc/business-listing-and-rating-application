<?php
class RatingModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function saveOrUpdate($business_id,$name,$email,$phone,$rating) {

        $check = $this->conn->query("
            SELECT id FROM ratings 
            WHERE business_id='$business_id'
            AND (email='$email' OR phone='$phone')
        ");

        if ($check->num_rows > 0) {

            $row = $check->fetch_assoc();

            $stmt = $this->conn->prepare("
                UPDATE ratings SET name=?, rating=?, email=?, phone=? WHERE id=?
            ");
            $stmt->bind_param("sdssi",$name,$rating,$email,$phone,$row['id']);
            $stmt->execute();

        } else {

            $stmt = $this->conn->prepare("
                INSERT INTO ratings (business_id,name,email,phone,rating)
                VALUES (?,?,?,?,?)
            ");
            $stmt->bind_param("isssd",$business_id,$name,$email,$phone,$rating);
            $stmt->execute();
        }
    }

    public function getAverage($business_id) {
        $res = $this->conn->query("
            SELECT IFNULL(AVG(rating),0) as avg_rating 
            FROM ratings WHERE business_id='$business_id'
        ");
        return $res->fetch_assoc();
    }
}