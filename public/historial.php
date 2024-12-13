<?php
session_start();
include_once '../private/filter_historial.php';

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Ocupaciones</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/historial.css">
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
    <div class="hamburger" id="hamburger-icon">
        &#9776;
    </div>
</div>

    <div class="container">
        <h1>Historial de Ocupaciones</h1>
        <form method="GET" id="filterForm">
            <label for="camarero">Camarero:</label>
            <input type="text" name="camarero" id="camarero" placeholder="Nombre del camarero"
                value="<?php echo isset($_GET['camarero']) ? htmlspecialchars($_GET['camarero']) : ''; ?>">

            <label for="mesa">Mesa:</label>
            <input type="number" name="mesa" id="mesa" placeholder="Número de la mesa"
                value="<?php echo isset($_GET['mesa']) ? htmlspecialchars($_GET['mesa']) : ''; ?>">

            <label for="fecha">Fecha:</label>
            <input type="date" name="fecha" id="fecha"
                value="<?php echo isset($_GET['fecha']) ? htmlspecialchars($_GET['fecha']) : ''; ?>">

            <label for="sala">Sala:</label>
            <select name="sala" id="sala">
                <option value="">Seleccionar Sala</option>
                <?php
                $querySalas = "SELECT id_sala, nombre_sala FROM tbl_sala";
                try {
                    $stmtSalas = $conn->prepare($querySalas);
                    $stmtSalas->execute();
                    $resultSalas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($resultSalas as $row) {
                        $selected = (isset($_GET['sala']) && $_GET['sala'] == $row['id_sala']) ? 'selected' : '';
                        echo "<option value='{$row['id_sala']}' $selected>{$row['nombre_sala']}</option>";
                    }
                } catch (PDOException $e) {
                    echo "<option value=''>Error al cargar las salas</option>";
                }
                ?>
            </select>
            <button type="submit">Filtrar</button>

            <?php if (!empty($_GET['camarero']) || !empty($_GET['mesa']) || !empty($_GET['fecha']) || !empty($_GET['sala'])): ?>
                <a href="historial.php" class="btn" style="background-color: red;">Borrar Filtros</a>
            <?php endif; ?>
        </form>
        <a href="historial.php?action=sala_concurrida" class="btn">Sala Más Concurrida</a>
        <a href="historial.php?action=mesa_concurrida" class="btn margen">Mesa Más Concurrida</a>

        <?php include_once '../private/occupancy_stats.php'; ?>

        <div id="resultados">
            <?php if (!empty($_GET) && $noResultsMessage): ?>
                <p class="red"><?php echo $noResultsMessage; ?></p>
            <?php endif; ?>

            <?php if ($result && count($result) > 0): ?>
                <table>
                    <tr>
                        <th>ID Ocupación</th>
                        <th>Camarero</th>
                        <th>Mesa</th>
                        <th>Fecha Ocupación</th>
                        <th>Fecha Desocupación</th>
                    </tr>
                    <?php foreach ($result as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id_ocupacion']) ?></td>
                            <td><?= htmlspecialchars($row['camarero']) ?></td>
                            <td><?= htmlspecialchars($row['id_mesa']) ?></td>
                            <td><?= htmlspecialchars($row['fecha_hora_ocupacion']) ?></td>
                            <td><?= htmlspecialchars($row['fecha_hora_desocupacion'] ?? 'Sin desocupar') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>

        <div id="form_error" class="red"></div>
    </div>
    <script src="../js/validation_historial.js"></script>
</body>
</html>
