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
    <div>
        <div><h1 align="center">Calendrier</h1></div>
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
        <div class="container">
            <div id="calendar"></div>
        </div>
    </div>

<!--Titre de l'evenement-->
<script>
    $(document).ready(function () {
        // Initialisation du calendrier
        $('#calendar').fullCalendar({
            defaultView: 'month',
            events: [] // Liste des événements initialement vide
        });

        // Gestion de la soumission du formulaire
        $('#eventForm').on('submit', function (e) {
            e.preventDefault();

            // Récupérer les données saisies par l'utilisateur
            var title = $('#eventTitle').val();
            var start = $('#startDate').val();
            var end = $('#endDate').val();

            // Vérifier que les champs sont remplis
            if (title && start && end) {
                // Ajouter l'événement au calendrier
                $('#calendar').fullCalendar('renderEvent', {
                    title: title,
                    start: moment(start).format("YYYY-MM-DD HH:mm:ss"),
                    end: moment(end).format("YYYY-MM-DD HH:mm:ss")
                }, true);

                // Réinitialiser le formulaire
                $('#eventForm')[0].reset();
            } else {
                alert('Veuillez remplir tous les champs.');
            }
        });
    });
</script>
    <div>
        <h2>Ajouter un événement</h2><br>
        <form id="eventForm">
            <label for="eventTitle">Titre de l'événement :</label>
            <input type="text" id="eventTitle" required><br><br>

            <label for="startDate">Date de début :</label>
            <input type="datetime-local" id="startDate" required><br><br>

            <label for="endDate">Date de fin :</label>
            <input type="datetime-local" id="endDate" required><br><br>

            <button type="submit">Ajouter l'événement</button>
        </form>
        <hr>
        <div id="calendar"></div>
    </div>




</body>
</html>


