src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"
src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"
src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"


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
    deleteButton.style.backgroundColor = "#403d3d";
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



