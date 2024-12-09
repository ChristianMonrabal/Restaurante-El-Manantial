<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

include_once '../db/conexion.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sala = $_POST['sala'];
    $num_sillas = $_POST['num_sillas'];

    if (empty($sala)) {
        $errors['sala'] = "Por favor, selecciona una sala.";
    }
    if (empty($num_sillas)) {
        $errors['num_sillas'] = "Por favor, selecciona el número de sillas.";
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        header("Location: ../public/add_mesas.php");
        exit();
    }

    $query = "INSERT INTO tbl_mesa (id_sala, num_sillas_mesa) VALUES (:sala, :num_sillas)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':sala', $sala, PDO::PARAM_INT);
    $stmt->bindParam(':num_sillas', $num_sillas, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: ../public/recursos.php");
        exit();
    } else {
        header("Location: ../public/recursos.php");
        exit();
    }
}
