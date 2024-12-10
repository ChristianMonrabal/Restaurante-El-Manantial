<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

include_once '../db/conexion.php';

try {
    $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, false);

    if (isset($_GET['id'])) {
        $id_sala = $_GET['id'];

        $conn->beginTransaction();

        $delete_mesas_query = "DELETE FROM tbl_mesa WHERE id_sala = :id_sala";
        $stmt_mesas = $conn->prepare($delete_mesas_query);
        $stmt_mesas->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        if (!$stmt_mesas->execute()) {
            throw new Exception("Error al eliminar las mesas.");
        }

        $delete_sala_query = "DELETE FROM tbl_sala WHERE id_sala = :id_sala";
        $stmt_sala = $conn->prepare($delete_sala_query);
        $stmt_sala->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        if (!$stmt_sala->execute()) {
            throw new Exception("Error al eliminar la sala.");
        }

        $conn->commit();

        $_SESSION['message'] = "Sala y mesas eliminadas correctamente.";
    } else {
        $_SESSION['errors'][] = "ID de sala no válido.";
    }
} catch (Exception $e) {
    $conn->rollBack();
    $_SESSION['errors'][] = $e->getMessage();
} finally {
    $conn->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
}

header("Location: ../public/recursos.php");
exit();
?>
