const calendar = document.querySelector(".calendar"),
    date = document.querySelector(".date"),
    daysContainer = document.querySelector(".days"),
    prev = document.querySelector(".prev"),
    next = document.querySelector(".next"),
    todayBtn = document.querySelector(".today-btn"),
    gotoBtn = document.querySelector(".goto-btn"),
    dateInput = document.querySelector(".date-input"),
    eventDay =document.querySelector(".event-day"),
    eventDate = document.querySelector(".event-date"),
    eventsContainer = document.querySelector(".events"),
    addEventSubmit = document.querySelector(".add-event-btn");

let today = new Date();
let activeDay;
let month = today.getMonth();
let year = today.getFullYear();

const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
];

// Default events array
/*const eventsArr = [
    {
        day: 14,
        month: 2,
        year: 2024,
        events: [
            {
                title: "Event 1 design login page",
                time: "10:00 AM",
            },
            {
                title: "Event 2",
                time: "11:00 AM",
            },
        ],
    },
    {
        day: 19,
        month: 2,
        year: 2024,
        events: [
            {
                title: "Event 1 design Homepage",
                time: "10:00 AM",
            },
        ],
    },
];*/
let eventsArr = [];
getEvents();
// Function to add days
function initCalendar() {
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const prevLastDay = new Date(year, month, 0);
    const prevDays = prevLastDay.getDate();
    const lastDate = lastDay.getDate();
    const day = firstDay.getDay();
    const nextDays = 7 - lastDay.getDay() - 1;

    date.innerHTML = months[month] + " " + year;

    let days = "";

    // Always display 35 days for the current month
    for (let i = 1; i <= 35; i++) {
        // Calculate the day to display based on the loop index
        const displayDay = i - day;

        if (displayDay < 1) {
            // Display previous month's days
            days += '<div class="day prev-date" data-day="' + (prevDays - day + i) + '">' + (prevDays - day + i) + '</div>';
        } else if (displayDay > lastDate) {
            // Display next month's days
            days += '<div class="day next-date" data-day="' + (displayDay - lastDate) + '">' + (displayDay - lastDate) + '</div>';
        } else {
            // Check if event is present on the current day
            let event = false;
            eventsArr.forEach((eventObj) => {
                if (eventObj.day === displayDay && eventObj.month === month + 1 && eventObj.year === year) {
                    event = true;
                }
            });

            // Display current month's days
            if (displayDay === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {

                activeDay = displayDay;
                getActiveDay(displayDay);
                updateEvents(displayDay);

                // If event found, also add the event class

                if (event) {
                    days += '<div class="day today active event" data-day="' + displayDay + '">' + displayDay + '</div>';
                } else {
                    days += '<div class="day today" data-day="' + displayDay + '">' + displayDay + '</div>';
                }
            } else {
                if (event) {
                    days += '<div class="day event" data-day="' + displayDay + '">' + displayDay + '</div>';
                } else {
                    days += '<div class="day" data-day="' + displayDay + '">' + displayDay + '</div>';
                }
            }
        }
    }

    daysContainer.innerHTML = days;
    // Add listener after calendar initialized
    addListener();
}

initCalendar();

// Prev month
function prevMonth() {
    month--;
    if (month < 0) {
        month = 11;
        year--;
    }
    initCalendar();
}

// Next month
function nextMonth() {
    month++;
    if (month > 11) {
        month = 0;
        year++;
    }
    initCalendar();
}

// Add event listeners on prev and next buttons
prev.addEventListener("click", prevMonth);
next.addEventListener("click", nextMonth);

// Today button functionality
todayBtn.addEventListener("click", () => {
    today = new Date();
    month = today.getMonth();
    year = today.getFullYear();
    initCalendar();
});

// Date input functionality
dateInput.addEventListener("input", (e) => {
    dateInput.value = dateInput.value.replace(/[^0-9/]/g, "");
    if (dateInput.value.length === 2) {
        dateInput.value += "/";
    }
    if (dateInput.value.length > 7) {
        dateInput.value = dateInput.value.slice(0, 7);
    }
    if (e.inputType === "deleteContentBackward" && dateInput.value.length === 3) {
        dateInput.value = dateInput.value.slice(0, 2);
    }
});

// Goto button functionality
gotoBtn.addEventListener("click", gotoDate);

function gotoDate() {
    const dateArr = dateInput.value.split("/");
    if (dateArr.length === 2) {
        if (dateArr[0] > 0 && dateArr[0] < 13 && dateArr[1].length === 4) {
            month = dateArr[0] - 1;
            year = dateArr[1];
            initCalendar();
            return;
        }
    }
    alert("Invalid date");
}

// Add event
const addEventBtn = document.querySelector(".add-event"),
    addEventContainer = document.querySelector(".add-event-wrapper"),
    addEventCloseBtn = document.querySelector(".close");
addEventTitle = document.querySelector(".event-name");
addEventFrom = document.querySelector(".event-time-from");
addEventTo = document.querySelector(".event-time-to");

addEventBtn.addEventListener("click", () => {
    addEventContainer.classList.toggle("active");
});
addEventCloseBtn.addEventListener("click", () => {
    addEventContainer.classList.remove("active");
});
document.addEventListener("click", (e) => {
    if (e.target !== addEventBtn && !addEventContainer.contains(e.target)) {
        addEventContainer.classList.remove("active");
    }
});

// Allow only 50 chars in title
addEventTitle.addEventListener("input", (e) => {
    addEventTitle.value = addEventTitle.value.slice(0, 50);
});

// Time format in from and to time
addEventFrom.addEventListener("input", (e) => {
    // Remove anything else numbers
    addEventFrom.value = addEventFrom.value.replace(/[^0-9:]/g, "");
    // If two numbers entered, auto add colon
    if (addEventFrom.value.length === 2) {
        addEventFrom.value += ":";
    }
    // Don't let the user enter more than 5 characters
    if (addEventFrom.value.length > 5) {
        addEventFrom.value = addEventFrom.value.slice(0, 5);
    }
});

// Same with to time
addEventTo.addEventListener("input", (e) => {
    // Remove anything else numbers
    addEventTo.value = addEventTo.value.replace(/[^0-9:]/g, "");
    // If two numbers entered, auto add colon
    if (addEventTo.value.length === 2) {
        addEventTo.value += ":";
    }
    // Don't let the user enter more than 5 characters
    if (addEventTo.value.length > 5) {
        addEventTo.value = addEventTo.value.slice(0, 5);
    }
});

// Let's create a function to add a listener on days after rendered
function addListener() {
    const days = document.querySelectorAll(".day");
    days.forEach((day) => {
        day.addEventListener("click", (e) => {
            // Set the current day as the active day
            activeDay = Number(e.target.dataset.day);

            //call active day after click
            getActiveDay(e.target.innerHTML);
            updateEvents(Number(e.target.innerHTML));

            // Remove active class from already active day
            days.forEach((d) => {
                d.classList.remove("active");
            });
            // If the previous month day is clicked, go to the previous month and add the active class
            if (e.target.classList.contains("prev-date")) {
                prevMonth();
                setTimeout(() => {
                    // Select all days of that month
                    const days = document.querySelectorAll(".day");
                    // After going to the previous month, add the active class to the clicked day
                    days.forEach((d) => {
                        if (!d.classList.contains("prev-date") && Number(d.dataset.day) === activeDay) {
                            d.classList.add("active");
                        }
                    });
                }, 100);
            } else if (e.target.classList.contains("next-date")) {
                // If the next month day is clicked, go to the next month and add the active class
                nextMonth();
                setTimeout(() => {
                    // Select all days of that month
                    const days = document.querySelectorAll(".day");
                    // After going to the next month, add the active class to the clicked day
                    days.forEach((d) => {
                        if (!d.classList.contains("next-date") && Number(d.dataset.day) === activeDay) {
                            d.classList.add("active");
                        }
                    });
                }, 100);
            } else {
                // Add the active class to the clicked day
                e.target.classList.add("active");
            }
        });
    });
}

//lets show active day events and date at top

function getActiveDay(date){
    const day = new Date(year,month,date);
    const dayName = day.toString().split(" ")[0];
    eventDay.innerHTML = dayName;
    eventDate.innerHTML = date + "" +months[month]+""+year;
}

//function to show events of that day
function updateEvents(date){
    let events="";
    eventsArr.forEach((event) =>{
        //get events of active day only
        if(date === event.day && month +1===event.month && year === event.year){

            //then show event
            // Then show event
            event.events.forEach((event) => {
                events += `<div class="event">
                  <div class="title">
                      <i class="fas fa-circle"></i>
                      <h3 class="event-title">${event.title}</h3>
                  </div>
                  <div class="event-time">
                      <span class="event-time">${event.time}</span>
                  </div>
               </div>`;
            });
        }
    });

    // if nothing found
    if (events === "") {
        events = `<div class="no-event">
                <h3>No Events</h3>
              </div>`;
    }

    eventsContainer.innerHTML = events;
    //save events when update event called
    saveEvents();
}

// lets create function to add events
addEventSubmit.addEventListener("click", ()=>{
    const eventTitle = addEventTitle.value;
    const eventTimeFrom = addEventFrom.value;
    const  eventTimeTo = addEventTo.value;

    //some validations
    if(eventTitle === "" || eventTimeFrom ==="" || eventTimeTo ===""){
        alert("Please fill all the fields");
    }

    const timeFromArr = eventTimeFrom.split(":");
    const timeToArr = eventTimeTo.split(":");

    if(timeFromArr.length !== 2 || timeToArr.length !== 2 || timeFromArr[0] >23 || timeFromArr[1]>59 || timeToArr[0] >23||timeToArr[1]>59){
        alert("Invalid Time Format");
    }

    const timeFrom = convertTime(eventTimeFrom);
    const timeTo =convertTime(eventTimeTo);

    const newEvent = {
        title: eventTitle,
        time: timeFrom+"-"+timeTo,
    };

    let eventAdded = false;

    //check if eventsarr not empty
    if(eventsArr.length >0){
        eventsArr.forEach((item) =>{
            if(item.day === activeDay && item.month === month+1 && item.year === year){
                item.events.push(newEvent);
                eventAdded= true;
            }
        });
    }

    if(!eventAdded){
        eventsArr.push({
            day:activeDay,
            month: month+1,
            year: year,
            events: [newEvent],
        });
    }

    addEventContainer.classList.remove("active");
    addEventTitle.value="";
    addEventFrom.value ="";
    addEventTo.value="";
    updateEvents(activeDay);

    const activeDayElem = document.querySelector(".day.active");
    if(!activeDayElem.classList.contains("event")){
        activeDayElem.classList.add("event");
    }


});

function convertTime(time){
    let timeArr = time.split(":");
    let timeHour = timeArr[0];
    let timeMin = timeArr[1];
    let timeFormat = timeHour >= 12 ? "PM":"AM";
    timeHour = timeHour %12 ||12;
    time = timeHour + ":" +timeMin +" "+timeFormat;
    return time;
}

//lets create a function to remove events on click
eventsContainer.addEventListener("click", (e) => {
    if (e.target.classList.contains("event")) {
        const eventTitle = e.target.querySelector(".event-title").innerHTML;

        // Show confirmation dialog
        const isConfirmed = confirm(`Do you want to delete this event: ${eventTitle}?`);

        if (isConfirmed) {
            // Remove the event
            eventsArr.forEach((event) => {
                if (event.day === activeDay && event.month === month + 1 && event.year === year) {
                    event.events.forEach((item, index) => {
                        if (item.title === eventTitle) {
                            event.events.splice(index, 1);
                        }
                    });

                    if (event.events.length === 0) {
                        eventsArr.splice(eventsArr.indexOf(event), 1);

                        const activeDayElem = document.querySelector(".day.active");
                        if (activeDayElem.classList.contains("event")) {
                            activeDayElem.classList.remove("event");
                        }
                    }
                }
            });

            // Update the events display
            updateEvents(activeDay);
        }
    }
});

function saveEvents(){
    localStorage.setItem("events",JSON.stringify(eventsArr));
}
function getEvents() {
    if (localStorage.getItem("events") !== null) {
        eventsArr.push(...JSON.parse(localStorage.getItem("events")));
    }
}
