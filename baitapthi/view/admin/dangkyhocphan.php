<?php
// File: views/admin/dangkyhocphan.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
require_once '../../config/database.php';

// Lấy danh sách sinh viên
$students = $conn->query("SELECT * FROM SinhVien");

// Lấy danh sách học phần
$hocphans = $conn->query("SELECT * FROM HocPhan");

// Xử lý đăng ký học phần
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $MaSV = $_POST['MaSV'];
    $MaHP = $_POST['MaHP'];

    // Kiểm tra sinh viên đã đăng ký học phần này chưa
    $check = $conn->prepare("SELECT * FROM ChiTietDangKy JOIN DangKy ON ChiTietDangKy.MaDK = DangKy.MaDK WHERE DangKy.MaSV = ? AND ChiTietDangKy.MaHP = ?");
    $check->bind_param("ss", $MaSV, $MaHP);
    $check->execute();
    $result = $check->get_result();
    
    if ($result->num_rows > 0) {
        echo "<script>alert('Sinh viên đã đăng ký học phần này!');</script>";
    } else {
        // Thêm vào bảng Đăng Ký nếu chưa có
        $sql_dk = "INSERT INTO DangKy (MaSV) VALUES (?)";
        $stmt_dk = $conn->prepare($sql_dk);
        $stmt_dk->bind_param("s", $MaSV);
        $stmt_dk->execute();
        $MaDK = $stmt_dk->insert_id;

        // Thêm vào Chi Tiết Đăng Ký
        $sql_ctdk = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)";
        $stmt_ctdk = $conn->prepare($sql_ctdk);
        $stmt_ctdk->bind_param("is", $MaDK, $MaHP);
        if ($stmt_ctdk->execute()) {
            echo "<script>alert('Đăng ký thành công!');</script>";
        } else {
            echo "<script>alert('Lỗi khi đăng ký học phần!');</script>";
        }
    }
}

// Lấy danh sách đăng ký thành công
$dangky_list = $conn->query("SELECT SinhVien.HoTen, HocPhan.TenHP, HocPhan.SoTinChi FROM ChiTietDangKy JOIN DangKy ON ChiTietDangKy.MaDK = DangKy.MaDK JOIN SinhVien ON DangKy.MaSV = SinhVien.MaSV JOIN HocPhan ON ChiTietDangKy.MaHP = HocPhan.MaHP");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Học Phần</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { width: 60%; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        select, button { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        button { background: #28a745; color: white; cursor: pointer; }
        button:hover { background: #218838; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; text-align: center; border: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        tr:hover { background: #f1f1f1; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Đăng Ký Học Phần</h2>
        <form method="POST">
            <div class="form-group">
                <label>Chọn Sinh Viên:</label>
                <select name="MaSV" required>
                    <?php while ($student = $students->fetch_assoc()): ?>
                        <option value="<?= $student['MaSV'] ?>"> <?= $student['HoTen'] ?> (<?= $student['MaSV'] ?>) </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Chọn Học Phần:</label>
                <select name="MaHP" required>
                    <?php while ($hocphan = $hocphans->fetch_assoc()): ?>
                        <option value="<?= $hocphan['MaHP'] ?>"> <?= $hocphan['TenHP'] ?> (<?= $hocphan['SoTinChi'] ?> tín chỉ) </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit">Đăng Ký</button>
        </form>
        
        <h2>Danh sách đăng ký thành công</h2>
        <table>
            <tr>
                <th>Sinh Viên</th>
                <th>Học Phần</th>
                <th>Số Tín Chỉ</th>
            </tr>
            <?php while ($row = $dangky_list->fetch_assoc()): ?>
            <tr>
                <td><?= $row['HoTen'] ?></td>
                <td><?= $row['TenHP'] ?></td>
                <td><?= $row['SoTinChi'] ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
