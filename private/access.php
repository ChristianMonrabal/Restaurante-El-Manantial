<?php
// Inicia una sesión o reanuda la existente.
session_start();

// Incluye el archivo de conexión a la base de datos.
include '../db/conexion.php';

// Verifica si la solicitud se hizo a través del método POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene el valor del campo 'codigo_empleado' del formulario y elimina espacios en blanco.
    $codigo_empleado = trim($_POST['codigo_empleado']);
    // Obtiene el valor del campo 'pwd' (contraseña) del formulario y elimina espacios en blanco.
    $pwd = trim($_POST['pwd']);

    // Almacena 'codigo_empleado' y 'pwd' en las variables de sesión.
    $_SESSION['codigo_empleado'] = $codigo_empleado;
    $_SESSION['pwd'] = $pwd;

    // Comprueba si alguno de los campos está vacío.
    if (empty($codigo_empleado) || empty($pwd)) {
        // Si alguno de los campos está vacío, crea un mensaje de error en la sesión.
        $_SESSION['error'] = "Ambos campos son obligatorios.";
        // Redirige al archivo 'index.php'.
        header("Location: ../index.php");
        // Finaliza la ejecución del script.
        exit();
    }

    // Define una consulta SQL para seleccionar un camarero por 'codigo_camarero'.
    $sql = "SELECT * FROM tbl_camarero WHERE codigo_camarero = ?";
    // Prepara la consulta SQL.
    $stmt = mysqli_prepare($conn, $sql);

    // Si la preparación de la consulta es exitosa.
    if ($stmt) {
        // Vincula la variable '$codigo_empleado' a la consulta preparada como un parámetro de tipo string (s).
        mysqli_stmt_bind_param($stmt, "s", $codigo_empleado);
        // Ejecuta la consulta SQL preparada.
        mysqli_stmt_execute($stmt);

        // Obtiene el resultado de la consulta ejecutada.
        $result = mysqli_stmt_get_result($stmt);

        // Comprueba si hay alguna fila en el resultado.
        if (mysqli_num_rows($result) > 0) {
            // Si hay una fila, convierte el resultado en un array asociativo.
            $usuario = mysqli_fetch_assoc($result);

            // Verifica si la contraseña proporcionada coincide con la contraseña almacenada en la base de datos.
            if (password_verify($pwd, $usuario['password_camarero'])) {
                // Si la contraseña es válida, establece las variables de sesión.
                $_SESSION['loggedin'] = true;
                $_SESSION['usuario_id'] = $usuario['id_camarero'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_camarero'];

                // Elimina las variables de sesión innecesarias.
                unset($_SESSION['codigo_empleado']);
                unset($_SESSION['pwd']);
                unset($_SESSION['error']);

                // Redirige al archivo 'dashboard.php'.
                header("Location: ../public/dashboard.php");
                // Finaliza la ejecución del script.
                exit();
            } else {
                // Si la contraseña no es válida, crea un mensaje de error en la sesión.
                $_SESSION['error'] = "Los datos introducidos son incorrectos.";
                // Redirige al archivo 'index.php'.
                header("Location: ../index.php");
                // Finaliza la ejecución del script.
                exit();
            }
        } else {
            // Si no hay filas en el resultado, crea un mensaje de error en la sesión.
            $_SESSION['error'] = "Los datos introducidos son incorrectos.";
            // Redirige al archivo 'index.php'.
            header("Location: ../index.php");
            // Finaliza la ejecución del script.
            exit();
        }
    } else {
        // Si la consulta falla, crea un mensaje de error en la sesión.
        $_SESSION['error'] = "Error en la consulta: " . mysqli_error($conn);
        // Redirige al archivo 'index.php'.
        header("Location: ../index.php");
        // Finaliza la ejecución del script.
        exit();
    }
} else {
    // Si la solicitud no se hizo a través del método POST, redirige al archivo 'login.php'.
    header("Location: ../index.php");
    // Finaliza la ejecución del script.
    exit();
}
?>