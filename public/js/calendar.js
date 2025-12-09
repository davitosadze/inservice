let currentMonth = new Date().getMonth() + 1;
let currentYear = new Date().getFullYear();
let currentView = 'week'; // 'month' or 'week' - default to week
let currentWeekStart = new Date(); // For week view

const monthNames = {
    1: 'იანვარი',
    2: 'თებერვალი',
    3: 'მარტი',
    4: 'აპრილი',
    5: 'მაისი',
    6: 'ივნისი',
    7: 'ივლისი',
    8: 'აგვისტო',
    9: 'სექტემბერი',
    10: 'ოქტომბერი',
    11: 'ნოემბერი',
    12: 'დეკემბერი'
};

// Initialize current week to start of this week
function initializeCurrentWeek() {
    const today = new Date();
    const dayOfWeek = today.getDay();
    const mondayOffset = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
    currentWeekStart = new Date(today);
    currentWeekStart.setDate(today.getDate() - mondayOffset);
}

document.addEventListener('DOMContentLoaded', function() {
    initializeCurrentWeek();
    updateViewButtons(); // Set initial button states
    updateCurrentMonthYear();
    loadCalendar();

    // View toggle buttons
    document.getElementById('monthViewBtn').addEventListener('click', function() {
        if (currentView !== 'month') {
            currentView = 'month';
            updateViewButtons();
            updateCurrentMonthYear();
            loadCalendar();
        }
    });

    document.getElementById('weekViewBtn').addEventListener('click', function() {
        if (currentView !== 'week') {
            currentView = 'week';
            updateViewButtons();
            updateCurrentMonthYear();
            loadCalendar();
        }
    });

    // Navigation controls
    document.getElementById('prevMonth').addEventListener('click', function() {
        if (currentView === 'month') {
            currentMonth--;
            if (currentMonth < 1) {
                currentMonth = 12;
                currentYear--;
            }
            updateSelects();
        } else {
            // Week view: go to previous week
            currentWeekStart.setDate(currentWeekStart.getDate() - 7);
        }
        updateCurrentMonthYear();
        loadCalendar();
    });

    document.getElementById('nextMonth').addEventListener('click', function() {
        if (currentView === 'month') {
            currentMonth++;
            if (currentMonth > 12) {
                currentMonth = 1;
                currentYear++;
            }
            updateSelects();
        } else {
            // Week view: go to next week
            currentWeekStart.setDate(currentWeekStart.getDate() + 7);
        }
        updateCurrentMonthYear();
        loadCalendar();
    });

    document.getElementById('todayBtn').addEventListener('click', function() {
        const today = new Date();
        currentMonth = today.getMonth() + 1;
        currentYear = today.getFullYear();
        initializeCurrentWeek();
        updateSelects();
        updateCurrentMonthYear();
        loadCalendar();
    });

    document.getElementById('monthSelect').addEventListener('change', function() {
        currentView = 'month';
        updateViewButtons();
        currentMonth = parseInt(this.value);
        updateCurrentMonthYear();
        loadCalendar();
    });

    document.getElementById('yearSelect').addEventListener('change', function() {
        currentView = 'month';
        updateViewButtons();
        currentYear = parseInt(this.value);
        updateCurrentMonthYear();
        loadCalendar();
    });
});

function updateCurrentMonthYear() {
    if (currentView === 'month') {
        document.getElementById('currentMonthYear').textContent = `${monthNames[currentMonth]} ${currentYear}`;
    } else {
        // Week view
        const weekStart = new Date(currentWeekStart);
        const weekEnd = new Date(currentWeekStart);
        weekEnd.setDate(weekEnd.getDate() + 6);

        if (weekStart.getMonth() === weekEnd.getMonth()) {
            document.getElementById('currentMonthYear').textContent =
                `${weekStart.getDate()}-${weekEnd.getDate()} ${monthNames[weekStart.getMonth() + 1]} ${weekStart.getFullYear()}`;
        } else {
            document.getElementById('currentMonthYear').textContent =
                `${weekStart.getDate()} ${monthNames[weekStart.getMonth() + 1]} - ${weekEnd.getDate()} ${monthNames[weekEnd.getMonth() + 1]} ${weekStart.getFullYear()}`;
        }
    }
}

