<?php
if (isset($_GET['status']) && isset($_GET['message'])) {
    $status = htmlspecialchars($_GET['status']);
    $message = htmlspecialchars($_GET['message']);

    echo "<script>
        window.addEventListener('DOMContentLoaded', () => {
            if ('$status' === 'success') {
                showSuccessAlert('Operación Exitosa', '$message');
            } else if ('$status' === 'error') {
                showErrorAlert('Error', '$message');
            }
        });
    </script>";
}
?>
