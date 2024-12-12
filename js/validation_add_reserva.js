document.addEventListener("DOMContentLoaded", function() {
    function showErrorMessage(element, message) {
        let errorElement = document.createElement("div");
        errorElement.classList.add("text-danger", "mt-2");
        errorElement.innerText = message;
        element.parentElement.appendChild(errorElement);
    }

    function clearErrorMessage(element) {
        const errorElement = element.parentElement.querySelector(".text-danger");
        if (errorElement) {
            errorElement.remove();
        }
    }

    document.getElementById("nombre_reserva").addEventListener("blur", function() {
        const nombreReserva = document.getElementById("nombre_reserva");
        clearErrorMessage(nombreReserva);
        if (nombreReserva.value === "") {
            showErrorMessage(nombreReserva, "El nombre de la reserva no puede estar vacío.");
        }
    });

    document.getElementById("cantidad_personas").addEventListener("blur", function() {
        const cantidadPersonas = document.getElementById("cantidad_personas");
        clearErrorMessage(cantidadPersonas);
        if (cantidadPersonas.value === "") {
            showErrorMessage(cantidadPersonas, "La cantidad de personas es obligatoria.");
        }
    });

    document.getElementById("id_sala").addEventListener("blur", function() {
        const sala = document.getElementById("id_sala");
        clearErrorMessage(sala);
        if (sala.value === "") {
            showErrorMessage(sala, "Debes seleccionar una sala.");
        }
    });

    document.getElementById("fecha_reserva").addEventListener("blur", function() {
        const fechaReserva = document.getElementById("fecha_reserva");
        clearErrorMessage(fechaReserva);
        if (fechaReserva.value === "") {
            showErrorMessage(fechaReserva, "La fecha de reserva es obligatoria.");
        }
    });

    document.getElementById("id_franja").addEventListener("blur", function() {
        const franja = document.getElementById("id_franja");
        clearErrorMessage(franja);
        if (franja.value === "") {
            showErrorMessage(franja, "Debes seleccionar una franja horaria.");
        }
    });
});
