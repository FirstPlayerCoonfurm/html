document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("burger").addEventListener("click", function() {
        document.querySelector("header").classList.toggle("open")
    })
})

var toggleBtn1 = document.querySelector('.toggle-calendar-btn1');
var toggleBtn2 = document.querySelector('.toggle-calendar-btn2');
var toggleBtn3 = document.querySelector('.toggle-calendar-btn3');
var toggleBtn4 = document.querySelector('.toggle-calendar-btn4');
var toggleBtn5 = document.querySelector('.toggle-calendar-btn5');

var calendar1 = document.querySelector('.calendar-container-1');
var calendar2 = document.querySelector('.calendar-container-2');
var calendar3 = document.querySelector('.calendar-container-3');
var calendar4 = document.querySelector('.calendar-container-4');
var calendar5 = document.querySelector('.calendar-container-5');

var closeBtn1 = document.querySelector('.close-cal1');
var closeBtn2 = document.querySelector('.close-cal2');
var closeBtn3 = document.querySelector('.close-cal3');
var closeBtn4 = document.querySelector('.close-cal4');
var closeBtn5 = document.querySelector('.close-cal5');

toggleBtn1.addEventListener('click', function() {
  calendar1.classList.add('show');
  toggleBtn1.classList.add('hidden');
});
closeBtn1.addEventListener('click', function() {
  calendar1.classList.remove('show');
  toggleBtn1.classList.remove('hidden');
});

toggleBtn2.addEventListener('click', function() {
    calendar2.classList.add('show');
    toggleBtn2.classList.add('hidden');
});
closeBtn2.addEventListener('click', function() {
    calendar2.classList.remove('show');
    toggleBtn2.classList.remove('hidden');
});

toggleBtn3.addEventListener('click', function() {
    calendar3.classList.add('show');
    toggleBtn3.classList.add('hidden');
});
closeBtn3.addEventListener('click', function() {
    calendar3.classList.remove('show');
    toggleBtn3.classList.remove('hidden');
});

toggleBtn4.addEventListener('click', function() {
    calendar4.classList.add('show');
    toggleBtn4.classList.add('hidden');
});
closeBtn4.addEventListener('click', function() {
    calendar4.classList.remove('show');
    toggleBtn4.classList.remove('hidden');
});

toggleBtn5.addEventListener('click', function() {
    calendar5.classList.add('show');
    toggleBtn5.classList.add('hidden');
});
closeBtn5.addEventListener('click', function() {
    calendar5.classList.remove('show');
    toggleBtn5.classList.remove('hidden');
});