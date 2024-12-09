function toggleTables() {
    var mesasTable = document.getElementById("mesasTable");
    var salasTable = document.getElementById("salasTable");
    var toggleButton = document.getElementById("toggleButton");

    if (mesasTable.style.display === "none") {
        mesasTable.style.display = "block";
        salasTable.style.display = "none";
        toggleButton.innerHTML = "Ver Salas";
    } else {
        mesasTable.style.display = "none";
        salasTable.style.display = "block";
        toggleButton.innerHTML = "Ver Mesas";
    }
}