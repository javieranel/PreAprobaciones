<?php
require_once "../../connection/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    // Validar las claves del formulario
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : null;
    $tank = isset($_POST["tank"]) ? $_POST["tank"] : null;
    $producto = isset($_POST["producto"]) ? $_POST["producto"] : null;
    $cliente = isset($_POST["cliente"]) ? $_POST["cliente"] : null;
    $volumen = isset($_POST["volumen"]) ? $_POST["volumen"] : null;
    $amendment = isset($_POST["amendment"]) ? $_POST["amendment"] : null;
    $expiration = isset($_POST["expiration"]) ? $_POST["expiration"] : null;
    $SNE = isset($_POST["SNE"]) ? $_POST["SNE"] : null;


    // Verificar que $id no sea nulo
    if ($id !== null) {
        // Consulta SQL preparada
        $stmt = $con->prepare("
            UPDATE contracts_schedule 
            SET tank = ?, 
                producto = ?, 
                cliente = ?, 
                volumen = ?, 
                amendment = ?, 
                expiration = ?, 
                SNE = ?
            WHERE id = ?
        ");

        // Enlazar los parámetros
        $stmt->bind_param(
            "sssssssi", 
            $tank, 
            $producto, 
            $cliente, 
            $volumen, 
            $amendment, 
            $expiration, 
            $SNE, 
            $id
        );

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Datos de la barcaza actualizados con éxito";
        } else {
            echo "Error al actualizar datos de la barcaza: " . $stmt->error;
        }

        $stmt->close(); // Cerrar la consulta preparada
    } else {
        echo "ID no válido o no proporcionado";
    }
} else {
    echo "Datos no proporcionados o método de solicitud incorrecto";
}

$con->close(); // Cerrar la conexión
?>
