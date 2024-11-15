<?php
session_start();

include '../db/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_empleado = trim($_POST['codigo_empleado']);
    $pwd = trim($_POST['pwd']);

    $_SESSION['codigo_empleado'] = $codigo_empleado;
    $_SESSION['pwd'] = $pwd;

    if (empty($codigo_empleado) || empty($pwd)) {
        $_SESSION['error'] = "Ambos campos son obligatorios.";
        header("Location: ../index.php");
        exit();
    }

    mysqli_begin_transaction($conn);

    $sql = "SELECT * FROM tbl_camarero WHERE codigo_camarero = ?";

    // Prepara la consulta SQL utilizando una declaración preparada para prevenir inyección SQL.
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Vincula la variable '$codigo_empleado' a la consulta preparada como un parámetro de tipo string (s).
        mysqli_stmt_bind_param($stmt, "s", $codigo_empleado);

        // Ejecuta la consulta SQL preparada.
        if (mysqli_stmt_execute($stmt)) {
            // Obtiene el resultado de la consulta ejecutada.
            $result = mysqli_stmt_get_result($stmt);

            // Comprueba si hay alguna fila en el resultado.
            if (mysqli_num_rows($result) > 0) {
                // Si hay una fila, convierte el resultado en un array asociativo.
                $usuario = mysqli_fetch_assoc($result);

                // Verifica si la contraseña proporcionada coincide con la contraseña almacenada en la base de datos.
                if (password_verify($pwd, $usuario['password_camarero'])) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['usuario_id'] = $usuario['id_camarero'];
                    $_SESSION['nombre_usuario'] = $usuario['nombre_camarero'];

                    unset($_SESSION['codigo_empleado']);
                    unset($_SESSION['pwd']);
                    unset($_SESSION['error']);

                    mysqli_commit($conn);

                    header("Location: ../public/dashboard.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Los datos introducidos son incorrectos.";
                    mysqli_rollback($conn);
                    header("Location: ../index.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Los datos introducidos son incorrectos.";
                mysqli_rollback($conn);
                header("Location: ../index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Error en la consulta: " . mysqli_error($conn);
            mysqli_rollback($conn);
            header("Location: ../index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Error en la consulta: " . mysqli_error($conn);
        mysqli_rollback($conn);
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>
