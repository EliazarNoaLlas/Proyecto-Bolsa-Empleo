<?php
require('fpdf186/fpdf.php');
session_start();

include '../../BD/Conexion.php';
include_once '../../BD/Consultas.php';
include_once '../../main/funcionesApp.php';

$Conexion = new Consultas();
$FuncionesApp = new funcionesApp();

// Datos de la empresa
$IDUser = $_SESSION['iduser'];
$NombresUser = $_SESSION['nombre'];
$ApellidosUser = $_SESSION['apellido'];
$CorreoUser = $_SESSION['email'];
$CargoUser = $_SESSION['cargo'];
$FotoUser = $_SESSION['foto'];

$PrimerNombre = explode(" ", $NombresUser)[0];
$TipoReporte = $FuncionesApp->test_input($_POST['TipoReporte'] ?? null);
$fechaInicial = $FuncionesApp->test_input($_POST['FechaInicial'] ?? null);
$fechaFinal = $FuncionesApp->test_input($_POST['FechaFinal'] ?? null);

class PDF extends FPDF {
    protected $fotoEmpresa;

    function setFotoEmpresa($foto) {
        $this->fotoEmpresa = $foto;
    }

    function Header() {
        // Fondo del header
        $this->SetFillColor(255, 255, 255);
        $this->Rect(0, 0, 210, 40, 'F');

        // Logo de la empresa
        $this->Image('../../assets/img/user/img02.jpeg', 10, 10, 20);


        // Título del reporte
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(30, 64, 175);
        $this->SetXY(50, 15);
        $this->Cell(110, 10, 'Reporte Empresarial', 0, 1, 'C');

        // Línea decorativa
        $this->SetLineWidth(0.5);
        $this->SetDrawColor(59, 130, 246);
        $this->Line(10, 35, 200, 35);
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(102, 102, 102);
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function SectionTitle($title) {
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(30, 64, 175);
        $this->Cell(0, 10, utf8_decode($title), 0, 1, 'L');
        $this->SetLineWidth(0.2);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(5);
    }

    function MetricBox($title, $value, $x, $y, $width = 60) {
        $this->SetFillColor(247, 250, 252);
        $this->RoundedRect($x, $y, $width, 25, 3, 'F');

        $this->SetXY($x, $y + 2);
        $this->SetFont('Arial', '', 8);
        $this->SetTextColor(107, 114, 128);
        $this->Cell($width, 5, utf8_decode($title), 0, 1, 'C');

        $this->SetXY($x, $y + 8);
        $this->SetFont('Arial', 'B', 12);
        $this->SetTextColor(30, 64, 175);
        $this->Cell($width, 15, $value, 0, 1, 'C');
    }

    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k));
        $this->_out(sprintf('%.2F %.2F l',($x+$w-$r)*$k,($hp-$y)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x+$w)*$k,($hp-$y)*$k,
            ($x+$w)*$k,($hp-($y+$r))*$k,
            ($x+$w-$r)*$k,($hp-($y+$h))*$k));
        $this->_out(sprintf('%.2F %.2F l',($x+$r)*$k,($hp-($y+$h))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x*$k,($hp-($y+$h))*$k,
            $x*$k,($hp-($y+$h-$r))*$k,
            ($x+$r)*$k,($hp-($y+$h))*$k));
        $this->_out(sprintf('%.2F %.2F l',$x*$k,($hp-($y+$r))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x*$k,($hp-$y)*$k,
            ($x+$r)*$k,($hp-$y)*$k,
            ($x+$r)*$k,($hp-$y)*$k));
        if($style=='F')
            $this->_out('f');
        elseif($style=='FD' || $style=='DF')
            $this->_out('B');
        else
            $this->_out('S');
    }

    function DrawChart($data, $x, $y, $width, $height) {
        $maxValue = max(array_column($data, 'Contador'));
        $padding = 10;
        $barWidth = ($width - (count($data) + 1) * $padding) / count($data);

        // Dibujar eje X
        $this->Line($x, $y + $height, $x + $width, $y + $height);

        // Dibujar barras
        foreach ($data as $index => $item) {
            $barHeight = ($item['Contador'] / $maxValue) * $height;
            $barX = $x + $padding + ($barWidth + $padding) * $index;
            $barY = $y + $height - $barHeight;

            // Dibujar barra
            $this->SetFillColor(59, 130, 246);
            $this->Rect($barX, $barY, $barWidth, $barHeight, 'F');

            // Etiqueta
            $this->SetFont('Arial', '', 8);
            $this->SetXY($barX, $y + $height + 2);
            $this->Cell($barWidth, 5, $item['Tipo'], 0, 0, 'C');
        }
    }
}

