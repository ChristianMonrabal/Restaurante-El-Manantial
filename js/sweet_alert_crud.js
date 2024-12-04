function showSweetAlert(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        confirmButtonText: 'Volver al inicio'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../public/usuarios.php';
        }
    });
}

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
        }
    });
}

// Función para mostrar SweetAlert de éxito
function showSuccessAlert(title, text, redirectUrl) {
    Swal.fire({
        icon: 'success',
        title: title,
        text: text,
    }).then(() => {
        if (redirectUrl) {
            window.location.href = redirectUrl;
        }
    });
}

// Función para mostrar SweetAlert de error
function showErrorAlert(title, text) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: text,
    }).then(() => {
        window.history.back();
    });
}
