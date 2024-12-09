function showSweetAlert(icon, title, text) {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        confirmButtonText: 'Volver'
    }).then(() => {
        window.location.href = '../public/dashboard.php';    });
}

function showSuccessAlert(title, text) {
    Swal.fire({
        icon: 'success',
        title: title,
        text: text,
    }).then(() => {
        window.location.href = '../public/dashboard.php';    });
}

function showErrorAlert(title, text) {
    Swal.fire({
        icon: 'error',
        title: title,
        text: text,
    }).then(() => {
        window.location.href = '../public/dashboard.php';    });
}
