<?php
session_start();
include_once '../db/conexion.php';
include_once '../private/alert.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
} else {
    header("Location: ../index.php");
    exit();
}

$query_reservas = "SELECT 
    r.id_reserva, 
    r.nombre_reserva, 
    u.nombre_usuario, 
    r.cantidad_personas, 
    r.id_mesa, 
    s.nombre_sala, 
    r.fecha_reserva, 
    f.franja_horaria 
FROM 
    tbl_reserva r
JOIN 
    tbl_usuario u ON r.id_usuario = u.id_usuario
JOIN 
    tbl_mesa m ON r.id_mesa = m.id_mesa
JOIN 
    tbl_sala s ON r.id_sala = s.id_sala
JOIN 
    tbl_franja_horaria f ON r.id_franja = f.id_franja
ORDER BY 
    r.fecha_reserva DESC";
$stmt_reservas = $conn->prepare($query_reservas);
$stmt_reservas->execute();
$result_reservas = $stmt_reservas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/usuarios.css">
    <link rel="stylesheet" href="../css/dashboard.css">
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
        <a href="add_reservas.php" class="add-user-button">Nueva reserva</a>
        <a href="../private/logout.php" class="logout-icon">
            <i class="fas fa-sign-out-alt" style="font-size: 20px; color: #000; margin-right: 10px;"></i>
        </a>
        <span><?php echo $_SESSION['nombre_usuario']; ?></span>
    </div>
</div>

<div class="container mt-5">
    <h1 class="text-center">Gestión de Reservas</h1>

    <div class="row mb-3">
        <div class="col-md-5">
            <input type="text" id="filtroNombre" class="form-control" placeholder="Buscar por nombre de reserva">
        </div>
        <div class="col-md-5">
            <input type="date" id="filtroFecha" class="form-control" placeholder="Buscar por fecha de reserva">
        </div>
        <div class="col-md-2">
        <button id="borrarFiltros" class="btn btn-outline-danger btn-sm w-100" style="display: none;">Borrar Filtros</button>
        </div>
    </div>

    <table class="table table-bordered table-striped" style="table-layout: fixed; width: 90%; margin: 0 auto;">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Personas</th>
                <th>Sala</th>
                <th>Mesa</th>
                <th>Fecha</th>
                <th>Horario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaReservas">
            <?php foreach ($result_reservas as $reserva): ?>
                <tr>
                    <td><?php echo htmlspecialchars($reserva['nombre_reserva']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['nombre_usuario']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['cantidad_personas']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['nombre_sala']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['id_mesa']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['fecha_reserva']); ?></td>
                    <td><?php echo htmlspecialchars($reserva['franja_horaria']); ?></td>
                    <td>
                        <a href="edit_reserva.php?id_reserva=<?php echo htmlspecialchars($reserva['id_reserva']); ?>" class="btn btn-warning btn-sm">Editar</a>
                        <button class="btn btn-danger btn-sm" onclick="confirmarEliminarMesa(<?php echo htmlspecialchars($reserva['id_reserva']); ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/sweet_alert_crud.js"></script>
<script src="../js/filtros_reserva.js"></script>
</body>
</html>
