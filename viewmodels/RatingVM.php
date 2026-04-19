<?php
require_once '../models/RatingModel.php';

class RatingVM {
    private $model;

    public function __construct($conn) {
        $this->model = new RatingModel($conn);
    }

    public function save($data) {

       
           if (empty($data['name'])) return "Name cannot be empty";
              if (empty($data['email'])) return "EMAIL cannot be empty";
                 if (empty($data['phone'])) return "Name cannot be empty";
           

        if (!preg_match('/^[0-9]{10}$/',$data['phone'])) {
            return "invalid_phone";
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return "invalid_email";
        }

        $this->model->saveOrUpdate(
            $data['business_id'],
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['rating']
        );

        return $this->model->getAverage($data['business_id']);
    }
}