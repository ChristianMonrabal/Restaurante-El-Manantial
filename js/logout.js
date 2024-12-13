function confirmarCerrarSesion() {
    Swal.fire({
        title: '¿Estás seguro?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cerrar sesión',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'swal2-confirm-left',
            cancelButton: 'swal2-cancel-right'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../private/logout.php';
        }
    });
}
