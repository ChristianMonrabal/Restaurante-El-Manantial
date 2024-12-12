<?php
session_start();
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

function listarMesas($conn) {
    try {
        $query = "SELECT m.id_mesa, m.num_sillas_mesa, m.estado_mesa, s.nombre_sala
                    FROM tbl_mesa m
                    JOIN tbl_sala s ON m.id_sala = s.id_sala";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener mesas: " . $e->getMessage();
        return [];
    }
}

function listarSalas($conn) {
    try {
        $query = "SELECT id_sala, nombre_sala FROM tbl_sala";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error al obtener salas: " . $e->getMessage();
        return [];
    }
}

$mesas = listarMesas($conn);
$salas = listarSalas($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/recursos.css">
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
        <a id="toggleButton" class="add-mesa-button" onclick="toggleTables()">Ver Mesas</a>
        <a href="./add_salas.php" class="add-mesa-button">Agregar Sala</a>
        <a href="./add_mesas.php" class="add-mesa-button">Agregar Mesa</a>
        <a href="../private/logout.php" class="logout-icon">
            <i class="fas fa-sign-out-alt" style="font-size: 20px; color: #000; margin-right: 10px;"></i>
        </a>
        <span><?php echo $_SESSION['nombre_usuario']; ?></span>
    </div>
    <div class="hamburger" id="hamburger-icon">
        &#9776;
    </div>
</div>

<br><br>

<div id="salasTable" class="table-responsive">
    <table class="table table-bordered table-striped" style="table-layout: fixed; width: 80%; margin: 0 auto;">
        <thead class="table-dark">
        <tr>
            <th>Nombre de Sala</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($salas as $sala): ?>
            <tr>
                <td><?php echo $sala['nombre_sala']; ?></td>
                <td>
                    <a href="./edit_salas.php?id=<?php echo $sala['id_sala']; ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="../private/delete_salas.php?id=<?php echo $sala['id_sala']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="mesasTable" class="table-responsive" style="display: none;">
    <table class="table table-bordered table-striped" style="table-layout: fixed; width: 80%; margin: 0 auto;">
        <thead class="table-dark">
        <tr>
            <th>Nº de Mesa</th>
            <th>Número de Sillas</th>
            <th>Estado</th>
            <th>Sala</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($mesas as $mesa): ?>
            <tr>
                <td><?php echo $mesa['id_mesa']; ?></td>
                <td><?php echo $mesa['num_sillas_mesa']; ?></td>
                <td><?php echo ucfirst($mesa['estado_mesa']); ?></td>
                <td><?php echo $mesa['nombre_sala']; ?></td>
                <td>
                    <a href="./update_mesas.php?id=<?php echo $mesa['id_mesa']; ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="../private/delete_mesas.php?id=<?php echo $mesa['id_mesa']; ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/sweet_alert_deletes.js"></script>
<script src="../js/recursos.js"></script>

</body>
</html>
