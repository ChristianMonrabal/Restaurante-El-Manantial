<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

include "../db/conexion.php";

$sql = "SELECT * FROM tbl_sala WHERE tipo_sala = 'privada'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$salas_privadas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar sala privada</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/choose_privada.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
</head>

<body>
<div class="navbar">
    <a href="../index.php">
        <img src="../img/icon.png" class="icon">
    </a>
    <div class="user-info">
        <div class="dropdown">
            <i class="fas fa-caret-down" style="font-size: 16px; margin-right: 10px;"></i>
            <div class="dropdown-content">
                <a href="../private/logout.php">Cerrar Sesión</a>
            </div>
        </div>
        <span><?php echo $_SESSION['nombre_usuario']; ?></span>
    </div>
</div>

<form action="gestion_mesas.php" method="post" class="options">
    <?php foreach ($salas_privadas as $index => $sala): ?>
        <div class="option privada<?php echo $index + 1; ?>">
            <h2><?php echo $sala['nombre_sala']; ?></h2>
            <div class="button-container">
                <input type="hidden" name="sala" value="<?php echo $sala['nombre_sala']; ?>" />
                <input type="submit" class="select-button" value="Seleccionar" />
            </div>
        </div>
    <?php endforeach; ?>
</form>

<script src="../js/dashboard.js"></script>
</body>
</html>
