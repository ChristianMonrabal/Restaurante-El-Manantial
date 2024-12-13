<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

include '../db/conexion.php';

$errors = [];
$message = null;

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No se proporcionó un ID válido para la sala.");
}

$id_sala = (int)$_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM tbl_sala WHERE id_sala = :id_sala");
    $stmt->execute(['id_sala' => $id_sala]);
    $sala = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sala) {
        die("Error: La sala no existe.");
    }

    $stmt_tipos = $conn->query("SELECT DISTINCT tipo_sala FROM tbl_sala");
    $tiposSala = $stmt_tipos->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    die("Error al consultar los datos: " . $e->getMessage());
}

if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sala</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/usuarios.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
</head>
<body>
<div class="navbar">
    <a href="../index.php">
        <img src="../img/icon.png" class="icon">
    </a>
    <a href="./historial.php" class="right-link">Historial</a>
    <a href="./recursos.php" class="right-link">Recursos</a>
    <a href="./usuarios.php" class="right-link">Usuarios</a>
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

<div class="container mt-5">
    <h3 class="mt-5">Editar Sala</h3>
    <form action="../private/update_salas.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_sala" value="<?php echo htmlspecialchars($sala['id_sala']); ?>">

        <div class="form-group">
            <label for="tipo_sala">Tipo de Sala</label>
            <select class="form-control" id="tipo_sala" name="tipo_sala">
                <?php foreach ($tiposSala as $tipo): ?>
                    <option value="<?php echo htmlspecialchars($tipo); ?>" 
                            <?php echo (isset($form_data['tipo_sala']) && $form_data['tipo_sala'] === $tipo) ? 'selected' : (($sala['tipo_sala'] === $tipo) ? 'selected' : ''); ?>>
                        <?php echo ucfirst($tipo); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="nombre_sala">Nombre de la Sala</label>
            <input type="text" class="form-control" id="nombre_sala" name="nombre_sala" value="<?php echo isset($form_data['nombre_sala']) ? htmlspecialchars($form_data['nombre_sala']) : htmlspecialchars($sala['nombre_sala']); ?>" >
        </div>

        <div class="form-group">
            <label for="capacidad_total">Capacidad Total</label>
            <input type="number" class="form-control" id="capacidad_total" name="capacidad_total" value="<?php echo isset($form_data['capacidad_total']) ? htmlspecialchars($form_data['capacidad_total']) : htmlspecialchars($sala['capacidad_total']); ?>" >
        </div>

        <div class="form-group">
                <label>Ruta de la imagen actual: </label>
                <input type="text" class="form-control" value="../img/salas/<?php echo htmlspecialchars($sala['imagen_sala']); ?>" readonly>
            <input type="file" class="form-control" id="imagen_sala" name="imagen_sala">
        </div>

        <?php if ($message): ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <p><?php echo $errors[0]; ?></p>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="./recursos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../js/validation_edit_salas.js"></script>
</body>
</html>