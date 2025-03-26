<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $MaHP = $_POST['MaHP'];
    $TenHP = $_POST['TenHP'];
    $SoTinChi = $_POST['SoTinChi'];
    $SoLuongDuKien = $_POST['SoLuongDuKien'];

    $sql = "INSERT INTO HocPhan (MaHP, TenHP, SoTinChi, SoLuongDuKien) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $MaHP, $TenHP, $SoTinChi, $SoLuongDuKien);
    
    if ($stmt->execute()) {
        header("Location: hocphan.php");
    } else {
        echo "Lỗi khi thêm học phần.";
    }
}
?>

<form method="POST">
    <label>Mã HP:</label> <input type="text" name="MaHP" required><br>
    <label>Tên HP:</label> <input type="text" name="TenHP" required><br>
    <label>Số tín chỉ:</label> <input type="number" name="SoTinChi" required><br>
    <label>Số lượng dự kiến:</label> <input type="number" name="SoLuongDuKien" required><br>
    <button type="submit">Thêm Học Phần</button>
</form>
