<?php
require_once "../../connection/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    // Validar y sanitizar los datos del formulario
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : null;
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : null;
    $nombre_empresa = isset($_POST["nombre_empresa"]) ? $_POST["nombre_empresa"] : null;
    $PILOT_LIC_OPERACION = isset($_POST["PILOT_LIC_OPERACION"]) ? $_POST["PILOT_LIC_OPERACION"] : null;
    $PILOT_COMPETENCIA = isset($_POST["PILOT_COMPETENCIA"]) ? $_POST["PILOT_COMPETENCIA"] : null;
    $PILOT_Reporte_maniobras = isset($_POST["PILOT_Reporte_maniobras"]) ? $_POST["PILOT_Reporte_maniobras"] : null;
    $PILOT_Hoja_Vida = isset($_POST["PILOT_Hoja_Vida"]) ? $_POST["PILOT_Hoja_Vida"] : null;
    $PILOT_Informe_Maniobras = isset($_POST["PILOT_Informe_Maniobras"]) ? $_POST["PILOT_Informe_Maniobras"] : null;

    // Validar que el ID no sea nulo
    if ($id !== null) {
        // Crear la consulta SQL con sentencias preparadas
        $stmt = $con->prepare("
            UPDATE documentos 
            SET nombre = ?, 
                nombre_empresa = ?, 
                PILOT_LIC_OPERACION = ?, 
                PILOT_COMPETENCIA = ?, 
                PILOT_Reporte_maniobras = ?, 
                PILOT_Hoja_Vida = ?, 
                PILOT_Informe_Maniobras = ?
            WHERE id = ?
        ");

        // Verificar si la consulta se preparó correctamente
        if ($stmt === false) {
            echo "Error en la consulta SQL: " . $con->error;
        } else {
            // Enlazar los parámetros a la consulta
            // Asegúrate de que el número de parámetros coincida con el tipo de la cadena de tipos.
            $stmt->bind_param(
                "sssssssi", // 7 strings y 1 entero
                $nombre, 
                $nombre_empresa, 
                $PILOT_LIC_OPERACION, 
                $PILOT_COMPETENCIA, 
                $PILOT_Reporte_maniobras, 
                $PILOT_Hoja_Vida, 
                $PILOT_Informe_Maniobras, 
                $id
            );
            

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "Datos del Pilotos actualizados con éxito.";
            } else {
                echo "Error al actualizar datos del Pilotos: " . $stmt->error;
            }

            // Cerrar la consulta
            $stmt->close();
        }
    } else {
        echo "ID no válido o no proporcionado.";
    }
} else {
    echo "Datos no proporcionados o método de solicitud incorrecto.";
}

// Cerrar la conexión a la base de datos
$con->close();
?>
