<?php
// db.php
$conn = new mysqli('localhost', 'root', '', 'flower_shop');
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
