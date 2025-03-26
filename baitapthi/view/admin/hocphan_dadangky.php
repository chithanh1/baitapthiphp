<?php
session_start();
require_once '../../config/database.php';

if (!isset($_SESSION['MaSV'])) {
    header("Location: login.php");
    exit();
}

$MaSV = $_SESSION['MaSV'];

$sql = "SELECT HP.MaHP, HP.TenHP, HP.SoTinChi 
        FROM ChiTietDangKy CTDK
        JOIN DangKy DK ON CTDK.MaDK = DK.MaDK
        JOIN HocPhan HP ON CTDK.MaHP = HP.MaHP
        WHERE DK.MaSV = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $MaSV);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học Phần Đã Đăng Ký</title>
</head>
<body>
    <h2>Danh sách học phần đã đăng ký</h2>
    <table border="1">
        <tr>
            <th>Mã HP</th>
            <th>Tên HP</th>
            <th>Số tín chỉ</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) : ?>
        <tr>
            <td><?= $row['MaHP'] ?></td>
            <td><?= $row['TenHP'] ?></td>
            <td><?= $row['SoTinChi'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
