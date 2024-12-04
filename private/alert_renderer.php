<?php
function renderAlert($type, $title, $message, $redirectUrl = null) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($title); ?></title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="../js/sweet_alert_crud.js"></script>
    </head>
    <body>
        <script>
            <?php if ($type === 'success') { ?>
                showSuccessAlert('<?php echo addslashes($title); ?>', '<?php echo addslashes($message); ?>', '<?php echo $redirectUrl; ?>');
            <?php } else { ?>
                showErrorAlert('<?php echo addslashes($title); ?>', '<?php echo addslashes($message); ?>');
            <?php } ?>
        </script>
    </body>
    </html>
    <?php
}
?>
