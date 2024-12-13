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

$errores = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_reserva = $_POST['nombre_reserva'];
    $cantidad_personas = $_POST['cantidad_personas'];
    $id_sala = $_POST['id_sala'];
    $id_franja = $_POST['id_franja'];
    $fecha_reserva = $_POST['fecha_reserva'];

    if (empty($nombre_reserva) || empty($cantidad_personas) || empty($id_sala) || empty($fecha_reserva) || empty($id_franja)) {
        $_SESSION['mensaje_error_campos_vacios'] = "Todos los campos son obligatorios.";
        $_SESSION['nombre_reserva'] = $nombre_reserva;
        $_SESSION['cantidad_personas'] = $cantidad_personas;
        $_SESSION['id_sala'] = $id_sala;
        $_SESSION['id_franja'] = $id_franja;
        $_SESSION['fecha_reserva'] = $fecha_reserva;
        header("Location: ../public/add_reservas.php");
        exit();
    }
    

    if (!empty($errores)) {
        $_SESSION['mensaje_error_campos_vacios'] = implode("<br>", $errores);
        $_SESSION['nombre_reserva'] = $nombre_reserva;
        $_SESSION['cantidad_personas'] = $cantidad_personas;
        $_SESSION['id_sala'] = $id_sala;
        $_SESSION['fecha_reserva'] = $fecha_reserva;
        $_SESSION['id_franja'] = $id_franja;
        header("Location: ../public/add_reservas.php");
        exit();
    }

    if ($cantidad_personas < 2 || $cantidad_personas > 10) {
        $_SESSION['mensaje_error_rango'] = "La cantidad de personas debe estar entre 2 y 10.";
        $_SESSION['nombre_reserva'] = $nombre_reserva;
        $_SESSION['cantidad_personas'] = $cantidad_personas;
        $_SESSION['id_sala'] = $id_sala;
        $_SESSION['fecha_reserva'] = $fecha_reserva;
        $_SESSION['id_franja'] = $id_franja;
        header("Location: ../public/add_reservas.php");
        exit();
    }

    $query_check_reserva = "SELECT id_mesa FROM tbl_reserva 
                            WHERE id_sala = :id_sala 
                            AND fecha_reserva = :fecha_reserva 
                            AND id_franja = :id_franja";
    $stmt_check_reserva = $conn->prepare($query_check_reserva);
    $stmt_check_reserva->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmt_check_reserva->bindParam(':fecha_reserva', $fecha_reserva, PDO::PARAM_STR);
    $stmt_check_reserva->bindParam(':id_franja', $id_franja, PDO::PARAM_INT);
    $stmt_check_reserva->execute();
    $reservas_existentes = $stmt_check_reserva->fetchAll(PDO::FETCH_ASSOC);

    $query_mesa = "SELECT id_mesa, num_sillas_mesa 
                    FROM tbl_mesa 
                    WHERE id_sala = :id_sala 
                    AND estado_mesa = 'libre' 
                    AND num_sillas_mesa = :cantidad_personas";
    $stmt_mesa = $conn->prepare($query_mesa);
    $stmt_mesa->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmt_mesa->bindParam(':cantidad_personas', $cantidad_personas, PDO::PARAM_INT);
    $stmt_mesa->execute();
    $mesas = $stmt_mesa->fetchAll(PDO::FETCH_ASSOC);

    $mesa_reservada = null;

    foreach ($mesas as $mesa) {
        $id_mesa = $mesa['id_mesa'];
        $mesa_esta_reservada = false;
        
        foreach ($reservas_existentes as $reserva) {
            if ($reserva['id_mesa'] == $id_mesa) {
                $mesa_esta_reservada = true;
                break;
            }
        }

        if (!$mesa_esta_reservada) {
            $mesa_reservada = $id_mesa;
            break;
        }
    }

    if ($mesa_reservada) {
        $query_reserva = "INSERT INTO tbl_reserva (nombre_reserva, cantidad_personas, id_sala, id_franja, fecha_reserva, id_mesa, id_usuario)
                        VALUES (:nombre_reserva, :cantidad_personas, :id_sala, :id_franja, :fecha_reserva, :id_mesa, :id_usuario)";
        $stmt_reserva = $conn->prepare($query_reserva);
        $stmt_reserva->bindParam(':nombre_reserva', $nombre_reserva, PDO::PARAM_STR);
        $stmt_reserva->bindParam(':cantidad_personas', $cantidad_personas, PDO::PARAM_INT);
        $stmt_reserva->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        $stmt_reserva->bindParam(':id_franja', $id_franja, PDO::PARAM_INT);
        $stmt_reserva->bindParam(':fecha_reserva', $fecha_reserva, PDO::PARAM_STR);
        $stmt_reserva->bindParam(':id_mesa', $mesa_reservada, PDO::PARAM_INT);
        $stmt_reserva->bindParam(':id_usuario', $usuario_id, PDO::PARAM_INT);
        $stmt_reserva->execute();

        $_SESSION['mensaje_successful'] = "Reserva realizada exitosamente.";

        unset($_SESSION['nombre_reserva']);
        unset($_SESSION['cantidad_personas']);
        unset($_SESSION['id_sala']);
        unset($_SESSION['fecha_reserva']);
        unset($_SESSION['id_franja']);

        header("Location: ../public/add_reservas.php");
        exit();
    } else {
        $_SESSION['mensaje_error_mesas'] = "No hay mesas disponibles con el número exacto de sillas para esta cantidad de personas.";
        header("Location: ../public/add_reservas.php");
        exit();
    }
}
?>
