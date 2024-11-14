// Asigna la función `validaForm` al evento `onsubmit` del formulario con ID 'filterForm'.
// Esto evita que el formulario se envíe directamente y ejecuta la validación primero.
document.getElementById("filterForm").onsubmit = validaForm;

// Función que se ejecuta cuando el formulario intenta enviarse.
function validaForm(event) {
    // Evita el envío del formulario por defecto para que podamos validar primero.
    event.preventDefault();

    // Obtiene los valores de los campos del formulario.
    let camarero = document.getElementById("camarero").value;
    let mesa = document.getElementById("mesa").value;
    let fecha = document.getElementById("fecha").value;
    let sala = document.getElementById("sala").value;

    // Comprueba si todos los campos están vacíos.
    if (camarero === "" && mesa === "" && fecha === "" && sala === "") {
        // Si todos los campos están vacíos, muestra un mensaje de error.
        let formError = document.getElementById("form_error");
        formError.textContent = "Debe completar al menos un campo."; // Mensaje que se mostrará.
        formError.style.display = "block"; // Muestra el mensaje de error.
        return false; // Detiene el envío del formulario.
    }

    // Si al menos un campo tiene valor, se borra el mensaje de error (si existía).
    let formError = document.getElementById("form_error");
    formError.textContent = "";
    formError.style.display = "none"; // Oculta el mensaje de error.

    // Llama a la función `validaCampo` para validar cada uno de los campos.
    let validCamarero = validaCampo("camarero");
    let validMesa = validaCampo("mesa");
    let validFecha = validaCampo("fecha");
    let validSala = validaCampo("sala");

    // Si al menos uno de los campos es válido, envía el formulario.
    if (validCamarero || validMesa || validFecha || validSala) {
        document.getElementById("filterForm").submit(); // Envía el formulario.
    }
}

// Función que valida un campo específico.
function validaCampo(campoId) {
    // Obtiene el valor del campo y el elemento de entrada correspondiente.
    let valor = document.getElementById(campoId).value;
    let input = document.getElementById(campoId);

    // Si el campo está vacío, le añade una clase 'error-border' para marcarlo visualmente.
    if (valor === "") {
        input.classList.add("error-border"); // Agrega el borde rojo al campo.
        return false; // Retorna false, indicando que el campo no es válido.
    } else {
        // Si el campo no está vacío, elimina la clase 'error-border'.
        input.classList.remove("error-border"); // Elimina el borde rojo.
        return true; // Retorna true, indicando que el campo es válido.
    }
}