<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id_reserva'])) {
    header("Location: ../public/reservas.php?status=error&message=No se proporcionó un ID de reserva");
    exit();
}

$id_reserva = $_GET['id_reserva'];

try {
    $query = "DELETE FROM tbl_reserva WHERE id_reserva = :id_reserva";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ../public/reservas.php?status=success&message=Reserva eliminada correctamente");
    } else {
        header("Location: ../public/reservas.php?status=error&message=Error al intentar eliminar la reserva");
    }
} catch (Exception $e) {
    header("Location: ../public/reservas.php?status=error&message=Ocurrió un error al eliminar la reserva: " . $e->getMessage());
}
exit();
?>
