<?php
require_once "../../connection/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    // Validar y sanitizar los datos del formulario
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : null;
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : null;
    $nombre_empresa = isset($_POST["nombre_empresa"]) ? $_POST["nombre_empresa"] : null;
    $licencia_amp = isset($_POST["licencia_amp"]) ? $_POST["licencia_amp"] : null;
    $COC = isset($_POST["COC"]) ? $_POST["COC"] : null;
    $REMOL_POLIZA = isset($_POST["REMOL_POLIZA"]) ? $_POST["REMOL_POLIZA"] : null;

    // Validar que el ID no sea nulo
    if ($id !== null) {
        // Crear la consulta SQL con sentencias preparadas
        $stmt = $con->prepare("
            UPDATE documentos 
            SET nombre = ?, 
                nombre_empresa = ?, 
                licencia_amp = ?, 
                COC = ?, 
                REMOL_POLIZA = ?
            WHERE id = ?
        ");

        // Enlazar los parámetros a la consulta
        $stmt->bind_param(
            "sssssi", // Tipos de datos: "s" para strings, "i" para integers
            $nombre, 
            $nombre_empresa, 
            $licencia_amp, 
            $COC, 
            $REMOL_POLIZA, 
            $id
        );

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Datos del Remolcador actualizados con éxito.";
        } else {
            echo "Error al actualizar datos del Remolcador: " . $stmt->error;
        }

        // Cerrar la consulta
        $stmt->close();
    } else {
        echo "ID no válido o no proporcionado.";
    }
} else {
    echo "Datos no proporcionados o método de solicitud incorrecto.";
}

// Cerrar la conexión a la base de datos
$con->close();
?>
