<?php
require_once "../../connection/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    // Validar las claves del formulario
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : null;
    $barcaza = isset($_POST["barcaza"]) ? $_POST["barcaza"] : null;
    $tipo_barcaza = isset($_POST["tipo_barcaza"]) ? $_POST["tipo_barcaza"] : null;
    $empresa_barcaza = isset($_POST["empresa_barcaza"]) ? $_POST["empresa_barcaza"] : null;
    $permiso_sne = isset($_POST["permiso_sne"]) ? $_POST["permiso_sne"] : null;
    $exencion_acp = isset($_POST["exencion_acp"]) ? $_POST["exencion_acp"] : null;
    $licencia_amp = isset($_POST["licencia_amp"]) ? $_POST["licencia_amp"] : null;
    $patente_navegacion = isset($_POST["patente_navegacion"]) ? $_POST["patente_navegacion"] : null;
    $ITC = isset($_POST["ITC"]) ? $_POST["ITC"] : null;
    $PYI = isset($_POST["PYI"]) ? $_POST["PYI"] : null;
    $CLC = isset($_POST["CLC"]) ? $_POST["CLC"] : null;
    $CLBC = isset($_POST["CLBC"]) ? $_POST["CLBC"] : null;
    $COC = isset($_POST["COC"]) ? $_POST["COC"] : null;
    $safety_equipment = isset($_POST["safety_equipment"]) ? $_POST["safety_equipment"] : null;
    $safety_radio = isset($_POST["safety_radio"]) ? $_POST["safety_radio"] : null;
    $safety_construction = isset($_POST["safety_construction"]) ? $_POST["safety_construction"] : null;
    $loadline = isset($_POST["loadline"]) ? $_POST["loadline"] : null;
    $SMC = isset($_POST["SMC"]) ? $_POST["SMC"] : null;
    $DOC = isset($_POST["DOC"]) ? $_POST["DOC"] : null;
    $ISPPC = isset($_POST["ISPPC"]) ? $_POST["ISPPC"] : null;
    $IAPP = isset($_POST["IAPP"]) ? $_POST["IAPP"] : null;
    $IOPP = isset($_POST["IOPP"]) ? $_POST["IOPP"] : null;

    // Verificar que $id no sea nulo
    if ($id !== null) {
        // Consulta SQL preparada
        $stmt = $con->prepare("
            UPDATE documentos 
            SET nombre = ?, 
                tipo_barcaza = ?, 
                nombre_empresa = ?, 
                permiso_SNE = ?, 
                exencion_ACP = ?, 
                licencia_amp = ?, 
                patente_de_navegacion = ?, 
                ITC = ?, 
                PYI = ?, 
                CLC = ?, 
                CLBC = ?, 
                COC = ?, 
                safety_equipment = ?, 
                safety_radio = ?, 
                safety_construction = ?, 
                loadline = ?, 
                SMC = ?, 
                DOC = ?, 
                ISPPC = ?, 
                IAPP = ?, 
                IOPP = ? 
            WHERE id = ?
        ");

        // Enlazar los parámetros
        $stmt->bind_param(
            "sssssssssssssssssssssi", 
            $barcaza, 
            $tipo_barcaza, 
            $empresa_barcaza, 
            $permiso_sne, 
            $exencion_acp, 
            $licencia_amp, 
            $patente_navegacion, 
            $ITC, 
            $PYI, 
            $CLC, 
            $CLBC, 
            $COC, 
            $safety_equipment, 
            $safety_radio, 
            $safety_construction, 
            $loadline, 
            $SMC, 
            $DOC, 
            $ISPPC, 
            $IAPP, 
            $IOPP, 
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
