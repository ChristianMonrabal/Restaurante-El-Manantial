// Función para mostrar mensajes de error
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

// Función para eliminar mensajes de error
function clearError(input) {
    let errorElement = input.nextElementSibling;
    if (errorElement && errorElement.tagName === "SMALL") {
        errorElement.textContent = "";
    }
    input.classList.remove("is-invalid");
}

// Validar nombre de usuario (no vacío)
function validateNotEmpty(input, errorMsg) {
    if (input.value.trim() === "") {
        showError(input, errorMsg);
        return false;
    } else {
        clearError(input);
        return true;
    }
}

// Validar correo electrónico (con dominio específico)
function validateEmail(input) {
    const email = input.value.trim();
    if (email === "") {
        showError(input, "El correo electrónico no puede estar vacío.");
        return false;
    }

    const regex = /^[a-zA-Z0-9._%+-]+@elmanantial\.com$/; // Valida el dominio @elmanantial.com
    if (!regex.test(email)) {
        showError(input, "El correo debe ser del dominio @elmanantial.com");
        return false;
    }

    clearError(input);
    return true;
}

// Validar contraseña (opcional, pero si se ingresa debe cumplir los requisitos)
function validatePassword(input) {
    // Si no hay valor en el campo, no se valida nada
    if (input.value.trim() === "") {
        clearError(input);
        return true; // No hay error si la contraseña es opcional
    }

    const regex = /^(?=.*\d).{6,}$/; // Mínimo 6 caracteres y al menos un número
    if (!regex.test(input.value)) {
        showError(input, "La contraseña debe tener al menos 6 caracteres y contener un número.");
        return false;
    }

    clearError(input);
    return true;
}

// Validar tipo de usuario (no vacío)
function validateSelect(input, errorMsg) {
    if (input.value.trim() === "") {
        showError(input, errorMsg);
        return false;
    } else {
        clearError(input);
        return true;
    }
}

// Agregar eventos `onblur` y `onfocus` para validación
document.addEventListener("DOMContentLoaded", () => {
    const nombreInput = document.getElementById("nombre_usuario");
    const emailInput = document.getElementById("email_usuario");
    const tipoInput = document.getElementById("tipo_usuario");
    const passwordInput = document.getElementById("password_usuario");

    // Validar los campos cuando el foco se pierde
    nombreInput.addEventListener("blur", function () {
        validateNotEmpty(nombreInput, "El nombre de usuario no puede estar vacío.");
    });

    emailInput.addEventListener("blur", function () {
        validateEmail(emailInput);
    });

    tipoInput.addEventListener("blur", function () {
        validateSelect(tipoInput, "Debe seleccionar un tipo de usuario.");
    });

    passwordInput.addEventListener("blur", function () {
        validatePassword(passwordInput);
    });

    // Limpiar los mensajes de error cuando el campo es enfocado nuevamente
    nombreInput.addEventListener("focus", function () {
        clearError(nombreInput);
    });

    emailInput.addEventListener("focus", function () {
        clearError(emailInput);
    });

    tipoInput.addEventListener("focus", function () {
        clearError(tipoInput);
    });

    passwordInput.addEventListener("focus", function () {
        clearError(passwordInput);
    });
});
