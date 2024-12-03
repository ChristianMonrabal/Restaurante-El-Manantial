<?php
include_once '../db/conexion.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sala'])) {
    $sala = $_POST['sala'];
    
    try {
        // Buscar ID de la sala
        $query = "SELECT id_sala FROM tbl_sala WHERE nombre_sala = :nombre_sala";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nombre_sala', $sala, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $id_sala = $result['id_sala'];

            // Buscar mesas asociadas a la sala
            $queryMesas = "SELECT * FROM tbl_mesa WHERE id_sala = :id_sala";
            $stmtMesas = $conn->prepare($queryMesas);
            $stmtMesas->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
            $stmtMesas->execute();
            $mesas = $stmtMesas->fetchAll(PDO::FETCH_ASSOC);

        } else {
            echo "No se ha encontrado ninguna sala con el nombre especificado.";
        }

        // Ver capacidad total de la sala
        $queryVerCapacidad = "SELECT capacidad_total FROM tbl_sala WHERE nombre_sala = :nombre_sala";
        $stmtCapacidad = $conn->prepare($queryVerCapacidad);
        $stmtCapacidad->bindParam(':nombre_sala', $sala, PDO::PARAM_STR);
        $stmtCapacidad->execute();
        $resultCapacidad = $stmtCapacidad->fetch(PDO::FETCH_ASSOC);

        if ($resultCapacidad) {
            echo "<h2 style='text-align: center;'>Capacidad total de la sala: " . htmlspecialchars($resultCapacidad['capacidad_total']) . "</h2>";
        } else {
            echo "No se encontró la sala especificada.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
