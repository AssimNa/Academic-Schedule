<!DOCTYPE html>
<html>
<head>
    <title>Calendar</title>
    <link rel="stylesheet" href="cal.css">
    <link rel="stylesheet"  href="calen.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
</head>
<body>
<style>
    /* Modal styles */
    body{
        background-color: white;
        color: black;
    }
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0,0.5);
        justify-content: center;
        align-items: center;
    }
    #details{
        padding: 20px;
        color: black;
    }

    .modal-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        width: 300px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .modal-buttons {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
    }

    button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .close-button {
        background-color: red;
        color: white;
    }

    .submit-button {
        background-color: green;
        color: white;
    }
    /* Style pour le bouton Créer */
    .create-button {
        background-color: #f1f1f1;
        color: black;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        margin-bottom: 10px;
        position: relative;
    }

    /* Style pour le menu déroulant */
    .dropdown {
        position: relative;
        display: inline-block;
        top: 115px;
        left: 25px;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
        margin-top: 0px;
        z-index: 1000;
        width: 200px;
        padding: 10px 0;
    }

    .dropdown-item {
        padding: 10px 20px;
        display: block;
        text-decoration: none;
        color: black;
        font-size: 14px;
    }

    .dropdown-item:hover {
        background-color: #f1f1f1;
        cursor: pointer;
    }
    .form-group{
        text-align: left;
    }

</style>
<script>
    $(document).ready(function () {
        var calendar = $('#calendar').fullCalendar({
            editable: true,
            header: {
                left: 'prev,next today',
                center: 'title',
                right : 'month,agendaWeek,agendaDay'
            },
            events: "events.php?action=load",
            selectable: true,
            selectHelper: true,
            select: function (start, end, allDay) {
                const userInput = document.getElementById("userInput");
                const modal = document.getElementById("myModal");
                const deleteButton = document.getElementById("closeModal");
                const updateButton = document.getElementById("submitModal");
                const errorMessage = document.getElementById("errorMessage");


                errorMessage.textContent = "";
                userInput.value = "";
                modal.style.display = "flex";
                deleteButton.style.backgroundColor = "#3e403d";
                deleteButton.textContent = "Cancel";
                updateButton.textContent = "Add";

                var startFormatted = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                var endFormatted = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");

                updateButton.replaceWith(updateButton.cloneNode(true));
                deleteButton.replaceWith(deleteButton.cloneNode(true));

                const newUpdateButton = document.getElementById("submitModal");
                const newDeleteButton = document.getElementById("closeModal");

                newUpdateButton.addEventListener("click", function () {
                    if (userInput.value) {
                        $.ajax({
                            url: "events.php?action=insert",
                            type: "POST",
                            data: { title: userInput.value, start: startFormatted, end: endFormatted },
                            success: function () {
                                calendar.fullCalendar('refetchEvents');
                                modal.style.display = "none";
                                console.log("Added Successfully");
                            }
                        });
                    } else {
                        errorMessage.textContent = "Please fill up the input";
                    }
                });

                newDeleteButton.addEventListener("click", function () {
                    modal.style.display = "none";
                });
            },
            eventClick: function (event) {
                const errorMessage = document.getElementById("errorMessage");
                const userInput = document.getElementById("userInput");
                const modal = document.getElementById("myModal");
                const deleteButton = document.getElementById("closeModal");
                const updateButton = document.getElementById("submitModal");

                errorMessage.textContent = "";
                userInput.value = event.title;
                modal.style.display = "flex";
                deleteButton.style.backgroundColor = "red";
                deleteButton.textContent = "Delete";
                updateButton.textContent = "Update";

                var id = event.id;
                var startFormatted = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var endFormatted = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");

                updateButton.replaceWith(updateButton.cloneNode(true));
                deleteButton.replaceWith(deleteButton.cloneNode(true));

                const newUpdateButton = document.getElementById("submitModal");
                const newDeleteButton = document.getElementById("closeModal");

                newDeleteButton.addEventListener("click", function () {
                    $.ajax({
                        url: "events.php?action=delete",
                        type: "POST",
                        data: { id: id },
                        success: function () {
                            calendar.fullCalendar('refetchEvents');
                            modal.style.display = "none";
                            console.log("Event Removed");
                        }
                    });
                });

                newUpdateButton.addEventListener("click", function () {
                    if (userInput.value) {
                        $.ajax({
                            url: "events.php?action=update",
                            type: "POST",
                            data: { title: userInput.value, start: startFormatted, end: endFormatted, id: id },
                            success: function () {
                                calendar.fullCalendar('refetchEvents');
                                modal.style.display = "none";
                                console.log("Event Updated");
                            }
                        });
                    } else {
                        errorMessage.textContent = "Please fill up the input";
                    }
                });
            }
        });
    });
    document.addEventListener("DOMContentLoaded", function () {
        const createButton = document.getElementById("createButton");
        const dropdownMenu = document.getElementById("dropdownMenu");

        // Afficher ou cacher le menu au clic sur le bouton
        createButton.addEventListener("click", () => {
            dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
        });

        // Cacher le menu lorsque l'utilisateur clique en dehors
        window.addEventListener("click", (event) => {
            if (!createButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.style.display = "none";
            }
        });
    });

