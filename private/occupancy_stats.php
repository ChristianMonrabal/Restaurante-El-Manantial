<?php
// Incluye el archivo de conexión a la base de datos.
include_once '../db/conexion.php';

// Verifica si se ha recibido un parámetro 'action' en la solicitud.
if (isset($_GET['action'])) {
    // Inicia una transacción.
    mysqli_begin_transaction($conn);

    try {
        // Inicializa la consulta y mensaje variables
        $query = '';
        $message = '';

        // Si el valor de 'action' es 'sala_concurrida', ajusta la consulta y el mensaje.
        if ($_GET['action'] === 'sala_concurrida') {
            $query = " SELECT s.nombre_sala, COUNT(DISTINCT o.id_ocupacion) AS total_ocupaciones
                FROM tbl_ocupacion o
                JOIN tbl_mesa m ON o.id_mesa = m.id_mesa
                JOIN tbl_sala s ON m.id_sala = s.id_sala
                GROUP BY s.id_sala
                ORDER BY total_ocupaciones DESC
                LIMIT 1";
            $message = 'La sala más concurrida es:';
        }

        // Si el valor de 'action' es 'mesa_concurrida', ajusta la consulta y el mensaje.
        if ($_GET['action'] === 'mesa_concurrida') {
            $query = " SELECT m.id_mesa, COUNT(DISTINCT o.id_ocupacion) AS total_ocupaciones
                FROM tbl_ocupacion o
                JOIN tbl_mesa m ON o.id_mesa = m.id_mesa
                GROUP BY m.id_mesa
                ORDER BY total_ocupaciones DESC
                LIMIT 1";
            $message = 'La mesa más concurrida es:';
        }

        // Si hay una consulta válida, la ejecutamos.
        if ($query) {
            // Prepara la consulta de forma segura.
            if ($stmt = mysqli_prepare($conn, $query)) {
                // Ejecuta la consulta preparada.
                mysqli_stmt_execute($stmt);
                // Obtiene el resultado.
                $result = mysqli_stmt_get_result($stmt);

                // Verifica si hay resultados y muestra la información correspondiente.
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    echo "<p class='info'>{$message} <strong>{$row[0]}</strong> con {$row['total_ocupaciones']} ocupaciones.</p>";
                } else {
                    echo "<p class='info'>No hay datos disponibles para la {$message}.</p>";
                }

                // Libera el resultado de la consulta.
                mysqli_free_result($result);
                // Cierra la declaración preparada.
                mysqli_stmt_close($stmt);
            } else {
                throw new Exception("Error al preparar la consulta.");
            }
        }

        // Si todo fue bien, realiza un commit de la transacción.
        mysqli_commit($conn);
    } catch (Exception $e) {
        // Si ocurre un error, revierte la transacción.
        mysqli_rollback($conn);
        // Muestra un mensaje de error.
        echo "<p class='error'>Error al procesar la solicitud: {$e->getMessage()}</p>";
    }
}
?>
