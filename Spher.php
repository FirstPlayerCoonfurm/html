<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
      $username = "sammy";
      $password = "PaSsWoRd";
      $dbname = "HOUSES";

      $conn = new mysqli($servername, $username, $password, $dbname);
      // Проверка соединения
      if ($conn->connect_error) {
          die("Ошибка подключения: " . $conn->connect_error);
      }
      // Обработка формы бронирования
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $full_name = $_POST['full_name3'];
        $email = $_POST['email3'];
        $start_date = $_POST['start_date3'];
        $end_date = $_POST['end_date3'];
        $stmt_spher = $conn->prepare("INSERT INTO Список_Сфера (ФИО, Email, start_date, end_date, Дом) VALUES ('$full_name', '$email', '$start_date', '$end_date', 'Сфера')");
        if ($stmt_spher->execute()) {
              // Редирект после успешного добавления бронирования
              echo("<script>alert('Ваша заява успешно доставлена!');</script>");
              header("Location: bron.php");
              exit();
          } else {
              echo "Ошибка при добавлении бронирования: " . $conn->error;
          }
          $conn->close();
    }
}
?>