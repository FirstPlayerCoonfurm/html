<?php
$current_date = date("Y-m-d"); // Создание переменной с текущей датой
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $servername = "localhost";
    $username = "sammy";
    $password = "PaSsWoRd";
    $dbname = "HOUSES";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error)
    {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        
        if ($start_date < $current_date) {
            echo "<script>
            alert('Дата начала бронирования не может быть меньше текущей даты.');
            window.location.href = 'bron.php';
            </script>";
            exit();
        }

        // Проверка свободных дат
        $stmt_check = $conn->prepare("SELECT * FROM Список_Трейлер WHERE (start_date >= ? AND start_date <= ?) OR (end_date >= ? AND end_date <= ?)");
        $stmt_check->bind_param("ssss", $start_date, $end_date, $start_date, $end_date);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if($result->num_rows > 0) // Если есть занятые даты
        {
            echo "<script>
            alert('Извините, выбранные даты заняты. Пожалуйста, выберите другие даты.');
            window.location.href = 'bron.php';
            </script>";
            //header("Location: bron.php");
        }
        else // Если даты свободны
        {
            $stmt_marb = $conn->prepare("INSERT INTO Список_Трейлер (ФИО, Email, start_date, end_date, Дом) VALUES (?, ?, ?, ?, 'Трейлер')");
            $stmt_marb->bind_param("ssss", $full_name, $email, $start_date, $end_date);
            
            $stmt = $conn->prepare("INSERT INTO Trail_house (startDate, endDate) VALUES (?, ?)");
            $stmt->bind_param("ss", $start_date, $end_date);
            if($stmt_marb->execute() && $stmt->execute())
            {
                header("Location: bron.php");

                $botToken = '';
                $chatId = '';
                $message = 'Новое бронирование в дом Трейлер от ' . $full_name . ' На период c ' . $start_date . ' по ' . $end_date;

                $url = "https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$chatId}&text={$message}";

                file_get_contents($url);
                exit();
            }
            else
            {
                echo "Ошибка при добавлении бронирования: " . $conn->error;
            }
        }

        $stmt_check->close();
        $stmt_marb->close();
        $conn->close();
    }
}
?>