</script>
<!--+creer events-->
<script>
    // Event data array
    let events = [];

    // Save event
    function save_event() {
        const eventName = document.getElementById("event_name").value;
        const startDate = document.getElementById("event_start_date").value;
        const endDate = document.getElementById("event_end_date").value;

        if (eventName && startDate && endDate) {
            const newEvent = {
                id: Date.now(), // Unique ID for the event
                name: eventName,
                start: startDate,
                end: endDate,
            };

            events.push(newEvent);
            displayEvents();
            resetForm();
            alert("Event added successfully!");
        } else {
            alert("Please fill out all fields!");
        }
    }

    // Update event
    function update_event(eventId) {
        const eventName = document.getElementById("event_name").value;
        const startDate = document.getElementById("event_start_date").value;
        const endDate = document.getElementById("event_end_date").value;

        const eventIndex = events.findIndex(event => event.id === eventId);
        if (eventIndex !== -1) {
            events[eventIndex].name = eventName;
            events[eventIndex].start = startDate;
            events[eventIndex].end = endDate;

            displayEvents();
            resetForm();
            alert("Event updated successfully!");
        } else {
            alert("Event not found!");
        }
    }

    // Delete event
    function delete_event(eventId) {
        events = events.filter(event => event.id !== eventId);
        displayEvents();
        alert("Event deleted successfully!");
    }

    // Cancel operation
    function cancel_operation() {
        resetForm();
        alert("Operation canceled!");
    }

    // Display events in console or UI
    function displayEvents() {
        const eventList = document.getElementById("event-list");
        if (eventList) {
            eventList.innerHTML = ""; // Clear the list

            events.forEach(event => {
                const listItem = document.createElement("li");
                listItem.textContent = `${event.name} (${event.start} - ${event.end})`;

                // Edit button
                const editButton = document.createElement("button");
                editButton.textContent = "Edit";
                editButton.onclick = () => {
                    populateForm(event);
                };

                // Delete button
                const deleteButton = document.createElement("button");
                deleteButton.textContent = "Delete";
                deleteButton.onclick = () => {
                    delete_event(event.id);
                };

                listItem.appendChild(editButton);
                listItem.appendChild(deleteButton);
                eventList.appendChild(listItem);
            });
        }
    }

    // Populate form for editing
    function populateForm(event) {
        document.getElementById("event_name").value = event.name;
        document.getElementById("event_start_date").value = event.start;
        document.getElementById("event_end_date").value = event.end;

        const saveButton = document.querySelector(".btn-primary");
        saveButton.textContent = "Update Event";
        saveButton.onclick = () => update_event(event.id);
    }

    // Reset form fields
    function resetForm() {
        document.getElementById("event_name").value = "";
        document.getElementById("event_start_date").value = "";
        document.getElementById("event_end_date").value = "";

        const saveButton = document.querySelector(".btn-primary");
        saveButton.textContent = "Save Event";
        saveButton.onclick = save_event;
    }

    // Example: Display event list container in the UI
    document.body.innerHTML += `<ul id="event-list"></ul>`;

</script>
        <div class="dropdown">
            <button class="create-button" id="createButton">+ Créer</button>
            <div class="dropdown-menu" id="dropdownMenu">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Add New Event</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">�</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="img-container">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="event_name">Event name</label>
                                        <input type="text" name="event_name" id="event_name" class="form-control" placeholder="Enter your event name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="event_start_date">Event start</label>
                                        <input type="date" name="event_start_date" id="event_start_date" class="form-control onlydatepicker" placeholder="Event start date">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="event_end_date">Event end</label>
                                        <input type="date" name="event_end_date" id="event_end_date" class="form-control" placeholder="Event end date">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="save_event()">Save Event</button>
                    </div>
                </div>
                <a href="#" class="dropdown-item">Evénement</a>
                <a href="#" class="dropdown-item">Tache</a>
                <a href="#" class="dropdown-item">Planning des rendez-vous</a>
            </div>
        </div>

        <div class="modal" id="myModal">
        <div class="modal-content">
            <h2 id="details">Enter Details</h2>
            <input type="text" id="userInput" placeholder="Type something...">
            <h5 id="errorMessage" style="color: red"></h5>
            <div class="modal-buttons">
                <button class="close-button"  id="closeModal">Delete</button>
                <button class="submit-button" id="submitModal">Update</button>
            </div>
        </div>
    </div>
        <br />
        <h2 align="center">Calendrier</h2>
        <br />
        <div class="container">
            <div id="calendar"></div>
        </div>

</body>
</html>
