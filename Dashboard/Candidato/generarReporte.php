<?php
require('fpdf186/fpdf.php');
session_start();

class PDF extends FPDF
{
    // Encabezado
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Reporte de Postulaciones', 0, 1, 'C');
        $this->Ln(10);
    }

    // Pie de página
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Datos ficticios
$nombreCandidato = 'Walter Stefano';
$totalPostulaciones = 10;
$enProceso = 3;
$aceptadas = 5;
$rechazadas = 2;

// Detalle de postulaciones ficticias
$postulaciones = [
    ['Empresa' => 'Smart Cities', 'Plaza' => 'Gerente de TI', 'Estado' => 'enviado', 'Fecha' => '2024-10-28 13:47:36'],
    ['Empresa' => 'SmartCities', 'Plaza' => 'Desarrollador Full Stack', 'Estado' => 'Aceptado', 'Fecha' => '2024-10-21 17:03:30'],
    ['Empresa' => 'SmartCities', 'Plaza' => 'Gerente de TI', 'Estado' => 'Aceptado', 'Fecha' => '2024-09-30 17:41:54'],
    ['Empresa' => 'SmartCities', 'Plaza' => 'Consultor SAP', 'Estado' => 'Aceptado', 'Fecha' => '2024-09-30 17:41:37'],
];

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(0, 10, 'Informacion Basica del Reporte', 0, 1, 'L');
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Candidato: ' . $nombreCandidato, 0, 1, 'L');
$pdf->Cell(0, 10, 'Fecha de Generacion: ' . date('d-m-Y'), 0, 1, 'L');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Resumen de Postulaciones', 0, 1, 'L');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Total de Postulaciones: ' . $totalPostulaciones, 0, 1, 'L');
$pdf->Cell(0, 10, 'En Proceso: ' . $enProceso, 0, 1, 'L');
$pdf->Cell(0, 10, 'Aceptadas: ' . $aceptadas, 0, 1, 'L');
$pdf->Cell(0, 10, 'Rechazadas: ' . $rechazadas, 0, 1, 'L');
$pdf->Ln(10);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Detalle de Postulaciones', 0, 1, 'L');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 10, 'Empresa', 1);
$pdf->Cell(50, 10, 'Plaza', 1);
$pdf->Cell(40, 10, 'Estado', 1);
$pdf->Cell(40, 10, 'Fecha', 1);
$pdf->Ln();

foreach ($postulaciones as $postulacion) {
    $pdf->Cell(60, 10, $postulacion['Empresa'], 1);
    $pdf->Cell(50, 10, $postulacion['Plaza'], 1);
    $pdf->Cell(40, 10, $postulacion['Estado'], 1);
    $pdf->Cell(40, 10, $postulacion['Fecha'], 1);
    $pdf->Ln();
}

// Configura los encabezados para la descarga del PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Reporte_Postulaciones_' . date('d_m_Y') . '.pdf"');

// Salida del archivo PDF
$pdf->Output('I');
?>