function updateViewButtons() {
    const monthBtn = document.getElementById('monthViewBtn');
    const weekBtn = document.getElementById('weekViewBtn');

    if (currentView === 'month') {
        monthBtn.classList.add('active');
        weekBtn.classList.remove('active');
    } else {
        weekBtn.classList.add('active');
        monthBtn.classList.remove('active');
    }
}

function updateSelects() {
    document.getElementById('monthSelect').value = currentMonth;
    document.getElementById('yearSelect').value = currentYear;
}

function loadCalendar() {
    const baseUrl = window.location.origin;

    if (currentView === 'month') {
        fetch(`${baseUrl}/calendar/data?month=${currentMonth}&year=${currentYear}`)
            .then(response => response.json())
            .then(data => {
                generateCalendar(data);
            })
            .catch(error => {
                console.error('Error loading calendar data:', error);
            });
    } else {
        // Week view - fetch data for the entire week
        const weekStart = new Date(currentWeekStart);
        const weekEnd = new Date(currentWeekStart);
        weekEnd.setDate(weekEnd.getDate() + 6);

        // Get month and year range for the week
        const startMonth = weekStart.getMonth() + 1;
        const startYear = weekStart.getFullYear();
        const endMonth = weekEnd.getMonth() + 1;
        const endYear = weekEnd.getFullYear();

        // If week spans two months, fetch both months
        if (startMonth === endMonth && startYear === endYear) {
            fetch(`${baseUrl}/calendar/data?month=${startMonth}&year=${startYear}`)
                .then(response => response.json())
                .then(data => {
                    generateWeekCalendar(data);
                })
                .catch(error => {
                    console.error('Error loading calendar data:', error);
                });
        } else {
            // Fetch both months and merge the data
            Promise.all([
                fetch(`${baseUrl}/calendar/data?month=${startMonth}&year=${startYear}`).then(r => r.json()),
                fetch(`${baseUrl}/calendar/data?month=${endMonth}&year=${endYear}`).then(r => r.json())
            ])
                .then(([data1, data2]) => {
                    const mergedData = { ...data1, ...data2 };
                    generateWeekCalendar(mergedData);
                })
                .catch(error => {
                    console.error('Error loading calendar data:', error);
                });
        }
    }
}

function generateCalendar(eventsData) {
    const calendarGrid = document.getElementById('calendar-grid');
    const calendarHeader = document.querySelector('.calendar-header');

    calendarGrid.innerHTML = '';
    calendarGrid.className = 'calendar-grid'; // Reset to month view class
    calendarHeader.className = 'calendar-header'; // Reset header class

    const firstDay = new Date(currentYear, currentMonth - 1, 1);

    // Get first Monday of the calendar grid
    const startDate = new Date(firstDay);
    const dayOfWeek = firstDay.getDay(); // 0 = Sunday, 1 = Monday, ..., 6 = Saturday
    // Calculate how many days to go back to get to Monday
    // If Sunday (0), go back 6 days; if Monday (1), go back 0 days; if Tuesday (2), go back 1 day, etc.
    const mondayOffset = dayOfWeek === 0 ? 6 : dayOfWeek - 1;
    startDate.setDate(firstDay.getDate() - mondayOffset);

    const today = new Date();

    // Generate 6 weeks (42 days) to fill the calendar
    for (let i = 0; i < 42; i++) {
        const currentDate = new Date(startDate);
        currentDate.setDate(startDate.getDate() + i);

        const dayDiv = createDayElement(currentDate, eventsData, today, currentMonth);
        calendarGrid.appendChild(dayDiv);
    }
}

