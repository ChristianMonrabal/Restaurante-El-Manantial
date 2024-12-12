<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
} else {
    header("Location: ../index.php");
    exit();
}

$errores = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_reserva = $_POST['nombre_reserva'];
    $cantidad_personas = $_POST['cantidad_personas'];
    $id_sala = $_POST['id_sala'];
    $id_franja = $_POST['id_franja'];
    $fecha_reserva = $_POST['fecha_reserva'];

    // Validación de campos vacíos
    if (empty($nombre_reserva)) {
        $errores['nombre_reserva'] = "El nombre de la reserva no puede estar vacío.";
    }

    if (empty($cantidad_personas)) {
        $errores['cantidad_personas'] = "La cantidad de personas es obligatoria.";
    }

    if (empty($id_sala)) {
        $errores['id_sala'] = "Debes seleccionar una sala.";
    }

    if (empty($fecha_reserva)) {
        $errores['fecha_reserva'] = "La fecha de reserva es obligatoria.";
    }

    if (empty($id_franja)) {
        $errores['id_franja'] = "Debes seleccionar una franja horaria.";
    }

    // Si hay errores, redirigir con mensaje
    if (!empty($errores)) {
        $_SESSION['errores'] = $errores;
        $_SESSION['nombre_reserva'] = $nombre_reserva;
        $_SESSION['cantidad_personas'] = $cantidad_personas;
        $_SESSION['id_sala'] = $id_sala;
        $_SESSION['fecha_reserva'] = $fecha_reserva;
        $_SESSION['id_franja'] = $id_franja;
        header("Location: ../public/add_reservas.php");
        exit();
    }

    // Si no hay errores, procesar la reserva
    $query_check_reserva = "SELECT COUNT(*) FROM tbl_reserva 
                            WHERE id_sala = :id_sala 
                            AND fecha_reserva = :fecha_reserva 
                            AND id_franja = :id_franja";
    $stmt_check_reserva = $conn->prepare($query_check_reserva);
    $stmt_check_reserva->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmt_check_reserva->bindParam(':fecha_reserva', $fecha_reserva, PDO::PARAM_STR);
    $stmt_check_reserva->bindParam(':id_franja', $id_franja, PDO::PARAM_INT);
    $stmt_check_reserva->execute();
    $reserva_existente = $stmt_check_reserva->fetchColumn();

    if ($reserva_existente > 0) {
        $_SESSION['mensaje'] = "Ya existe una reserva para esta mesa en el horario seleccionado.";
        header("Location: ../public/add_reservas.php");
        exit();
    } else {
        $query_mesa = "SELECT id_mesa, num_sillas_mesa 
                        FROM tbl_mesa 
                        WHERE id_sala = :id_sala 
                        AND estado_mesa = 'libre' 
                        AND num_sillas_mesa >= :cantidad_personas
                        LIMIT 1";
        $stmt_mesa = $conn->prepare($query_mesa);
        $stmt_mesa->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        $stmt_mesa->bindParam(':cantidad_personas', $cantidad_personas, PDO::PARAM_INT);
        $stmt_mesa->execute();
        $mesa = $stmt_mesa->fetch(PDO::FETCH_ASSOC);

        if ($mesa) {
            $id_mesa = $mesa['id_mesa'];
            $query_reserva = "INSERT INTO tbl_reserva (nombre_reserva, cantidad_personas, id_sala, id_franja, fecha_reserva, id_mesa, id_usuario)
                                VALUES (:nombre_reserva, :cantidad_personas, :id_sala, :id_franja, :fecha_reserva, :id_mesa, :id_usuario)";
            $stmt_reserva = $conn->prepare($query_reserva);
            $stmt_reserva->bindParam(':nombre_reserva', $nombre_reserva, PDO::PARAM_STR);
            $stmt_reserva->bindParam(':cantidad_personas', $cantidad_personas, PDO::PARAM_INT);
            $stmt_reserva->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
            $stmt_reserva->bindParam(':id_franja', $id_franja, PDO::PARAM_INT);
            $stmt_reserva->bindParam(':fecha_reserva', $fecha_reserva, PDO::PARAM_STR);
            $stmt_reserva->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
            $stmt_reserva->bindParam(':id_usuario', $usuario_id, PDO::PARAM_INT);
            $stmt_reserva->execute();

            $_SESSION['mensaje_successful'] = "Reserva realizada exitosamente.";
            header("Location: ../public/add_reservas.php");
            exit();
        } else {
            $_SESSION['mensaje'] = "No hay mesas disponibles para esta cantidad de personas.";
            header("Location: ../public/add_reservas.php");
            exit();
        }
    }
}
?>
