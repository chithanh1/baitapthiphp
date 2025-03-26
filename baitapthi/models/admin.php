<?php

require_once 'C:/xampp/htdocs/baitapthi/config/database.php';



class Admin {
    public static function login($username, $password) {
        global $conn;
        $sql = "SELECT * FROM Admins WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
        return $result->num_rows > 0;
    }
}
?>