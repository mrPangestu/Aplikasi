// JavaScript
document.addEventListener('DOMContentLoaded', function () {
  const yearSelector = document.getElementById('year-selector');
  const updateCalendarBtn = document.getElementById('update-calendar');
  const prevMonthBtn = document.getElementById('prev-month');
  const nextMonthBtn = document.getElementById('next-month');
  const calendarHeader = document.getElementById('current-month-year');
  const calendarTable = document.getElementById('calendar');
  const eventDateInput = document.getElementById('event-date');
  const eventDescriptionInput = document.getElementById('event-description');
  const addEventBtn = document.getElementById('add-event');

  let currentDate = new Date();

  function updateYearSelector() {
    const currentYear = new Date().getFullYear();
    const years = Array.from({ length: 10 }, (_, i) => currentYear - 5 + i);

    yearSelector.innerHTML = '';
    years.forEach((year) => {
      const option = document.createElement('option');
      option.value = year;
      option.text = year;
      yearSelector.appendChild(option);
    });

    yearSelector.value = currentDate.getFullYear();
  }

  function updateCalendar() {
    const selectedYear = yearSelector.value;

    currentDate = new Date(selectedYear, currentDate.getMonth(), 1);
    const firstDayOfWeek = currentDate.getDay();
    const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

    calendarHeader.textContent = `${currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}`;

    calendarTable.innerHTML = '<tr><th>Minggu</th><th>Senin</th><th>Selasa</th><th>Rabu</th><th>Kamis</th><th>Jumat</th><th>Sabtu</th></tr>';

    let dayCount = 1;

    for (let i = 0; i < 6; i++) {
      const row = calendarTable.insertRow();

      for (let j = 0; j < 7; j++) {
        if (i === 0 && j < firstDayOfWeek) {
          row.insertCell();
        } else if (dayCount > lastDay) {
          break;
        } else {
          const cell = row.insertCell();
          cell.textContent = dayCount;
          cell.dataset.date = `${selectedYear}-${(currentDate.getMonth() + 1).toString().padStart(2, '0')}-${dayCount.toString().padStart(2, '0')}`;
          cell.addEventListener('click', handleCellClick);
          dayCount++;
        }
      }
    }
  }

  function handleCellClick(event) {
    const selectedDate = event.target.dataset.date;
    eventDateInput.value = selectedDate;
  
    // Hapus kelas 'selected-date' dari semua sel tanggal
    const allCells = document.querySelectorAll('td');
    allCells.forEach(cell => cell.classList.remove('selected-date'));
  
    // Tambahkan kelas 'selected-date' pada sel yang diklik
    event.target.classList.add('selected-date');
  }

  function addEvent() {
    const date = eventDateInput.value;
    const description = eventDescriptionInput.value;

    // Implement your logic to add the event to the database or handle it as needed

    // For now, just hide the form
  }

  // Event listeners
  updateCalendarBtn.addEventListener('click', updateCalendar);
  prevMonthBtn.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    updateCalendar();
  });
  nextMonthBtn.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    updateCalendar();
  });
  addEventBtn.addEventListener('click', addEvent);

  // Initial setup
  updateYearSelector();
  updateCalendar();
});
