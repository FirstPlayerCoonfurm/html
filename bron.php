<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="/css/bron.css" />
    <script src="/js/bron.js" defer></script>
    <title>Бронирование</title>
  </head>
  <body>
    <div>
        <header>
          <div class="container">
              <button class="burger" id="burger">
                  <span></span><span></span><span></span>
              </button>
              <img class="logo" src="/img/logo-2.jpg" alt="">
              <nav class="menu">
                <ul class="menu__list">
                    <li class="menu__item"><a class="menu__link" href="index.html">Главная</a></li>
                    <li class="menu__item"><a class="menu__link" href="about.html">О нас</a></li>
                    <li class="menu__item"><a class="menu__link" href="news.html">Новости</a></li>
                    <li class="menu__item"><a class="menu__link" href="bron.php">Бронирование</a></li>
                </ul>
            </nav>
          </div>
        </header>
    </div>
    <div class="text1">
      <h2>Наши предложения: </h2>
    </div>
    <div class="content">
      <div class="text2">
          <h2>Коттедж «МАРБАКА»</h2>
          <p>Двухэтажный дом в скандинавском стиле с просторной гостинной, уютной  кухонкой и жаркой сауной.
              Хотите жить в доме у моря?
              Марбака – воплощение этой мечты!
              Деревянные полы, лестницы, в окнах – с одной стороны качаются зеленым морем сосны, с другой – манят и зовут волны Обского моря.
              Это другая реальность, другой воздух, здесь время замедляет скорость и жизнь состоит из мгновений, наполненных смыслом.</p>
              <button class="toggle-calendar-btn1">Открыть календарь</button>
              <div class="calendar-container-1">
              <button class="close-cal1" type="button">Закрыть</button>
              <?php
              function createCalendar($year)
              {
                // Создание таблицы календаря
                $calendar = '<table><tr>';

                  $monthNames = array(
                      'Январь', 'Февраль', 'Март', 'Апрель',
                      'Май', 'Июнь', 'Июль', 'Август',
                      'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
                  );

                  $currentDay = date('j');
                  $currentMonth = date('n');
                  $currentYear = date('Y');

                  // Получение забронированных дат из базы данных
                  $bookedDates = array();
                  if($bookedDates == null){
                    $bookedDates = getBookedDatesFromDatabase();
                }

                  for ($month = 1; $month <= 12; $month++) {
                      // Первый день месяца
                      $firstDay = date("N", strtotime("$year-$month-01"));

                      // Общее количество дней в месяце
                      $totalDays = date("t", strtotime("$year-$month-01"));

                      // Название месяца
                      $monthName = $monthNames[$month - 1];

                      // Создание заголовка месяца
                      $calendar .= '<td style="vertical-align: top; padding: 10px;"><table><tr><th colspan="7">' . $monthName . '</th></tr>';
                      $calendar .= '<tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Вс</th></tr>';

                      $dayCounter = 1;

                      // Заполнение пустых ячеек до первого дня месяца
                      $calendar .= '<tr>';
                      for ($i = 1; $i < $firstDay; $i++) {
                          $calendar .= '<td></td>';
                      }

                      // Заполнение ячеек с датами
                      while ($dayCounter <= $totalDays) {
                          for ($i = $firstDay; $i <= 7; $i++) {
                              if ($dayCounter > $totalDays) {
                                  break;
                              }

                              // Добавление классов "current-day" и "booked-day"
                              $class = '';
                              if ($dayCounter == $currentDay && $month == $currentMonth && $year == $currentYear) {
                                  $class .= 'current-day ';
                              }

                              $isBooked = in_array("$year-$month-$dayCounter", $bookedDates);
                              $class .= $isBooked ? 'booked-day' : '';

                              $calendar .= "<td class='$class'>$dayCounter</td>";
                              $dayCounter++;
                          }

                          // Начать новую строку после каждой недели
                          if ($dayCounter <= $totalDays) {
                              $calendar .= '</tr><tr>';
                          }

                          // Сброс первого дня недели после окончания каждой недели
                          $firstDay = 1;
                      }

                      $calendar .= '</tr></table></td>';

                      // Добавить отступы между календарями
                      if ($month == 6) {
                          $calendar .= '</tr><tr style="height: 20px;"></tr><tr>';
                      }
                  }
                  $calendar .= '</tr></table>';
                  
                  return $calendar;
              }

              // Получение забронированных дат из базы данных
              function getBookedDatesFromDatabase()
              {
                $bookedDates = array();
                  $servername = "localhost";
                  $username = "sammy";
                  $password = "PaSsWoRd";
                  $dbname = "HOUSES";

                  $conn = new mysqli($servername, $username, $password, $dbname);
                  // Проверка соединения
                  if ($conn->connect_error) {
                      die("Ошибка подключения: " . $conn->connect_error);
                  }

                  // Запрос на получение забронированных дат
                  $sql_marb = "SELECT startDate, endDate FROM Marb_house";
                  $result_marb = $conn->query($sql_marb);

                  if ($result_marb->num_rows > 0) {
                      // Добавление забронированных дат в массив
                      while ($row = $result_marb->fetch_assoc()) {
                          $startDate = $row["startDate"];
                          $endDate = $row["endDate"];

                          $start = new DateTime($startDate);
                          $end = new DateTime($endDate);

                          $interval = new DateInterval('P1D');
                          $period = new DatePeriod($start, $interval, $end);

                          foreach ($period as $date) {
                            $bookedDates[] = $date->format("Y-n-j");
                          }
                      }
                  }
                  $conn->close();

                  return $bookedDates;
              }

              // Текущий год
              $currentYear = date('Y');

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
                          print_r($stmt_marb);
                          header("Location: bron.php");
                          exit();
                      } else {
                          echo "Ошибка при добавлении бронирования: " . $conn->error;
                      }
                      $conn->close();
                }
              }
                
              echo createCalendar($currentYear);
              ?>
              </div>
              <div class="event-form">
                <h3>Форма бронирования Марбака</h3>
                <form method="POST" action="">
                    <input type="text" name="full_name" id="full-name" placeholder="ФИО" required>
                    <input type="email" name="email" id="email" placeholder="Email" required>
                    <input type="date" name="start_date" id="event-start-date" placeholder="Start Date" required> 
                    <input type="date" name="end_date" id="event-end-date" placeholder="End Date" required>
                    <button class="sub" type="submit" id="add-event-btn">Забронировать</button>
                </form>
            </div>
        </div>
  
      <div class="text3">
          <h2>Дом на воде «ПРОФЕССОР»</h2>
          <p>Двухпалубный дебаркадер с каютами в морском стиле на 10 человек.
              Здесь есть все, чтобы отлично отдохнуть с друзьями – на первом этаже кают-компания, ТВ, сауна, кухня.
              На втором – открытая палуба под тентом, мангал, столы, стулья.
              Все закаты будут ваши!
              Уютные двухместные каюты вверху и внизу.
              Прямо за бортом – песчаный пляж.
              Санузел, душ на территории в 50 метрах от Дома на воде.</p>
              <button class="toggle-calendar-btn2">Открыть календарь</button>
              <div class="calendar-container-2">
              <button class="close-cal2" type="button">Закрыть</button>
              <?php
              function createCalendar1($year)
              {
                // Создание таблицы календаря
                $calendar1 = '<table><tr>';

                  $monthNames = array(
                      'Январь', 'Февраль', 'Март', 'Апрель',
                      'Май', 'Июнь', 'Июль', 'Август',
                      'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
                  );

                  $currentDay = date('j');
                  $currentMonth = date('n');
                  $currentYear = date('Y');

                  // Получение забронированных дат из базы данных
                  $bookedDates1 = array();
                  if($bookedDates1 == null){
                    $bookedDates1 = getBookedDatesFromDatabase1();
                }

                  for ($month = 1; $month <= 12; $month++) {
                      // Первый день месяца
                      $firstDay = date("N", strtotime("$year-$month-01"));

                      // Общее количество дней в месяце
                      $totalDays = date("t", strtotime("$year-$month-01"));

                      // Название месяца
                      $monthName = $monthNames[$month - 1];

                      // Создание заголовка месяца
                      $calendar1 .= '<td style="vertical-align: top; padding: 10px;"><table><tr><th colspan="7">' . $monthName . '</th></tr>';
                      $calendar1 .= '<tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Вс</th></tr>';

                      $dayCounter = 1;

                      // Заполнение пустых ячеек до первого дня месяца
                      $calendar1 .= '<tr>';
                      for ($i = 1; $i < $firstDay; $i++) {
                          $calendar1 .= '<td></td>';
                      }

                      // Заполнение ячеек с датами
                      while ($dayCounter <= $totalDays) {
                          for ($i = $firstDay; $i <= 7; $i++) {
                              if ($dayCounter > $totalDays) {
                                  break;
                              }

                              // Добавление классов "current-day" и "booked-day"
                              $class = '';
                              if ($dayCounter == $currentDay && $month == $currentMonth && $year == $currentYear) {
                                  $class .= 'current-day ';
                              }
                              $isBooked = in_array("$year-$month-$dayCounter", $bookedDates1);
                              $class .= $isBooked ? 'booked-day' : '';

                              $calendar1 .= "<td class='$class'>$dayCounter</td>";
                              $dayCounter++;
                          }
                          $servername = "localhost";

                          // Начать новую строку после каждой недели
                          if ($dayCounter <= $totalDays) {
                              $calendar1 .= '</tr><tr>';
                          }

                          // Сброс первого дня недели после окончания каждой недели
                          $firstDay = 1;
                      }

                      $calendar1 .= '</tr></table></td>';

                      // Добавить отступы между календарями
                      if ($month == 6) {
                          $calendar1 .= '</tr><tr style="height: 20px;"></tr><tr>';
                      }
                  }
                  $calendar1 .= '</tr></table>';
                  
                  return $calendar1;
              }

              // Получение забронированных дат из базы данных
              function getBookedDatesFromDatabase1()
              {
                  $bookedDates1 = array();

                  $servername = "localhost";
                  $username = "sammy";
                  $password = "PaSsWoRd";
                  $dbname = "HOUSES";

                  $conn = new mysqli($servername, $username, $password, $dbname);
                  // Проверка соединения
                  if ($conn->connect_error) {
                      die("Ошибка подключения: " . $conn->connect_error);
                  }

                  // Запрос на получение забронированных дат
                  $sql_proff = "SELECT startDate, endDate FROM Proff_house";
                  $result_proff = $conn->query($sql_proff);

                  if ($result_proff->num_rows > 0) {
                      // Добавление забронированных дат в массив
                      while ($row = $result_proff->fetch_assoc()) {
                          $startDate = $row["startDate"];
                          $endDate = $row["endDate"];

                          $start = new DateTime($startDate);
                          $end = new DateTime($endDate);

                          $interval = new DateInterval('P1D');
                          $period = new DatePeriod($start, $interval, $end);

                          foreach ($period as $date) {
                            $bookedDates1[] = $date->format("j-n-Y");
                          }
                      }
                  }

                  $conn->close();

                  return $bookedDates1;
              }

              // Текущий год
              $currentYear = date('Y');

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
                    $full_name_1 = $_POST['full_name_1'];
                    $email_1 = $_POST['email_1'];
                    $start_date_1 = $_POST['start_date_1'];
                    $end_date_1 = $_POST['end_date_1'];
                    $stmt_proff = $conn->prepare("INSERT INTO Список_Профессор (ФИО, Email, start_date, end_date, Дом) VALUES ('$full_name_1', '$email_1', '$start_date_1', '$end_date_1', 'Профессор')");
                    if ($stmt_proff->execute()) {
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
              echo createCalendar1($currentYear);
              ?>
              </div>
              <div class="event-form">
                <h3>Форма бронирования Профессор</h3>
                <form method="POST" action="">
                    <input type="text" name="full_name_1" id="full-name_1" placeholder="ФИО" required>
                    <input type="email" name="email_1" id="email_1" placeholder="Email" required>
                    <input type="date" name="start_date_1" id="event-start-date_1" placeholder="Start Date" required>
                    <input type="date" name="end_date_1" id="event-end-date_1" placeholder="End Date" required>
                    <button class="sub" type="submit" id="add-event-btn">Забронировать</button>
                </form>
              </div>
      </div>
  
      <div class="text4">
          <h2>Дом "У ВОДЫ"</h2>
          <p>Бревенчатый домик с мансардой расположился у самой воды.
              У вас будет свой собственный пляж, прямо на берегу стол со скамейками, костровище, мангал.
              И деревянные мостки над водой – чтобы загорать и любоваться закатами.
              В доме горница с деревянным столом и скамьями, удобным диваном и креслами, мини кухня и душ.
              Спальня на втором этаже под крышей.
              Здесь дышится и спится особенно легко и спокойно.</p>
              <button class="toggle-calendar-btn3">Открыть календарь</button>
              <div class="calendar-container-3">
              <button class="close-cal3" type="button">Закрыть</button>
              <?php
              function createCalendar2($year)
              {
                // Создание таблицы календаря
                $calendar2 = '<table><tr>';

                  $monthNames = array(
                      'Январь', 'Февраль', 'Март', 'Апрель',
                      'Май', 'Июнь', 'Июль', 'Август',
                      'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
                  );

                  $currentDay = date('j');
                  $currentMonth = date('n');
                  $currentYear = date('Y');

                  $bookedDates2 = array();
                  if($bookedDates2 == null){
                    $bookedDates2 = getBookedDatesFromDatabase2();
                }

                  for ($month = 1; $month <= 12; $month++) {
                      // Первый день месяца
                      $firstDay = date("N", strtotime("$year-$month-01"));

                      // Общее количество дней в месяце
                      $totalDays = date("t", strtotime("$year-$month-01"));

                      // Название месяца
                      $monthName = $monthNames[$month - 1];

                      // Создание заголовка месяца
                      $calendar2 .= '<td style="vertical-align: top; padding: 10px;"><table><tr><th colspan="7">' . $monthName . '</th></tr>';
                      $calendar2 .= '<tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Вс</th></tr>';

                      $dayCounter = 1;

                      // Заполнение пустых ячеек до первого дня месяца
                      $calendar2 .= '<tr>';
                      for ($i = 1; $i < $firstDay; $i++) {
                          $calendar2 .= '<td></td>';
                      }

                      // Заполнение ячеек с датами
                      while ($dayCounter <= $totalDays) {
                          for ($i = $firstDay; $i <= 7; $i++) {
                              if ($dayCounter > $totalDays) {
                                  break;
                              }

                              // Добавление классов "current-day" и "booked-day"
                              $class = '';
                              if ($dayCounter == $currentDay && $month == $currentMonth && $year == $currentYear) {
                                  $class .= 'current-day ';
                              }
                              $isBooked = in_array("$year-$month-$dayCounter", $bookedDates2);
                              $class .= $isBooked ? 'booked-day' : '';

                              $calendar2 .= "<td class='$class'>$dayCounter</td>";
                              $dayCounter++;
                          }

                          // Начать новую строку после каждой недели
                          if ($dayCounter <= $totalDays) {
                              $calendar2 .= '</tr><tr>';
                          }

                          // Сброс первого дня недели после окончания каждой недели
                          $firstDay = 1;
                      }

                      $calendar2 .= '</tr></table></td>';

                      // Добавить отступы между календарями
                      if ($month == 6) {
                          $calendar2 .= '</tr><tr style="height: 20px;"></tr><tr>';
                      }
                  }
                  $calendar2 .= '</tr></table>';
                  
                  return $calendar2;
              }

              // Получение забронированных дат из базы данных
              function getBookedDatesFromDatabase2()
              {
                  $bookedDates2 = array();

                  $servername = "localhost";
                  $username = "sammy";
                  $password = "PaSsWoRd";
                  $dbname = "HOUSES";

                  $conn = new mysqli($servername, $username, $password, $dbname);
                  // Проверка соединения
                  if ($conn->connect_error) {
                      die("Ошибка подключения: " . $conn->connect_error);
                  }

                  // Запрос на получение забронированных дат
                  $sql_u_vod = "SELECT startDate, endDate FROM U_vod_house";
                  $result = $conn->query($sql_u_vod);

                  if ($result->num_rows > 0) {
                      // Добавление забронированных дат в массив
                      while ($row = $result->fetch_assoc()) {
                          $startDate = $row["startDate"];
                          $endDate = $row["endDate"];

                          $start = new DateTime($startDate);
                          $end = new DateTime($endDate);

                          $interval = new DateInterval('P1D');
                          $period = new DatePeriod($start, $interval, $end);

                          foreach ($period as $date) {
                            $bookedDates2[] = $date->format("Y-n-j");
                          }
                      }
                  }

                  $conn->close();

                  return $bookedDates2;
              }

              // Текущий год
              $currentYear = date('Y');

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
                    $full_name = $_POST['full_name2'];
                    $email = $_POST['email2'];
                    $start_date = $_POST['start_date2'];
                    $end_date = $_POST['end_date2'];
                    $stmt_u_vod = $conn->prepare("INSERT INTO Список_бронирований (ФИО, Email, start_date, end_date, Дом) VALUES ('$full_name', '$email', '$start_date', '$end_date', 'У Воды')");
                    if ($stmt_u_vod->execute()) {
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

              echo createCalendar2($currentYear);
              ?>
              </div>
              <div class="event-form">
                <h3>Форма бронирования У воды</h3>
                <form method="POST" action="">
                    <input type="text" name="full_name2" id="full-name2" placeholder="ФИО" required>
                    <input type="email" name="email2" id="email2" placeholder="Email" required>
                    <input type="date" name="start_date2" id="event-start-date2" placeholder="Start Date" required>
                    <input type="date" name="end_date2" id="event-end-date2" placeholder="End Date" required>
                    <button class="sub" type="submit" id="add-event-btn2">Забронировать</button>
                </form>
              </div>
      </div>
  
      <div class="text5">
          <h2>Сафари-домик «СФЕРА»</h2>
          <p>Сосны нежно трогают крышу палатки своими лохматыми лапами, а в раскрытую дверь видно море...
              В домике светло в любую погоду, ночью тепло, а днем не жарко – работает кондиционер.
              Для тех кто хочет сильнее ощутить единение с природой, на общем с домиком помосте - минибеседка.
              Есть чайник, предоставляется холодильник.
              Рядом – костровое место, мангал, столик, скамейки, умывальник.
              А в 20 метрах – комфортный душ, туалет.</p>
              <button class="toggle-calendar-btn4">Открыть календарь</button>
              <div class="calendar-container-4">
              <button class="close-cal4" type="button">Закрыть</button>
              <?php
              function createCalendar3($year)
              {
                // Создание таблицы календаря
                $calendar3 = '<table><tr>';

                  $monthNames = array(
                      'Январь', 'Февраль', 'Март', 'Апрель',
                      'Май', 'Июнь', 'Июль', 'Август',
                      'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
                  );

                  $currentDay = date('j');
                  $currentMonth = date('n');
                  $currentYear = date('Y');

                  $bookedDates3 = array();
                  if($bookedDates3 == null){
                    $bookedDates3 = getBookedDatesFromDatabase3();
                }

                  for ($month = 1; $month <= 12; $month++) {
                      // Первый день месяца
                      $firstDay = date("N", strtotime("$year-$month-01"));

                      // Общее количество дней в месяце
                      $totalDays = date("t", strtotime("$year-$month-01"));

                      // Название месяца
                      $monthName = $monthNames[$month - 1];

                      // Создание заголовка месяца
                      $calendar3 .= '<td style="vertical-align: top; padding: 10px;"><table><tr><th colspan="7">' . $monthName . '</th></tr>';
                      $calendar3 .= '<tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Вс</th></tr>';

                      $dayCounter = 1;

                      // Заполнение пустых ячеек до первого дня месяца
                      $calendar3 .= '<tr>';
                      for ($i = 1; $i < $firstDay; $i++) {
                          $calendar3 .= '<td></td>';
                      }

                      // Заполнение ячеек с датами
                      while ($dayCounter <= $totalDays) {
                          for ($i = $firstDay; $i <= 7; $i++) {
                              if ($dayCounter > $totalDays) {
                                  break;
                              }

                              // Добавление классов "current-day" и "booked-day"
                              $class = '';
                              if ($dayCounter == $currentDay && $month == $currentMonth && $year == $currentYear) {
                                  $class .= 'current-day ';
                              }
                              $isBooked = in_array("$year-$month-$dayCounter", $bookedDates3);
                              $class .= $isBooked ? 'booked-day' : '';

                              $calendar3 .= "<td class='$class'>$dayCounter</td>";
                              $dayCounter++;
                          }

                          // Начать новую строку после каждой недели
                          if ($dayCounter <= $totalDays) {
                              $calendar3 .= '</tr><tr>';
                          }

                          // Сброс первого дня недели после окончания каждой недели
                          $firstDay = 1;
                      }

                      $calendar3 .= '</tr></table></td>';

                      // Добавить отступы между календарями
                      if ($month == 6) {
                          $calendar3 .= '</tr><tr style="height: 20px;"></tr><tr>';
                      }
                  }
                  $calendar3 .= '</tr></table>';
                  
                  return $calendar3;
              }

              // Получение забронированных дат из базы данных
              function getBookedDatesFromDatabase3()
              {
                  $bookedDates3 = array();

                  $servername = "localhost";
                  $username = "sammy";
                  $password = "PaSsWoRd";
                  $dbname = "HOUSES";

                  $conn = new mysqli($servername, $username, $password, $dbname);
                  // Проверка соединения
                  if ($conn->connect_error) {
                      die("Ошибка подключения: " . $conn->connect_error);
                  }
                  // Запрос на получение забронированных дат
                  $sql_spher = "SELECT startDate, endDate FROM Spher_house";
                  $result = $conn->query($sql_spher);

                  if ($result->num_rows > 0) {
                      // Добавление забронированных дат в массив
                      while ($row = $result->fetch_assoc()) {
                          $startDate = $row["startDate"];
                          $endDate = $row["endDate"];

                          $start = new DateTime($startDate);
                          $end = new DateTime($endDate);

                          $interval = new DateInterval('P1D');
                          $period = new DatePeriod($start, $interval, $end);

                          foreach ($period as $date) {
                            $bookedDates3[] = $date->format("Y-n-j");
                          }
                      }
                  }

                  $conn->close();

                  return $bookedDates3;
              }

              // Текущий год
              $currentYear = date('Y');

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
                    $stmt_spher = $conn->prepare("INSERT INTO Список_бронирований (ФИО, Email, start_date, end_date, Дом) VALUES ('$full_name', '$email', '$start_date', '$end_date', 'Сфера')");
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

              echo createCalendar3($currentYear);
              ?>
              </div>
              <div class="event-form">
                <h3>Форма бронирования Сфера</h3>
                <form method="POST" action="">
                    <input type="text" name="full_name3" id="full-name" placeholder="ФИО" required>
                    <input type="email" name="email3" id="email" placeholder="Email" required>
                    <input type="date" name="start_date3" id="event-start-date" placeholder="Start Date" required>
                    <input type="date" name="end_date3" id="event-end-date" placeholder="End Date" required>
                    <button class="sub" type="submit" id="add-event-btn">Забронировать</button>
                </form>
              </div>
      </div>
  
      <div class="text6">
          <h2>Дом на колесах "Трейлер"</h2>
          <p>Для тех, кого влечет кочевая романтика.
              Для тех, кто считает, что дом должен быть там, где я.
              Для двух человек.
              Кухня - мини, холодильник - мини, душ - мини.
              Спальное место — макси.</p>
              <button class="toggle-calendar-btn5">Открыть календарь</button>
              <div class="calendar-container-5">
              <button class="close-cal5" type="button">Закрыть</button>
                <?php
                function createCalendar4($year)
                {
                    // Создание таблицы календаря
                    $calendar4 = '<table><tr>';

                    $monthNames = array(
                        'Январь', 'Февраль', 'Март', 'Апрель',
                        'Май', 'Июнь', 'Июль', 'Август',
                        'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
                    );

                    $currentDay = date('j');
                    $currentMonth = date('n');
                    $currentYear = date('Y');

                    $bookedDates4 = array();
                  if($bookedDates4 == null){
                    $bookedDates4 = getBookedDatesFromDatabase4();
                }

                    for ($month = 1; $month <= 12; $month++) {
                        // Первый день месяца
                        $firstDay = date("N", strtotime("$year-$month-01"));

                        // Общее количество дней в месяце
                        $totalDays = date("t", strtotime("$year-$month-01"));

                        // Название месяца
                        $monthName = $monthNames[$month - 1];

                        // Создание заголовка месяца
                        $calendar4 .= '<td style="vertical-align: top; padding: 10px;"><table><tr><th colspan="7">' . $monthName . '</th></tr>';
                        $calendar4 .= '<tr><th>Пн</th><th>Вт</th><th>Ср</th><th>Чт</th><th>Пт</th><th>Сб</th><th>Вс</th></tr>';

                        $dayCounter = 1;

                        // Заполнение пустых ячеек до первого дня месяца
                        $calendar4 .= '<tr>';
                        for ($i = 1; $i < $firstDay; $i++) {
                            $calendar4 .= '<td></td>';
                        }

                        // Заполнение ячеек с датами
                        while ($dayCounter <= $totalDays) {
                            for ($i = $firstDay; $i <= 7; $i++) {
                                if ($dayCounter > $totalDays) {
                                    break;
                                }

                                // Добавление классов "current-day" и "booked-day"
                                $class = '';
                                if ($dayCounter == $currentDay && $month == $currentMonth && $year == $currentYear) {
                                    $class .= 'current-day ';
                                }
                                $isBooked = in_array("$year-$month-$dayCounter", $bookedDates4);
                                $class .= $isBooked ? 'booked-day' : '';

                                $calendar4 .= "<td class='$class'>$dayCounter</td>";
                                $dayCounter++;
                            }

                            // Начать новую строку после каждой недели
                            if ($dayCounter <= $totalDays) {
                                $calendar4 .= '</tr><tr>';
                            }

                            // Сброс первого дня недели после окончания каждой недели
                            $firstDay = 1;
                        }

                        $calendar4 .= '</tr></table></td>';

                        // Добавить отступы между календарями
                        if ($month == 6) {
                            $calendar4 .= '</tr><tr style="height: 20px;"></tr><tr>';
                        }
                    }
                    $calendar4 .= '</tr></table>';
                    
                    return $calendar4;
                }

                // Получение забронированных дат из базы данных
                function getBookedDatesFromDatabase4()
                {
                    $bookedDates4 = array();

                    $servername = "localhost";
                    $username = "sammy";
                    $password = "PaSsWoRd";
                    $dbname = "HOUSES";

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    // Проверка соединения
                    if ($conn->connect_error) {
                        die("Ошибка подключения: " . $conn->connect_error);
                    }

                    // Запрос на получение забронированных дат
                    $sql_trail = "SELECT startDate, endDate FROM Trail_house";
                    $result = $conn->query($sql_trail);

                    if ($result->num_rows > 0) {
                        // Добавление забронированных дат в массив
                        while ($row = $result->fetch_assoc()) {
                            $startDate = $row["startDate"];
                            $endDate = $row["endDate"];

                            $start = new DateTime($startDate);
                            $end = new DateTime($endDate);

                            $interval = new DateInterval('P1D');
                            $period = new DatePeriod($start, $interval, $end);

                            foreach ($period as $date) {
                                $bookedDates4[] = $date->format("Y-n-j");
                              }
                        }
                    }

                    $conn->close();

                    return $bookedDates4;
                }

                // Текущий год
                $currentYear = date('Y');

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
                    $full_name = $_POST['full_name4'];
                    $email = $_POST['email4'];
                    $start_date = $_POST['start_date4'];
                    $end_date = $_POST['end_date4'];
                    $stmt_trail = $conn->prepare("INSERT INTO Список_бронирований (ФИО, Email, start_date, end_date, Дом) VALUES ('$full_name', '$email', '$start_date', '$end_date', 'Трейлер')");
                    if ($stmt_trail->execute()) {
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

                echo createCalendar4($currentYear);
                ?>
              </div>
              <div class="event-form">
                <h3>Форма бронирования Трейлер</h3>
                <form method="POST" action="">
                    <input type="text" name="full_name4" id="full-name4" placeholder="ФИО" required>
                    <input type="email" name="email4" id="email4" placeholder="Email" required>
                    <input type="date" name="start_date4" id="event-start-date4" placeholder="Start Date" required>
                    <input type="date" name="end_date4" id="event-end-date4" placeholder="End Date" required>
                    <button class="sub" type="submit" id="add-event-btn4">Забронировать</button>
                </form>
              </div>
      </div>
  
      <div class="text7">
          <h2>Правила пребывания на базе отдыха "Сфера":</h2>
          <em>Мы будем рады видеть Вас среди наших гостей и надеемся, что дни, проведенные на базе отдыха "Сфера", станут одними из самых беззаботных! Для этого ознакомьтесь, пожалуйста, с Правилами проживания.</em>
          <p><br>Правила заселения и выезда:<br><br>
               - Заезд на базу отдыха осуществляется с 14-00 часов.
               - Расчетный час 12-00.<br><br>
               - Допускается продление пребывания гостей после расчетного часа выезда только по согласованию с администрацией базы отдыха.<br><br>
               - Гости, выезжающие с территории базы отдыха в период с 12-00 до 17-00 производят доплату в размере 50% суточной платы;
              выезжающие после 17-00 производят оплату за сутки.<br><br><br>
              Оплата за проживание:<br><br>
               - Расчет за проживание производится в течение часа после заселения на базу отдыха полностью за все дни пребывания за минусом предоплаты (бронирования).<br><br>
               - В случае несвоевременного прибытия проживание не продлевается.<br><br>
               - В случае досрочного выезда, оплата за неиспользованные услуги не возвращается.<br><br><br>
              Бронирование:<br><br>
               - Предварительную заявку на бронирование можно оформить на сайте или по телефону +7-(953)-872-9009.<br><br>
               - Предварительное бронирование возможно от 2-х суток, стоимость бронирования – 10% от общей стоимости проживания.<br><br>
               - Бронирование считается осуществленным только после подтверждения администрации.<br><br>
               - Сумма за бронирование возвращается в случае отказа от проживания не менее, чем за неделю до даты заезда.<br><br>
               - Вся текущая информация о свободных местах есть на сайте, дополнительную информацию можно получить по телефону.<br><br><br>
              Питание:<br><br>
               - Питание на базе отдыха осуществляется самостоятельно.<br><br>
               - Возле домиков есть мангальные зоны.<br><br>
               - Для хранения продуктов есть холодильники.<br><br><br>
              О сохранности имущества:<br><br>
               - Администрация базы отдыха не несет ответственности за оставленные без присмотра деньги или ценные вещи.<br><br>
               - Администрация не несет ответственности за сохранность личного транспорта гостей, оставленного на парковке.<br><br>
               - Проживающие на базе отдыха должны бережно относиться к имуществу и оборудованию, соблюдать чистоту и порядок, не мешать отдыхающим из других домиков.<br><br>
               - За поврежденное или утерянное имущество базы отдыха проживающие и отдыхающие несут материальную ответственность и возмещают причиненный материальный ущерб в действующих на момент проживания ценах.<br><br><br>
              Проживание:<br><br>
               - Размещение возможно только в соответствии с количеством заявленных в домике мест.<br><br>
               - Размещать гостей на территории и в номере в дневное время возможно только по согласованию с администрацией базы отдыха.<br><br>
               - В ночное время размещение гостей запрещено.<br><br>
               - Беседки и площадки с мангалами возле домиков предназначены только для проживающих в этих домиках.<br><br>
               - Личный автотранспорт оставлять на месте, предназначенном для парковки.<br><br>
               - Запрещается включать музыку, нарушать покой и отдых других посетителей базы.<br><br>
               - Размещение с собаками и другими домашними животными возможно только по предварительной договоренности.<br><br><br>
              Приятного отдыха!</p>
      </div>
    </div>
  </body>
</html>