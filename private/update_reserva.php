<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_reserva = $_POST['id_reserva'];
    $nombre_reserva = $_POST['nombre_reserva'];
    $cantidad_personas = $_POST['cantidad_personas'];
    $id_sala = $_POST['id_sala'];
    $id_franja = $_POST['id_franja'];
    $fecha_reserva = $_POST['fecha_reserva'];

    $errores = false;

    if (empty($nombre_reserva) || empty($cantidad_personas) || empty($id_sala) || empty($fecha_reserva) || empty($id_franja)) {
        $errores = true;
    }

    if ($errores) {
        $_SESSION['mensaje'] = "Por favor, completa todos los campos requeridos.";
        header("Location: ../public/edit_reserva.php?id_reserva=$id_reserva");
        exit();
    }

    $query_update = "UPDATE tbl_reserva 
                    SET nombre_reserva = :nombre_reserva, 
                        cantidad_personas = :cantidad_personas, 
                        id_sala = :id_sala, 
                        id_franja = :id_franja, 
                        fecha_reserva = :fecha_reserva 
                    WHERE id_reserva = :id_reserva";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bindParam(':nombre_reserva', $nombre_reserva, PDO::PARAM_STR);
    $stmt_update->bindParam(':cantidad_personas', $cantidad_personas, PDO::PARAM_STR);
    $stmt_update->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmt_update->bindParam(':id_franja', $id_franja, PDO::PARAM_INT);
    $stmt_update->bindParam(':fecha_reserva', $fecha_reserva, PDO::PARAM_STR);
    $stmt_update->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);

    if ($stmt_update->execute()) {
        $_SESSION['mensaje_successful'] = "Reserva actualizada correctamente.";
        header("Location: ../public/reservas.php");
        exit();
    } else {
        $_SESSION['mensaje'] = "Error al actualizar la reserva.";
        header("Location: ../public/edit_reserva.php?id_reserva=$id_reserva");
        exit();
    }
} else {
    header("Location: ../public/reservas.php");
    exit();
}
?>
