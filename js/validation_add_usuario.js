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

// Validar si un campo está vacío
function validateNotEmpty(input, errorMsg) {
    if (input.value.trim() === "") {
        showError(input, errorMsg);
        return false;
    } else {
        clearError(input);
        return true;
    }
}

// Validar correo electrónico con dominio específico (@elmanantial.com)
function validateEmail(input) {
    const email = input.value.trim();
    const regex = /^[a-zA-Z0-9._%+-]+@elmanantial\.com$/;

    if (!email) {
        showError(input, "El correo electrónico no puede estar vacío.");
        return false;
    }

    if (!regex.test(email)) {
        showError(input, "El correo debe ser de dominio @elmanantial.com.");
        return false;
    }

    clearError(input);
    return true;
}

// Validar contraseña con prioridad a que no esté vacía
function validatePassword(input) {
    if (input.value.trim() === "") {
        showError(input, "La contraseña no puede estar vacía.");
        return false;
    }

    const regex = /^(?=.*\d).{6,}$/; // Mínimo 6 caracteres y al menos un número
    if (!regex.test(input.value)) {
        showError(
            input,
            "La contraseña debe tener al menos 6 caracteres y contener un número."
        );
        return false;
    }

    clearError(input);
    return true;
}

// Agregar eventos `onblur` a los campos
document.addEventListener("DOMContentLoaded", () => {
    const nombreUsuario = document.getElementById("nombre_usuario");
    const emailUsuario = document.getElementById("email_usuario");
    const tipoUsuario = document.getElementById("tipo_usuario");
    const passwordUsuario = document.getElementById("password_usuario");

    nombreUsuario.addEventListener("blur", () =>
        validateNotEmpty(nombreUsuario, "El nombre de usuario no puede estar vacío.")
    );

    emailUsuario.addEventListener("blur", () =>
        validateEmail(emailUsuario) // Cambiar para usar la validación del correo
    );

    tipoUsuario.addEventListener("blur", () =>
        validateNotEmpty(tipoUsuario, "Debe seleccionar un tipo de usuario.")
    );

    passwordUsuario.addEventListener("blur", () => validatePassword(passwordUsuario));
});
