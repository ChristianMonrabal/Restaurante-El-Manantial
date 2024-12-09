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
    $query = "DELETE FROM tbl_mesa WHERE id_mesa = :id_mesa";
    $stmt = $conn->prepare($query);
    $stmt->execute(['id_mesa' => $id_mesa]);

    // Redirigir a la página de recursos después de eliminar
    header("Location: ../public/recursos.php");
    exit();
} catch (PDOException $e) {
    echo "Error al eliminar la mesa: " . $e->getMessage();
    exit();
}
?>
