<?php
include_once '../db/conexion.php';
include_once '../private/header.php';
session_start();
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ocupar'])){ 
    $mesa_id = $_POST['ocupar'];
    $camarero_id = $_SESSION['usuario_id']; // Asegúrate de que esto esté bien definido en la sesión.
    
    try {
        // Iniciar transacción
        $conn->beginTransaction();

        // Insertar la ocupación en la tabla tbl_ocupacion
        $queryReserva = "INSERT INTO tbl_ocupacion (id_mesa, id_usuario, fecha_hora_ocupacion) VALUES (?, ?, NOW())";
        $stmtReserva = $conn->prepare($queryReserva);
        $stmtReserva->bindParam(1, $mesa_id, PDO::PARAM_INT);
        $stmtReserva->bindParam(2, $camarero_id, PDO::PARAM_INT);

        if ($stmtReserva->execute()) {
            // Actualizar estado de la mesa a 'ocupada'
            $updateQuery = "UPDATE tbl_mesa SET estado_mesa = 'ocupada' WHERE id_mesa = ?";
            $stmtUpdate = $conn->prepare($updateQuery);
            $stmtUpdate->bindParam(1, $mesa_id, PDO::PARAM_INT);
            $stmtUpdate->execute();

            $success = "Mesa ocupada con éxito.";
            $conn->commit();  // Commit de la transacción
        } else {
            $error = "Hubo un error al hacer la reserva.";
            $conn->rollBack();  // Rollback de la transacción
        }

        // Cerrar las sentencias
        $stmtReserva = null;
        $stmtUpdate = null;

    } catch (Exception $e) {
        // En caso de error, hacer rollback y capturar el error
        $conn->rollBack();
        $error = $e->getMessage();
    }

    // Mostrar el mensaje adecuado
    if (!$error) {
        echo "<script>showSweetAlert('success', 'Éxito', '$success');</script>";
    } else {
        echo "<script>showSweetAlert('error', 'Error', '$error');</script>";
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['desocupar'])) {
    $mesa_id = $_POST['desocupar'];
    $camarero_id = $_SESSION['usuario_id']; // Asegúrate de que este ID sea el correcto en la sesión.
    
    try {
        // Iniciar transacción
        $conn->beginTransaction();

        // Actualizar la ocupación más reciente para la mesa
        $updateOcupacion = "UPDATE tbl_ocupacion 
                            SET fecha_hora_desocupacion = NOW() 
                            WHERE id_mesa = ? AND id_usuario = ? AND fecha_hora_desocupacion IS NULL";
        $stmtOcupacion = $conn->prepare($updateOcupacion);
        $stmtOcupacion->bindParam(1, $mesa_id, PDO::PARAM_INT);
        $stmtOcupacion->bindParam(2, $camarero_id, PDO::PARAM_INT);

        if ($stmtOcupacion->execute()) {
            // Cambiar el estado de la mesa a 'libre'
            $updateMesa = "UPDATE tbl_mesa SET estado_mesa = 'libre' WHERE id_mesa = ?";
            $stmtUpdateMesa = $conn->prepare($updateMesa);
            $stmtUpdateMesa->bindParam(1, $mesa_id, PDO::PARAM_INT);

            if ($stmtUpdateMesa->execute()) {
                $success = "Mesa desocupada con éxito.";
                $conn->commit();  // Commit de la transacción
            } else {
                $error = "Hubo un error al actualizar el estado de la mesa.";
                $conn->rollBack();  // Rollback de la transacción
            }

            $stmtUpdateMesa = null;
        } else {
            $error = "Hubo un error al registrar la desocupación en la ocupación.";
            $conn->rollBack();  // Rollback de la transacción
        }

        $stmtOcupacion = null;

    } catch (Exception $e) {
        // En caso de error, hacer rollback y capturar el error
        $conn->rollBack();
        $error = $e->getMessage();
    }

    // Mostrar el mensaje adecuado
    if (!$error) {
        echo "<script>showSweetAlert('success', 'Éxito', '$success');</script>";
    } else {
        echo "<script>showSweetAlert('error', 'Error', '$error');</script>";
    }
    exit();
}
?>
