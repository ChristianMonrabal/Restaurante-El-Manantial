<?php
session_start();
include_once '../db/conexion.php';

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

// Consulta para obtener las reservas con la información relacionada de otras tablas, incluyendo el id_reserva
$query_reservas = "SELECT r.id_reserva, r.nombre_reserva, u.nombre_usuario, m.num_sillas_mesa, r.fecha_reserva, f.franja_horaria 
                    FROM tbl_reserva r
                    JOIN tbl_usuario u ON r.id_usuario = u.id_usuario
                    JOIN tbl_mesa m ON r.id_mesa = m.id_mesa
                    JOIN tbl_franja_horaria f ON r.id_franja = f.id_franja
                    WHERE r.id_usuario = :usuario_id
                    ORDER BY r.fecha_reserva DESC";
$stmt_reservas = $conn->prepare($query_reservas);
$stmt_reservas->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
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
    <div class="hamburger" id="hamburger-icon">
        &#9776;
    </div>
</div>
<div class="mobile-nav" id="mobile-nav">
    <a href="./historial.php">Historial</a>
    <a href="#"><?php echo $_SESSION['nombre_usuario']; ?></a>
    <a href="../private/logout.php">Cerrar Sesión</a>
</div>

<div class="container mt-5">
    <h1 class="text-center">Gestión de Reservas</h1>
    <table class="table table-bordered table-striped" style="table-layout: fixed; width: 80%; margin: 0 auto;">
    <thead class="table-dark">
        <tr>
            <th>Nombre de la Reserva</th>
            <th>Usuario</th>
            <th>Mesa</th>
            <th>Fecha</th>
            <th>Horario</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result_reservas as $reserva): ?>
            <tr>
                <td><?php echo htmlspecialchars($reserva['nombre_reserva']); ?></td>
                <td><?php echo htmlspecialchars($reserva['nombre_usuario']); ?></td>
                <td><?php echo htmlspecialchars($reserva['num_sillas_mesa']); ?></td>
                <td><?php echo htmlspecialchars($reserva['fecha_reserva']); ?></td>
                <td><?php echo htmlspecialchars($reserva['franja_horaria']); ?></td>
                <td>
                    <a href="edit_reserva.php?id_reserva=<?php echo htmlspecialchars($reserva['id_reserva']); ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="delete_reserva.php?id_reserva=<?php echo htmlspecialchars($reserva['id_reserva']); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
        </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
