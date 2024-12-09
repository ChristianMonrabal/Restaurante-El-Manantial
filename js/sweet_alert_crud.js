// Función general para mostrar SweetAlert con redirección a la página anterior
function showSweetAlert(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        confirmButtonText: 'Volver'
    }).then(() => {
        window.history.back();
    });
}

// Función para confirmar la eliminación de un elemento
function confirmarEliminar(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esta acción",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../private/delete_usuario.php?id=${id}`;
        } else {
            window.history.back();
        }
    });
}

// Función para mostrar SweetAlert de éxito y volver a la página anterior
function showSuccessAlert(title, text) {
    Swal.fire({
        icon: 'success',
        title: title,
        text: text,
    }).then(() => {
        window.history.back();
    });
}

// Función para mostrar SweetAlert de error y volver a la página anterior
function showErrorAlert(title, text) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: text,
    }).then(() => {
        window.history.back();
    });
}
