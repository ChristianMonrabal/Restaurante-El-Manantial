<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

include_once '../db/conexion.php';

if (isset($_GET['id'])) {
    $id_sala = $_GET['id'];

    $delete_query = "DELETE FROM tbl_sala WHERE id_sala = :id_sala";
    $stmt = $conn->prepare($delete_query);
    $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Sala eliminada correctamente.";
    } else {
        $_SESSION['errors'][] = "Error al eliminar la sala.";
    }
} else {
    $_SESSION['errors'][] = "ID de sala no válido.";
}

header("Location: ../public/recursos.php");
exit();
?>
