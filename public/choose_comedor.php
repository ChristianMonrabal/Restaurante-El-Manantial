<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

$salasComedor = [];
try {
    $query = "SELECT * FROM tbl_sala WHERE tipo_sala = 'comedor'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $salasComedor = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener las salas de comedor: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar comedor</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/choose_salas.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
</head>
<body>
    <div class="navbar">
        <a href="../index.php">
            <img src="../img/icon.png" class="icon">
        </a>
        <a href="./historial.php" class="right-link">Historial</a>
        <a href="./reservas.php" class="right-link">Reservas</a>
        <?php if ($_SESSION['tipo_usuario'] === 'administrador'): ?>
            <a href="./recursos.php" class="right-link">Recursos</a>
            <a href="./usuarios.php" class="right-link">Usuarios</a>
        <?php endif; ?>

        <div class="user-info">
            <i class="fas fa-sign-out-alt logout-icon" onclick="confirmarCerrarSesion()" style="font-size: 20px; color: #000;"></i>
            <span><?php echo $_SESSION['nombre_usuario']; ?></span>
        </div>
    </div>

    <form action="gestion_mesas.php" method="post" class="options">
        <?php foreach ($salasComedor as $sala): ?>
            <div class="option">
                <h2><?php echo htmlspecialchars($sala['nombre_sala']); ?></h2>
                <?php 
                $imagen_sala = $sala['imagen_sala'] ?: 'default.jpg';
                ?>
                <div class="button-container">
                    <button type="submit" name="sala" value="<?php echo htmlspecialchars($sala['nombre_sala']); ?>" style="background: none; border: none;">
                        <img src="../img/salas/<?php echo htmlspecialchars($imagen_sala); ?>" alt="<?php echo htmlspecialchars($sala['nombre_sala']); ?>" class="sala-img">
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </form>

    <script src="../js/dashboard.js"></script>
</body>
</html>
