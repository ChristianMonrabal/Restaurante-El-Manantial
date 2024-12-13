<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

include_once '../db/conexion.php';

$query = "SELECT id_sala, nombre_sala FROM tbl_sala";
$stmt = $conn->prepare($query);
$stmt->execute();
$salas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
if ($errors) {
    unset($_SESSION['errors']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Mesa</title>
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
    <h2>Agregar Mesa</h2>
    <form action="../private/insert_mesas.php" method="POST">
        <div class="form-group">
            <label for="sala">Sala</label>
            <select class="form-control <?php echo isset($errors['sala']) ? 'is-invalid' : ''; ?>" id="sala" name="sala">
                <option value="" disabled selected>Seleccionar una sala</option>
                <?php foreach ($salas as $sala): ?>
                    <option value="<?php echo $sala['id_sala']; ?>"><?php echo $sala['nombre_sala']; ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['sala'])): ?>
                <div class="invalid-feedback"><?php echo $errors['sala']; ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="num_sillas">Número de Sillas</label>
            <select class="form-control <?php echo isset($errors['num_sillas']) ? 'is-invalid' : ''; ?>" id="num_sillas" name="num_sillas">
                <option value="" disabled selected>Seleccionar número de sillas</option>
                <option value="2">2 sillas</option>
                <option value="3">3 sillas</option>
                <option value="4">4 sillas</option>
                <option value="5">5 sillas</option>
                <option value="6">6 sillas</option>
                <option value="10">10 sillas</option>
            </select>
            <?php if (isset($errors['num_sillas'])): ?>
                <div class="invalid-feedback"><?php echo $errors['num_sillas']; ?></div>
            <?php endif; ?>
        </div>
        <?php if (isset($_SESSION['mensaje_successful'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['mensaje_successful']; ?>
                <?php unset($_SESSION['mensaje_successful']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['mensaje_error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['mensaje_error']; ?>
                <?php unset($_SESSION['mensaje_error']); ?>
            </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary">Agregar Mesa</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../js/validation_add_mesas.js"></script>
</body>
</html>
