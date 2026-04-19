<?php
include '../config/db.php';
require_once '../viewmodels/BusinessVM.php';

$vm = new BusinessVM($conn);
$action = $_POST['action'] ?? '';

if ($action == "fetch") {
    echo json_encode($vm->fetch());
}

if ($action == "get") {
    echo json_encode($vm->get($_POST['id']));
}

if ($action == "add" || $action == "update") {
    echo $vm->save($_POST);
}

if ($action == "delete") {
    $vm->delete($_POST['id']);
}