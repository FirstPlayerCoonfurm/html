document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("burger").addEventListener("click", function() {
        document.querySelector("header").classList.toggle("open")
    })
})

var Cal = function(divId) {
  // Сохраняем идентификатор div
  this.divId = divId;
  // Дни недели с понедельника
  this.DaysOfWeek = [
    'Пн',
    'Вт',
    'Ср',
    'Чтв',
    'Птн',
    'Суб',
    'Вск'
  ];
  // Месяцы начиная с января
  this.Months =['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
  // Устанавливаем текущий месяц, год
  var d = new Date();
  this.currMonth = d.getMonth();
  this.currYear = d.getFullYear();
  this.currDay = d.getDate();
  this.db = null; // Инициализируем переменную для хранения ссылки на IndexDB
  this.openDB(); // Вызываем функцию для открытия базы данных IndexDB;
  this.selectedStartDate = null; // Сохраняем выбранную начальную дату
  this.selectedEndDate = null; // Сохраняем выбранную конечную дату
  this.isSelectingRange = false; // Флаг, указывающий на то, что пользователь выбирает промежуток дат
};


// Открытие базы данных IndexDB
Cal.prototype.openDB = function() {
  var self = this;
  var request = window.indexedDB.open("CalendarDB", 1);

  request.onupgradeneeded = function(event) {
    var db = event.target.result;
    var objectStore = db.createObjectStore("events", { keyPath: "id", autoIncrement: true });
    objectStore.createIndex("start", "start", { unique: false });
    objectStore.createIndex("end", "end", { unique: false });
    objectStore.createIndex("title", "title", { unique: false });
  };

  request.onsuccess = function(event) {
    self.db = event.target.result;
    self.showcurr();
  };

  request.onerror = function(event) {
    console.log("Error opening database");
  };
};

// Добавление события в базу данных IndexDB
Cal.prototype.addEvent = function(title, start, end) {
  var self = this;
  var transaction = this.db.transaction(["events"], "readwrite");
  var objectStore = transaction.objectStore("events");
  var request = objectStore.add({title: title, start: start, end: end });

  request.onsuccess = function(event) {
    console.log("Event added to the database");
  };

  request.onerror = function(event) {
    console.log("Error adding event to the database");
  };
};

// Получение всех событий из базы данных IndexDB
Cal.prototype.getEvents = function(callback) {
  if (this.db === null) {
    console.log("Database is not open");
    callback([]);
    return;
  }

  var transaction = this.db.transaction(["events"], "readonly");
  var objectStore = transaction.objectStore("events");
  var request = objectStore.getAll();

  request.onsuccess = function(event) {
    callback(event.target.result);
  };

  request.onerror = function(event) {
    console.log("Error getting events from database");
    callback([]);
  };
};

// Переход к следующему месяцу
Cal.prototype.nextMonth = function() {
  if ( this.currMonth == 11 ) {
    this.currMonth = 0;
    this.currYear = this.currYear + 1;
  }
  else {
    this.currMonth = this.currMonth + 1;
  }
  this.showcurr();
};
// Переход к предыдущему месяцу
Cal.prototype.previousMonth = function() {
  if ( this.currMonth == 0 ) {
    this.currMonth = 11;
    this.currYear = this.currYear - 1;
  }
  else {
    this.currMonth = this.currMonth - 1;
  }
  this.showcurr();
};
// Показать текущий месяц
Cal.prototype.showcurr = function() {
  this.showMonth(this.currYear, this.currMonth);
};
// Показать месяц (год, месяц)
Cal.prototype.showMonth = function(y, m) {
  var d = new Date()
  // Первый день недели в выбранном месяце 
  , firstDayOfMonth = new Date(y, m, 7).getDay()
  // Последний день выбранного месяца
  , lastDateOfMonth =  new Date(y, m+1, 0).getDate()
  // Последний день предыдущего месяца
  , lastDayOfLastMonth = m == 0 ? new Date(y-1, 11, 0).getDate() : new Date(y, m, 0).getDate();
  var html = '<table>';
  // Запись выбранного месяца и года
  html += '<thead><tr>';
  html += '<td colspan="7">' + this.Months[m] + ' ' + y + '</td>';
  html += '</tr></thead>';
  // заголовок дней недели
  html += '<tr class="days">';
  for(var i=0; i < this.DaysOfWeek.length;i++) {
    html += '<td>' + this.DaysOfWeek[i] + '</td>';
  }
  html += '</tr>';
  // Записываем дни
  var i=1;
  do {
    var dow = new Date(y, m, i).getDay();
    // Начать новую строку в понедельник
    if ( dow == 1 ) {
      html += '<tr>';
    }
    // Если первый день недели не понедельник показать последние дни предыдущего месяца
    else if ( i == 1 ) {
      html += '<tr>';
      var k = lastDayOfLastMonth - firstDayOfMonth+1;
      for(var j=0; j < firstDayOfMonth; j++) {
        html += '<td class="not-current">' + k + '</td>';
        k++;
      }
    }
    // Записываем текущий день в цикл
    var chk = new Date();
    var chkY = chk.getFullYear();
    var chkM = chk.getMonth();
    if (chkY == this.currYear && chkM == this.currMonth && i == this.currDay) {
      html += '<td class="today">' + i + '</td>';
    } else {
      html += '<td class="normal">' + i + '</td>';
    }
    // закрыть строку в воскресенье
    if ( dow == 0 ) {
      html += '</tr>';
    }
    // Если последний день месяца не воскресенье, показать первые дни следующего месяца
    else if ( i == lastDateOfMonth ) {
      var k=1;
      for(dow; dow < 7; dow++) {
        html += '<td class="not-current">' + k + '</td>';
        k++;
      }
    }
    i++;
  }while(i <= lastDateOfMonth);
  // Конец таблицы
  html += '</table>';
  // Записываем HTML в div
  document.getElementById(this.divId).innerHTML = html;
};
// При загрузке окна
window.onload = function() {
  // Начать календарь
  var c = new Cal("divCal");			
  c.showcurr();
  // Привязываем кнопки «Следующий» и «Предыдущий»
  getId('btnNext').onclick = function() {
    c.nextMonth();
  };
  getId('btnPrev').onclick = function() {
    c.previousMonth();
  };
}
// Получить элемент по id
function getId(id) {
  return document.getElementById(id);
}

// Создание экземпляра календаря
var calendar = new Cal("divCal");

// Обработчик клика на день в календаре
document.getElementById("divCal").addEventListener("click", function(event) {
    var target = event.target;
    if (target.classList.contains("day") && !calendar.isSelectingRange) {
      var day = target.getAttribute("data-day");
      var month = target.getAttribute("data-month");
      var year = target.getAttribute("data-year");
      var date = new Date(year, month, day);
      var formattedDate = formatDate(date);
      var title = prompt("Введите название события для " + formattedDate + ":");
      if (title) {
        calendar.addEvent(title, date, date);
        calendar.showcurr();
      }
    }
  });

// Функция для форматирования даты в формат "ДД.ММ.ГГГГ"
function formatDate(date) {
  var day = date.getDate();
  var month = date.getMonth() + 1;
  var year = date.getFullYear();
  return day + '.' + month + '.' + year;
}

// Функция для отображения событий в календаре
function showEvents(events) {
  var eventList = document.getElementById("eventList");
  eventList.innerHTML = "";
  for (var i = 0; i < events.length; i++) {
    var event = events[i];
    var listItem = document.createElement("li");
    listItem.textContent = event.title;
    eventList.appendChild(listItem);
  }
}

// Обновите вызов функции `getEvents` в коде:
// Получение всех событий из базы данных и отображение их в календаре
calendar.getEvents(function(events) {
  showEvents(events);
});

// Проверка, является ли дата забронированной
Cal.prototype.isDateBooked = function(date) {
  var events = this.getEventsSync();
  for (var i = 0; i < events.length; i++) {
    var event = events[i];
    var start = new Date(event.start);
    var end = new Date(event.end);
    if (date >= start && date <= end) {
      return true;
    }
  }
  return false;
};

// Получение всех событий из базы данных и отображение их в календаре
calendar.getEvents(showEvents);

function bookInterval() {
  var startDateInput = document.getElementById("startDate");
  var endDateInput = document.getElementById("endDate");

  if (startDateInput && endDateInput) {
    var startDate = new Date(startDateInput.value);
    var endDate = new Date(endDateInput.value);
    
    if (startDate <= endDate) {
      calendar.addEvent("Бронирование", startDate, endDate); 
      calendar.isSelectingRange = false;
      calendar.showcurr();
    } else {
      console.log("Invalid date range");
    }
  }
}