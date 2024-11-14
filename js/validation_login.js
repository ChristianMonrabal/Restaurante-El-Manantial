// Asigna la función `validaCodigoCamarero` al evento `onblur` del campo "codigo_empleado".
// Esto valida el campo cuando el usuario sale del campo (pierde el foco).
document.getElementById("codigo_empleado").onblur = validaCodigoCamarero;

// Asigna la función `validaPassword` al evento `onblur` del campo "pwd".
// Esto valida el campo cuando el usuario sale del campo de contraseña.
document.getElementById("pwd").onblur = validaPassword;

// Asigna la función `validaForm` al evento `onsubmit` del formulario "loginForm".
// Esto evita el envío del formulario si los campos no son válidos.
document.getElementById("loginForm").onsubmit = validaForm;

// Función que valida el campo del código de empleado
function validaCodigoCamarero() {
    // Obtiene el valor del campo "codigo_empleado" y el elemento de entrada correspondiente.
    let codigo_empleado = document.getElementById("codigo_empleado").value;
    let input_empleado = document.getElementById("codigo_empleado");
    let codigoError = document.getElementById("codigo_empleado_error");

    // Si el campo está vacío o es nulo, muestra un mensaje de error.
    if(codigo_empleado === "" || codigo_empleado === null){
        codigoError.textContent = "El código de empleado es obligatorio."; // Mensaje de error.
        input_empleado.classList.add("error-border"); // Añade un borde rojo al campo.
        return false; // Devuelve false, indicando que el campo es inválido.
    } 
    // Si el código de empleado tiene menos de 4 caracteres, muestra un mensaje de error.
    else if(codigo_empleado.length < 4){
        codigoError.textContent = "El código de empleado debe tener 4 caracteres."; // Mensaje de error.
        input_empleado.classList.add("error-border"); // Añade un borde rojo al campo.
        return false; // Devuelve false.
    } else {
        // Si el código es válido, borra el mensaje de error y quita el borde rojo.
        codigoError.textContent = "";
        input_empleado.classList.remove("error-border");
        return true; // Devuelve true, indicando que el campo es válido.
    }
}

// Función que valida el campo de contraseña
function validaPassword() {
    // Obtiene el valor del campo "pwd" y el elemento de entrada correspondiente.
    let pwd = document.getElementById("pwd").value;
    let input_pwd = document.getElementById("pwd");
    let pwdError = document.getElementById("pwd_error");

    // Si el campo de contraseña está vacío o es nulo, muestra un mensaje de error.
    if(pwd === "" || pwd === null){
        pwdError.textContent = "La contraseña es obligatoria."; // Mensaje de error.
        input_pwd.classList.add("error-border"); // Añade un borde rojo al campo.
        return false; // Devuelve false, indicando que el campo es inválido.
    } 
    // Si la contraseña tiene menos de 8 caracteres, muestra un mensaje de error.
    else if(pwd.length < 8){
        pwdError.textContent = "La contraseña debe tener 9 caracteres."; // Mensaje de error.
        input_pwd.classList.add("error-border"); // Añade un borde rojo al campo.
        return false; // Devuelve false.
    } 
    // Si la contraseña no contiene al menos una mayúscula, una minúscula y un número, muestra un mensaje de error.
    else if(!pwd.match(/[A-Z]/) || !pwd.match(/[a-z]/) || !pwd.match(/[0-9]/)){
        pwdError.textContent = "La contraseña debe contener al menos una letra mayúscula o minúscula y un número."; // Mensaje de error.
        input_pwd.classList.add("error-border"); // Añade un borde rojo al campo.
        return false; // Devuelve false.
    } else {
        // Si la contraseña es válida, borra el mensaje de error y quita el borde rojo.
        pwdError.textContent = "";
        input_pwd.classList.remove("error-border");
        return true; // Devuelve true, indicando que el campo es válido.
    }
}

// Función que se ejecuta cuando el formulario intenta enviarse.
function validaForm(event) {
    // Evita el envío del formulario por defecto para que se valide primero.
    event.preventDefault();

    // Si los campos de código de empleado y contraseña son válidos, se envía el formulario.
    if (validaCodigoCamarero() && validaPassword()) {
        document.getElementById("loginForm").submit(); // Envío del formulario.
    }
}