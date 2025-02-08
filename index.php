<?php
session_start();

include 'db.php';
if (!isset($_SESSION['role']) || !isset($_SESSION['id'])) {
    // Redirect to login.php if not logged in
    header("Location: login.php");
    exit(); // Ensure the script stops executing after the redirect
}
$role = $_SESSION['role']; // Admin or Teacher
$user_id = $_SESSION['id'];
?>


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
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(90deg, #1e3c72, #2a5298, #1c1c1c, #0d3b66);
            background-size: 400% 400%;
            animation: gradientBackground 15s ease infinite;
        }
        @keyframes gradientBackground {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {
                background-position: 0% 50%;
            }
        }
        #calendar {
            background-color: rgba(255, 255, 255, 0.9); /* Fond blanc semi-transparent */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }
        #calendar-container {
            position: relative;
            margin: 50px auto;
            max-width: 80%;
        }

    </style>
    <style>
        /* Modal styles */

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
    </style>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(90deg, #1e3c72, #2a5298, #1c1c1c, #0d3b66);
            background-size: 400% 400%;
            animation: gradientBackground 15s ease infinite;
            font-family: Arial, sans-serif;
            color: blue;
        }

        @keyframes gradientBackground {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #ffffff;
            font-size: 2.5em;
        }

        #addEventButton {
            display: inline-block;
            margin: 20px;
            padding: 10px 20px;
            background-color: #1e3c72;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }

        #addEventButton:hover {
            background-color: #2a5298;
        }

        #calendar-container {
            margin: 40px auto;
            max-width: 80%;
        }

        #calendar {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }
    </style>

    <h1>Calendrier</h1>
    <a id="addEventButton"  href="logout.php" onclick="logout()">Logout</a>

    <script>
        $(document).ready(function () {
            // Charger les événements depuis le Local Storage
            const events = JSON.parse(localStorage.getItem('calendarEvents')) || [];

            // Initialisation du calendrier FullCalendar
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                events: events // Charger les événements
            });
        });

        // add,cancel,delete,update

        eventClick: function (event) {
            const modal = document.getElementById("myModal");
            const userInput = document.getElementById("userInput");
            const errorMessage = document.getElementById("errorMessage");
            const deleteButton = document.getElementById("closeModal");
            const updateButton = document.getElementById("submitModal");

            // Initialiser le modal
            errorMessage.textContent = "";
            userInput.value = event.title;
            modal.style.display = "flex";


            const formattedDate = "2025-02-04 09:29:00";

// Reverse the formatted date string to match the datetime-local format 'YYYY-MM-DDTHH:MM'
const dateForInput = formattedDate.replace(" ", "T").substring(0, 16);

// Assuming you have an input of type datetime-local with id="myDatetimeInput"
document.getElementById('myDatetimeInput').value = dateForInput;


            // Configurer le bouton de suppression
            deleteButton.textContent = "Supprimer";
            deleteButton.style.backgroundColor = "red";
            deleteButton.onclick = function () {
                const events = JSON.parse(localStorage.getItem('calendarEvents')) || [];
                const updatedEvents = events.filter(e => e.start !== event.start.format() || e.end !== event.end.format());
                localStorage.setItem('calendarEvents', JSON.stringify(updatedEvents));
                $('#calendar').fullCalendar('removeEvents', event._id); // Supprimer l'événement visuellement
                modal.style.display = "none";
            };

            // Configurer le bouton de mise à jour
            updateButton.textContent = "Mettre à jour";
            updateButton.style.backgroundColor = "green";
            updateButton.onclick = function () {
                if (userInput.value) {
                    const events = JSON.parse(localStorage.getItem('calendarEvents')) || [];
                    const updatedEvents = events.map(e => {
                        if (e.start === event.start.format() && e.end === event.end.format()) {
                            return { ...e, title: userInput.value }; // Met à jour le titre
                        }
                        return e;
                    });
                    localStorage.setItem('calendarEvents', JSON.stringify(updatedEvents));
                    event.title = userInput.value; // Met à jour visuellement
                    $('#calendar').fullCalendar('updateEvent', event);
                    modal.style.display = "none";
                } else {
                    errorMessage.textContent = "Le titre ne peut pas être vide !";
                }
            };
        }

    </script>
    <style>
        /* Animation de fond */
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(90deg, #1e3c72, #2a5298, #1c1c1c, #0d3b66);
            background-size: 400% 400%;
            animation: gradientBackground 15s ease infinite;
            font-family: Arial, sans-serif;
            color: blue;
        }

        @keyframes gradientBackground {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h1 {
            text-align: center;
            margin-top: 20px;
            color: #ffffff;
            font-size: 2.5em;
        }

        /* Conteneur du calendrier */
        #calendar-container {
            margin: 40px auto;
            max-width: 80%;
        }

        #calendar {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        }

        /* Boutons FullCalendar */
        .fc-button {
            background-color: #fff3f3 !important;
            color: #1e3c72 !important;
            border: none !important;
            border-radius: 5px !important;
            padding: 5px 10px !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .fc-button:hover {
            background-color: #2a5298 !important;
        }

        .fc-button-today {
            background-color: #2a5298 !important;
        }

        .fc-title {
            color: #1c1c1c;
        }

        /* Formulaire d'ajout d'événement */
        .evenement {
            margin: 20px auto;
            max-width: 600px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .evenement h2 {
            text-align: center;
            color: #1e3c72;
        }

        .evenement label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #1e3c72;
        }

        .evenement input, .evenement button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        .evenement button {
            background-color: #1e3c72;
            color: white;
            cursor: pointer;
        }

        .evenement button:hover {
            background-color: #2a5298;
        }
    </style>

    <script>
$(document).ready(function () {
    var calendar = $('#calendar').fullCalendar({
        editable: true,
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
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

            // Reset buttons to ensure proper handling
            updateButton.replaceWith(updateButton.cloneNode(true));
            deleteButton.replaceWith(deleteButton.cloneNode(true));

            const newUpdateButton = document.getElementById("submitModal");
            const newDeleteButton = document.getElementById("closeModal");

            newUpdateButton.addEventListener("click", function () {
                const dateObj = new Date(document.getElementById("startDate").value);

                // Format the start date into 'YYYY-MM-DD HH:MM:SS'
                const newDebut = dateObj.getFullYear() + '-' 
                    + String(dateObj.getMonth() + 1).padStart(2, '0') + '-'
                    + String(dateObj.getDate()).padStart(2, '0') + ' '
                    + String(dateObj.getHours()).padStart(2, '0') + ':'
                    + String(dateObj.getMinutes()).padStart(2, '0') + ':'
                    + String(dateObj.getSeconds()).padStart(2, '0');

                const dateObjend = new Date(document.getElementById("endDate").value);

                // Format the end date into 'YYYY-MM-DD HH:MM:SS'
                const newFin = dateObjend.getFullYear() + '-' 
                    + String(dateObjend.getMonth() + 1).padStart(2, '0') + '-'
                    + String(dateObjend.getDate()).padStart(2, '0') + ' '
                    + String(dateObjend.getHours()).padStart(2, '0') + ':'
                    + String(dateObjend.getMinutes()).padStart(2, '0') + ':'
                    + String(dateObjend.getSeconds()).padStart(2, '0');

                if (userInput.value) {
                    console.log({ title: userInput.value, start: newDebut, end: newFin });

                    const role = '<?php echo $role; ?>'; // 'admin' or 'teacher'
                    let affected_to;
                    if (role === 'admin') {
                        affected_to = parseInt(document.getElementById("teacherSelect").value);
                    } else {
                        // Use current user's ID if not admin
                        affected_to = '<?php echo $user_id; ?>';
                    }

                    $.ajax({
                        url: "events.php?action=insert",
                        type: "POST",
                        data: { title: userInput.value, start: newDebut, end: newFin, affected_to: affected_to },
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
            console.log("Event Data: ", event);
    const errorMessage = document.getElementById("errorMessage");
    const userInput = document.getElementById("userInput");
    const modal = document.getElementById("myModal");
    const deleteButton = document.getElementById("closeModal");
    const updateButton = document.getElementById("submitModal");
    
    errorMessage.textContent = "";
    userInput.value = event.title.split("--")[0];
    document.getElementById("teacherSelect").value = event.affected_to;
    modal.style.display = "flex";

    deleteButton.style.backgroundColor = "red";
    deleteButton.textContent = "Delete";
    updateButton.textContent = "Update";

    // Ensure that the end date is not null
    var startFormatted = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
    var endFormatted = event.end ? $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss") : null;  // Handle null end date

    console.log("Event Start:", startFormatted);  // Debug start date
    console.log("Event End:", endFormatted);  // Debug end date

// Convert to a Date object
var dynamicDate = new Date(endFormatted.replace(" ", "T")); // Replace space with "T" for valid ISO format

document.getElementById('endDate').value = dynamicDate.toISOString().slice(0, 19);



var dynamicDate = new Date(startFormatted.replace(" ", "T")); // Replace space with "T" for valid ISO format

document.getElementById('startDate').value = dynamicDate.toISOString().slice(0, 19);

    updateButton.replaceWith(updateButton.cloneNode(true));
    deleteButton.replaceWith(deleteButton.cloneNode(true));

    const newUpdateButton = document.getElementById("submitModal");
    const newDeleteButton = document.getElementById("closeModal");

    newDeleteButton.addEventListener("click", function () {
        $.ajax({
            url: "events.php?action=delete",
            type: "POST",
            data: { id: event.id },
            success: function () {
                calendar.fullCalendar('refetchEvents');
                modal.style.display = "none";
                console.log("Event Removed");
            }
        });
    });

    newUpdateButton.addEventListener("click", function () {
        if (userInput.value) {
            const role = '<?php echo $role; ?>'; // 'admin' or 'teacher'
                    let affected_to;
                    if (role === 'admin') {
                        affected_to = parseInt(document.getElementById("teacherSelect").value);
                    } else {
                        // Use current user's ID if not admin
                        affected_to = '<?php echo $user_id; ?>';
                    }

            $.ajax({
                url: "events.php?action=update",
                type: "POST",
                data: { title: userInput.value, start: startFormatted, end: endFormatted, id: event.id, affected_to:affected_to },
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
        <div>
<!--                <div><h1 align="center">Calendrier</h1></div>-->
<div class="modal" id="myModal">
    <div class="modal-content">
        <h2 id="details">Enter Details</h2>
        
        <!-- Combobox for teachers -->
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>        
        <label for="teacherSelect">Select Teacher:</label>
        <select id="teacherSelect">
            <option value="">-- Select Teacher --</option>
            <?php
            // Query to fetch all users with the 'user' role
            $stmt = $conn->prepare("SELECT id,name FROM user WHERE role = 'user'");
            $stmt->execute();
            $result = $stmt->get_result();

            // Loop through the users and display them in the select options
            while ($user = $result->fetch_assoc()) {
                echo '<option value="' . htmlspecialchars($user['id']) . '">' . htmlspecialchars($user['name']) . '</option>';
            }
            ?>
        </select>
    <?php endif; ?>
        <br>
        <label for="userInput">Titre de l'événement :</label>
    <input type="text" id="userInput" ><br>

    <label for="startDate">Date de début :</label>
    <input type="datetime-local" id="startDate" ><br>

    <label for="endDate">Date de fin :</label>
    <input type="datetime-local" id="endDate" ><br>
        <h5 id="errorMessage" style="color: red"></h5>
        
        <div class="modal-buttons">
            <button class="close-button" id="closeModal">Delete</button>
            <button class="submit-button" id="submitModal">Update</button>
        </div>
    </div>
</div>

                <br />
                <div class="container">
                    <div id="calendar"></div>
                </div>
            </div>

</body>
</html>



