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
    $imagen_sala = $_FILES['imagen_sala'];

    $errors = [];

    // Validación básica
    if (empty($nombre_sala)) {
        $errors[] = "El nombre de la sala es obligatorio.";
    }
    if (empty($tipo_sala)) {
        $errors[] = "El tipo de sala es obligatorio.";
    }
    if (empty($capacidad_total) || !filter_var($capacidad_total, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
        $errors[] = "La capacidad total debe ser un número entero positivo.";
    }

    $image_name = null;
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

            if (!move_uploaded_file($imagen_sala['tmp_name'], $image_path)) {
                $errors[] = "Error al mover la imagen al directorio de destino.";
            }
        }
    } else {
        $errors[] = "Es obligatorio subir una imagen.";
    }

    if (empty($errors)) {
        $insert_query = "INSERT INTO tbl_sala (nombre_sala, tipo_sala, capacidad_total, imagen_sala) 
                            VALUES (:nombre_sala, :tipo_sala, :capacidad_total, :imagen_sala)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bindParam(':nombre_sala', $nombre_sala);
        $stmt->bindParam(':tipo_sala', $tipo_sala);
        $stmt->bindParam(':capacidad_total', $capacidad_total);
        $stmt->bindParam(':imagen_sala', $image_name);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Sala agregada correctamente.";
            header("Location: ../public/add_salas.php");
            exit();
        } else {
            $errors[] = "Error al agregar la sala en la base de datos.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../public/add_salas.php");
        exit();
    }
}
?>
