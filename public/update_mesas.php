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

if (!isset($_GET['id'])) {
    header("Location: ./recursos.php");
    exit();
}

$id_mesa = $_GET['id'];

try {
    $query = "SELECT m.id_mesa, m.num_sillas_mesa, s.nombre_sala 
                FROM tbl_mesa m
                JOIN tbl_sala s ON m.id_sala = s.id_sala
                WHERE m.id_mesa = :id_mesa";
    $stmt = $conn->prepare($query);
    $stmt->execute(['id_mesa' => $id_mesa]);
    $mesa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mesa) {
        header("Location: ./recursos.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Error al obtener datos de la mesa: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mesa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/usuarios.css">
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
    <p class="text-center mb-4">Estás editando la mesa Nº <?php echo $mesa['id_mesa']; ?> de la sala "<?php echo $mesa['nombre_sala']; ?>"</p>

    <form action="../private/update_mesas.php" method="post" class="p-4 border rounded">
        <input type="hidden" name="id_mesa" value="<?php echo $mesa['id_mesa']; ?>">
        <div class="mb-3">
            <label for="num_sillas" class="form-label">Número de Sillas:</label>
            <select name="num_sillas" id="num_sillas" class="form-select">
                <?php
                $opciones = [2, 3, 4, 5, 6, 10];
                foreach ($opciones as $opcion) {
                    $selected = ($mesa['num_sillas_mesa'] == $opcion) ? 'selected' : '';
                    echo "<option value=\"$opcion\" $selected>$opcion</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <a href="./recursos.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
