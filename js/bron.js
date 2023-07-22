document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("burger").addEventListener("click", function() {
        document.querySelector("header").classList.toggle("open")
    })
})

var toggleBtn = document.querySelector('.toggle-calendar-btn');
var calendar = document.querySelector('.calendar');
var closeBtn = document.querySelector('.close-cal');

toggleBtn.addEventListener('click', function() {
  calendar.classList.add('show');
  toggleBtn.classList.add('hidden');
});

closeBtn.addEventListener('click', function() {
  calendar.classList.remove('show');
  toggleBtn.classList.remove('hidden');
});

//marb

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
const eventStartDateInput = document.querySelector('#event-start-date');
const eventEndDateInput = document.querySelector('#event-end-date');
const eventStartDate = eventStartDateInput.value;
const eventEndDate = eventEndDateInput.value;

if (eventStartDate && eventEndDate) {
addEventToCalendar(eventStartDate, eventEndDate);
eventStartDateInput.value = '';
eventEndDateInput.value = '';
}
});

// Отобразить календарь для указанного месяца и года
function displayCalendar(month, year) {
// Очистить предыдущий календарь
daysContainer.innerHTML = '';

// Установить метку текущего месяца и года
currentMonthLabel.textContent = getMonthName(month) + ' ' + year;

// Получить текущую дату
const currentDate = new Date();
const currentMonth = currentDate.getMonth();
const currentYear = currentDate.getFullYear();
const currentDay = currentDate.getDate();

// Получить первый день указанного месяца
const firstDay = new Date(year, month, 1);

// Определить день недели для первого дня
let firstDayOfWeek = firstDay.getDay();
if (firstDayOfWeek === 0) {
firstDayOfWeek = 6; // Переносим воскресенье в конец недели
} else {
firstDayOfWeek--; // Переносим остальные дни на одну позицию вперед
}
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

if (year === currentYear && month === currentMonth && date === currentDay) {
  cell.classList.add('current-date');
}

const currentDate = new Date(year, month, date);
const events = getEventsFromLocalStorage();
if (events.some(event => isDateInRange(currentDate, new Date(event.startDate), new Date(event.endDate)))) {
  cell.classList.add('event-date');
}

// Получить текущую дату
const currentFullDate = new Date();
currentFullDate.setHours(0, 0, 0, 0); // Установить время на начало дня

if (currentFullDate > currentDate) {
  cell.classList.add('past-date');
}

// Добавить классы для текущей даты и событий
if (year === currentYear && month === currentMonth && date === currentDay) {
cell.classList.add('current-date');
}

if (events.some(event => isDateInRange(currentDate, new Date(event.startDate), new Date(event.endDate)))) {
cell.classList.add('event-date');
}

// Получить выбранные даты из формы
const selectedStartDate = new Date(document.getElementById('event-start-date').value);
selectedStartDate.setHours(3, 0, 0, 0); // Установить время на началоня
const selectedEndDate = new Date(document.getElementById('event-end-date').value);
selectedEndDate.setHours(23, 59, 59, 999); //Установить время на конец дня

if (selectedStartDate <= currentDate && currentDate <= selectedEndDate) {
cell.classList.add('selected-date');
}
newRow.appendChild(cell);
date++;
}
}

daysContainer.appendChild(newRow);
}
}

// Получить название месяца по его номеру
function getMonthName(month) {
const monthNames = [
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
return monthNames[month];
}

// Получить события из локального хранилища
function getEventsFromLocalStorage() {
const events = localStorage.getItem('events');
return events ? JSON.parse(events) : [];
}

// Добавить событие в календарь и сохранить в локальное хранилище
function addEventToCalendar(startDate, endDate) {
  const eventStartDate = new Date(startDate);
  const eventEndDate = new Date(endDate);
  const currentDate = new Date();

  if (eventStartDate >= currentDate && eventEndDate >= currentDate) {
    const event = { startDate, endDate };
    const events = getEventsFromLocalStorage();
    events.push(event);
    localStorage.setItem('events', JSON.stringify(events));
    displayCalendar(currentMonth, currentYear);
  } else {
    alert('Выберите дату, которая больше или равна текущей дате.');
  }
}

// Проверить, находится ли указанная дата в промежутке между start и end
function isDateInRange(date, startDate, endDate) {
return date >= startDate && date <= endDate;
}
});