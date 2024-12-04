<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

// Verificar si se recibió el ID del usuario
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: usuarios.php");
    exit();
}

include_once '../db/conexion.php';
include_once './header.php';

$id_usuario = $_GET['id'];
$mensaje = "";
$tipo_alerta = "";

try {
    // Preparar la consulta para eliminar al usuario
    $query = "DELETE FROM tbl_usuario WHERE id_usuario = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $mensaje = "Usuario eliminado correctamente.";
        $tipo_alerta = "success";
    } else {
        $mensaje = "Error al intentar eliminar el usuario.";
        $tipo_alerta = "error";
    }
} catch (PDOException $e) {
    $mensaje = "Ocurrió un error: " . $e->getMessage();
    $tipo_alerta = "error";
}

// Mostrar el SweetAlert con el resultado
echo "<script src='../js/sweet_alert_crud.js'></script>";
echo "<script>
        showSweetAlert('$tipo_alerta', 'Resultado de la operación', '$mensaje');
    </script>";
?>
