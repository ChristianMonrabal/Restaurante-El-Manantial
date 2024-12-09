<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

include_once '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_sala'])) {
    $nombre_sala = $_POST['nombre_sala'];
    $tipo_sala = $_POST['tipo_sala'];
    $capacidad_total = $_POST['capacidad_total'];
    $imagen_sala = $_POST['imagen_sala'];

    $errors = [];

    if (empty($nombre_sala)) {
        $errors['nombre_sala'] = "El nombre de la sala es obligatorio.";
    }

    if (empty($tipo_sala)) {
        $errors['tipo_sala'] = "El tipo de sala es obligatorio.";
    }

    if (empty($capacidad_total) || !preg_match('/^[1-9]\d*$/', $capacidad_total)) {
        $errors['capacidad_total'] = "La capacidad total debe ser un número entero positivo.";
    }

    if (empty($imagen_sala)) {
        $errors['imagen_sala'] = "La URL de la imagen es obligatoria.";
    }

    if (empty($errors)) {
        $insert_query = "INSERT INTO tbl_sala (nombre_sala, tipo_sala, capacidad_total, imagen_sala) 
                            VALUES (:nombre_sala, :tipo_sala, :capacidad_total, :imagen_sala)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bindParam(':nombre_sala', $nombre_sala);
        $stmt->bindParam(':tipo_sala', $tipo_sala);
        $stmt->bindParam(':capacidad_total', $capacidad_total);
        $stmt->bindParam(':imagen_sala', $imagen_sala);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Sala agregada correctamente.";
            header("Location: ../public/add_salas.php");
            exit();
        } else {
            $_SESSION['errors'][] = "Error al agregar la sala.";
        }
    } else {
        $_SESSION['errors'] = $errors;
        header("Location: ../public/add_salas.php");
        exit();
    }
}
?>
