<?php
// Incluye el archivo de conexión a la base de datos.
include_once '../db/conexion.php';

// Comprueba si la sesión está iniciada y si el usuario ha iniciado sesión.
// Si no está iniciada o la variable 'loggedin' no es verdadera, redirige al usuario a la página de inicio de sesión.
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}

// Comprueba si la solicitud es de tipo POST y si se ha enviado un valor para 'sala'.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sala'])) {
    // Obtiene el valor de 'sala' desde el formulario enviado.
    $sala = $_POST['sala'];

    try {
        // Consulta para buscar el ID de la sala basada en su nombre.
        $query = "SELECT id_sala FROM tbl_sala WHERE nombre_sala = ?";
        // Prepara la consulta.
        $stmt = mysqli_prepare($conn, $query);
        // Vincula el parámetro a la consulta preparada (tipo string).
        mysqli_stmt_bind_param($stmt, "s", $sala);
        // Ejecuta la consulta.
        mysqli_stmt_execute($stmt);
        // Obtiene el resultado de la consulta.
        $result = mysqli_stmt_get_result($stmt);

        // Si se encuentra una fila (sala con el nombre proporcionado).
        if ($row = mysqli_fetch_assoc($result)) {
            // Almacena el ID de la sala.
            $id_sala = $row['id_sala'];

            // Consulta para seleccionar todas las mesas asociadas con el ID de la sala.
            $queryMesas = "SELECT * FROM tbl_mesa WHERE id_sala = ?";
            // Prepara la consulta.
            $stmtMesas = mysqli_prepare($conn, $queryMesas);
            // Vincula el parámetro (ID de la sala) a la consulta (tipo entero).
            mysqli_stmt_bind_param($stmtMesas, "i", $id_sala);
            // Ejecuta la consulta.
            mysqli_stmt_execute($stmtMesas);
            // Obtiene el resultado de la consulta.
            $resultMesas = mysqli_stmt_get_result($stmtMesas);

            // Inicializa un array para almacenar las mesas.
            $mesas = [];
            // Recorre cada fila del resultado y agrega cada mesa al array.
            while ($mesa = mysqli_fetch_assoc($resultMesas)) {
                $mesas[] = $mesa;
            }

            // Cierra la declaración preparada.
            mysqli_stmt_close($stmtMesas);
        } else {
            // Muestra un mensaje si no se encuentra ninguna sala con el nombre especificado.
            echo "No se ha encontrado ninguna sala con el nombre especificado.";
        }

        // Consulta para obtener la capacidad total de la sala basada en su nombre.
        $queryVerCapacidad = "SELECT capacidad_total FROM tbl_sala WHERE nombre_sala = ?";
        // Prepara la consulta.
        $stmtVerCapacidad = mysqli_prepare($conn, $queryVerCapacidad);
        // Vincula el parámetro (nombre de la sala) a la consulta (tipo string).
        mysqli_stmt_bind_param($stmtVerCapacidad, "s", $sala);
        // Ejecuta la consulta.
        mysqli_stmt_execute($stmtVerCapacidad);
        // Obtiene el resultado de la consulta.
        $resultCapacidad = mysqli_stmt_get_result($stmtVerCapacidad);

        // Si se encuentra una fila (capacidad total de la sala).
        if ($row = mysqli_fetch_assoc($resultCapacidad)) {
            // Muestra la capacidad total de la sala en un encabezado con estilo centrado.
            echo "<h2 style='text-align: center;'>Capacidad total de la sala: " . $row['capacidad_total'] . "</h2>";
        } else {
            // Muestra un mensaje si no se encuentra la sala especificada.
            echo "No se encontró la sala especificada.";
        }

        // Cierra la declaración preparada.
        mysqli_stmt_close($stmtVerCapacidad);
        // Cierra la declaración preparada para la consulta de ID de sala.
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        // Muestra un mensaje de error si ocurre una excepción.
        echo "Error: " . $e->getMessage();
    }
}
?>