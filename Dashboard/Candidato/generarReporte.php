<?php
require('fpdf186/fpdf.php');
session_start();

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../assets/media/photos/logo.png', 10, 6, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Reporte de Postulaciones', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Datos ficticios
$nombreCandidato = 'Juan Pérez';
$totalPostulaciones = 10;
$enProceso = 3;
$aceptadas = 5;
$rechazadas = 2;

// Detalle de postulaciones ficticias
$postulaciones = [
    ['Empresa' => 'Empresa A', 'Plaza' => 'Desarrollador', 'Estado' => 'Aceptada', 'Fecha' => '2024-01-15'],
    ['Empresa' => 'Empresa B', 'Plaza' => 'Analista', 'Estado' => 'Rechazada', 'Fecha' => '2024-02-20'],
    ['Empresa' => 'Empresa C', 'Plaza' => 'Gerente', 'Estado' => 'En Proceso', 'Fecha' => '2024-03-05'],
    ['Empresa' => 'Empresa D', 'Plaza' => 'Desarrollador', 'Estado' => 'Aceptada', 'Fecha' => '2024-04-12'],
    ['Empresa' => 'Empresa E', 'Plaza' => 'Diseñador', 'Estado' => 'En Proceso', 'Fecha' => '2024-05-08'],
    ['Empresa' => 'Empresa F', 'Plaza' => 'Consultor', 'Estado' => 'Aceptada', 'Fecha' => '2024-06-18'],
    ['Empresa' => 'Empresa G', 'Plaza' => 'Ingeniero', 'Estado' => 'Rechazada', 'Fecha' => '2024-07-22'],
    ['Empresa' => 'Empresa H', 'Plaza' => 'Científico de Datos', 'Estado' => 'En Proceso', 'Fecha' => '2024-08-14'],
    ['Empresa' => 'Empresa I', 'Plaza' => 'Administrador', 'Estado' => 'Aceptada', 'Fecha' => '2024-09-03'],
    ['Empresa' => 'Empresa J', 'Plaza' => 'Especialista', 'Estado' => 'Aceptada', 'Fecha' => '2024-10-10'],
];

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

$pdf->Cell(0, 10, 'Información Básica del Reporte', 0, 1, 'L');
$pdf->Ln(5);
$pdf->Cell(0, 10, 'Candidato: ' . $nombreCandidato, 0, 1, 'L');
$pdf->Cell(0, 10, 'Fecha de Generación: ' . date('d-m-Y'), 0, 1, 'L');
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

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Reporte_Postulaciones_' . date('d_m_Y') . '.pdf"');

$pdf->Output('I');
?>
