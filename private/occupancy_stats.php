<?php
// Incluye el archivo de conexión a la base de datos.
include_once '../db/conexion.php';

// Verifica si se ha recibido un parámetro 'action' en la solicitud.
if (isset($_GET['action'])) {

    // Si el valor de 'action' es 'sala_concurrida', ejecuta el siguiente bloque.
    if ($_GET['action'] === 'sala_concurrida') {
        // Consulta para obtener la sala más concurrida, agrupando por sala y contando las ocupaciones.
        $query = "SELECT s.nombre_sala, COUNT(DISTINCT o.id_ocupacion) AS total_ocupaciones
                    FROM tbl_ocupacion o
                    JOIN tbl_mesa m ON o.id_mesa = m.id_mesa
                    JOIN tbl_sala s ON m.id_sala = s.id_sala
                    GROUP BY s.id_sala
                    ORDER BY total_ocupaciones DESC
                    LIMIT 1"; // Devolverá solo una fila

        // Ejecuta la consulta y almacena el resultado.
        $result = mysqli_query($conn, $query);
        // Comprueba si hay resultados y muestra la sala más concurrida.
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Muestra el nombre de la sala más concurrida y el número total de ocupaciones.
            echo "<p class='info'>La sala más concurrida es: <strong>{$row['nombre_sala']}</strong> con {$row['total_ocupaciones']} ocupaciones.</p>";
        } else {
            // Muestra un mensaje si no hay datos disponibles.
            echo "<p class='info'>No hay datos disponibles para la sala más concurrida.</p>";
        }
    }

    // Si el valor de 'action' es 'mesa_concurrida', ejecuta el siguiente bloque.
    if ($_GET['action'] === 'mesa_concurrida') {
        // Consulta para obtener la mesa más concurrida, agrupando por mesa y contando las ocupaciones.
        $query = "SELECT m.id_mesa, COUNT(DISTINCT o.id_ocupacion) AS total_ocupaciones
                    FROM tbl_ocupacion o
                    JOIN tbl_mesa m ON o.id_mesa = m.id_mesa
                    GROUP BY m.id_mesa
                    ORDER BY total_ocupaciones DESC
                    LIMIT 1";

        // Ejecuta la consulta y almacena el resultado.
        $result = mysqli_query($conn, $query);
        // Comprueba si hay resultados y muestra la mesa más concurrida.
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Muestra el ID de la mesa más concurrida y el número total de ocupaciones.
            echo "<p class='info'>La mesa más concurrida es: <strong>Mesa {$row['id_mesa']}</strong> con {$row['total_ocupaciones']} ocupaciones.</p>";
        } else {
            // Muestra un mensaje si no hay datos disponibles.
            echo "<p class='info'>No hay datos disponibles para la mesa más concurrida.</p>";
        }
    }
}
?>