function showSweetAlert(icon, title, text) {
    Swal.fire({
        icon: icon,  // El tipo de icono a mostrar (puede ser 'success', 'error', 'warning', 'info', etc.)
        title: title,  // El título de la alerta
        text: text,  // El texto de la alerta
        confirmButtonText: 'Volver al inicio'  // El texto del botón de confirmación
    }).then((result) => {
        // Luego de que el usuario confirme la alerta, se ejecuta esta función
        if (result.isConfirmed) {
            // Si el usuario hace clic en "Volver al inicio", lo redirige al dashboard
            window.location.href = '../public/dashboard.php';
        }
    });
}
