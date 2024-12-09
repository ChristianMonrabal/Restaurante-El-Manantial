function showError(input, message) {
    let errorElement = input.nextElementSibling;

    if (!errorElement || errorElement.tagName !== "P") {
        errorElement = document.createElement("p");
        errorElement.classList.add("text-danger");
        input.parentNode.appendChild(errorElement);
    }

    input.classList.add("is-invalid");
    errorElement.textContent = message;
}

function clearError(input) {
    let errorElement = input.nextElementSibling;
    if (errorElement && errorElement.tagName === "P") {
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

function validateCapacidad(input) {
    const value = input.value.trim();
    if (value === "") {
        showError(input, "La capacidad total no puede estar vacía.");
        return false;
    } else if (!Number.isInteger(parseInt(value)) || parseInt(value) < 0) {
        showError(input, "La capacidad total debe ser un número entero.");
        return false;
    }
    clearError(input);
    return true;
}

function validateImagen(input) {
    if (input.value.trim() === "") {
        clearError(input);
        return true;
    }
    return true;
}

document.addEventListener("DOMContentLoaded", () => {
    const tipoSalaInput = document.getElementById("tipo_sala");
    const nombreSalaInput = document.getElementById("nombre_sala");
    const capacidadTotalInput = document.getElementById("capacidad_total");
    const imagenSalaInput = document.getElementById("imagen_sala");

    nombreSalaInput.addEventListener("blur", function () {
        validateNotEmpty(nombreSalaInput, "El nombre de la sala no puede estar vacío.");
    });

    tipoSalaInput.addEventListener("blur", function () {
        validateNotEmpty(tipoSalaInput, "Debe seleccionar un tipo de sala.");
    });

    capacidadTotalInput.addEventListener("blur", function () {
        validateCapacidad(capacidadTotalInput);
    });

    imagenSalaInput.addEventListener("blur", function () {
        validateImagen(imagenSalaInput);
    });

    nombreSalaInput.addEventListener("focus", function () {
        clearError(nombreSalaInput);
    });

    tipoSalaInput.addEventListener("focus", function () {
        clearError(tipoSalaInput);
    });

    capacidadTotalInput.addEventListener("focus", function () {
        clearError(capacidadTotalInput);
    });

    imagenSalaInput.addEventListener("focus", function () {
        clearError(imagenSalaInput);
    });
});
