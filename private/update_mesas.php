<?php
include_once '../db/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_mesa = $_POST['id_mesa'];
    $num_sillas = $_POST['num_sillas'];

    try {
        $query = "UPDATE tbl_mesa SET num_sillas_mesa = :num_sillas WHERE id_mesa = :id_mesa";
        $stmt = $conn->prepare($query);
        $stmt->execute(['num_sillas' => $num_sillas, 'id_mesa' => $id_mesa]);

        header("Location: ../public/recursos.php");
        exit();
    } catch (PDOException $e) {
        echo "Error al actualizar la mesa: " . $e->getMessage();
    }
} else {
    header("Location: ../public/recursos.php");
    exit();
}
?>
