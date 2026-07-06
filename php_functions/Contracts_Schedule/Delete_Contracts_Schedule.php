<?php
require_once "../../connection/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = isset($_POST["id"]) ? $_POST["id"] : null;

    if ($id !== null) {
        // Consulta SQL para eliminar la barcaza
        $sql = "DELETE FROM contracts_schedule WHERE id = ?";

        // Preparar la declaración
        $stmt = $con->prepare($sql);

        // Vincular parámetros
        $stmt->bind_param("i", $id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "contracts schedule eliminada con éxito";
        } else {
            echo "Error al eliminar contracts schedule: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "ID de contracts schedule no proporcionado";
    }
} else {
    echo "Datos no proporcionados o método de solicitud incorrecto";
}

// Cerrar la conexión
$con->close();
?>