function generateWeekCalendar(eventsData) {
    const calendarGrid = document.getElementById('calendar-grid');
    const calendarHeader = document.querySelector('.calendar-header');

    calendarGrid.innerHTML = '';
    calendarGrid.className = 'calendar-grid week-view';
    calendarHeader.className = 'calendar-header week-view';

    // Find min/max hours with events across all days of the week
    let minHour = 24;
    let maxHour = 0;
    let hasEvents = false;

    for (let day = 0; day < 7; day++) {
        const currentDate = new Date(currentWeekStart);
        currentDate.setDate(currentWeekStart.getDate() + day);
        const dateKey = currentDate.toISOString().split('T')[0];

        if (eventsData[dateKey]) {
            eventsData[dateKey].forEach(event => {
                hasEvents = true;
                const eventHour = parseInt(event.time.split(':')[0]);
                minHour = Math.min(minHour, eventHour);
                maxHour = Math.max(maxHour, eventHour);
            });
        }
    }

    // Set display range with padding
    let startHour, endHour;
    if (hasEvents) {
        startHour = Math.max(0, minHour - 1);  // 1 hour before first event
        endHour = Math.min(23, maxHour + 2);   // 2 hours after last event
    } else {
        // Default business hours if no events
        startHour = 8;
        endHour = 18;
    }

    // Set dynamic grid rows based on hour count
    const hourCount = endHour - startHour + 1;
    calendarGrid.style.gridTemplateRows = `repeat(${hourCount}, minmax(120px, auto))`;

    // Create time slots and day columns for the calculated range
    for (let hour = startHour; hour <= endHour; hour++) {
        // Create time slot label
        const timeSlot = document.createElement('div');
        timeSlot.className = 'time-slot';
        timeSlot.textContent = `${hour.toString().padStart(2, '0')}:00`;
        timeSlot.dataset.hour = hour;
        calendarGrid.appendChild(timeSlot);

        // Track maximum height for this hour row
        let maxHeightForHour = 120;

        // Create cells for each day of the week
        for (let day = 0; day < 7; day++) {
            const currentDate = new Date(currentWeekStart);
            currentDate.setDate(currentWeekStart.getDate() + day);

            const cellDiv = document.createElement('div');
            cellDiv.className = 'time-slot-cell';
            cellDiv.dataset.date = currentDate.toISOString().split('T')[0];
            cellDiv.dataset.hour = hour;

            // Add events for this hour slot
            const dateKey = currentDate.toISOString().split('T')[0];
            if (eventsData[dateKey]) {
                const hourEvents = eventsData[dateKey].filter(event => {
                    const eventHour = parseInt(event.time.split(':')[0]);
                    return eventHour === hour;
                });

                // Calculate required height based on number of events
                if (hourEvents.length > 0) {
                    const eventHeight = 60; // Height per event (increased)
                    const eventGap = 6; // Gap between events
                    const padding = 10; // Top/bottom padding
                    const requiredHeight = (hourEvents.length * eventHeight) + ((hourEvents.length - 1) * eventGap) + padding;
                    const minHeight = 120; // Minimum height

                    const finalHeight = Math.max(minHeight, requiredHeight);
                    cellDiv.style.height = `${finalHeight}px`;
                    maxHeightForHour = Math.max(maxHeightForHour, finalHeight);
                }

                hourEvents.forEach((event, index) => {
                    const eventDiv = document.createElement('div');
                    eventDiv.className = `time-slot-event ${event.type}`;

                    // Create 3 lines of text
                    eventDiv.innerHTML = `
                        <div class="event-time">${event.time}</div>
                        <div class="event-title">${event.title}</div>
                        <div class="event-details">${event.purchaser} - ${event.performer}</div>
                    `;

                    eventDiv.title = `${event.title}\nმომხმარებელი: ${event.purchaser}\nშემსრულებელი: ${event.performer}\nდრო: ${event.time}`;

                    // Position multiple events in columns (vertically stacked)
                    if (hourEvents.length > 1) {
                        const eventHeight = 60; // Height for 3 lines of text (increased)
                        const topPosition = 5 + (index * (eventHeight + 6)); // Stack vertically with 6px gap

                        eventDiv.style.width = 'calc(100% - 10px)';
                        eventDiv.style.left = '5px';
                        eventDiv.style.right = 'auto';
                        eventDiv.style.top = `${topPosition}px`;
                        eventDiv.style.height = `${eventHeight}px`;
                    } else {
                        // Single event takes full width
                        eventDiv.style.left = '5px';
                        eventDiv.style.right = '5px';
                        eventDiv.style.width = 'auto';
                        eventDiv.style.top = '5px';
                        eventDiv.style.height = '60px';
                    }

                    eventDiv.addEventListener('click', function(e) {
                        e.stopPropagation();
                        showEventDetails(event);
                    });

                    cellDiv.appendChild(eventDiv);
                });
            }

            calendarGrid.appendChild(cellDiv);
        }

        // Apply the maximum height to the time slot for this hour
        if (maxHeightForHour > 120) {
            timeSlot.style.height = `${maxHeightForHour}px`;
        }
    }
}

