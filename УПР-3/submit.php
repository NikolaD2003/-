<?php
// Конфигурация за връзка с базата данни
$host = "localhost";
$dbname = "test_db";
$username = "root";
$password = "";

// Променливи за съобщения
$message = ""; // По подразбиране празно

try {
    // Създаване на PDO връзка
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Проверка дали формата е изпратена
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Уверяваме се, че данните са подадени чрез формата
        if (isset($_POST['name'], $_POST['email'], $_POST['message'])) {
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $user_message = htmlspecialchars($_POST['message']);

            // SQL заявка за вмъкване на данните
            $sql = "INSERT INTO registrations (name, email, message) VALUES (:name, :email, :message)";
            $stmt = $conn->prepare($sql);

            // Свързване на параметри
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $user_message);

            // Изпълнение на заявката
            $stmt->execute();
            $message = "Вашите данни бяха успешно запазени!";
        }
    }
} catch (PDOException $e) {
    $message = "Грешка: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <form action="form.php" method="post">
            <h1>Регистрация</h1>
            <!-- Показване на съобщение само ако има такова -->
            <?php if (!empty($message)) : ?>
                <p class="message"><?php echo $message; ?></p>
            <?php endif; ?>
            
            <label for="name">Име:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Имейл:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="message">Съобщение:</label>
            <textarea id="message" name="message" rows="4" required></textarea>
            
            <button type="submit">Изпрати</button>
        </form>
    </div>
</body>
</html>
