<?php
// Incluye el archivo de conexión a la base de datos y el encabezado privado.
include_once '../db/conexion.php';
include_once '../private/header.php';

// Inicia la sesión.
session_start();

// Inicializa las variables para los mensajes de éxito y error.
$success = $error = '';

// Manejo del formulario para ocupar una mesa.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ocupar'])){ 
    $mesa_id = $_POST['ocupar'];
    $camarero_id = $_SESSION['usuario_id'];
    try {
        // Desactiva el autocommit para iniciar la transacción manualmente.
        mysqli_autocommit($conn, false);
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        
        // Consulta para insertar una nueva ocupación.
        $queryReserva = "INSERT INTO tbl_ocupacion (id_mesa, id_camarero, fecha_hora_ocupacion) VALUES (?,?,NOW())";
        $stmtReserva = mysqli_prepare($conn, $queryReserva);
        mysqli_stmt_bind_param($stmtReserva, "ii", $mesa_id, $camarero_id);

        if (mysqli_stmt_execute($stmtReserva)) {
            // Si la inserción es exitosa, actualiza el estado de la mesa a 'ocupada'.
            $updateQuery = "UPDATE tbl_mesa SET estado_mesa = 'ocupada' WHERE id_mesa = ?";
            $stmtUpdate = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($stmtUpdate, "i", $mesa_id);
            mysqli_stmt_execute($stmtUpdate);

            // Mensaje de éxito.
            $success = "Mesa ocupada con éxito.";
        } else {
            // Mensaje de error si hay un problema.
            $error = "Hubo un error al hacer la reserva.";
        }

        // Confirma la transacción.
        mysqli_commit($conn);
        // Cierra las declaraciones.
        mysqli_stmt_close($stmtReserva);
        mysqli_stmt_close($stmtUpdate);
    } catch (Exception $e) {
        // Si hay un error, se revierte la transacción.
        mysqli_rollback($conn);
        $error = $e->getMessage();
    }

    // Muestra un mensaje con una alerta personalizada de éxito o error.
    if (!$error) {
        echo "<script>showSweetAlert('success', 'Éxito', '$success');</script>";
    } else {
        echo "<script>showSweetAlert('error', 'Error', '$error');</script>";
    }
    exit();
}

// Manejo del formulario para desocupar una mesa.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['desocupar'])) {
    $mesa_id = $_POST['desocupar'];
    $camarero_id = $_SESSION['usuario_id'];

    // Inicia la transacción.
    try {
        mysqli_autocommit($conn, false);
        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

        // Actualiza la ocupación más reciente para registrar la hora de desocupación.
        $updateOcupacion = "UPDATE tbl_ocupacion 
                            SET fecha_hora_desocupacion = NOW() 
                            WHERE id_mesa = ? AND id_camarero = ? AND fecha_hora_desocupacion IS NULL";
        $stmtOcupacion = mysqli_prepare($conn, $updateOcupacion);
        mysqli_stmt_bind_param($stmtOcupacion, "ii", $mesa_id, $camarero_id);

        if (mysqli_stmt_execute($stmtOcupacion)) {
            // Si la actualización es exitosa, cambia el estado de la mesa a 'libre'.
            $updateMesa = "UPDATE tbl_mesa SET estado_mesa = 'libre' WHERE id_mesa = ?";
            $stmtUpdateMesa = mysqli_prepare($conn, $updateMesa);
            mysqli_stmt_bind_param($stmtUpdateMesa, "i", $mesa_id);

            if (mysqli_stmt_execute($stmtUpdateMesa)) {
                // Mensaje de éxito.
                $success = "Mesa desocupada con éxito.";
                mysqli_commit($conn);
            } else {
                // Mensaje de error si hay un problema.
                $error = "Hubo un error al actualizar el estado de la mesa.";
                mysqli_rollback($conn);
            }

            // Cierra la declaración.
            mysqli_stmt_close($stmtUpdateMesa);
        } else {
            // Si hay un error en la actualización de la ocupación.
            $error = "Hubo un error al registrar la desocupación en la ocupación.";
            mysqli_rollback($conn);
        }

        // Cierra la declaración.
        mysqli_stmt_close($stmtOcupacion);
    } catch (Exception $e) {
        // Si hay un error, se revierte la transacción.
        mysqli_rollback($conn);
        $error = $e->getMessage();
    }

    // Muestra un mensaje con una alerta personalizada de éxito o error.
    if (!$error) {
        echo "<script>showSweetAlert('success', 'Éxito', '$success');</script>";
    } else {
        echo "<script>showSweetAlert('error', 'Error', '$error');</script>";
    }
    exit();
}
?>