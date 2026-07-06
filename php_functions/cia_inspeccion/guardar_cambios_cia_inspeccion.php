<?php
require_once "../../connection/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    // Validar que las claves existen antes de usarlas
    $id = isset($_POST["id"]) ? $_POST["id"] : null;
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : null;
    $nombre_empresa = isset($_POST["nombre_empresa"]) ? $_POST["nombre_empresa"] : null;
    $CIA_AMP = isset($_POST["CIA_AMP"]) ? $_POST["CIA_AMP"] : null;
    $CIA_CNA_1 = isset($_POST["CIA_CNA_1"]) ? $_POST["CIA_CNA_1"] : null;
    $CIA_CNA_2 = isset($_POST["CIA_CNA_2"]) ? $_POST["CIA_CNA_2"] : null;
    $CIA_SNE_1 = isset($_POST["CIA_SNE_1"]) ? $_POST["CIA_SNE_1"] : null;
    $CIA_SNE_2 = isset($_POST["CIA_SNE_2"]) ? $_POST["CIA_SNE_2"] : null;
    $CIA_POLIZA = isset($_POST["CIA_POLIZA"]) ? $_POST["CIA_POLIZA"] : null;
    echo $CIA_SNE_2;
    // Construcción segura de la consulta SQL
    $sql = "UPDATE documentos SET
            nombre = '$nombre',
            nombre_empresa = '$nombre_empresa',
            CIA_AMP = '$CIA_AMP',
            CIA_CNA_1 = '$CIA_CNA_1',
            CIA_CNA_2 = '$CIA_CNA_2',
            CIA_SNE_1 = '$CIA_SNE_1',
            CIA_SNE_2 = '$CIA_SNE_2',
            CIA_POLIZA = '$CIA_POLIZA'
            WHERE id = $id";

    if ($con->query($sql) === TRUE) {
        echo "Datos de la CIA INSPECCION actualizados con éxito";
    } else {
        echo "Error al actualizar datos de la CIA INSPECCION: " . $con->error;
    }
} else {
    echo "Datos no proporcionados o método de solicitud incorrecto";
}

$con->close();
?>


