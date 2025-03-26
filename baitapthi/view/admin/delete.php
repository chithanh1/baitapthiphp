<?php
require_once '../../config/database.php';
$id = $_GET['id'];
$sql = "DELETE FROM SinhVien WHERE MaSV='$id'";
if ($conn->query($sql) === TRUE) {
    header("Location: dashboard.php");
} else {
    echo "Lỗi: " . $conn->error;
}
?>
