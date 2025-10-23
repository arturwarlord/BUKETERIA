<?php
// Подключение к базе данных
$conn = new mysqli('localhost', 'root', '', 'flower_shop');
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Обработка POST-запросов (редактирование или удаление)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $id = (int)$_POST['id'];

        if ($action === 'update') {
            // Обновление товара
            $name = $_POST['name'];
            $category = $_POST['category'];
            $price = $_POST['price'];

            // Обработка изображения, если загружено
            if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/';
                if(!is_dir($upload_dir)){
                    mkdir($upload_dir, 0755, true);
                }
                $file_tmp = $_FILES['image']['tmp_name'];
                $file_name = basename($_FILES['image']['name']);
                $target_path = $upload_dir . time() . '_' . $file_name;

                if(move_uploaded_file($file_tmp, $target_path)){
                    $image_url = $target_path;
                } else {
                    $image_url = ''; // Или оставить старое изображение
                }
            } else {
                // Оставить существующее изображение
                $image_url = $_POST['current_image'];
            }

            // Обновление записи
            $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, image_url=? WHERE id=?");
            $stmt->bind_param("ssdsi", $name, $category, $price, $image_url, $id);
            $stmt->execute();
            $stmt->close();

        } elseif ($action === 'delete') {
            // Удаление товара
            $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
}


// Получение всех товаров
$result = $conn->query("SELECT * FROM products");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <h2>Добавить новый товар</h2>
<form action="admin/add_product.php" method="post" enctype="multipart/form-data">
    <label for="name">Название:</label><br>
    <input type="text" id="name" name="name" required><br><br>

    <label for="category">Категория:</label><br>
    <input type="text" id="category" name="category" required><br><br>

    <label for="price">Цена (₽):</label><br>
    <input type="number" id="price" name="price" step="0.01" required><br><br>


    <label for="image">Изображение:</label><br>
    <input type="file" id="image" name="image" accept="image/*" required><br><br>

    <button type="submit">Добавить товар</button>
</form>


<h2>Товары для редактирования</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Название</th>
        <th>Категория</th>
        <th>Цена</th>
        <th>Изображение</th>
        <th>Редактировать</th>
        <th>Удалить</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <form method="post" enctype="multipart/form-data" style="margin:0;">
        <td>
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($row['image_url']) ?>">
            <input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>" required>
        </td>
        <td><input type="text" name="category" value="<?= htmlspecialchars($row['category']) ?>" required></td>
        <td><input type="number" name="price" step="0.01" value="<?= $row['price'] ?>" required></td>
        <td>
            <img src="<?= htmlspecialchars($row['image_url']) ?>" width="100"><br>
            Заменить: <input type="file" name="image" accept="image/*">
        </td>
        <td>
            <button type="submit" name="action" value="update">Сохранить</button>
        </td>
        </form>
        <td>
            <form method="post" onsubmit="return confirm('Удалить этот товар?');">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="action" value="delete">Удалить</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php $conn->close(); ?>
</body>
</html>
