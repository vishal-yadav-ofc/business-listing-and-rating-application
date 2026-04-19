<?php
include '../config/db.php';
require_once '../viewmodels/RatingVM.php';

// ✅ VERY IMPORTANT
header('Content-Type: application/json');

$vm = new RatingVM($conn);

$result = $vm->save($_POST);

// ✅ Always return proper JSON
echo json_encode($result);
exit;