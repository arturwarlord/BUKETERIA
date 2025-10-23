<?php
// Включите соединение с базой данных (предположим, у вас есть файл db.php)
require '../db.php';
// Получаем данные из формы (проверьте, что данные отправлены)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];

    // Проверка и обработка загруженного изображения
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = basename($_FILES['image']['name']);
        $target_path = $upload_dir . time() . '_' . $file_name;

        if (move_uploaded_file($file_tmp, $target_path)) {
            $image_url = $target_path;
        } else {
            die('Ошибка при загрузке изображения.');
        }
    } else {
        die('Изображение не загружено или возникла ошибка при загрузке.');
    }

    // Предотвращение SQL-инъекций и подготовка запроса
    $stmt = $conn->prepare("INSERT INTO products (name, image_url, category, price) VALUES (?, ?, ?, ?)");

    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $conn->error);
    }

    // Преобразуйте число в правильный тип
    $price_value = (float)$price;

    // Свяжите параметры
    $stmt->bind_param("sssd", $name, $image_url, $category, $price_value);

    // Выполнение запроса
    if ($stmt->execute()) {
        echo "Товар успешно добавлен.";
    } else {
        echo "Ошибка: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
