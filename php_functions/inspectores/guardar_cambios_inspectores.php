<?php
require_once "../../connection/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    // Obtener y validar los datos del formulario
    $id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;
    $inspector = isset($_POST["inspector"]) ? trim($_POST["inspector"]) : '';
    $nombre_empresa = isset($_POST["nombre_empresa"]) ? trim($_POST["nombre_empresa"]) : '';
    $numero_cedula = isset($_POST["numero_cedula"]) ? trim($_POST["numero_cedula"]) : '';
    $cedula_vencimiento = isset($_POST["cedula_vencimiento"]) ? trim($_POST["cedula_vencimiento"]) : '';
    $Inspector_P_A = isset($_POST["Inspector_P_A"]) ? trim($_POST["Inspector_P_A"]) : '';
    $Aviso_Entrada_Seguro = isset($_POST["Aviso_Entrada_Seguro"]) ? trim($_POST["Aviso_Entrada_Seguro"]) : '';

    if ($id > 0) {
        // Consulta SQL preparada
        $sql = "UPDATE documentos SET
                    nombre = ?,
                    nombre_empresa = ?,
                    cedula_vencimiento = ?,
                    numero_cedula = ?,
                    Inspector_P_A = ?,
                    Aviso_Entrada_Seguro = ?
                WHERE id = ?";

        // Preparar la consulta
        if ($stmt = $con->prepare($sql)) {
            // Vincular parámetros
            $stmt->bind_param(
                "ssssssi",
                $inspector,
                $nombre_empresa,
                $cedula_vencimiento,
                $numero_cedula,
                $Inspector_P_A,
                $Aviso_Entrada_Seguro,
                $id
            );

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "Datos de inspectores actualizados con éxito.";
            } else {
                echo "Error al ejecutar la consulta: " . $stmt->error;
            }

            // Cerrar el statement
            $stmt->close();
        } else {
            echo "Error al preparar la consulta: " . $con->error;
        }
    } else {
        echo "ID no válido.";
    }
} else {
    echo "Datos no proporcionados o método de solicitud incorrecto.";
}

// Cerrar la conexión
$con->close();
?>
