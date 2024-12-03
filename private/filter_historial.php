<?php
include_once '../db/conexion.php';

$noResultsMessage = '';
$filters = [];
$params = [];

if (!empty($_GET['camarero'])) {
    $filters[] = 'u.nombre_usuario LIKE :camarero';
    $params[':camarero'] = '%' . $_GET['camarero'] . '%';
}

if (!empty($_GET['mesa'])) {
    $filters[] = 'm.id_mesa = :mesa';
    $params[':mesa'] = $_GET['mesa'];
}

if (!empty($_GET['fecha'])) {
    $filters[] = 'DATE(o.fecha_hora_ocupacion) = :fecha';
    $params[':fecha'] = $_GET['fecha'];
}

if (!empty($_GET['sala'])) {
    $filters[] = 's.id_sala = :sala';
    $params[':sala'] = $_GET['sala'];
}

if (!empty($filters)) {
    $query = "SELECT 
                o.id_ocupacion, 
                u.nombre_usuario AS camarero, 
                m.id_mesa, 
                m.estado_mesa, 
                s.nombre_sala, 
                o.fecha_hora_ocupacion, 
                o.fecha_hora_desocupacion
                FROM tbl_ocupacion o
                JOIN tbl_mesa m ON o.id_mesa = m.id_mesa
                JOIN tbl_sala s ON m.id_sala = s.id_sala
                JOIN tbl_usuario u ON o.id_usuario = u.id_usuario";

    $query .= ' WHERE ' . implode(' AND ', $filters);

    try {
        $stmt = $conn->prepare($query);
        $stmt->execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($filters) && empty($result)) {
            $noResultsMessage = "No hay resultados para los filtros seleccionados.";
        }
    } catch (PDOException $e) {
        die("Error al ejecutar la consulta: " . $e->getMessage());
    }
} else {
    $result = null;
}
?>