function createDayElement(currentDate, eventsData, today, referenceMonth) {
    const dayDiv = document.createElement('div');
    dayDiv.className = 'calendar-day';

    const isCurrentMonth = currentDate.getMonth() === (referenceMonth - 1);
    const isToday = currentDate.toDateString() === today.toDateString();
    const dayOfWeekNum = currentDate.getDay();
    const isWeekend = dayOfWeekNum === 0 || dayOfWeekNum === 6;

    if (!isCurrentMonth && currentView === 'month') {
        dayDiv.classList.add('other-month');
    }

    if (isToday) {
        dayDiv.classList.add('today');
    }

    if (isWeekend) {
        dayDiv.classList.add('weekend');
    }

    const dayNumber = document.createElement('div');
    dayNumber.className = 'calendar-day-number';
    dayNumber.textContent = currentDate.getDate();

    // Add debug info as title attribute to verify alignment
    const dayNames = ['კვირა', 'ორშაბათი', 'სამშაბათი', 'ოთხშაბათი', 'ხუთშაბათი', 'პარასკევი', 'შაბათი'];
    dayNumber.title = `${dayNames[dayOfWeekNum]} - ${currentDate.toLocaleDateString()}`;

    dayDiv.appendChild(dayNumber);

    // Add events for this day
    const dateKey = currentDate.toISOString().split('T')[0];
    if (eventsData[dateKey]) {
        eventsData[dateKey].forEach(event => {
            const eventDiv = document.createElement('div');
            eventDiv.className = `calendar-event ${event.type}`;

            // Create structured event content
            const timeSpan = document.createElement('div');
            timeSpan.className = 'calendar-event-time';
            timeSpan.textContent = event.time;

            const titleSpan = document.createElement('div');
            titleSpan.className = 'calendar-event-title';
            titleSpan.textContent = event.title;

            eventDiv.appendChild(timeSpan);
            eventDiv.appendChild(titleSpan);

            // Add tooltip
            eventDiv.title = `${event.title}\nმომხმარებელი: ${event.purchaser}\nშემსრულებელი: ${event.performer}\nდრო: ${event.time}`;

            // Add click handler for more details
            eventDiv.addEventListener('click', function(e) {
                e.stopPropagation();
                showEventDetails(event);
            });

            dayDiv.appendChild(eventDiv);
        });
    }

    return dayDiv;
}

function showEventDetails(event) {
    alert(`${event.type === 'repair' ? 'რემონტი' : 'სერვისი'}\n\nშინაარსი: ${event.title}\nმომხმარებელი: ${event.purchaser}\nშემსრულებელი: ${event.performer}\nდრო: ${event.time}`);
}