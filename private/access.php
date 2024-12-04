<?php
session_start();
include '../db/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $pwd = trim($_POST['pwd']);

    $_SESSION['nombre_usuario'] = $nombre_usuario;
    $_SESSION['pwd'] = $pwd;

    if (empty($nombre_usuario) || empty($pwd)) {
        $_SESSION['error'] = "Ambos campos son obligatorios.";
        header("Location: ../index.php");
        exit();
    }

    try {
        $sql = "SELECT id_usuario, nombre_usuario, password_usuario, tipo_usuario FROM tbl_usuario WHERE nombre_usuario = :nombre_usuario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($pwd, $usuario['password_usuario'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            unset($_SESSION['pwd']);
            unset($_SESSION['error']);

            header("Location: ../public/dashboard.php");
            exit();
        } else {
            $_SESSION['error'] = "Los datos introducidos son incorrectos.";
            header("Location: ../index.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error en la consulta: " . $e->getMessage();
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../public/login.php");
    exit();
}
