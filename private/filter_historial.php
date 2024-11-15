<?php
// Incluye el archivo de conexión a la base de datos.
include_once '../db/conexion.php';

// Inicializa un mensaje de "sin resultados".
$noResultsMessage = '';

// Inicializa un array para almacenar los filtros de búsqueda.
$filters = [];
// Inicializa un array para almacenar los valores de los parámetros.
$params = [];
// Inicializa una cadena para almacenar los tipos de datos de los parámetros.
$types = '';

// Inicia una transacción.
mysqli_begin_transaction($conn);

// Comprueba si el parámetro 'camarero' se ha proporcionado a través de la URL (método GET).
if (!empty($_GET['camarero'])) {
    // Agrega una condición de filtro para el nombre del camarero con un operador LIKE.
    $filters[] = 'c.nombre_camarero LIKE ?';
    // Agrega el valor correspondiente al array de parámetros, incluyendo los caracteres comodín (%).
    $params[] = '%' . $_GET['camarero'] . '%';
    // Agrega el tipo de dato 's' (string) a la cadena de tipos.
    $types .= 's';
}

if (!empty($_GET['mesa'])) {
    // Agrega una condición de filtro para el identificador de la mesa.
    $filters[] = 'm.id_mesa = ?';
    // Agrega el valor correspondiente al array de parámetros.
    $params[] = $_GET['mesa'];
    // Agrega el tipo de dato 'i' (entero) a la cadena de tipos.
    $types .= 'i';
}

if (!empty($_GET['fecha'])) {
    // Agrega una condición de filtro para la fecha de ocupación, comparando solo la parte de la fecha.
    $filters[] = 'DATE(o.fecha_hora_ocupacion) = ?';
    // Agrega el valor correspondiente al array de parámetros.
    $params[] = $_GET['fecha'];
    // Agrega el tipo de dato 's' (string) a la cadena de tipos.
    $types .= 's';
}

if (!empty($_GET['sala'])) {
    // Agrega una condición de filtro para el identificador de la sala.
    $filters[] = 's.id_sala = ?';
    // Agrega el valor correspondiente al array de parámetros.
    $params[] = $_GET['sala'];
    // Agrega el tipo de dato 'i' (entero) a la cadena de tipos.
    $types .= 'i';
}

if (!empty($filters)) {
    $query = "SELECT o.id_ocupacion, c.nombre_camarero, m.id_mesa, m.estado_mesa, s.nombre_sala, o.fecha_hora_ocupacion, o.fecha_hora_desocupacion
            FROM tbl_ocupacion o
            JOIN tbl_camarero c ON o.id_camarero = c.id_camarero
            JOIN tbl_mesa m ON o.id_mesa = m.id_mesa
            JOIN tbl_sala s ON m.id_sala = s.id_sala";

    // Agrega las condiciones de los filtros a la consulta.
    $query .= ' WHERE ' . implode(' AND ', $filters);

    // Prepara la consulta SQL con la conexión.
    $stmt = mysqli_prepare($conn, $query);

    // Si la preparación de la consulta es exitosa.
    if ($stmt) {
        // Si hay tipos definidos (significa que hay filtros), vincula los parámetros a la consulta preparada.
        if ($types) {
            // Vincula los parámetros utilizando los tipos y los valores del array de parámetros.
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }

        // Ejecuta la consulta preparada.
        mysqli_stmt_execute($stmt);
        // Obtiene el resultado de la consulta.
        $result = mysqli_stmt_get_result($stmt);

        // Si el método de la solicitud es GET y hay filtros, pero no se encontraron resultados.
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($filters) && mysqli_num_rows($result) === 0) {
            // Establece un mensaje de que no hay resultados para los filtros seleccionados.
            $noResultsMessage = "No hay resultados para los filtros seleccionados.";
        }
    } else {
        // Si la consulta falla, maneja el error.
        $noResultsMessage = "Error en la consulta: " . mysqli_error($conn);
        mysqli_rollback($conn); // Si ocurre un error, se revierte la transacción.
    }
} else {
    // Si no hay filtros, establece el resultado como nulo.
    $result = null;
}

// Si todo ha sido exitoso, confirma la transacción.
if ($noResultsMessage === '') {
    mysqli_commit($conn);
} else {
    // Si hay algún error, revierte la transacción.
    mysqli_rollback($conn);
}
?>
