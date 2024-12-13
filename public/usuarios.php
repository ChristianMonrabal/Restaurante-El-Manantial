<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

include_once '../db/conexion.php';

$query = "SELECT * FROM tbl_usuario ORDER BY id_usuario DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/usuarios.css">
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
        <a href="add_usuario.php" class="add-user-button">Agregar usuario</a>
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
    <h1 class="text-center">Gestión de Usuarios</h1>
    <div class="row mb-3">
        <div class="col-md-5">
            <input type="text" id="filtroUsuario" class="form-control" placeholder="Buscar por usuario">
        </div>
        <div class="col-md-5">
            <select id="filtroPuesto" class="form-control">
                <option value="">Seleccionar puesto</option>
                <option value="camarero">Camarero</option>
                <option value="gerente">Gerente</option>
                <option value="mantenimiento">Mantenimiento</option>
                <option value="administrador">Administrador</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="borrarFiltros" class="btn btn-outline-danger btn-sm w-100" style="display: none;">Borrar Filtros</button>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>Usuario</th>
                <th>Correo electrónico</th>
                <th>Puesto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="tablaUsuarios">
            <?php if (count($usuarios) > 0): ?>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario['nombre_usuario']; ?></td>
                    <td><?php echo $usuario['email_usuario']; ?></td>
                    <td><?php echo ucfirst($usuario['tipo_usuario']); ?></td>
                    <td>
                    <a href="update_usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-warning btn-sm">Editar</a>
                    <button class="btn btn-danger btn-sm" onclick="confirmarEliminarUsuario(<?php echo $usuario['id_usuario']; ?>)">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No se encontraron resultados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div id="mensajeNoResultados" class="text-center" style="display: none;">
        <p>No se encontraron usuarios con los filtros aplicados.</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/sweet_alert_crud.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../js/filtros_usuarios.js"></script>
</script>
</body>
</html>
