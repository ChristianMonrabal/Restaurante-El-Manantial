<?php
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: usuarios.php");
    exit();
}

include_once '../db/conexion.php';

$id_usuario = $_GET['id'];

$query = "SELECT * FROM tbl_usuario WHERE id_usuario = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: usuarios.php");
    exit();
}

$mensaje = "";
$errors = [];
$nombre_usuario = $usuario['nombre_usuario'];
$email_usuario = $usuario['email_usuario'];
$tipo_usuario = $usuario['tipo_usuario'];
$password_usuario = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $email_usuario = $_POST['email_usuario'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $password_usuario = !empty($_POST['password_usuario']) ? password_hash($_POST['password_usuario'], PASSWORD_BCRYPT) : $usuario['password_usuario'];

    if (empty($nombre_usuario)) {
        $errors['nombre_usuario'] = "El usuario no puede estar vacío.";
    } else {
        $checkNombreUsuarioQuery = "SELECT * FROM tbl_usuario WHERE nombre_usuario = :nombre AND id_usuario != :id";
        $stmt = $conn->prepare($checkNombreUsuarioQuery);
        $stmt->bindParam(':nombre', $nombre_usuario);
        $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $errors['nombre_usuario'] = "Este nombre de usuario ya está siendo utilizado por otro usuario.";
        }
    }

    if (empty($email_usuario)) {
        $errors['email_usuario'] = "El correo electrónico no puede estar vacío.";
    } elseif (!filter_var($email_usuario, FILTER_VALIDATE_EMAIL) || !preg_match('/@elmanantial\.com$/', $email_usuario)) {
        $errors['email_usuario'] = "El correo electrónico debe ser válido y tener el dominio @elmanantial.com.";
    } else {
        $checkEmailQuery = "SELECT * FROM tbl_usuario WHERE email_usuario = :email AND id_usuario != :id";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bindParam(':email', $email_usuario);
        $stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $errors['email_usuario'] = "Ya existe un usuario con este correo electrónico.";
        }
    }

    if (empty($tipo_usuario)) {
        $errors['tipo_usuario'] = "El tipo de usuario es obligatorio.";
    }

    if (empty($errors)) {
        $update_query = "UPDATE tbl_usuario SET nombre_usuario = :nombre, email_usuario = :email, tipo_usuario = :tipo, password_usuario = :password WHERE id_usuario = :id";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(':nombre', $nombre_usuario);
        $update_stmt->bindParam(':email', $email_usuario);
        $update_stmt->bindParam(':tipo', $tipo_usuario);
        $update_stmt->bindParam(':password', $password_usuario);
        $update_stmt->bindParam(':id', $id_usuario, PDO::PARAM_INT);

        if ($update_stmt->execute()) {
            $mensaje = "Usuario actualizado correctamente.";
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $mensaje = "Hubo un error al actualizar el usuario.";
        }
    }
}
?>
