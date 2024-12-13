<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

$tiposSala = ['terraza', 'comedor', 'privada'];
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
if ($errors) {
    unset($_SESSION['errors']);
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} else {
    $message = null;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Sala</title>
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
        <a href="./reservas.php" class="right-link">Reservas</a>
        <?php if ($_SESSION['tipo_usuario'] === 'administrador'): ?>
            <a href="./recursos.php" class="right-link">Recursos</a>
            <a href="./usuarios.php" class="right-link">Usuarios</a>
        <?php endif; ?>
        <div class="user-info">
            <a href="../private/logout.php" class="logout-icon">
                <i class="fas fa-sign-out-alt" style="font-size: 20px; color: #000; margin-right: 10px;"></i>
            </a>
            <span><?php echo $_SESSION['nombre_usuario']; ?></span>
        </div>
    </div>

    <div class="container mt-5">
        <h3 class="mt-5">Agregar Nueva Sala</h3>
        <form action="../private/insert_salas.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="tipo_sala">Tipo de Sala</label>
                <select class="form-control <?php echo isset($errors['tipo_sala']) ? 'is-invalid' : ''; ?>" id="tipo_sala" name="tipo_sala">
                    <option value="" disabled selected>Seleccionar tipo de sala</option>
                    <?php foreach ($tiposSala as $tipo): ?>
                        <option value="<?php echo $tipo; ?>" <?php echo isset($_POST['tipo_sala']) && $_POST['tipo_sala'] == $tipo ? 'selected' : ''; ?>><?php echo ucfirst($tipo); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['tipo_sala'])): ?>
                    <small class="text-danger"><?php echo $errors['tipo_sala']; ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="nombre_sala">Nombre de la Sala</label>
                <input type="text" class="form-control <?php echo isset($errors['nombre_sala']) ? 'is-invalid' : ''; ?>" id="nombre_sala" name="nombre_sala" placeholder="Nombre de la sala" value="<?php echo isset($_POST['nombre_sala']) ? $_POST['nombre_sala'] : ''; ?>">
                <?php if (isset($errors['nombre_sala'])): ?>
                    <small class="text-danger"><?php echo $errors['nombre_sala']; ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="capacidad_total">Capacidad Total</label>
                <input type="number" class="form-control <?php echo isset($errors['capacidad_total']) ? 'is-invalid' : ''; ?>" id="capacidad_total" name="capacidad_total" placeholder="Capacidad total" value="<?php echo isset($_POST['capacidad_total']) ? $_POST['capacidad_total'] : ''; ?>">
                <?php if (isset($errors['capacidad_total'])): ?>
                    <small class="text-danger"><?php echo $errors['capacidad_total']; ?></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="imagen_sala">Imagen de la Sala</label>
                <input type="file" class="form-control <?php echo isset($errors['imagen_sala']) ? 'is-invalid' : ''; ?>" id="imagen_sala" name="imagen_sala">
                <?php if (isset($errors['imagen_sala'])): ?>
                    <small class="text-danger"><?php echo $errors['imagen_sala']; ?></small>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-success" name="create_sala">Crear Sala</button>
        </form>

        <?php if ($message): ?>
            <div class="alert alert-success mt-3"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../js/validation_add_salas.js"></script>
</body>
</html>
