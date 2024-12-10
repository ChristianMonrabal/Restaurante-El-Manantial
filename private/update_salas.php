<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

include_once '../db/conexion.php';

// Verificamos que el id_sala haya sido enviado
if (isset($_POST['id_sala'])) {
    $id_sala = $_POST['id_sala'];

    // Obtener los datos actuales de la sala
    $query = "SELECT * FROM tbl_sala WHERE id_sala = :id_sala";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_sala', $id_sala);
    $stmt->execute();
    $sala = $stmt->fetch(PDO::FETCH_ASSOC); // Obtenemos los datos actuales de la sala

    if (!$sala) {
        $_SESSION['errors'] = ["Sala no encontrada."];
        header("Location: ../public/recursos.php");  // Redirigir si no se encuentra la sala
        exit();
    }

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

    // Lógica para manejar la imagen
    $image_name = $sala['imagen_sala']; // Si no se sube una nueva imagen, mantenemos la actual
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

            // Eliminar la imagen anterior si existe
            $current_image_path = "../img/salas/" . $sala['imagen_sala'];
            if (file_exists($current_image_path) && $sala['imagen_sala'] != null) {
                unlink($current_image_path);  // Eliminar la imagen vieja
            }

            if (!move_uploaded_file($imagen_sala['tmp_name'], $image_path)) {
                $errors[] = "Error al mover la imagen al directorio de destino.";
            }
        }
    }

    if (empty($errors)) {
        // Preparar la consulta para actualizar los datos de la sala
        $update_query = "UPDATE tbl_sala SET nombre_sala = :nombre_sala, tipo_sala = :tipo_sala, capacidad_total = :capacidad_total, imagen_sala = :imagen_sala WHERE id_sala = :id_sala";
        $stmt = $conn->prepare($update_query);
        $stmt->bindParam(':nombre_sala', $nombre_sala);
        $stmt->bindParam(':tipo_sala', $tipo_sala);
        $stmt->bindParam(':capacidad_total', $capacidad_total);
        $stmt->bindParam(':imagen_sala', $image_name);
        $stmt->bindParam(':id_sala', $id_sala);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Sala actualizada correctamente.";
            header("Location: ../public/recursos.php");  // Redirigir a la lista de recursos
            exit();
        } else {
            $errors[] = "Error al actualizar la sala en la base de datos.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: ../public/edit_salas.php?id_sala=" . $id_sala);  // Volver a la página de edición con los errores
        exit();
    }
}
?>
