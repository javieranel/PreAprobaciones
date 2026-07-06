<?php
require_once "../../connection/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    // Validar y sanitizar los datos del formulario
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : null;
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : null;
    $nombre_empresa = isset($_POST["nombre_empresa"]) ? $_POST["nombre_empresa"] : null;
    $COC = isset($_POST["COC"]) ? $_POST["COC"] : null;
    $PYI = isset($_POST["PYI"]) ? $_POST["PYI"] : null;
    $CLC = isset($_POST["CLC"]) ? $_POST["CLC"] : null;
    $CLBC = isset($_POST["CLBC"]) ? $_POST["CLBC"] : null;
    $safety_equipment = isset($_POST["safety_equipment"]) ? $_POST["safety_equipment"] : null;
    $safety_radio = isset($_POST["safety_radio"]) ? $_POST["safety_radio"] : null;
    $safety_construction = isset($_POST["safety_construction"]) ? $_POST["safety_construction"] : null;
    $loadline = isset($_POST["loadline"]) ? $_POST["loadline"] : null;
    $IOPP = isset($_POST["IOPP"]) ? $_POST["IOPP"] : null;
    $SMC = isset($_POST["SMC"]) ? $_POST["SMC"] : null;
    $DOC = isset($_POST["DOC"]) ? $_POST["DOC"] : null;
    $ISPPC = isset($_POST["ISPPC"]) ? $_POST["ISPPC"] : null;
    $issc = isset($_POST["issc"]) ? $_POST["issc"] : null;
    $IAPP = isset($_POST["IAPP"]) ? $_POST["IAPP"] : null;

    // Validar que el ID no sea nulo
    if ($id !== null) {
        // Crear la consulta SQL con sentencias preparadas
        $stmt = $con->prepare("
            UPDATE documentos 
            SET nombre = ?, 
                nombre_empresa = ?, 
                COC = ?, 
                PYI = ?, 
                CLC = ?, 
                CLBC = ?, 
                safety_equipment = ?, 
                safety_radio = ?, 
                safety_construction = ?, 
                loadline = ?, 
                IOPP = ?, 
                SMC = ?, 
                DOC = ?, 
                ISPPC = ?, 
                issc = ?, 
                IAPP = ?
            WHERE id = ?
        ");

        // Enlazar los parámetros a la consulta
        $stmt->bind_param(
            "ssssssssssssssssi", 
            $nombre, 
            $nombre_empresa, 
            $COC, 
            $PYI, 
            $CLC, 
            $CLBC, 
            $safety_equipment, 
            $safety_radio, 
            $safety_construction, 
            $loadline, 
            $IOPP, 
            $SMC, 
            $DOC, 
            $ISPPC, 
            $issc, 
            $IAPP, 
            $id
        );

        // Verificar los parámetros antes de ejecutar
        if ($stmt->execute()) {
            
            echo "Datos de la tanquero actualizados con éxito";
        } else {
            echo "Error al actualizar datos de la tanquero: " . $stmt->error;
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
