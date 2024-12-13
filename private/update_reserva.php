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

    $query_reserva = "SELECT id_mesa, cantidad_personas FROM tbl_reserva WHERE id_reserva = :id_reserva";
    $stmt_reserva = $conn->prepare($query_reserva);
    $stmt_reserva->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
    $stmt_reserva->execute();
    $reserva = $stmt_reserva->fetch(PDO::FETCH_ASSOC);

    if (!$reserva) {
        $_SESSION['mensaje'] = "La reserva no existe.";
        header("Location: ../public/edit_reserva.php?id_reserva=$id_reserva");
        exit();
    }

    $id_mesa_actual = $reserva['id_mesa'];
    $cantidad_personas_actual = $reserva['cantidad_personas'];

    $query_mesa = "SELECT id_mesa 
                    FROM tbl_mesa 
                    WHERE id_sala = :id_sala 
                    AND estado_mesa = 'libre' 
                    AND num_sillas_mesa >= :cantidad_personas
                    AND id_mesa != :id_mesa_actual
                    LIMIT 1";
    $stmt_mesa = $conn->prepare($query_mesa);
    $stmt_mesa->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmt_mesa->bindParam(':cantidad_personas', $cantidad_personas, PDO::PARAM_INT);
    $stmt_mesa->bindParam(':id_mesa_actual', $id_mesa_actual, PDO::PARAM_INT);
    $stmt_mesa->execute();
    $mesa = $stmt_mesa->fetch(PDO::FETCH_ASSOC);

    if ($mesa) {
        $id_mesa = $mesa['id_mesa'];
    } else {
        $id_mesa = $id_mesa_actual;
    }

    $query_check = "SELECT COUNT(*) 
                    FROM tbl_reserva 
                    WHERE id_sala = :id_sala 
                    AND fecha_reserva = :fecha_reserva 
                    AND id_franja = :id_franja 
                    AND id_mesa = :id_mesa 
                    AND id_reserva != :id_reserva";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmt_check->bindParam(':fecha_reserva', $fecha_reserva, PDO::PARAM_STR);
    $stmt_check->bindParam(':id_franja', $id_franja, PDO::PARAM_INT);
    $stmt_check->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
    $stmt_check->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);

    $stmt_check->execute();
    $existing_reservations = $stmt_check->fetchColumn();

    if ($existing_reservations > 0) {
        $_SESSION['mensaje'] = "Ya existe una reserva para la misma fecha, hora y mesa.";
        header("Location: ../public/edit_reserva.php?id_reserva=$id_reserva");
        exit();
    }

    $query_update = "UPDATE tbl_reserva 
                        SET nombre_reserva = :nombre_reserva, 
                            cantidad_personas = :cantidad_personas, 
                            id_sala = :id_sala, 
                            id_franja = :id_franja, 
                            fecha_reserva = :fecha_reserva, 
                            id_mesa = :id_mesa 
                        WHERE id_reserva = :id_reserva";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bindParam(':nombre_reserva', $nombre_reserva, PDO::PARAM_STR);
    $stmt_update->bindParam(':cantidad_personas', $cantidad_personas, PDO::PARAM_STR);
    $stmt_update->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmt_update->bindParam(':id_franja', $id_franja, PDO::PARAM_INT);
    $stmt_update->bindParam(':fecha_reserva', $fecha_reserva, PDO::PARAM_STR);
    $stmt_update->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
    $stmt_update->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);

    if ($stmt_update->execute()) {
        $_SESSION['mensaje_successful'] = "Reserva actualizada correctamente.";
        header("Location: ../public/edit_reserva.php?id_reserva=$id_reserva");
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
