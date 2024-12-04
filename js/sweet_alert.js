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
