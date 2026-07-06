<?php

// Conexión a la base de datos
require_once "../connection/db_connection.php"; // Cambia esta ruta según tu configuración

if ($con->connect_error) {
    die("Conexión fallida: " . $con->connect_error);
}

// Obtenemos la fecha actual y la fecha límite para "por vencer"
$fecha_actual = date('Y-m-d');
$fecha_limite = date('Y-m-d', strtotime('+60 days'));

// Lista de columnas que contienen fechas de vencimiento
$columnas_vencimiento = [
    'permiso_sne', 'exencion_acp', 'licencia_amp', 'patente_de_navegacion', 
    'ITC', 'PYI', 'CLC', 'CLBC', 'COC', 'safety_equipment', 'safety_radio', 
    'safety_construction', 'loadline', 'SMC', 'DOC', 'ISPPC', 'IAPP', 'IOPP', 
    'issc', 'LOA', 'LBP', 'MAX_M', 'Displacement_MAX', 'cedula_vencimiento', 
    'CIA_AMP', 'CIA_CNA_1', 'CIA_CNA_2', 'CIA_SNE_1', 'CIA_SNE_2', 'CIA_POLIZA', 
    'REMOL_POLIZA', 'PILOT_LIC_OPERACION', 'PILOT_COMPETENCIA'
];

// Consulta para obtener todos los registros relevantes
$sql = "SELECT id, nombre, nombre_empresa, categoria, " . implode(", ", $columnas_vencimiento) . " FROM documentos";
$resultado = $con->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    $vencidas = [];
    $por_vencer = [];

    while ($row = $resultado->fetch_assoc()) {
        foreach ($columnas_vencimiento as $columna) {
            $fecha_vencimiento = $row[$columna];

            // Ignoramos fechas vacías o inválidas
            if ($fecha_vencimiento == '0000-00-00' || empty($fecha_vencimiento)) {
                continue;
            }

            // Verificamos si la licencia está vencida
            if (strtotime($fecha_vencimiento) < strtotime($fecha_actual)) {
                $vencidas[] = [
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'empresa' => $row['nombre_empresa'],
                    'categoria' => $row['categoria'],
                    'documento' => $columna,
                    'fecha_vencimiento' => $fecha_vencimiento
                ];
            }
            // Verificamos si la licencia está por vencer (dentro de los próximos 60 días)
            elseif (strtotime($fecha_vencimiento) <= strtotime($fecha_limite)) {
                $por_vencer[] = [
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'empresa' => $row['nombre_empresa'],
                    'categoria' => $row['categoria'],
                    'documento' => $columna,
                    'fecha_vencimiento' => $fecha_vencimiento
                ];
            }
        }
    }

    // Incluir estilos de Bootstrap
    echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">';
    echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
    echo '<div class="container mt-4">';

    // Mostramos las licencias vencidas
    echo '<div class="container text-center mt-4">';
    echo '<button class="btn btn-secondary" onclick="window.history.back();"><i class="fas fa-arrow-left"></i> Volver</button>';
    echo '</div>';


    echo "<h2 class='text-danger'>Licencias Vencidas:</h2>";

    if (!empty($vencidas)) {
        echo "<table class='table table-bordered table-striped'>
                <thead class='table-danger'>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Empresa</th>
                        <th>Sección (Categoría)</th>
                        <th>Documento</th>
                        <th>Fecha de Vencimiento</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($vencidas as $licencia) {
            echo "<tr>
                    <td>{$licencia['id']}</td>
                    <td>{$licencia['nombre']}</td>
                    <td>{$licencia['empresa']}</td>
                    <td>{$licencia['categoria']}</td>
                    <td>{$licencia['documento']}</td>
                    <td>{$licencia['fecha_vencimiento']}</td>
                </tr>";
        }
        echo "</tbody>
              </table>";
    } else {
        echo "<p class='text-muted'>No hay licencias vencidas.</p>";
    }

    // Mostramos las licencias por vencer
    echo "<h2 class='text-warning'>Licencias por Vencer (próximos 60 días):</h2>";
    if (!empty($por_vencer)) {
        echo "<table class='table table-bordered table-striped'>
                <thead class='table-warning'>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Empresa</th>
                        <th>Sección (Categoría)</th>
                        <th>Documento</th>
                        <th>Fecha de Vencimiento</th>
                    </tr>
                </thead>
                <tbody>";
        foreach ($por_vencer as $licencia) {
            echo "<tr>
                    <td>{$licencia['id']}</td>
                    <td>{$licencia['nombre']}</td>
                    <td>{$licencia['empresa']}</td>
                    <td>{$licencia['categoria']}</td>
                    <td>{$licencia['documento']}</td>
                    <td>{$licencia['fecha_vencimiento']}</td>
                </tr>";
        }
        echo "</tbody>
              </table>";
    } else {
        echo "<p class='text-muted'>No hay licencias próximas a vencer.</p>";
    }

    echo '</div>';
    // Botón para volver atrás

} else {
    echo "<div class='alert alert-info'>No se encontraron registros en la base de datos.</div>";
}

?>
