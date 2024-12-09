function showError(input, message) {
    let errorElement = input.nextElementSibling;

    if (!errorElement || errorElement.tagName !== "SMALL") {
        errorElement = document.createElement("small");
        errorElement.classList.add("text-danger");
        input.parentNode.appendChild(errorElement);
    }

    input.classList.add("is-invalid");
    errorElement.textContent = message;
}

function clearError(input) {
    let errorElement = input.nextElementSibling;
    if (errorElement && errorElement.tagName === "SMALL") {
        errorElement.textContent = "";
    }
    input.classList.remove("is-invalid");
}

function validateNotEmpty(input, errorMsg) {
    if (input.value.trim() === "") {
        showError(input, errorMsg);
        return false;
    } else {
        clearError(input);
        return true;
    }
}

function validatePositiveInteger(input, errorMsg) {
    const value = input.value.trim();
    const regex = /^[1-9]\d*$/;

    if (!regex.test(value)) {
        showError(input, errorMsg);
        return false;
    } else {
        clearError(input);
        return true;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    const tipoSalaInput = document.getElementById("tipo_sala");
    const nombreSalaInput = document.getElementById("nombre_sala");
    const capacidadTotalInput = document.getElementById("capacidad_total");
    const imagenSalaInput = document.getElementById("imagen_sala");

    tipoSalaInput.addEventListener("blur", function () {
        validateNotEmpty(tipoSalaInput, "El tipo de sala no puede estar vacío.");
    });

    nombreSalaInput.addEventListener("blur", function () {
        validateNotEmpty(nombreSalaInput, "El nombre de la sala no puede estar vacío.");
    });

    capacidadTotalInput.addEventListener("blur", function () {
        validatePositiveInteger(capacidadTotalInput, "La capacidad total debe ser un número entero positivo.");
    });

    imagenSalaInput.addEventListener("blur", function () {
        validateNotEmpty(imagenSalaInput, "La URL de la imagen no puede estar vacía.");
    });

    tipoSalaInput.addEventListener("focus", function () {
        clearError(tipoSalaInput);
    });

    nombreSalaInput.addEventListener("focus", function () {
        clearError(nombreSalaInput);
    });

    capacidadTotalInput.addEventListener("focus", function () {
        clearError(capacidadTotalInput);
    });

    imagenSalaInput.addEventListener("focus", function () {
        clearError(imagenSalaInput);
    });
});
