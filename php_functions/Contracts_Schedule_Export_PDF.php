<?php

require_once "../connection/db_connection.php";
require_once "./tcpdf/tcpdf.php"; // Asegúrate de tener TCPDF

// Consulta para obtener los registros aprobados
$sql = "SELECT tank, producto, cliente, status 
        FROM contracts_schedule 
        WHERE status = 'Aprobado' 
        ORDER BY cliente ASC";


$stmt = $con->prepare($sql);

// Verifica si la consulta fue preparada correctamente
if (!$stmt) {
  die("Error al preparar la consulta SQL: " . $con->error);
}

$stmt->execute();
$result = $stmt->get_result();

$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Reporte de Contracts Schedule - ' . date("Y"));
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

// Agregar imagen en la parte superior
$pdf->Image('../assets/image/logo_nombre.jpg', 10, 10, 40); // Ajusta la ruta y tamaño según necesidad
$pdf->Ln(15); // Salto de línea para que el texto no se sobreponga

// Encabezado
$html = '<h2 style="text-align:center;">Reporte de Contracts Schedule - ' . date("Y") . '</h2>';
$html .= '<table border="1" cellpadding="5" cellspacing="0">
            <thead>
              <tr style="background-color:#30D5C8;">
                <th style="text-align:center; color:#ffffff;"><b>Tank</b></th>
                <th style="text-align:center; color:#ffffff;"><b>Producto</b></th>
                <th style="text-align:center; color:#ffffff;"><b>Cliente</b></th>
                <th style="text-align:center; color:#ffffff;"><b>Status</b></th>
              </tr>
            </thead>
            <tbody>';

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $html .= '<tr>
                              <td>' . htmlspecialchars($row['tank']) . '</td>
                              <td>' . htmlspecialchars($row['producto']) . '</td>
                              <td>' . htmlspecialchars($row['cliente']) . '</td>
                              <td>' . htmlspecialchars($row['status']) . '</td>
                            </tr>';
  }
} else {
  $html .= '<tr><td colspan="4">No se encontraron registros.</td></tr>';
}

$html .= '</tbody></table>';

$pdf->writeHTML($html, true, false, true, false, '');

// Asignar el nombre dinámico para el archivo PDF
$nombre = 'Reporte de Contracts Schedule - ' . date("Y");
$pdf->Output(strtolower($nombre) . '.pdf', 'D');


?>

