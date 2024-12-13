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

if (isset($_GET['id_reserva'])) {
    $id_reserva = $_GET['id_reserva'];

    $query_reserva = "SELECT r.id_reserva, r.nombre_reserva, r.cantidad_personas, r.id_sala, r.fecha_reserva, r.id_franja, u.nombre_usuario
                        FROM tbl_reserva r
                        JOIN tbl_usuario u ON r.id_usuario = u.id_usuario
                        WHERE r.id_reserva = :id_reserva";
    $stmt_reserva = $conn->prepare($query_reserva);
    $stmt_reserva->bindParam(':id_reserva', $id_reserva, PDO::PARAM_INT);
    $stmt_reserva->execute();
    $reserva = $stmt_reserva->fetch(PDO::FETCH_ASSOC);

    if (!$reserva) {
        header("Location: ./reservas.php");
        exit();
    }
} else {
    header("Location: ./reservas.php");
    exit();
}

$query_salas = "SELECT id_sala, nombre_sala FROM tbl_sala";
$stmt_salas = $conn->prepare($query_salas);
$stmt_salas->execute();
$result_salas = $stmt_salas->fetchAll(PDO::FETCH_ASSOC);

$query_franjas = "SELECT id_franja, franja_horaria FROM tbl_franja_horaria";
$stmt_franjas = $conn->prepare($query_franjas);
$stmt_franjas->execute();
$result_franjas = $stmt_franjas->fetchAll(PDO::FETCH_ASSOC);

$opciones_cantidad_personas = ['2', '3', '4', '5', '6', '10'];

$ocupadas = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_reserva = $_POST['fecha_reserva'];

    $query_ocupadas = "SELECT DISTINCT id_franja FROM tbl_reserva WHERE fecha_reserva = :fecha_reserva";
    $stmt_ocupadas = $conn->prepare($query_ocupadas);
    $stmt_ocupadas->bindParam(':fecha_reserva', $fecha_reserva, PDO::PARAM_STR);
    $stmt_ocupadas->execute();
    $ocupadas = $stmt_ocupadas->fetchAll(PDO::FETCH_COLUMN, 0);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/usuarios.css">
    <link rel="stylesheet" href="../css/header.css">
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
        <h2 class="text-center">Editar reserva</h2>
        <form method="POST" action="../private/update_reserva.php">
            <input type="hidden" name="id_reserva" value="<?php echo $reserva['id_reserva']; ?>">

            <div class="form-group mb-4">
                <label for="nombre_reserva">Nombre de la reserva:</label>
                <input type="text" class="form-control" id="nombre_reserva" name="nombre_reserva" value="<?php echo htmlspecialchars($reserva['nombre_reserva']); ?>">
            </div>

            <div class="form-group mb-4">
                <label for="cantidad_personas">Cantidad de personas:</label>
                <select class="form-control" id="cantidad_personas" name="cantidad_personas">
                    <option value="" disabled selected>Selecciona una opción</option>
                    <?php for ($i = 2; $i <= 10; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo $reserva['cantidad_personas'] == $i ? 'selected' : ''; ?>>
                            <?php echo $i; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>


            <div class="form-group mb-4">
                <label for="id_sala">Seleccionar sala:</label>
                <select class="form-control" id="id_sala" name="id_sala">
                    <option value="" disabled>Selecciona una opción</option>
                    <?php foreach ($result_salas as $sala): ?>
                        <option value="<?php echo $sala['id_sala']; ?>" <?php echo $reserva['id_sala'] == $sala['id_sala'] ? 'selected' : ''; ?>>
                            <?php echo $sala['nombre_sala']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="fecha_reserva">Fecha de la reserva:</label>
                <input type="date" class="form-control" id="fecha_reserva" name="fecha_reserva" min="<?php echo date('Y-m-d'); ?>" value="<?php echo $reserva['fecha_reserva']; ?>">
            </div>

            <div class="form-group mb-4">
                <label for="id_franja">Seleccionar franja horaria:</label>
                <select class="form-control" id="id_franja" name="id_franja">
                    <option value="" disabled>Selecciona una opción</option>
                    <?php foreach ($result_franjas as $franja): ?>
                        <?php if (!in_array($franja['id_franja'], $ocupadas)): ?>
                            <option value="<?php echo $franja['id_franja']; ?>" <?php echo $reserva['id_franja'] == $franja['id_franja'] ? 'selected' : ''; ?>>
                                <?php echo $franja['franja_horaria']; ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php
                if (isset($_SESSION['mensaje_successful'])) {
                    echo '<div class="success">' . $_SESSION['mensaje_successful'] . '</div>';
                    unset($_SESSION['mensaje_successful']);
                }
                ?>
            <?php
                if (isset($_SESSION['mensaje'])) {
                    echo '<div class="error">' . $_SESSION['mensaje'] . '</div>';
                    unset($_SESSION['mensaje']);
                }
                ?>
            <button type="submit" class="btn btn-primary mt-3">Actualizar Reserva</button>
        </form>
    </div>

</body>
</html>
