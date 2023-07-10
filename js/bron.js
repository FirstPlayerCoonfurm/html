document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("burger").addEventListener("click", function() {
        document.querySelector("header").classList.toggle("open")
    })
})

document.addEventListener('DOMContentLoaded', function() {
    const prevMonthBtn = document.querySelector('.prev-month');
    const nextMonthBtn = document.querySelector('.next-month');
    const currentMonthLabel = document.querySelector('.current-month');
    const daysContainer = document.querySelector('.days tbody');
    const addEventBtn = document.querySelector('#add-event-btn');
  
    // Получить текущую дату
    const currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
  
    // Отобразить календарь для текущего месяца
    displayCalendar(currentMonth, currentYear);
  
    // Переключение на предыдущий месяц
    prevMonthBtn.addEventListener('click', function() {
      currentYear = currentMonth === 0 ? currentYear - 1 : currentYear;
      currentMonth = currentMonth === 0 ? 11 : currentMonth - 1;
      displayCalendar(currentMonth, currentYear);
    });
  
    // Переключение на следующий месяц
    nextMonthBtn.addEventListener('click', function() {
      currentYear = currentMonth === 11 ? currentYear + 1 : currentYear;
      currentMonth = currentMonth === 11 ? 0 : currentMonth + 1;
      displayCalendar(currentMonth, currentYear);
    });
  
    // Добавление события при нажатии кнопки "Add"
    addEventBtn.addEventListener('click', function() {
      const eventTitleInput = document.querySelector('#event-title');
      const eventDateInput = document.querySelector('#event-date');
      const eventTitle = eventTitleInput.value;
      const eventDate = eventDateInput.value;
  
      if (eventTitle && eventDate) {
        addEventToCalendar(eventTitle, eventDate);
        eventTitleInput.value = '';
        eventDateInput.value = '';
      }
    });
  
    // Отобразить календарь для указанного месяца и года
    function displayCalendar(month, year) {
      // Очистить предыдущий календарь
      daysContainer.innerHTML = '';
  
      // Установить метку текущего месяца и года
      currentMonthLabel.textContent = getMonthName(month) + ' ' + year;
  
      // Получить первый день указанного месяца
      const firstDay = new Date(year, month, 1);
  
      // Определить день недели для первого дня
      let firstDayOfWeek = firstDay.getDay();
      if (firstDayOfWeek === 0) {
        firstDayOfWeek = 6; // Переносим воскресенье в конец недели
      } else {
        firstDayOfWeek--; // Переносим остальные дни на одну позицию вперед
      }
  
      // Получить количество дней в указанном месяце
      const lastDay = new Date(year, month + 1, 0);
      const totalDays = lastDay.getDate();
  
      let date = 1;
  
      // Создать ячейки для дней в таблице
      for (let row = 0; row < 6; row++) {
        const newRow = document.createElement('tr');
  
        for (let col = 0; col < 7; col++) {
          if ((row === 0 && col < firstDayOfWeek) || date > totalDays) {
            // Создать пустую ячейку для дней до начала месяца или после его окончания
            const emptyCell = document.createElement('td');
            newRow.appendChild(emptyCell);
          } else {
            // Создать ячейку с номером дня
            const cell = document.createElement('td');
            cell.textContent = date;
            newRow.appendChild(cell);
            date++;
          }
        }
  
        daysContainer.appendChild(newRow);
      }
    }

// Получить название месяца по его номеру
function getMonthName(month) {
const months = [
'Январь',
'Февраль',
'Март',
'Апрель',
'Май',
'Июнь',
'Июль',
'Август',
'Сентябрь',
'Октябрь',
'Ноябрь',
'Декабрь'
];
return months[month];
}

// Добавить событие в календарь
function addEventToCalendar(title, date) {
const eventRow = document.createElement('tr');
const titleCell = document.createElement('td');
const dateCell = document.createElement('td');

titleCell.textContent = title;
dateCell.textContent = date;

eventRow.appendChild(titleCell);
eventRow.appendChild(dateCell);

daysContainer.appendChild(eventRow);
}
});