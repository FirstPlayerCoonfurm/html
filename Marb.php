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
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $stmt_marb = $conn->prepare("INSERT INTO Список_Марбака (ФИО, Email, start_date, end_date, Дом) VALUES ('$full_name', '$email', '$start_date', '$end_date', 'Мабрака')");
        if ($stmt_marb->execute()) {
              // Редирект после успешного добавления бронирования
              header("Location: bron.php");
              exit();
          } else {
              echo "Ошибка при добавлении бронирования: " . $conn->error;
          }
          $conn->close();
    }
  }
?>