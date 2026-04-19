<?php
require_once '../models/BusinessModel.php';

class BusinessVM {
    private $model;

    public function __construct($conn) {
        $this->model = new BusinessModel($conn);
    }

    public function fetch() {
        return $this->model->getAll();
    }

    public function get($id) {
        return $this->model->getById($id);
    }

    public function save($data) {

        if (empty($data['name']) || empty($data['address']) || empty($data['phone']) || empty($data['email'])) {
            return "error";
        }

        if (!preg_match('/^[0-9]{10}$/',$data['phone'])) {
            return "invalid_phone";
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return "invalid_email";
        }

        if (empty($data['id'])) {
            $this->model->add($data['name'],$data['address'],$data['phone'],$data['email']);
        } else {
            $this->model->update($data['id'],$data['name'],$data['address'],$data['phone'],$data['email']);
        }

        return "success";
    }

    public function delete($id) {
        $this->model->delete($id);
    }
}