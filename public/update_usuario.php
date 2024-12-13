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
include "../private/update_usuario.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/usuarios.css">
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
    <div class="container mt-5">
        <h2>Editar Usuario</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?php echo htmlspecialchars($nombre_usuario); ?>">
                <small class="text-danger"><?php echo isset($errors['nombre_usuario']) ? $errors['nombre_usuario'] : ''; ?></small>
            </div>
            <div class="form-group">
                <label for="email_usuario">Correo Electrónico</label>
                <input type="email" class="form-control" id="email_usuario" name="email_usuario" value="<?php echo htmlspecialchars($email_usuario); ?>">
                <small class="text-danger"><?php echo isset($errors['email_usuario']) ? $errors['email_usuario'] : ''; ?></small>
            </div>
            <div class="form-group">
                <label for="tipo_usuario">Tipo de Usuario</label>
                <select class="form-control" id="tipo_usuario" name="tipo_usuario">
                    <option value="camarero" <?php echo $tipo_usuario === 'camarero' ? 'selected' : ''; ?>>Camarero</option>
                    <option value="gerente" <?php echo $tipo_usuario === 'gerente' ? 'selected' : ''; ?>>Gerente</option>
                    <option value="mantenimiento" <?php echo $tipo_usuario === 'mantenimiento' ? 'selected' : ''; ?>>Mantenimiento</option>
                    <option value="administrador" <?php echo $tipo_usuario === 'administrador' ? 'selected' : ''; ?>>Administrador</option>
                </select>
                <small class="text-danger"><?php echo isset($errors['tipo_usuario']) ? $errors['tipo_usuario'] : ''; ?></small>
            </div>
            <div class="form-group">
                <label for="password_usuario">Nueva Contraseña (opcional)</label>
                <input type="password" class="form-control" id="password_usuario" name="password_usuario">
                <small class="text-danger"><?php echo isset($errors['password_usuario']) ? $errors['password_usuario'] : ''; ?></small>
            </div>
            <?php if (!empty($mensaje)): ?>
                <div class="alert <?php echo ($mensaje === "Usuario actualizado correctamente.") ? 'alert-success' : 'alert-danger'; ?>">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="usuarios.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/validation_update_usuario.js"></script>
    <script src="../js/logout.js"></script>
</body>
</html>
