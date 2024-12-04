<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

include_once '../db/conexion.php';

$nombre = isset($_POST['nombre_usuario']) ? $_POST['nombre_usuario'] : '';
$email = isset($_POST['email_usuario']) ? $_POST['email_usuario'] : '';
$tipo = isset($_POST['tipo_usuario']) ? $_POST['tipo_usuario'] : '';
$password = isset($_POST['password_usuario']) ? $_POST['password_usuario'] : '';

$nombreError = $emailError = $tipoError = $passwordError = "";

if (empty($nombre)) {
    $nombreError = "El nombre de usuario no puede estar vacío.";
}
if (empty($email)) {
    $emailError = "El correo electrónico no puede estar vacío.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailError = "El correo electrónico no es válido.";
}
if (empty($tipo)) {
    $tipoError = "El tipo de usuario es obligatorio.";
}
if (empty($password)) {
    $passwordError = "La contraseña no puede estar vacía.";
}

if (empty($nombreError) && empty($emailError) && empty($tipoError) && empty($passwordError)) {
    try {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_usuario WHERE nombre_usuario = :nombre");
        $stmt->bindParam(':nombre', $nombre);
        $stmt->execute();
        if ($stmt->fetchColumn() > 0) {
            $nombreError = "El nombre de usuario ya existe.";
        }

        if (empty($nombreError)) {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM tbl_usuario WHERE email_usuario = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                $emailError = "El correo electrónico ya está registrado.";
            }
        }

        if (empty($nombreError) && empty($emailError)) {
            $query = "INSERT INTO tbl_usuario (nombre_usuario, tipo_usuario, email_usuario, password_usuario) 
                        VALUES (:nombre, :tipo, :email, :password)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $passwordHash);

            if ($stmt->execute()) {
                header('Location: ../public/usuarios.php');
                exit();
            } else {
                throw new Exception("Error al insertar el usuario.");
            }
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header('Location: ../public/add_usuario.php');
        exit();
    }
}

$_SESSION['nombre'] = $nombre;
$_SESSION['email'] = $email;
$_SESSION['tipo'] = $tipo;

$_SESSION['nombreError'] = $nombreError;
$_SESSION['emailError'] = $emailError;
$_SESSION['tipoError'] = $tipoError;
$_SESSION['passwordError'] = $passwordError;

header('Location: ../public/add_usuario.php');
exit();
?>
