<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['tipo_usuario'] !== 'administrador') {
    header("Location: ../index.php");
    exit();
}

include_once '../db/conexion.php';

$query = "SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'tbl_usuario' AND COLUMN_NAME = 'tipo_usuario'";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$enumValues = explode("','", str_replace(["enum('", "')"], '', $result['COLUMN_TYPE']));

$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$tipo = isset($_SESSION['tipo']) ? $_SESSION['tipo'] : '';
$nombreError = isset($_SESSION['nombreError']) ? $_SESSION['nombreError'] : '';
$emailError = isset($_SESSION['emailError']) ? $_SESSION['emailError'] : '';
$tipoError = isset($_SESSION['tipoError']) ? $_SESSION['tipoError'] : '';
$passwordError = isset($_SESSION['passwordError']) ? $_SESSION['passwordError'] : '';

unset($_SESSION['nombre']);
unset($_SESSION['email']);
unset($_SESSION['tipo']);
unset($_SESSION['nombreError']);
unset($_SESSION['emailError']);
unset($_SESSION['tipoError']);
unset($_SESSION['passwordError']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/usuarios.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Agregar Usuario</h2>
    <form action="../private/add_usuario.php" method="POST" id="formularioAgregarUsuario">
        <div class="form-group">
            <label for="nombre_usuario">Nombre de Usuario</label>
            <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?php echo htmlspecialchars($nombre); ?>">
            <?php if ($nombreError): ?>
                <small class="text-danger"><?php echo $nombreError; ?></small>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="email_usuario">Correo Electrónico</label>
            <input type="email" class="form-control" id="email_usuario" name="email_usuario" value="<?php echo htmlspecialchars($email); ?>">
            <?php if ($emailError): ?>
                <small class="text-danger"><?php echo $emailError; ?></small>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="tipo_usuario">Tipo de Usuario</label>
            <select class="form-control" id="tipo_usuario" name="tipo_usuario">
                <option value="">Seleccione un tipo</option>
                <?php foreach ($enumValues as $tipoOption): ?>
                    <option value="<?php echo $tipoOption; ?>" <?php echo ($tipoOption === $tipo) ? 'selected' : ''; ?>>
                        <?php echo ucfirst($tipoOption); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if ($tipoError): ?>
                <small class="text-danger"><?php echo $tipoError; ?></small>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="password_usuario">Contraseña</label>
            <input type="password" class="form-control" id="password_usuario" name="password_usuario">
            <?php if ($passwordError): ?>
                <small class="text-danger"><?php echo $passwordError; ?></small>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Agregar</button>
        <a href="./usuarios.php" class="btn btn-secondary btn-block">Cancelar</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../js/validation_add_usuario.js"></script>
</body>
</html>

<?php
unset($_SESSION['nombreError']);
unset($_SESSION['emailError']);
unset($_SESSION['tipoError']);
unset($_SESSION['passwordError']);
?>