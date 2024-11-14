<?php
// Inicia o reanuda la sesión.
session_start();

// Reinicia el array de sesión, eliminando todas las variables de sesión activas.
$_SESSION = array();

// Destruye la sesión, eliminando toda la información almacenada.
session_destroy();

// Redirige al usuario a la página de inicio (index.php) después de cerrar la sesión.
header("Location: ../index.php");

// Termina el script para asegurar que no se ejecute ningún otro código después de la redirección.
exit();
?>