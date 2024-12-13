<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

include_once '../db/conexion.php';

if (isset($_POST['id_sala'])) {
    $id_sala = $_POST['id_sala'];

    $query = "SELECT * FROM tbl_sala WHERE id_sala = :id_sala";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_sala', $id_sala);
    $stmt->execute();
    $sala = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sala) {
        $_SESSION['errors'] = ["Sala no encontrada."];
        header("Location: ../public/recursos.php");
        exit();
    }

    $nombre_sala = $_POST['nombre_sala'];
    $tipo_sala = $_POST['tipo_sala'];
    $capacidad_total = $_POST['capacidad_total'];
    $imagen_sala = $_FILES['imagen_sala'];

    $errors = [];

    if (empty($nombre_sala) || empty($tipo_sala) || empty($capacidad_total) || !filter_var($capacidad_total, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
        $errors[] = "Todos los campos son obligatorios.";
    }
    

    $image_name = $sala['imagen_sala'];
    if ($imagen_sala['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($imagen_sala['name'], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $errors[] = "El formato de la imagen no es válido. Solo se permiten JPG, JPEG, PNG, y GIF.";
        } elseif ($imagen_sala['size'] > 2 * 1024 * 1024) {
            $errors[] = "El tamaño de la imagen no debe exceder los 2 MB.";
        } else {
            $image_name = uniqid('sala_') . '.' . $file_extension;
            $image_path = "../img/salas/" . $image_name;

            $current_image_path = "../img/salas/" . $sala['imagen_sala'];
            if (file_exists($current_image_path) && $sala['imagen_sala'] != null) {
                unlink($current_image_path);
            }

            if (!move_uploaded_file($imagen_sala['tmp_name'], $image_path)) {
                $errors[] = "Error al mover la imagen al directorio de destino.";
            }
        }
    }

    if (empty($errors)) {
        $update_query = "UPDATE tbl_sala SET nombre_sala = :nombre_sala, tipo_sala = :tipo_sala, capacidad_total = :capacidad_total, imagen_sala = :imagen_sala WHERE id_sala = :id_sala";
        $stmt = $conn->prepare($update_query);
        $stmt->bindParam(':nombre_sala', $nombre_sala);
        $stmt->bindParam(':tipo_sala', $tipo_sala);
        $stmt->bindParam(':capacidad_total', $capacidad_total);
        $stmt->bindParam(':imagen_sala', $image_name);
        $stmt->bindParam(':id_sala', $id_sala);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Sala actualizada correctamente.";
            header("Location: ../public/edit_salas.php?id=" . $id_sala);
            exit();
        } else {
            $errors[] = "Error al actualizar la sala en la base de datos.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../public/edit_salas.php?id=" . $id_sala);
        exit();
    }
}
?>