<?php
require_once "../connection/db_connection.php";
require_once "./tcpdf/tcpdf.php";

$seccion = isset($_GET['id']) ? $_GET['id'] : 'Barcazas';

/* Códigos por categoría */
$codigos = [
    'Barcazas'          => 'FOR-30-002',
    'Capitanes'         => 'FOR-30-004',
    'Inspectores'       => 'FOR-30-008',
    'Cia_Inspeccion'    => 'FOR-30-011',
    'Remolcadores'      => 'FOR-30-013',
    'Pilotos'           => 'FOR-30-014'
];

$codigoDocumento = isset($codigos[$seccion]) ? $codigos[$seccion] : 'N/A';
$fechaGeneracion = date('d/m/Y H:i:s');

$sql = "SELECT nombre, nombre_empresa, status
        FROM documentos
        WHERE categoria = ? AND status = 'Aprobado'
        ORDER BY nombre_empresa ASC";

$stmt = $con->prepare($sql);
$stmt->bind_param("s", $seccion);
$stmt->execute();
$result = $stmt->get_result();

/* Clase personalizada */
class MYPDF extends TCPDF {

    public $codigoDocumento;
    public $fechaGeneracion;

    // Encabezado
    public function Header() {

        // Logo
        $this->Image('../assets/image/logo_nombre.jpg', 10, 8, 35);

        // Código
        $this->SetFont('helvetica', 'B', 10);
        $this->SetXY(150, 10);
        $this->Cell(40, 5, 'Código: ' . $this->codigoDocumento, 0, 1, 'R');

        // Fecha
        $this->SetXY(150, 15);
        $this->Cell(40, 5, 'Fecha: ' . date('d/m/Y'), 0, 1, 'R');

        $this->Ln(15);
    }

    // Pie de página
    public function Footer() {

        $this->SetY(-15);

        $this->SetFont('helvetica', 'I', 8);

        $this->Cell(
            0,
            10,
            'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(),
            0,
            0,
            'C'
        );
    }
}

/* Crear PDF */
$pdf = new MYPDF();
$pdf->codigoDocumento = $codigoDocumento;
$pdf->fechaGeneracion = $fechaGeneracion;

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Reporte de ' . ucfirst($seccion));
$pdf->SetMargins(10, 30, 10);
$pdf->SetAutoPageBreak(TRUE, 20);
$pdf->AddPage();

/* Encabezado principal */
$html = '
<table border="1" cellpadding="4">
    <tr>
        <td width="70%">
            <h2 style="text-align:center;">LISTA DE ' . strtoupper($seccion) . ' APROBADOS</h2>
        </td>
        <td width="30%">
            <b>Código:</b> ' . $codigoDocumento . '<br>
            <b>Emitido:</b> ' . date('d/m/Y') . '
        </td>
    </tr>
</table>

<br><br>

<table border="1" cellpadding="5">
    <thead>
        <tr style="background-color:#30D5C8;">
            <th style="text-align:center;color:white;"><b>Nombre de ' . ucfirst($seccion) . '</b></th>
            <th style="text-align:center;color:white;"><b>Cliente</b></th>
            <th style="text-align:center;color:white;"><b>Status</b></th>
        </tr>
    </thead>
    <tbody>
';

while ($row = $result->fetch_assoc()) {

    $html .= '
    <tr>
        <td>' . htmlspecialchars($row['nombre']) . '</td>
        <td>' . htmlspecialchars($row['nombre_empresa']) . '</td>
        <td>' . htmlspecialchars($row['status']) . '</td>
    </tr>';
}

$html .= '
    </tbody>
</table>

<br><br>

<table border="0">
    <tr>
        <td align="right">
            <b>Fecha y hora de generación:</b> ' . $fechaGeneracion . '
        </td>
    </tr>
</table>
';

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Output(
    'reporte_' . strtolower(str_replace(' ', '_', $seccion)) . '_' . date('Ymd') . '.pdf',
    'D'
);
?>