// Consultas SQL con datos hardcodeados
$IDUser = 2; // Ejemplo de un ID de usuario fijo
$TipoReporte = 'Perfiles vistos'; // Tipo de reporte fijo
$fechaInicial = '2024-01-09'; // Fecha inicial fija
$fechaFinal = '2025-01-09'; // Fecha final fija

// Consultas SQL según filtros
if ($fechaInicial && $fechaFinal && $TipoReporte) {
    $sql = "SELECT `IDReporte`, `IDEmpresa`, `Tipo`, `contador` AS 'Contador', `fecha` AS 'Fecha'  
            FROM `reportes_generales` 
            WHERE `IDEmpresa` = ? AND `Tipo` = ? AND (`fecha` >= ? AND `fecha` <= ?) 
            ORDER BY `fecha` DESC";
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser, $TipoReporte, $fechaInicial, $fechaFinal));
} elseif ($TipoReporte) {
    $sql = "SELECT `IDReporte`, `IDEmpresa`, `Tipo`, `contador` AS 'Contador', `fecha` AS 'Fecha'  
            FROM `reportes_generales` 
            WHERE `IDEmpresa` = ? AND `Tipo` = ? 
            ORDER BY `fecha` DESC";
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser, $TipoReporte));
} else {
    $sql = "SELECT `IDReporte`, `IDEmpresa`, `Tipo`, `contador` AS 'Contador', `fecha` AS 'Fecha'  
            FROM `reportes_generales` 
            WHERE `IDEmpresa` = ? 
            ORDER BY `fecha` DESC";
    $stmt = Conexion::conectar()->prepare($sql);
    $stmt->execute(array($IDUser));
}

$reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear PDF
$pdf = new PDF();
$pdf->setFotoEmpresa($FotoUser);
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 15);

// Información de la Empresa
$pdf->SectionTitle(utf8_decode('Informacion de la Empresa'));
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(40, 7, 'Empresa:', 0);
$pdf->Cell(0, 7, utf8_decode($PrimerNombre), 0, 1);
$pdf->Cell(40, 7, 'Correo:', 0);
$pdf->Cell(0, 7, $CorreoUser, 0, 1);
if ($TipoReporte) {
    $pdf->Cell(40, 7, 'Tipo de Reporte:', 0);
    $pdf->Cell(0, 7, utf8_decode($TipoReporte), 0, 1);
}
$pdf->Cell(40, 7, utf8_decode('Período:'), 0);
$pdf->Cell(0, 7, ($fechaInicial && $fechaFinal) ? "Del $fechaInicial al $fechaFinal" : 'Completo', 0, 1);
$pdf->Ln(5);

// Métricas Generales
$pdf->SectionTitle('Métricas Generales');
$totalReportes = count($reportes);
$totalContador = array_sum(array_column($reportes, 'Contador'));
$promedioContador = $totalReportes > 0 ? round($totalContador / $totalReportes, 2) : 0;

// Mostrar métricas en cajas
$pdf->MetricBox('Total Reportes', $totalReportes, 10, $pdf->GetY(), 55);
$pdf->MetricBox('Total Registros', $totalContador, 75, $pdf->GetY(), 55);
$pdf->MetricBox('Promedio', $promedioContador, 140, $pdf->GetY(), 55);
$pdf->Ln(35);

// Gráfico de datos
if (!empty($reportes)) {
    $pdf->SectionTitle(utf8_decode('Análisis Gráfico'));
    $pdf->DrawChart(array_slice($reportes, 0, 6), 10, $pdf->GetY(), 180, 50);
    $pdf->Ln(70);
}

// Detalle de Reportes
$pdf->SectionTitle('Detalle de Reportes');
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(247, 250, 252);
$pdf->Cell(60, 8, 'Tipo de Seguimiento', 1, 0, 'C', true);
$pdf->Cell(40, 8, 'Contador', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Fecha', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
foreach ($reportes as $reporte) {
    $pdf->Cell(60, 8, utf8_decode($reporte['Tipo']), 1);
    $pdf->Cell(40, 8, $reporte['Contador'], 1, 0, 'C');
    $pdf->Cell(50, 8, date('d/m/Y', strtotime($reporte['Fecha'])), 1, 1);
}

// Generar PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Reporte_Empresa_' . date('d_m_Y') . '.pdf"');
$pdf->Output('I');
?>