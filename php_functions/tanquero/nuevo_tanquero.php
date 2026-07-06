<?php
require_once "../../connection/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $data = json_decode(file_get_contents("php://input"), true);
    
    $nombre = $data["nombre"];
    $empresa = $data["empresa"];
    $categoria = $data["categoria"];

    // Consulta SQL preparada para insertar en la tabla documentos
    $sql = "INSERT INTO documentos (nombre, nombre_empresa, categoria) VALUES (?, ?, ?)";

    // Preparar la declaración
    $stmt = $con->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("sss", $nombre, $empresa, $categoria);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Mensaje para la notificación
        $mensaje = "Nuevo Tanquero agregado: $nombre, Empresa: $empresa, Categoría: $categoria.";

        // Consulta para insertar la notificación
        $sql_notificacion = "INSERT INTO notificaciones (mensaje) VALUES (?)";
        $stmt_notificacion = $con->prepare($sql_notificacion);
        $stmt_notificacion->bind_param("s", $mensaje);

        if ($stmt_notificacion->execute()) {
            echo "Nuevo Tanquero agregado con éxito y notificación creada.";
        } else {
            echo "Error al crear la notificación: " . $stmt_notificacion->error;
        }

        // Cerrar la declaración de notificación
        $stmt_notificacion->close();
    } else {
        echo "Error al agregar el nuevo Tanquero: " . $stmt->error;
    }

    // Cerrar la declaración principal
    $stmt->close();
} else {
    echo "Datos no proporcionados o método de solicitud incorrecto";
}

// Cerrar la conexión
$con->close();
?>
