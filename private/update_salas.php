<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

include '../db/conexion.php';

$errors = [];

$id_sala = isset($_POST['id_sala']) ? (int)$_POST['id_sala'] : null;
$tipo_sala = isset($_POST['tipo_sala']) ? trim($_POST['tipo_sala']) : '';
$nombre_sala = isset($_POST['nombre_sala']) ? trim($_POST['nombre_sala']) : '';
$capacidad_total = isset($_POST['capacidad_total']) ? trim($_POST['capacidad_total']) : '';
$imagen_sala = isset($_POST['imagen_sala']) ? trim($_POST['imagen_sala']) : null;

if (!$id_sala) {
    die("Error: ID de sala no válido.");
}

if (empty($tipo_sala)) {
    $errors['tipo_sala'] = "El tipo de sala no puede estar vacío.";
}

if (empty($nombre_sala)) {
    $errors['nombre_sala'] = "El nombre de la sala no puede estar vacío.";
}

if (empty($capacidad_total) || !is_numeric($capacidad_total)) {
    $errors['capacidad_total'] = "La capacidad total debe ser un número válido.";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['form_data'] = $_POST;
    header("Location: ../public/edit_salas.php?id=$id_sala");
    exit();
}

try {
    $stmt = $conn->prepare("
        UPDATE tbl_sala 
        SET tipo_sala = :tipo_sala, nombre_sala = :nombre_sala, 
            capacidad_total = :capacidad_total, imagen_sala = :imagen_sala 
        WHERE id_sala = :id_sala
    ");
    $stmt->execute([
        'tipo_sala' => $tipo_sala,
        'nombre_sala' => $nombre_sala,
        'capacidad_total' => $capacidad_total,
        'imagen_sala' => $imagen_sala,
        'id_sala' => $id_sala
    ]);

    $_SESSION['message'] = "Sala actualizada exitosamente.";
    header("Location: ../public/edit_salas.php?id=$id_sala");
    exit();
} catch (PDOException $e) {
    die("Error al actualizar la sala: " . $e->getMessage());
}
?>
