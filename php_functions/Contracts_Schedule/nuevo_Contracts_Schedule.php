<?php
require_once "../../connection/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $data = json_decode(file_get_contents("php://input"), true);
    
    $tank = $data["tank"];
    $producto = $data["producto"];
    $cliente = $data["cliente"];

    // Consulta SQL preparada para la barcaza
    $sql_barcaza = "INSERT INTO contracts_schedule (tank, producto, cliente) VALUES (?, ?, ?)";
    $stmt_barcaza = $con->prepare($sql_barcaza);
    $stmt_barcaza->bind_param("sss", $tank, $producto, $cliente);

    if ($stmt_barcaza->execute()) {
        // Mensaje para la notificación
        $mensaje = "Se ha agregado un Contracts : $tank de la empresa $cliente.";

        // Insertar notificación
        $sql_notificacion = "INSERT INTO notificaciones (mensaje) VALUES (?)";
        $stmt_notificacion = $con->prepare($sql_notificacion);
        $stmt_notificacion->bind_param("s", $mensaje);

        if ($stmt_notificacion->execute()) {
            echo "Nuevo Contracts agregada con éxito y notificación creada.";
        } else {
            echo "Error al crear la notificación: " . $stmt_notificacion->error;
        }

        $stmt_notificacion->close();
    } else {
        echo "Error al agregar un Contracts: " . $stmt_barcaza->error;
    }

    $stmt_barcaza->close();
} else {
    echo "Datos no proporcionados o método de solicitud incorrecto";
}

// Cerrar la conexión
$con->close();
?>
