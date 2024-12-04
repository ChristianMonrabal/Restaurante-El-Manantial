document.getElementById("nombre_usuario").onblur = validaUsuario;
document.getElementById("pwd").onblur = validaPassword;
document.getElementById("loginForm").onsubmit = validaForm;

function validaUsuario() {
    let nombre_usuario = document.getElementById("nombre_usuario").value;
    let inputUsuario = document.getElementById("nombre_usuario");
    let errorUsuario = document.getElementById("nombre_usuario_error");

    if (nombre_usuario === "" || nombre_usuario === null) {
        errorUsuario.textContent = "El nombre de usuario es obligatorio.";
        inputUsuario.classList.add("error-border");
        return false;
    } else {
        errorUsuario.textContent = "";
        inputUsuario.classList.remove("error-border");
        return true;
    }
}

function validaPassword() {
    let pwd = document.getElementById("pwd").value;
    let inputPwd = document.getElementById("pwd");
    let errorPwd = document.getElementById("pwd_error");

    if (pwd === "" || pwd === null) {
        errorPwd.textContent = "La contraseña es obligatoria.";
        inputPwd.classList.add("error-border");
        return false;
    } else {
        errorPwd.textContent = "";
        inputPwd.classList.remove("error-border");
        return true;
    }
}

function validaForm(event) {
    event.preventDefault();
    if (validaUsuario() && validaPassword()) {
        document.getElementById("loginForm").submit();
    }
}
