<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

$salasTerraza = [];
try {
    $query = "SELECT * FROM tbl_sala WHERE tipo_sala = 'terraza'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $salasTerraza = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener las salas de terraza: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar terraza</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/choose_salas.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
</head>
<body>
    <div class="navbar">
        <a href="../index.php">
            <img src="../img/icon.png" class="icon" alt="Icono">
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
        <?php foreach ($salasTerraza as $index => $sala): ?>
            <div class="option terraza<?php echo $index + 1; ?>">
                <h2><?php echo htmlspecialchars($sala['nombre_sala']); ?></h2>
                <?php 
                $imagen_sala = $sala['imagen_sala'];
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
