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

if (!isset($_GET['id'])) {
    header("Location: ../public/recursos.php");
    exit();
}

$id_mesa = $_GET['id'];

try {
    $conn->beginTransaction();

    $fecha_actual = date('Y-m-d H:i:s');

    $query_delete_reservas = "DELETE FROM tbl_reserva WHERE id_sala = :id_mesa AND fecha_reserva > :fecha_actual";
    $stmt_delete_reservas = $conn->prepare($query_delete_reservas);
    $stmt_delete_reservas->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
    $stmt_delete_reservas->bindParam(':fecha_actual', $fecha_actual, PDO::PARAM_STR);
    $stmt_delete_reservas->execute();

    $query_delete_mesa = "DELETE FROM tbl_mesa WHERE id_mesa = :id_mesa";
    $stmt_delete_mesa = $conn->prepare($query_delete_mesa);
    $stmt_delete_mesa->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
    $stmt_delete_mesa->execute();

    $conn->commit();

    header("Location: ../public/recursos.php");
    exit();
} catch (PDOException $e) {
    $conn->rollBack();
    echo "Error al eliminar la mesa y sus reservas: " . $e->getMessage();
    exit();
}
?>
