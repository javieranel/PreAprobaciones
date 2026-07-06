<?php

// Conexión a la base de datos
require_once "../connection/db_connection.php"; // Cambia esta ruta según tu configuración

if ($con->connect_error) {
    die("Conexión fallida: " . $con->connect_error);
}

// Obtenemos la fecha actual
$fecha_actual = date('Y-m-d');

// Lista de columnas que contienen fechas de vencimiento
$columnas_vencimiento = [
    'permiso_sne', 'exencion_acp', 'licencia_amp', 'patente_de_navegacion',
    'PYI', 'CLC', 'CLBC', 'COC', 'safety_equipment', 'safety_radio', 
    'safety_construction', 'loadline', 'SMC', 'DOC', 'ISPPC', 'IAPP', 'IOPP', 
    'issc', 'LOA', 'LBP', 'MAX_M', 'Displacement_MAX', 'cedula_vencimiento', 
    'CIA_AMP', 'CIA_CNA_1', 'CIA_CNA_2', 'CIA_SNE_1', 'CIA_SNE_2', 'CIA_POLIZA', 
    'REMOL_POLIZA', 'PILOT_LIC_OPERACION', 'PILOT_COMPETENCIA'
];

// Recorremos todas las filas de la base de datos
$sql = "SELECT id, " . implode(", ", $columnas_vencimiento) . " FROM documentos";
$resultado = $con->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        // Inicializamos el estado como Aprobado
        $estado_actual = "Aprobado";  // Se asume aprobado si todas las fechas son válidas

        // Recorremos las columnas de fechas para verificar su validez
        foreach ($columnas_vencimiento as $columna) {
            $fecha_vencimiento = $row[$columna];

            // Si la fecha es 0000-00-00, se ignora
            if ($fecha_vencimiento == '0000-00-00' || empty($fecha_vencimiento)) {
                continue;
            }

            // Verificamos si la fecha es válida (es decir, si no está vencida)
            if (strtotime($fecha_vencimiento) <= strtotime($fecha_actual)) {
                // Si alguna fecha está vencida, cambiamos el estado a Desaprobado
                $estado_actual = "Desaprobado";
                break;  // Detenemos el ciclo si ya encontramos una fecha vencida
            }
        }

        // Actualizamos el estado en la base de datos
        $update_sql = "UPDATE documentos SET status = ? WHERE id = ?";
        $stmt = $con->prepare($update_sql);
        $stmt->bind_param('si', $estado_actual, $row['id']);
        
        $stmt->execute();
        $stmt->close();
    }
} else {
    echo "No se encontraron registros en la base de datos.";
}

?>
