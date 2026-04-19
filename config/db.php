<?php
$conn = new mysqli("localhost", "root", "", "business-listing-and-rating-application");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
